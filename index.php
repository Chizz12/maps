<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maps</title>

    <!-- leaflet css  -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />

    <!-- bootstrap cdn  -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <style>
        /* ukuran peta */
        #mapid {
            height: 100%;
        }

        .jumbotron {
            height: 100%;
            border-radius: 0;
        }

        body {
            background-color: #ebe7e1;
        }
    </style>
</head>

<body>
    <div class="row"> <!-- class row digunakan sebelum membuat column  -->
        <div class="col-4"> <!-- ukuruan layar dengan bootstrap adalah 12 kolom, bagian kiri dibuat sebesar 4 kolom-->
            <div class="jumbotron"> <!-- untuk membuat semacam container berwarna abu -->
                <h1>Add Location</h1>
                <hr>
                <form action="proses.php" method="post">
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Latitude, Longitude</label>
                        <input type="text" class="form-control" id="latlong" name="latlong">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Nama Tempat</label>
                        <input type="text" class="form-control" name="nama_tempat">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Kategori Tempat</label>
                        <select class="form-control" name="kategori" id="">
                            <option value="">--Kategori Tempat--</option>
                            <option value="rumah makan">Rumah Makan</option>
                            <option value="pom bensin">Pom Bensin</option>
                            <option value="Fasilitas Umum">Fasilitas Umum</option>
                            <option value="Pasar/Mall">Pasar/Mall</option>
                            <option value="rumah sakit">Rumah Sakit</option>
                            <option value="Sekolah">Sekolah</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Keterangan</label>
                        <textarea class="form-control" name="keterangan" cols="30" rows="5"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-info">Add</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-8"> <!-- ukuruan layar dengan bootstrap adalah 12 kolom, bagian kiri dibuat sebesar 4 kolom-->
            <!-- peta akan ditampilkan dengan id = mapid -->
            <div id="mapid"></div>
        </div>
    </div>



    <!-- leaflet js  -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>

    <script>
        // Mendefinisikan layer group untuk setiap kategori
        var layerGroups = {
            'rumah makan': L.layerGroup(),
            'pom bensin': L.layerGroup(),
            'Fasilitas Umum': L.layerGroup(),
            'Pasar/Mall': L.layerGroup(),
            'rumah sakit': L.layerGroup(),
            'Sekolah': L.layerGroup()
        };
        // mendefinisikan icon dari asset yang ada untuk setiap kategori
        var iconUrls = {
            'rumah makan': 'img/cyan.png',
            'pom bensin': 'img/red.png',
            'Fasilitas Umum': 'img/indigo.png',
            'Pasar/Mall': 'img/kuning.png',
            'rumah sakit': 'img/hijau.png',
            'Sekolah': 'img/biru.png'
        };

        // membuat custom icon untuk setiap kategori
        var customIcons = {};
        for (var category in iconUrls) {
            customIcons[category] = L.icon({
                iconUrl: iconUrls[category],
                iconSize: [50, 35], // ubah ukuran icon jika diperlukan
                iconAnchor: [16, 32], // ukuran anchor point
                popupAnchor: [0, -32] // popup Anchor (optional)
            });
        }
        // set lokasi latitude dan longitude, lokasinya kota palembang 
        var mymap = L.map('mapid').setView([-7.330715, 108.34267], 15);
        //setting maps menggunakan api mapbox bukan google maps, daftar dan dapatkan token      
        L.tileLayer(
            'https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                maxZoom: 20,
                id: 'mapbox/streets-v11', //menggunakan peta model streets-v11 kalian bisa melihat jenis-jenis peta lainnnya di web resmi mapbox
                tileSize: 512,
                zoomOffset: -1,
                accessToken: 'your.mapbox.access.token'
            }).addTo(mymap);


        // buat variabel berisi fugnsi L.popup 
        var popup = L.popup();

        // buat fungsi popup saat map diklik
        function onMapClick(e) {
            popup
                .setLatLng(e.latlng)
                .setContent("koordinatnya adalah " + e.latlng
                    .toString()
                ) //set isi konten yang ingin ditampilkan, kali ini kita akan menampilkan latitude dan longitude
                .openOn(mymap);

            document.getElementById('latlong').value = e.latlng //value pada form latitde, longitude akan berganti secara otomatis
        }
        mymap.on('click', onMapClick); //jalankan fungsi



        <?php
        $mysqli = mysqli_connect('localhost', 'root', '', 'latihan');
        $tampil = mysqli_query($mysqli, "select * from lokasi");
        while ($hasil = mysqli_fetch_array($tampil)) {
            // Extract the latitude and longitude from the string
            $latLngArray = explode(',', str_replace(['[', ']', 'LatLng', '(', ')'], '', $hasil['lat_long']));
            $latitude = trim($latLngArray[0]);
            $longitude = trim($latLngArray[1]);

            // gunakan variabel $category untuk menampung kategori dari database
            $category = $hasil['kategori'];
        ?>

            // tambahkan marker untuk setiap lokasi
            var marker = L.marker([<?php echo $latitude; ?>, <?php echo $longitude; ?>], {
                icon: customIcons['<?php echo $category; ?>']
            }).bindPopup(`<?php echo 'nama tempat: ' . $hasil['nama_tempat'] . '|kategori: ' . $hasil['kategori'] . '|keterangan: ' . $hasil['keterangan']; ?>`);

            layerGroups['<?php echo $category; ?>'].addLayer(marker);

        <?php } ?>

        // tambahkan layer group ke dalam map
        for (var category in layerGroups) {
            layerGroups[category].addTo(mymap);
        }

        // menambahkan control layer ke dalam map
        L.control.layers(null, layerGroups, {
            collapsed: false
        }).addTo(mymap);
    </script>
</body>

</html>