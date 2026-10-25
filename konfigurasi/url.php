<?php
// -------------------------------------------------------------------------
// KONFIGURASI SEDERHANA (HANYA UNTUK MENCEGAH ERROR)
// -------------------------------------------------------------------------

// Kita set BASE_URL manual saja agar aman. 
// Jika folder anda namanya 'website_ppdb', biarkan seperti ini.
// Jika nanti di hosting/domain, ganti jadi "https://namadomain.com/"
define('BASE_URL', 'http://localhost/website_ppdb/');

define('APP_NAME', 'PPDB SMK Kota Padang 2025');

// PATH UNTUK UPLOAD FILE (JANGAN DIHAPUS, PENTING UNTUK UPLOAD)
if (!defined('PATH_RAPOR')) {
    define('PATH_PROFIL', __DIR__ . '/../unggahan/profil/');
    define('PATH_RAPOR', __DIR__ . '/../unggahan/rapor/');
    define('PATH_PRESTASI', __DIR__ . '/../unggahan/prestasi/');
    define('PATH_DOKUMEN', __DIR__ . '/../unggahan/dokumen/');
    define('PATH_KESEHATAN', __DIR__ . '/../unggahan/kesehatan/');
}
?>