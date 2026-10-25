<?php
function uploadFile($file, $tujuan, $format_nama_baru) {
    // 1. Cek Error Upload
    if ($file['error'] !== 0) {
        return ["status" => false, "pesan" => "Terjadi error saat upload data."];
    }

    // 2. Cek Ekstensi (Hanya Gambar & PDF)
    $ekstensi_diizinkan = ['jpg', 'jpeg', 'png', 'pdf'];
    $nama_file = $file['name'];
    $x = explode('.', $nama_file);
    $ekstensi = strtolower(end($x));

    if (!in_array($ekstensi, $ekstensi_diizinkan)) {
        return ["status" => false, "pesan" => "Format file tidak valid! Hanya JPG, PNG, atau PDF."];
    }

    // 3. Cek Ukuran (Maks 2MB)
    if ($file['size'] > 2000000) {
        return ["status" => false, "pesan" => "Ukuran file terlalu besar! Maksimal 2MB."];
    }

    // 4. Generate Nama Baru (Agar tidak duplikat/tertimpa)
    // Contoh: 0054321_RAPOR_1.pdf
    $nama_baru = $format_nama_baru . '.' . $ekstensi;
    $target_path = $tujuan . $nama_baru;

    // 5. Pindahkan File
    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        return ["status" => true, "nama_file" => $nama_baru];
    } else {
        return ["status" => false, "pesan" => "Gagal memindahkan file ke server."];
    }
}
?>