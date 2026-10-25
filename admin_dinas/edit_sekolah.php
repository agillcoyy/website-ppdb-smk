<?php 
require_once '../konfigurasi/url.php'; 
require_once '../konfigurasi/koneksi.php';
include '../komponen/header_umum.php'; 
include '../komponen/sidebar_dinas.php'; 

$id = mysqli_real_escape_string($koneksi, $_GET['id']);
$query = mysqli_query($koneksi, "SELECT * FROM sekolah WHERE id = '$id'");
$data = mysqli_fetch_array($query);

if (!$data) {
    echo "<script>alert('Data sekolah tidak ditemukan!'); window.location='kelola_sekolah.php';</script>";
    exit;
}

// Ambil data jurusan yang sudah ada (Tanpa Kuota)
$q_sj = mysqli_query($koneksi, "SELECT j.nama_jurusan 
                                FROM sekolah_jurusan sj 
                                JOIN jurusan j ON sj.id_jurusan = j.id 
                                WHERE sj.id_sekolah = '$id'");
$selected_jurusan = [];
while($sj = mysqli_fetch_array($q_sj)) {
    $selected_jurusan[] = $sj['nama_jurusan'];
}

$master_jurusan = mysqli_query($koneksi, "SELECT * FROM jurusan ORDER BY nama_jurusan ASC");
?>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="content-area">
    <div class="card">
        <h2>Edit Sekolah & Manajemen Jurusan</h2>
        <hr>
        <form action="proses_update_sekolah.php" method="POST">
            <input type="hidden" name="id" value="<?= $data['id'] ?>">
            
            <div class="form-group">
                <label>Nama Sekolah</label>
                <input type="text" name="nama_sekolah" class="form-control" value="<?= htmlspecialchars($data['nama_sekolah']) ?>" required>
            </div>

            <div class="form-group">
                <label>Daftar Jurusan (Bisa Pilih Banyak)</label>
                <div id="jurusan-container">
                    <?php if (empty($selected_jurusan)) : ?>
                        <div class="row-jurusan" style="display: flex; gap: 10px; margin-bottom: 10px;">
                            <select name="jurusan[]" class="form-control select2-tags" required style="flex: 1;">
                                <option value="">-- Pilih atau Ketik Jurusan --</option>
                                <?php mysqli_data_seek($master_jurusan, 0); 
                                while($j = mysqli_fetch_array($master_jurusan)) echo "<option value='{$j['nama_jurusan']}'>{$j['nama_jurusan']}</option>"; ?>
                            </select>
                            <button type="button" onclick="addRow()" style="background: #28a745; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer;">+</button>
                        </div>
                    <?php else : foreach ($selected_jurusan as $index => $nama_jur) : ?>
                        <div class="row-jurusan" style="display: flex; gap: 10px; margin-bottom: 10px;">
                            <select name="jurusan[]" class="form-control select2-tags" required style="flex: 1;">
                                <option value="<?= htmlspecialchars($nama_jur) ?>" selected><?= htmlspecialchars($nama_jur) ?></option>
                                <?php mysqli_data_seek($master_jurusan, 0); 
                                while($j = mysqli_fetch_array($master_jurusan)) echo "<option value='{$j['nama_jurusan']}'>{$j['nama_jurusan']}</option>"; ?>
                            </select>
                            
                            <?php if ($index == 0) : ?>
                                <button type="button" onclick="addRow()" style="background: #28a745; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer;">+</button>
                            <?php else : ?>
                                <button type="button" onclick="this.parentElement.remove()" style="background: #dc3545; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer;">-</button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>

            <div style="margin-top: 20px;">
                <button type="submit" class="btn-simpan" style="background: #006400; color: white; padding: 12px 25px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%;">
                    <i class="fas fa-save"></i> SIMPAN PERUBAHAN JURUSAN
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    function initSelect2() {
        $('.select2-tags').select2({ tags: true, width: '100%' });
    }
    function addRow() {
        const row = `
            <div class="row-jurusan" style="display: flex; gap: 10px; margin-bottom: 10px;">
                <select name="jurusan[]" class="form-control select2-tags" required style="flex: 1;">
                    <option value="">-- Pilih atau Ketik Jurusan --</option>
                    <?php mysqli_data_seek($master_jurusan, 0); 
                    while($j = mysqli_fetch_array($master_jurusan)) echo "<option value='".addslashes($j['nama_jurusan'])."'>".addslashes($j['nama_jurusan'])."</option>"; ?>
                </select>
                <button type="button" onclick="this.parentElement.remove()" style="background: #dc3545; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer;">-</button>
            </div>`;
        $('#jurusan-container').append(row);
        initSelect2();
    }
    $(document).ready(function() { initSelect2(); });
</script>
<?php include '../komponen/footer_umum.php'; ?>