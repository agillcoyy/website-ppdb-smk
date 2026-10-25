<?php
session_start();
require_once '../konfigurasi/koneksi.php';
require_once '../konfigurasi/url.php';

if (!isset($_SESSION['status']) || $_SESSION['role'] != 'dinas') {
    header("location:../akses/login.php"); exit();
}

// Hitung Statistik
$q_sekolah = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM sekolah");
$d_sekolah = mysqli_fetch_assoc($q_sekolah);

$q_siswa = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM siswa");
$d_siswa = mysqli_fetch_assoc($q_siswa);

$q_daftar = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pendaftaran");
$d_daftar = mysqli_fetch_assoc($q_daftar);

include '../komponen/header_umum.php';
include '../komponen/sidebar_dinas.php';
?>

<div class="content-area">
    <h2>Dashboard Dinas Pendidikan</h2>
    <div style="display: flex; gap: 20px;">
        <div class="card" style="flex:1; background:#17a2b8; color:white;">
            <h3><?= $d_sekolah['total'] ?></h3>
            <p>Jumlah SMK Negeri</p>
        </div>
        <div class="card" style="flex:1; background:#28a745; color:white;">
            <h3><?= $d_siswa['total'] ?></h3>
            <p>Total Akun Siswa</p>
        </div>
        <div class="card" style="flex:1; background:#ffc107;">
            <h3><?= $d_daftar['total'] ?></h3>
            <p>Sudah Mendaftar</p>
        </div>
    </div>
</div>
<?php include '../komponen/footer_umum.php'; ?>