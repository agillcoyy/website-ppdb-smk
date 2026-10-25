<?php
// Script untuk mereset akun Superadmin
require_once 'konfigurasi/koneksi.php';

// 1. Hapus akun superadmin lama (jika ada)
$hapus = mysqli_query($koneksi, "DELETE FROM admin WHERE username='superadmin'");

// 2. Buat Password Hash 'superadmin' yang VALID
$password_baru = password_hash("superadmin", PASSWORD_DEFAULT);

// 3. Masukkan Akun Baru
$tambah = mysqli_query($koneksi, "INSERT INTO admin (nama_lengkap, username, password, role, id_sekolah) 
                                  VALUES ('Super Administrator', 'superadmin', '$password_baru', 'dinas', NULL)");

if($tambah) {
    echo "<h2 style='color:green'>SUKSES! Akun Superadmin berhasil dibuat.</h2>";
    echo "<p>Username: <b>superadmin</b></p>";
    echo "<p>Password: <b>superadmin</b></p>";
    echo "<br><a href='akses/login.php'>KLIK DISINI UNTUK LOGIN</a>";
} else {
    echo "Gagal: " . mysqli_error($koneksi);
}
?>