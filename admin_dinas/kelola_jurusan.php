<?php
session_start();
require_once '../konfigurasi/koneksi.php';
require_once '../konfigurasi/url.php';

if ($_SESSION['role'] != 'dinas') { header("location:../akses/login.php"); exit(); }

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $bw = $_POST['bw'];
    mysqli_query($koneksi, "INSERT INTO jurusan (nama_jurusan, butuh_buta_warna) VALUES ('$nama','$bw')");
    echo "<script>window.location.href='kelola_jurusan.php';</script>";
}

$data = mysqli_query($koneksi, "SELECT * FROM jurusan ORDER BY nama_jurusan ASC");

include '../komponen/header_umum.php';
include '../komponen/sidebar_dinas.php';
?>

<div class="content-area">
    <h3>Master Data Jurusan</h3>
    <div class="card">
        <form method="POST" style="display:flex; gap:10px;">
            <input type="text" name="nama" class="form-control" placeholder="Nama Jurusan Baru (Contoh: TKJ)" required style="flex:2;">
            <select name="bw" class="form-control" style="flex:1;">
                <option value="TIDAK">Boleh Buta Warna</option>
                <option value="YA">Larang Buta Warna</option>
            </select>
            <button type="submit" name="simpan" class="btn-save">Tambah</button>
        </form>
    </div>

    <div class="card">
        <table>
            <tr><th>No</th><th>Nama Jurusan</th><th>Syarat Buta Warna</th></tr>
            <?php $no=1; while($r=mysqli_fetch_assoc($data)): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $r['nama_jurusan'] ?></td>
                <td><?= ($r['butuh_buta_warna']=='YA') ? '<span style="color:red">Wajib Sehat Mata</span>' : 'Normal' ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>
<?php include '../komponen/footer_umum.php'; ?>