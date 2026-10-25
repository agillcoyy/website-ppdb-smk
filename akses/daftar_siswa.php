<?php
require_once '../konfigurasi/url.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Siswa Baru - PPDB SMK</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            margin: 0; padding: 0; font-family: 'Poppins', sans-serif;
            /* BACKGROUND GAMBAR SAMA SEPERTI LOGIN & HOME */
            background: url('<?= BASE_URL ?>aset/img/bg-smk.jpeg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
        }
        
        /* OVERLAY HIJAU TRANSPARAN */
        .overlay {
            position: fixed; top:0; left:0; width:100%; height:100%;
            background: rgba(0, 100, 0, 0.6); 
            z-index: 1;
        }
        
        /* KOTAK FORM */
        .register-box {
            position: relative; z-index: 2;
            background: white; padding: 40px; border-radius: 12px;
            width: 90%; max-width: 480px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            text-align: center;
            margin: 30px 0; /* Jarak atas bawah agar tidak mentok di layar kecil */
        }

        .register-header img { width: 60px; margin-bottom: 10px; }
        .register-header h2 { color: #006400; margin: 0; font-weight: 700; font-size: 24px; }
        .register-header p { color: #666; font-size: 14px; margin-top: 5px; margin-bottom: 25px;}
        
        .form-group { margin-bottom: 15px; text-align: left; }
        .form-group label { display: block; font-weight: 600; color: #333; margin-bottom: 8px; font-size: 13px; }
        
        .form-control { 
            width: 100%; padding: 12px; 
            border: 1px solid #ccc; border-radius: 6px; 
            box-sizing: border-box; font-family: 'Poppins', sans-serif;
            font-size: 14px; transition: 0.3s;
        }
        .form-control:focus { border-color: #006400; outline: none; box-shadow: 0 0 5px rgba(0,100,0,0.2); }
        
        /* TOMBOL DAFTAR HIJAU */
        .btn-register {
            width: 100%; padding: 12px;
            background: linear-gradient(45deg, #006400, #008000); /* Gradasi Hijau */
            color: white; border: none; border-radius: 6px;
            font-weight: bold; font-size: 16px; cursor: pointer;
            transition: 0.3s; margin-top: 15px;
            box-shadow: 0 4px 10px rgba(0,100,0,0.2);
        }
        .btn-register:hover { background: linear-gradient(45deg, #004d00, #006400); transform: translateY(-2px); }
        
        .footer-link { margin-top: 25px; font-size: 13px; color: #555; }
        .footer-link a { color: #006400; text-decoration: none; font-weight: bold; transition:0.2s; }
        .footer-link a:hover { text-decoration: underline; }

        /* ALERT ERROR STYLE */
        .alert-error {
            background: #ffebee; color: #c62828; padding: 12px; 
            border-radius: 6px; font-size: 13px; margin-bottom: 20px;
            border-left: 5px solid #d32f2f; text-align: left;
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    
    <div class="register-box">
        <div class="register-header">
            <img src="<?= BASE_URL ?>aset/img/logo-smk.png" alt="Logo">
            <h2>Pendaftaran Akun</h2>
            <p>Isi data diri Anda untuk membuat akun siswa baru</p>
        </div>

        <?php 
        if(isset($_GET['pesan'])){
            if($_GET['pesan'] == "gagal"){
                echo "<div class='alert-error'><i class='fas fa-exclamation-circle'></i> <strong>Gagal!</strong> NISN tersebut mungkin sudah terdaftar.</div>";
            } else if($_GET['pesan'] == "beda_password"){
                echo "<div class='alert-error'><i class='fas fa-times-circle'></i> <strong>Error!</strong> Konfirmasi password tidak cocok.</div>";
            }
        }
        ?>

        <form action="../proses/simpan_siswa.php" method="POST">
            <div class="form-group">
                <label>NISN (Nomor Induk Siswa Nasional)</label>
                <input type="text" name="nisn" class="form-control" required placeholder="Contoh: 0081234567" maxlength="20">
            </div>

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-control" required placeholder="Sesuai Ijazah / Rapor">
            </div>
            
            <div class="form-group">
                <label>Asal Sekolah (SMP/MTs)</label>
                <input type="text" name="asal_sekolah" class="form-control" required placeholder="Nama Sekolah Asal">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required placeholder="Buat password yang aman">
            </div>

            <div class="form-group">
                <label>Ulangi Password</label>
                <input type="password" name="konfirmasi_password" class="form-control" required placeholder="Ketik ulang password">
            </div>

            <button type="submit" class="btn-register">BUAT AKUN SISWA</button>
        </form>

        <div class="footer-link">
            Sudah punya akun? <a href="login.php">Login Disini</a>
            <br><br>
            <a href="../index.php" style="color:#888; font-weight:normal;">&larr; Kembali ke Halaman Utama</a>
        </div>
    </div>
</body>
</html>