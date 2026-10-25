<?php
session_start();
require_once '../konfigurasi/koneksi.php';

if (!isset($_POST['id_siswa'])) {
    header("location:../siswa/dashboard.php");
    exit();
}

// Sanitasi Input
$id    = $_POST['id_siswa'];
$nama  = mysqli_real_escape_string($koneksi, $_POST['nama']);
$nik   = mysqli_real_escape_string($koneksi, $_POST['nik']);
$tmp   = mysqli_real_escape_string($koneksi, $_POST['tempat_lahir']);
$tgl   = $_POST['tgl_lahir'];
$jk    = $_POST['jk'];
$alm   = mysqli_real_escape_string($koneksi, $_POST['alamat']);
$lat   = $_POST['lat'];
$lng   = $_POST['lng'];
$hp    = mysqli_real_escape_string($koneksi, $_POST['no_hp']);

// Update Data
$query = "UPDATE siswa SET 
            nama_lengkap = '$nama',
            nik = '$nik',
            tempat_lahir = '$tmp',
            tgl_lahir = '$tgl',
            jenis_kelamin = '$jk',
            alamat_rumah = '$alm',
            lat = '$lat',
            lng = '$lng',
            no_hp = '$hp'
          WHERE id = '$id'";

if (mysqli_query($koneksi, $query)) {
    // Jika berhasil, arahkan ke tahap selanjutnya (Isi Rapor)
    echo "<script>alert('Biodata berhasil disimpan!'); window.location.href='../siswa/isi_rapor.php';</script>";
} else {
    echo "Gagal menyimpan: " . mysqli_error($koneksi);
}
?>