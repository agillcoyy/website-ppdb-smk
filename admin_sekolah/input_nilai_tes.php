<?php
session_start();
require_once '../konfigurasi/koneksi.php';
require_once '../konfigurasi/url.php';

if (!isset($_SESSION['status']) || $_SESSION['role'] != 'sekolah') {
    header("location:../akses/login.php"); exit();
}

$id_sekolah = $_SESSION['id_sekolah'];

// Ambil siswa yang SUDAH DIVALIDASI (Status = Diterima) di sekolah ini
$query = "SELECT p.*, s.nama_lengkap, s.nisn 
          FROM pendaftaran p
          JOIN siswa s ON p.id_siswa = s.id
          JOIN sekolah_jurusan sj ON p.pilihan_1_id_jurusan = sj.id
          WHERE sj.id_sekolah = '$id_sekolah' AND p.status_verifikasi = 'Diterima'
          ORDER BY s.nama_lengkap ASC";

$result = mysqli_query($koneksi, $query);

include '../komponen/header_umum.php';
include '../komponen/sidebar_sekolah.php';
?>

<div class="content-area">
    <h3>Input Nilai Tes Minat Bakat</h3>
    <div class="alert-box">
        Masukkan nilai hasil tes (Skala 0-100). Sistem otomatis menghitung Skor Akhir (70% Tes + 30% Rapor) setelah disimpan.
    </div>

    <div class="card">
        <form action="../proses/simpan_nilai_tes.php" method="POST">
            <table border="1" cellpadding="8" cellspacing="0" width="100%" style="border-collapse:collapse;">
                <tr style="background:#f8f9fa;">
                    <th>No</th>
                    <th>NISN</th>
                    <th>Nama Siswa</th>
                    <th>Rerata Rapor (30%)</th>
                    <th>Nilai Tes (70%)</th>
                </tr>
                <?php 
                $no=1; 
                while($row = mysqli_fetch_assoc($result)): 
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $row['nisn'] ?></td>
                    <td><?= $row['nama_lengkap'] ?></td>
                    <td align="center"><?= $row['rata_rata_rapor_total'] ?></td>
                    <td>
                        <input type="number" step="0.01" max="100" name="nilai_tes[<?= $row['id'] ?>]" class="form-control" value="<?= $row['nilai_tes_minat_bakat'] ?>" placeholder="0-100" style="width:100px;">
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
            
            <br>
            <div style="text-align:right;">
                <button type="submit" class="btn-save">SIMPAN SEMUA NILAI TES</button>
            </div>
        </form>
    </div>
</div>

<?php include '../komponen/footer_umum.php'; ?>