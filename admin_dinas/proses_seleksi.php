<?php
session_start();
require_once '../konfigurasi/url.php';
if ($_SESSION['role'] != 'dinas') { header("location:../akses/login.php"); exit(); }

include '../komponen/header_umum.php';
include '../komponen/sidebar_dinas.php';
?>

<div class="content-area">
    <h3>Proses Seleksi PPDB</h3>
    <div class="card">
        <h4>Status Sistem: OTOMATIS (REALTIME)</h4>
        <p>Sistem melakukan perankingan secara otomatis setiap kali ada perubahan data nilai atau pendaftaran baru. Anda tidak perlu menekan tombol proses manual.</p>
        <hr>
        <p><strong>Rumus Seleksi SMK 2025:</strong></p>
        <ul>
            <li>Skor Akhir = (30% x Rerata Rapor Sem 1-5) + (70% x Nilai Tes Minat Bakat).</li>
            <li>Jika Skor Akhir sama, prioritas: Nilai Tes > Rapor > Usia/Waktu Daftar.</li>
        </ul>
        <br>
        <a href="ranking.php" class="btn-save" style="text-decoration:none;">Lihat Hasil Perangkingan</a>
    </div>
</div>
<?php include '../komponen/footer_umum.php'; ?>