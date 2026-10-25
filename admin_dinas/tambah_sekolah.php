<?php 
require_once '../konfigurasi/url.php'; 
require_once '../konfigurasi/koneksi.php';
include '../komponen/header_umum.php'; 
include '../komponen/sidebar_dinas.php'; 

// Ambil master jurusan untuk dropdown
$master_jurusan = mysqli_query($koneksi, "SELECT * FROM jurusan ORDER BY nama_jurusan ASC");
?>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div class="content-area">
    <div class="card">
        <h2>Tambah Sekolah & Manajemen Jurusan</h2>
        <p>Input data sekolah, tentukan lokasi, serta tambahkan daftar jurusan sekaligus.</p>
        <hr>
        <form action="proses_simpan_sekolah.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nama Sekolah</label>
                <input type="text" name="nama_sekolah" class="form-control" placeholder="Contoh: SMKN 1 Padang" required>
            </div>

            <div class="form-group">
                <label>Alamat Sekolah</label>
                <textarea name="alamat" class="form-control" rows="2" required></textarea>
            </div>

            <div class="form-group">
                <label>Daftar Jurusan (Bisa Tambah Banyak)</label>
                <div id="jurusan-container">
                    <div class="row-jurusan" style="display: flex; gap: 10px; margin-bottom: 10px;">
                        <select name="jurusan[]" class="form-control select2-tags" required style="flex: 1;">
                            <option value="">-- Pilih atau Ketik Jurusan Baru --</option>
                            <?php 
                            mysqli_data_seek($master_jurusan, 0);
                            while($j = mysqli_fetch_array($master_jurusan)) {
                                echo "<option value='".htmlspecialchars($j['nama_jurusan'])."'>".htmlspecialchars($j['nama_jurusan'])."</option>";
                            }
                            ?>
                        </select>
                        <button type="button" onclick="addRow()" style="background: #28a745; color: white; border: none; padding: 0 15px; border-radius: 4px; cursor: pointer; font-weight: bold;">+</button>
                    </div>
                </div>
            </div>

            <div class="row" style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label>Latitude</label>
                    <input type="text" name="lat" id="lat" class="form-control" readonly required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Longitude</label>
                    <input type="text" name="lng" id="lng" class="form-control" readonly required>
                </div>
            </div>

            <div id="map_input" style="height: 300px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #ddd;"></div>

            <div class="form-group">
                <label>Foto Sekolah</label>
                <input type="file" name="gambar" class="form-control" accept="image/*">
            </div>

            <div style="margin-top: 20px;">
                <button type="submit" class="btn-simpan" style="background: #006400; color: white; padding: 12px 25px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%;">
                    <i class="fas fa-save"></i> SIMPAN SEKOLAH & DAFTAR JURUSAN
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    function initSelect2() {
        $('.select2-tags').select2({
            tags: true, 
            placeholder: "Pilih atau ketik...",
            width: '100%'
        });
    }

    function addRow() {
        const container = document.getElementById('jurusan-container');
        const newRow = document.createElement('div');
        newRow.className = 'row-jurusan';
        newRow.style = 'display: flex; gap: 10px; margin-bottom: 10px;';
        newRow.innerHTML = `
            <select name="jurusan[]" class="form-control select2-tags" required style="flex: 1;">
                <option value="">-- Pilih atau Ketik Jurusan Baru --</option>
                <?php
                mysqli_data_seek($master_jurusan, 0);
                while($j = mysqli_fetch_array($master_jurusan)) {
                    echo "<option value='".addslashes($j['nama_jurusan'])."'>".addslashes($j['nama_jurusan'])."</option>";
                }
                ?>
            </select>
            <button type="button" onclick="this.parentElement.remove()" style="background: #dc3545; color: white; border: none; padding: 0 15px; border-radius: 4px; cursor: pointer;">-</button>
        `;
        container.appendChild(newRow);
        initSelect2();
    }

    $(document).ready(function() {
        initSelect2();
        var map = L.map('map_input').setView([-0.947, 100.417], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        var marker;
        map.on('click', function(e) {
            if (marker) map.removeLayer(marker);
            marker = L.marker(e.latlng).addTo(map);
            document.getElementById('lat').value = e.latlng.lat;
            document.getElementById('lng').value = e.latlng.lng;
        });
    });
</script>

<?php include '../komponen/footer_umum.php'; ?>