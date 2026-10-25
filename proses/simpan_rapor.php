<?php
session_start();
require_once '../konfigurasi/koneksi.php';
require_once '../konfigurasi/url.php';
require_once '../fungsi/upload_aman.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("location:../siswa/dashboard.php");
    exit();
}

$id_siswa = $_POST['id_siswa'];
$nisn = $_POST['nisn'];
$error_upload = [];

// Loop Semester 1 sampai 5
for ($smt = 1; $smt <= 5; $smt++) {
    
    $nilai = $_POST['nilai_' . $smt];
    $file_input_name = 'file_' . $smt;
    $file_lama = isset($_POST['file_lama_' . $smt]) ? $_POST['file_lama_' . $smt] : null;
    $nama_final_file = $file_lama;

    // Cek apakah ada file baru diupload
    if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] == 0) {
        
        // Format nama unik: NISN_SMT_1.pdf
        $format_nama = $nisn . "_SMT_" . $smt;
        
        // Panggil fungsi upload
        $hasil_upload = uploadFile($_FILES[$file_input_name], PATH_RAPOR, $format_nama);

        if ($hasil_upload['status']) {
            $nama_final_file = $hasil_upload['nama_file'];
        } else {
            $error_upload[] = "Semester $smt: " . $hasil_upload['pesan'];
            continue; // Lanjut ke semester berikutnya meski gagal (nanti ditampilkan errornya)
        }
    }

    // Simpan ke Database (Insert atau Update)
    // Cek dulu apakah data semester ini sudah ada
    $cek = mysqli_query($koneksi, "SELECT id FROM nilai_rapor WHERE id_siswa='$id_siswa' AND semester='$smt'");
    
    if (mysqli_num_rows($cek) > 0) {
        // UPDATE
        $sql = "UPDATE nilai_rapor SET 
                nilai_pengetahuan = '$nilai',
                file_rapor = '$nama_final_file' 
                WHERE id_siswa='$id_siswa' AND semester='$smt'";
    } else {
        // INSERT
        $sql = "INSERT INTO nilai_rapor (id_siswa, semester, nilai_pengetahuan, file_rapor) 
                VALUES ('$id_siswa', '$smt', '$nilai', '$nama_final_file')";
    }

    mysqli_query($koneksi, $sql);
}

// Redirect dengan pesan
if (count($error_upload) > 0) {
    $pesan_error = implode(", ", $error_upload);
    echo "<script>alert('Beberapa file gagal diupload: $pesan_error'); window.location.href='../siswa/isi_rapor.php';</script>";
} else {
    echo "<script>alert('Berhasil! Nilai Rapor Semester 1-5 tersimpan.'); window.location.href='../siswa/upload_berkas.php';</script>";
}
?>