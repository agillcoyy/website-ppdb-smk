<?php
session_start();
require_once '../konfigurasi/url.php';
require_once '../konfigurasi/koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['role'] != 'siswa') {
    header("location:../akses/login.php");
    exit();
}

$id_siswa = $_SESSION['id_user'];
// Ambil data siswa terbaru
$q = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id='$id_siswa'");
$d = mysqli_fetch_assoc($q);

$lokasi_folder = "../"; // Kunci agar CSS header terbaca
include '../komponen/header_umum.php';
include '../komponen/sidebar_siswa.php';

// Fungsi bantu untuk cek status
function cekStatus($nama_file_db, $folder) {
    if (!empty($nama_file_db)) {
        return '<span class="badge badge-success" style="background:#d4edda; color:#155724; padding:5px 10px; border-radius:15px; font-size:12px; border:1px solid #c3e6cb;"><i class="fas fa-check-circle"></i> Selesai</span>';
    } else {
        return '<span class="badge badge-danger" style="background:#f8d7da; color:#721c24; padding:5px 10px; border-radius:15px; font-size:12px; border:1px solid #f5c6cb;"><i class="fas fa-times-circle"></i> Belum ada</span>';
    }
}
?>

<style>
    /* CSS Khusus Tombol Upload Santai */
    .btn-upload-santai {
        background-color: #006400;
        color: white;
        padding: 8px 15px; /* Ukuran lebih kecil */
        font-size: 13px;   /* Font lebih santai */
        border: none;
        border-radius: 4px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: 0.2s;
        text-transform: uppercase;
        font-weight: 600;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .btn-upload-santai:hover {
        background-color: #004d00;
        transform: translateY(-1px);
    }
    .file-input-group {
        display: flex; 
        gap: 10px; 
        align-items: center;
        margin-top: 10px;
        background: #f9f9f9;
        padding: 10px;
        border-radius: 5px;
        border: 1px dashed #ccc;
    }
</style>

<div class="content-area">
    <h3>Upload Berkas Persyaratan</h3>
    
    <div class="alert-box">
        <i class="fas fa-info-circle"></i> &nbsp;
        Upload dokumen dalam format <b>PDF, JPG, atau PNG</b>. Maksimal ukuran 2MB per file.
    </div>

    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h4 style="margin:0;"><i class="fas fa-file-alt" style="color:#555;"></i> 1. Kartu Keluarga (KK)</h4>
            <div><?= cekStatus($d['file_kk'], 'dokumen') ?></div>
        </div>
        <form action="../proses/simpan_berkas.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="tipe_berkas" value="kk">
            <div class="file-input-group">
                <input type="file" name="berkas" class="form-control" style="border:none; padding:5px;" required>
                <button type="submit" class="btn-upload-santai">
                    <i class="fas fa-upload"></i> Upload
                </button>
            </div>
            <?php if(!empty($d['file_kk'])): ?>
                <small style="color:#28a745; display:block; margin-top:5px;">File tersimpan: <?= $d['file_kk'] ?></small>
            <?php endif; ?>
        </form>
    </div>

    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h4 style="margin:0;"><i class="fas fa-file-alt" style="color:#555;"></i> 2. Akta Kelahiran</h4>
            <div><?= cekStatus($d['file_akta'], 'dokumen') ?></div>
        </div>
        <form action="../proses/simpan_berkas.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="tipe_berkas" value="akta">
            <div class="file-input-group">
                <input type="file" name="berkas" class="form-control" style="border:none; padding:5px;" required>
                <button type="submit" class="btn-upload-santai">
                    <i class="fas fa-upload"></i> Upload
                </button>
            </div>
            <?php if(!empty($d['file_akta'])): ?>
                <small style="color:#28a745; display:block; margin-top:5px;">File tersimpan: <?= $d['file_akta'] ?></small>
            <?php endif; ?>
        </form>
    </div>

    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h4 style="margin:0;"><i class="fas fa-address-card" style="color:#555;"></i> 3. KIP/KKS/PKH (Opsional)</h4>
            <div><?= cekStatus($d['file_kip'], 'dokumen') ?></div>
        </div>
        <form action="../proses/simpan_berkas.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="tipe_berkas" value="kip">
            <div class="file-input-group">
                <input type="file" name="berkas" class="form-control" style="border:none; padding:5px;">
                <button type="submit" class="btn-upload-santai">
                    <i class="fas fa-upload"></i> Upload
                </button>
            </div>
            <?php if(!empty($d['file_kip'])): ?>
                <small style="color:#28a745; display:block; margin-top:5px;">File tersimpan: <?= $d['file_kip'] ?></small>
            <?php endif; ?>
        </form>
    </div>

    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h4 style="margin:0;"><i class="fas fa-hospital" style="color:#555;"></i> 4. Surat Ket. Buta Warna</h4>
            
            <div>
                <?= cekStatus(isset($d['file_buta_warna']) ? $d['file_buta_warna'] : '', 'kesehatan') ?>
            </div>
        </div>
        <form action="../proses/simpan_berkas.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="tipe_berkas" value="kesehatan">
            <div class="file-input-group">
                <input type="file" name="berkas" class="form-control" style="border:none; padding:5px;" required>
                <button type="submit" class="btn-upload-santai">
                    <i class="fas fa-upload"></i> Upload
                </button>
            </div>
            <?php if(isset($d['file_buta_warna']) && !empty($d['file_buta_warna'])): ?>
                <small style="color:#28a745; display:block; margin-top:5px;">File tersimpan: <?= $d['file_buta_warna'] ?></small>
            <?php endif; ?>
        </form>
    </div>

</div>

<?php include '../komponen/footer_umum.php'; ?>