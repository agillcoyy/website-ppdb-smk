<?php
session_start();
require_once '../konfigurasi/koneksi.php';

// Ambil data dan bersihkan dari karakter berbahaya (SQL Injection Prevention)
$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$password = $_POST['password']; // Password mentah untuk verifikasi hash
$sebagai  = $_POST['sebagai'];

// ==========================================================
// LOGIKA 1: LOGIN SISWA
// ==========================================================
if ($sebagai == "siswa") {
    $q = mysqli_query($koneksi, "SELECT * FROM siswa WHERE nisn='$username'");
    
    if (mysqli_num_rows($q) > 0) {
        $d = mysqli_fetch_assoc($q);
        
        // Verifikasi Password
        if (password_verify($password, $d['password'])) {
            // Set Session
            $_SESSION['status'] = "login";
            $_SESSION['role']   = "siswa";
            $_SESSION['id_user']= $d['id'];
            $_SESSION['nama']   = $d['nama_lengkap'];
            $_SESSION['nisn']   = $d['nisn'];
            
            header("location:../siswa/dashboard.php");
            exit;
        }
    }
    // Jika salah
    header("location:login.php?pesan=gagal");
    exit;
}

// ==========================================================
// LOGIKA 2: LOGIN ADMIN (SEKOLAH / DINAS)
// ==========================================================
else if ($sebagai == "admin") {
    $q = mysqli_query($koneksi, "SELECT * FROM admin WHERE username='$username'");
    
    if (mysqli_num_rows($q) > 0) {
        $d = mysqli_fetch_assoc($q);
        
        // Verifikasi Password
        if (password_verify($password, $d['password'])) {
            
            // Set Session Dasar
            $_SESSION['status']  = "login";
            $_SESSION['id_user'] = $d['id'];
            $_SESSION['nama']    = $d['nama_lengkap'];
            $_SESSION['role']    = $d['role']; // 'dinas' atau 'sekolah'
            
            // Redirect Sesuai Jabatan
            if ($d['role'] == "dinas") {
                header("location:../admin_dinas/dashboard.php");
            } else {
                // Jika sekolah, simpan ID sekolahnya
                $_SESSION['id_sekolah'] = $d['id_sekolah'];
                header("location:../admin_sekolah/dashboard.php");
            }
            exit;
        }
    }
    // Jika salah
    header("location:login.php?pesan=gagal");
    exit;
}
?>