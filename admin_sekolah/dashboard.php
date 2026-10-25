<?php
session_start();
require_once '../konfigurasi/koneksi.php';
require_once '../konfigurasi/url.php';

// Cek Sesi Admin Sekolah
if (!isset($_SESSION['status']) || $_SESSION['role'] != 'sekolah') {
    header("location:../akses/login.php"); exit();
}

$id_sekolah = $_SESSION['id_sekolah'];

// Hitung Pendaftar Masuk (Pilihan 1 atau Pilihan 2)
// Logic: Kita cari siswa yang memilih jurusan di sekolah ini
$query_total = mysqli_query($koneksi, "
    SELECT COUNT(*) as total FROM pendaftaran p
    JOIN sekolah_jurusan sj1 ON p.pilihan_1_id_jurusan = sj1.id
    LEFT JOIN sekolah_jurusan sj2 ON p.pilihan_2_id_jurusan = sj2.id
    WHERE sj1.id_sekolah = '$id_sekolah' OR sj2.id_sekolah = '$id_sekolah'
");
$d_total = mysqli_fetch_assoc($query_total);

// Hitung yang Belum Diverifikasi
$query_pending = mysqli_query($koneksi, "
    SELECT COUNT(*) as total FROM pendaftaran p
    JOIN sekolah_jurusan sj1 ON p.pilihan_1_id_jurusan = sj1.id
    WHERE sj1.id_sekolah = '$id_sekolah' AND p.status_verifikasi = 'Menunggu'
");
$d_pending = mysqli_fetch_assoc($query_pending);

include '../komponen/header_umum.php';
include '../komponen/sidebar_sekolah.php';
?>

<div class="content-area">
    <h2>Dashboard Admin Sekolah</h2>
    
    <div style="display: flex; gap: 20px;">
        <div class="card" style="flex: 1; background: #007bff; color: white;">
            <h3><?= $d_total['total'] ?></h3>
            <p>Total Pendaftar Masuk</p>
        </div>
        <div class="card" style="flex: 1; background: #ffc107; color: black;">
            <h3><?= $d_pending['total'] ?></h3>
            <p>Perlu Verifikasi</p>
        </div>
        <div class="card" style="flex: 1; background: #28a745; color: white;">
            <h3>Jadwal Tes</h3>
            <p>Input Nilai Segera!</p>
        </div>
    </div>

    <div class="card">
        <h3>Selamat Datang, Operator!</h3>
        <p>Tugas utama Anda saat ini:</p>
        <ol>
            <li>Memverifikasi berkas fisik siswa yang datang ke sekolah.</li>
            <li>Menginput Nilai <strong>Tes Minat Bakat</strong> setelah ujian dilaksanakan.</li>
            <li>Memantau kuota jurusan.</li>
        </ol>
    </div>
</div>

<?php include '../komponen/footer_umum.php'; ?>