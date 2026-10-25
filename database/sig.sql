-- phpMyAdmin SQL Dump
-- Status: SIAP PAKAI (CLEAN DATABASE)
-- User: Superadmin Only
-- Data Siswa: Kosong (Default File NULL)

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";

-- ========================================================
-- 1. BERSIHKAN TABEL LAMA
-- ========================================================
DROP TABLE IF EXISTS `pendaftaran`;
DROP TABLE IF EXISTS `nilai_rapor`;
DROP TABLE IF EXISTS `sekolah_jurusan`;
DROP TABLE IF EXISTS `admin`;
DROP TABLE IF EXISTS `siswa`;
DROP TABLE IF EXISTS `sekolah`;
DROP TABLE IF EXISTS `jurusan`;

-- ========================================================
-- 2. TABEL DATA MASTER (TETAP ADA)
-- ========================================================

-- TABEL JURUSAN
CREATE TABLE `jurusan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_jurusan` varchar(100) NOT NULL,
  `butuh_buta_warna` enum('YA','TIDAK') DEFAULT 'TIDAK',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `jurusan` (`id`, `nama_jurusan`, `butuh_buta_warna`) VALUES
(1, 'Teknik Komputer dan Jaringan (TKJ)', 'YA'),
(2, 'Rekayasa Perangkat Lunak (RPL)', 'TIDAK'),
(3, 'Desain Komunikasi Visual (DKV)', 'YA'),
(4, 'Teknik Kendaraan Ringan (Otomotif)', 'YA'),
(5, 'Teknik Instalasi Tenaga Listrik', 'YA'),
(6, 'Teknik Pemesinan', 'YA'),
(7, 'Bisnis Daring dan Pemasaran', 'TIDAK'),
(8, 'Otomatisasi Tata Kelola Perkantoran', 'TIDAK'),
(9, 'Akuntansi dan Keuangan Lembaga', 'TIDAK'),
(10, 'Tata Boga', 'TIDAK'),
(11, 'Perhotelan', 'TIDAK'),
(12, 'Tata Busana', 'YA'),
(13, 'Kimia Analisis', 'YA'),
(14, 'Kimia Industri', 'YA'),
(15, 'Usaha Perjalanan Wisata', 'TIDAK');

-- TABEL SEKOLAH (SMK KOTA PADANG)
CREATE TABLE `sekolah` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `npsn` varchar(20) DEFAULT NULL,
  `nama_sekolah` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `lat` double NOT NULL,
  `lng` double NOT NULL,
  `foto` varchar(255) DEFAULT 'default_sekolah.jpg',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `sekolah` (`id`, `npsn`, `nama_sekolah`, `alamat`, `lat`, `lng`, `foto`) VALUES
(1, '10304851', 'SMKN 1 Padang', 'Jl. M. Yunus, Kp. Tulang, Kuranji', -0.922636, 100.390368, 'smkn1.jpg'),
(2, '10304852', 'SMKN 2 Padang', 'Jl. Baru Andalas, Simpang Haru', -0.945202, 100.378664, 'smkn2.jpg'),
(3, '10304853', 'SMKN 3 Padang', 'Jl. Jendral Sudirman No. 11', -0.945190, 100.362337, 'smkn3.jpg'),
(4, '10304854', 'SMKN 4 Padang', 'Jl. Cengkeh, Lubuk Begalung', -0.951800, 100.415300, 'smkn4.jpg'),
(5, '10304855', 'SMKN 5 Padang', 'Jl. Beringin No. 4, Lolong', -0.921390, 100.351692, 'smkn5.jpg'),
(6, '10304856', 'SMKN 6 Padang', 'Jl. Suliki No. 1, Jati', -0.936820, 100.364683, 'smkn6.jpg'),
(7, '10304857', 'SMKN 7 Padang', 'Jl. Cengkeh, Lubuk Begalung', -0.949816, 100.415254, 'smkn7.jpg'),
(8, '10304858', 'SMKN 8 Padang', 'Cengkeh, Nan XX', -0.951216, 100.415814, 'smkn8.jpg'),
(9, '10304859', 'SMKN 9 Padang', 'Jl. Bundo Kandung No. 18', -0.954387, 100.359674, 'smkn9.jpg'),
(10, '10304860', 'SMKN 10 Padang', 'Jl. Flamboyan, Lubuk Buaya', -0.821344, 100.321098, 'smkn10.jpg'),
(11, '10304861', 'SMK SMTI Padang', 'Jl. Ir. H. Juanda No. 2', -0.929871, 100.352328, 'smti.jpg'),
(12, '10304862', 'SMK SMAK Padang', 'Jl. Alai Pauh V No. 13', -0.933397, 100.441100, 'smak.jpg');

-- TABEL KUOTA (SEKOLAH_JURUSAN)
CREATE TABLE `sekolah_jurusan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_sekolah` int(11) NOT NULL,
  `id_jurusan` int(11) NOT NULL,
  `kuota` int(11) DEFAULT 32,
  PRIMARY KEY (`id`),
  KEY `id_sekolah` (`id_sekolah`),
  KEY `id_jurusan` (`id_jurusan`),
  CONSTRAINT `sekolah_jurusan_ibfk_1` FOREIGN KEY (`id_sekolah`) REFERENCES `sekolah` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sekolah_jurusan_ibfk_2` FOREIGN KEY (`id_jurusan`) REFERENCES `jurusan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Mapping Jurusan ke Sekolah (Data Awal)
INSERT INTO `sekolah_jurusan` (`id_sekolah`, `id_jurusan`, `kuota`) VALUES
(1, 1, 64), (1, 2, 64), (1, 5, 64), (1, 6, 64),
(2, 7, 64), (2, 8, 64), (2, 9, 64), (2, 1, 32),
(3, 10, 64), (3, 11, 64), (3, 12, 64),
(4, 3, 64), (4, 1, 32),
(5, 5, 64), (5, 6, 64),
(6, 10, 32), (6, 12, 32), (6, 2, 32),
(9, 10, 64), (9, 11, 64),
(11, 14, 128), (11, 6, 64),
(12, 13, 128);

-- ========================================================
-- 3. TABEL USER (BERSIH)
-- ========================================================

-- TABEL ADMIN (Hanya Superadmin Dinas)
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('dinas','sekolah') NOT NULL DEFAULT 'sekolah',
  `id_sekolah` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_sekolah` (`id_sekolah`),
  CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`id_sekolah`) REFERENCES `sekolah` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Admin Dinas (Password: 123)
INSERT INTO `admin` (`nama_lengkap`, `username`, `password`, `role`, `id_sekolah`) VALUES
('Administrator Dinas', 'superadmin', '$2y$10$hyuIdqwA9cTsNxyZhzyTPeDjSnArqfzzBpJOoAOjR0RdKsRlMEr46', 'dinas', NULL);
-- CATATAN: Admin sekolah DIHAPUS sesuai permintaan. 
-- Dinas nanti bisa membuat akun sekolah melalui menu "Manajemen User".

-- TABEL SISWA (KOSONG & FIX DEFAULT NULL)
CREATE TABLE `siswa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nisn` varchar(20) NOT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `alamat_rumah` text DEFAULT NULL,
  `lat` double DEFAULT NULL,
  `lng` double DEFAULT NULL,
  `asal_sekolah` varchar(100) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `foto_profil` varchar(255) DEFAULT 'default.png',
  -- PERBAIKAN: Default NULL agar saat daftar file tidak dianggap ada
  `file_kk` varchar(255) DEFAULT NULL,
  `file_akta` varchar(255) DEFAULT NULL,
  `file_kip` varchar(255) DEFAULT NULL,
  `file_buta_warna` varchar(255) DEFAULT NULL,
  `status_data` enum('BELUM','LENGKAP') DEFAULT 'BELUM',
  `tgl_daftar_akun` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `nisn` (`nisn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================================
-- 4. TABEL TRANSAKSI (KOSONG)
-- ========================================================

CREATE TABLE `nilai_rapor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_siswa` int(11) NOT NULL,
  `semester` int(1) NOT NULL,
  `nilai_pengetahuan` double NOT NULL,
  `file_rapor` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_siswa` (`id_siswa`),
  CONSTRAINT `nilai_rapor_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `pendaftaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_siswa` int(11) NOT NULL,
  `jalur_pendaftaran` enum('REGULER','AFIRMASI','PRESTASI') NOT NULL DEFAULT 'REGULER',
  `tgl_daftar` timestamp NOT NULL DEFAULT current_timestamp(),
  `pilihan_1_id_jurusan` int(11) NOT NULL,
  `pilihan_2_id_jurusan` int(11) DEFAULT NULL,
  `file_buta_warna` varchar(255) DEFAULT NULL,
  `jarak_meter_pilihan_1` double DEFAULT 0,
  `jarak_meter_pilihan_2` double DEFAULT 0,
  `rata_rata_rapor_total` double DEFAULT 0,
  `nilai_tes_minat_bakat` double DEFAULT 0,
  `skor_akhir_pilihan_1` double DEFAULT 0,
  `skor_akhir_pilihan_2` double DEFAULT 0,
  `status_verifikasi` enum('Menunggu','Proses','Diterima','Ditolak','Cabut Berkas') DEFAULT 'Menunggu',
  `diterima_di_pilihan` int(1) DEFAULT 0,
  `catatan_verifikator` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_siswa` (`id_siswa`),
  CONSTRAINT `pendaftaran_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;