<?php
require_once '../konfigurasi/koneksi.php';

// Ambil data dari form
$nisn = mysqli_real_escape_string($koneksi, $_POST['nisn']);
$nama = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
$asal = mysqli_real_escape_string($koneksi, $_POST['asal_sekolah']);
$pass = mysqli_real_escape_string($koneksi, $_POST['password']);
$conf = mysqli_real_escape_string($koneksi, $_POST['konfirmasi_password']);

// Validasi Password
if ($pass != $conf) {
    header("location:../akses/daftar_siswa.php?pesan=beda_password");
    exit();
}

// Cek apakah NISN sudah ada
$cek_nisn = mysqli_query($koneksi, "SELECT nisn FROM siswa WHERE nisn='$nisn'");
if (mysqli_num_rows($cek_nisn) > 0) {
    header("location:../akses/daftar_siswa.php?pesan=gagal"); // NISN duplikat
    exit();
}

// Enkripsi Password (Hash)
$password_hash = password_hash($pass, PASSWORD_DEFAULT);

// Insert Data (Default status_data = BELUM)
$query = "INSERT INTO siswa (nisn, nama_lengkap, asal_sekolah, password, status_data) 
          VALUES ('$nisn', '$nama', '$asal', '$password_hash', 'BELUM')";

if (mysqli_query($koneksi, $query)) {
    // Berhasil, arahkan ke login
    header("location:../akses/login.php?pesan=daftar_sukses");
} else {
    // Gagal Query
    header("location:../akses/daftar_siswa.php?pesan=gagal");
}
?>