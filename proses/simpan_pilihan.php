<?php
session_start();
require_once '../konfigurasi/koneksi.php';
require_once '../konfigurasi/url.php';
require_once '../fungsi/hitung_jarak.php';
require_once '../fungsi/upload_aman.php';

$id_siswa = $_POST['id_siswa'];
$jalur    = $_POST['jalur']; // TANGKAP DATA JALUR
$lat_siswa = $_POST['lat_siswa'];
$lng_siswa = $_POST['lng_siswa'];

// Validasi Jalur Afirmasi (Cek apakah punya KIP)
if ($jalur == 'AFIRMASI') {
    $cek_kip = mysqli_query($koneksi, "SELECT file_kip FROM siswa WHERE id='$id_siswa'");
    $d_kip = mysqli_fetch_assoc($cek_kip);
    if (empty($d_kip['file_kip'])) {
        echo "<script>alert('Untuk Jalur Afirmasi, Anda WAJIB upload KIP/PKH di menu Upload Berkas terlebih dahulu!'); window.history.back();</script>";
        exit();
    }
}

// ... (BAGIAN BAWAH SAMA, CUKUP UPDATE QUERY INSERT) ...

// Ambil inputan
$p1_id = $_POST['jurusan_1'];
$sekolah_1_id = $_POST['sekolah_1'];
$p2_id = !empty($_POST['jurusan_2']) ? $_POST['jurusan_2'] : NULL;
$sekolah_2_id = !empty($_POST['sekolah_2']) ? $_POST['sekolah_2'] : NULL;

// Validasi Jurusan (Logic sama seperti sebelumnya)
$q1 = mysqli_query($koneksi, "SELECT * FROM sekolah_jurusan WHERE id='$p1_id'");
$d1 = mysqli_fetch_assoc($q1);
$id_jurusan_asli_1 = $d1['id_jurusan'];

if ($p2_id) {
    $q2 = mysqli_query($koneksi, "SELECT * FROM sekolah_jurusan WHERE id='$p2_id'");
    $d2 = mysqli_fetch_assoc($q2);
    $id_jurusan_asli_2 = $d2['id_jurusan'];

    if ($sekolah_1_id == $sekolah_2_id && $id_jurusan_asli_1 == $id_jurusan_asli_2) {
         echo "<script>alert('Pilihan 1 dan 2 tidak boleh sama persis!'); window.history.back();</script>"; exit();
    }
}

// Hitung Jarak
$qs1 = mysqli_query($koneksi, "SELECT lat, lng FROM sekolah WHERE id='$sekolah_1_id'");
$ds1 = mysqli_fetch_assoc($qs1);
$jarak_1 = hitungJarak($lat_siswa, $lng_siswa, $ds1['lat'], $ds1['lng']);

$jarak_2 = 0;
if ($sekolah_2_id) {
    $qs2 = mysqli_query($koneksi, "SELECT lat, lng FROM sekolah WHERE id='$sekolah_2_id'");
    $ds2 = mysqli_fetch_assoc($qs2);
    $jarak_2 = hitungJarak($lat_siswa, $lng_siswa, $ds2['lat'], $ds2['lng']);
}

// Hitung Rerata Rapor
$q_nilai = mysqli_query($koneksi, "SELECT AVG(nilai_pengetahuan) as rerata FROM nilai_rapor WHERE id_siswa='$id_siswa'");
$d_nilai = mysqli_fetch_assoc($q_nilai);
$rerata_rapor = round($d_nilai['rerata'], 2);

// Upload Buta Warna
$file_bw = NULL;
if (isset($_FILES['file_buta_warna']) && $_FILES['file_buta_warna']['error'] == 0) {
    $nisn = $_SESSION['nisn'];
    $upload = uploadFile($_FILES['file_buta_warna'], PATH_KESEHATAN, $nisn . "_BUTAWARNA");
    if ($upload['status']) $file_bw = $upload['nama_file'];
}

// INSERT DATABASE (Tambahkan kolom jalur_pendaftaran)
$query = "INSERT INTO pendaftaran 
          (id_siswa, jalur_pendaftaran, pilihan_1_id_jurusan, pilihan_2_id_jurusan, 
           jarak_meter_pilihan_1, jarak_meter_pilihan_2, 
           rata_rata_rapor_total, file_buta_warna, status_verifikasi)
          VALUES 
          ('$id_siswa', '$jalur', '$p1_id', " . ($p2_id ? "'$p2_id'" : "NULL") . ", 
           '$jarak_1', '$jarak_2', 
           '$rerata_rapor', " . ($file_bw ? "'$file_bw'" : "NULL") . ", 'Menunggu')";

if (mysqli_query($koneksi, $query)) {
    echo "<script>alert('Pendaftaran Jalur $jalur Berhasil!'); window.location.href='../siswa/cetak_bukti.php';</script>";
} else {
    echo "Gagal: " . mysqli_error($koneksi);
}
?>