<?php
session_start();
require_once '../konfigurasi/url.php';
require_once '../konfigurasi/koneksi.php';

// Cek sesi
if (!isset($_SESSION['status']) || $_SESSION['role'] != 'siswa') {
    header("location:../akses/login.php");
    exit();
}

$id_siswa = $_SESSION['id_user'];
$query = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id='$id_siswa'");
$data = mysqli_fetch_assoc($query);

// Variabel path untuk header (PENTING AGAR CSS MUNCUL)
$lokasi_folder = "../";

include '../komponen/header_umum.php';
include '../komponen/sidebar_siswa.php';
?>

<div class="content-area">
    <h3>Lengkapi Biodata Diri</h3>
    
    <div class="alert-box">
        <i class="fas fa-info-circle"></i> &nbsp;
        Silakan lengkapi data diri Anda dengan benar sesuai Ijazah dan KK. Data Koordinat (Lokasi) wajib diisi untuk perhitungan jarak zonasi.
    </div>

    <div class="card">
        <form action="../proses/simpan_biodata.php" method="POST">
            <input type="hidden" name="id_siswa" value="<?= $data['id'] ?>">
            
            <div class="form-group">
                <label>NISN (Tidak bisa diubah)</label>
                <input type="text" class="form-control" value="<?= $data['nisn'] ?>" readonly>
            </div>

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" value="<?= $data['nama_lengkap'] ?>" required>
            </div>

            <div class="form-group">
                <label>NIK (Nomor Induk Kependudukan)</label>
                <input type="number" name="nik" class="form-control" value="<?= $data['nik'] ?>" required placeholder="Lihat di KK">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" class="form-control" value="<?= $data['tempat_lahir'] ?>" required>
                </div>
                <div class="form-group">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="tgl_lahir" class="form-control" value="<?= $data['tgl_lahir'] ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label>Jenis Kelamin</label>
                <select name="jk" class="form-control" required>
                    <option value="">- Pilih -</option>
                    <option value="L" <?= ($data['jenis_kelamin'] == 'L') ? 'selected' : '' ?>>Laki-laki</option>
                    <option value="P" <?= ($data['jenis_kelamin'] == 'P') ? 'selected' : '' ?>>Perempuan</option>
                </select>
            </div>

            <div class="form-group">
                <label>Nomor HP / WhatsApp</label>
                <input type="text" name="no_hp" class="form-control" value="<?= $data['no_hp'] ?>" required placeholder="Contoh: 08123456789">
            </div>

            <div class="form-group">
                <label>Alamat Lengkap (Sesuai KK)</label>
                <textarea name="alamat" class="form-control" rows="3" required><?= $data['alamat_rumah'] ?></textarea>
            </div>

            <hr style="border: 0; border-top: 1px dashed #ccc; margin: 20px 0;">

            <h4 style="color:#006400; margin-bottom:15px;"><i class="fas fa-map-marker-alt"></i> Titik Koordinat Rumah</h4>
            
            <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; border: 1px solid #ddd;">
                <p style="font-size: 13px; margin-bottom: 15px; color: #555;">
                    Klik tombol di bawah ini untuk mengisi Latitude & Longitude secara otomatis.
                </p>
                
                <button type="button" onclick="ambilLokasi()" class="btn-save" style="background: #ff9800; margin-bottom:15px;">
                    <i class="fas fa-crosshairs"></i> AMBIL LOKASI SAYA
                </button>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Latitude</label>
                        <input type="text" name="lat" id="lat" class="form-control" value="<?= $data['lat'] ?>" required placeholder="-0.xxxxxx">
                    </div>

                    <div class="form-group">
                        <label>Longitude</label>
                        <input type="text" name="lng" id="lng" class="form-control" value="<?= $data['lng'] ?>" required placeholder="100.xxxxxx">
                    </div>
                </div>
                <small id="lokasi-status" style="color: #006400; font-weight: bold;"></small>
            </div>
            
            <br><br>
            <button type="submit" class="btn-save" style="width: 100%;">
                <i class="fas fa-save"></i> SIMPAN PERUBAHAN BIODATA
            </button>
        </form>
    </div>
</div>

<script>
function ambilLokasi() {
    var status = document.getElementById("lokasi-status");
    
    if (navigator.geolocation) {
        status.innerHTML = "Mendeteksi lokasi...";
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else {
        alert("Geolocation tidak didukung browser ini.");
    }
}

function showPosition(position) {
    document.getElementById("lat").value = position.coords.latitude;
    document.getElementById("lng").value = position.coords.longitude;
    document.getElementById("lokasi-status").innerHTML = "Lokasi ditemukan!";
}

function showError(error) {
    document.getElementById("lokasi-status").innerHTML = "Gagal mengambil lokasi.";
}
</script>

<?php include '../komponen/footer_umum.php'; ?>