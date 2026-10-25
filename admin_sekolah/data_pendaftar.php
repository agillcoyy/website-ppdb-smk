<?php
session_start();
require_once '../konfigurasi/koneksi.php';
require_once '../konfigurasi/url.php';

if (!isset($_SESSION['status']) || $_SESSION['role'] != 'sekolah') {
    header("location:../akses/login.php"); exit();
}

$id_sekolah = $_SESSION['id_sekolah'];

// PERBAIKAN QUERY: Menambahkan JOIN ke tabel jurusan (j1 dan j2)
$query = "SELECT p.*, s.nama_lengkap, s.nisn, s.asal_sekolah,
          j1.nama_jurusan as jur1, 
          j2.nama_jurusan as jur2 
          FROM pendaftaran p
          JOIN siswa s ON p.id_siswa = s.id
          JOIN sekolah_jurusan sj1 ON p.pilihan_1_id_jurusan = sj1.id
          JOIN jurusan j1 ON sj1.id_jurusan = j1.id
          LEFT JOIN sekolah_jurusan sj2 ON p.pilihan_2_id_jurusan = sj2.id
          LEFT JOIN jurusan j2 ON sj2.id_jurusan = j2.id
          WHERE sj1.id_sekolah = '$id_sekolah' OR sj2.id_sekolah = '$id_sekolah'
          ORDER BY p.tgl_daftar DESC";

$result = mysqli_query($koneksi, $query);

// Cek jika error query masih muncul untuk debugging
if (!$result) {
    die("Query Error: " . mysqli_error($koneksi));
}

include '../komponen/header_umum.php';
include '../komponen/sidebar_sekolah.php';
?>

<div class="content-area">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h3>Data Semua Pendaftar</h3>
        <a href="laporan.php" class="btn-save" style="text-decoration:none;">Download Excel</a>
    </div>
    
    <div class="card">
        <table border="1" cellpadding="5" cellspacing="0" width="100%" style="border-collapse:collapse; font-size:14px;">
            <thead>
                <tr style="background:#f8f9fa;">
                    <th>No</th>
                    <th>Tgl Daftar</th>
                    <th>NISN</th>
                    <th>Nama Siswa</th>
                    <th>Asal Sekolah</th>
                    <th>Pilihan 1</th>
                    <th>Pilihan 2</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no=1; 
                if (mysqli_num_rows($result) > 0):
                    while($row = mysqli_fetch_assoc($result)): 
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= date('d/m/Y', strtotime($row['tgl_daftar'])) ?></td>
                    <td><?= $row['nisn'] ?></td>
                    <td><?= $row['nama_lengkap'] ?></td>
                    <td><?= $row['asal_sekolah'] ?></td>
                    <td><?= $row['jur1'] ?></td>
                    <td><?= $row['jur2'] ?? '-' ?></td>
                    <td>
                        <?php
                        $warna = 'orange';
                        if($row['status_verifikasi'] == 'Diterima') $warna = 'green';
                        if($row['status_verifikasi'] == 'Ditolak') $warna = 'red';
                        ?>
                        <span style="padding:5px 10px; border-radius:15px; color:white; background: <?= $warna ?>; font-size:12px;">
                            <?= $row['status_verifikasi'] ?>
                        </span>
                    </td>
                </tr>
                <?php 
                    endwhile; 
                else:
                ?>
                <tr>
                    <td colspan="8" align="center">Belum ada pendaftar masuk.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include '../komponen/footer_umum.php'; ?>