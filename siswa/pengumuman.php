<?php
session_start();
require_once '../konfigurasi/koneksi.php';
require_once '../konfigurasi/url.php';

if (!isset($_SESSION['status']) || $_SESSION['role'] != 'siswa') {
    header("location:../akses/login.php"); exit();
}

$id_siswa = $_SESSION['id_user'];

// 1. Cek Data Pendaftaran
$q_daftar = mysqli_query($koneksi, "SELECT * FROM pendaftaran WHERE id_siswa='$id_siswa'");
$data = mysqli_fetch_assoc($q_daftar);

// Jika belum daftar
if (!$data) {
    echo "<script>alert('Anda belum mendaftar!'); window.location.href='dashboard.php';</script>";
    exit();
}

// 2. Cek Status Kelulusan (Mengambil logic perankingan sederhana)
// Kita harus cek ranking dia di pilihan 1 atau pilihan 2
$status_lulus = "MENUNGGU";
$sekolah_diterima = "-";
$jurusan_diterima = "-";

// Ambil ID Pilihan
$p1 = $data['pilihan_1_id_jurusan'];
$p2 = $data['pilihan_2_id_jurusan'];
$skor_saya = $data['skor_akhir_pilihan_1']; // Skor sama untuk kedua pilihan
$jalur = $data['jalur_pendaftaran'];

// --- LOGIC PENGECEKAN KELULUSAN ---
// Kita cek apakah ID Siswa ini masuk dalam Top Limit Kuota di Pilihan 1?
function cekRanking($koneksi, $id_sekolah_jurusan, $id_siswa_saya) {
    // Ambil Kuota
    $q_kuota = mysqli_query($koneksi, "SELECT kuota FROM sekolah_jurusan WHERE id='$id_sekolah_jurusan'");
    $d_kuota = mysqli_fetch_assoc($q_kuota);
    $kuota = $d_kuota['kuota'];

    // Ambil Ranking (Urutkan skor tertinggi)
    // Filter hanya yang status verifikasinya 'Diterima' (Valid)
    $query_rank = "
        SELECT p.id_siswa 
        FROM pendaftaran p 
        WHERE (p.pilihan_1_id_jurusan = '$id_sekolah_jurusan' OR p.pilihan_2_id_jurusan = '$id_sekolah_jurusan')
        AND p.status_verifikasi = 'Diterima'
        ORDER BY p.skor_akhir_pilihan_1 DESC, p.nilai_tes_minat_bakat DESC, p.rata_rata_rapor_total DESC
        LIMIT $kuota
    ";
    
    $result = mysqli_query($koneksi, $query_rank);
    $masuk = false;
    
    while($r = mysqli_fetch_assoc($result)) {
        if ($r['id_siswa'] == $id_siswa_saya) {
            $masuk = true; 
            break;
        }
    }
    return $masuk;
}

// Cek hanya jika Status Verifikasi sudah 'Diterima' (Berkas Valid)
if ($data['status_verifikasi'] == 'Diterima') {
    // Cek Pilihan 1
    if (cekRanking($koneksi, $p1, $id_siswa)) {
        $status_lulus = "LULUS";
        
        // Ambil Nama Sekolah & Jurusan P1
        $q_det = mysqli_query($koneksi, "SELECT s.nama_sekolah, j.nama_jurusan FROM sekolah_jurusan sj JOIN sekolah s ON sj.id_sekolah=s.id JOIN jurusan j ON sj.id_jurusan=j.id WHERE sj.id='$p1'");
        $d_det = mysqli_fetch_assoc($q_det);
        $sekolah_diterima = $d_det['nama_sekolah'];
        $jurusan_diterima = $d_det['nama_jurusan'];
        
    } 
    // Jika Gagal P1, Cek Pilihan 2
    else if ($p2 && cekRanking($koneksi, $p2, $id_siswa)) {
        $status_lulus = "LULUS";
        
        $q_det = mysqli_query($koneksi, "SELECT s.nama_sekolah, j.nama_jurusan FROM sekolah_jurusan sj JOIN sekolah s ON sj.id_sekolah=s.id JOIN jurusan j ON sj.id_jurusan=j.id WHERE sj.id='$p2'");
        $d_det = mysqli_fetch_assoc($q_det);
        $sekolah_diterima = $d_det['nama_sekolah'];
        $jurusan_diterima = $d_det['nama_jurusan'];
    } 
    else {
        $status_lulus = "TIDAK LULUS";
    }
} else if ($data['status_verifikasi'] == 'Ditolak') {
    $status_lulus = "GUGUR (BERKAS DITOLAK)";
} else {
    $status_lulus = "PROSES VERIFIKASI";
}

include '../komponen/header_umum.php';
include '../komponen/sidebar_siswa.php';
?>

<div class="content-area">
    <h3>Pengumuman Hasil Seleksi</h3>

    <div class="card" style="text-align: center; padding: 40px;">
        <h4 style="color:#555;">STATUS KELULUSAN ANDA:</h4>
        
        <?php if($status_lulus == "LULUS"): ?>
            <h1 style="color: green; font-size: 3em; margin: 10px 0;">SELAMAT! ANDA LULUS</h1>
            <p>Anda diterima di:</p>
            <h3><?= $sekolah_diterima ?></h3>
            <h4>Kompetensi Keahlian: <?= $jurusan_diterima ?></h4>
            <br>
            <div class="alert-box" style="background:#d4edda; border-color:#c3e6cb; color:#155724;">
                Silakan lakukan <strong>Daftar Ulang</strong> ke sekolah tujuan dengan membawa Bukti Pendaftaran dan dokumen asli pada tanggal yang ditentukan.
            </div>

        <?php elseif($status_lulus == "TIDAK LULUS"): ?>
            <h1 style="color: red; font-size: 3em; margin: 10px 0;">MOHON MAAF</h1>
            <p>Anda belum lulus seleksi pada pilihan sekolah yang Anda tuju.</p>
            <p>Skor Akhir Anda: <strong><?= $data['skor_akhir_pilihan_1'] ?></strong></p>
            <p>Jangan putus asa, tetap semangat!</p>

        <?php elseif($status_lulus == "PROSES VERIFIKASI"): ?>
            <h1 style="color: orange; font-size: 3em; margin: 10px 0;">MENUNGGU VERIFIKASI</h1>
            <p>Berkas Anda sedang diverifikasi atau Nilai Tes belum diinput oleh admin sekolah.</p>
            <p>Pantau terus halaman ini.</p>
        
        <?php else: ?>
             <h1 style="color: darkred; font-size: 3em; margin: 10px 0;">GUGUR</h1>
             <p>Berkas pendaftaran Anda ditolak saat verifikasi.</p>
             <p>Alasan: <i><?= $data['catatan_verifikator'] ?></i></p>
        <?php endif; ?>
    </div>

    <div class="card">
        <h4>Detail Pendaftaran</h4>
        <table border="0" style="width: 100%;">
            <tr><td width="200">Jalur Pendaftaran</td><td>: <strong><?= $jalur ?></strong></td></tr>
            <tr><td>Nilai Rapor (30%)</td><td>: <?= $data['rata_rata_rapor_total'] ?></td></tr>
            <tr><td>Nilai Tes Minat (70%)</td><td>: <?= $data['nilai_tes_minat_bakat'] ?></td></tr>
            <tr><td><strong>Total Skor Akhir</strong></td><td>: <strong><?= $data['skor_akhir_pilihan_1'] ?></strong></td></tr>
        </table>
    </div>
</div>

<?php include '../komponen/footer_umum.php'; ?>