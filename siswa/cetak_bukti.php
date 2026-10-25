<?php
session_start();
require_once '../konfigurasi/koneksi.php';
require_once '../konfigurasi/url.php';

// Ambil Data Lengkap Siswa Join Pendaftaran
$id_siswa = $_SESSION['id_user'];
$query = "SELECT p.*, s.nama_lengkap, s.nisn, s.asal_sekolah, 
          j1.nama_jurusan as jur1, sk1.nama_sekolah as sek1,
          j2.nama_jurusan as jur2, sk2.nama_sekolah as sek2
          FROM pendaftaran p
          JOIN siswa s ON p.id_siswa = s.id
          JOIN sekolah_jurusan sj1 ON p.pilihan_1_id_jurusan = sj1.id
          JOIN sekolah sk1 ON sj1.id_sekolah = sk1.id
          JOIN jurusan j1 ON sj1.id_jurusan = j1.id
          LEFT JOIN sekolah_jurusan sj2 ON p.pilihan_2_id_jurusan = sj2.id
          LEFT JOIN sekolah sk2 ON sj2.id_sekolah = sk2.id
          LEFT JOIN jurusan j2 ON sj2.id_jurusan = j2.id
          WHERE p.id_siswa = '$id_siswa'";

$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "Belum ada data pendaftaran."; exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Bukti Pendaftaran - <?= $data['nisn'] ?></title>
    <style>
        body { font-family: 'Times New Roman', serif; padding: 40px; }
        .kop { text-align: center; border-bottom: 3px double black; padding-bottom: 10px; margin-bottom: 20px; }
        .kop h2, .kop h3 { margin: 0; }
        .box-bukti { border: 1px solid black; padding: 20px; }
        table { width: 100%; margin-top: 10px; }
        td { padding: 5px; vertical-align: top; }
        .btn-print { margin-top: 20px; padding: 10px 20px; background: blue; color: white; border: none; cursor: pointer; }
        @media print { .btn-print { display: none; } }
    </style>
</head>
<body>

    <div class="kop">
        <h3>PEMERINTAH PROVINSI SUMATERA BARAT</h3>
        <h2>DINAS PENDIDIKAN</h2>
        <p>PANITIA PENERIMAAN PESERTA DIDIK BARU (PPDB) SMK TAHUN 2025</p>
    </div>

    <center><h3>TANDA BUKTI PENDAFTARAN</h3></center>

    <div class="box-bukti">
        <table border="0">
            <tr>
                <td width="200"><strong>Nomor Peserta</strong></td>
                <td>: <strong><?= $data['nisn'] ?></strong></td>
                <td rowspan="5" align="right">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=<?= $data['nisn'] ?>" alt="QR">
                </td>
            </tr>
            <tr>
                <td><strong>Nama Lengkap</strong></td>
                <td>: <?= strtoupper($data['nama_lengkap']) ?></td>
            </tr>
            <tr>
                <td><strong>Asal Sekolah</strong></td>
                <td>: <?= $data['asal_sekolah'] ?></td>
            </tr>
            <tr>
                <td><strong>Tanggal Daftar</strong></td>
                <td>: <?= date('d F Y H:i', strtotime($data['tgl_daftar'])) ?> WIB</td>
            </tr>
        </table>

        <hr>
        <h4>PILIHAN SEKOLAH & JURUSAN</h4>
        <table border="1" cellspacing="0" cellpadding="5">
            <tr style="background:#eee;">
                <th>Pilihan</th>
                <th>Sekolah Tujuan</th>
                <th>Konsentrasi Keahlian (Jurusan)</th>
                <th>Jarak (Meter)</th>
            </tr>
            <tr>
                <td align="center">1</td>
                <td><?= $data['sek1'] ?></td>
                <td><?= $data['jur1'] ?></td>
                <td align="center"><?= $data['jarak_meter_pilihan_1'] ?> m</td>
            </tr>
            <?php if ($data['pilihan_2_id_jurusan']): ?>
            <tr>
                <td align="center">2</td>
                <td><?= $data['sek2'] ?></td>
                <td><?= $data['jur2'] ?></td>
                <td align="center"><?= $data['jarak_meter_pilihan_2'] ?> m</td>
            </tr>
            <?php endif; ?>
        </table>

        <br>
        <div style="border: 1px dashed black; padding: 10px; font-size: 12px;">
            <strong>CATATAN PENTING:</strong>
            <ol>
                <li>Simpan bukti pendaftaran ini.</li>
                <li>Silakan datang ke <strong><?= $data['sek1'] ?></strong> (Sekolah Pilihan 1) untuk Verifikasi Berkas.</li>
                <li>Bawa Bukti ini, Rapor Asli, KK Asli, dan Dokumen Pendukung lainnya.</li>
                <li>Wajib mengikuti <strong>TES MINAT BAKAT</strong> di sekolah tujuan setelah verifikasi.</li>
            </ol>
        </div>

        <br><br>
        <table border="0">
            <tr>
                <td width="70%"></td>
                <td align="center">
                    Padang, <?= date('d F Y') ?> <br>
                    Siswa Pendaftar,
                    <br><br><br>
                    ( <?= $data['nama_lengkap'] ?> )
                </td>
            </tr>
        </table>
    </div>

    <button onclick="window.print()" class="btn-print">CETAK BUKTI / SIMPAN PDF</button>
    <a href="dashboard.php" class="btn-print" style="background: grey; text-decoration: none;">Kembali ke Dashboard</a>

</body>
</html>