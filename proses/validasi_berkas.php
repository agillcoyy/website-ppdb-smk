<?php
session_start();
require_once '../konfigurasi/koneksi.php';

// Pastikan hanya admin sekolah yang bisa akses
if (!isset($_SESSION['status']) || $_SESSION['role'] != 'sekolah') {
    header("location:../akses/login.php"); exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pendaftaran = $_POST['id_pendaftaran'];
    $status_baru    = $_POST['status']; // Isinya 'Diterima' atau 'Ditolak'
    
    // Verifikasi ID
    if(empty($id_pendaftaran)){
        echo "<script>alert('ID Pendaftaran tidak ditemukan!'); window.history.back();</script>";
        exit;
    }

    // Eksekusi Update ke Database
    $update = mysqli_query($koneksi, "UPDATE pendaftaran 
                                      SET status_verifikasi = '$status_baru' 
                                      WHERE id = '$id_pendaftaran'");

    if ($update) {
        echo "<script>
            alert('Status berhasil diubah menjadi: $status_baru'); 
            window.location='../admin_sekolah/verifikasi.php';
        </script>";
    } else {
        echo "<script>
            alert('Gagal mengubah status! Error: " . mysqli_error($koneksi) . "'); 
            window.history.back();
        </script>";
    }
}
?>