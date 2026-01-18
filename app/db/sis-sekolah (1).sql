-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 18 Jan 2026 pada 16.41
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
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `pendidikan_terakhir` varchar(50) DEFAULT NULL,
  `sertifikasi` varchar(100) DEFAULT NULL,
  `status_guru` enum('PNS','GTT','GTY','HONORER') DEFAULT 'GTY',
  `foto` varchar(255) DEFAULT 'default.png',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_guru`
--

INSERT INTO `tbl_guru` (`id`, `user_id`, `nip`, `nama_lengkap`, `gelar_depan`, `gelar_belakang`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `no_hp`, `email`, `pendidikan_terakhir`, `sertifikasi`, `status_guru`, `foto`, `created_at`, `updated_at`) VALUES
(3, 6, '3213012912010002', 'Riki Wahyudin', '', 'S.Tr.T', 'L', NULL, NULL, 'Subang', NULL, NULL, NULL, NULL, 'GTY', '1767435241_b33cd1f830710fd763a4.jpg', '2026-01-03 10:14:01', '2026-01-05 10:56:53'),
(4, 7, '3213234503980005', 'Alpin MUldiyana', '', 'S.Tr.T', 'L', '', '0000-00-00', 'hsdfbjshdfbj', '', '', 'S1 Pendidikan Matematika', 'ya', 'GTY', '1767435632_82b3176141acf6081438.png', '2026-01-03 10:20:32', '2026-01-16 13:20:01'),
(5, 8, '3213125012910010', 'Rian Alviana', '', 'S.Or', 'L', NULL, NULL, 'xvcdfgbdgf', NULL, NULL, NULL, NULL, 'GTY', '1767436093_029684e44eb3eacca9e5.jpg', '2026-01-03 10:28:13', '2026-01-04 14:32:38'),
(6, 9, '3213234503980012', 'YUNI ROSMIANTI', '', 'S.Pd', 'L', '', '0000-00-00', 'CIKASUNGKA', '085155232366', 'rikiwahyudin52@gmail.com', '', '', 'GTY', '1767437618_fd0a08c5b4c7ddd8e12f.png', '2026-01-03 10:53:38', '2026-01-16 13:20:23'),
(8, 13, '3213234503980097', 'xoni Nas', '', 'S.Pd', 'L', NULL, NULL, 'CIKASUNGKA', NULL, NULL, NULL, NULL, 'GTY', '1767535626_a8c48a52cdd26a997530.jpg', '2026-01-04 14:07:07', '2026-01-04 14:07:07'),
(64, 791, '199001012022011001', 'Budi Santoso, S.Pd', NULL, NULL, 'L', NULL, NULL, NULL, '081234567890', NULL, 'S1 Pendidikan Matematika', NULL, 'PNS', 'default.png', '2026-01-18 15:36:12', '2026-01-18 15:36:12');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_jadwal`
--

CREATE TABLE `tbl_jadwal` (
  `id` int(11) NOT NULL,
  `id_tahun_ajaran` int(11) NOT NULL,
  `id_kelas` int(11) NOT NULL,
  `id_mapel` int(11) NOT NULL,
  `id_guru` int(11) NOT NULL,
  `hari` varchar(20) NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_jadwal`
--

INSERT INTO `tbl_jadwal` (`id`, `id_tahun_ajaran`, `id_kelas`, `id_mapel`, `id_guru`, `hari`, `jam_mulai`, `jam_selesai`, `created_at`, `updated_at`) VALUES
(1, 4, 1, 1, 3, 'Senin', '07:15:00', '08:35:00', '2026-01-18 14:44:34', '2026-01-18 14:44:34'),
(2, 4, 2, 2, 3, 'Senin', '08:35:00', '10:10:00', '2026-01-18 14:56:00', '2026-01-18 14:56:00'),
(3, 4, 3, 1, 4, 'Senin', '07:15:00', '09:15:00', '2026-01-18 15:02:28', '2026-01-18 15:02:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_jam_master`
--

CREATE TABLE `tbl_jam_master` (
  `id` int(11) NOT NULL,
  `urutan` int(11) NOT NULL,
  `nama_jam` varchar(50) NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `is_istirahat` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_jam_master`
--

INSERT INTO `tbl_jam_master` (`id`, `urutan`, `nama_jam`, `jam_mulai`, `jam_selesai`, `is_istirahat`) VALUES
(1, 1, 'JP 1', '07:15:00', '07:55:00', 0),
(2, 2, 'JP 2', '07:55:00', '08:35:00', 0),
(3, 3, 'JP 3', '08:35:00', '09:15:00', 0),
(4, 4, 'Istirahat', '09:15:00', '09:30:00', 1),
(5, 5, 'JP 4', '09:30:00', '10:10:00', 0),
(6, 6, 'JP 5', '10:10:00', '10:50:00', 0),
(7, 7, 'JP 6', '14:00:00', '14:40:00', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_jurusan`
--

CREATE TABLE `tbl_jurusan` (
  `id` int(11) NOT NULL,
  `kode_jurusan` varchar(10) NOT NULL,
  `nama_jurusan` varchar(100) NOT NULL,
  `kepala_jurusan_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `tbl_jurusan`
--

INSERT INTO `tbl_jurusan` (`id`, `kode_jurusan`, `nama_jurusan`, `kepala_jurusan_id`) VALUES
(1, 'TJKT', 'Teknik Jaringan Komputer Dan Telekomunikasi', 3),
(2, 'MPLB', 'Manajemen Perkantoran Dan Layanan Bisnis', 4),
(3, 'Far', 'Teknologi Farmasi', 5),
(4, 'Pem', 'Pemasaran', 6),
(5, 'TKR', 'Teknik Kendaraan Ringan', 10);

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
(5, 'XI TJKT 1', 1, 3);

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
(1, 'Matematika', 'MTK', 'A'),
(2, 'Dasar Dasar TJKT', 'DTJKT', 'C');

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
(4, 1, 4),
(5, 2, 1);

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
  `user_id` int(11) DEFAULT NULL,
  `kelas_id` int(11) DEFAULT NULL,
  `jurusan_id` int(11) DEFAULT NULL,
  `nisn` varchar(20) NOT NULL,
  `nis` varchar(20) DEFAULT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT 'L',
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `agama` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_hp_siswa` varchar(20) DEFAULT NULL,
  `email_siswa` varchar(100) DEFAULT NULL,
  `nama_ayah` varchar(100) DEFAULT NULL,
  `nama_ibu` varchar(100) DEFAULT NULL,
  `nama_wali` varchar(100) DEFAULT NULL,
  `no_hp_ortu` varchar(20) DEFAULT NULL,
  `pekerjaan_ayah` varchar(50) DEFAULT NULL,
  `pekerjaan_ibu` varchar(50) DEFAULT NULL,
  `status_siswa` enum('Aktif','Lulus','Keluar','Skorsing') DEFAULT 'Aktif',
  `foto` varchar(255) DEFAULT 'default.png',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_siswa`
--

INSERT INTO `tbl_siswa` (`id`, `user_id`, `kelas_id`, `jurusan_id`, `nisn`, `nis`, `nama_lengkap`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `agama`, `alamat`, `no_hp_siswa`, `email_siswa`, `nama_ayah`, `nama_ibu`, `nama_wali`, `no_hp_ortu`, `pekerjaan_ayah`, `pekerjaan_ibu`, `status_siswa`, `foto`, `created_at`, `updated_at`) VALUES
(1, 687, 1, 1, '0054321987', '2023001', 'Ahmad Siswa Teladan', 'L', 'Subang', '2008-05-20', NULL, NULL, '08123456789', NULL, 'Budi Ayah', 'Siti Ibu', NULL, '08139876543', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(2, 688, 1, 1, '81234001', '24001', 'ADITYA PRATAMA', 'L', 'Bandung', '0000-00-00', NULL, '', '8123456001', '', 'Hartono', 'Sri Wahyuni', NULL, '8190000001', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(3, 689, 1, 1, '81234002', '24002', 'AHMAD FAUZI', 'L', 'Jakarta', '0000-00-00', NULL, '', '8123456002', '', 'Budi Santoso', 'Siti Aminah', NULL, '8190000002', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(4, 690, 1, 2, '81234003', '24003', 'Aisyah Putri', 'P', 'Surabaya', '0000-00-00', NULL, NULL, '8123456003', NULL, 'Joko Susilo', 'Dewi Sartika', NULL, '8190000003', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(5, 691, 1, 2, '81234004', '24004', 'Aldi Mahendra', 'L', 'Medan', '0000-00-00', NULL, NULL, '8123456004', NULL, 'Bambang', 'Rina Wati', NULL, '8190000004', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(6, 692, 2, 1, '81234005', '24005', 'Amelia Sari', 'P', 'Yogyakarta', '0000-00-00', NULL, NULL, '8123456005', NULL, 'Agus Salim', 'Nurhayati', NULL, '8190000005', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(7, 693, 2, 1, '81234006', '24006', 'Andi Saputra', 'L', 'Semarang', '0000-00-00', NULL, NULL, '8123456006', NULL, 'Hendra', 'Yulia', NULL, '8190000006', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(8, 694, 2, 1, '81234007', '24007', 'Annisa Rahma', 'P', 'Solo', '0000-00-00', NULL, NULL, '8123456007', NULL, 'Eko Prasetyo', 'Lina', NULL, '8190000007', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(9, 695, 2, 2, '81234008', '24008', 'Arif Hidayat', 'L', 'Malang', '0000-00-00', NULL, NULL, '8123456008', NULL, 'Fajar', 'Ratna', NULL, '8190000008', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(10, 696, 3, 1, '81234009', '24009', 'Ayu Lestari', 'P', 'Denpasar', '0000-00-00', NULL, NULL, '8123456009', NULL, 'Gunawan', 'Sari', NULL, '8190000009', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(11, 697, 3, 1, '81234010', '24010', 'Bagas Kara', 'L', 'Makassar', '0000-00-00', NULL, NULL, '8123456010', NULL, 'Heru', 'Tini', NULL, '8190000010', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(12, 698, 3, 2, '81234011', '24011', 'Bayu Nugraha', 'L', 'Palembang', '0000-00-00', NULL, NULL, '8123456011', NULL, 'Indra', 'Wulan', NULL, '8190000011', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(13, 699, 3, 2, '81234012', '24012', 'Bunga Citra', 'P', 'Padang', '0000-00-00', NULL, NULL, '8123456012', NULL, 'Jamal', 'Ningsih', NULL, '8190000012', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(14, 700, 1, 1, '81234013', '24013', 'Cahya Ramadhan', 'L', 'Bekasi', '0000-00-00', NULL, NULL, '8123456013', NULL, 'Kurniawan', 'Endang', NULL, '8190000013', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(15, 701, 1, 1, '81234014', '24014', 'Cantika Dewi', 'P', 'Bogor', '0000-00-00', NULL, NULL, '8123456014', NULL, 'Lukman', 'Fitri', NULL, '8190000014', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(16, 702, 1, 2, '81234015', '24015', 'Daffa Ardiansyah', 'L', 'Depok', '0000-00-00', NULL, NULL, '8123456015', NULL, 'Mulyono', 'Susi', NULL, '8190000015', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(17, 703, 1, 2, '81234016', '24016', 'Dani Firmansyah', 'L', 'Tangerang', '0000-00-00', NULL, NULL, '8123456016', NULL, 'Nurdin', 'Yanti', NULL, '8190000016', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(18, 704, 2, 1, '81234017', '24017', 'Dea Ananda', 'P', 'Serang', '0000-00-00', NULL, NULL, '8123456017', NULL, 'Oki', 'Maya', NULL, '8190000017', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(19, 705, 2, 1, '81234018', '24018', 'Dedi Kurniawan', 'L', 'Cirebon', '0000-00-00', NULL, NULL, '8123456018', NULL, 'Purnomo', 'Dian', NULL, '8190000018', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(20, 706, 2, 1, '81234019', '24019', 'Dewi Sartika', 'P', 'Tasikmalaya', '0000-00-00', NULL, NULL, '8123456019', NULL, 'Qomar', 'Eka', NULL, '8190000019', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(21, 707, 2, 2, '81234020', '24020', 'Dimas Anggara', 'L', 'Garut', '0000-00-00', NULL, NULL, '8123456020', NULL, 'Rudi', 'Indah', NULL, '8190000020', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(22, 708, 3, 1, '81234021', '24021', 'Dini Aminarti', 'P', 'Sukabumi', '0000-00-00', NULL, NULL, '8123456021', NULL, 'Soleh', 'Tuti', NULL, '8190000021', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(23, 709, 3, 1, '81234022', '24022', 'Doni Tata', 'L', 'Cianjur', '0000-00-00', NULL, NULL, '8123456022', NULL, 'Taufik', 'Murni', NULL, '8190000022', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(24, 710, 3, 2, '81234023', '24023', 'Eka Saputra', 'L', 'Subang', '0000-00-00', NULL, NULL, '8123456023', NULL, 'Usman', 'Ani', NULL, '8190000023', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(25, 711, 3, 2, '81234024', '24024', 'Eko Patrio', 'L', 'Purwakarta', '0000-00-00', NULL, NULL, '8123456024', NULL, 'Vicky', 'Ratih', NULL, '8190000024', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(26, 712, 1, 1, '81234025', '24025', 'Elly Sugigi', 'P', 'Karawang', '0000-00-00', NULL, NULL, '8123456025', NULL, 'Wahyu', 'Desi', NULL, '8190000025', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(27, 713, 1, 1, '81234026', '24026', 'Fajar Sadboy', 'L', 'Indramayu', '0000-00-00', NULL, NULL, '8123456026', NULL, 'Xaverius', 'Clara', NULL, '8190000026', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(28, 714, 1, 2, '81234027', '24027', 'Fani Rose', 'P', 'Majalengka', '0000-00-00', NULL, NULL, '8123456027', NULL, 'Yanto', 'Tari', NULL, '8190000027', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(29, 715, 1, 2, '81234028', '24028', 'Farhan Ali', 'L', 'Kuningan', '0000-00-00', NULL, NULL, '8123456028', NULL, 'Zainal', 'Umi', NULL, '8190000028', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(30, 716, 2, 1, '81234029', '24029', 'Fifi Lety', 'P', 'Sumedang', '0000-00-00', NULL, NULL, '8123456029', NULL, 'Adam', 'Eva', NULL, '8190000029', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(31, 717, 2, 1, '81234030', '24030', 'Gilang Dirga', 'L', 'Ciamis', '0000-00-00', NULL, NULL, '8123456030', NULL, 'Beni', 'Citra', NULL, '8190000030', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(32, 718, 2, 1, '81234031', '24031', 'Gita Gutawa', 'P', 'Banjar', '0000-00-00', NULL, NULL, '8123456031', NULL, 'Candra', 'Dina', NULL, '8190000031', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(33, 719, 2, 2, '81234032', '24032', 'Guntur Bumi', 'L', 'Pangandaran', '0000-00-00', NULL, NULL, '8123456032', NULL, 'Dedi', 'Erna', NULL, '8190000032', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(34, 720, 3, 1, '81234033', '24033', 'Hana Hanifah', 'P', 'Cimahi', '0000-00-00', NULL, NULL, '8123456033', NULL, 'Erwin', 'Fani', NULL, '8190000033', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(35, 721, 3, 1, '81234034', '24034', 'Hari Panca', 'L', 'Bandung Barat', '0000-00-00', NULL, NULL, '8123456034', NULL, 'Faris', 'Gina', NULL, '8190000034', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(36, 722, 3, 2, '81234035', '24035', 'Hesti Purwadinata', 'P', 'Jakarta Selatan', '0000-00-00', NULL, NULL, '8123456035', NULL, 'Galih', 'Hani', NULL, '8190000035', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(37, 723, 3, 2, '81234036', '24036', 'Ian Kasela', 'L', 'Jakarta Timur', '0000-00-00', NULL, NULL, '8123456036', NULL, 'Hadi', 'Ika', NULL, '8190000036', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(38, 724, 1, 1, '81234037', '24037', 'Iis Dahlia', 'P', 'Jakarta Barat', '0000-00-00', NULL, NULL, '8123456037', NULL, 'Irwan', 'Jamilah', NULL, '8190000037', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(39, 725, 1, 1, '81234038', '24038', 'Indra Bekti', 'L', 'Jakarta Pusat', '0000-00-00', NULL, NULL, '8123456038', NULL, 'Jaya', 'Kiki', NULL, '8190000038', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(40, 726, 1, 2, '81234039', '24039', 'Indah Permatasari', 'P', 'Jakarta Utara', '0000-00-00', NULL, NULL, '8123456039', NULL, 'Koko', 'Lilis', NULL, '8190000039', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(41, 727, 1, 2, '81234040', '24040', 'Jaka Sembung', 'L', 'Kepulauan Seribu', '0000-00-00', NULL, NULL, '8123456040', NULL, 'Lutfi', 'Mimi', NULL, '8190000040', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(42, 728, 2, 1, '81234041', '24041', 'Juwita Bahar', 'P', 'Banda Aceh', '0000-00-00', NULL, NULL, '8123456041', NULL, 'Mamat', 'Nana', NULL, '8190000041', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(43, 729, 2, 1, '81234042', '24042', 'Kevin Julio', 'L', 'Medan', '0000-00-00', NULL, NULL, '8123456042', NULL, 'Nanang', 'Oki', NULL, '8190000042', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(44, 730, 2, 1, '81234043', '24043', 'Kiki Amalia', 'P', 'Padang', '0000-00-00', NULL, NULL, '8123456043', NULL, 'Otoy', 'Puput', NULL, '8190000043', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(45, 731, 2, 2, '81234044', '24044', 'Lukman Sardi', 'L', 'Pekanbaru', '0000-00-00', NULL, NULL, '8123456044', NULL, 'Pepen', 'Qori', NULL, '8190000044', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(46, 732, 3, 1, '81234045', '24045', 'Luna Maya', 'P', 'Jambi', '0000-00-00', NULL, NULL, '8123456045', NULL, 'Qodir', 'Rere', NULL, '8190000045', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(47, 733, 3, 1, '81234046', '24046', 'Marcel Chandrawinata', 'L', 'Palembang', '0000-00-00', NULL, NULL, '8123456046', NULL, 'Ridwan', 'Sasa', NULL, '8190000046', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(48, 734, 3, 2, '81234047', '24047', 'Marshanda', 'P', 'Bengkulu', '0000-00-00', NULL, NULL, '8123456047', NULL, 'Sandi', 'Tata', NULL, '8190000047', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(49, 735, 3, 2, '81234048', '24048', 'Nabila Syakieb', 'P', 'Bandar Lampung', '0000-00-00', NULL, NULL, '8123456048', NULL, 'Tono', 'Uut', NULL, '8190000048', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(50, 736, 1, 1, '81234049', '24049', 'Raffi Ahmad', 'L', 'Pangkal Pinang', '0000-00-00', NULL, NULL, '8123456049', NULL, 'Udin', 'Vivi', NULL, '8190000049', NULL, NULL, 'Aktif', 'default.png', NULL, NULL),
(51, 737, 1, 1, '81234050', '24050', 'Raisa Andriana', 'P', 'Tanjung Pinang', '0000-00-00', NULL, NULL, '8123456050', NULL, 'Vian', 'Winda', NULL, '8190000050', NULL, NULL, 'Aktif', 'default.png', NULL, NULL);

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
(7, 'Alpin MUldiyana', '3213234503980005', '', '', '1080546870', '$2y$10$jCGgUYPSmjeg.0sEakLcqOY/SYDsYm/Z7NI5gkH3QjIo12.Rp8YMu', NULL, 'guru', '2026-01-03 10:20:32', '2026-01-16 13:20:01'),
(8, 'Rian Alviana', '3213125012910010', '3213125012910010@sekolah.id', '6285155232366', '1080546870', '$2y$10$WToMLtszIUR85adcuinVs.orUVhZTTJsgOmDdhqauA1cTrYyeT3xe', NULL, 'guru', '2026-01-03 10:28:13', '2026-01-04 14:32:38'),
(9, 'YUNI ROSMIANTI', '3213234503980012', 'rikiwahyudin52@gmail.com', '085155232366', '', '$2y$10$Zuzr5pZrnTjTh9Kt1HollOi83032qzzc0G/T2Ool422Lb3NczsWji', NULL, 'guru', '2026-01-03 10:53:38', '2026-01-16 13:20:23'),
(10, 'Super Administrator', 'admin', 'admin@sekolah.id', '081234567890', '123456789', '$2y$10$HEDl4UKWFmKd0x7ZGviw6eDh69BNrM70jW3LvJx01jBP0pseXguBS', NULL, 'admin', '2026-01-03 13:32:24', NULL),
(12, 'Ani Suryani', '2024001', 'ani.suryani@sekolah.id', '085678901234', '', '$2y$10$HEDl4UKWFmKd0x7ZGviw6eDh69BNrM70jW3LvJx01jBP0pseXguBS', NULL, 'admin', '2026-01-03 13:32:24', NULL),
(13, 'xoni Nas', '3213234503980097', '3213234503980097@sekolah.id', '083176915165', NULL, '$2y$10$KNmjCqjA7aZqfmmqCNBDFuXRTE.GR4ZU63meQz9H/3lmgkyBcTK.O', NULL, 'admin', '2026-01-04 14:07:06', '2026-01-04 14:07:06'),
(14, 'Admin Alpin', 'admin.alpin', NULL, NULL, NULL, '$2y$10$xuvJp7OKuCQ2F4jMdwSwNuO0YEdkuVhNn4lxQD8DkAR91xrMkf.Zu', NULL, 'admin', '2026-01-06 03:02:10', NULL),
(687, 'Ahmad Siswa Teladan', '0054321987', '0054321987@student.sch.id', '08123456789', '123456789', '$2y$10$06atMBqijuqd3ngO5l.hQePoPD47xe/pAAtQaJiW6vvf42Ll1ll.S', NULL, 'admin', '2026-01-18 13:54:57', '2026-01-18 13:54:57'),
(688, 'ADITYA PRATAMA', '81234001', '81234001@student.sch.id', '8123456001', '1080546870', '$2y$10$eyie1iYwiJtRf04WJqFozevURpI/2U/R3Xzgq8FBCSWU41iAxvY8O', NULL, 'admin', '2026-01-18 13:54:57', '2026-01-18 13:58:09'),
(689, 'AHMAD FAUZI', '81234002', '81234002@student.sch.id', '8123456002', '1080546870', '$2y$10$vamIH9gp/N.I6SZ4BKJEXeHoVTvZjCaj3fk3gWFddCG8MaMAqsmLC', NULL, 'admin', '2026-01-18 13:54:57', '2026-01-18 14:00:52'),
(690, 'Aisyah Putri', '81234003', '81234003@student.sch.id', '8123456003', '10003', '$2y$10$me1l4STg5JCXcRJmYHg52Ooiab4aZQO6WWytxc9E4UIoo2pLeuZWK', NULL, 'admin', '2026-01-18 13:54:57', '2026-01-18 13:54:57'),
(691, 'Aldi Mahendra', '81234004', '81234004@student.sch.id', '8123456004', '10004', '$2y$10$pClxaUuPmYNzEqPpEycZ4eVDviUuO8PzHgCx15J3Ost05d71tGWHW', NULL, 'admin', '2026-01-18 13:54:58', '2026-01-18 13:54:58'),
(692, 'Amelia Sari', '81234005', '81234005@student.sch.id', '8123456005', '10005', '$2y$10$z4ni8PNC5hC16m2.JrXMD.R4NjpK4y6wi5adU/RYu7WeoaxA3XCwq', NULL, 'admin', '2026-01-18 13:54:58', '2026-01-18 13:54:58'),
(693, 'Andi Saputra', '81234006', '81234006@student.sch.id', '8123456006', '10006', '$2y$10$TqcicSS3Cyx/f012JvvLLO28fxQe/hQlzSwpLLsdynx3bR2r1gnJO', NULL, 'admin', '2026-01-18 13:54:58', '2026-01-18 13:54:58'),
(694, 'Annisa Rahma', '81234007', '81234007@student.sch.id', '8123456007', '10007', '$2y$10$YtkOzm9u.70Dhp0GEIdIyuvU0HKbYGr6H7/sdSO42HLe0J5bqpkLu', NULL, 'admin', '2026-01-18 13:54:58', '2026-01-18 13:54:58'),
(695, 'Arif Hidayat', '81234008', '81234008@student.sch.id', '8123456008', '10008', '$2y$10$P1bLu7w2aSSy7ZQeUyHNyOlCE135lmYsH3fU0TNykwlo15fnyVQSK', NULL, 'admin', '2026-01-18 13:54:58', '2026-01-18 13:54:58'),
(696, 'Ayu Lestari', '81234009', '81234009@student.sch.id', '8123456009', '10009', '$2y$10$IKNY7FmpUNmf5bOhfmV1u.huCC2Ouf8U0U2PMEvONWICQ5SJ2GsqW', NULL, 'admin', '2026-01-18 13:54:58', '2026-01-18 13:54:58'),
(697, 'Bagas Kara', '81234010', '81234010@student.sch.id', '8123456010', '10010', '$2y$10$h49trK46fuQhp5dIUH9H4ORHhrC9pGTz6FUCn6KlU34xwR5S3eFt.', NULL, 'admin', '2026-01-18 13:54:58', '2026-01-18 13:54:58'),
(698, 'Bayu Nugraha', '81234011', '81234011@student.sch.id', '8123456011', '10011', '$2y$10$S4H5Mq/SDD/ZxBNuigFSWeogGB5Nk5tpJodOB04tcQxoHEAdOTAve', NULL, 'admin', '2026-01-18 13:54:59', '2026-01-18 13:54:59'),
(699, 'Bunga Citra', '81234012', '81234012@student.sch.id', '8123456012', '10012', '$2y$10$R7l3bSR8ACT6yx0upmEuYOmb9mOzrcbAiirlsEZpkmLDOtoYMS9zm', NULL, 'admin', '2026-01-18 13:54:59', '2026-01-18 13:54:59'),
(700, 'Cahya Ramadhan', '81234013', '81234013@student.sch.id', '8123456013', '10013', '$2y$10$Yym/3jawgKSsqflwC9X8OuRA3gIhWKauF30KDdKPj8MYSE3Vaa04K', NULL, 'admin', '2026-01-18 13:54:59', '2026-01-18 13:54:59'),
(701, 'Cantika Dewi', '81234014', '81234014@student.sch.id', '8123456014', '10014', '$2y$10$nd21PCI83WShIsTN6q7FduiEUlZakazwphUfL8ci63bfKG5yJClhC', NULL, 'admin', '2026-01-18 13:54:59', '2026-01-18 13:54:59'),
(702, 'Daffa Ardiansyah', '81234015', '81234015@student.sch.id', '8123456015', '10015', '$2y$10$L7iUk.sa06msGJHyZ0FLMOJ88lahUYH5CEyrv.WTq97zQGMlwNsK6', NULL, 'admin', '2026-01-18 13:54:59', '2026-01-18 13:54:59'),
(703, 'Dani Firmansyah', '81234016', '81234016@student.sch.id', '8123456016', '10016', '$2y$10$HZbDzSvaN/0dUQGw65QwYe/M2OrBphAOhT1N0GWq3eyjC2S81c8ia', NULL, 'admin', '2026-01-18 13:54:59', '2026-01-18 13:54:59'),
(704, 'Dea Ananda', '81234017', '81234017@student.sch.id', '8123456017', '10017', '$2y$10$6UZrj/nX48Nse9mYkRB2TORgwRj0xEnunM9UHQyW1DwpSUpbdyghy', NULL, 'admin', '2026-01-18 13:54:59', '2026-01-18 13:54:59'),
(705, 'Dedi Kurniawan', '81234018', '81234018@student.sch.id', '8123456018', '10018', '$2y$10$zdSkLmFjgpeeSxwmRFsZZO4KnBV7CeWLtLl3cByohVxIrzlBD3lFO', NULL, 'admin', '2026-01-18 13:54:59', '2026-01-18 13:54:59'),
(706, 'Dewi Sartika', '81234019', '81234019@student.sch.id', '8123456019', '10019', '$2y$10$6RQInYc7gavjnLi07Yc9OuPi5qvFtOCUcqRkDhoGIfsLFcSWbT9yK', NULL, 'admin', '2026-01-18 13:54:59', '2026-01-18 13:54:59'),
(707, 'Dimas Anggara', '81234020', '81234020@student.sch.id', '8123456020', '10020', '$2y$10$9JGnUgKvrxgZeTTWE.bhc.aTalDLAEuRlmAj5r1sHrIjrYLehXEg6', NULL, 'admin', '2026-01-18 13:54:59', '2026-01-18 13:54:59'),
(708, 'Dini Aminarti', '81234021', '81234021@student.sch.id', '8123456021', '10021', '$2y$10$hu4Qw2ltsfXvRnqYcphq.ODWz7Jz/JAlGlzKBFqxGV7nKLdTVc0Wi', NULL, 'admin', '2026-01-18 13:54:59', '2026-01-18 13:54:59'),
(709, 'Doni Tata', '81234022', '81234022@student.sch.id', '8123456022', '10022', '$2y$10$nqD/Tc0c8f8.K9UfJBgtQO8H63eS9cp/JZCo7YSegNWU4XWVK5xXW', NULL, 'admin', '2026-01-18 13:55:00', '2026-01-18 13:55:00'),
(710, 'Eka Saputra', '81234023', '81234023@student.sch.id', '8123456023', '10023', '$2y$10$uZe7QaYjb9S2r/Iq9045OOLSsE3s2LV7bazDiOeZO7.yBSY.cOONG', NULL, 'admin', '2026-01-18 13:55:00', '2026-01-18 13:55:00'),
(711, 'Eko Patrio', '81234024', '81234024@student.sch.id', '8123456024', '10024', '$2y$10$w2WRKe0TKBOy/d.0HiFaJOPFyScqCerFtxxL0CsTDXOq4hY/py38y', NULL, 'admin', '2026-01-18 13:55:00', '2026-01-18 13:55:00'),
(712, 'Elly Sugigi', '81234025', '81234025@student.sch.id', '8123456025', '10025', '$2y$10$TdB3WcCCaKAQraVLvjCztesDwPb/GYPa8e4e5E4/qwvDVNDYc7QZ2', NULL, 'admin', '2026-01-18 13:55:00', '2026-01-18 13:55:00'),
(713, 'Fajar Sadboy', '81234026', '81234026@student.sch.id', '8123456026', '10026', '$2y$10$F1mYYHEdOhf7e6CW5txSmOmdXjRmZw19YkGdAc6OOIEQTSkJgc9xS', NULL, 'admin', '2026-01-18 13:55:00', '2026-01-18 13:55:00'),
(714, 'Fani Rose', '81234027', '81234027@student.sch.id', '8123456027', '10027', '$2y$10$W1C9F4Y06X95UKEZfUnoyutq5xASRcIgr.Aw6iiwQytDpeUncUVpm', NULL, 'admin', '2026-01-18 13:55:00', '2026-01-18 13:55:00'),
(715, 'Farhan Ali', '81234028', '81234028@student.sch.id', '8123456028', '10028', '$2y$10$RatATm107E1rTLSWOwPQ1O0V7x.k5cNwNCBAy9nxZAUYThwanXZI.', NULL, 'admin', '2026-01-18 13:55:00', '2026-01-18 13:55:00'),
(716, 'Fifi Lety', '81234029', '81234029@student.sch.id', '8123456029', '10029', '$2y$10$3op1Kxqf048SNbR4G6Zaf.0KYMk05MNpIZW4WquNGB43ct9JinA1G', NULL, 'admin', '2026-01-18 13:55:00', '2026-01-18 13:55:00'),
(717, 'Gilang Dirga', '81234030', '81234030@student.sch.id', '8123456030', '10030', '$2y$10$mVBeOTOIEJRTKZlgsGiaOeOSP.MbM4psnXsfkg19LKl36xhp0XKnq', NULL, 'admin', '2026-01-18 13:55:00', '2026-01-18 13:55:00'),
(718, 'Gita Gutawa', '81234031', '81234031@student.sch.id', '8123456031', '10031', '$2y$10$A.lFejKdytHxH6r2TroyqeATIL3cDu7t5SX9FdamZ49GQS5rvXcgu', NULL, 'admin', '2026-01-18 13:55:00', '2026-01-18 13:55:00'),
(719, 'Guntur Bumi', '81234032', '81234032@student.sch.id', '8123456032', '10032', '$2y$10$8zfbvmyifBCnfRFTve0hX.kxiOoZnueeQwx.AJ4efGKzZ.su5sPAi', NULL, 'admin', '2026-01-18 13:55:00', '2026-01-18 13:55:00'),
(720, 'Hana Hanifah', '81234033', '81234033@student.sch.id', '8123456033', '10033', '$2y$10$OU00nSj3w1ex5OSc/X97P.323hQ2loY7ByimbhdiD8TPMCJpCH4Ym', NULL, 'admin', '2026-01-18 13:55:01', '2026-01-18 13:55:01'),
(721, 'Hari Panca', '81234034', '81234034@student.sch.id', '8123456034', '10034', '$2y$10$siM7qJvNqQHjuzkgXxPCJuaFBWcj5k8AErx8qrkWsAqt62oTjXiFu', NULL, 'admin', '2026-01-18 13:55:01', '2026-01-18 13:55:01'),
(722, 'Hesti Purwadinata', '81234035', '81234035@student.sch.id', '8123456035', '10035', '$2y$10$p6vg5o6fN0aemL3UtuS83ual5m0IlBEhzSmA2Gi8qqb1AaMASKWZK', NULL, 'admin', '2026-01-18 13:55:01', '2026-01-18 13:55:01'),
(723, 'Ian Kasela', '81234036', '81234036@student.sch.id', '8123456036', '10036', '$2y$10$xFlE/.mJmLJZdjkDjCD.v.Wa7LZQTyFgA1PMFpBd6HeU/lzvLgxaq', NULL, 'admin', '2026-01-18 13:55:01', '2026-01-18 13:55:01'),
(724, 'Iis Dahlia', '81234037', '81234037@student.sch.id', '8123456037', '10037', '$2y$10$Bc0KiF41T6v95siQk1jEB.3RihCPU1lhJvUfh69q5OK3K.b32ihD.', NULL, 'admin', '2026-01-18 13:55:01', '2026-01-18 13:55:01'),
(725, 'Indra Bekti', '81234038', '81234038@student.sch.id', '8123456038', '10038', '$2y$10$0JzOMZ4jfWRn46TrR9UDOepU5.RUY9Vhp7GCDL6hNuKRfZ0b0YGTO', NULL, 'admin', '2026-01-18 13:55:01', '2026-01-18 13:55:01'),
(726, 'Indah Permatasari', '81234039', '81234039@student.sch.id', '8123456039', '10039', '$2y$10$0xYSvMJ3lFPcObmofpKib.fN/0aHO3CNAe2VPbn7R6jels691UM2C', NULL, 'admin', '2026-01-18 13:55:01', '2026-01-18 13:55:01'),
(727, 'Jaka Sembung', '81234040', '81234040@student.sch.id', '8123456040', '10040', '$2y$10$XSGT2Gb/HogkUafIzCCfpOlQL6g2wwqTALfEHwxnFdi/KgdgTunmy', NULL, 'admin', '2026-01-18 13:55:01', '2026-01-18 13:55:01'),
(728, 'Juwita Bahar', '81234041', '81234041@student.sch.id', '8123456041', '10041', '$2y$10$i4MOcClwLiVPk/c6rOHK0O4Ll.IY4nR3fc7UDVt0NOIJW49yH2Z7m', NULL, 'admin', '2026-01-18 13:55:01', '2026-01-18 13:55:01'),
(729, 'Kevin Julio', '81234042', '81234042@student.sch.id', '8123456042', '10042', '$2y$10$oVjq7nyBoOyFD6VeZlt9AOlByfIZu2rz.YPKnZrjSiacZvHkB0Wpu', NULL, 'admin', '2026-01-18 13:55:01', '2026-01-18 13:55:01'),
(730, 'Kiki Amalia', '81234043', '81234043@student.sch.id', '8123456043', '10043', '$2y$10$pk0upDfIcOzQ4Ruh4HmlL.S/sAazd2cuY/WfhkOIxmrGR76XVwz5q', NULL, 'admin', '2026-01-18 13:55:01', '2026-01-18 13:55:01'),
(731, 'Lukman Sardi', '81234044', '81234044@student.sch.id', '8123456044', '10044', '$2y$10$SwxjtK9Tyj0UK.qoNik3HeeSXIyA1ol5tKT1Pn3eyNdBeQYO9r2Qe', NULL, 'admin', '2026-01-18 13:55:01', '2026-01-18 13:55:01'),
(732, 'Luna Maya', '81234045', '81234045@student.sch.id', '8123456045', '10045', '$2y$10$lI.ATHcdx1dRd58SPS9lMu5NF94T41C/mk0oY.U8EoaH2rFoC2x8O', NULL, 'admin', '2026-01-18 13:55:02', '2026-01-18 13:55:02'),
(733, 'Marcel Chandrawinata', '81234046', '81234046@student.sch.id', '8123456046', '10046', '$2y$10$fkRKAyljPeRSa3XPL.QqKecdIBBmS/nurGxs2rixROVlzbUcZwOea', NULL, 'admin', '2026-01-18 13:55:02', '2026-01-18 13:55:02'),
(734, 'Marshanda', '81234047', '81234047@student.sch.id', '8123456047', '10047', '$2y$10$hwkr3.CFBqqJqfTMlW/wseJmaTNZQNGu5lKbzPTaDL2W6csSdXsZC', NULL, 'admin', '2026-01-18 13:55:02', '2026-01-18 13:55:02'),
(735, 'Nabila Syakieb', '81234048', '81234048@student.sch.id', '8123456048', '10048', '$2y$10$QKSbfizHTtGprq0Q5mWeEOlYi8DpznYsMZho9xRFF85/kwmyJ1ho2', NULL, 'admin', '2026-01-18 13:55:02', '2026-01-18 13:55:02'),
(736, 'Raffi Ahmad', '81234049', '81234049@student.sch.id', '8123456049', '10049', '$2y$10$WBnlKouFxsgQ.yG8DpURpuJ1xdOq35qLEHgqWER7Qg8w89atxedzK', NULL, 'admin', '2026-01-18 13:55:02', '2026-01-18 13:55:02'),
(737, 'Raisa Andriana', '81234050', '81234050@student.sch.id', '8123456050', '10050', '$2y$10$0CvcRGZ/bTqWyujhn4a2ou7gYqnOna8mMYDWjwnLghlKTqjlOiKfG', NULL, 'admin', '2026-01-18 13:55:02', '2026-01-18 13:55:02'),
(791, 'Budi Santoso, S.Pd', '199001012022011001', '199001012022011001@sekolah.id', '081234567890', NULL, '$2y$10$3ChcmEd0yUVtvOu5RZwiYOtjKKGRxK6emsYMTS4pts2HDMRp5L4C6', NULL, 'guru', '2026-01-18 15:36:12', '2026-01-18 15:36:12');

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
(52, 8, 9),
(105, 687, 11),
(106, 688, 11),
(107, 689, 11),
(108, 690, 11),
(109, 691, 11),
(110, 692, 11),
(111, 693, 11),
(112, 694, 11),
(113, 695, 11),
(114, 696, 11),
(115, 697, 11),
(116, 698, 11),
(117, 699, 11),
(118, 700, 11),
(119, 701, 11),
(120, 702, 11),
(121, 703, 11),
(122, 704, 11),
(123, 705, 11),
(124, 706, 11),
(125, 707, 11),
(126, 708, 11),
(127, 709, 11),
(128, 710, 11),
(129, 711, 11),
(130, 712, 11),
(131, 713, 11),
(132, 714, 11),
(133, 715, 11),
(134, 716, 11),
(135, 717, 11),
(136, 718, 11),
(137, 719, 11),
(138, 720, 11),
(139, 721, 11),
(140, 722, 11),
(141, 723, 11),
(142, 724, 11),
(143, 725, 11),
(144, 726, 11),
(145, 727, 11),
(146, 728, 11),
(147, 729, 11),
(148, 730, 11),
(149, 731, 11),
(150, 732, 11),
(151, 733, 11),
(152, 734, 11),
(153, 735, 11),
(154, 736, 11),
(155, 737, 11),
(156, 791, 8);

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
-- Indeks untuk tabel `tbl_jadwal`
--
ALTER TABLE `tbl_jadwal`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_jam_master`
--
ALTER TABLE `tbl_jam_master`
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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nisn` (`nisn`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT untuk tabel `tbl_jadwal`
--
ALTER TABLE `tbl_jadwal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `tbl_jam_master`
--
ALTER TABLE `tbl_jam_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tbl_mapel_jurusan`
--
ALTER TABLE `tbl_mapel_jurusan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=792;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=157;

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
