<?php
session_start();
require_once '../konfigurasi/koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['role'] != 'sekolah') {
    header("location:../akses/login.php"); exit();
}

$nilai_tes_array = $_POST['nilai_tes'];

foreach ($nilai_tes_array as $id_daftar => $nilai_tes) {
    // Pastikan nilai angka
    $nilai_tes = floatval($nilai_tes);

    // Ambil nilai rapor dulu dari database untuk dihitung
    $q = mysqli_query($koneksi, "SELECT rata_rata_rapor_total FROM pendaftaran WHERE id='$id_daftar'");
    $d = mysqli_fetch_assoc($q);
    $rapor = $d['rata_rata_rapor_total'];

    // RUMUS JUKNIS SMK: (0.3 x Rapor) + (0.7 x Tes)
    $skor_akhir = ($rapor * 0.3) + ($nilai_tes * 0.7);

    // Update Database
    // Kita update skor akhir untuk kedua pilihan (jika ada) karena Tes Minat Bakat berlaku umum untuk bakat siswa
    $query_update = "UPDATE pendaftaran SET 
                     nilai_tes_minat_bakat = '$nilai_tes',
                     skor_akhir_pilihan_1 = '$skor_akhir',
                     skor_akhir_pilihan_2 = '$skor_akhir'
                     WHERE id = '$id_daftar'";
    
    mysqli_query($koneksi, $query_update);
}

echo "<script>alert('Nilai Tes & Skor Akhir berhasil dihitung!'); window.location.href='../admin_sekolah/input_nilai_tes.php';</script>";
?>