<?php
// Set Timezone agar waktu pendaftaran sesuai WIB
date_default_timezone_set('Asia/Jakarta');

$hostname = "localhost";
$username = "root";
$password = "";       
$database = "sig";   
$koneksi = mysqli_connect($hostname, $username, $password, $database);

if (!$koneksi) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}
?>