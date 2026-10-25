<?php require_once 'konfigurasi/url.php'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPDB SMK Kota Padang 2025</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: #f4f6f9;
            overflow-x: hidden;
        }

        .container {
            width: 90%;
            max-width: 1100px;
            margin: auto;
        }

        /* HEADER RESPONSIVE */
        header {
            background: white;
            padding: 15px 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #006400;
            font-weight: bold;
            font-size: 20px;
        }

        .logo img {
            height: 45px;
        }

        .nav {
            display: flex;
            align-items: center;
        }

        .nav a {
            margin-left: 15px;
            text-decoration: none;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }

        .btn-daftar {
            background: #006400;
            color: white !important;
            padding: 8px 18px;
            border-radius: 20px;
            transition: 0.3s;
        }

        .btn-daftar:hover {
            background: #004d00;
        }

        /* HERO DENGAN TINGGI DINAMIS */
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('<?= BASE_URL ?>aset/img/smk-bg.jpeg') no-repeat center center;
            background-size: cover;
            min-height: 450px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 40px 20px;
        }

        .hero-content {
            color: white;
            width: 100%;
            max-width: 800px;
        }

        .hero h2 {
            font-size: clamp(24px, 5vw, 42px);
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .hero p {
            font-size: clamp(14px, 2vw, 18px);
        }

        .btn-cta {
            background: #ffd700;
            color: black;
            padding: 15px 35px;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            font-size: 18px;
            display: inline-block;
            margin-top: 25px;
            transition: 0.3s;
        }

        .btn-cta:hover {
            background: #ffc107;
            transform: translateY(-3px);
        }

        /* INFO CARDS MENGGUNAKAN GRID */
        .info-section {
            padding: 40px 0;
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-top: -80px;
            position: relative;
            z-index: 2;
        }

        .info-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-top: 5px solid #006400;
            transition: 0.3s;
        }

        .info-card:hover {
            transform: translateY(-5px);
        }

        .info-card h3 {
            color: #006400;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 0;
            font-size: 20px;
        }

        .info-card p {
            color: #555;
            line-height: 1.6;
            font-size: 15px;
        }

        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 25px;
            margin-top: 60px;
        }

        /* MEDIA QUERIES UNTUK HP */
        @media (max-width: 768px) {
            header .container {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .logo {
                justify-content: center;
            }

            .nav {
                justify-content: center;
                flex-wrap: wrap;
                gap: 10px;
            }

            .nav a {
                margin: 5px;
                font-size: 13px;
            }

            .card-grid {
                margin-top: 30px;
                padding: 0 10px;
            }

            .hero {
                min-height: 350px;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="container">
            <div class="logo">
                <img src="<?= BASE_URL ?>aset/img/logo-smk.png" alt="Logo">
                PPDB SMK
            </div>
            <nav class="nav">
                <a href="index.php">Beranda</a>
                <a href="peta.php">Peta Sebaran</a>
                <a href="akses/login.php">Login</a>
                <a href="akses/daftar_siswa.php" class="btn-daftar">Daftar</a>
            </nav>
        </div>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h2>PPDB SMK KOTA PADANG 2025</h2>
            <p>Siapkan Masa Depan Gemilang Bersama Sekolah Menengah Kejuruan</p>
            <a href="akses/daftar_siswa.php" class="btn-cta">DAFTAR SEKARANG</a>
        </div>
    </section>

    <section class="info-section container">
        <div class="card-grid">
            <div class="info-card">
                <h3><i class="fas fa-route"></i> Jalur Masuk</h3>
                <p>Tersedia Jalur Reguler, Afirmasi, dan Prestasi untuk lulusan SMP sederajat.</p>
            </div>
            <div class="info-card">
                <h3><i class="fas fa-calendar"></i> Jadwal</h3>
                <p>Pendaftaran resmi dibuka mulai tanggal <strong>23 Juni 2025</strong> melalui sistem online.</p>
            </div>
            <div class="info-card">
                <h3><i class="fas fa-map-marker-alt"></i> Lokasi</h3>
                <p>Cek peta sebaran SMK Negeri di Kota Padang untuk menentukan pilihan sekolah terdekat.</p>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 Dinas Pendidikan Prov. Sumatera Barat | PPDB SMK Padang</p>
    </footer>
</body>

</html>