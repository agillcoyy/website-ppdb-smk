<?php
session_start();
require_once '../konfigurasi/koneksi.php';
require_once '../konfigurasi/url.php';

if ($_SESSION['role'] != 'dinas') { header("location:../akses/login.php"); exit(); }

// --- HAPUS USER ---
if (isset($_GET['hapus_id'])) {
    $id = $_GET['hapus_id'];
    mysqli_query($koneksi, "DELETE FROM admin WHERE id='$id'");
    echo "<script>alert('User Berhasil Dihapus'); window.location.href='kelola_pengguna.php';</script>";
}

// --- TAMBAH USER ---
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $user = $_POST['username'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $id_sekolah = $_POST['id_sekolah'];

    $q = mysqli_query($koneksi, "INSERT INTO admin (nama_lengkap, username, password, role, id_sekolah) VALUES ('$nama', '$user', '$pass', 'sekolah', '$id_sekolah')");
    if($q) echo "<script>alert('Admin Sekolah Ditambahkan'); window.location.href='kelola_pengguna.php';</script>";
}

$sekolah_list = mysqli_query($koneksi, "SELECT * FROM sekolah ORDER BY nama_sekolah ASC");
$admin_list = mysqli_query($koneksi, "SELECT a.*, s.nama_sekolah FROM admin a LEFT JOIN sekolah s ON a.id_sekolah = s.id WHERE a.role='sekolah' ORDER BY s.nama_sekolah ASC");

include '../komponen/header_umum.php';
include '../komponen/sidebar_dinas.php';
?>

<div class="content-area">
    <h3>Manajemen User (Operator Sekolah)</h3>
    
    <div class="card">
        <h4>Buat Akun Operator Baru</h4>
        <form method="POST">
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
                <div class="form-group">
                    <label>Pilih Sekolah</label>
                    <select name="id_sekolah" class="form-control" required>
                        <option value="">- Pilih -</option>
                        <?php foreach($sekolah_list as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= $s['nama_sekolah'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Nama Operator</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="text" name="password" class="form-control" required>
                </div>
            </div>
            <button type="submit" name="tambah" class="btn-save">Buat Akun</button>
        </form>
    </div>

    <div class="card">
        <h4>Daftar Operator Terdaftar</h4>
        <table border="1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Sekolah</th>
                    <th>Nama Operator</th>
                    <th>Username</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no=1; while($row = mysqli_fetch_assoc($admin_list)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $row['nama_sekolah'] ?></td>
                    <td><?= $row['nama_lengkap'] ?></td>
                    <td><?= $row['username'] ?></td>
                    <td>
                        <a href="edit_pengguna.php?id=<?= $row['id'] ?>" class="btn-save" style="background:#ffc107; color:black; padding:5px 10px; font-size:12px; text-decoration:none;">
                            <i class="fas fa-key"></i> Ganti Password
                        </a>
                        <a href="kelola_pengguna.php?hapus_id=<?= $row['id'] ?>" onclick="return confirm('Hapus user ini?')" class="btn-save" style="background:#dc3545; padding:5px 10px; font-size:12px; text-decoration:none;">
                            <i class="fas fa-trash"></i> Hapus
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include '../komponen/footer_umum.php'; ?>