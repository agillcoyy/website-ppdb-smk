<?php
session_start();
require_once '../konfigurasi/koneksi.php';
require_once '../konfigurasi/url.php';

if (!isset($_SESSION['status']) || $_SESSION['role'] != 'sekolah') {
    header("location:../akses/login.php"); exit();
}

$id_sekolah = $_SESSION['id_sekolah'];

// Ambil Siswa yang memilih sekolah ini di PILIHAN 1
$query = "SELECT p.*, s.nama_lengkap, s.nisn, j.nama_jurusan 
          FROM pendaftaran p
          JOIN siswa s ON p.id_siswa = s.id
          JOIN sekolah_jurusan sj ON p.pilihan_1_id_jurusan = sj.id
          JOIN jurusan j ON sj.id_jurusan = j.id
          WHERE sj.id_sekolah = '$id_sekolah'
          ORDER BY p.tgl_daftar ASC";

$result = mysqli_query($koneksi, $query);

include '../komponen/header_umum.php';
include '../komponen/sidebar_sekolah.php';
?>

<div class="content-area">
    <h3>Verifikasi Berkas Pendaftar</h3>
    <div class="alert-box">
        Daftar siswa di bawah ini memilih sekolah Anda sebagai <strong>Pilihan 1</strong>. Silakan cek berkas fisik dan validasi.
    </div>

    <div class="card">
        <table border="1" cellpadding="10" cellspacing="0" width="100%" style="border-collapse:collapse; border-color:#eee;">
            <thead>
                <tr style="background:#f8f9fa; text-align:left;">
                    <th>No</th>
                    <th>NISN</th>
                    <th>Nama Siswa</th>
                    <th>Jurusan Tujuan (Pil 1)</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no=1; 
                while($row = mysqli_fetch_assoc($result)): 
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $row['nisn'] ?></td>
                    <td><?= $row['nama_lengkap'] ?></td>
                    <td><?= $row['nama_jurusan'] ?></td>
                    <td>
                        <?php if($row['status_verifikasi'] == 'Menunggu'): ?>
                            <span style="background:orange; padding:3px 8px; border-radius:4px; font-size:12px;">Menunggu</span>
                        <?php elseif($row['status_verifikasi'] == 'Diterima'): ?>
                            <span style="background:lightgreen; padding:3px 8px; border-radius:4px; font-size:12px;">Valid</span>
                        <?php else: ?>
                            <span style="background:pink; padding:3px 8px; border-radius:4px; font-size:12px;"><?= $row['status_verifikasi'] ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="detail_siswa.php?id=<?= $row['id'] ?>" class="btn-save" style="text-decoration:none; font-size:12px; background:#17a2b8;">Periksa Dokumen</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../komponen/footer_umum.php'; ?>