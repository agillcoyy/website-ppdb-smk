<?php
session_start();
require_once '../konfigurasi/koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['role'] != 'sekolah') { exit(); }

$id_sekolah = $_SESSION['id_sekolah'];
$filename = "Data_Pendaftar_" . date('Ymd') . ".xls";

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=$filename");

// PERBAIKAN QUERY DI admin_sekolah/laporan.php
$query = "SELECT p.*, s.nama_lengkap, s.nisn, s.asal_sekolah, s.no_hp,
          j1.nama_jurusan as jur1, 
          j2.nama_jurusan as jur2 
          FROM pendaftaran p
          JOIN siswa s ON p.id_siswa = s.id
          JOIN sekolah_jurusan sj1 ON p.pilihan_1_id_jurusan = sj1.id
          JOIN jurusan j1 ON sj1.id_jurusan = j1.id  -- INI DITAMBAHKAN
          LEFT JOIN sekolah_jurusan sj2 ON p.pilihan_2_id_jurusan = sj2.id
          LEFT JOIN jurusan j2 ON sj2.id_jurusan = j2.id -- INI DITAMBAHKAN
          WHERE sj1.id_sekolah = '$id_sekolah' OR sj2.id_sekolah = '$id_sekolah'";
$result = mysqli_query($koneksi, $query);
?>

<h3>DATA PENDAFTAR PPDB SMK</h3>
<table border="1">
    <tr>
        <th>No</th><th>NISN</th><th>Nama</th><th>HP</th><th>Asal Sekolah</th>
        <th>Pilihan 1</th><th>Pilihan 2</th><th>Rapor (30%)</th><th>Tes (70%)</th><th>Skor Akhir</th><th>Status</th>
    </tr>
    <?php $no=1; while($r = mysqli_fetch_assoc($result)): ?>
    <tr>
        <td><?= $no++ ?></td>
        <td>'<?= $r['nisn'] ?></td>
        <td><?= $r['nama_lengkap'] ?></td>
        <td><?= $r['no_hp'] ?></td>
        <td><?= $r['asal_sekolah'] ?></td>
        <td><?= $r['jur1'] ?></td>
        <td><?= $r['jur2'] ?? '-' ?></td>
        <td><?= $r['rata_rata_rapor_total'] ?></td>
        <td><?= $r['nilai_tes_minat_bakat'] ?></td>
        <td><?= $r['skor_akhir_pilihan_1'] ?></td>
        <td><?= $r['status_verifikasi'] ?></td>
    </tr>
    <?php endwhile; ?>
</table>