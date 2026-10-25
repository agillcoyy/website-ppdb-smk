<?php 
// Pastikan BASE_URL ada
if (!defined('BASE_URL')) {
    if (file_exists(__DIR__ . '/../konfigurasi/url.php')) {
        require_once __DIR__ . '/../konfigurasi/url.php';
    }
}

// Ambil URL saat ini untuk menandai menu aktif
$uri = $_SERVER['REQUEST_URI'];
?>

<div class="sidebar">
    <div class="sidebar-header" style="text-align: center; padding: 20px;">
        <img src="<?= BASE_URL ?>aset/img/logo-smk.png" width="60" style="margin-bottom:10px; display:block; margin: 0 auto;">
        
        <h5 style="color: white; margin-top: 10px;">PANEL SISWA</h5>
        <small style="color: #ddd;">PPDB Online</small>
    </div>

    <ul class="nav-links">
        <li>
            <a href="<?= BASE_URL ?>siswa/dashboard.php" class="<?= strpos($uri, 'dashboard.php') ? 'active' : '' ?>">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>siswa/isi_biodata.php" class="<?= strpos($uri, 'isi_biodata.php') ? 'active' : '' ?>">
                <i class="fas fa-user-edit"></i> Isi Biodata
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>siswa/isi_rapor.php" class="<?= strpos($uri, 'isi_rapor.php') ? 'active' : '' ?>">
                <i class="fas fa-book"></i> Input Rapor
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>siswa/upload_berkas.php" class="<?= strpos($uri, 'upload_berkas.php') ? 'active' : '' ?>">
                <i class="fas fa-upload"></i> Upload Berkas
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>peta.php">
                <i class="fas fa-map-marked-alt"></i> Peta Lokasi
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>siswa/pilih_jurusan.php" class="<?= strpos($uri, 'pilih_jurusan.php') ? 'active' : '' ?>">
                <i class="fas fa-graduation-cap"></i> Pilih Jurusan
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>siswa/pengumuman.php" class="<?= strpos($uri, 'pengumuman.php') ? 'active' : '' ?>">
                <i class="fas fa-bullhorn"></i> Pengumuman
            </a>
        </li>
        
        <li style="margin-top:20px; border-top:1px solid rgba(255,255,255,0.1);">
            <a href="<?= BASE_URL ?>akses/keluar.php" onclick="return confirm('Yakin ingin keluar?')" style="color: #ff8080;">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </li>
    </ul>
</div>