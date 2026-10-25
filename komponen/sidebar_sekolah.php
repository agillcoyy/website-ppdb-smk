<?php
$uri = $_SERVER['REQUEST_URI'];
?>
<div class="sidebar">
    <div class="sidebar-header">
        <h4>Panel Sekolah</h4>
        <p>Admin: <?= $_SESSION['nama'] ?></p>
    </div>
    <ul class="nav-links">
        <li>
            <a href="dashboard.php" class="<?= strpos($uri, 'dashboard.php') ? 'active' : '' ?>">
                Dashboard Statistik
            </a>
        </li>
        <li>
            <a href="verifikasi.php" class="<?= strpos($uri, 'verifikasi.php') ? 'active' : '' ?>">
                Verifikasi Berkas
            </a>
        </li>
        <li>
            <a href="input_nilai_tes.php" class="<?= strpos($uri, 'input_nilai_tes.php') ? 'active' : '' ?>">
                Input Nilai Tes Minat
            </a>
        </li>
        <li>
            <a href="data_pendaftar.php" class="<?= strpos($uri, 'data_pendaftar.php') ? 'active' : '' ?>">
                Data Semua Pendaftar
            </a>
        </li>
        <li>
            <a href="../akses/keluar.php" onclick="return confirm('Keluar dari panel admin?')">Logout</a>
        </li>
    </ul>
</div>