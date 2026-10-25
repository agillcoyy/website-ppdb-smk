<?php
require_once '../konfigurasi/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_sekolah = mysqli_real_escape_string($koneksi, $_POST['id']);
    $nama_sekolah = mysqli_real_escape_string($koneksi, $_POST['nama_sekolah']);
    $jurusan_list = $_POST['jurusan']; // Array nama jurusan

    // 1. Update data sekolah
    mysqli_query($koneksi, "UPDATE sekolah SET nama_sekolah = '$nama_sekolah' WHERE id = '$id_sekolah'");

    // 2. Hapus relasi jurusan lama
    mysqli_query($koneksi, "DELETE FROM sekolah_jurusan WHERE id_sekolah = '$id_sekolah'");

    // 3. Simpan ulang jurusan baru (Tanpa Kuota)
    foreach ($jurusan_list as $val) {
        $nama_jur = mysqli_real_escape_string($koneksi, trim($val));

        // Cek master jurusan
        $cek = mysqli_query($koneksi, "SELECT id FROM jurusan WHERE nama_jurusan = '$nama_jur'");
        if (mysqli_num_rows($cek) == 0) {
            // Jika jurusan diketik manual dan belum ada di database
            mysqli_query($koneksi, "INSERT INTO jurusan (nama_jurusan) VALUES ('$nama_jur')");
            $id_jur = mysqli_insert_id($koneksi);
        } else {
            $id_jur = mysqli_fetch_array($cek)['id'];
        }

        // Simpan ke tabel sekolah_jurusan (Kolom kuota diabaikan/dihapus dari query)
        mysqli_query($koneksi, "INSERT INTO sekolah_jurusan (id_sekolah, id_jurusan) 
                                VALUES ('$id_sekolah', '$id_jur')");
    }

    echo "<script>alert('Berhasil Update Sekolah dan Daftar Jurusan!'); window.location='kelola_sekolah.php';</script>";
}
?>