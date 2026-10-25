<?php
session_start();
require_once '../konfigurasi/url.php';
require_once '../konfigurasi/koneksi.php';

// 1. Cek Login
if (!isset($_SESSION['status']) || $_SESSION['role'] != 'siswa') {
    header("location:../akses/login.php"); exit();
}

$id_siswa = $_SESSION['id_user'];

// -----------------------------------------------------------
// PERBAIKAN LOGIKA CEK KELENGKAPAN (REAL-TIME CHECK)
// -----------------------------------------------------------

// A. Ambil Data Siswa Terbaru
$q_siswa = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id='$id_siswa'");
$d_siswa = mysqli_fetch_assoc($q_siswa);

// B. Cek Biodata (NIK & Koordinat Wajib)
if (empty($d_siswa['nik']) || empty($d_siswa['lat']) || empty($d_siswa['lng'])) {
    echo "<script>alert('Gagal: Biodata belum lengkap! Mohon isi NIK dan Koordinat Lokasi dulu.'); window.location.href='isi_biodata.php';</script>";
    exit();
}

// C. Cek Berkas Wajib (KK & Akta)
// Catatan: KIP & Buta Warna itu opsional / menyusul, jadi tidak dicek disini.
if (empty($d_siswa['file_kk']) || empty($d_siswa['file_akta'])) {
    echo "<script>alert('Gagal: Berkas Wajib (KK dan Akta Kelahiran) belum diupload!'); window.location.href='upload_berkas.php';</script>";
    exit();
}

// D. Cek Nilai Rapor (Harus sudah diisi minimal Semester 1)
$q_rapor = mysqli_query($koneksi, "SELECT id FROM nilai_rapor WHERE id_siswa='$id_siswa'");
if (mysqli_num_rows($q_rapor) == 0) {
    echo "<script>alert('Gagal: Nilai Rapor belum diisi! Silakan input nilai rapor semester 1-5.'); window.location.href='isi_rapor.php';</script>";
    exit();
}

// -----------------------------------------------------------
// AKHIR PERBAIKAN LOGIKA
// -----------------------------------------------------------


// Cek Apakah Sudah Pernah Daftar?
$q_daftar = mysqli_query($koneksi, "SELECT * FROM pendaftaran WHERE id_siswa='$id_siswa'");
if (mysqli_num_rows($q_daftar) > 0) {
    echo "<script>alert('Anda sudah melakukan pendaftaran! Tidak bisa mendaftar dua kali.'); window.location.href='cetak_bukti.php';</script>";
    exit();
}

// Ambil Data Sekolah
$sekolah = mysqli_query($koneksi, "SELECT * FROM sekolah ORDER BY nama_sekolah ASC");

// Ambil Data Jurusan & Kuota
$sekolah_jurusan = mysqli_query($koneksi, "
    SELECT sj.id, sj.id_sekolah, j.nama_jurusan, j.butuh_buta_warna 
    FROM sekolah_jurusan sj 
    JOIN jurusan j ON sj.id_jurusan = j.id
    WHERE sj.kuota > 0
");

$data_jurusan = [];
while ($row = mysqli_fetch_assoc($sekolah_jurusan)) {
    $data_jurusan[] = $row;
}

// Variabel path untuk CSS Header
$lokasi_folder = "../";

include '../komponen/header_umum.php';
include '../komponen/sidebar_siswa.php';
?>

<div class="content-area">
    <h3>Formulir Pilihan Sekolah & Jurusan</h3>
    
    <div class="alert-box">
        <strong>PENTING:</strong> 
        Pastikan pilihan Anda sudah final. Data tidak dapat diubah setelah tombol Simpan ditekan.
        <ul>
            <li><strong>REGULER:</strong> Seleksi umum (Gabungan Nilai Rapor & Jarak).</li>
            <li><strong>AFIRMASI:</strong> Wajib punya KIP/KKS/PKH (Upload Bukti di menu Upload Berkas).</li>
            <li><strong>PRESTASI:</strong> Wajib punya Sertifikat Juara.</li>
        </ul>
    </div>

    <div class="card">
        <form action="../proses/simpan_pilihan.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_siswa" value="<?= $id_siswa ?>">
            <input type="hidden" name="lat_siswa" value="<?= $d_siswa['lat'] ?>">
            <input type="hidden" name="lng_siswa" value="<?= $d_siswa['lng'] ?>">

            <div class="form-group" style="background: #e9ecef; padding: 15px; border-radius: 5px;">
                <label>Jalur Pendaftaran <span style="color:red">*</span></label>
                <select name="jalur" class="form-control" required>
                    <option value="">- Pilih Jalur -</option>
                    <option value="REGULER">Jalur Reguler (Umum)</option>
                    <option value="AFIRMASI">Jalur Afirmasi (KIP/Keluarga Tidak Mampu)</option>
                    <option value="PRESTASI">Jalur Prestasi (Lomba/Juara)</option>
                </select>
            </div>
            <br>

            <h4>Pilihan Prioritas 1 (Wajib)</h4>
            <div class="form-group">
                <label>Sekolah Tujuan</label>
                <select name="sekolah_1" id="sekolah_1" class="form-control" required onchange="loadJurusan(1)">
                    <option value="">- Pilih Sekolah -</option>
                    <?php foreach ($sekolah as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= $s['nama_sekolah'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Konsentrasi Keahlian (Jurusan)</label>
                <select name="jurusan_1" id="jurusan_1" class="form-control" required onchange="cekButaWarna()">
                    <option value="">- Pilih Sekolah Dulu -</option>
                </select>
            </div>

            <hr>

            <h4>Pilihan Prioritas 2 (Opsional)</h4>
            <div class="form-group">
                <label>Sekolah Tujuan</label>
                <select name="sekolah_2" id="sekolah_2" class="form-control" onchange="loadJurusan(2)">
                    <option value="">- Tidak Memilih / Sama dengan Pilihan 1 -</option>
                    <?php 
                    // Reset pointer data sekolah agar bisa di-loop lagi
                    mysqli_data_seek($sekolah, 0);
                    foreach ($sekolah as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= $s['nama_sekolah'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Konsentrasi Keahlian (Jurusan)</label>
                <select name="jurusan_2" id="jurusan_2" class="form-control" onchange="cekButaWarna()">
                    <option value="">- Pilih Sekolah Dulu -</option>
                </select>
            </div>

            <div id="box-buta-warna" style="display:none; background: #fff3cd; padding: 15px; margin-top: 15px; border:1px solid #ffeeba; border-radius:5px;">
                <label style="color:#856404;"><strong><i class="fas fa-exclamation-triangle"></i> Syarat Khusus: Tidak Buta Warna</strong></label>
                <p style="font-size:13px; margin:5px 0;">
                    Salah satu jurusan yang Anda pilih mewajibkan siswa <strong>Tidak Buta Warna</strong>. 
                    Silakan upload Surat Keterangan Dokter (Jika belum upload di menu Berkas, bisa upload disini).
                </p>
                
                <?php if(!empty($d_siswa['file_buta_warna'])): ?>
                    <div style="color:green; font-size:13px; margin-bottom:10px;">
                        <i class="fas fa-check-circle"></i> Anda sudah mengupload file: <b><?= $d_siswa['file_buta_warna'] ?></b>. (Tidak perlu upload ulang jika file sama).
                    </div>
                    <input type="file" name="file_buta_warna" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    <small>Biarkan kosong jika ingin menggunakan file yang sudah ada.</small>
                <?php else: ?>
                    <input type="file" name="file_buta_warna" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    <small style="color:red;">*Wajib Upload</small>
                <?php endif; ?>
            </div>

            <br><br>
            <button type="submit" class="btn-save" style="width:100%; padding:15px; font-size:16px;" onclick="return confirm('Apakah Anda yakin dengan pilihan ini? Data tidak bisa diubah setelah disimpan.')">
                <i class="fas fa-paper-plane"></i> SIMPAN PENDAFTARAN
            </button>
        </form>
    </div>
</div>

<script>
// Data Jurusan dari PHP ke JS
const dataJurusan = <?= json_encode($data_jurusan) ?>;

function loadJurusan(level) {
    const idSekolah = document.getElementById('sekolah_' + level).value;
    const selectJurusan = document.getElementById('jurusan_' + level);
    
    // Reset Opsi
    selectJurusan.innerHTML = '<option value="">- Pilih Jurusan -</option>';
    
    if (idSekolah) {
        // Filter jurusan sesuai sekolah yang dipilih
        const filtered = dataJurusan.filter(item => item.id_sekolah == idSekolah);
        
        if(filtered.length === 0) {
             selectJurusan.innerHTML = '<option value="">- Jurusan Penuh / Tidak Ada -</option>';
        } else {
            filtered.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id; // Ini ID dari tabel sekolah_jurusan
                option.text = item.nama_jurusan;
                option.setAttribute('data-bw', item.butuh_buta_warna);
                selectJurusan.appendChild(option);
            });
        }
    }
    // Cek ulang buta warna setiap ganti sekolah
    cekButaWarna();
}

function cekButaWarna() {
    const j1 = document.getElementById('jurusan_1');
    const j2 = document.getElementById('jurusan_2');
    let butuh = false;
    
    // Cek Pilihan 1
    if (j1.selectedIndex > 0) {
        const opt1 = j1.options[j1.selectedIndex];
        if (opt1.getAttribute('data-bw') === 'YA') butuh = true;
    }
    
    // Cek Pilihan 2
    if (j2.selectedIndex > 0) {
        const opt2 = j2.options[j2.selectedIndex];
        if (opt2.getAttribute('data-bw') === 'YA') butuh = true;
    }

    const box = document.getElementById('box-buta-warna');
    const inputFile = document.querySelector('input[name="file_buta_warna"]');
    
    // Cek apakah user sudah punya file di database (variabel PHP disisipkan ke JS)
    const sudahPunyaFile = "<?= !empty($d_siswa['file_buta_warna']) ? 'ya' : 'tidak' ?>";

    if (butuh) {
        box.style.display = 'block';
        // Jika belum punya file, maka wajib required
        if (sudahPunyaFile === 'tidak') {
            inputFile.required = true;
        } else {
            inputFile.required = false;
        }
    } else {
        box.style.display = 'none';
        inputFile.required = false;
    }
}
</script>

<?php include '../komponen/footer_umum.php'; ?>