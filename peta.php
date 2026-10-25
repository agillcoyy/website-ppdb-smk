<?php 
require_once 'konfigurasi/url.php'; 
require_once 'konfigurasi/koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Sebaran SMK - PPDB SMK Kota Padang</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { margin:0; padding:0; font-family: 'Poppins', sans-serif; background: #f4f6f9; }
        
        /* NAVBAR RESPONSIVE */
        header { background: white; padding: 15px 0; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        header .container { 
            width: 90%; 
            max-width: 1200px; 
            margin: auto; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            flex-wrap: wrap;
        }
        .logo { display: flex; align-items: center; gap: 10px; color: #006400; font-weight: bold; font-size: 20px; text-decoration: none; }
        .logo img { height: 40px; }
        .nav a { margin-left: 15px; text-decoration: none; color: #333; font-weight: 500; font-size: 14px; }

        /* MAIN LAYOUT PETA */
        .peta-wrapper {
            display: flex;
            height: calc(100vh - 80px); 
            width: 100%;
        }

        #map { flex: 3; height: 100%; z-index: 1; background: #e5e3df; } /* Tambah bg color agar terlihat jika map load */

        .info-sidebar { 
            flex: 1; 
            background: white; 
            padding: 20px; 
            overflow-y: auto; 
            box-shadow: -2px 0 5px rgba(0,0,0,0.1);
            z-index: 2;
        }

        .info-sidebar h2 { color: #006400; font-size: 20px; margin-top: 0; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        
        .sekolah-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: 0.3s;
        }
        .sekolah-item:hover { background: #f0fdf4; }
        .sekolah-item h4 { margin: 0 0 5px 0; color: #333; }
        .sekolah-item p { margin: 0; font-size: 13px; color: #666; line-height: 1.4; }

        /* RESPONSIVE PETA (MOBILE) */
        @media (max-width: 768px) {
            header .container { flex-direction: column; gap: 10px; text-align: center; }
            .nav { margin-top: 10px; }
            .nav a { margin: 0 8px; }

            .peta-wrapper {
                flex-direction: column;
                height: auto;
            }

            #map {
                height: 400px;
                flex: none;
                width: 100%;
            }

            .info-sidebar {
                flex: none;
                width: 100%;
                height: 300px;
                box-sizing: border-box;
            }
        }
    </style>
</head>
<body>

    <header>
        <div class="container">
            <a href="index.php" class="logo">
                <img src="<?= BASE_URL ?>aset/img/logo-smk.png" alt="Logo">
                PPDB SMK
            </a>
            <nav class="nav">
                <a href="index.php">Beranda</a>
                <a href="akses/login.php">Login</a>
                <a href="akses/daftar_siswa.php">Daftar</a>
            </nav>
        </div>
    </header>

    <div class="peta-wrapper">
        <div id="map"></div>

        <div class="info-sidebar">
            <h2>Daftar SMK Negeri</h2>
            <div id="daftar-sekolah">
                <?php
                $query = mysqli_query($koneksi, "SELECT * FROM sekolah ORDER BY nama_sekolah ASC");
                if (mysqli_num_rows($query) > 0) {
                    while($d = mysqli_fetch_array($query)) {
                ?>
                <div class="sekolah-item" onclick="zoomToMarker(<?= $d['lat'] ?>, <?= $d['lng'] ?>)">
                    <h4><?= htmlspecialchars($d['nama_sekolah']) ?></h4>
                    <p><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($d['alamat']) ?></p>
                </div>
                <?php 
                    }
                } else {
                    echo "<p style='padding:15px; color:red;'>Data sekolah tidak ditemukan di database.</p>";
                }
                ?>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Inisialisasi Peta Kota Padang
        var map = L.map('map').setView([-0.947, 100.417], 12);

        // Load Tile Layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        <?php
        // Query ulang untuk marker
        $query_marker = mysqli_query($koneksi, "SELECT * FROM sekolah");
        while($row = mysqli_fetch_array($query_marker)) {
            // Gunakan $row['id'] sesuai dengan database sig.sql
            $nama = addslashes($row['nama_sekolah']);
            $alamat = addslashes($row['alamat']);
            $id = $row['id']; 
            
            echo "L.marker([{$row['lat']}, {$row['lng']}])
                .addTo(map)
                .bindPopup('<b>$nama</b><br>$alamat<br><br><a href=\"" . BASE_URL . "admin_sekolah/detail_siswa.php?id=$id\" style=\"color:#006400; font-weight:bold; text-decoration:none;\">Lihat Detail</a>');\n";
        }
        ?>

        function zoomToMarker(lat, lng) {
            map.flyTo([lat, lng], 16);
            if(window.innerWidth <= 768) {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }

        // Fix untuk memastikan map ter-render dengan benar setelah load halaman
        setTimeout(function(){ map.invalidateSize();}, 400);
    </script>
</body>
</html>