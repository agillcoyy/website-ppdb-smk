<?php
session_start();
require_once '../konfigurasi/koneksi.php';
require_once '../konfigurasi/url.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'dinas') { 
    header("location:../akses/login.php"); 
    exit(); 
}

// --- PROSES HAPUS SEKOLAH ---
if (isset($_GET['hapus_id'])) {
    $id_hapus = $_GET['hapus_id'];
    // Hapus data terkait (opsional: tambahkan logika hapus jurusan/admin jika perlu)
    $q_hapus = mysqli_query($koneksi, "DELETE FROM sekolah WHERE id='$id_hapus'");
    if($q_hapus) {
        echo "<script>alert('Data Sekolah Berhasil Dihapus!'); window.location.href='kelola_sekolah.php';</script>";
    } else {
        echo "<script>alert('Gagal Hapus! Masih ada data terkait.'); window.location.href='kelola_sekolah.php';</script>";
    }
}

// --- PROSES TAMBAH SEKOLAH ---
if (isset($_POST['tambah_sekolah'])) {
    $npsn = $_POST['npsn'];
    $nama = $_POST['nama_sekolah'];
    $alamat = $_POST['alamat'];
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    
    // Cek duplikat NPSN
    $cek = mysqli_query($koneksi, "SELECT id FROM sekolah WHERE npsn='$npsn'");
    if(mysqli_num_rows($cek) > 0){
        echo "<script>alert('Gagal: NPSN sudah terdaftar!');</script>";
    } else {
        $q = mysqli_query($koneksi, "INSERT INTO sekolah (npsn, nama_sekolah, alamat, lat, lng) VALUES ('$npsn','$nama','$alamat','$lat','$lng')");
        if($q) echo "<script>alert('Sekolah Berhasil Ditambah'); window.location.href='kelola_sekolah.php';</script>";
    }
}

// --- AMBIL DATA SEKOLAH ---
$sekolah = mysqli_query($koneksi, "SELECT * FROM sekolah ORDER BY nama_sekolah ASC");

// KUNCI AGAR CSS MUNCUL
$lokasi_folder = "../";

include '../komponen/header_umum.php';
include '../komponen/sidebar_dinas.php';
?>

<div class="content-area">
    <h3>Kelola Data Sekolah</h3>
    
    <button onclick="bukaForm()" class="btn-save" style="margin-bottom: 20px;">
        <i class="fas fa-plus-circle"></i> TAMBAH SEKOLAH BARU
    </button>

    <div id="form-tambah" class="card" style="display:none; border-left: 5px solid #28a745; background-color: #f9fff9;">
        <h4 style="color:#006400;"><i class="fas fa-school"></i> Form Tambah Sekolah</h4>
        <hr>
        <form method="POST">
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px;">
                <div class="form-group">
                    <label>NPSN (Nomor Pokok Sekolah Nasional)</label>
                    <input type="text" name="npsn" class="form-control" required placeholder="Contoh: 10203040">
                </div>
                <div class="form-group">
                    <label>Nama Sekolah</label>
                    <input type="text" name="nama_sekolah" class="form-control" required placeholder="Contoh: SMK Negeri 1 Padang">
                </div>
                <div class="form-group">
                    <label>Latitude (Koordinat)</label>
                    <input type="text" name="lat" class="form-control" required placeholder="-0.xxxxxx">
                </div>
                <div class="form-group">
                    <label>Longitude (Koordinat)</label>
                    <input type="text" name="lng" class="form-control" required placeholder="100.xxxxxx">
                </div>
            </div>
            <div class="form-group">
                <label>Alamat Lengkap</label>
                <textarea name="alamat" class="form-control" rows="2" required></textarea>
            </div>
            
            <div style="margin-top:15px;">
                <button type="submit" name="tambah_sekolah" class="btn-save">
                    <i class="fas fa-save"></i> Simpan Data
                </button>
                <button type="button" onclick="tutupForm()" class="btn-save" style="background:#6c757d; margin-left:10px;">
                    <i class="fas fa-times"></i> Batal
                </button>
            </div>
        </form>
    </div>

    <div class="card">
        <h4><i class="fas fa-list"></i> Daftar Sekolah Terdaftar</h4>
        
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">NPSN</th>
                    <th>Nama Sekolah</th>
                    <th>Alamat</th>
                    <th width="20%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no=1; while($row = mysqli_fetch_assoc($sekolah)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><span style="background:#eef; padding:3px 8px; border-radius:4px; font-weight:bold; color:#006400;"><?= $row['npsn'] ?></span></td>
                    <td><strong><?= $row['nama_sekolah'] ?></strong></td>
                    <td><?= $row['alamat'] ?></td>
                    <td>
                        <a href="edit_sekolah.php?id=<?= $row['id'] ?>" class="btn-save" style="background:#ffc107; color:black; padding:5px 10px; font-size:12px;">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="kelola_sekolah.php?hapus_id=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus sekolah ini? Hati-hati, data terkait bisa hilang!')" class="btn-save" style="background:#dc3545; padding:5px 10px; font-size:12px; margin-left:5px;">
                            <i class="fas fa-trash"></i> Hapus
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function bukaForm() { document.getElementById('form-tambah').style.display = 'block'; }
function tutupForm() { document.getElementById('form-tambah').style.display = 'none'; }
</script>

<?php include '../komponen/footer_umum.php'; ?>