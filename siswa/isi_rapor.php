<?php
session_start();
require_once '../konfigurasi/url.php';
require_once '../konfigurasi/koneksi.php';

// Cek Sesi
if (!isset($_SESSION['status']) || $_SESSION['role'] != 'siswa') {
    header("location:../akses/login.php");
    exit();
}

$id_siswa = $_SESSION['id_user'];
$nisn = $_SESSION['nisn'];

include '../komponen/header_umum.php';
include '../komponen/sidebar_siswa.php';
?>

<div class="content-area">
    <h3>Input Nilai Rapor (Semester 1 - 5)</h3>
    <div class="alert-box">
        <strong>Petunjuk:</strong>
        <ul>
            <li>Isi <strong>Nilai Rerata Pengetahuan</strong> (Skala 0-100). Gunakan titik untuk desimal (Contoh: 85.50).</li>
            <li>Upload scan rapor asli per semester (PDF/JPG, Maks 2MB).</li>
            <li>Pastikan tulisan di scan rapor terbaca jelas.</li>
        </ul>
    </div>

    <div class="card">
        <form action="../proses/simpan_rapor.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_siswa" value="<?= $id_siswa ?>">
            <input type="hidden" name="nisn" value="<?= $nisn ?>">

            <table border="1" cellpadding="10" cellspacing="0" width="100%" style="border-collapse: collapse; border-color: #ddd;">
                <tr style="background-color: #f8f9fa;">
                    <th width="10%">Semester</th>
                    <th width="20%">Rerata Nilai Pengetahuan</th>
                    <th width="40%">Upload Scan Rapor Asli</th>
                    <th width="30%">Status File</th>
                </tr>

                <?php 
                for ($smt = 1; $smt <= 5; $smt++) { 
                    // Ambil data jika sudah ada di database
                    $q_nilai = mysqli_query($koneksi, "SELECT * FROM nilai_rapor WHERE id_siswa='$id_siswa' AND semester='$smt'");
                    $d_nilai = mysqli_fetch_assoc($q_nilai);
                    $nilai_ada = $d_nilai ? $d_nilai['nilai_pengetahuan'] : "";
                    $file_ada = $d_nilai ? $d_nilai['file_rapor'] : "";
                ?>
                <tr>
                    <td align="center"><strong>Semester <?= $smt ?></strong></td>
                    <td>
                        <input type="number" step="0.01" name="nilai_<?= $smt ?>" class="form-control" value="<?= $nilai_ada ?>" required placeholder="00.00">
                    </td>
                    <td>
                        <input type="file" name="file_<?= $smt ?>" class="form-control" accept=".pdf,.jpg,.jpeg,.png" <?= ($file_ada == "") ? "required" : "" ?>>
                        <small style="color:red">*Wajib PDF/JPG</small>
                    </td>
                    <td>
                        <?php if ($file_ada != ""): ?>
                            <span style="color: green;">✔ Terupload</span><br>
                            <a href="<?= BASE_URL ?>unggahan/rapor/<?= $file_ada ?>" target="_blank" style="font-size:12px;">Lihat File</a>
                            <input type="hidden" name="file_lama_<?= $smt ?>" value="<?= $file_ada ?>">
                        <?php else: ?>
                            <span style="color: red;">❌ Belum ada file</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php } ?>
            </table>

            <br>
            <div style="text-align: right;">
                <button type="submit" class="btn-save" style="width: 200px;">SIMPAN DATA RAPOR</button>
            </div>
        </form>
    </div>
</div>

<?php include '../komponen/footer_umum.php'; ?>