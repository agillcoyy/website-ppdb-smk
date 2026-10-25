<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}

// Logika Path Folder (Agar CSS terbaca dari folder manapun)
// Jika $lokasi_folder tidak di-set di file utama, default ke '../'
$path = isset($lokasi_folder) ? $lokasi_folder : '../';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPDB SMK PADANG</title>
    
    <link rel="stylesheet" href="<?= $path ?>aset/css/gaya_admin.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <nav class="top-navbar">
        <div class="brand-logo">
            <button class="menu-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <span style="margin-left: 15px; font-weight:bold;">PPDB SMK PADANG</span>
        </div>
        
        <div class="date-info" style="margin-left:auto; font-size: 14px;">
            <i class="fas fa-calendar-alt"></i> <?= date('d M Y') ?>
        </div>
    </nav>

    <div class="main-wrapper">