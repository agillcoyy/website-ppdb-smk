<?php
session_start();
require_once '../konfigurasi/koneksi.php';
require_once '../konfigurasi/url.php';

if ($_SESSION['role'] != 'dinas') { header("location:../akses/login.php"); exit(); }

$id = $_GET['id'];
$q = mysqli_query($koneksi, "SELECT a.*, s.nama_sekolah FROM admin a LEFT JOIN sekolah s ON a.id_sekolah = s.id WHERE a.id='$id'");
$data = mysqli_fetch_assoc($q);

if (isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $new_pass = $_POST['password'];
    
    // Logic Update: Jika password diisi, update password. Jika kosong, skip.
    if (!empty($new_pass)) {
        $hash = password_hash($new_pass, PASSWORD_DEFAULT);
        $query = "UPDATE admin SET nama_lengkap='$nama', password='$hash' WHERE id='$id'";
    } else {
        $query = "UPDATE admin SET nama_lengkap='$nama' WHERE id='$id'";
    }
    
    if(mysqli_query($koneksi, $query)) {
        echo "<script>alert('Data User Diperbarui'); window.location.href='kelola_pengguna.php';</script>";
    }
}

include '../komponen/header_umum.php';
include '../komponen/sidebar_dinas.php';
?>

<div class="content-area">
    <h3>Edit Operator Sekolah</h3>
    
    <div class="card">
        <h4>Operator: <?= $data['nama_sekolah'] ?></h4>
        <form method="POST">
            <div class="form-group">
                <label>Username (Tidak bisa diubah)</label>
                <input type="text" class="form-control" value="<?= $data['username'] ?>" readonly style="background:#eee;">
            </div>
            
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" value="<?= $data['nama_lengkap'] ?>" required>
            </div>
            
            <div class="form-group">
                <label>Password Baru (Isi jika ingin mengganti)</label>
                <input type="text" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
            </div>
            
            <button type="submit" name="update" class="btn-save">Simpan Perubahan</button>
            <a href="kelola_pengguna.php" class="btn-save" style="background:#6c757d; text-decoration:none;">Kembali</a>
        </form>
    </div>
</div>
<?php include '../komponen/footer_umum.php'; ?>