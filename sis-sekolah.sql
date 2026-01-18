-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Jan 2026 pada 12.59
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sis-sekolah`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `gurus`
--

CREATE TABLE `gurus` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `nip` varchar(20) DEFAULT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `gelar_depan` varchar(20) DEFAULT NULL,
  `gelar_belakang` varchar(20) DEFAULT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL DEFAULT 'L',
  `alamat` text DEFAULT NULL,
  `foto` varchar(255) NOT NULL DEFAULT 'default.jpg',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2025-12-28-135801', 'App\\Database\\Migrations\\CreateUsersTable', 'default', 'App', 1766934119, 1),
(2, '2025-12-28-144103', 'App\\Database\\Migrations\\CreateProfilesTable', 'default', 'App', 1766934119, 1),
(3, '2025-12-31-032504', 'App\\Database\\Migrations\\CreateGurusTable', 'default', 'App', 1767151562, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `profiles`
--

CREATE TABLE `profiles` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `nomor_induk` varchar(30) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_key` varchar(50) NOT NULL,
  `role_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `roles`
--

INSERT INTO `roles` (`id`, `role_key`, `role_name`) VALUES
(1, 'admin', 'Administrator'),
(2, 'kepsek', 'Kepala Sekolah'),
(3, 'kurikulum', 'Waka Kurikulum'),
(4, 'kesiswaan', 'Waka Kesiswaan'),
(5, 'sarpras', 'Sarana Prasarana'),
(6, 'bendahara', 'Bendahara'),
(7, 'bk', 'Guru BK'),
(8, 'guru', 'Guru Mapel'),
(9, 'wali_kelas', 'Wali Kelas'),
(10, 'piket', 'Guru Piket'),
(11, 'siswa', 'Peserta Didik');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_absensi`
--

CREATE TABLE `tbl_absensi` (
  `id` int(11) NOT NULL,
  `tgl` date NOT NULL,
  `status` enum('H','S','I','A') NOT NULL COMMENT 'Hadir, Sakit, Izin, Alpha'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_anggota_ekskul`
--

CREATE TABLE `tbl_anggota_ekskul` (
  `id` int(11) NOT NULL,
  `ekskul_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_anggota_ekskul`
--

INSERT INTO `tbl_anggota_ekskul` (`id`, `ekskul_id`, `siswa_id`) VALUES
(1, 1, 1),
(2, 1, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_ekskul`
--

CREATE TABLE `tbl_ekskul` (
  `id` int(11) NOT NULL,
  `nama_ekskul` varchar(100) NOT NULL,
  `guru_id` int(11) DEFAULT NULL,
  `hari` varchar(50) DEFAULT NULL,
  `jam` varchar(50) DEFAULT NULL,
  `foto` varchar(255) DEFAULT 'default_ekskul.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_ekskul`
--

INSERT INTO `tbl_ekskul` (`id`, `nama_ekskul`, `guru_id`, `hari`, `jam`, `foto`) VALUES
(1, 'Pramuka', 1, 'Jumat', '13:00 - 15:00', 'default_ekskul.jpg'),
(2, 'Futsal', 0, 'Sabtu', '08:00 - 10:00', 'default_ekskul.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_gallery`
--

CREATE TABLE `tbl_gallery` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_guru`
--

CREATE TABLE `tbl_guru` (
  `id` int(11) NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `nip` varchar(20) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `gelar_depan` varchar(50) DEFAULT NULL,
  `gelar_belakang` varchar(50) DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT 'L',
  `alamat` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT 'default.png',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_guru`
--

INSERT INTO `tbl_guru` (`id`, `user_id`, `nip`, `nama_lengkap`, `gelar_depan`, `gelar_belakang`, `jenis_kelamin`, `alamat`, `foto`, `created_at`, `updated_at`) VALUES
(2, NULL, 'G002', 'Bu Guru 2', NULL, NULL, 'L', NULL, 'default.png', NULL, NULL),
(3, 6, '3213012912010002', 'Riki Wahyudin', '', 'S.Tr.T', 'L', 'Subang', '1767435241_b33cd1f830710fd763a4.jpg', '2026-01-03 10:14:01', '2026-01-05 10:56:53'),
(4, 7, '3213234503980005', 'Alpin MUldiyana', '', 'S.Tr.T', 'L', 'hsdfbjshdfbj', '1767435632_82b3176141acf6081438.png', '2026-01-03 10:20:32', '2026-01-03 10:49:54'),
(5, 8, '3213125012910010', 'Rian Alviana', '', 'S.Or', 'L', 'xvcdfgbdgf', '1767436093_029684e44eb3eacca9e5.jpg', '2026-01-03 10:28:13', '2026-01-04 14:32:38'),
(6, 9, '3213234503980012', 'YUNI ROSMIANTI', '', 'S.Pd', 'L', 'CIKASUNGKA', '1767437618_fd0a08c5b4c7ddd8e12f.png', '2026-01-03 10:53:38', '2026-01-03 10:53:38'),
(7, 11, '198501012010011001', 'Budi Santoso', NULL, 'S.Pd.', 'L', NULL, 'default.png', NULL, NULL),
(8, 13, '3213234503980097', 'xoni Nas', '', 'S.Pd', 'L', 'CIKASUNGKA', '1767535626_a8c48a52cdd26a997530.jpg', '2026-01-04 14:07:07', '2026-01-04 14:07:07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_jurusan`
--

CREATE TABLE `tbl_jurusan` (
  `id` int(11) NOT NULL,
  `kode_jurusan` varchar(10) NOT NULL,
  `nama_jurusan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `tbl_jurusan`
--

INSERT INTO `tbl_jurusan` (`id`, `kode_jurusan`, `nama_jurusan`) VALUES
(1, 'TJKT', 'Teknik Jaringan Komputer Dan Telekomunikasi'),
(2, 'MPLB', 'Manajemen Perkantoran Dan Layanan Bisnis'),
(3, 'Far', 'Teknologi Farmasi'),
(4, 'Pem', 'Pemasaran'),
(5, 'TKR', 'Teknik Kendaraan Ringan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_kegiatan`
--

CREATE TABLE `tbl_kegiatan` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `isi_berita` text NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `tanggal` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_kegiatan`
--

INSERT INTO `tbl_kegiatan` (`id`, `judul`, `slug`, `isi_berita`, `gambar`, `tanggal`) VALUES
(1, 'Pelantikan OSIS 2025', 'pelantikan-osis-2025', 'Kegiatan pelantikan OSIS berlangsung khidmat...', 'kegiatan1.jpg', '2025-01-10'),
(2, 'Juara 1 Lomba Web Design', 'juara-1-web-design', 'Siswa kami berhasil memenangkan lomba tingkat nasional...', 'kegiatan2.jpg', '2025-02-15');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_kelas`
--

CREATE TABLE `tbl_kelas` (
  `id` int(11) NOT NULL,
  `nama_kelas` varchar(50) NOT NULL,
  `id_jurusan` int(11) DEFAULT NULL,
  `guru_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_kelas`
--

INSERT INTO `tbl_kelas` (`id`, `nama_kelas`, `id_jurusan`, `guru_id`) VALUES
(1, 'X RPL 1', 1, 5),
(2, 'X TKJ 1', NULL, 2),
(3, 'XI RPL 1', NULL, 2),
(4, 'X TJKT 2', NULL, 8),
(5, 'XI TJKT 1', 1, 3),
(12, '7', 2147483647, NULL),
(13, '8', 2147483647, NULL),
(14, '9', 2147483647, NULL),
(15, '10', 2147483647, NULL),
(16, '11', 2147483647, NULL),
(17, '12', 2147483647, NULL),
(18, '13', 2147483647, NULL),
(19, '14', 2147483647, NULL),
(20, '15', 2147483647, NULL),
(21, '16', 2147483647, NULL),
(22, '17', 2147483647, NULL),
(23, '18', 2147483647, NULL),
(24, '19', 2147483647, NULL),
(25, '20', 2147483647, NULL),
(26, '21', 2147483647, NULL),
(27, '22', 2147483647, NULL),
(28, '23', 2147483647, NULL),
(29, '24', 2147483647, NULL),
(30, '25', 2147483647, NULL),
(31, '26', 2147483647, NULL),
(32, '27', 2147483647, NULL),
(33, '28', 2147483647, NULL),
(34, '29', 2147483647, NULL),
(35, '30', 2147483647, NULL),
(36, '31', 2147483647, NULL),
(37, '32', 2147483647, NULL),
(38, '33', 2147483647, NULL),
(39, '34', 2147483647, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_mapel`
--

CREATE TABLE `tbl_mapel` (
  `id` int(11) NOT NULL,
  `nama_mapel` varchar(100) NOT NULL,
  `kode_mapel` varchar(20) DEFAULT NULL,
  `kelompok` varchar(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `tbl_mapel`
--

INSERT INTO `tbl_mapel` (`id`, `nama_mapel`, `kode_mapel`, `kelompok`) VALUES
(1, 'Matematika', 'MTK', 'A');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_mapel_jurusan`
--

CREATE TABLE `tbl_mapel_jurusan` (
  `id` int(11) NOT NULL,
  `id_mapel` int(11) DEFAULT NULL,
  `id_jurusan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_mapel_jurusan`
--

INSERT INTO `tbl_mapel_jurusan` (`id`, `id_mapel`, `id_jurusan`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_orangtua`
--

CREATE TABLE `tbl_orangtua` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `nama_ayah` varchar(100) DEFAULT NULL,
  `pekerjaan_ayah` varchar(100) DEFAULT NULL,
  `no_hp_ortu` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_orangtua`
--

INSERT INTO `tbl_orangtua` (`id`, `siswa_id`, `nama_ayah`, `pekerjaan_ayah`, `no_hp_ortu`) VALUES
(1, 1, 'AKIM PERMANA', 'W', '831769151465');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_pelanggaran`
--

CREATE TABLE `tbl_pelanggaran` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `jenis_pelanggaran` text NOT NULL,
  `poin` int(11) DEFAULT 0,
  `status` enum('belum','sudah') DEFAULT 'belum',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_pembayaran`
--

CREATE TABLE `tbl_pembayaran` (
  `id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `tgl_bayar` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_pengaturan`
--

CREATE TABLE `tbl_pengaturan` (
  `id` int(11) NOT NULL,
  `kunci` varchar(100) DEFAULT NULL,
  `nilai` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_pengaturan`
--

INSERT INTO `tbl_pengaturan` (`id`, `kunci`, `nilai`) VALUES
(1, 'nama_sekolah', 'SMK Riyadhul Jannah Jalancagak'),
(2, 'alamat', 'Jl.Raya Prapatan bandung jalancagak Subang Jawa Barat'),
(3, 'no_telp', '(021) 789-1234'),
(4, 'email', 'info@smkdigital.sch.id'),
(5, 'deskripsi_hero', 'Sekolah berbasis teknologi masa depan dengan kurikulum standar industri internasional.'),
(6, 'sambutan_kepsek', 'Selamat datang di website resmi kami. Kami berkomitmen mencetak generasi unggul.'),
(7, 'foto_kepsek', '1767608551_0b703be2cccd05874ec9.jpg'),
(8, 'map_link', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126920.2409783186!2d106.756262!3d-6.2297465!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3e945e34b9d%3A0x5371bf0fdad786a2!2sJakarta!5e0!3m2!1sid!2sid!4v1642130000000!5m2!1sid!2sid'),
(9, 'nama_sekolah', 'SMK Riyadhul Jannah Jalancagak'),
(10, 'alamat', 'Jl.Raya Prapatan bandung jalancagak Subang Jawa Barat'),
(11, 'no_telp', '(021) 789-1234'),
(12, 'email', 'info@smkdigital.sch.id'),
(13, 'deskripsi_hero', 'Sekolah berbasis teknologi masa depan dengan kurikulum standar industri internasional.'),
(14, 'sambutan_kepsek', 'Selamat datang di website resmi kami. Kami berkomitmen mencetak generasi unggul.'),
(15, 'foto_kepsek', '1767608551_0b703be2cccd05874ec9.jpg'),
(16, 'map_link', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126920.2409783186!2d106.756262!3d-6.2297465!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3e945e34b9d%3A0x5371bf0fdad786a2!2sJakarta!5e0!3m2!1sid!2sid!4v1642130000000!5m2!1sid!2sid'),
(17, 'telegram_token', '8482784079:AAHtWyQ4HVoJUMk9Ot6lEO5ltvPnoDqkGM8');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_perizinan`
--

CREATE TABLE `tbl_perizinan` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `tgl` date NOT NULL,
  `tipe` enum('masuk','pulang') NOT NULL,
  `alasan` text DEFAULT NULL,
  `status` enum('pending','disetujui','ditolak') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_prestasi`
--

CREATE TABLE `tbl_prestasi` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `nama_prestasi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_ruangan`
--

CREATE TABLE `tbl_ruangan` (
  `id` int(11) NOT NULL,
  `nama_ruangan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `tbl_ruangan`
--

INSERT INTO `tbl_ruangan` (`id`, `nama_ruangan`) VALUES
(1, 'Kelas X TJKT 1'),
(4, 'Kelas X TJKT 2');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_siswa`
--

CREATE TABLE `tbl_siswa` (
  `id` int(11) NOT NULL,
  `nis` varchar(20) NOT NULL,
  `nisn` varchar(20) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jk` enum('L','P') DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `nama_ibu` varchar(100) DEFAULT NULL,
  `kelas_id` int(11) DEFAULT NULL,
  `ekskul_id` int(11) DEFAULT NULL,
  `status` enum('aktif','lulus','pindah') DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_siswa`
--

INSERT INTO `tbl_siswa` (`id`, `nis`, `nisn`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jk`, `nik`, `nama_ibu`, `kelas_id`, `ekskul_id`, `status`) VALUES
(1, '1001', '1234567890', 'Ahmad', 'Subang', '0000-00-00', 'L', '3213291206890007', 'Kusmawati', 1, 1, 'aktif'),
(2, '1002', '', 'Budi', 'Subang', '2026-01-20', 'L', '', '', 1, 2, 'aktif'),
(3, '1003', NULL, 'Citra', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'aktif'),
(4, '1004', NULL, 'Doni', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_slider`
--

CREATE TABLE `tbl_slider` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `urutan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_tagihan`
--

CREATE TABLE `tbl_tagihan` (
  `id` int(11) NOT NULL,
  `sisa` int(11) NOT NULL,
  `status` enum('belum','lunas') DEFAULT 'belum'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_tahun_ajaran`
--

CREATE TABLE `tbl_tahun_ajaran` (
  `id` int(11) NOT NULL,
  `tahun_ajaran` varchar(10) NOT NULL,
  `semester` enum('Ganjil','Genap') NOT NULL,
  `status` enum('Aktif','Nonaktif') DEFAULT 'Nonaktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `tbl_tahun_ajaran`
--

INSERT INTO `tbl_tahun_ajaran` (`id`, `tahun_ajaran`, `semester`, `status`) VALUES
(4, '2025/2026', 'Genap', 'Aktif'),
(5, '2025/2026', 'Ganjil', 'Nonaktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `nomor_wa` varchar(20) DEFAULT NULL,
  `telegram_chat_id` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `role` enum('admin','guru','siswa') NOT NULL DEFAULT 'admin',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `nama_lengkap`, `username`, `email`, `nomor_wa`, `telegram_chat_id`, `password`, `last_login`, `role`, `created_at`, `updated_at`) VALUES
(3, 'Administrator', 'admin', 'admin@sekolah.sch.id', '6281234567890', '1080546870', 'admin', NULL, 'admin', '2026-01-01 00:59:01', NULL),
(4, 'Administrator Utama', 'admin', 'admin@sekolah.sch.id', '628123456789', '1080546870', 'admin', NULL, 'admin', '2026-01-01 01:05:15', NULL),
(6, 'Riki Wahyudin', '3213012912010002', 'rikiwahyudin52@gmail.com', '085155232366', '1080546870', '$2y$10$81WPjCkPy3PSmHWxtAjf7.DMQD54K1ugA9gRCeieektChQ9Nz1IpW', NULL, 'guru', '2026-01-03 10:14:01', '2026-01-05 10:56:53'),
(7, 'Alpin MUldiyana', '3213234503980005', '3213234503980005@sekolah.id', '083176915165', NULL, '$2y$10$jCGgUYPSmjeg.0sEakLcqOY/SYDsYm/Z7NI5gkH3QjIo12.Rp8YMu', NULL, 'guru', '2026-01-03 10:20:32', '2026-01-03 10:49:54'),
(8, 'Rian Alviana', '3213125012910010', '3213125012910010@sekolah.id', '6285155232366', '1080546870', '$2y$10$WToMLtszIUR85adcuinVs.orUVhZTTJsgOmDdhqauA1cTrYyeT3xe', NULL, 'guru', '2026-01-03 10:28:13', '2026-01-04 14:32:38'),
(9, 'YUNI ROSMIANTI', '3213234503980012', '3213234503980012@sekolah.id', '6285155232366', NULL, '$2y$10$Zuzr5pZrnTjTh9Kt1HollOi83032qzzc0G/T2Ool422Lb3NczsWji', NULL, 'guru', '2026-01-03 10:53:38', '2026-01-03 10:53:38'),
(10, 'Super Administrator', 'admin', 'admin@sekolah.id', '081234567890', '123456789', '$2y$10$HEDl4UKWFmKd0x7ZGviw6eDh69BNrM70jW3LvJx01jBP0pseXguBS', NULL, 'admin', '2026-01-03 13:32:24', NULL),
(11, 'Budi Santoso, S.Pd.', '198501012010011001', 'budi.santoso@sekolah.id', '089876543210', '', '$2y$10$HEDl4UKWFmKd0x7ZGviw6eDh69BNrM70jW3LvJx01jBP0pseXguBS', NULL, 'admin', '2026-01-03 13:32:24', NULL),
(12, 'Ani Suryani', '2024001', 'ani.suryani@sekolah.id', '085678901234', '', '$2y$10$HEDl4UKWFmKd0x7ZGviw6eDh69BNrM70jW3LvJx01jBP0pseXguBS', NULL, 'admin', '2026-01-03 13:32:24', NULL),
(13, 'xoni Nas', '3213234503980097', '3213234503980097@sekolah.id', '083176915165', NULL, '$2y$10$KNmjCqjA7aZqfmmqCNBDFuXRTE.GR4ZU63meQz9H/3lmgkyBcTK.O', NULL, 'admin', '2026-01-04 14:07:06', '2026-01-04 14:07:06'),
(14, 'Admin Alpin', 'admin.alpin', NULL, NULL, NULL, '$2y$10$xuvJp7OKuCQ2F4jMdwSwNuO0YEdkuVhNn4lxQD8DkAR91xrMkf.Zu', NULL, 'admin', '2026-01-06 03:02:10', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `nomor_wa` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','guru','siswa','ortu') NOT NULL DEFAULT 'siswa',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `nomor_wa`, `password`, `role`, `created_at`, `updated_at`) VALUES
(3, 'admin', 'admin@sekolah.sch.id', '6285155232366', '$2y$10$ejLahTJOClUJyutleXGqi.auZAPLs9L5awW7fzTzxl/y0fBpoFlRe', 'admin', '2025-12-30 19:19:49', '2025-12-30 19:19:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_roles`
--

CREATE TABLE `user_roles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user_roles`
--

INSERT INTO `user_roles` (`id`, `user_id`, `role_id`) VALUES
(1, 3, 1),
(2, 4, 1),
(6, 9, 8),
(8, 10, 1),
(9, 11, 8),
(10, 12, 11),
(24, 7, 7),
(25, 7, 8),
(38, 13, 8),
(41, 6, 1),
(42, 6, 8),
(43, 6, 9),
(44, 6, 10),
(46, 14, 1),
(47, 14, 8),
(50, 8, 7),
(51, 8, 8),
(52, 8, 9);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `gurus`
--
ALTER TABLE `gurus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gurus_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profiles_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_key` (`role_key`);

--
-- Indeks untuk tabel `tbl_absensi`
--
ALTER TABLE `tbl_absensi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_anggota_ekskul`
--
ALTER TABLE `tbl_anggota_ekskul`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_ekskul`
--
ALTER TABLE `tbl_ekskul`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_gallery`
--
ALTER TABLE `tbl_gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_guru`
--
ALTER TABLE `tbl_guru`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_jurusan`
--
ALTER TABLE `tbl_jurusan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_kegiatan`
--
ALTER TABLE `tbl_kegiatan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_kelas`
--
ALTER TABLE `tbl_kelas`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_mapel`
--
ALTER TABLE `tbl_mapel`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_mapel_jurusan`
--
ALTER TABLE `tbl_mapel_jurusan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_orangtua`
--
ALTER TABLE `tbl_orangtua`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_pelanggaran`
--
ALTER TABLE `tbl_pelanggaran`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_pembayaran`
--
ALTER TABLE `tbl_pembayaran`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_pengaturan`
--
ALTER TABLE `tbl_pengaturan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_perizinan`
--
ALTER TABLE `tbl_perizinan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_prestasi`
--
ALTER TABLE `tbl_prestasi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_ruangan`
--
ALTER TABLE `tbl_ruangan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_siswa`
--
ALTER TABLE `tbl_siswa`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_slider`
--
ALTER TABLE `tbl_slider`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_tagihan`
--
ALTER TABLE `tbl_tagihan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_tahun_ajaran`
--
ALTER TABLE `tbl_tahun_ajaran`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `gurus`
--
ALTER TABLE `gurus`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `tbl_absensi`
--
ALTER TABLE `tbl_absensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tbl_anggota_ekskul`
--
ALTER TABLE `tbl_anggota_ekskul`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tbl_ekskul`
--
ALTER TABLE `tbl_ekskul`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tbl_gallery`
--
ALTER TABLE `tbl_gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tbl_guru`
--
ALTER TABLE `tbl_guru`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `tbl_jurusan`
--
ALTER TABLE `tbl_jurusan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `tbl_kegiatan`
--
ALTER TABLE `tbl_kegiatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tbl_kelas`
--
ALTER TABLE `tbl_kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT untuk tabel `tbl_mapel`
--
ALTER TABLE `tbl_mapel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `tbl_mapel_jurusan`
--
ALTER TABLE `tbl_mapel_jurusan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `tbl_orangtua`
--
ALTER TABLE `tbl_orangtua`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `tbl_pelanggaran`
--
ALTER TABLE `tbl_pelanggaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tbl_pembayaran`
--
ALTER TABLE `tbl_pembayaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tbl_pengaturan`
--
ALTER TABLE `tbl_pengaturan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `tbl_perizinan`
--
ALTER TABLE `tbl_perizinan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tbl_prestasi`
--
ALTER TABLE `tbl_prestasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tbl_ruangan`
--
ALTER TABLE `tbl_ruangan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `tbl_siswa`
--
ALTER TABLE `tbl_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `tbl_slider`
--
ALTER TABLE `tbl_slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tbl_tagihan`
--
ALTER TABLE `tbl_tagihan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tbl_tahun_ajaran`
--
ALTER TABLE `tbl_tahun_ajaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `gurus`
--
ALTER TABLE `gurus`
  ADD CONSTRAINT `gurus_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
