<?php
session_start();
require_once '../konfigurasi/url.php';
require_once '../konfigurasi/koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['role'] != 'siswa') {
    header("location:../akses/login.php");
    exit();
}

$id_siswa = $_SESSION['id_user'];
$query = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id='$id_siswa'");
$siswa = mysqli_fetch_assoc($query);

// --- LOGIKA CEK KELENGKAPAN (DIPERBAIKI) ---
// Kita cek apakah kolom di database terisi atau tidak.
$kelengkapan = 0;
$total_syarat = 5; // Biodata, Nilai, KK, Akta, Buta Warna

// 1. Cek Biodata (Simpel: jika NIK ada)
if (!empty($siswa['nik']) && !empty($siswa['lat'])) $kelengkapan++;

// 2. Cek Nilai Rapor (Kita anggap lengkap jika rata-rata tidak 0)
// (Anda mungkin perlu query ke tabel nilai jika dipisah, tapi ini contoh sederhana jika nilai gabung di tabel siswa atau logic lain)
// Disini saya anggap user sudah input jika status_finalisasi != 'belum' atau cek tabel lain.
// Untuk amannya, kita cek manual tombolnya nanti.

// 3. Cek Berkas (Berdasarkan Database)
if (!empty($siswa['file_kk'])) $kelengkapan++;
if (!empty($siswa['file_akta'])) $kelengkapan++;
if (!empty($siswa['file_buta_warna'])) $kelengkapan++;

// Hitung persentase kasar (Sesuaikan dengan kebutuhan)
$persen = ($kelengkapan / 4) * 100; // Pembagi 4 (Biodata, KK, Akta, Sehat)
if ($persen > 100) $persen = 100;

$lokasi_folder = "../"; 
include '../komponen/header_umum.php';
include '../komponen/sidebar_siswa.php';
?>

<div class="content-area">
    <h3>Dashboard Siswa</h3>
    
    <div class="alert-box" style="background: #d4edda; color: #155724; border-left-color: #28a745;">
        Selamat Datang, <b><?= $siswa['nama_lengkap'] ?></b>! <br>
        Pantau terus status pendaftaran Anda di halaman ini.
    </div>

    <div class="card">
        <h4>Status Kelengkapan Data</h4>
        <div style="background:#e9ecef; border-radius:10px; height:20px; width:100%; margin:10px 0;">
            <div style="background:<?= $persen==100?'green':'orange' ?>; height:100%; width:<?= $persen ?>%; border-radius:10px; text-align:center; color:white; line-height:20px; font-size:12px;">
                <?= $persen ?>%
            </div>
        </div>
        
        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:15px; margin-top:20px;">
            
            <div style="border:1px solid #ddd; padding:15px; border-radius:5px; text-align:center;">
                <i class="fas fa-user-edit" style="font-size:30px; color:#007bff; margin-bottom:10px;"></i><br>
                Biodata Diri <br>
                <?php if(!empty($siswa['nik']) && !empty($siswa['lat'])): ?>
                    <span style="color:green; font-weight:bold;"><i class="fas fa-check-circle"></i> LENGKAP</span>
                <?php else: ?>
                    <span style="color:red; font-weight:bold;"><i class="fas fa-times-circle"></i> BELUM LENGKAP</span>
                <?php endif; ?>
            </div>

            <div style="border:1px solid #ddd; padding:15px; border-radius:5px; text-align:center;">
                <i class="fas fa-folder-open" style="font-size:30px; color:#ffc107; margin-bottom:10px;"></i><br>
                Berkas Upload <br>
                <?php if(!empty($siswa['file_kk']) && !empty($siswa['file_akta'])): ?>
                    <span style="color:green; font-weight:bold;"><i class="fas fa-check-circle"></i> SUDAH UPLOAD</span>
                <?php else: ?>
                    <span style="color:red; font-weight:bold;"><i class="fas fa-exclamation-circle"></i> BELUM LENGKAP</span>
                <?php endif; ?>
            </div>

            <div style="border:1px solid #ddd; padding:15px; border-radius:5px; text-align:center;">
                <i class="fas fa-trophy" style="font-size:30px; color:#28a745; margin-bottom:10px;"></i><br>
                Status Seleksi <br>
                <span style="background:#17a2b8; color:white; padding:2px 8px; border-radius:4px; font-size:12px;">
                    <?= strtoupper($siswa['status_pendaftaran'] ?? 'BELUM DAFTAR') ?>
                </span>
            </div>

        </div>
    </div>
    
    <div class="card">
        <h4><i class="fas fa-bullhorn"></i> Informasi Penting</h4>
        <ul>
            <li>Pastikan semua berkas diupload dengan jelas (bisa dibaca).</li>
            <li>Jika status masih "Belum Lengkap", silakan cek menu <b>Upload Berkas</b>.</li>
            <li>Pengumuman kelulusan akan diinfokan pada tanggal yang ditentukan dinas.</li>
        </ul>
    </div>

</div>

<?php include '../komponen/footer_umum.php'; ?>