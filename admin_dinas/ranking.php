<?php
session_start();
require_once '../konfigurasi/koneksi.php';
require_once '../konfigurasi/url.php';

if ($_SESSION['role'] != 'dinas') { header("location:../akses/login.php"); exit(); }

// Filter Sekolah & Jurusan
$id_sekolah = isset($_GET['sekolah']) ? $_GET['sekolah'] : '';
$id_jurusan_master = isset($_GET['jurusan']) ? $_GET['jurusan'] : '';

$sekolah_list = mysqli_query($koneksi, "SELECT * FROM sekolah");
$jurusan_list = mysqli_query($koneksi, "SELECT * FROM jurusan");

include '../komponen/header_umum.php';
include '../komponen/sidebar_dinas.php';
?>

<div class="content-area">
    <h3>Lihat Perangkingan Seleksi</h3>
    
    <div class="card">
        <form method="GET">
            <div style="display:flex; gap:10px;">
                <select name="sekolah" class="form-control" required>
                    <option value="">- Pilih Sekolah -</option>
                    <?php foreach($sekolah_list as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= ($id_sekolah == $s['id'])?'selected':'' ?>><?= $s['nama_sekolah'] ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="jurusan" class="form-control" required>
                    <option value="">- Pilih Jurusan -</option>
                    <?php foreach($jurusan_list as $j): ?>
                        <option value="<?= $j['id'] ?>" <?= ($id_jurusan_master == $j['id'])?'selected':'' ?>><?= $j['nama_jurusan'] ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn-save">Tampilkan</button>
            </div>
        </form>
    </div>

    <?php if($id_sekolah && $id_jurusan_master): 
        // Logic Perangkingan:
        // 1. Cari ID sekolah_jurusan berdasarkan input
        $q_sj = mysqli_query($koneksi, "SELECT id, kuota FROM sekolah_jurusan WHERE id_sekolah='$id_sekolah' AND id_jurusan='$id_jurusan_master'");
        $d_sj = mysqli_fetch_assoc($q_sj);
        
        if(!$d_sj) { echo "<div class='alert-box'>Jurusan tidak tersedia di sekolah ini.</div>"; } 
        else {
            $id_sj = $d_sj['id'];
            $kuota = $d_sj['kuota'];

            // Query Ranking: Ambil siswa yang memilih jurusan ini (baik Pilihan 1 atau 2)
            // Filter: Hanya yang status Verifikasi 'Diterima'
            // Sort: Skor Akhir DESC -> Tes DESC -> Rapor DESC -> Waktu Daftar ASC
            $query_rank = "
                SELECT p.*, s.nama_lengkap, s.nisn,
                CASE 
                    WHEN p.pilihan_1_id_jurusan = '$id_sj' THEN p.skor_akhir_pilihan_1
                    WHEN p.pilihan_2_id_jurusan = '$id_sj' THEN p.skor_akhir_pilihan_2
                END as skor_final
                FROM pendaftaran p
                JOIN siswa s ON p.id_siswa = s.id
                WHERE (p.pilihan_1_id_jurusan = '$id_sj' OR p.pilihan_2_id_jurusan = '$id_sj')
                AND p.status_verifikasi = 'Diterima'
                ORDER BY skor_final DESC, p.nilai_tes_minat_bakat DESC, p.rata_rata_rapor_total DESC, p.tgl_daftar ASC
            ";
            $hasil_rank = mysqli_query($koneksi, $query_rank);
    ?>
    
    <div class="card">
        <h4>Hasil Perangkingan (Kuota: <?= $kuota ?> Kursi)</h4>
        <table border="1" width="100%" cellpadding="5" cellspacing="0" style="border-collapse:collapse;">
            <tr style="background:#343a40; color:white;">
                <th>Rank</th>
                <th>NISN</th>
                <th>Nama Siswa</th>
                <th>Nilai Akhir</th>
                <th>Nilai Tes (70%)</th>
                <th>Rerata Rapor (30%)</th>
                <th>Status</th>
            </tr>
            <?php 
            $rank = 1;
            while($row = mysqli_fetch_assoc($hasil_rank)): 
                $status_lulus = ($rank <= $kuota) ? "<span style='color:green; font-weight:bold;'>LULUS</span>" : "<span style='color:red;'>CADANGAN</span>";
            ?>
            <tr style="<?= ($rank <= $kuota) ? 'background:#e8f5e9;' : '' ?>">
                <td align="center"><?= $rank++ ?></td>
                <td><?= $row['nisn'] ?></td>
                <td><?= $row['nama_lengkap'] ?></td>
                <td align="center" style="font-weight:bold; font-size:1.1em;"><?= number_format($row['skor_final'], 2) ?></td>
                <td align="center"><?= $row['nilai_tes_minat_bakat'] ?></td>
                <td align="center"><?= $row['rata_rata_rapor_total'] ?></td>
                <td align="center"><?= $status_lulus ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
    <?php } endif; ?>
</div>
<?php include '../komponen/footer_umum.php'; ?>