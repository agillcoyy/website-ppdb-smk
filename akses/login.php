<?php
session_start();
require_once '../konfigurasi/url.php';

// Redirect jika sudah login
if(isset($_SESSION['status']) && $_SESSION['status'] == "login"){
    if($_SESSION['role'] == 'siswa') header("location:../siswa/dashboard.php");
    else if($_SESSION['role'] == 'sekolah') header("location:../admin_sekolah/dashboard.php");
    else if($_SESSION['role'] == 'dinas') header("location:../admin_dinas/dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - PPDB Online</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            margin: 0; padding: 0; font-family: 'Segoe UI', sans-serif;
            background: url('<?= BASE_URL ?>aset/img/bg-smk.jpeg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex; align-items: center; justify-content: center;
        }
        .overlay {
            position: absolute; top:0; left:0; width:100%; height:100%;
            background: rgba(0, 50, 0, 0.7);
        }
        .login-box {
            position: relative; z-index: 2;
            background: #fff; padding: 40px; border-radius: 8px;
            width: 100%; max-width: 380px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            text-align: center;
        }
        .login-header img { width: 70px; margin-bottom: 15px; }
        .login-header h3 { color: #333; margin: 0; font-weight: 700; font-size: 22px; }
        .login-header p { color: #777; font-size: 14px; margin-top: 5px; margin-bottom: 30px; }
        
        .form-group { margin-bottom: 20px; text-align: left; position: relative; }
        .form-group label { 
            display: block; font-weight: 600; color: #444; margin-bottom: 8px; font-size: 13px; 
        }
        .form-control { 
            width: 100%; padding: 12px 12px 12px 40px; 
            border: 1px solid #ddd; border-radius: 4px; 
            box-sizing: border-box; font-size: 14px; color: #333;
        }
        .form-control:focus { border-color: #006400; outline: none; }
        
        .icon-input {
            position: absolute; top: 38px; left: 12px; color: #999; font-size: 14px;
        }

        .btn-login {
            width: 100%; padding: 12px;
            background: #006400; color: white; 
            border: none; border-radius: 4px;
            font-weight: 600; font-size: 15px; cursor: pointer;
            transition: 0.3s; margin-top: 10px;
            text-transform: uppercase;
        }
        .btn-login:hover { background: #004d00; }
        
        .alert { 
            padding: 12px; border-radius: 4px; margin-bottom: 20px; 
            font-size: 13px; text-align: left; display: flex; align-items: center; gap: 10px;
        }
        .alert-danger { background: #ffebee; color: #c62828; border: 1px solid #ef9a9a; }
        
        .footer-link { margin-top: 25px; font-size: 13px; color: #666; }
        .footer-link a { color: #006400; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="login-box">
        <div class="login-header">
            <img src="<?= BASE_URL ?>aset/img/logo-smk.png" alt="Logo">
            <h3>LOGIN APLIKASI</h3>
            <p>Sistem Penerimaan Peserta Didik Baru</p>
        </div>

        <?php if(isset($_GET['pesan'])): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> 
                <span>
                    <?php 
                    if($_GET['pesan'] == "gagal") echo "Username atau Password salah.";
                    else if($_GET['pesan'] == "tidak_aktif") echo "Akun dinonaktifkan.";
                    else echo "Gagal masuk ke sistem.";
                    ?>
                </span>
            </div>
        <?php endif; ?>

        <form action="cek_login.php" method="POST">
            
            <div class="form-group">
                <label>Login Sebagai</label>
                <i class="fas fa-users icon-input"></i>
                <select name="sebagai" id="roleSelect" class="form-control" onchange="ubahUI()" required>
                    <option value="siswa">Siswa (Peserta Didik)</option>
                    <option value="admin">Administrator (Sekolah/Dinas)</option>
                </select>
            </div>

            <div class="form-group">
                <label id="labelUser">NISN</label>
                <i class="fas fa-user icon-input"></i>
                <input type="text" name="username" id="inputUser" class="form-control" required placeholder="Masukkan Nomor Identitas" autocomplete="off">
            </div>

            <div class="form-group">
                <label>Kata Sandi</label>
                <i class="fas fa-lock icon-input"></i>
                <input type="password" name="password" class="form-control" required placeholder="Masukkan Kata Sandi">
            </div>
            
            <button type="submit" class="btn-login">Masuk</button>
        </form>

        <div class="footer-link">
            <a href="daftar_siswa.php">Buat Akun Siswa Baru</a>
            <br><br>
            <a href="../index.php" style="color:#888; font-weight:normal;">Kembali ke Beranda</a>
        </div>
    </div>

    <script>
        function ubahUI() {
            var role = document.getElementById("roleSelect").value;
            var label = document.getElementById("labelUser");
            var input = document.getElementById("inputUser");

            if (role == "siswa") {
                label.innerText = "NISN";
                input.placeholder = "Masukkan NISN";
            } else {
                label.innerText = "Username";
                input.placeholder = "Masukkan Username";
            }
        }
    </script>
</body>
</html>