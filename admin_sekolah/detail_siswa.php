<?php 
session_start();
require_once '../konfigurasi/koneksi.php';
require_once '../konfigurasi/url.php';

if (!isset($_SESSION['status']) || $_SESSION['role'] != 'sekolah') {
    header("location:../akses/login.php"); exit();
}

$id_pendaftaran = $_GET['id']; // Ini ID dari tabel pendaftaran

// Ambil data detail siswa berdasarkan ID Pendaftaran
$query = mysqli_query($koneksi, "SELECT p.*, s.*, j.nama_jurusan 
          FROM pendaftaran p
          JOIN siswa s ON p.id_siswa = s.id
          JOIN sekolah_jurusan sj ON p.pilihan_1_id_jurusan = sj.id
          JOIN jurusan j ON sj.id_jurusan = j.id
          WHERE p.id = '$id_pendaftaran'");

$data = mysqli_fetch_array($query);

include '../komponen/header_umum.php'; 
include '../komponen/sidebar_sekolah.php'; 
?>

<div class="content-area">
    <div class="card">
        <div style="display:flex; justify-content:space-between;">
            <h3>Detail Calon Siswa</h3>
            <a href="verifikasi.php" class="btn-back" style="text-decoration:none; background:#6c757d; color:white; padding:5px 15px; border-radius:4px;">Kembali</a>
        </div>
        <hr>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px;">
            <div>
                <h4>Biodata Siswa</h4>
                <table class="table" style="width:100%;">
                    <tr><td width="150">NISN</td><td>: <strong><?= $data['nisn'] ?></strong></td></tr>
                    <tr><td>Nama Lengkap</td><td>: <?= $data['nama_lengkap'] ?></td></tr>
                    <tr><td>Asal Sekolah</td><td>: <?= $data['asal_sekolah'] ?></td></tr>
                    <tr><td>Jurusan Pilihan</td><td>: <?= $data['nama_jurusan'] ?></td></tr>
                    <tr><td>Status Saat Ini</td><td>: 
                        <span style="font-weight:bold; color:blue;"><?= $data['status_verifikasi'] ?></span>
                    </td></tr>
                </table>
            </div>

            <div>
                <h4>Berkas Upload</h4>
                <ul>
                    <li>Kartu Keluarga: <a href="<?= BASE_URL ?>unggahan/dokumen/<?= $data['file_kk'] ?>" target="_blank">Lihat</a></li>
                    <li>Akta Kelahiran: <a href="<?= BASE_URL ?>unggahan/dokumen/<?= $data['file_akta'] ?>" target="_blank">Lihat</a></li>
                    <li>KIP/KPS (Opsional): 
                        <?php if(!empty($data['file_kip'])): ?>
                            <a href="<?= BASE_URL ?>unggahan/dokumen/<?= $data['file_kip'] ?>" target="_blank">Lihat</a>
                        <?php else: echo "-"; endif; ?>
                    </li>
                </ul>
            </div>
        </div>

        <hr>
        
        <div style="background:#f1f1f1; padding:20px; border-radius:8px; text-align:center;">
            <h4>Aksi Verifikasi</h4>
            <p>Pastikan semua berkas fisik/digital sudah sesuai sebelum mengubah status.</p>
            
            <form action="../proses/validasi_berkas.php" method="POST">
                <input type="hidden" name="id_pendaftaran" value="<?= $data[0] ?>"> <button type="submit" name="status" value="Diterima" class="btn-simpan" style="background:#28a745; color:white; padding:10px 20px; border:none; border-radius:5px; cursor:pointer; font-weight:bold; margin-right:10px;">
                    <i class="fas fa-check"></i> BERKAS VALID (TERIMA)
                </button>
                
                <button type="submit" name="status" value="Ditolak" class="btn-tolak" style="background:#dc3545; color:white; padding:10px 20px; border:none; border-radius:5px; cursor:pointer; font-weight:bold;" onclick="return confirm('Yakin ingin menolak berkas siswa ini?')">
                    <i class="fas fa-times"></i> BERKAS TIDAK LENGKAP (TOLAK)
                </button>
            </form>
        </div>

    </div>
</div>

<?php include '../komponen/footer_umum.php'; ?>