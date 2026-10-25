<?php
require_once '../konfigurasi/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_sekolah = mysqli_real_escape_string($koneksi, $_POST['nama_sekolah']);
    $alamat       = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $lat          = mysqli_real_escape_string($koneksi, $_POST['lat']);
    $lng          = mysqli_real_escape_string($koneksi, $_POST['lng']);
    $jurusan_list = $_POST['jurusan']; 

    // Upload Foto
    $foto = "smk-bg.jpeg";
    if (!empty($_FILES['gambar']['name'])) {
        $foto = time() . "_" . $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], "../aset/img/" . $foto);
    }

    // 1. Simpan Data Sekolah
    $q_sekolah = mysqli_query($koneksi, "INSERT INTO sekolah (nama_sekolah, alamat, lat, lng, foto) 
                                         VALUES ('$nama_sekolah', '$alamat', '$lat', '$lng', '$foto')");
    $id_sekolah = mysqli_insert_id($koneksi);

    if ($id_sekolah) {
        // 2. Simpan Daftar Jurusan
        foreach ($jurusan_list as $val) {
            $nama_jurusan = mysqli_real_escape_string($koneksi, trim($val));
            if (empty($nama_jurusan)) continue;

            // Cek apakah jurusan sudah ada di master
            $cek = mysqli_query($koneksi, "SELECT id FROM jurusan WHERE nama_jurusan = '$nama_jurusan'");
            if (mysqli_num_rows($cek) == 0) {
                // Tambah ke master jika baru
                mysqli_query($koneksi, "INSERT INTO jurusan (nama_jurusan) VALUES ('$nama_jurusan')");
                $id_jurusan = mysqli_insert_id($koneksi);
            } else {
                $id_jurusan = mysqli_fetch_array($cek)['id'];
            }

            // Simpan relasi ke sekolah_jurusan
            mysqli_query($koneksi, "INSERT INTO sekolah_jurusan (id_sekolah, id_jurusan) 
                                    VALUES ('$id_sekolah', '$id_jurusan')");
        }

        echo "<script>alert('Berhasil Menambah Sekolah & Daftar Jurusan!'); window.location='kelola_sekolah.php';</script>";
    } else {
        echo "<script>alert('Gagal simpan data!'); window.history.back();</script>";
    }
}
?>