<?php
session_start();
require_once '../konfigurasi/koneksi.php';
require_once '../konfigurasi/url.php'; // Penting untuk path folder

if (!isset($_SESSION['id_user'])) {
    die("Error: Anda belum login.");
}

$id_siswa = $_SESSION['id_user'];
$tipe_berkas = $_POST['tipe_berkas']; // kk, akta, kip, kesehatan, foto

// Tentukan Target Folder & Nama Kolom Database
$target_dir = "";
$kolom_db = "";

switch ($tipe_berkas) {
    case 'kk':
        $target_dir = "../unggahan/dokumen/";
        $kolom_db = "file_kk";
        break;
    case 'akta':
        $target_dir = "../unggahan/dokumen/";
        $kolom_db = "file_akta";
        break;
    case 'kip':
        $target_dir = "../unggahan/dokumen/";
        $kolom_db = "file_kip";
        break;
    case 'kesehatan':
        $target_dir = "../unggahan/kesehatan/";
        $kolom_db = "file_buta_warna"; // Sesuaikan dengan nama kolom di database Anda
        break;
    case 'foto':
        $target_dir = "../unggahan/profil/";
        $kolom_db = "foto_profil";
        break;
    default:
        die("Tipe berkas tidak dikenali.");
}

// Cek apakah ada file yang dikirim
if (isset($_FILES['berkas']) && $_FILES['berkas']['error'] == 0) {
    
    $file_tmp = $_FILES['berkas']['tmp_name'];
    $file_name = $_FILES['berkas']['name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    // Nama file baru: ID_TIPE.ext (Contoh: 101_KK.pdf) agar rapi & tidak tertukar
    $nama_baru = $id_siswa . "_" . strtoupper($tipe_berkas) . "." . $file_ext;
    $target_file = $target_dir . $nama_baru;

    // Cek ekstensi yang dibolehkan
    $allowed = ['pdf', 'jpg', 'jpeg', 'png'];
    if (!in_array($file_ext, $allowed)) {
        echo "<script>alert('Format file harus PDF, JPG, atau PNG!'); window.location='../siswa/upload_berkas.php';</script>";
        exit();
    }

    // PROSES UPLOAD
    if (move_uploaded_file($file_tmp, $target_file)) {
        
        // UPDATE DATABASE (PENTING!)
        // Kita update kolom yang sesuai dengan nama file baru
        $query = "UPDATE siswa SET $kolom_db = '$nama_baru' WHERE id = '$id_siswa'";
        $update = mysqli_query($koneksi, $query);

        if ($update) {
            echo "<script>alert('Berhasil upload berkas $tipe_berkas!'); window.location='../siswa/upload_berkas.php';</script>";
        } else {
            echo "<script>alert('Gagal update database: " . mysqli_error($koneksi) . "'); window.location='../siswa/upload_berkas.php';</script>";
        }

    } else {
        echo "<script>alert('Gagal memindahkan file ke folder $target_dir'); window.location='../siswa/upload_berkas.php';</script>";
    }

} else {
    echo "<script>alert('Pilih file terlebih dahulu atau file terlalu besar!'); window.location='../siswa/upload_berkas.php';</script>";
}
?>