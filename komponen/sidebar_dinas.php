<?php $uri = $_SERVER['REQUEST_URI']; ?>
<div class="sidebar">
    <div class="sidebar-header">
        <img src="<?= defined('BASE_URL') ? BASE_URL : '../' ?>aset/img/logo-kotapadang.png" width="50" style="margin-bottom:10px;">
        <h4>PANEL DINAS</h4>
        <p><i class="fas fa-user-tie"></i> <?= isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Admin' ?></p>
    </div>
    <ul class="nav-links">
        <li>
            <a href="dashboard.php" class="<?= strpos($uri, 'dashboard.php') ? 'active' : '' ?>">
                <i class="fas fa-tachometer-alt" style="width:25px;"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="kelola_sekolah.php" class="<?= strpos($uri, 'kelola_sekolah.php') ? 'active' : '' ?>">
                <i class="fas fa-school" style="width:25px;"></i> Data Sekolah
            </a>
        </li>
        <li>
            <a href="kelola_pengguna.php" class="<?= strpos($uri, 'kelola_pengguna.php') ? 'active' : '' ?>">
                <i class="fas fa-users-cog" style="width:25px;"></i> Manajemen User
            </a>
        </li>
        <li>
            <a href="ranking.php" class="<?= strpos($uri, 'ranking.php') ? 'active' : '' ?>">
                <i class="fas fa-trophy" style="width:25px;"></i> Lihat Perangkingan
            </a>
        </li>
        <li style="margin-top:20px; border-top:1px solid #34495e;">
            <a href="../akses/keluar.php" onclick="return confirm('Yakin ingin keluar dari sistem?')" style="color:#e74c3c;">
                <i class="fas fa-sign-out-alt" style="width:25px;"></i> Logout
            </a>
        </li>
    </ul>
</div>