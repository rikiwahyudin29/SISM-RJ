-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 05 Feb 2026 pada 12.59
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

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
-- Struktur dari tabel `tbl_absensi_mapel`
--

CREATE TABLE `tbl_absensi_mapel` (
  `id` int(11) NOT NULL,
  `id_jurnal` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `status` enum('H','S','I','A') DEFAULT 'H',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_absensi_mapel`
--

INSERT INTO `tbl_absensi_mapel` (`id`, `id_jurnal`, `id_siswa`, `status`, `created_at`) VALUES
(96, 7, 2, 'H', '2026-01-29 21:41:11'),
(97, 7, 3, 'H', '2026-01-29 21:41:11'),
(98, 7, 1, 'H', '2026-01-29 21:41:11'),
(99, 7, 4, 'H', '2026-01-29 21:41:11'),
(100, 7, 5, 'H', '2026-01-29 21:41:11'),
(101, 7, 14, 'H', '2026-01-29 21:41:11'),
(102, 7, 15, 'H', '2026-01-29 21:41:11'),
(103, 7, 16, 'H', '2026-01-29 21:41:11'),
(104, 7, 17, 'H', '2026-01-29 21:41:11'),
(105, 7, 26, 'H', '2026-01-29 21:41:11'),
(106, 7, 27, 'H', '2026-01-29 21:41:11'),
(107, 7, 28, 'H', '2026-01-29 21:41:11'),
(108, 7, 29, 'H', '2026-01-29 21:41:11'),
(109, 7, 38, 'H', '2026-01-29 21:41:11'),
(110, 7, 40, 'H', '2026-01-29 21:41:11'),
(111, 7, 39, 'H', '2026-01-29 21:41:11'),
(112, 7, 41, 'H', '2026-01-29 21:41:11'),
(113, 7, 50, 'H', '2026-01-29 21:41:11'),
(114, 7, 51, 'H', '2026-01-29 21:41:11');

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
-- Struktur dari tabel `tbl_bank_soal`
--

CREATE TABLE `tbl_bank_soal` (
  `id` int(11) NOT NULL,
  `kode_bank` varchar(50) NOT NULL,
  `judul_ujian` varchar(255) NOT NULL,
  `id_mapel` int(11) NOT NULL,
  `id_guru` int(11) NOT NULL,
  `jumlah_soal` int(11) DEFAULT 0,
  `status` varchar(20) DEFAULT 'Tidak Aktif',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `durasi` int(11) DEFAULT 60,
  `acak_soal` int(11) DEFAULT 1,
  `wajib_lokasi` int(11) DEFAULT 0 COMMENT '1=Wajib GPS',
  `lat_ujian` varchar(50) DEFAULT NULL,
  `long_ujian` varchar(50) DEFAULT NULL,
  `radius_ujian` int(11) DEFAULT 100,
  `token` varchar(6) DEFAULT NULL COMMENT 'Token Masuk Ujian',
  `acak_opsi` int(11) DEFAULT 0 COMMENT '1=Jawaban Diacak, 0=Urut Abjad',
  `jumlah_soal_pg` int(11) DEFAULT 0 COMMENT 'Total Soal Pilihan Ganda',
  `jumlah_soal_esai` int(11) DEFAULT 0 COMMENT 'Total Soal Esai'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_bank_soal`
--

INSERT INTO `tbl_bank_soal` (`id`, `kode_bank`, `judul_ujian`, `id_mapel`, `id_guru`, `jumlah_soal`, `status`, `created_at`, `updated_at`, `durasi`, `acak_soal`, `wajib_lokasi`, `lat_ujian`, `long_ujian`, `radius_ujian`, `token`, `acak_opsi`, `jumlah_soal_pg`, `jumlah_soal_esai`) VALUES
(2, 'BS-1768813985', 'US DJTK', 2, 3, 4, 'Tidak Aktif', '2026-01-19 09:13:05', '2026-01-23 22:49:58', 60, 1, 0, NULL, NULL, 100, NULL, 0, 1, 0),
(3, 'BS-1768817420', 'US Matematika', 1, 3, 7, 'Tidak Aktif', '2026-01-19 10:10:20', '2026-01-27 19:32:19', 60, 1, 0, NULL, NULL, 100, NULL, 0, 40, 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_bank_soal_kelas`
--

CREATE TABLE `tbl_bank_soal_kelas` (
  `id` int(11) NOT NULL,
  `id_bank_soal` int(11) NOT NULL,
  `id_kelas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_bank_soal_kelas`
--

INSERT INTO `tbl_bank_soal_kelas` (`id`, `id_bank_soal`, `id_kelas`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1),
(4, 3, 2),
(5, 3, 3),
(6, 3, 4),
(7, 3, 5),
(8, 3, 40);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_dapodik_setting`
--

CREATE TABLE `tbl_dapodik_setting` (
  `id` int(11) NOT NULL,
  `npsn` varchar(20) NOT NULL,
  `ip_dapodik` varchar(100) NOT NULL COMMENT 'Contoh: http://192.168.1.5:5774',
  `key_integrasi` varchar(255) NOT NULL,
  `status_koneksi` enum('Terhubung','Gagal') DEFAULT 'Gagal',
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_dapodik_setting`
--

INSERT INTO `tbl_dapodik_setting` (`id`, `npsn`, `ip_dapodik`, `key_integrasi`, `status_koneksi`, `updated_at`) VALUES
(1, '20217025', 'http://192.168.1.9:5774', 'gsqvDqTmxpMtQz5', 'Terhubung', '2026-01-30 11:59:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_divisi`
--

CREATE TABLE `tbl_divisi` (
  `id` int(11) NOT NULL,
  `nama_divisi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_divisi`
--

INSERT INTO `tbl_divisi` (`id`, `nama_divisi`) VALUES
(1, 'Tata Usaha'),
(2, 'Kurikulum'),
(3, 'Kesiswaan'),
(4, 'Sarpras/Umum'),
(5, 'Humas');

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
  `id_user` int(11) DEFAULT NULL,
  `dapodik_id` varchar(50) DEFAULT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `nip` varchar(30) DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `jk` enum('L','P') DEFAULT 'L',
  `rfid_uid` varchar(50) DEFAULT NULL,
  `qr_code` varchar(100) DEFAULT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `nuptk` varchar(30) DEFAULT NULL,
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
  `updated_at` datetime DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `ibu_kandung` varchar(100) DEFAULT NULL,
  `status_kepegawaian` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(20) DEFAULT 'guru'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_guru`
--

INSERT INTO `tbl_guru` (`id`, `id_user`, `dapodik_id`, `user_id`, `nip`, `nik`, `jk`, `rfid_uid`, `qr_code`, `nama_lengkap`, `nuptk`, `gelar_depan`, `gelar_belakang`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `no_hp`, `email`, `pendidikan_terakhir`, `sertifikasi`, `status_guru`, `foto`, `created_at`, `updated_at`, `tgl_lahir`, `ibu_kandung`, `status_kepegawaian`, `password`, `role`) VALUES
(3, NULL, NULL, 6, '3213012912010002', NULL, 'L', NULL, NULL, 'Riki Wahyudin', NULL, '', 'S.Tr.T', 'L', 'SUBANG', NULL, 'Kp.Ciceuri 06/03', '85155232366', '', NULL, NULL, '', '1767435241_b33cd1f830710fd763a4.jpg', '2026-01-03 10:14:01', '2026-01-19 11:14:29', '2001-12-29', 'Ida Widiawati', NULL, NULL, 'guru'),
(4, NULL, NULL, 7, '3213234503980005', NULL, 'L', NULL, NULL, 'Alpin MUldiyana', NULL, '', 'S.Tr.T', 'L', '', '0000-00-00', 'hsdfbjshdfbj', '', '', 'S1 Pendidikan Matematika', 'ya', 'GTY', '1767435632_82b3176141acf6081438.png', '2026-01-03 10:20:32', '2026-01-16 13:20:01', NULL, NULL, NULL, NULL, 'guru'),
(5, NULL, NULL, 8, '3213125012910010', NULL, 'L', NULL, NULL, 'Rian Alviana', NULL, '', 'S.Or', 'L', NULL, NULL, 'xvcdfgbdgf', NULL, NULL, NULL, NULL, 'GTY', '1767436093_029684e44eb3eacca9e5.jpg', '2026-01-03 10:28:13', '2026-01-04 14:32:38', NULL, NULL, NULL, NULL, 'guru'),
(6, NULL, NULL, 9, '3213234503980012', NULL, 'L', NULL, NULL, 'YUNI ROSMIANTI', NULL, '', 'S.Pd', 'L', '', '0000-00-00', 'CIKASUNGKA', '085155232366', 'rikiwahyudin52@gmail.com', '', '', 'GTY', '1767437618_fd0a08c5b4c7ddd8e12f.png', '2026-01-03 10:53:38', '2026-01-16 13:20:23', NULL, NULL, NULL, NULL, 'guru'),
(8, NULL, NULL, 13, '3213234503980097', '3213280802830001', 'L', '', NULL, 'xoni Nas', '', '', 'S.Pd', 'L', '', NULL, 'CIKASUNGKA', '', NULL, '', NULL, 'GTY', '1767535626_a8c48a52cdd26a997530.jpg', '2026-01-04 14:07:07', '2026-01-04 14:07:07', '0000-00-00', '', 'GTY', NULL, 'guru'),
(64, NULL, NULL, 791, '199001012022011001', NULL, 'L', NULL, NULL, 'Budi Santoso, S.Pd', NULL, NULL, NULL, 'L', NULL, NULL, NULL, '081234567890', NULL, 'S1 Pendidikan Matematika', NULL, 'PNS', 'default.png', '2026-01-18 15:36:12', '2026-01-18 15:36:12', NULL, NULL, NULL, NULL, 'guru'),
(65, 1272, 'dfc82442-5e47-4100-95bb-05be663b86b3', NULL, '', '3213280802830001', 'L', NULL, NULL, 'Ade Sunandar', '4134761663200033', NULL, NULL, 'L', 'Subang', NULL, NULL, NULL, NULL, NULL, NULL, 'GTY', 'default.png', NULL, NULL, '1983-02-08', NULL, 'GTY/PTY', '$2y$10$ROYWAcOswLn6qGrDJXb43uMoG6HhBK8k9QwrWWEt.n9aNsCBfKLqO', 'guru'),
(66, 1273, 'f9bf8a88-3e47-4b82-b6d7-09a12177e3bc', NULL, '', '3213010306950004', 'L', NULL, NULL, 'Aep Saepudin', '6935773674130192', NULL, NULL, 'L', 'SUBANG', NULL, NULL, NULL, NULL, NULL, NULL, 'GTY', 'default.png', NULL, NULL, '1995-06-03', NULL, 'Guru Honor Sekolah', '$2y$10$3kp4WLqFY9x37N1pc69e0uBQJU6A0mpYIvzMnCPRsIe1kjPIEmY7m', 'guru'),
(67, 1274, 'cc6f29a7-4e71-4d4d-ae3f-00b2faf46400', NULL, '', '3213191010970001', 'L', '', NULL, 'Agun Gunawan', '1739779680130102', '', '', 'L', 'Subang', NULL, '', '', NULL, '', NULL, 'GTY', 'default.png', NULL, NULL, '2001-04-07', '', 'GTY', '$2y$10$qHyTjIEMBos3K9tiV6KcH./WEyN9vQtNqR7lbXhZQ27i8CeaY527y', 'guru'),
(68, 1275, '558b93e2-a2f7-4458-95a4-7f863a529106', NULL, '', '3213121804850003', 'L', NULL, NULL, 'Aripin', '2750763664130152', NULL, NULL, 'L', 'Bandung', NULL, NULL, NULL, NULL, NULL, NULL, 'GTY', 'default.png', NULL, NULL, '1985-04-18', NULL, 'GTY/PTY', '$2y$10$mldwGs2oc791VSVRahfauugbX2p2bc2jMWnLNrmSYq54nWDoRZ1jq', 'guru'),
(69, 1253, '7e3b3490-7bbb-4af5-83a3-0c89fbae5f67', NULL, '', '3213086612960004', 'P', NULL, NULL, 'CICA ROIHATUL JANAH', NULL, NULL, NULL, 'L', 'SUBANG', NULL, NULL, NULL, NULL, NULL, NULL, 'GTY', 'default.png', NULL, NULL, '1996-12-26', NULL, 'GTY/PTY', '$2y$10$yd9Mu8WahdW2UC478SOexuI3nW33Tcp8h5vDOuvekJXBzhjE6avE.', 'guru'),
(70, 1276, 'b6d9ca62-8958-4573-9599-f79c298444dd', NULL, '', '3213294311910001', 'P', NULL, NULL, 'FIKRI MAEDAN FAHMI', '6435769670230313', NULL, NULL, 'L', 'SUBANG', NULL, NULL, NULL, NULL, NULL, NULL, 'GTY', 'default.png', NULL, NULL, '1991-11-03', NULL, 'GTY/PTY', '$2y$10$nC6YDQXhBjgB/XaBi69wj.La9ISC7IUaFG1a5NfrQHW6exXA.EY1i', 'guru'),
(71, 1277, '530f10da-41fd-48b8-b840-d0ac5a134ce7', NULL, '', '3213026101960005', 'P', NULL, NULL, 'FITRI ANJANI SAEPUDIN', '2453774675230182', NULL, NULL, 'L', 'SUBANG', NULL, NULL, NULL, NULL, NULL, NULL, 'GTY', 'default.png', NULL, NULL, '1996-01-21', NULL, 'GTY/PTY', '$2y$10$3ED1stdj71ByiQxJPTK7AOBKIIXmPM0Q04uLAaAwWP4sxReG1BKgW', 'guru'),
(72, 1278, '32396879-2652-4533-9412-f0682a2d5904', NULL, '198108162009021002', '3213021608810010', 'L', NULL, NULL, 'IIK TAOPIK HASAN', '8148759660200023', NULL, NULL, 'L', 'Subang', NULL, NULL, NULL, NULL, NULL, NULL, 'GTY', 'default.png', NULL, NULL, '1981-08-16', NULL, 'PNS Diperbantukan', '$2y$10$S.vcPuk3uDutqgiMX/4OK.gKVPgpm9EF0FIlRmcC7AopfuWEISwPe', 'guru'),
(73, 1279, '67363de4-ae0e-4cd7-8106-e5970b69e36d', NULL, '', '3213264908880001', 'P', NULL, NULL, 'Irma Purwanti', '4141776677230233', NULL, NULL, 'L', 'Bandung', NULL, NULL, NULL, NULL, NULL, NULL, 'GTY', 'default.png', NULL, NULL, '1998-08-09', NULL, 'GTY/PTY', '$2y$10$WlqiXznMmnZgwSRC22Z.4OzQ7AqMMq4Yw1NPLgPXufY00/XFDQzHW', 'guru'),
(74, 1280, '0945342c-97a6-404d-8d5a-e7126f33dc58', NULL, '', '3213020408830007', 'L', NULL, NULL, 'Iwan Setiawan', '1136761662130133', NULL, NULL, 'L', 'Subang', NULL, NULL, NULL, NULL, NULL, NULL, 'GTY', 'default.png', NULL, NULL, '1983-08-04', NULL, 'GTY/PTY', '$2y$10$ZMJRxbAbLTNjQtWMXod9Fec5DjarCAJ1HdSjfnYSRr/Fy1gffuit.', 'guru'),
(75, 1281, '6d0a61a2-8c8d-41a4-9e31-b922206f28c0', NULL, '', '3213120702740001', 'L', NULL, NULL, 'M. D. AMUNG SUNARYA, S.AG', '2539752653120002', NULL, NULL, 'L', 'Majalengka', NULL, NULL, NULL, NULL, NULL, NULL, 'GTY', 'default.png', NULL, NULL, '1974-02-07', NULL, 'GTY/PTY', '$2y$10$A4slDYNKp12Mv2s6gNw.5eoV3aEuko0jzK0v4unRDQTkoUQCRbaFO', 'guru'),
(76, 1282, '13c97747-9d51-462b-bb83-d8579d266016', NULL, '', '3213026705900001', 'P', NULL, NULL, 'Nurfitria', '4859768669230202', NULL, NULL, 'L', 'SUBANG', NULL, NULL, NULL, NULL, NULL, NULL, 'GTY', 'default.png', NULL, NULL, '1990-05-27', NULL, 'GTY/PTY', '$2y$10$n6Bif9LINZQnL2LRz.f2vOOV.wcw6pTGt.xQd73yHAyyeTODK3nYq', 'guru'),
(77, 1283, '8c4a110b-15d1-11ec-a210-42010aba8128', NULL, '', '3213011703970002', 'L', NULL, NULL, 'RIAN ALVIANA', '0649775676130212', NULL, NULL, 'L', 'SUBANG', NULL, NULL, NULL, NULL, NULL, NULL, 'GTY', 'default.png', NULL, NULL, '1997-03-17', NULL, 'GTY/PTY', '$2y$10$38R5nW2MvOdPdEvOpzWig.P4ZW58GWkPRrFlnfo1v4cpKl93QN1u6', 'guru'),
(78, 1284, '786212af-3f10-409e-9173-4cd3fbfd32bb', NULL, '', '3213021208930003', 'L', NULL, NULL, 'Rijal Agustian', '9144771672130073', NULL, NULL, 'L', 'SUBANG', NULL, NULL, NULL, NULL, NULL, NULL, 'GTY', 'default.png', NULL, NULL, '1993-08-12', NULL, 'GTY/PTY', '$2y$10$/aJWxGDuokCBxD9.meUJa.O42u3HFsoluKCshObtfnMzzs6E.qP8O', 'guru'),
(79, 6, '4f1e8f2d-736f-45dd-9f8f-b8a007e7c161', NULL, '', '3213012912010002', 'L', '', NULL, 'Riki Wahyudin', '3561779680130043', '', 'S.Tr.T', 'L', 'SUBANG', NULL, 'Kp.Ciceuri 06/03', '85155232366', NULL, '', NULL, 'GTY', 'default.png', NULL, NULL, '2001-12-29', 'Ida Widiawati', 'GTY/PTY', '$2y$10$zVeU9epDNmutkmhUOvrpDOmzyfNsGU8vAPWGcs8tcuF/2P8BPtwS.', 'guru'),
(80, 1285, '93d29b85-b8d0-41f8-b4e4-a66cca67268f', NULL, '', '3213016702970002', 'P', '', NULL, 'RIRI FEBRIANA', '7559775676230122', '', '', 'P', 'SUBANG', NULL, '', '', NULL, '', NULL, 'GTY', 'default.png', NULL, NULL, '1997-02-27', NULL, 'GTY/PTY', '$2y$10$ytCR/SY4qBpwzYjjzMJkN.WFHDV8gcTPP3m/FfgrRtHmFaZiQPdMO', 'guru'),
(81, 1265, '7d6ef429-0acb-4400-aae8-f64624ceb820', NULL, '', '3205190607890001', 'L', NULL, NULL, 'Saepudin', NULL, NULL, NULL, 'L', 'Sumedang', NULL, NULL, NULL, NULL, NULL, NULL, 'GTY', 'default.png', NULL, NULL, '1989-07-06', NULL, 'GTY/PTY', '$2y$10$/fiV4Wrl/MyWn.0jjZnRA.0DPzWn.X.4EoxczRw9wO2HkkRY0QX7a', 'guru'),
(82, 1266, 'e35025c3-a1d2-4eb0-b4e8-507cc997a00a', NULL, '', '3212230301960003', 'L', NULL, NULL, 'SHALEH AFIF', NULL, NULL, NULL, 'L', 'INDRAMAYU', NULL, NULL, NULL, NULL, NULL, NULL, 'GTY', 'default.png', NULL, NULL, '1996-01-03', NULL, 'GTY/PTY', '$2y$10$ypEGf23lyKFdL78Yx/lTtuhWWqgBvCOYi8ROl3oMAK.teTwH.o4TC', 'guru'),
(83, 1286, '1454cff0-32f1-4390-8db5-b8e6d440f891', NULL, '', '3213125005900004', 'P', NULL, NULL, 'Siti Nurjanah', '5655768669230452', NULL, NULL, 'L', 'SUBANG', NULL, NULL, NULL, NULL, NULL, NULL, 'GTY', 'default.png', NULL, NULL, '1990-03-23', NULL, 'GTY/PTY', '$2y$10$1NuB63VzRozlR1ai.ncOCu36A3xLBrLrshG7Hmi3YagBhjwvmytPa', 'guru'),
(84, 1287, '8ad6472b-46a8-4a57-a3fe-b142f10915f8', NULL, '', '3213196009940001', 'P', NULL, NULL, 'SIVA ISTIKOMAH', '7252772673230213', NULL, NULL, 'L', 'Subang', NULL, NULL, NULL, NULL, NULL, NULL, 'GTY', 'default.png', NULL, NULL, '1994-09-20', NULL, 'GTY/PTY', '$2y$10$O7eyXWi46HoMiCpeO5FViOzfSTJTQUETyMaVJRP3FALHAyPSMBVVG', 'guru'),
(85, 1288, '9924e8da-05e3-4bdc-afc7-782a932e5700', NULL, '', '3213262404630002', 'L', NULL, NULL, 'Suparman', '1756741644200022', NULL, NULL, 'L', 'Subang', NULL, NULL, NULL, NULL, NULL, NULL, 'GTY', 'default.png', NULL, NULL, '1963-04-24', NULL, 'GTY/PTY', '$2y$10$l2W3nBLmN5sEepfQBWuE7eHMvD7J4vYQV4DoO8MGPcCJDi1XL9FqS', 'guru'),
(86, 1289, '2736c679-dc6e-496e-bb39-1d81c0114d87', NULL, '', '3213121008920005', 'L', NULL, NULL, 'Triana Nugraha', '8142770671130063', NULL, NULL, 'L', 'subang', NULL, NULL, NULL, NULL, NULL, NULL, 'GTY', 'default.png', NULL, NULL, '1992-08-10', NULL, 'GTY/PTY', '$2y$10$v4eXlVFEJmyLgi./VCtJLudXd3CIYQcXce12tZUGaePQWqws80EC.', 'guru'),
(87, 1290, 'f0abcbee-dad0-420c-a4a7-e27a32bc0bd3', NULL, '', '3213194207980003', 'P', NULL, NULL, 'YUYUM SUMYATI', '4034776677230203', NULL, NULL, 'L', 'SUBANG', NULL, NULL, NULL, NULL, NULL, NULL, 'GTY', 'default.png', NULL, NULL, '1998-07-02', NULL, 'Guru Honor Sekolah', '$2y$10$YVPfENmLI2FHAyTRn4fdVOT3bxKXcZJpqv8hKaYBHHliYibni6EZG', 'guru');

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
(3, 4, 3, 1, 4, 'Senin', '07:15:00', '09:15:00', '2026-01-18 15:02:28', '2026-01-18 15:02:28'),
(4, 4, 1, 2, 3, 'Rabu', '14:00:00', '14:40:00', '2026-01-28 21:56:02', '2026-01-28 21:56:02'),
(5, 4, 1, 1, 5, 'Kamis', '09:30:00', '14:40:00', '2026-01-29 20:48:10', '2026-01-29 20:48:10'),
(6, 5, 1, 1, 5, 'Kamis', '07:55:00', '09:15:00', '2026-01-29 21:14:39', '2026-01-29 21:14:39');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_jadwal_kelas`
--

CREATE TABLE `tbl_jadwal_kelas` (
  `id` int(11) NOT NULL,
  `id_jadwal_ujian` int(11) NOT NULL,
  `id_kelas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_jadwal_kelas`
--

INSERT INTO `tbl_jadwal_kelas` (`id`, `id_jadwal_ujian`, `id_kelas`) VALUES
(61, 7, 1),
(62, 7, 4),
(63, 7, 5),
(64, 9, 40),
(65, 10, 51),
(66, 10, 40);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_jadwal_ujian`
--

CREATE TABLE `tbl_jadwal_ujian` (
  `id` int(11) NOT NULL,
  `nama_ujian` varchar(255) NOT NULL,
  `kode_ujian` varchar(50) DEFAULT NULL,
  `id_bank_soal` int(11) NOT NULL,
  `id_guru` int(11) NOT NULL,
  `waktu_mulai` datetime DEFAULT NULL,
  `waktu_selesai` datetime DEFAULT NULL,
  `durasi` int(11) DEFAULT 60 COMMENT 'Menit',
  `token` varchar(10) DEFAULT NULL,
  `acak_soal` int(11) DEFAULT 1,
  `acak_opsi` int(11) DEFAULT 0,
  `wajib_lokasi` int(11) DEFAULT 0,
  `status` int(11) DEFAULT 1 COMMENT '1=Aktif, 0=Nonaktif',
  `created_at` datetime DEFAULT current_timestamp(),
  `limit_pelanggaran` int(11) DEFAULT 3 COMMENT 'Batas Max Pelanggaran',
  `min_waktu` int(11) DEFAULT 10 COMMENT 'Menit minimal sebelum tombol selesai muncul',
  `bobot_pg` int(11) DEFAULT 100,
  `bobot_esai` int(11) DEFAULT 0,
  `min_waktu_selesai` int(11) DEFAULT 0 COMMENT 'Menit minimal sebelum tombol selesai muncul',
  `setting_strict` tinyint(1) DEFAULT 0 COMMENT '1=Aktif, 0=Nonaktif',
  `setting_afk_timeout` int(11) DEFAULT 0 COMMENT 'Detik toleransi keluar tab',
  `setting_max_violation` int(11) DEFAULT 3 COMMENT 'Batas pelanggaran',
  `setting_show_score` tinyint(1) DEFAULT 0 COMMENT 'Tampilkan nilai setelah selesai',
  `setting_multi_login` tinyint(1) DEFAULT 0 COMMENT '1=Cegah login ganda',
  `setting_token` tinyint(1) DEFAULT 0,
  `status_ujian` enum('AKTIF','NONAKTIF','SELESAI') DEFAULT 'AKTIF',
  `id_tahun_ajaran` int(11) DEFAULT NULL,
  `id_jenis_ujian` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_jadwal_ujian`
--

INSERT INTO `tbl_jadwal_ujian` (`id`, `nama_ujian`, `kode_ujian`, `id_bank_soal`, `id_guru`, `waktu_mulai`, `waktu_selesai`, `durasi`, `token`, `acak_soal`, `acak_opsi`, `wajib_lokasi`, `status`, `created_at`, `limit_pelanggaran`, `min_waktu`, `bobot_pg`, `bobot_esai`, `min_waktu_selesai`, `setting_strict`, `setting_afk_timeout`, `setting_max_violation`, `setting_show_score`, `setting_multi_login`, `setting_token`, `status_ujian`, `id_tahun_ajaran`, `id_jenis_ujian`) VALUES
(7, 'US DTJKT 223', NULL, 2, 3, '2026-01-23 20:18:00', '2026-01-28 23:18:00', 10, '', 1, 1, 0, 1, '2026-01-23 20:18:44', 3, 10, 100, 0, 3, 1, 3, 3, 1, 1, 0, 'AKTIF', 5, 2),
(9, 'us matem', NULL, 3, 3, '2026-01-26 21:03:00', '2026-01-28 21:03:00', 20, '', 1, 1, 0, 1, '2026-01-26 21:03:58', 3, 10, 100, 0, 4, 1, 9, 3, 1, 1, 0, 'AKTIF', 4, 4),
(10, 'us matem', NULL, 3, 3, '2026-01-30 20:10:00', '2026-01-31 20:10:00', 20, '', 1, 1, 0, 1, '2026-01-30 20:10:53', 3, 10, 100, 0, 10, 1, 9, 3, 1, 1, 0, 'AKTIF', 5, 2);

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
-- Struktur dari tabel `tbl_jam_sekolah`
--

CREATE TABLE `tbl_jam_sekolah` (
  `id` int(11) NOT NULL,
  `jam_masuk_mulai` time NOT NULL DEFAULT '06:00:00',
  `jam_masuk_akhir` time NOT NULL DEFAULT '07:15:00',
  `jam_pulang_mulai` time NOT NULL DEFAULT '14:00:00',
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `latitude` varchar(100) DEFAULT NULL,
  `longitude` varchar(100) DEFAULT NULL,
  `radius` int(11) DEFAULT 100
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_jam_sekolah`
--

INSERT INTO `tbl_jam_sekolah` (`id`, `jam_masuk_mulai`, `jam_masuk_akhir`, `jam_pulang_mulai`, `updated_at`, `latitude`, `longitude`, `radius`) VALUES
(1, '05:30:00', '06:35:00', '14:30:00', '2026-01-28 21:34:24', '-6.676266165327266', '107.6830974847583', 50);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_jawaban_siswa`
--

CREATE TABLE `tbl_jawaban_siswa` (
  `id` int(11) NOT NULL,
  `id_ujian_siswa` int(11) NOT NULL,
  `id_soal` int(11) NOT NULL,
  `id_opsi` int(11) DEFAULT NULL,
  `jawaban_siswa` text DEFAULT NULL,
  `jawaban_isian` text DEFAULT NULL,
  `ragu` int(11) DEFAULT 0,
  `is_benar` int(11) DEFAULT 0,
  `nilai` float DEFAULT 0,
  `nilai_esai` float DEFAULT 0 COMMENT 'Nilai per butir soal',
  `nomor_urut` int(11) DEFAULT 0 COMMENT 'Urutan Nomor Soal'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_jawaban_siswa`
--

INSERT INTO `tbl_jawaban_siswa` (`id`, `id_ujian_siswa`, `id_soal`, `id_opsi`, `jawaban_siswa`, `jawaban_isian`, `ragu`, `is_benar`, `nilai`, `nilai_esai`, `nomor_urut`) VALUES
(1, 1, 19, 74, NULL, NULL, 0, 1, 0, 0, 0),
(2, 1, 20, 65, NULL, '7', 0, 1, 0, 0, 0),
(3, 1, 18, 0, NULL, '7', 0, 0, 0, 0, 0),
(4, 1, 17, 63, NULL, NULL, 0, 0, 0, 0, 0),
(5, 2, 18, NULL, NULL, NULL, 0, 0, 0, 0, 0),
(6, 2, 20, NULL, NULL, NULL, 0, 0, 0, 0, 0),
(7, 2, 17, NULL, NULL, NULL, 0, 0, 0, 0, 0),
(8, 2, 19, NULL, NULL, NULL, 0, 0, 0, 0, 0),
(9, 3, 21, NULL, NULL, NULL, 0, 0, 0, 0, 0),
(10, 3, 23, NULL, NULL, NULL, 0, 0, 0, 0, 0),
(11, 3, 22, NULL, NULL, NULL, 0, 0, 0, 0, 0),
(12, 3, 24, NULL, NULL, NULL, 0, 0, 0, 0, 0),
(13, 4, 17, NULL, NULL, NULL, 0, 0, 0, 0, 0),
(14, 4, 19, NULL, NULL, NULL, 0, 0, 0, 0, 0),
(15, 4, 20, NULL, NULL, NULL, 0, 0, 0, 0, 0),
(16, 4, 18, NULL, NULL, NULL, 0, 0, 0, 0, 0),
(17, 5, 18, NULL, NULL, NULL, 0, 0, 0, 0, 0),
(18, 5, 19, NULL, NULL, NULL, 0, 0, 0, 0, 0),
(19, 5, 20, NULL, NULL, NULL, 0, 0, 0, 0, 0),
(20, 5, 17, NULL, NULL, NULL, 0, 0, 0, 0, 0),
(21, 2, 19, NULL, NULL, NULL, 0, 0, 0, 0, 1),
(22, 2, 20, NULL, NULL, NULL, 0, 0, 0, 0, 2),
(23, 2, 18, NULL, NULL, NULL, 0, 0, 0, 0, 3),
(24, 2, 17, NULL, NULL, NULL, 0, 0, 0, 0, 4),
(25, 6, 19, NULL, NULL, NULL, 0, 0, 0, 0, 1),
(26, 6, 17, NULL, NULL, NULL, 0, 0, 0, 0, 2),
(27, 6, 20, NULL, NULL, NULL, 0, 0, 0, 0, 3),
(28, 6, 18, NULL, NULL, NULL, 0, 0, 0, 0, 4),
(29, 7, 20, NULL, NULL, NULL, 0, 0, 0, 0, 1),
(30, 7, 17, NULL, NULL, NULL, 0, 0, 0, 0, 2),
(31, 7, 18, NULL, NULL, NULL, 0, 0, 0, 0, 3),
(32, 7, 19, NULL, NULL, NULL, 0, 0, 0, 0, 4),
(33, 8, 20, NULL, NULL, NULL, 0, 0, 0, 0, 1),
(34, 8, 19, NULL, NULL, NULL, 0, 0, 0, 0, 2),
(35, 8, 18, NULL, NULL, NULL, 0, 0, 0, 0, 3),
(36, 8, 17, NULL, NULL, NULL, 0, 0, 0, 0, 4),
(37, 9, 19, NULL, NULL, NULL, 0, 0, 0, 0, 1),
(38, 9, 17, NULL, NULL, NULL, 0, 0, 0, 0, 2),
(39, 9, 18, NULL, NULL, NULL, 0, 0, 0, 0, 3),
(40, 9, 20, NULL, NULL, NULL, 0, 0, 0, 0, 4),
(41, 10, 17, NULL, NULL, NULL, 0, 0, 0, 0, 1),
(42, 10, 18, NULL, NULL, NULL, 0, 0, 0, 0, 2),
(43, 10, 19, NULL, NULL, NULL, 0, 0, 0, 0, 3),
(44, 10, 20, NULL, NULL, NULL, 0, 0, 0, 0, 4),
(45, 11, 18, NULL, NULL, NULL, 0, 0, 0, 0, 1),
(46, 11, 20, NULL, NULL, NULL, 0, 0, 0, 0, 2),
(47, 11, 19, NULL, NULL, NULL, 0, 0, 0, 0, 3),
(48, 11, 17, NULL, NULL, NULL, 0, 0, 0, 0, 4),
(49, 12, 20, NULL, NULL, NULL, 0, 0, 0, 0, 1),
(50, 12, 19, NULL, NULL, NULL, 0, 0, 0, 0, 2),
(51, 12, 18, NULL, NULL, NULL, 0, 0, 0, 0, 3),
(52, 12, 17, NULL, NULL, NULL, 0, 0, 0, 0, 4),
(53, 13, 18, 0, '[\"69\",\"71\"]', NULL, 0, 1, 4, 0, 1),
(54, 13, 20, 0, '7', NULL, 0, 1, 2, 0, 2),
(55, 13, 19, 0, '{\"Jawa Tengah\":\"Semarang\",\"Jawa Barat\":\"Bandung\",\"Jawa Timur\":\"Surabaya\"}', NULL, 0, 1, 2, 0, 3),
(56, 13, 17, 65, NULL, NULL, 0, 1, 2, 0, 4),
(57, 14, 17, 93, NULL, NULL, 0, 1, 20, 0, 1),
(58, 14, 20, 0, '7', NULL, 0, 0, 0, 0, 2),
(59, 14, 18, NULL, NULL, NULL, 0, 0, 0, 0, 3),
(60, 14, 19, NULL, NULL, NULL, 0, 0, 0, 0, 4),
(61, 15, 32, 0, '7', NULL, 0, 1, 10, 0, 1),
(62, 15, 30, 0, '[\"118\",\"116\"]', NULL, 0, 1, 40, 0, 2),
(63, 15, 31, 0, '{\"Jawa Barat\":\"Bandung\",\"Jawa Tengah\":\"Semarang\",\"Jawa Timur\":\"Surabaya\"}', NULL, 0, 1, 40, 0, 3),
(64, 15, 29, 112, NULL, NULL, 0, 1, 20, 0, 4),
(65, 16, 30, NULL, NULL, NULL, 0, 0, 0, 0, 1),
(66, 16, 29, NULL, NULL, NULL, 0, 0, 0, 0, 2),
(67, 16, 32, NULL, NULL, NULL, 0, 0, 0, 0, 3),
(68, 16, 31, NULL, NULL, NULL, 0, 0, 0, 0, 4),
(69, 17, 29, NULL, NULL, NULL, 0, 0, 0, 0, 1),
(70, 17, 30, NULL, NULL, NULL, 0, 0, 0, 0, 2),
(71, 17, 31, NULL, NULL, NULL, 0, 0, 0, 0, 3),
(72, 17, 32, NULL, NULL, NULL, 0, 0, 0, 0, 4),
(73, 18, 29, NULL, NULL, NULL, 0, 0, 0, 0, 1),
(74, 18, 32, NULL, NULL, NULL, 0, 0, 0, 0, 2),
(75, 18, 31, NULL, NULL, NULL, 0, 0, 0, 0, 3),
(76, 18, 30, NULL, NULL, NULL, 0, 0, 0, 0, 4),
(77, 19, 29, NULL, NULL, NULL, 0, 0, 0, 0, 1),
(78, 19, 31, NULL, NULL, NULL, 0, 0, 0, 0, 2),
(79, 19, 30, NULL, NULL, NULL, 0, 0, 0, 0, 3),
(80, 19, 32, NULL, NULL, NULL, 0, 0, 0, 0, 4),
(81, 20, 29, NULL, NULL, NULL, 0, 0, 0, 0, 1),
(82, 20, 31, NULL, NULL, NULL, 0, 0, 0, 0, 2),
(83, 20, 30, NULL, NULL, NULL, 0, 0, 0, 0, 3),
(84, 20, 32, NULL, NULL, NULL, 0, 0, 0, 0, 4),
(85, 21, 32, 0, '8', NULL, 0, 0, 0, 0, 1),
(86, 21, 31, 0, '{\"Jawa Barat\":\"Bandung\",\"Jawa Tengah\":\"Semarang\",\"Jawa Timur\":\"Surabaya\"}', NULL, 0, 1, 40, 0, 2),
(87, 21, 29, 126, NULL, NULL, 0, 1, 10, 0, 3),
(88, 21, 30, 0, '[\"116\",\"118\"]', NULL, 0, 1, 40, 0, 4),
(89, 22, 32, 0, '7', NULL, 0, 1, 10, 0, 1),
(90, 22, 31, 0, '{\"Jawa Barat\":\"Bandung\",\"Jawa Tengah\":\"Semarang\",\"Jawa Timur\":\"Surabaya\"}', NULL, 0, 1, 40, 0, 2),
(91, 22, 29, 126, NULL, NULL, 0, 1, 10, 0, 3),
(92, 22, 30, 0, '[\"116\",\"118\"]', NULL, 0, 1, 40, 0, 4),
(93, 23, 32, 0, '7', NULL, 0, 1, 10, 0, 1),
(94, 23, 29, 126, NULL, NULL, 0, 1, 10, 0, 2),
(95, 23, 30, 0, '[\"116\",\"118\"]', NULL, 0, 1, 40, 0, 3),
(96, 23, 31, 0, '{\"Jawa Barat\":\"Bandung\",\"Jawa Timur\":\"Surabaya\",\"Jawa Tengah\":\"Semarang\"}', NULL, 0, 1, 40, 0, 4),
(97, 24, 31, 0, '{\"Jawa Barat\":\"Bandung\",\"Jawa Timur\":\"Surabaya\",\"Jawa Tengah\":\"Semarang\"}', NULL, 0, 0, 0, 0, 1),
(98, 24, 32, 0, '7', NULL, 0, 0, 0, 0, 2),
(99, 24, 29, 126, NULL, NULL, 0, 0, 0, 0, 3),
(100, 24, 30, 0, '[\"115\",\"117\"]', NULL, 0, 0, 0, 0, 4),
(101, 25, 30, 0, '[\"116\",\"118\"]', NULL, 0, 1, 40, 0, 1),
(102, 25, 31, 0, '{\"Jawa Barat\":\"Bandung\",\"Jawa Tengah\":\"Semarang\",\"Jawa Timur\":\"Surabaya\"}', NULL, 0, 1, 40, 0, 2),
(103, 25, 32, 0, '7', NULL, 0, 1, 10, 0, 3),
(104, 25, 29, 126, NULL, NULL, 0, 1, 10, 0, 4),
(105, 26, 30, 0, '[\"116\",\"118\"]', NULL, 0, 1, 40, 0, 1),
(106, 26, 29, 126, NULL, NULL, 0, 1, 10, 0, 2),
(107, 26, 31, NULL, NULL, NULL, 0, 0, 0, 0, 3),
(108, 26, 32, NULL, NULL, NULL, 0, 0, 0, 0, 4),
(109, 27, 30, 0, '[\"118\",\"116\"]', NULL, 0, 1, 40, 0, 1),
(110, 27, 32, 0, '7', NULL, 0, 0, 0, 0, 2),
(111, 27, 31, NULL, NULL, NULL, 0, 0, 0, 0, 3),
(112, 27, 29, NULL, NULL, NULL, 0, 0, 0, 0, 4),
(113, 28, 29, NULL, NULL, NULL, 0, 0, 0, 0, 1),
(114, 28, 30, NULL, NULL, NULL, 0, 0, 0, 0, 2),
(115, 28, 31, NULL, NULL, NULL, 0, 0, 0, 0, 3),
(116, 28, 32, NULL, NULL, NULL, 0, 0, 0, 0, 4),
(117, 29, 32, 0, '', NULL, 0, 0, 0, 0, 1),
(118, 29, 31, NULL, NULL, NULL, 0, 0, 0, 0, 2),
(119, 29, 29, NULL, NULL, NULL, 0, 0, 0, 0, 3),
(120, 29, 30, NULL, NULL, NULL, 0, 0, 0, 0, 4),
(121, 30, 29, NULL, NULL, NULL, 0, 0, 0, 0, 1),
(122, 30, 32, NULL, NULL, NULL, 0, 0, 0, 0, 2),
(123, 30, 31, NULL, NULL, NULL, 0, 0, 0, 0, 3),
(124, 30, 30, NULL, NULL, NULL, 0, 0, 0, 0, 4),
(125, 31, 30, NULL, NULL, NULL, 0, 0, 0, 0, 1),
(126, 31, 32, NULL, NULL, NULL, 0, 0, 0, 0, 2),
(127, 31, 29, NULL, NULL, NULL, 0, 0, 0, 0, 3),
(128, 31, 31, NULL, NULL, NULL, 0, 0, 0, 0, 4),
(129, 32, 31, NULL, NULL, NULL, 0, 0, 0, 0, 1),
(130, 32, 30, 0, '[\"118\",\"116\"]', NULL, 0, 1, 40, 0, 2),
(131, 32, 32, 0, '7', NULL, 0, 1, 10, 0, 3),
(132, 32, 29, 0, NULL, NULL, 0, 0, 0, 0, 4),
(133, 33, 32, 0, '7', NULL, 0, 1, 10, 0, 1),
(134, 33, 31, 0, '{\"Jawa Timur\":\"Bandung\",\"Jawa Tengah\":\"Semarang\",\"Jawa Barat\":\"Bandung\"}', NULL, 0, 0, 26.6667, 0, 2),
(135, 33, 29, 0, NULL, NULL, 0, 0, 0, 0, 3),
(136, 33, 30, 0, '[\"118\",\"116\"]', NULL, 0, 1, 40, 0, 4),
(137, 34, 32, 0, '7', NULL, 0, 1, 10, 0, 1),
(138, 34, 30, 0, '[\"115\",\"116\"]', NULL, 0, 0, 0, 0, 2),
(139, 34, 29, 126, NULL, NULL, 0, 1, 10, 0, 3),
(140, 34, 31, 0, NULL, NULL, 0, 0, 0, 0, 4),
(141, 35, 32, 0, '7', NULL, 0, 1, 10, 0, 1),
(142, 35, 30, 0, '[\"116\",\"118\"]', NULL, 0, 1, 40, 0, 2),
(143, 35, 29, 126, NULL, NULL, 0, 1, 10, 0, 3),
(144, 35, 31, 0, '{\"Jawa Tengah\":\"Bandung\",\"Jawa Timur\":\"Surabaya\",\"Jawa Barat\":\"Bandung\"}', NULL, 0, 0, 26.6667, 0, 4),
(145, 36, 21, NULL, NULL, NULL, 0, 0, 0, 0, 1),
(146, 36, 23, NULL, NULL, NULL, 0, 0, 0, 0, 2),
(147, 36, 22, NULL, NULL, NULL, 0, 0, 0, 0, 3),
(148, 36, 24, NULL, NULL, NULL, 0, 0, 0, 0, 4),
(149, 37, 21, 79, NULL, NULL, 0, 0, 0, 0, 1),
(150, 37, 22, 0, '[\"83\",\"85\"]', NULL, 0, 0, 0, 0, 2),
(151, 37, 23, 0, '{\"Jawa Timur\":\"Surabaya\",\"Jawa Barat\":\"Bandung\",\"Jawa Tengah\":\"Bandung\"}', NULL, 0, 0, 0, 0, 3),
(152, 37, 24, 0, '7', NULL, 0, 0, 0, 0, 4),
(153, 38, 29, 124, NULL, NULL, 0, 0, 0, 0, 1),
(154, 38, 30, 0, '[\"118\",\"116\"]', NULL, 0, 0, 0, 0, 2),
(155, 38, 31, NULL, NULL, NULL, 0, 0, 0, 0, 3),
(156, 38, 32, NULL, NULL, NULL, 0, 0, 0, 0, 4),
(157, 39, 24, 0, '7', NULL, 0, 1, 2, 0, 1),
(158, 39, 22, 0, '[\"82\",\"85\"]', NULL, 0, 0, 0, 0, 2),
(159, 39, 23, 0, '{\"Jawa Tengah\":\"Semarang\",\"Jawa Timur\":\"Surabaya\",\"Jawa Barat\":\"Bandung\"}', NULL, 0, 1, 2, 0, 3),
(160, 39, 21, 0, NULL, NULL, 0, 0, 0, 0, 4),
(161, 40, 24, 0, '7', NULL, 0, 0, 0, 0, 1),
(162, 40, 21, 79, NULL, NULL, 0, 0, 0, 0, 2),
(163, 40, 22, 0, '[\"83\",\"85\"]', NULL, 0, 0, 0, 0, 3),
(164, 40, 23, 0, '{\"Jawa Timur\":\"Surabaya\",\"Jawa Barat\":\"Bandung\",\"Jawa Tengah\":\"Semarang\"}', NULL, 0, 0, 0, 0, 4),
(165, 41, 24, 0, '7', NULL, 1, 0, 0, 0, 1),
(166, 41, 21, 79, NULL, NULL, 0, 0, 0, 0, 2),
(167, 41, 23, 0, '{\"Jawa Tengah\":\"Semarang\",\"Jawa Barat\":\"Bandung\",\"Jawa Timur\":\"Semarang\"}', NULL, 0, 0, 0, 0, 3),
(168, 41, 22, 0, '[\"83\",\"85\"]', NULL, 0, 0, 0, 0, 4),
(169, 42, 21, 79, NULL, NULL, 0, 1, 2, 0, 1),
(170, 42, 23, 0, '{\"Jawa Barat\":\"Bandung\",\"Jawa Timur\":\"Surabaya\",\"Jawa Tengah\":\"Semarang\"}', NULL, 0, 1, 2, 0, 2),
(171, 42, 22, 0, '[\"83\",\"85\"]', NULL, 0, 1, 4, 0, 3),
(172, 42, 24, 0, '7', NULL, 0, 1, 2, 0, 4),
(173, 43, 21, 79, NULL, NULL, 0, 0, 0, 0, 1),
(174, 43, 22, 0, '[\"83\",\"85\"]', NULL, 0, 0, 0, 0, 2),
(175, 43, 24, 0, '7', NULL, 0, 0, 0, 0, 3),
(176, 43, 23, 0, NULL, NULL, 1, 0, 0, 0, 4),
(177, 44, 22, 0, '[\"85\",\"83\"]', NULL, 0, 1, 4, 0, 1),
(178, 44, 23, 0, '{\"Jawa Barat\":\"Bandung\",\"Jawa Timur\":\"Surabaya\",\"Jawa Tengah\":\"Semarang\"}', NULL, 0, 1, 2, 0, 2),
(179, 44, 33, 133, NULL, NULL, 0, 1, 2, 0, 3),
(180, 44, 24, 0, '7', NULL, 0, 1, 2, 0, 4),
(181, 44, 21, 79, NULL, NULL, 0, 1, 2, 0, 5),
(182, 45, 21, 79, NULL, NULL, 0, 0, 0, 0, 1),
(183, 45, 34, 137, NULL, NULL, 0, 0, 0, 0, 2),
(184, 45, 24, 0, '7', NULL, 0, 0, 0, 0, 3),
(185, 45, 33, 133, NULL, NULL, 0, 0, 0, 0, 4),
(186, 45, 22, 0, '[\"83\",\"85\"]', NULL, 0, 0, 0, 0, 5),
(187, 45, 23, 0, '{\"Jawa Barat\":\"Bandung\",\"Jawa Timur\":\"Surabaya\",\"Jawa Tengah\":\"Semarang\"}', NULL, 0, 0, 0, 0, 6),
(188, 46, 21, 79, NULL, NULL, 0, 1, 2, 0, 1),
(189, 46, 33, 133, NULL, NULL, 0, 1, 2, 0, 2),
(190, 46, 35, 142, NULL, NULL, 0, 0, 0, 0, 3),
(191, 46, 24, 0, '7', NULL, 0, 1, 2, 0, 4),
(192, 46, 34, 137, NULL, NULL, 0, 0, 0, 0, 5),
(193, 46, 22, 0, '[\"85\",\"83\"]', NULL, 0, 1, 4, 0, 6),
(194, 46, 23, 0, NULL, NULL, 0, 0, 0, 0, 7),
(195, 47, 34, NULL, NULL, NULL, 0, 0, 0, 0, 1),
(196, 47, 24, NULL, NULL, NULL, 0, 0, 0, 0, 2),
(197, 47, 35, NULL, NULL, NULL, 0, 0, 0, 0, 3),
(198, 47, 21, NULL, NULL, NULL, 0, 0, 0, 0, 4),
(199, 47, 23, NULL, NULL, NULL, 0, 0, 0, 0, 5),
(200, 47, 22, NULL, NULL, NULL, 0, 0, 0, 0, 6),
(201, 47, 33, NULL, NULL, NULL, 0, 0, 0, 0, 7),
(202, 48, 24, NULL, NULL, NULL, 0, 0, 0, 0, 1),
(203, 48, 21, NULL, NULL, NULL, 0, 0, 0, 0, 2),
(204, 48, 33, NULL, NULL, NULL, 0, 0, 0, 0, 3),
(205, 48, 23, NULL, NULL, NULL, 0, 0, 0, 0, 4),
(206, 48, 34, NULL, NULL, NULL, 0, 0, 0, 0, 5),
(207, 48, 22, NULL, NULL, NULL, 0, 0, 0, 0, 6),
(208, 48, 35, NULL, NULL, NULL, 0, 0, 0, 0, 7),
(209, 49, 22, 0, '[\"85\",\"83\"]', NULL, 0, 0, 0, 0, 1),
(210, 49, 33, 133, NULL, NULL, 0, 0, 0, 0, 2),
(211, 49, 35, NULL, NULL, NULL, 0, 0, 0, 0, 3),
(212, 49, 23, NULL, NULL, NULL, 0, 0, 0, 0, 4),
(213, 49, 21, NULL, NULL, NULL, 0, 0, 0, 0, 5),
(214, 49, 34, NULL, NULL, NULL, 0, 0, 0, 0, 6),
(215, 49, 24, NULL, NULL, NULL, 0, 0, 0, 0, 7),
(216, 50, 34, 138, NULL, NULL, 0, 0, 0, 0, 1),
(217, 50, 33, NULL, NULL, NULL, 0, 0, 0, 0, 2),
(218, 50, 22, NULL, NULL, NULL, 0, 0, 0, 0, 3),
(219, 50, 35, NULL, NULL, NULL, 0, 0, 0, 0, 4),
(220, 50, 21, NULL, NULL, NULL, 0, 0, 0, 0, 5),
(221, 50, 23, NULL, NULL, NULL, 0, 0, 0, 0, 6),
(222, 50, 24, NULL, NULL, NULL, 0, 0, 0, 0, 7);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_jenis_bayar`
--

CREATE TABLE `tbl_jenis_bayar` (
  `id` int(11) NOT NULL,
  `id_pos_bayar` int(11) NOT NULL,
  `id_tahun_ajaran` int(11) NOT NULL,
  `tipe_bayar` enum('BULANAN','BEBAS') NOT NULL COMMENT 'Bulanan=SPP, Bebas=Sekali Bayar',
  `nominal_default` double DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_jenis_bayar`
--

INSERT INTO `tbl_jenis_bayar` (`id`, `id_pos_bayar`, `id_tahun_ajaran`, `tipe_bayar`, `nominal_default`, `created_at`, `updated_at`) VALUES
(3, 3, 5, 'BEBAS', 5000000, '2026-01-28 13:50:50', '2026-01-28 13:50:50'),
(4, 4, 5, 'BEBAS', 1800000, '2026-01-31 11:23:30', '2026-01-31 11:23:30');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_jenis_pengeluaran`
--

CREATE TABLE `tbl_jenis_pengeluaran` (
  `id` int(11) NOT NULL,
  `nama_jenis` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_jenis_pengeluaran`
--

INSERT INTO `tbl_jenis_pengeluaran` (`id`, `nama_jenis`) VALUES
(1, 'Bahan Habis Pakai (ATK)'),
(2, 'Konsumsi & Rapat'),
(3, 'Transportasi'),
(4, 'Perbaikan & Maintenance'),
(5, 'Honorarium Lepas'),
(6, 'Barang Inventaris');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_jenis_ujian`
--

CREATE TABLE `tbl_jenis_ujian` (
  `id` int(11) NOT NULL,
  `nama_jenis` varchar(50) NOT NULL,
  `kode_jenis` varchar(20) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_jenis_ujian`
--

INSERT INTO `tbl_jenis_ujian` (`id`, `nama_jenis`, `kode_jenis`, `status`) VALUES
(1, 'Penilaian Harian', 'PH', 1),
(2, 'Penilaian Tengah Semester', 'PTS', 1),
(3, 'Penilaian Akhir Semester', 'PAS', 1),
(4, 'Ujian Sekolah', 'US', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_jurnal`
--

CREATE TABLE `tbl_jurnal` (
  `id` int(11) NOT NULL,
  `id_guru` int(11) NOT NULL,
  `id_tahun_ajaran` int(11) DEFAULT NULL,
  `id_kelas` int(11) NOT NULL,
  `id_mapel` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jam_ke` varchar(50) NOT NULL,
  `materi` text NOT NULL,
  `keterangan` text DEFAULT NULL,
  `foto_kegiatan` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_jurnal`
--

INSERT INTO `tbl_jurnal` (`id`, `id_guru`, `id_tahun_ajaran`, `id_kelas`, `id_mapel`, `tanggal`, `jam_ke`, `materi`, `keterangan`, `foto_kegiatan`, `created_at`) VALUES
(7, 5, 5, 1, 1, '2026-01-29', '1', 'Geometri', 'lancar', '1769697666_c58d9cfe55c22b61fb88.jpg', '2026-01-29 21:41:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_jurusan`
--

CREATE TABLE `tbl_jurusan` (
  `id` int(11) NOT NULL,
  `dapodik_id` varchar(50) DEFAULT NULL,
  `kode_jurusan` varchar(10) NOT NULL,
  `nama_jurusan` varchar(100) NOT NULL,
  `kepala_jurusan_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `tbl_jurusan`
--

INSERT INTO `tbl_jurusan` (`id`, `dapodik_id`, `kode_jurusan`, `nama_jurusan`, `kepala_jurusan_id`) VALUES
(6, '21014', 'Tekno', 'Teknologi Farmasi', NULL),
(7, '16012', 'Tekni', 'Teknik Jaringan Komputer dan Telekomunikasi', NULL),
(8, '35012', 'Manaj', 'Manajemen Perkantoran dan Layanan Bisnis', NULL),
(9, '16012425', 'Tekni', 'Teknik Komputer dan Jaringan', NULL),
(10, '35012585', 'Manaj', 'Manajemen Perkantoran', NULL),
(11, '21014465', 'Layan', 'Layanan Penunjang Kefarmasian Klinis dan Komunitas', NULL),
(12, '35281530', 'Otoma', 'Otomatisasi dan Tata Kelola Perkantoran', NULL),
(13, '15052515', 'Tekni', 'Teknik Komputer dan Jaringan', NULL),
(14, '21171380', 'Farma', 'Farmasi Klinis dan Komunitas', NULL);

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
  `dapodik_id` varchar(50) DEFAULT NULL,
  `nama_kelas` varchar(50) NOT NULL,
  `tingkat` int(2) DEFAULT NULL,
  `id_jurusan` int(11) DEFAULT NULL,
  `guru_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_kelas`
--

INSERT INTO `tbl_kelas` (`id`, `dapodik_id`, `nama_kelas`, `tingkat`, `id_jurusan`, `guru_id`) VALUES
(40, '67d9edb1-f0e2-4693-992d-3e4cccd0e1a0', 'XII TKJ 2', 12, 13, 67),
(41, 'da90cc11-526e-48aa-b7ce-a9afbc0d8023', 'X FARMASI', 10, 6, 70),
(42, '0168bb86-45b1-4a2f-8beb-b147e3bf05a7', 'X TKJT 1', 10, 7, 79),
(43, 'f0dd79d4-b8fa-4815-b531-f65b873990ea', 'X MPLB 2', 10, 8, 73),
(44, '005b0659-8a5e-4160-8830-9ddb091fbf00', 'X TKJT 2', 10, 7, 71),
(45, '1d260111-0d05-4b10-a525-1eeeef43fe6b', 'X MPLB 1', 10, 8, 76),
(46, '1d4ffd24-3a9e-4c03-afd8-a12ab295694a', 'XI TKJT 1', 11, 9, 80),
(47, '619c6aff-d40d-4ac9-905d-243b54536570', 'XI MPLB 1', 11, 10, 69),
(48, '9cce8b82-f77c-4be6-be06-449e5da19e86', 'XI MPLB 2', 11, 10, 74),
(49, '2bcef959-7a06-4631-b590-f53fb9f93bc2', 'XI TKJT 2', 11, 9, 87),
(50, 'e94587e3-0ece-43f2-b670-502c10beffe3', 'XI FARMASI', 11, 11, 84),
(51, '6b9f4508-c1f9-46e0-b8a6-a03bacd4a708', 'XII OTKP 2', 12, 12, 83),
(52, '20a865c3-2d47-4cb4-90a4-885b04daa92e', 'XII OTKP 1', 12, 12, 86),
(53, 'beea584c-74a1-4013-9a57-06b34248371b', 'XII TKJ 1', 12, 13, 75),
(54, '68edb4cb-5f7a-4dfb-b043-d5f663a2f21b', 'XII FARMASI', 12, 14, 65),
(55, '964b7cb7-a338-4574-814c-f5954ed784eb', 'XII OTKP 3', 12, 12, 68);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_log_keuangan`
--

CREATE TABLE `tbl_log_keuangan` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nama_user` varchar(100) NOT NULL,
  `role` varchar(50) NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `device_info` text DEFAULT NULL,
  `aksi` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_log_keuangan`
--

INSERT INTO `tbl_log_keuangan` (`id`, `user_id`, `nama_user`, `role`, `ip_address`, `lokasi`, `device_info`, `aksi`, `created_at`) VALUES
(1, 688, 'ADITYA PRATAMA', 'Unknown', '::1', 'Localhost / Private Network', 'Chrome 144.0.0.0 on Android (Mobile: Android)', 'Membuat Invoice Tripay (SHOPEEPAY) Ref: INV-260128143541506 senilai Rp 50.000', '2026-01-28 14:35:42'),
(2, 0, 'Guest/System', 'Unknown', '::1', 'Localhost / Private Network', '  on Unknown Platform', 'SYSTEM: Pembayaran Diterima via Tripay Ref: INV-260128143541506 (LUNAS/CICIL)', '2026-01-28 14:36:51'),
(3, 6, 'Riki Wahyudin', 'Unknown', '::1', 'Localhost / Private Network', 'Chrome 144.0.0.0 on Windows 10', 'MENGHAPUS Transaksi ID: 10 (Pembatalan). Saldo tagihan dikembalikan.', '2026-01-28 14:38:04'),
(4, 6, 'Riki Wahyudin', 'Unknown', '::1', 'Localhost / Private Network', 'Chrome 144.0.0.0 on Windows 10', 'MENGHAPUS Pembayaran a.n ADITYA PRATAMA sebesar Rp 50.000 (ID Trx: 11). Saldo dikembalikan.', '2026-01-28 14:42:12'),
(5, 6, 'Riki Wahyudin', 'Unknown', '::1', 'Localhost / Private Network', 'Chrome 144.0.0.0 on Windows 10', 'Menerima Pembayaran Tunai Rp 50.000 dari siswa ADITYA PRATAMA', '2026-01-28 14:43:10'),
(6, 6, 'Riki Wahyudin', 'Unknown', '::1', 'Localhost / Private Network', 'Chrome 144.0.0.0 on Windows 10', 'Mencatat Pengeluaran: Transport Rapat Bimtek sebesar Rp 50.000', '2026-01-28 14:48:24'),
(7, 6, 'Riki Wahyudin', 'Unknown', '::1', 'Localhost / Private Network', 'Chrome 144.0.0.0 on Windows 10', 'Menerima Pembayaran Tunai Rp 20.000 dari siswa ADITYA PRATAMA', '2026-01-28 15:01:25'),
(8, 1067, 'LUCKY FAUZIA SATYA', 'Unknown', '::1', 'Localhost / Private Network', 'Chrome 144.0.0.0 on Windows 10', 'Membuat Invoice Tripay (BRIVA) Ref: INV-260130195158281 senilai Rp 5.000.000', '2026-01-30 19:51:59'),
(9, 847, 'MUHAMMAD IRSYAD', 'Unknown', '::1', 'Localhost / Private Network', 'Safari 604.1 on iOS (Mobile: Apple iPhone)', 'Membuat Invoice Tripay (DANA) Ref: INV-260130195215856 senilai Rp 5.000.000', '2026-01-30 19:52:15'),
(10, 0, 'Guest/System', 'Unknown', '::1', 'Localhost / Private Network', '  on Unknown Platform', 'SYSTEM: Pembayaran Diterima via Tripay Ref: INV-260130195215856 (LUNAS/CICIL)', '2026-01-30 19:53:28'),
(11, 6, 'Riki Wahyudin', 'Unknown', '::1', 'Localhost / Private Network', 'Chrome 144.0.0.0 on Windows 10', 'Menerima Pembayaran Tunai Rp 3.000.000 dari siswa ARYA NUGRAHA ZAELANI', '2026-01-31 11:27:38');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_mapel`
--

CREATE TABLE `tbl_mapel` (
  `id` int(11) NOT NULL,
  `dapodik_id` varchar(50) DEFAULT NULL,
  `nama_mapel` varchar(100) NOT NULL,
  `kode_mapel` varchar(20) DEFAULT NULL,
  `kelompok` varchar(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `tbl_mapel`
--

INSERT INTO `tbl_mapel` (`id`, `dapodik_id`, `nama_mapel`, `kode_mapel`, `kelompok`) VALUES
(1, '100011060', 'Sejarah Kebudayaan Islam', 'SKI', 'B'),
(2, '100011070', 'Pendidikan Agama Islam dan Budi Pekerti', 'PAIBP', 'A'),
(3, '100012000', 'Pendidikan Agama Kristen', 'PAK', 'A'),
(4, '100012030', 'Sejarah Gereja/Sejarah Suci', 'SGSS', 'A'),
(5, '100013000', 'Pendidikan Agama Katholik', 'PAK', 'A'),
(6, '100013010', 'Pendidikan Agama Katholik dan Budi Pekerti', 'PAKBP', 'A'),
(7, '100013090', 'Sejarah Gereja', 'SG', 'A'),
(8, '100014000', 'Pendidikan Agama Buddha', 'PAB', 'A'),
(9, '100014090', 'Seni dan Budaya Buddhis', 'SBB', 'B'),
(10, '100014140', 'Pendidikan Agama Buddha dan Budi Pekerti', 'PABBP', 'A'),
(11, '100015050', 'Sejarah Agama Hindu', 'SAH', 'A'),
(12, '100016000', 'Pendidikan Agama Konghuchu', 'PAK', 'A'),
(13, '101001000', 'Pendidikan Agama dan Budi Pekerti', 'PABP', 'A'),
(14, '300210000', 'Bahasa Inggris', 'BI', 'A'),
(15, '200010100', 'Pendidikan Kewarganegaraan dan Sejarah', 'PKS', 'A'),
(16, '200010000', 'Pendidikan Pancasila dan Kewarganegaraan', 'PPK', 'A'),
(17, '300311900', 'Muatan Lokal Bahasa Daerah', 'MLBD', 'B'),
(18, '300312900', 'Muatan Lokal Potensi Daerah', 'MLPD', 'B'),
(19, '400100260', 'Perkembangan Seni', 'PS', 'B'),
(20, '300110000', 'Bahasa Indonesia', 'BI', 'A'),
(21, '401230000', 'Sejarah', 'SEJ', 'A'),
(22, '401232000', 'Sejarah Nasional dan Sejarah Umum', 'SNSU', 'A'),
(23, '401233000', 'Sejarah Budaya', 'SB', 'B'),
(24, '401251010', 'Dasar-dasar Perbankan', 'DP', 'C'),
(25, '500040000', 'Pendidikan Jasmani dan Olahraga', 'PJO', 'B'),
(26, '600010000', 'Pendidikan Seni', 'PS', 'B'),
(27, '600011000', 'Seni dan Budaya', 'SB', 'B'),
(28, '600020000', 'Seni Budaya dan Prakarya', 'SBP', 'B'),
(29, '600050000', 'Kerajinan Tangan dan Kesenian', 'KTK', 'B'),
(30, '600070100', 'Produk Kreatif dan Kewirausahaan', 'PKK', 'C'),
(31, '500060000', 'Pendidikan Olahraga dan Rekreasi', 'POR', 'B'),
(32, '700106000', 'Teknik Informatika dan Komputer', 'TIK', 'A'),
(33, '700106100', 'Informatika', 'INF', 'A'),
(34, '700109000', 'Seni Musik', 'SM', 'B'),
(35, '700110000', 'Seni Tari', 'ST', 'B'),
(36, '700111000', 'Seni Lukis', 'SL', 'B'),
(37, '700114000', 'Seni Membatik', 'SM', 'B'),
(38, '800010101', 'Dasar-dasar Komunikasi', 'DK', 'C'),
(39, '800010121', 'Melaksanakan dasar-dasar KIE (Kom., Informasi, & Edukasi)', 'MKIEKIE', 'C'),
(40, '800010123', 'Menjelaskan dasar-dasar penyakit', 'MEN', 'C'),
(41, '800030201', 'Menjelaskan dasar-dasar pembuatan & pengemasan sediaan obat', 'MEN', 'C'),
(42, '800050200', 'Dasar-dasar Keperawatan', 'DK', 'C'),
(43, '800061000', 'Dasar-dasar Ilmu Kedokteran Gigi', 'DIKG', 'C'),
(44, '800080100', 'Dasar-dasar Kerja di Laboratorium', 'DKL', 'C'),
(45, '801030305', 'Menjelaskan dasar-dasar pekerjaan sosial', 'MEN', 'C'),
(46, '802030602', 'Menjelaskan dasar-dasar multimedia', 'MEN', 'C'),
(47, '802040900', 'Dasar Seni Audio Visual', 'DSAV', 'B'),
(48, '803020502', 'Menerapkan dasar-dasar komunikasi siaran radio', 'MEN', 'C'),
(49, '803050101', 'Menerapkan dasar-dasar teknik mesin', 'MEN', 'C'),
(50, '803060301', 'Menjelaskan dasar-dasar teknologi TV', 'MTV', 'C'),
(51, '804050302', 'Menjelaskan dasar-dasar konstruksi baja', 'MEN', 'C'),
(52, '804050402', 'Menjelaskan dasar-dasar ilmu bangunan', 'MEN', 'C'),
(53, '804050802', 'Menerapkan dasar-dasar ilmu bangunan', 'MEN', 'C'),
(54, '804080600', 'Dasar-dasar Plambing', 'DP', 'C'),
(55, '804090301', 'Menerapkan dasar-dasar plumbing', 'MEN', 'C'),
(56, '804100903', 'Menerapkan dasar-dasar elektronika', 'MEN', 'C'),
(57, '824051100', 'Seni Film', 'SF', 'B'),
(58, '809010400', 'Dasar-Dasar Pengelolaan Usaha Grafika', 'DDPUG', 'C'),
(59, '813010101', 'Menerapkan dasar-dasar instrumentasi', 'MEN', 'C'),
(60, '813010112', 'Mendeskripsikan dasar-dasar instrumentasi industri', 'MEN', 'C'),
(61, '814100132', 'Menerapkan dasar-dasar ilmu tekstil', 'MEN', 'C'),
(62, '817010108', 'Menerapkan dasar-dasar pemboran', 'MEN', 'C'),
(63, '817060100', 'Dasar-dasar Teknik Pemboran', 'DTP', 'C'),
(64, '820070101', 'Menjelaskan dasar-dasar mesin', 'MEN', 'C'),
(65, '821061002', 'Menjelaskan dasar-dasar kelistrikan & teknik mesin', 'MEN', 'C'),
(66, '822060101', 'Menjelaskan dasar-dasar kerja mesin', 'MEN', 'C'),
(67, '822070109', 'Melakukan dasar-dasar pekerjaan bengkel elektronika', 'MEL', 'C'),
(68, '822130100', 'Dasar-dasar Energi Terbarukan', 'DET', 'C'),
(69, '824051000', 'Sejarah Film', 'SF', 'A'),
(70, '825010100', 'Dasar-dasar Budidaya Tanaman', 'DBT', 'C'),
(71, '825060100', 'Dasar-dasar Pemeliharaan Ternak', 'DPT', 'C'),
(72, '825060200', 'Dasar-dasar Pakan Ternak', 'DPT', 'C'),
(73, '825060300', 'Dasar-dasar Kesehatan Ternak', 'DKT', 'C'),
(74, '825060400', 'Dasar-dasar Pembibitan Ternak', 'DPT', 'C'),
(75, '825062100', 'Dasar-dasar Peternakan', 'DP', 'C'),
(76, '804050480', 'Dasar-dasar Konstruksi Bangunan dan Teknik Pengukuran Tanah', 'DKBTPT', 'C'),
(77, '825210123', 'Melaksanakan dasar-dasar pengawetan bahan hasil pertanian', 'MEL', 'C'),
(78, '825210124', 'Melaksanakan dasar-dasar pengendalian mutu hasil pertanian', 'MEL', 'C'),
(79, '825250102', 'Melakukan dasar-dasar pekerjaan analisis kimia', 'MEL', 'C'),
(80, '827010109', 'Menjelaskan dasar-dasar elektronika & permesinan kapal', 'MEN', 'C'),
(81, '827040100', 'Dasar-dasar Keselamatan di Laut (DKL)', 'DKLDKL', 'C'),
(82, '827050100', 'Bahasa Inggris Maritim dan Perikanan (BIMP)', 'BIMPBIMP', 'A'),
(83, '827050110', 'Bahasa Inggris Maritim', 'BIM', 'A'),
(84, '827140100', 'Dasar-dasar Budidaya Perairan', 'DBP', 'C'),
(85, '827330100', 'Dasar-dasar Penanganan Pengaturan Muatan (DPPM)', 'DPPMDPPM', 'C'),
(86, '827340100', 'Bahasa Inggris Maritim', 'BIM', 'A'),
(87, '827350103', 'Menerapkan bahasa inggris maritim', 'MEN', 'A'),
(88, '827350108', 'Menjelaskan dasar-dasar elektronik & permesinan kapal', 'MEN', 'C'),
(89, '827390202', 'Menerapkan dasar-dasar keselamatan kerja di laut', 'MEN', 'C'),
(90, '827390208', 'Menerapkan dasar-dasar kenautikaan', 'MEN', 'C'),
(91, '827350106', 'Menerapkan dasar-dasar keselamatan di laut', 'MEN', 'C'),
(92, '831101000', 'Dasar-dasar Desain', 'DD', 'C'),
(93, '831101200', 'Dasar-dasar Desain Kriya', 'DDK', 'C'),
(94, '831102000', 'Dasar-dasar Seni Rupa', 'DSR', 'B'),
(95, '832030100', 'Seni Lukis Dekoratif', 'SLD', 'B'),
(96, '832030200', 'Seni Lukis Realis', 'SLR', 'B'),
(97, '832030300', 'Seni Lukis Non Realis', 'SLNR', 'B'),
(98, '832030400', 'Seni Lukis dinding (Mural/Mosaik)', 'SLMM', 'B'),
(99, '834020110', 'Melaksanakan pameran seni patung', 'MEL', 'B'),
(100, '843010300', 'Pengetahuan Dasar Seni Musik', 'PDSM', 'B'),
(101, '843030100', 'Wawasan Seni dan Desain', 'WSD', 'B'),
(102, '843030200', 'Wawasan Seni Pertunjukan', 'WSP', 'B'),
(103, '843030300', 'Manajemen Seni Pertunjukan', 'MSP', 'B'),
(104, '843030400', 'Dasar-dasar Kreativitas', 'DK', 'C'),
(105, '843030500', 'Tinjauan Seni', 'TS', 'B'),
(106, '843040100', 'Seni Lukis Modern', 'SLM', 'B'),
(107, '843060100', 'Pengetahuan Seni Tari', 'PST', 'B'),
(108, '875150002', 'Menerapkan dasar-dasar teknologi pesawat udara', 'MEN', 'C'),
(109, '999800401', 'Bahasa Indonesia', 'BI', 'A'),
(110, '999800405', 'Matematika', 'MAT', 'A'),
(111, '999800406', 'Muatan Lokal', 'ML', 'B'),
(112, '999800407', 'Pendidikan Agama', 'PA', 'A'),
(113, '999800408', 'Pendidikan Jasmani, Olahraga dan Kesehatan', 'PJOK', 'B'),
(114, '999800411', 'Seni Budaya', 'SB', 'B'),
(115, '999800501', 'Bahasa Indonesia', 'BI', 'A'),
(116, '999800502', 'Bahasa Inggris', 'BI', 'A'),
(117, '999800506', 'Matematika', 'MAT', 'A'),
(118, '999800507', 'Muatan Lokal', 'ML', 'B'),
(119, '999800508', 'Pendidikan Agama', 'PA', 'A'),
(120, '999800509', 'Pendidikan Jasmani, Olahraga dan Kesehatan', 'PJOK', 'B'),
(121, '999800601', 'Bahasa Indonesia', 'BI', 'A'),
(122, '999800602', 'Bahasa Inggris', 'BI', 'A'),
(123, '999800606', 'Matematika', 'MAT', 'A'),
(124, '999800607', 'Muatan Lokal', 'ML', 'B'),
(125, '999800608', 'Pendidikan Agama', 'PA', 'A'),
(126, '999800612', 'Sejarah', 'SEJ', 'A'),
(127, '999800412', 'Muatan Lokal Sekolah', 'MLS', 'B'),
(128, '999891002', 'Olahraga dan Kesehatan', 'OK', 'B'),
(129, '999891004', 'Seni dan Budaya', 'SB', 'B'),
(130, '999902912', 'Dasar-Dasar Jurnalistik (Md-3.1)', 'DDJM', 'C'),
(131, '999905422', 'Bahasa Indonesia (MD-11)', 'BIMD', 'A'),
(132, '999905920', 'Cidera Olahraga (MD. 5)', 'COMD', 'B'),
(133, '825230500', 'Dasar-dasar Otomatisasi Teknologi Pertanian', 'DOTP', 'C'),
(134, '999907814', 'Dasar seni dan desain (MD-03)', 'DMD', 'B'),
(135, '999908535', 'Teknik komunikasi dalam bahasa Indonesia dan Inggris (MD-9)', 'TIIMD', 'A'),
(136, '999908537', 'Logika matematika (MD-11)', 'LMD', 'A'),
(137, '999909501', 'Komunikasi dalam Bahasa Inggris dengan klien', 'KBI', 'A'),
(138, '999909502', 'Komunikasi dalam Bahasa Inggris dengan keluarga klien', 'KBI', 'A'),
(139, '999909505', 'Pencatatan Status kesehatan dan kegiatan klien dalam Bahasa Inggris', 'PSBI', 'A'),
(140, '999911704', 'MD 4  Manajemen Seni Pertunjukan', 'MDMSP', 'B'),
(141, '999912001', 'Kesadaran Tentang NKRI dan Pancasila (MD-01)', 'KTNKRIPMD', 'A'),
(142, '999912101', 'MP1. Pancasila', 'MPP', 'A'),
(143, '999912108', 'MP8. Seni Peran', 'MPSP', 'B'),
(144, '999912805', 'Dasar-dasar power train tipe rantai (chain) (MD-5)', 'DMD', 'C'),
(145, '999912806', 'Dasar-dasar power train tipe roda (wheel) (MD-6)', 'DMD', 'C'),
(146, '999912807', 'Dasar-dasar sistem hidrolik (MD-7)', 'DMD', 'C'),
(147, '999912811', 'Dasar-dasar undercarriage (MD-11)', 'DMD', 'C'),
(148, '999912813', 'Dasar-dasar  AC (MD-13)', 'DACMD', 'C'),
(149, '999912815', 'Dasar-dasar perawatan berkala (MD-15)', 'DMD', 'C'),
(150, '999912817', 'Dasar-dasar pengoperasian alat berat (MD-17)', 'DMD', 'C'),
(151, '999913703', 'MD.3 Dasar-dasar pengukuran', 'MDD', 'C'),
(152, '999913704', 'MD.4 Dasar-dasar penyolderan standar baku (IPC J-STD-001 certification)', 'MDDIPCJSTD', 'C'),
(153, '999913720', 'MD.20 Dasar-dasar robotika', 'MDD', 'C'),
(154, '100010000', 'Pendidikan Agama', 'PA', 'A'),
(155, '100011000', 'Pendidikan Agama Islam', 'PAI', 'A'),
(156, '800030105', 'Menjelaskan dasar-dasar penyakit & farmakologi', 'MEN', 'C'),
(157, '803030502', 'Memahami dasar-dasar sistem telekomunikasi', 'MEM', 'C'),
(158, '803080501', 'Menjelaskan dasar-dasar sistem telekomunikasi', 'MEN', 'C'),
(159, '804051002', 'Menerapkan pekerjaan dasar-dasar furnitur/cabinet making', 'MEN', 'C'),
(160, '805010300', 'Dasar-dasar Perhitungan Survey Pemetaan', 'DPSP', 'C'),
(161, '805010602', 'Menerapkan dasar-dasar survei & pemetaan', 'MEN', 'C'),
(162, '805010603', 'Menerapkan dasar-dasar gambar teknik', 'MEN', 'C'),
(163, '809010100', 'Dasar-dasar Kegrafikaan', 'DK', 'C'),
(164, '828020106', 'Berkomunikasi dalam bahasa Inggris', 'BI', 'A'),
(165, '843040107', 'Membuat lukisan teknik seni grafis', 'MEM', 'B'),
(166, '843040111', 'Melaksanakan pameran seni lukis', 'MEL', 'B'),
(167, '843040200', 'Seni Lukis Imajiner', 'SLI', 'B'),
(168, '843040300', 'Seni Eksperimen', 'SE', 'B'),
(169, '843040500', 'Seni Lukis Ekspresif', 'SLE', 'B'),
(170, '843090304', 'Menerapkan dasar-dasar dramatik', 'MEN', 'C'),
(171, '999800613', 'Seni Budaya', 'SB', 'B'),
(172, '999800701', 'Bahasa Indonesia', 'BI', 'A'),
(173, '999800702', 'Bahasa Inggris', 'BI', 'A'),
(174, '999800709', 'Matematika', 'MAT', 'A'),
(175, '999800710', 'Muatan Lokal', 'ML', 'B'),
(176, '999800711', 'Pendidikan Agama', 'PA', 'A'),
(177, '999800712', 'Pendidikan Jasmani, Olahraga dan Kesehatan', 'PJOK', 'B'),
(178, '999800715', 'Sejarah', 'SEJ', 'A'),
(179, '999800716', 'Seni Budaya', 'SB', 'B'),
(180, '999903317', 'Bahasa Inggris Teknik (MD-6)', 'BITMD', 'A'),
(181, '999905819', 'Dasar Bahasa Inggris', 'DBI', 'A'),
(182, '999905822', 'Bahasa Inggris (MD-04)', 'BIMD', 'A'),
(183, '999905823', 'Korespondensi Bahasa Inggris', 'KBI', 'A'),
(184, '999905813', 'Korespondensi Niaga Bahasa Indonesia', 'KNBI', 'A'),
(185, '999911413', 'MP-14 Dasar-Dasar Desain Busana', 'MPDDDB', 'C'),
(186, '999912931', 'Logika Matematika (MA-11)', 'LMMA', 'A'),
(187, '999915130', 'Berkomunikasi dalam Bahasa Inggris', 'BBI', 'A'),
(188, '401002000', 'Matematika Tingkat Lanjut', 'MTL', 'A'),
(189, '401900000', 'Ilmu Pengetahuan Alam dan Sosial (IPAS)', 'IPASIPAS', 'A'),
(190, '700109010', 'Seni Musik Ketrampilan', 'SMK', 'B'),
(191, '700110010', 'Seni Tari Ketrampilan', 'STK', 'B'),
(192, '700121000', 'Seni Rupa', 'SR', 'B'),
(193, '700122000', 'Seni Teater', 'ST', 'B'),
(194, '300130000', 'Bahasa Indonesia Tingkat Lanjut', 'BITL', 'A'),
(195, '300230000', 'Bahasa Inggris Tingkat Lanjut', 'BITL', 'A'),
(196, '200030070', 'Kewirausahaan (P4 Pancasila & Budaya Kerja)', 'KPPBK', 'B'),
(197, '200030080', 'Kebekerjaan (P4 Pancasila & Budaya Kerja)', 'KPPBK', 'B'),
(198, '200030090', 'Budaya Kerja (P4 Pancasila & Budaya Kerja)', 'BKPPBK', 'B'),
(199, '401901000', 'Projek IPAS', 'PIPAS', 'A'),
(200, '600030000', 'Seni Budaya dan Keterampilan', 'SBK', 'B'),
(201, '800000259', 'Seni Lukis', 'SL', 'B'),
(202, '843020100', 'Seni Budaya', 'SB', 'B'),
(203, '200030030', 'Bhinneka Tunggal Ika (P4 Pancasila & Budaya Kerja)', 'BTIPPBK', 'B'),
(204, '200030050', 'Suara Demokrasi (P4 Pancasila & Budaya Kerja)', 'SDPPBK', 'B'),
(205, '800000144', 'Dasar Dasar Seni Rupa', 'DDSR', 'B'),
(206, '800000147', 'Dasar Dasar Seni Pertunjukan', 'DDSP', 'B'),
(207, '800000268', 'Seni Musik', 'SM', 'B'),
(208, '800000269', 'Seni Tari', 'ST', 'B'),
(209, '800000270', 'Seni Karawitan', 'SK', 'B'),
(210, '800000271', 'Seni Pedalangan', 'SP', 'B'),
(211, '800000272', 'Seni Teater', 'ST', 'B'),
(212, '800030100', 'Dasar-dasar Kefarmasian', 'DK', 'C'),
(213, '802031101', 'Memahami dasar-dasar animasi', 'MEM', 'C'),
(214, '999800512', 'Seni Budaya', 'SB', 'B'),
(215, '825062101', 'Menjelaskan dasar-dasar budidaya aneka ternak', 'MEN', 'C'),
(216, '825062600', 'Dasar-dasar Mikrobiologi dan Parasitologi', 'DMP', 'C'),
(217, '999800609', 'Pendidikan Jasmani, Olahraga dan Kesehatan', 'PJOK', 'B'),
(218, '999904728', 'Bahasa Inggris untuk bidang Perpajakan (English for Taxation Purpose)', 'BIPETP', 'A'),
(219, '999905824', 'Korespondensi Bahasa Niaga Bahasa Indonesia', 'KBNBI', 'A'),
(220, '200010300', 'Pendidikan Pancasila', 'PP', 'A'),
(221, '401000000', 'Matematika (Umum)', 'MU', 'A'),
(222, '401234000', 'Sejarah Tingkat Lanjut', 'STL', 'A'),
(223, '600012000', 'Seni, Budaya dan Prakarya', 'SBP', 'B'),
(224, '800000260', 'Seni Patung', 'SP', 'B'),
(225, '100015000', 'Pendidikan Agama Hindu', 'PAH', 'A'),
(226, '999905914', 'PPPk dan Cedera Olahraga (MD. 3)', 'PPPCOMD', 'B'),
(227, '999907920', 'Dasar-dasar Komunikasi (MD-04)', 'DKMD', 'C'),
(228, '999912109', 'MP9. Dasar-Dasar Manajemen Produksi Acara TV', 'MPDDMPATV', 'C'),
(229, '999912419', 'MD.6 Teknik merangkai bunga pola dasar bentuk kipas (mass arrangement)', 'MDT', 'A'),
(230, '999801001', 'Keterampilan membaca, menulis dan bahasa Indonesia tingkat lanjutan', 'KI', 'A'),
(231, '200030020', 'Kearifan lokal (P4 Pancasila & Budaya Kerja)', 'KPPBK', 'B'),
(232, '100015090', 'Seni Budaya Keagamaan Hindu', 'SBKH', 'B'),
(233, '999916708', 'MD. 8. Bahasa Inggris untuk bidang Perpajakan (English for Taxation Purpose)', 'MDBIPETP', 'A'),
(234, '100015010', 'Pendidikan Agama Hindu dan Budi Pekerti', 'PAHBP', 'A'),
(235, '200030010', 'Gaya Hidup Berkelanjutan (P4 Pancasila & Budaya Kerja)', 'GHBPPBK', 'B'),
(236, '200030040', 'Bangunlah Jiwa dan Raganya (P4 Pancasila & Budaya Kerja)', 'BJRPPBK', 'B'),
(237, '401231000', 'Sejarah Indonesia', 'SI', 'A'),
(238, '999907614', 'Dasar seni dan desain (MD-03)', 'DMD', 'B'),
(239, '999912804', 'Dasar-dasar Diesel EngineFastener, Sealing device & locking application (MD-4)', 'DDEFSMD', 'C'),
(240, '999913404', 'MP-4 Aplikasi Matematika, Fisika dan Kimia Bahan dalam Perakitan Pipa', 'MPAMFKBPP', 'A'),
(241, '804050420', 'Dasar-dasar Konstruksi Bangunan', 'DKB', 'C'),
(242, '817010200', 'Dasar-dasar Geologi', 'DG', 'C'),
(243, '825010220', 'Pengoperasian Dasar-Dasar Perbengkelan Pertanian', 'PDDPP', 'C'),
(244, '843040310', 'Seni Lukis Eksperimental', 'SLE', 'B'),
(245, '200030060', 'Berekayasa dan Berteknologi untuk Membangun NKRI (P4 Pancasila & Budaya Kerja)', 'BBMNKRIPPB', 'B'),
(246, '500010000', 'Pendidikan Jasmani, Olahraga, dan Kesehatan', 'PJOK', 'B'),
(247, '100012050', 'Pendidikan Agama Kristen dan Budi Pekerti', 'PAKBP', 'A'),
(248, '100016010', 'Pendidikan Agama Konghuchu dan Budi Pekerti', 'PAKBP', 'A'),
(249, '999800312', 'Keterampilan membaca, menulis dan bahasa Indonesia tingkat dasar', 'KI', 'A'),
(250, '401001000', 'Matematika (Peminatan)', 'MP', 'A'),
(251, '401140800', 'Dasar-dasar Mikrobiologi', 'DM', 'C'),
(252, '800050201', 'Menjelaskan dasar-dasar keperawatan & pelayanan kesehatan', 'MEN', 'C'),
(253, '999912929', 'Teknik Komunikasi Dalam Bahasa Indonesia Dan Inggris(MA-9)', 'TKDBIDIMA', 'A');

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
(1, 1, 6),
(2, 1, 7),
(3, 1, 8),
(4, 1, 9),
(5, 1, 10),
(6, 1, 11),
(7, 1, 12),
(8, 1, 13),
(9, 1, 14),
(10, 2, 6),
(11, 2, 7),
(12, 2, 8),
(13, 2, 9),
(14, 2, 10),
(15, 2, 11),
(16, 2, 12),
(17, 2, 13),
(18, 2, 14),
(19, 3, 6),
(20, 3, 7),
(21, 3, 8),
(22, 3, 9),
(23, 3, 10),
(24, 3, 11),
(25, 3, 12),
(26, 3, 13),
(27, 3, 14),
(28, 4, 6),
(29, 4, 7),
(30, 4, 8),
(31, 4, 9),
(32, 4, 10),
(33, 4, 11),
(34, 4, 12),
(35, 4, 13),
(36, 4, 14),
(37, 5, 6),
(38, 5, 7),
(39, 5, 8),
(40, 5, 9),
(41, 5, 10),
(42, 5, 11),
(43, 5, 12),
(44, 5, 13),
(45, 5, 14),
(46, 6, 6),
(47, 6, 7),
(48, 6, 8),
(49, 6, 9),
(50, 6, 10),
(51, 6, 11),
(52, 6, 12),
(53, 6, 13),
(54, 6, 14),
(55, 7, 6),
(56, 7, 7),
(57, 7, 8),
(58, 7, 9),
(59, 7, 10),
(60, 7, 11),
(61, 7, 12),
(62, 7, 13),
(63, 7, 14),
(64, 8, 6),
(65, 8, 7),
(66, 8, 8),
(67, 8, 9),
(68, 8, 10),
(69, 8, 11),
(70, 8, 12),
(71, 8, 13),
(72, 8, 14),
(73, 9, 6),
(74, 9, 7),
(75, 9, 8),
(76, 9, 9),
(77, 9, 10),
(78, 9, 11),
(79, 9, 12),
(80, 9, 13),
(81, 9, 14),
(82, 10, 6),
(83, 10, 7),
(84, 10, 8),
(85, 10, 9),
(86, 10, 10),
(87, 10, 11),
(88, 10, 12),
(89, 10, 13),
(90, 10, 14),
(91, 11, 6),
(92, 11, 7),
(93, 11, 8),
(94, 11, 9),
(95, 11, 10),
(96, 11, 11),
(97, 11, 12),
(98, 11, 13),
(99, 11, 14),
(100, 12, 6),
(101, 12, 7),
(102, 12, 8),
(103, 12, 9),
(104, 12, 10),
(105, 12, 11),
(106, 12, 12),
(107, 12, 13),
(108, 12, 14),
(109, 13, 6),
(110, 13, 7),
(111, 13, 8),
(112, 13, 9),
(113, 13, 10),
(114, 13, 11),
(115, 13, 12),
(116, 13, 13),
(117, 13, 14),
(118, 14, 6),
(119, 14, 7),
(120, 14, 8),
(121, 14, 9),
(122, 14, 10),
(123, 14, 11),
(124, 14, 12),
(125, 14, 13),
(126, 14, 14),
(127, 15, 6),
(128, 15, 7),
(129, 15, 8),
(130, 15, 9),
(131, 15, 10),
(132, 15, 11),
(133, 15, 12),
(134, 15, 13),
(135, 15, 14),
(136, 16, 6),
(137, 16, 7),
(138, 16, 8),
(139, 16, 9),
(140, 16, 10),
(141, 16, 11),
(142, 16, 12),
(143, 16, 13),
(144, 16, 14),
(145, 17, 6),
(146, 17, 7),
(147, 17, 8),
(148, 17, 9),
(149, 17, 10),
(150, 17, 11),
(151, 17, 12),
(152, 17, 13),
(153, 17, 14),
(154, 18, 6),
(155, 18, 7),
(156, 18, 8),
(157, 18, 9),
(158, 18, 10),
(159, 18, 11),
(160, 18, 12),
(161, 18, 13),
(162, 18, 14),
(163, 19, 6),
(164, 19, 7),
(165, 19, 8),
(166, 19, 9),
(167, 19, 10),
(168, 19, 11),
(169, 19, 12),
(170, 19, 13),
(171, 19, 14),
(172, 20, 6),
(173, 20, 7),
(174, 20, 8),
(175, 20, 9),
(176, 20, 10),
(177, 20, 11),
(178, 20, 12),
(179, 20, 13),
(180, 20, 14),
(181, 21, 6),
(182, 21, 7),
(183, 21, 8),
(184, 21, 9),
(185, 21, 10),
(186, 21, 11),
(187, 21, 12),
(188, 21, 13),
(189, 21, 14),
(190, 22, 6),
(191, 22, 7),
(192, 22, 8),
(193, 22, 9),
(194, 22, 10),
(195, 22, 11),
(196, 22, 12),
(197, 22, 13),
(198, 22, 14),
(199, 23, 6),
(200, 23, 7),
(201, 23, 8),
(202, 23, 9),
(203, 23, 10),
(204, 23, 11),
(205, 23, 12),
(206, 23, 13),
(207, 23, 14),
(208, 25, 6),
(209, 25, 7),
(210, 25, 8),
(211, 25, 9),
(212, 25, 10),
(213, 25, 11),
(214, 25, 12),
(215, 25, 13),
(216, 25, 14),
(217, 26, 6),
(218, 26, 7),
(219, 26, 8),
(220, 26, 9),
(221, 26, 10),
(222, 26, 11),
(223, 26, 12),
(224, 26, 13),
(225, 26, 14),
(226, 27, 6),
(227, 27, 7),
(228, 27, 8),
(229, 27, 9),
(230, 27, 10),
(231, 27, 11),
(232, 27, 12),
(233, 27, 13),
(234, 27, 14),
(235, 28, 6),
(236, 28, 7),
(237, 28, 8),
(238, 28, 9),
(239, 28, 10),
(240, 28, 11),
(241, 28, 12),
(242, 28, 13),
(243, 28, 14),
(244, 29, 6),
(245, 29, 7),
(246, 29, 8),
(247, 29, 9),
(248, 29, 10),
(249, 29, 11),
(250, 29, 12),
(251, 29, 13),
(252, 29, 14),
(253, 31, 6),
(254, 31, 7),
(255, 31, 8),
(256, 31, 9),
(257, 31, 10),
(258, 31, 11),
(259, 31, 12),
(260, 31, 13),
(261, 31, 14),
(262, 32, 6),
(263, 32, 7),
(264, 32, 8),
(265, 32, 9),
(266, 32, 10),
(267, 32, 11),
(268, 32, 12),
(269, 32, 13),
(270, 32, 14),
(271, 33, 6),
(272, 33, 7),
(273, 33, 8),
(274, 33, 9),
(275, 33, 10),
(276, 33, 11),
(277, 33, 12),
(278, 33, 13),
(279, 33, 14),
(280, 34, 6),
(281, 34, 7),
(282, 34, 8),
(283, 34, 9),
(284, 34, 10),
(285, 34, 11),
(286, 34, 12),
(287, 34, 13),
(288, 34, 14),
(289, 35, 6),
(290, 35, 7),
(291, 35, 8),
(292, 35, 9),
(293, 35, 10),
(294, 35, 11),
(295, 35, 12),
(296, 35, 13),
(297, 35, 14),
(298, 36, 6),
(299, 36, 7),
(300, 36, 8),
(301, 36, 9),
(302, 36, 10),
(303, 36, 11),
(304, 36, 12),
(305, 36, 13),
(306, 36, 14),
(307, 37, 6),
(308, 37, 7),
(309, 37, 8),
(310, 37, 9),
(311, 37, 10),
(312, 37, 11),
(313, 37, 12),
(314, 37, 13),
(315, 37, 14),
(316, 47, 6),
(317, 47, 7),
(318, 47, 8),
(319, 47, 9),
(320, 47, 10),
(321, 47, 11),
(322, 47, 12),
(323, 47, 13),
(324, 47, 14),
(325, 57, 6),
(326, 57, 7),
(327, 57, 8),
(328, 57, 9),
(329, 57, 10),
(330, 57, 11),
(331, 57, 12),
(332, 57, 13),
(333, 57, 14),
(334, 69, 6),
(335, 69, 7),
(336, 69, 8),
(337, 69, 9),
(338, 69, 10),
(339, 69, 11),
(340, 69, 12),
(341, 69, 13),
(342, 69, 14),
(343, 82, 6),
(344, 82, 7),
(345, 82, 8),
(346, 82, 9),
(347, 82, 10),
(348, 82, 11),
(349, 82, 12),
(350, 82, 13),
(351, 82, 14),
(352, 83, 6),
(353, 83, 7),
(354, 83, 8),
(355, 83, 9),
(356, 83, 10),
(357, 83, 11),
(358, 83, 12),
(359, 83, 13),
(360, 83, 14),
(361, 86, 6),
(362, 86, 7),
(363, 86, 8),
(364, 86, 9),
(365, 86, 10),
(366, 86, 11),
(367, 86, 12),
(368, 86, 13),
(369, 86, 14),
(370, 87, 6),
(371, 87, 7),
(372, 87, 8),
(373, 87, 9),
(374, 87, 10),
(375, 87, 11),
(376, 87, 12),
(377, 87, 13),
(378, 87, 14),
(379, 94, 6),
(380, 94, 7),
(381, 94, 8),
(382, 94, 9),
(383, 94, 10),
(384, 94, 11),
(385, 94, 12),
(386, 94, 13),
(387, 94, 14),
(388, 95, 6),
(389, 95, 7),
(390, 95, 8),
(391, 95, 9),
(392, 95, 10),
(393, 95, 11),
(394, 95, 12),
(395, 95, 13),
(396, 95, 14),
(397, 96, 6),
(398, 96, 7),
(399, 96, 8),
(400, 96, 9),
(401, 96, 10),
(402, 96, 11),
(403, 96, 12),
(404, 96, 13),
(405, 96, 14),
(406, 97, 6),
(407, 97, 7),
(408, 97, 8),
(409, 97, 9),
(410, 97, 10),
(411, 97, 11),
(412, 97, 12),
(413, 97, 13),
(414, 97, 14),
(415, 98, 6),
(416, 98, 7),
(417, 98, 8),
(418, 98, 9),
(419, 98, 10),
(420, 98, 11),
(421, 98, 12),
(422, 98, 13),
(423, 98, 14),
(424, 99, 6),
(425, 99, 7),
(426, 99, 8),
(427, 99, 9),
(428, 99, 10),
(429, 99, 11),
(430, 99, 12),
(431, 99, 13),
(432, 99, 14),
(433, 100, 6),
(434, 100, 7),
(435, 100, 8),
(436, 100, 9),
(437, 100, 10),
(438, 100, 11),
(439, 100, 12),
(440, 100, 13),
(441, 100, 14),
(442, 101, 6),
(443, 101, 7),
(444, 101, 8),
(445, 101, 9),
(446, 101, 10),
(447, 101, 11),
(448, 101, 12),
(449, 101, 13),
(450, 101, 14),
(451, 102, 6),
(452, 102, 7),
(453, 102, 8),
(454, 102, 9),
(455, 102, 10),
(456, 102, 11),
(457, 102, 12),
(458, 102, 13),
(459, 102, 14),
(460, 103, 6),
(461, 103, 7),
(462, 103, 8),
(463, 103, 9),
(464, 103, 10),
(465, 103, 11),
(466, 103, 12),
(467, 103, 13),
(468, 103, 14),
(469, 105, 6),
(470, 105, 7),
(471, 105, 8),
(472, 105, 9),
(473, 105, 10),
(474, 105, 11),
(475, 105, 12),
(476, 105, 13),
(477, 105, 14),
(478, 106, 6),
(479, 106, 7),
(480, 106, 8),
(481, 106, 9),
(482, 106, 10),
(483, 106, 11),
(484, 106, 12),
(485, 106, 13),
(486, 106, 14),
(487, 107, 6),
(488, 107, 7),
(489, 107, 8),
(490, 107, 9),
(491, 107, 10),
(492, 107, 11),
(493, 107, 12),
(494, 107, 13),
(495, 107, 14),
(496, 109, 6),
(497, 109, 7),
(498, 109, 8),
(499, 109, 9),
(500, 109, 10),
(501, 109, 11),
(502, 109, 12),
(503, 109, 13),
(504, 109, 14),
(505, 110, 6),
(506, 110, 7),
(507, 110, 8),
(508, 110, 9),
(509, 110, 10),
(510, 110, 11),
(511, 110, 12),
(512, 110, 13),
(513, 110, 14),
(514, 111, 6),
(515, 111, 7),
(516, 111, 8),
(517, 111, 9),
(518, 111, 10),
(519, 111, 11),
(520, 111, 12),
(521, 111, 13),
(522, 111, 14),
(523, 112, 6),
(524, 112, 7),
(525, 112, 8),
(526, 112, 9),
(527, 112, 10),
(528, 112, 11),
(529, 112, 12),
(530, 112, 13),
(531, 112, 14),
(532, 113, 6),
(533, 113, 7),
(534, 113, 8),
(535, 113, 9),
(536, 113, 10),
(537, 113, 11),
(538, 113, 12),
(539, 113, 13),
(540, 113, 14),
(541, 114, 6),
(542, 114, 7),
(543, 114, 8),
(544, 114, 9),
(545, 114, 10),
(546, 114, 11),
(547, 114, 12),
(548, 114, 13),
(549, 114, 14),
(550, 115, 6),
(551, 115, 7),
(552, 115, 8),
(553, 115, 9),
(554, 115, 10),
(555, 115, 11),
(556, 115, 12),
(557, 115, 13),
(558, 115, 14),
(559, 116, 6),
(560, 116, 7),
(561, 116, 8),
(562, 116, 9),
(563, 116, 10),
(564, 116, 11),
(565, 116, 12),
(566, 116, 13),
(567, 116, 14),
(568, 117, 6),
(569, 117, 7),
(570, 117, 8),
(571, 117, 9),
(572, 117, 10),
(573, 117, 11),
(574, 117, 12),
(575, 117, 13),
(576, 117, 14),
(577, 118, 6),
(578, 118, 7),
(579, 118, 8),
(580, 118, 9),
(581, 118, 10),
(582, 118, 11),
(583, 118, 12),
(584, 118, 13),
(585, 118, 14),
(586, 119, 6),
(587, 119, 7),
(588, 119, 8),
(589, 119, 9),
(590, 119, 10),
(591, 119, 11),
(592, 119, 12),
(593, 119, 13),
(594, 119, 14),
(595, 120, 6),
(596, 120, 7),
(597, 120, 8),
(598, 120, 9),
(599, 120, 10),
(600, 120, 11),
(601, 120, 12),
(602, 120, 13),
(603, 120, 14),
(604, 121, 6),
(605, 121, 7),
(606, 121, 8),
(607, 121, 9),
(608, 121, 10),
(609, 121, 11),
(610, 121, 12),
(611, 121, 13),
(612, 121, 14),
(613, 122, 6),
(614, 122, 7),
(615, 122, 8),
(616, 122, 9),
(617, 122, 10),
(618, 122, 11),
(619, 122, 12),
(620, 122, 13),
(621, 122, 14),
(622, 123, 6),
(623, 123, 7),
(624, 123, 8),
(625, 123, 9),
(626, 123, 10),
(627, 123, 11),
(628, 123, 12),
(629, 123, 13),
(630, 123, 14),
(631, 124, 6),
(632, 124, 7),
(633, 124, 8),
(634, 124, 9),
(635, 124, 10),
(636, 124, 11),
(637, 124, 12),
(638, 124, 13),
(639, 124, 14),
(640, 125, 6),
(641, 125, 7),
(642, 125, 8),
(643, 125, 9),
(644, 125, 10),
(645, 125, 11),
(646, 125, 12),
(647, 125, 13),
(648, 125, 14),
(649, 126, 6),
(650, 126, 7),
(651, 126, 8),
(652, 126, 9),
(653, 126, 10),
(654, 126, 11),
(655, 126, 12),
(656, 126, 13),
(657, 126, 14),
(658, 127, 6),
(659, 127, 7),
(660, 127, 8),
(661, 127, 9),
(662, 127, 10),
(663, 127, 11),
(664, 127, 12),
(665, 127, 13),
(666, 127, 14),
(667, 128, 6),
(668, 128, 7),
(669, 128, 8),
(670, 128, 9),
(671, 128, 10),
(672, 128, 11),
(673, 128, 12),
(674, 128, 13),
(675, 128, 14),
(676, 129, 6),
(677, 129, 7),
(678, 129, 8),
(679, 129, 9),
(680, 129, 10),
(681, 129, 11),
(682, 129, 12),
(683, 129, 13),
(684, 129, 14),
(685, 131, 6),
(686, 131, 7),
(687, 131, 8),
(688, 131, 9),
(689, 131, 10),
(690, 131, 11),
(691, 131, 12),
(692, 131, 13),
(693, 131, 14),
(694, 132, 6),
(695, 132, 7),
(696, 132, 8),
(697, 132, 9),
(698, 132, 10),
(699, 132, 11),
(700, 132, 12),
(701, 132, 13),
(702, 132, 14),
(703, 134, 6),
(704, 134, 7),
(705, 134, 8),
(706, 134, 9),
(707, 134, 10),
(708, 134, 11),
(709, 134, 12),
(710, 134, 13),
(711, 134, 14),
(712, 135, 6),
(713, 135, 7),
(714, 135, 8),
(715, 135, 9),
(716, 135, 10),
(717, 135, 11),
(718, 135, 12),
(719, 135, 13),
(720, 135, 14),
(721, 136, 6),
(722, 136, 7),
(723, 136, 8),
(724, 136, 9),
(725, 136, 10),
(726, 136, 11),
(727, 136, 12),
(728, 136, 13),
(729, 136, 14),
(730, 137, 6),
(731, 137, 7),
(732, 137, 8),
(733, 137, 9),
(734, 137, 10),
(735, 137, 11),
(736, 137, 12),
(737, 137, 13),
(738, 137, 14),
(739, 138, 6),
(740, 138, 7),
(741, 138, 8),
(742, 138, 9),
(743, 138, 10),
(744, 138, 11),
(745, 138, 12),
(746, 138, 13),
(747, 138, 14),
(748, 139, 6),
(749, 139, 7),
(750, 139, 8),
(751, 139, 9),
(752, 139, 10),
(753, 139, 11),
(754, 139, 12),
(755, 139, 13),
(756, 139, 14),
(757, 140, 6),
(758, 140, 7),
(759, 140, 8),
(760, 140, 9),
(761, 140, 10),
(762, 140, 11),
(763, 140, 12),
(764, 140, 13),
(765, 140, 14),
(766, 141, 6),
(767, 141, 7),
(768, 141, 8),
(769, 141, 9),
(770, 141, 10),
(771, 141, 11),
(772, 141, 12),
(773, 141, 13),
(774, 141, 14),
(775, 142, 6),
(776, 142, 7),
(777, 142, 8),
(778, 142, 9),
(779, 142, 10),
(780, 142, 11),
(781, 142, 12),
(782, 142, 13),
(783, 142, 14),
(784, 143, 6),
(785, 143, 7),
(786, 143, 8),
(787, 143, 9),
(788, 143, 10),
(789, 143, 11),
(790, 143, 12),
(791, 143, 13),
(792, 143, 14),
(793, 154, 6),
(794, 154, 7),
(795, 154, 8),
(796, 154, 9),
(797, 154, 10),
(798, 154, 11),
(799, 154, 12),
(800, 154, 13),
(801, 154, 14),
(802, 155, 6),
(803, 155, 7),
(804, 155, 8),
(805, 155, 9),
(806, 155, 10),
(807, 155, 11),
(808, 155, 12),
(809, 155, 13),
(810, 155, 14),
(811, 164, 6),
(812, 164, 7),
(813, 164, 8),
(814, 164, 9),
(815, 164, 10),
(816, 164, 11),
(817, 164, 12),
(818, 164, 13),
(819, 164, 14),
(820, 165, 6),
(821, 165, 7),
(822, 165, 8),
(823, 165, 9),
(824, 165, 10),
(825, 165, 11),
(826, 165, 12),
(827, 165, 13),
(828, 165, 14),
(829, 166, 6),
(830, 166, 7),
(831, 166, 8),
(832, 166, 9),
(833, 166, 10),
(834, 166, 11),
(835, 166, 12),
(836, 166, 13),
(837, 166, 14),
(838, 167, 6),
(839, 167, 7),
(840, 167, 8),
(841, 167, 9),
(842, 167, 10),
(843, 167, 11),
(844, 167, 12),
(845, 167, 13),
(846, 167, 14),
(847, 168, 6),
(848, 168, 7),
(849, 168, 8),
(850, 168, 9),
(851, 168, 10),
(852, 168, 11),
(853, 168, 12),
(854, 168, 13),
(855, 168, 14),
(856, 169, 6),
(857, 169, 7),
(858, 169, 8),
(859, 169, 9),
(860, 169, 10),
(861, 169, 11),
(862, 169, 12),
(863, 169, 13),
(864, 169, 14),
(865, 171, 6),
(866, 171, 7),
(867, 171, 8),
(868, 171, 9),
(869, 171, 10),
(870, 171, 11),
(871, 171, 12),
(872, 171, 13),
(873, 171, 14),
(874, 172, 6),
(875, 172, 7),
(876, 172, 8),
(877, 172, 9),
(878, 172, 10),
(879, 172, 11),
(880, 172, 12),
(881, 172, 13),
(882, 172, 14),
(883, 173, 6),
(884, 173, 7),
(885, 173, 8),
(886, 173, 9),
(887, 173, 10),
(888, 173, 11),
(889, 173, 12),
(890, 173, 13),
(891, 173, 14),
(892, 174, 6),
(893, 174, 7),
(894, 174, 8),
(895, 174, 9),
(896, 174, 10),
(897, 174, 11),
(898, 174, 12),
(899, 174, 13),
(900, 174, 14),
(901, 175, 6),
(902, 175, 7),
(903, 175, 8),
(904, 175, 9),
(905, 175, 10),
(906, 175, 11),
(907, 175, 12),
(908, 175, 13),
(909, 175, 14),
(910, 176, 6),
(911, 176, 7),
(912, 176, 8),
(913, 176, 9),
(914, 176, 10),
(915, 176, 11),
(916, 176, 12),
(917, 176, 13),
(918, 176, 14),
(919, 177, 6),
(920, 177, 7),
(921, 177, 8),
(922, 177, 9),
(923, 177, 10),
(924, 177, 11),
(925, 177, 12),
(926, 177, 13),
(927, 177, 14),
(928, 178, 6),
(929, 178, 7),
(930, 178, 8),
(931, 178, 9),
(932, 178, 10),
(933, 178, 11),
(934, 178, 12),
(935, 178, 13),
(936, 178, 14),
(937, 179, 6),
(938, 179, 7),
(939, 179, 8),
(940, 179, 9),
(941, 179, 10),
(942, 179, 11),
(943, 179, 12),
(944, 179, 13),
(945, 179, 14),
(946, 180, 6),
(947, 180, 7),
(948, 180, 8),
(949, 180, 9),
(950, 180, 10),
(951, 180, 11),
(952, 180, 12),
(953, 180, 13),
(954, 180, 14),
(955, 181, 6),
(956, 181, 7),
(957, 181, 8),
(958, 181, 9),
(959, 181, 10),
(960, 181, 11),
(961, 181, 12),
(962, 181, 13),
(963, 181, 14),
(964, 182, 6),
(965, 182, 7),
(966, 182, 8),
(967, 182, 9),
(968, 182, 10),
(969, 182, 11),
(970, 182, 12),
(971, 182, 13),
(972, 182, 14),
(973, 183, 6),
(974, 183, 7),
(975, 183, 8),
(976, 183, 9),
(977, 183, 10),
(978, 183, 11),
(979, 183, 12),
(980, 183, 13),
(981, 183, 14),
(982, 184, 6),
(983, 184, 7),
(984, 184, 8),
(985, 184, 9),
(986, 184, 10),
(987, 184, 11),
(988, 184, 12),
(989, 184, 13),
(990, 184, 14),
(991, 186, 6),
(992, 186, 7),
(993, 186, 8),
(994, 186, 9),
(995, 186, 10),
(996, 186, 11),
(997, 186, 12),
(998, 186, 13),
(999, 186, 14),
(1000, 187, 6),
(1001, 187, 7),
(1002, 187, 8),
(1003, 187, 9),
(1004, 187, 10),
(1005, 187, 11),
(1006, 187, 12),
(1007, 187, 13),
(1008, 187, 14),
(1009, 188, 6),
(1010, 188, 7),
(1011, 188, 8),
(1012, 188, 9),
(1013, 188, 10),
(1014, 188, 11),
(1015, 188, 12),
(1016, 188, 13),
(1017, 188, 14),
(1018, 189, 6),
(1019, 189, 7),
(1020, 189, 8),
(1021, 189, 9),
(1022, 189, 10),
(1023, 189, 11),
(1024, 189, 12),
(1025, 189, 13),
(1026, 189, 14),
(1027, 190, 6),
(1028, 190, 7),
(1029, 190, 8),
(1030, 190, 9),
(1031, 190, 10),
(1032, 190, 11),
(1033, 190, 12),
(1034, 190, 13),
(1035, 190, 14),
(1036, 191, 6),
(1037, 191, 7),
(1038, 191, 8),
(1039, 191, 9),
(1040, 191, 10),
(1041, 191, 11),
(1042, 191, 12),
(1043, 191, 13),
(1044, 191, 14),
(1045, 192, 6),
(1046, 192, 7),
(1047, 192, 8),
(1048, 192, 9),
(1049, 192, 10),
(1050, 192, 11),
(1051, 192, 12),
(1052, 192, 13),
(1053, 192, 14),
(1054, 193, 6),
(1055, 193, 7),
(1056, 193, 8),
(1057, 193, 9),
(1058, 193, 10),
(1059, 193, 11),
(1060, 193, 12),
(1061, 193, 13),
(1062, 193, 14),
(1063, 194, 6),
(1064, 194, 7),
(1065, 194, 8),
(1066, 194, 9),
(1067, 194, 10),
(1068, 194, 11),
(1069, 194, 12),
(1070, 194, 13),
(1071, 194, 14),
(1072, 195, 6),
(1073, 195, 7),
(1074, 195, 8),
(1075, 195, 9),
(1076, 195, 10),
(1077, 195, 11),
(1078, 195, 12),
(1079, 195, 13),
(1080, 195, 14),
(1081, 196, 6),
(1082, 196, 7),
(1083, 196, 8),
(1084, 196, 9),
(1085, 196, 10),
(1086, 196, 11),
(1087, 196, 12),
(1088, 196, 13),
(1089, 196, 14),
(1090, 197, 6),
(1091, 197, 7),
(1092, 197, 8),
(1093, 197, 9),
(1094, 197, 10),
(1095, 197, 11),
(1096, 197, 12),
(1097, 197, 13),
(1098, 197, 14),
(1099, 198, 6),
(1100, 198, 7),
(1101, 198, 8),
(1102, 198, 9),
(1103, 198, 10),
(1104, 198, 11),
(1105, 198, 12),
(1106, 198, 13),
(1107, 198, 14),
(1108, 199, 6),
(1109, 199, 7),
(1110, 199, 8),
(1111, 199, 9),
(1112, 199, 10),
(1113, 199, 11),
(1114, 199, 12),
(1115, 199, 13),
(1116, 199, 14),
(1117, 200, 6),
(1118, 200, 7),
(1119, 200, 8),
(1120, 200, 9),
(1121, 200, 10),
(1122, 200, 11),
(1123, 200, 12),
(1124, 200, 13),
(1125, 200, 14),
(1126, 201, 6),
(1127, 201, 7),
(1128, 201, 8),
(1129, 201, 9),
(1130, 201, 10),
(1131, 201, 11),
(1132, 201, 12),
(1133, 201, 13),
(1134, 201, 14),
(1135, 202, 6),
(1136, 202, 7),
(1137, 202, 8),
(1138, 202, 9),
(1139, 202, 10),
(1140, 202, 11),
(1141, 202, 12),
(1142, 202, 13),
(1143, 202, 14),
(1144, 203, 6),
(1145, 203, 7),
(1146, 203, 8),
(1147, 203, 9),
(1148, 203, 10),
(1149, 203, 11),
(1150, 203, 12),
(1151, 203, 13),
(1152, 203, 14),
(1153, 204, 6),
(1154, 204, 7),
(1155, 204, 8),
(1156, 204, 9),
(1157, 204, 10),
(1158, 204, 11),
(1159, 204, 12),
(1160, 204, 13),
(1161, 204, 14),
(1162, 205, 6),
(1163, 205, 7),
(1164, 205, 8),
(1165, 205, 9),
(1166, 205, 10),
(1167, 205, 11),
(1168, 205, 12),
(1169, 205, 13),
(1170, 205, 14),
(1171, 206, 6),
(1172, 206, 7),
(1173, 206, 8),
(1174, 206, 9),
(1175, 206, 10),
(1176, 206, 11),
(1177, 206, 12),
(1178, 206, 13),
(1179, 206, 14),
(1180, 207, 6),
(1181, 207, 7),
(1182, 207, 8),
(1183, 207, 9),
(1184, 207, 10),
(1185, 207, 11),
(1186, 207, 12),
(1187, 207, 13),
(1188, 207, 14),
(1189, 208, 6),
(1190, 208, 7),
(1191, 208, 8),
(1192, 208, 9),
(1193, 208, 10),
(1194, 208, 11),
(1195, 208, 12),
(1196, 208, 13),
(1197, 208, 14),
(1198, 209, 6),
(1199, 209, 7),
(1200, 209, 8),
(1201, 209, 9),
(1202, 209, 10),
(1203, 209, 11),
(1204, 209, 12),
(1205, 209, 13),
(1206, 209, 14),
(1207, 210, 6),
(1208, 210, 7),
(1209, 210, 8),
(1210, 210, 9),
(1211, 210, 10),
(1212, 210, 11),
(1213, 210, 12),
(1214, 210, 13),
(1215, 210, 14),
(1216, 211, 6),
(1217, 211, 7),
(1218, 211, 8),
(1219, 211, 9),
(1220, 211, 10),
(1221, 211, 11),
(1222, 211, 12),
(1223, 211, 13),
(1224, 211, 14),
(1225, 214, 6),
(1226, 214, 7),
(1227, 214, 8),
(1228, 214, 9),
(1229, 214, 10),
(1230, 214, 11),
(1231, 214, 12),
(1232, 214, 13),
(1233, 214, 14),
(1234, 217, 6),
(1235, 217, 7),
(1236, 217, 8),
(1237, 217, 9),
(1238, 217, 10),
(1239, 217, 11),
(1240, 217, 12),
(1241, 217, 13),
(1242, 217, 14),
(1243, 218, 6),
(1244, 218, 7),
(1245, 218, 8),
(1246, 218, 9),
(1247, 218, 10),
(1248, 218, 11),
(1249, 218, 12),
(1250, 218, 13),
(1251, 218, 14),
(1252, 219, 6),
(1253, 219, 7),
(1254, 219, 8),
(1255, 219, 9),
(1256, 219, 10),
(1257, 219, 11),
(1258, 219, 12),
(1259, 219, 13),
(1260, 219, 14),
(1261, 220, 6),
(1262, 220, 7),
(1263, 220, 8),
(1264, 220, 9),
(1265, 220, 10),
(1266, 220, 11),
(1267, 220, 12),
(1268, 220, 13),
(1269, 220, 14),
(1270, 221, 6),
(1271, 221, 7),
(1272, 221, 8),
(1273, 221, 9),
(1274, 221, 10),
(1275, 221, 11),
(1276, 221, 12),
(1277, 221, 13),
(1278, 221, 14),
(1279, 222, 6),
(1280, 222, 7),
(1281, 222, 8),
(1282, 222, 9),
(1283, 222, 10),
(1284, 222, 11),
(1285, 222, 12),
(1286, 222, 13),
(1287, 222, 14),
(1288, 223, 6),
(1289, 223, 7),
(1290, 223, 8),
(1291, 223, 9),
(1292, 223, 10),
(1293, 223, 11),
(1294, 223, 12),
(1295, 223, 13),
(1296, 223, 14),
(1297, 224, 6),
(1298, 224, 7),
(1299, 224, 8),
(1300, 224, 9),
(1301, 224, 10),
(1302, 224, 11),
(1303, 224, 12),
(1304, 224, 13),
(1305, 224, 14),
(1306, 225, 6),
(1307, 225, 7),
(1308, 225, 8),
(1309, 225, 9),
(1310, 225, 10),
(1311, 225, 11),
(1312, 225, 12),
(1313, 225, 13),
(1314, 225, 14),
(1315, 226, 6),
(1316, 226, 7),
(1317, 226, 8),
(1318, 226, 9),
(1319, 226, 10),
(1320, 226, 11),
(1321, 226, 12),
(1322, 226, 13),
(1323, 226, 14),
(1324, 229, 6),
(1325, 229, 7),
(1326, 229, 8),
(1327, 229, 9),
(1328, 229, 10),
(1329, 229, 11),
(1330, 229, 12),
(1331, 229, 13),
(1332, 229, 14),
(1333, 230, 6),
(1334, 230, 7),
(1335, 230, 8),
(1336, 230, 9),
(1337, 230, 10),
(1338, 230, 11),
(1339, 230, 12),
(1340, 230, 13),
(1341, 230, 14),
(1342, 231, 6),
(1343, 231, 7),
(1344, 231, 8),
(1345, 231, 9),
(1346, 231, 10),
(1347, 231, 11),
(1348, 231, 12),
(1349, 231, 13),
(1350, 231, 14),
(1351, 232, 6),
(1352, 232, 7),
(1353, 232, 8),
(1354, 232, 9),
(1355, 232, 10),
(1356, 232, 11),
(1357, 232, 12),
(1358, 232, 13),
(1359, 232, 14),
(1360, 233, 6),
(1361, 233, 7),
(1362, 233, 8),
(1363, 233, 9),
(1364, 233, 10),
(1365, 233, 11),
(1366, 233, 12),
(1367, 233, 13),
(1368, 233, 14),
(1369, 234, 6),
(1370, 234, 7),
(1371, 234, 8),
(1372, 234, 9),
(1373, 234, 10),
(1374, 234, 11),
(1375, 234, 12),
(1376, 234, 13),
(1377, 234, 14),
(1378, 235, 6),
(1379, 235, 7),
(1380, 235, 8),
(1381, 235, 9),
(1382, 235, 10),
(1383, 235, 11),
(1384, 235, 12),
(1385, 235, 13),
(1386, 235, 14),
(1387, 236, 6),
(1388, 236, 7),
(1389, 236, 8),
(1390, 236, 9),
(1391, 236, 10),
(1392, 236, 11),
(1393, 236, 12),
(1394, 236, 13),
(1395, 236, 14),
(1396, 237, 6),
(1397, 237, 7),
(1398, 237, 8),
(1399, 237, 9),
(1400, 237, 10),
(1401, 237, 11),
(1402, 237, 12),
(1403, 237, 13),
(1404, 237, 14),
(1405, 238, 6),
(1406, 238, 7),
(1407, 238, 8),
(1408, 238, 9),
(1409, 238, 10),
(1410, 238, 11),
(1411, 238, 12),
(1412, 238, 13),
(1413, 238, 14),
(1414, 240, 6),
(1415, 240, 7),
(1416, 240, 8),
(1417, 240, 9),
(1418, 240, 10),
(1419, 240, 11),
(1420, 240, 12),
(1421, 240, 13),
(1422, 240, 14),
(1423, 244, 6),
(1424, 244, 7),
(1425, 244, 8),
(1426, 244, 9),
(1427, 244, 10),
(1428, 244, 11),
(1429, 244, 12),
(1430, 244, 13),
(1431, 244, 14),
(1432, 245, 6),
(1433, 245, 7),
(1434, 245, 8),
(1435, 245, 9),
(1436, 245, 10),
(1437, 245, 11),
(1438, 245, 12),
(1439, 245, 13),
(1440, 245, 14),
(1441, 246, 6),
(1442, 246, 7),
(1443, 246, 8),
(1444, 246, 9),
(1445, 246, 10),
(1446, 246, 11),
(1447, 246, 12),
(1448, 246, 13),
(1449, 246, 14),
(1450, 247, 6),
(1451, 247, 7),
(1452, 247, 8),
(1453, 247, 9),
(1454, 247, 10),
(1455, 247, 11),
(1456, 247, 12),
(1457, 247, 13),
(1458, 247, 14),
(1459, 248, 6),
(1460, 248, 7),
(1461, 248, 8),
(1462, 248, 9),
(1463, 248, 10),
(1464, 248, 11),
(1465, 248, 12),
(1466, 248, 13),
(1467, 248, 14),
(1468, 249, 6),
(1469, 249, 7),
(1470, 249, 8),
(1471, 249, 9),
(1472, 249, 10),
(1473, 249, 11),
(1474, 249, 12),
(1475, 249, 13),
(1476, 249, 14),
(1477, 250, 6),
(1478, 250, 7),
(1479, 250, 8),
(1480, 250, 9),
(1481, 250, 10),
(1482, 250, 11),
(1483, 250, 12),
(1484, 250, 13),
(1485, 250, 14),
(1486, 253, 6),
(1487, 253, 7),
(1488, 253, 8),
(1489, 253, 9),
(1490, 253, 10),
(1491, 253, 11),
(1492, 253, 12),
(1493, 253, 13),
(1494, 253, 14);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_materi`
--

CREATE TABLE `tbl_materi` (
  `id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `file_materi` varchar(255) DEFAULT NULL,
  `link_youtube` varchar(255) DEFAULT NULL,
  `status` int(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_nilai`
--

CREATE TABLE `tbl_nilai` (
  `id` int(11) NOT NULL,
  `id_jadwal` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `status` enum('SEDANG MENGERJAKAN','SELESAI','RAGU','NONAKTIF') DEFAULT 'NONAKTIF',
  `waktu_mulai` datetime DEFAULT NULL,
  `waktu_selesai` datetime DEFAULT NULL,
  `nilai_pg` float DEFAULT 0,
  `nilai_esai` float DEFAULT 0,
  `nilai_sementara` float DEFAULT 0,
  `jml_benar` int(11) DEFAULT 0,
  `jml_salah` int(11) DEFAULT 0,
  `list_jawaban` text DEFAULT NULL,
  `list_ragu_ragu` text DEFAULT NULL,
  `is_locked` tinyint(1) DEFAULT 1 COMMENT '1=Terkunci di device, 0=Buka Kunci',
  `extra_time` int(11) DEFAULT 0 COMMENT 'Menit tambahan waktu',
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL COMMENT 'Browser Siswa',
  `is_blocked` tinyint(1) DEFAULT 0 COMMENT '1=Didiskualifikasi',
  `jml_pelanggaran` int(11) DEFAULT 0,
  `alasan_blokir` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_nilai`
--

INSERT INTO `tbl_nilai` (`id`, `id_jadwal`, `id_siswa`, `status`, `waktu_mulai`, `waktu_selesai`, `nilai_pg`, `nilai_esai`, `nilai_sementara`, `jml_benar`, `jml_salah`, `list_jawaban`, `list_ragu_ragu`, `is_locked`, `extra_time`, `ip_address`, `user_agent`, `is_blocked`, `jml_pelanggaran`, `alasan_blokir`) VALUES
(1, 5, 688, 'SEDANG MENGERJAKAN', '2026-01-23 15:58:30', '2026-01-23 18:58:30', 0, 0, 0, 0, 0, NULL, NULL, 0, 0, '192.168.1.12', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36', 0, 0, NULL),
(2, 7, 688, 'SEDANG MENGERJAKAN', '2026-01-23 20:31:46', '2026-01-23 22:31:46', 0, 0, 0, 0, 0, NULL, NULL, 0, 0, '192.168.1.12', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36', 0, 0, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_opsi_soal`
--

CREATE TABLE `tbl_opsi_soal` (
  `id` int(11) NOT NULL,
  `id_soal` int(11) NOT NULL,
  `kode_opsi` varchar(50) DEFAULT NULL,
  `teks_opsi` longtext NOT NULL,
  `is_benar` tinyint(1) DEFAULT 0,
  `pasangan_uuid` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_opsi_soal`
--

INSERT INTO `tbl_opsi_soal` (`id`, `id_soal`, `kode_opsi`, `teks_opsi`, `is_benar`, `pasangan_uuid`, `created_at`) VALUES
(6, 2, 'A', 'hxdfh', 1, NULL, '2026-01-19 00:04:38'),
(7, 2, 'B', 'fdhfh', 0, NULL, '2026-01-19 00:04:38'),
(8, 2, 'C', 'dfh', 0, NULL, '2026-01-19 00:04:38'),
(9, 2, 'D', 'fh', 0, NULL, '2026-01-19 00:04:38'),
(10, 2, 'E', 'dfh', 0, NULL, '2026-01-19 00:04:38'),
(77, 21, 'A', 'Soeharto', 0, NULL, '2026-01-19 17:10:34'),
(78, 21, 'B', 'B.J. Habibie', 0, NULL, '2026-01-19 17:10:34'),
(79, 21, 'C', 'Ir. Soekarno', 1, NULL, '2026-01-19 17:10:34'),
(80, 21, 'D', 'Jokowi', 0, NULL, '2026-01-19 17:10:34'),
(81, 21, 'E', 'Megawati', 0, NULL, '2026-01-19 17:10:34'),
(82, 22, 'A', 'Ayam', 0, NULL, '2026-01-19 17:10:35'),
(83, 22, 'B', 'Apel', 1, NULL, '2026-01-19 17:10:35'),
(84, 22, 'C', 'Kucing', 0, NULL, '2026-01-19 17:10:35'),
(85, 22, 'D', 'Mangga', 1, NULL, '2026-01-19 17:10:35'),
(86, 22, 'E', 'Besi', 0, NULL, '2026-01-19 17:10:35'),
(87, 23, 'Jawa Barat', 'Bandung', 1, NULL, '2026-01-19 17:10:35'),
(88, 23, 'Jawa Timur', 'Surabaya', 1, NULL, '2026-01-19 17:10:35'),
(89, 23, 'Jawa Tengah', 'Semarang', 1, NULL, '2026-01-19 17:10:35'),
(90, 24, 'KUNCI', '7', 1, NULL, '2026-01-19 17:10:35'),
(115, 30, 'A', 'Ayam', 0, NULL, '2026-01-23 22:49:58'),
(116, 30, 'B', 'Apel', 1, NULL, '2026-01-23 22:49:58'),
(117, 30, 'C', 'Kucing', 0, NULL, '2026-01-23 22:49:58'),
(118, 30, 'D', 'Mangga', 1, NULL, '2026-01-23 22:49:58'),
(119, 30, 'E', 'Besi', 0, NULL, '2026-01-23 22:49:58'),
(120, 31, 'Jawa Barat', 'Bandung', 1, NULL, '2026-01-23 22:49:58'),
(121, 31, 'Jawa Timur', 'Surabaya', 1, NULL, '2026-01-23 22:49:58'),
(122, 31, 'Jawa Tengah', 'Semarang', 1, NULL, '2026-01-23 22:49:58'),
(123, 32, 'KUNCI', '7', 1, NULL, '2026-01-23 22:49:58'),
(124, 29, 'A', 'Soeharto', 0, NULL, '2026-01-23 23:19:09'),
(125, 29, 'B', 'B.J. Habibie', 0, NULL, '2026-01-23 23:19:09'),
(126, 29, 'C', 'Ir. Soekarno', 1, NULL, '2026-01-23 23:19:09'),
(127, 29, 'D', 'Jokowi', 0, NULL, '2026-01-23 23:19:09'),
(128, 29, 'E', 'Megawati', 0, NULL, '2026-01-23 23:19:09'),
(129, 33, 'A', '1', 0, NULL, '2026-01-27 18:31:26'),
(130, 33, 'B', '2', 0, NULL, '2026-01-27 18:31:26'),
(131, 33, 'C', '3', 0, NULL, '2026-01-27 18:31:26'),
(132, 33, 'D', '4', 0, NULL, '2026-01-27 18:31:26'),
(133, 33, 'E', '5', 1, NULL, '2026-01-27 18:31:26'),
(134, 34, 'A', 'ba', 1, NULL, '2026-01-27 18:45:06'),
(135, 34, 'B', 'ytfht', 0, NULL, '2026-01-27 18:45:06'),
(136, 34, 'C', 'bhb', 0, NULL, '2026-01-27 18:45:06'),
(137, 34, 'D', 'mhbjh', 0, NULL, '2026-01-27 18:45:06'),
(138, 34, 'E', 'nvhj', 0, NULL, '2026-01-27 18:45:06'),
(139, 35, 'A', 'ini jawaban', 1, NULL, '2026-01-27 18:47:09'),
(140, 35, 'B', 'jhg', 0, NULL, '2026-01-27 18:47:09'),
(141, 35, 'C', 'hgh', 0, NULL, '2026-01-27 18:47:09'),
(142, 35, 'D', '1g', 0, NULL, '2026-01-27 18:47:09'),
(143, 35, 'E', 'gj', 0, NULL, '2026-01-27 18:47:09');

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
-- Struktur dari tabel `tbl_pengeluaran`
--

CREATE TABLE `tbl_pengeluaran` (
  `id` int(11) NOT NULL,
  `id_divisi` int(11) NOT NULL,
  `id_jenis` int(11) NOT NULL,
  `judul_pengeluaran` varchar(255) NOT NULL,
  `nominal` double NOT NULL,
  `tanggal` date DEFAULT curdate(),
  `keterangan` text DEFAULT NULL,
  `petugas_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_pengeluaran`
--

INSERT INTO `tbl_pengeluaran` (`id`, `id_divisi`, `id_jenis`, `judul_pengeluaran`, `nominal`, `tanggal`, `keterangan`, `petugas_id`, `created_at`) VALUES
(1, 4, 6, 'Acces Point 10 pcs', 5000000, '2026-01-27', 'untuk ujian', 1, '2026-01-27 21:43:39'),
(2, 2, 3, 'Transport Rapat Bimtek', 50000, '2026-01-28', 'Ke subang', 6, '2026-01-28 14:48:24');

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
-- Struktur dari tabel `tbl_pos_bayar`
--

CREATE TABLE `tbl_pos_bayar` (
  `id` int(11) NOT NULL,
  `nama_pos` varchar(100) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_pos_bayar`
--

INSERT INTO `tbl_pos_bayar` (`id`, `nama_pos`, `keterangan`, `created_at`, `updated_at`) VALUES
(3, 'Daftar Ulang 1', 'Daftar Ulang Siswa Baru', '2026-01-28 13:46:32', '2026-01-28 13:46:50'),
(4, 'Daftar Ulang 2', 'Daftar Ulang Kelas 11', '2026-01-31 11:22:49', '2026-01-31 11:22:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_presensi`
--

CREATE TABLE `tbl_presensi` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role` enum('siswa','guru') NOT NULL,
  `tanggal` date NOT NULL,
  `jam_masuk` time DEFAULT NULL,
  `jam_pulang` time DEFAULT NULL,
  `latitude` varchar(100) DEFAULT NULL,
  `longitude` varchar(100) DEFAULT NULL,
  `jarak_meter` int(11) DEFAULT NULL,
  `status_kehadiran` enum('Tepat Waktu','Terlambat','Izin','Sakit','Alpha') DEFAULT 'Alpha',
  `metode` enum('QR','RFID','Manual','Online') DEFAULT 'Online',
  `keterangan` text DEFAULT NULL,
  `status_verifikasi` enum('Pending','Disetujui','Ditolak') DEFAULT 'Disetujui',
  `bukti_izin` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_presensi`
--

INSERT INTO `tbl_presensi` (`id`, `user_id`, `role`, `tanggal`, `jam_masuk`, `jam_pulang`, `latitude`, `longitude`, `jarak_meter`, `status_kehadiran`, `metode`, `keterangan`, `status_verifikasi`, `bukti_izin`, `created_at`, `updated_at`) VALUES
(1, 2, 'siswa', '2026-01-28', '16:26:48', '16:55:49', NULL, NULL, NULL, 'Terlambat', 'QR', NULL, 'Disetujui', NULL, '2026-01-28 16:26:48', '2026-01-28 16:55:49'),
(2, 3, 'siswa', '2026-01-28', '16:30:41', NULL, NULL, NULL, NULL, 'Terlambat', 'QR', NULL, 'Disetujui', NULL, '2026-01-28 16:30:41', '2026-01-28 16:30:41'),
(3, 1, 'siswa', '2026-01-28', '16:30:55', NULL, NULL, NULL, NULL, 'Terlambat', 'QR', NULL, 'Disetujui', NULL, '2026-01-28 16:30:55', '2026-01-28 16:30:55'),
(4, 4, 'siswa', '2026-01-28', '16:31:03', NULL, NULL, NULL, NULL, 'Terlambat', 'QR', NULL, 'Disetujui', NULL, '2026-01-28 16:31:03', '2026-01-28 16:31:03'),
(5, 6, 'siswa', '2026-01-28', '17:47:36', NULL, NULL, NULL, NULL, 'Sakit', 'Manual', 'sakit', 'Disetujui', NULL, '2026-01-28 17:47:36', '2026-01-28 17:47:36'),
(6, 3, 'guru', '2026-01-28', '18:41:29', '18:41:37', NULL, NULL, NULL, 'Terlambat', 'Manual', NULL, 'Disetujui', NULL, '2026-01-28 18:41:29', '2026-01-28 18:41:37'),
(7, 3, 'guru', '2026-01-29', NULL, NULL, NULL, NULL, NULL, '', '', 'Monitoring PKL', 'Pending', NULL, '2026-01-28 19:20:58', '2026-01-28 19:20:58'),
(8, 2, 'siswa', '2026-01-29', NULL, NULL, NULL, NULL, NULL, 'Izin', '', 'nikahan', 'Disetujui', '1769603108_6929da12fc78562881a5.jpg', '2026-01-28 19:25:08', '2026-01-28 19:33:56'),
(9, 3, 'guru', '2026-01-30', NULL, NULL, NULL, NULL, NULL, 'Sakit', '', 'Monitoring PKL', 'Disetujui', NULL, '2026-01-28 19:32:26', '2026-01-28 19:34:03'),
(10, 2, 'siswa', '2026-01-30', '10:23:33', '10:23:56', NULL, NULL, NULL, 'Terlambat', '', NULL, 'Disetujui', NULL, '2026-01-30 10:23:33', '2026-01-30 10:23:56'),
(11, 5, 'guru', '2026-01-30', '10:41:06', NULL, '-6.6764928', '107.683212', 28, 'Terlambat', '', NULL, '', 'absen_20260130_104106_5.jpg', '2026-01-30 10:41:06', '2026-01-30 10:41:06'),
(12, 3, 'siswa', '2026-01-30', '10:57:53', NULL, '-6.6765112', '107.6832203', 30, 'Terlambat', 'Online', NULL, 'Disetujui', 'siswa_20260130_105753_3.jpg', '2026-01-30 10:57:53', '2026-01-30 10:57:53'),
(13, 107, 'siswa', '2026-01-30', NULL, '19:59:36', NULL, NULL, NULL, 'Sakit', 'Online', 'well', 'Ditolak', '1769776107_f3cc3605fc72edb1beeb.jpeg', '2026-01-30 19:28:27', '2026-01-30 19:59:36');

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
(1, 'LAB KOMPUTER 1'),
(4, 'LAB KOMPUTER 2'),
(5, 'Ruang 3');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_ruang_peserta`
--

CREATE TABLE `tbl_ruang_peserta` (
  `id` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `id_ruangan` int(11) NOT NULL,
  `id_sesi` int(11) NOT NULL,
  `no_komputer` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_ruang_peserta`
--

INSERT INTO `tbl_ruang_peserta` (`id`, `id_siswa`, `id_ruangan`, `id_sesi`, `no_komputer`) VALUES
(1, 1, 1, 1, 'PC-01'),
(2, 2, 1, 1, 'PC-02'),
(3, 3, 1, 1, 'PC-03'),
(4, 10, 1, 1, 'PC-04'),
(5, 11, 1, 1, 'PC-05'),
(6, 4, 4, 1, 'PC-01'),
(7, 5, 4, 1, 'PC-02'),
(8, 6, 4, 1, 'PC-03'),
(9, 7, 4, 1, 'PC-04'),
(10, 8, 4, 1, 'PC-05'),
(12, 9, 5, 1, 'PC-01'),
(13, 12, 5, 1, 'PC-02'),
(14, 13, 5, 1, 'PC-03'),
(15, 14, 5, 1, 'PC-04'),
(16, 15, 5, 1, 'PC-05'),
(17, 16, 5, 1, 'PC-06'),
(18, 17, 5, 1, 'PC-07'),
(19, 107, 5, 1, 'PC-08'),
(20, 327, 5, 1, 'PC-09');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_sekolah`
--

CREATE TABLE `tbl_sekolah` (
  `id` int(11) NOT NULL,
  `npsn` varchar(10) NOT NULL,
  `nama_sekolah` varchar(100) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `desa_kelurahan` varchar(50) DEFAULT NULL,
  `kecamatan` varchar(50) DEFAULT NULL,
  `kabupaten` varchar(50) DEFAULT NULL,
  `provinsi` varchar(50) DEFAULT NULL,
  `kode_pos` varchar(10) DEFAULT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `website` varchar(50) DEFAULT NULL,
  `kepala_sekolah` varchar(100) DEFAULT NULL,
  `nip_kepsek` varchar(30) DEFAULT NULL,
  `dapodik_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_sekolah`
--

INSERT INTO `tbl_sekolah` (`id`, `npsn`, `nama_sekolah`, `alamat`, `desa_kelurahan`, `kecamatan`, `kabupaten`, `provinsi`, `kode_pos`, `no_telp`, `email`, `website`, `kepala_sekolah`, `nip_kepsek`, `dapodik_id`) VALUES
(1, '20217025', 'SMKS RIYADHUL JANNAH JALANCAGAK', 'JLN. RAYA JALANCAGAK', 'Jalancagak', 'Kec. Jalancagak', 'Kab. Subang', 'Prov. Jawa Barat', '41281', '0260471707', 'smkriyadhuljannah.jalancagak@gmail.com', 'http://www.riyadhul-jannah.org', 'Rijal Agustian', '-', '3252c47f-0580-40b3-9ab4-b5de0b5d0daf');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_sesi`
--

CREATE TABLE `tbl_sesi` (
  `id` int(11) NOT NULL,
  `nama_sesi` varchar(50) NOT NULL,
  `waktu_mulai` time NOT NULL,
  `waktu_selesai` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_sesi`
--

INSERT INTO `tbl_sesi` (`id`, `nama_sesi`, `waktu_mulai`, `waktu_selesai`) VALUES
(1, 'Sesi 1', '07:30:00', '09:30:00'),
(2, 'Sesi 2', '10:00:00', '12:00:00'),
(3, 'Sesi 3', '13:00:00', '15:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_siswa`
--

CREATE TABLE `tbl_siswa` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `dapodik_id` varchar(50) DEFAULT NULL,
  `rombel_id_dapodik` varchar(50) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `kelas_id` int(11) DEFAULT NULL,
  `jurusan_id` int(11) DEFAULT NULL,
  `nisn` varchar(20) NOT NULL,
  `nis` varchar(20) DEFAULT NULL,
  `rfid_uid` varchar(50) DEFAULT NULL,
  `qr_code` varchar(100) DEFAULT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `jk` enum('L','P') DEFAULT 'L',
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
  `updated_at` datetime DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `agama_id` int(2) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(20) DEFAULT 'siswa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_siswa`
--

INSERT INTO `tbl_siswa` (`id`, `id_user`, `dapodik_id`, `rombel_id_dapodik`, `user_id`, `kelas_id`, `jurusan_id`, `nisn`, `nis`, `rfid_uid`, `qr_code`, `nama_lengkap`, `jk`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `agama`, `alamat`, `no_hp_siswa`, `email_siswa`, `nama_ayah`, `nama_ibu`, `nama_wali`, `no_hp_ortu`, `pekerjaan_ayah`, `pekerjaan_ibu`, `status_siswa`, `foto`, `created_at`, `updated_at`, `nik`, `tgl_lahir`, `agama_id`, `password`, `role`) VALUES
(1, NULL, NULL, NULL, 687, 1, 1, '0054321987', '2023001', NULL, NULL, 'AHMAD SISWA TELADAN', 'L', 'L', 'Subang', '2008-05-20', NULL, '', '85155232366', '', 'Budi Ayah', 'Siti Ibu', NULL, '85155232366', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(2, NULL, NULL, NULL, 688, 1, 1, '81234001', '24001', '', '', 'ADITYA PRATAMA', 'L', 'L', 'Bandung', '0000-00-00', NULL, '', '85155232366', 'riki.wahyudin151@gmail.com', 'Hartono', 'Sri Wahyuni', NULL, '85155232366', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(3, NULL, NULL, NULL, 689, 1, 1, '81234002', '24002', NULL, NULL, 'AHMAD FAUZI', 'L', 'L', 'Jakarta', '0000-00-00', NULL, '', '85155232366', '', 'Budi Santoso', 'Siti Aminah', NULL, '85155232366', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(4, NULL, NULL, NULL, 690, 1, 2, '81234003', '24003', NULL, NULL, 'AISYAH PUTRI', 'L', 'P', 'Surabaya', '0000-00-00', NULL, '', '85155232366', '', 'Joko Susilo', 'Dewi Sartika', NULL, '85155232366', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(5, NULL, NULL, NULL, 691, 1, 2, '81234004', '24004', NULL, NULL, 'Aldi Mahendra', 'L', 'L', 'Medan', '0000-00-00', NULL, NULL, '8123456004', NULL, 'Bambang', 'Rina Wati', NULL, '8190000004', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(6, NULL, NULL, NULL, 692, 2, 1, '81234005', '24005', NULL, NULL, 'Amelia Sari', 'L', 'P', 'Yogyakarta', '0000-00-00', NULL, NULL, '8123456005', NULL, 'Agus Salim', 'Nurhayati', NULL, '8190000005', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(7, NULL, NULL, NULL, 693, 2, 1, '81234006', '24006', NULL, NULL, 'Andi Saputra', 'L', 'L', 'Semarang', '0000-00-00', NULL, NULL, '8123456006', NULL, 'Hendra', 'Yulia', NULL, '8190000006', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(8, NULL, NULL, NULL, 694, 2, 1, '81234007', '24007', NULL, NULL, 'Annisa Rahma', 'L', 'P', 'Solo', '0000-00-00', NULL, NULL, '8123456007', NULL, 'Eko Prasetyo', 'Lina', NULL, '8190000007', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(9, NULL, NULL, NULL, 695, 2, 2, '81234008', '24008', NULL, NULL, 'Arif Hidayat', 'L', 'L', 'Malang', '0000-00-00', NULL, NULL, '8123456008', NULL, 'Fajar', 'Ratna', NULL, '8190000008', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(10, NULL, NULL, NULL, 696, 3, 1, '81234009', '24009', NULL, NULL, 'Ayu Lestari', 'L', 'P', 'Denpasar', '0000-00-00', NULL, NULL, '8123456009', NULL, 'Gunawan', 'Sari', NULL, '8190000009', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(11, NULL, NULL, NULL, 697, 3, 1, '81234010', '24010', NULL, NULL, 'Bagas Kara', 'L', 'L', 'Makassar', '0000-00-00', NULL, NULL, '8123456010', NULL, 'Heru', 'Tini', NULL, '8190000010', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(12, NULL, NULL, NULL, 698, 3, 2, '81234011', '24011', NULL, NULL, 'Bayu Nugraha', 'L', 'L', 'Palembang', '0000-00-00', NULL, NULL, '8123456011', NULL, 'Indra', 'Wulan', NULL, '8190000011', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(13, NULL, NULL, NULL, 699, 3, 2, '81234012', '24012', NULL, NULL, 'Bunga Citra', 'L', 'P', 'Padang', '0000-00-00', NULL, NULL, '8123456012', NULL, 'Jamal', 'Ningsih', NULL, '8190000012', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(14, NULL, NULL, NULL, 700, 1, 1, '81234013', '24013', NULL, NULL, 'Cahya Ramadhan', 'L', 'L', 'Bekasi', '0000-00-00', NULL, NULL, '8123456013', NULL, 'Kurniawan', 'Endang', NULL, '8190000013', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(15, NULL, NULL, NULL, 701, 1, 1, '81234014', '24014', NULL, NULL, 'Cantika Dewi', 'L', 'P', 'Bogor', '0000-00-00', NULL, NULL, '8123456014', NULL, 'Lukman', 'Fitri', NULL, '8190000014', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(16, NULL, NULL, NULL, 702, 1, 2, '81234015', '24015', NULL, NULL, 'Daffa Ardiansyah', 'L', 'L', 'Depok', '0000-00-00', NULL, NULL, '8123456015', NULL, 'Mulyono', 'Susi', NULL, '8190000015', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(17, NULL, NULL, NULL, 703, 1, 2, '81234016', '24016', NULL, NULL, 'Dani Firmansyah', 'L', 'L', 'Tangerang', '0000-00-00', NULL, NULL, '8123456016', NULL, 'Nurdin', 'Yanti', NULL, '8190000016', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(18, NULL, NULL, NULL, 704, 2, 1, '81234017', '24017', NULL, NULL, 'Dea Ananda', 'L', 'P', 'Serang', '0000-00-00', NULL, NULL, '8123456017', NULL, 'Oki', 'Maya', NULL, '8190000017', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(19, NULL, NULL, NULL, 705, 2, 1, '81234018', '24018', NULL, NULL, 'Dedi Kurniawan', 'L', 'L', 'Cirebon', '0000-00-00', NULL, NULL, '8123456018', NULL, 'Purnomo', 'Dian', NULL, '8190000018', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(20, NULL, NULL, NULL, 706, 2, 1, '81234019', '24019', NULL, NULL, 'Dewi Sartika', 'L', 'P', 'Tasikmalaya', '0000-00-00', NULL, NULL, '8123456019', NULL, 'Qomar', 'Eka', NULL, '8190000019', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(21, NULL, NULL, NULL, 707, 2, 2, '81234020', '24020', NULL, NULL, 'Dimas Anggara', 'L', 'L', 'Garut', '0000-00-00', NULL, NULL, '8123456020', NULL, 'Rudi', 'Indah', NULL, '8190000020', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(22, NULL, NULL, NULL, 708, 3, 1, '81234021', '24021', NULL, NULL, 'Dini Aminarti', 'L', 'P', 'Sukabumi', '0000-00-00', NULL, NULL, '8123456021', NULL, 'Soleh', 'Tuti', NULL, '8190000021', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(23, NULL, NULL, NULL, 709, 3, 1, '81234022', '24022', NULL, NULL, 'Doni Tata', 'L', 'L', 'Cianjur', '0000-00-00', NULL, NULL, '8123456022', NULL, 'Taufik', 'Murni', NULL, '8190000022', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(24, NULL, NULL, NULL, 710, 3, 2, '81234023', '24023', NULL, NULL, 'Eka Saputra', 'L', 'L', 'Subang', '0000-00-00', NULL, NULL, '8123456023', NULL, 'Usman', 'Ani', NULL, '8190000023', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(25, NULL, NULL, NULL, 711, 3, 2, '81234024', '24024', NULL, NULL, 'Eko Patrio', 'L', 'L', 'Purwakarta', '0000-00-00', NULL, NULL, '8123456024', NULL, 'Vicky', 'Ratih', NULL, '8190000024', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(26, NULL, NULL, NULL, 712, 1, 1, '81234025', '24025', NULL, NULL, 'Elly Sugigi', 'L', 'P', 'Karawang', '0000-00-00', NULL, NULL, '8123456025', NULL, 'Wahyu', 'Desi', NULL, '8190000025', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(27, NULL, NULL, NULL, 713, 1, 1, '81234026', '24026', NULL, NULL, 'Fajar Sadboy', 'L', 'L', 'Indramayu', '0000-00-00', NULL, NULL, '8123456026', NULL, 'Xaverius', 'Clara', NULL, '8190000026', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(28, NULL, NULL, NULL, 714, 1, 2, '81234027', '24027', NULL, NULL, 'Fani Rose', 'L', 'P', 'Majalengka', '0000-00-00', NULL, NULL, '8123456027', NULL, 'Yanto', 'Tari', NULL, '8190000027', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(29, NULL, NULL, NULL, 715, 1, 2, '81234028', '24028', NULL, NULL, 'Farhan Ali', 'L', 'L', 'Kuningan', '0000-00-00', NULL, NULL, '8123456028', NULL, 'Zainal', 'Umi', NULL, '8190000028', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(30, NULL, NULL, NULL, 716, 2, 1, '81234029', '24029', NULL, NULL, 'Fifi Lety', 'L', 'P', 'Sumedang', '0000-00-00', NULL, NULL, '8123456029', NULL, 'Adam', 'Eva', NULL, '8190000029', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(31, NULL, NULL, NULL, 717, 2, 1, '81234030', '24030', NULL, NULL, 'Gilang Dirga', 'L', 'L', 'Ciamis', '0000-00-00', NULL, NULL, '8123456030', NULL, 'Beni', 'Citra', NULL, '8190000030', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(32, NULL, NULL, NULL, 718, 2, 1, '81234031', '24031', NULL, NULL, 'Gita Gutawa', 'L', 'P', 'Banjar', '0000-00-00', NULL, NULL, '8123456031', NULL, 'Candra', 'Dina', NULL, '8190000031', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(33, NULL, NULL, NULL, 719, 2, 2, '81234032', '24032', NULL, NULL, 'Guntur Bumi', 'L', 'L', 'Pangandaran', '0000-00-00', NULL, NULL, '8123456032', NULL, 'Dedi', 'Erna', NULL, '8190000032', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(34, NULL, NULL, NULL, 720, 3, 1, '81234033', '24033', NULL, NULL, 'Hana Hanifah', 'L', 'P', 'Cimahi', '0000-00-00', NULL, NULL, '8123456033', NULL, 'Erwin', 'Fani', NULL, '8190000033', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(35, NULL, NULL, NULL, 721, 3, 1, '81234034', '24034', NULL, NULL, 'Hari Panca', 'L', 'L', 'Bandung Barat', '0000-00-00', NULL, NULL, '8123456034', NULL, 'Faris', 'Gina', NULL, '8190000034', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(36, NULL, NULL, NULL, 722, 3, 2, '81234035', '24035', NULL, NULL, 'Hesti Purwadinata', 'L', 'P', 'Jakarta Selatan', '0000-00-00', NULL, NULL, '8123456035', NULL, 'Galih', 'Hani', NULL, '8190000035', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(37, NULL, NULL, NULL, 723, 3, 2, '81234036', '24036', NULL, NULL, 'Ian Kasela', 'L', 'L', 'Jakarta Timur', '0000-00-00', NULL, NULL, '8123456036', NULL, 'Hadi', 'Ika', NULL, '8190000036', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(38, NULL, NULL, NULL, 724, 1, 1, '81234037', '24037', NULL, NULL, 'Iis Dahlia', 'L', 'P', 'Jakarta Barat', '0000-00-00', NULL, NULL, '8123456037', NULL, 'Irwan', 'Jamilah', NULL, '8190000037', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(39, NULL, NULL, NULL, 725, 1, 1, '81234038', '24038', NULL, NULL, 'Indra Bekti', 'L', 'L', 'Jakarta Pusat', '0000-00-00', NULL, NULL, '8123456038', NULL, 'Jaya', 'Kiki', NULL, '8190000038', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(40, NULL, NULL, NULL, 726, 1, 2, '81234039', '24039', NULL, NULL, 'Indah Permatasari', 'L', 'P', 'Jakarta Utara', '0000-00-00', NULL, NULL, '8123456039', NULL, 'Koko', 'Lilis', NULL, '8190000039', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(41, NULL, NULL, NULL, 727, 1, 2, '81234040', '24040', NULL, NULL, 'Jaka Sembung', 'L', 'L', 'Kepulauan Seribu', '0000-00-00', NULL, NULL, '8123456040', NULL, 'Lutfi', 'Mimi', NULL, '8190000040', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(42, NULL, NULL, NULL, 728, 2, 1, '81234041', '24041', NULL, NULL, 'Juwita Bahar', 'L', 'P', 'Banda Aceh', '0000-00-00', NULL, NULL, '8123456041', NULL, 'Mamat', 'Nana', NULL, '8190000041', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(43, NULL, NULL, NULL, 729, 2, 1, '81234042', '24042', NULL, NULL, 'Kevin Julio', 'L', 'L', 'Medan', '0000-00-00', NULL, NULL, '8123456042', NULL, 'Nanang', 'Oki', NULL, '8190000042', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(44, NULL, NULL, NULL, 730, 2, 1, '81234043', '24043', NULL, NULL, 'Kiki Amalia', 'L', 'P', 'Padang', '0000-00-00', NULL, NULL, '8123456043', NULL, 'Otoy', 'Puput', NULL, '8190000043', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(45, NULL, NULL, NULL, 731, 2, 2, '81234044', '24044', NULL, NULL, 'Lukman Sardi', 'L', 'L', 'Pekanbaru', '0000-00-00', NULL, NULL, '8123456044', NULL, 'Pepen', 'Qori', NULL, '8190000044', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(46, NULL, NULL, NULL, 732, 3, 1, '81234045', '24045', NULL, NULL, 'Luna Maya', 'L', 'P', 'Jambi', '0000-00-00', NULL, NULL, '8123456045', NULL, 'Qodir', 'Rere', NULL, '8190000045', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(47, NULL, NULL, NULL, 733, 3, 1, '81234046', '24046', NULL, NULL, 'Marcel Chandrawinata', 'L', 'L', 'Palembang', '0000-00-00', NULL, NULL, '8123456046', NULL, 'Ridwan', 'Sasa', NULL, '8190000046', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(48, NULL, NULL, NULL, 734, 3, 2, '81234047', '24047', NULL, NULL, 'Marshanda', 'L', 'P', 'Bengkulu', '0000-00-00', NULL, NULL, '8123456047', NULL, 'Sandi', 'Tata', NULL, '8190000047', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(49, NULL, NULL, NULL, 735, 3, 2, '81234048', '24048', NULL, NULL, 'Nabila Syakieb', 'L', 'P', 'Bandar Lampung', '0000-00-00', NULL, NULL, '8123456048', NULL, 'Tono', 'Uut', NULL, '8190000048', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(50, NULL, NULL, NULL, 736, 1, 1, '81234049', '24049', NULL, NULL, 'Raffi Ahmad', 'L', 'L', 'Pangkal Pinang', '0000-00-00', NULL, NULL, '8123456049', NULL, 'Udin', 'Vivi', NULL, '8190000049', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(51, NULL, NULL, NULL, 737, 1, 1, '81234050', '24050', NULL, NULL, 'Raisa Andriana', 'L', 'P', 'Tanjung Pinang', '0000-00-00', NULL, NULL, '8123456050', NULL, 'Vian', 'Winda', NULL, '8190000050', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, 'siswa'),
(52, 792, '21444f39-6016-4b30-b6b8-00c239b47853', '1d260111-0d05-4b10-a525-1eeeef43fe6b', NULL, 45, NULL, '0105197771', '252614149', NULL, NULL, 'REGISKA ALMAIRA OKTAFIANA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'TAJUDIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213265110100001', '2010-10-11', 1, '$2y$10$it03iQHKUCY3DQVieXbJ6u26kYSqCxr4uFZ7OZYVFTzsYIEM9.0ce', 'siswa'),
(53, 793, 'ab0abbe8-7136-45b0-914e-00f80d1d1398', '005b0659-8a5e-4160-8830-9ddb091fbf00', NULL, 44, NULL, '3106338266', '252614128', NULL, NULL, 'MOHAMAD RIZKY FEBRIYANTO', 'L', 'L', 'KARAWANG', NULL, NULL, '-', NULL, NULL, 'SUPRIYADI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3215050602100002', '2010-03-05', 1, '$2y$10$5Cr.Z8cbmvTq7fy14A0QiOR/X5ZsTZpqUi75KxRI3JjTgLrEHvRNe', 'siswa'),
(54, 794, '22161c88-fa8b-44b6-890b-02d4eb42938e', 'f0dd79d4-b8fa-4815-b531-f65b873990ea', NULL, 43, NULL, '0093171796', '252614071', NULL, NULL, 'NAYLA OKTOVIANI ALMIRA PUTRI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'JENAL', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213195710090002', '2009-10-17', 1, '$2y$10$OEZ5L4ZTRF9ir9G08NsELu9RRTIB5O.BElnYdOl8eInV5wrPmLMei', 'siswa'),
(55, 795, 'b911d7cd-291b-4d07-b349-02e06d68df06', 'e94587e3-0ece-43f2-b670-502c10beffe3', NULL, 50, NULL, '0098543075', '24250147', NULL, NULL, 'RIZKI ILHAM SATARI', 'L', 'L', 'BEKASI', NULL, NULL, '-', NULL, NULL, 'ASEP SATARI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3216062801090006', '2009-01-28', 1, '$2y$10$ulsz15c1uqNiO.U1Qn8/1u.Medtlss6MWS/NyZg/WhG/qsn5hlZrW', 'siswa'),
(56, 796, 'ec35a2e0-3d7c-11e5-a939-0315e7b941d6', 'f0dd79d4-b8fa-4815-b531-f65b873990ea', NULL, 43, NULL, '0092585873', '252614083', NULL, NULL, 'UTARI WULANDARI', 'P', 'L', 'Jakarta', NULL, NULL, '-', NULL, NULL, 'WASITO', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3175025512090004', '2009-12-15', 1, '$2y$10$IPOFZw25oPPueYuvqnuKZ.PJmbnVtxNUXYtNzaU.Fwy8tTkut9Z8.', 'siswa'),
(57, 797, '61f4c000-3a54-11e5-8aaa-03215a7d00d1', '9cce8b82-f77c-4be6-be06-449e5da19e86', NULL, 48, NULL, '0098370552', '24250035', NULL, NULL, 'EVA SITI BADRIAH', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ROSID', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213295302090001', '2009-02-13', 1, '$2y$10$rV0SFUfSu234dNNo/slMmuy7Wz5ydQyOJ5Ns1D8koY/bP4XzIrlou', 'siswa'),
(58, 798, '5267c970-380c-11e4-a7dd-03352399db8c', '964b7cb7-a338-4574-814c-f5954ed784eb', NULL, 55, NULL, '0078422539', '23240072', NULL, NULL, 'REVALINA EGISNIA', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Ade Suryana', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296008070001', '2007-08-20', 1, '$2y$10$AZ.tNuvLqp5pdv2DLpyu8OSETm1zkrSSfzNaaXoyyeoeHnHXfyE2S', 'siswa'),
(59, 799, '37597fd0-3f0e-11e4-9455-036504fc5936', '6b9f4508-c1f9-46e0-b8a6-a03bacd4a708', NULL, 51, NULL, '0085314086', '23240052', NULL, NULL, 'YESA DWI PUTRI', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'SARIPUDIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213295901080011', '2008-01-19', 1, '$2y$10$ieRXXkTEsGCRSYrtuLiFPONSIDIMNW8dm8NUCnx73G8AD6.irY2GS', 'siswa'),
(60, 800, '8ccec032-8f7c-4ef5-9dc5-03a7b988779f', '005b0659-8a5e-4160-8830-9ddb091fbf00', NULL, 44, NULL, '0105643514', '252614137', NULL, NULL, 'RIKI RAHAYU', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ADE KUSNADI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213192803100001', '2010-03-28', 1, '$2y$10$miNU3bBfXKhqKxxw6F6aCenAyZaL35ogynoKimf1uYg5oraSIbq/q', 'siswa'),
(61, 801, '4e7e572b-3a6a-4941-ad87-04d99cd40fb0', '0168bb86-45b1-4a2f-8beb-b147e3bf05a7', NULL, 42, NULL, '0096762404', NULL, NULL, NULL, 'SYARIF FADILLAH', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'AGUS TAUFIK HIDAYAT', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213123007090002', '2009-07-30', 1, '$2y$10$N/wtXZRs5EWY03M8WXcbXOTmXeWE7GsiXD7wtyQWr4XTFKsJo/vny', 'siswa'),
(62, 802, 'f4c22ee0-cad0-415c-b135-0632d6aebe6a', '1d4ffd24-3a9e-4c03-afd8-a12ab295694a', NULL, 46, NULL, '0044425383', '242500', NULL, NULL, 'RIFKI RAMADANI', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, '', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213262104050001', '2005-10-21', 1, '$2y$10$KSat6ldCnWrQq5TEK8NIKOHWsuwpbujo4MyFELuOgWc9ztKSoNfiK', 'siswa'),
(63, 803, '2fd57e3e-b6c0-4ea4-9d48-070596f027ac', '1d260111-0d05-4b10-a525-1eeeef43fe6b', NULL, 45, NULL, '0108073279', '252614029', NULL, NULL, 'AFRILLIA ANGGIA SUFY', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'PANDI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296004100001', '2010-04-20', 1, '$2y$10$t2o6et/ZwZl4DdLMIxOeX.avG02kQxNnZnqipajtoIJTW2AsWmwnK', 'siswa'),
(64, 804, '0ead0afc-583f-11e5-8b7a-0732b20a7b18', '2bcef959-7a06-4631-b590-f53fb9f93bc2', NULL, 49, NULL, '0082078022', '24250128', NULL, NULL, 'SULAEMAN', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'AKO', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213290912080001', '2008-12-09', 1, '$2y$10$O9x52CBKRbR8dugYSmmh0udzRfVUJvefXy7ZcCPIsqv2Re5nfOTFi', 'siswa'),
(65, 805, '9f9acb52-39bf-11e4-ae89-07ab301e4a2a', '68edb4cb-5f7a-4dfb-b043-d5f663a2f21b', NULL, 54, NULL, '0085579351', '23240157', NULL, NULL, 'MUHAMMAD HAFIDZ ALIMUDIN', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'WAWAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213013003080001', '2008-03-30', 1, '$2y$10$qtmLHJIN/CYm67vrV/sGCumYBUcq/BK7hpMHuwUDmDfv2582bKgpS', 'siswa'),
(66, 806, '49582ac2-5305-11e5-ae8b-07d48af676c6', 'e94587e3-0ece-43f2-b670-502c10beffe3', NULL, 50, NULL, '0087231267', '24250145', NULL, NULL, 'RIYADI TRI SUTRISNA', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Suryana', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213012306090001', '2008-06-23', 1, '$2y$10$VwU9PUNFuDi0hROpadiXxutXQUPxmasv8XEY53i6SgMoIZt6ulRwK', 'siswa'),
(67, 807, '96910285-f8d2-4c12-b23e-07e3ad3169a0', '6b9f4508-c1f9-46e0-b8a6-a03bacd4a708', NULL, 51, NULL, '3077711335', '23240038', NULL, NULL, 'Meisya Nurul Latifah', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Wahyat', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213294605060001', '2008-10-06', 1, '$2y$10$CdoIM6bnKEMcHqT676YXpe/wPsboLf30PM8T.bQFriWjV5kyNW3sK', 'siswa'),
(68, 808, '8b01b6ca-4e7b-4778-a795-09aa08f93c0e', 'da90cc11-526e-48aa-b7ce-a9afbc0d8023', NULL, 41, NULL, '0108639103', '252614021', NULL, NULL, 'SAFITRI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'UNTUNG', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213126801100012', '2010-01-28', 1, '$2y$10$MHk/NWYNdBwaQWUfRWsEXOJotOgysn31zG6VCC8gjgl7QMWnOpDNW', 'siswa'),
(69, 809, '3971c8aa-2e78-11e4-bbaa-0b2fc116d426', '6b9f4508-c1f9-46e0-b8a6-a03bacd4a708', NULL, 51, NULL, '0087870269', '23240050', NULL, NULL, 'WIFA ROSLINA', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'PIPIK WIRYADI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213126303080001', '2008-03-22', 1, '$2y$10$lOvrwu69Zpd1KOdF0NWmL.6HTj9k/ObfpCAkiHLjfA2aqImwITIPq', 'siswa'),
(70, 810, '42a6597c-cab9-406d-a08f-0b8aa185ddc0', '1d260111-0d05-4b10-a525-1eeeef43fe6b', NULL, 45, NULL, '3097880430', '252614054', NULL, NULL, 'WENDI BAYU SUKMA', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'Agus supriatna', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213292208090001', '2009-08-22', 1, '$2y$10$lY5ge0UMprp4EbxfFO84SOLucynbT06oSEVilUWbFo4a/jLFp4s/C', 'siswa'),
(71, 811, 'c3bc01e2-fbd7-4108-8122-0b99612632c2', '1d260111-0d05-4b10-a525-1eeeef43fe6b', NULL, 45, NULL, '0107193081', '252614038', NULL, NULL, 'GIANT RIZKY PRATAMA', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'EDWAR GUNAWAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213291304100001', '2010-04-13', 1, '$2y$10$/8xgP4pu04wPXmCYiJjH2eORd492sxs2wYWWrNTLN3Bsd1UcfW3VC', 'siswa'),
(72, 812, '41c83d29-2350-4c05-b563-0c67a7dcd2b0', 'da90cc11-526e-48aa-b7ce-a9afbc0d8023', NULL, 41, NULL, '0104880623', '252614002', NULL, NULL, 'ANGGY NURWYDIATY', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'AKIM PERMANA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213295107100001', '2010-07-11', 1, '$2y$10$e1fuvuSEWvHUVWLRdUQFc.0W5jjgKlihw3H4Y.YpKn/O60KY0Z8YW', 'siswa'),
(73, 813, 'a1adc112-c08e-4ee1-bb35-0f1d6744f081', '2bcef959-7a06-4631-b590-f53fb9f93bc2', NULL, 49, NULL, '0096121571', '24250114', NULL, NULL, 'MAULIDA SUSILAWATI MAISYARAH', 'P', 'L', 'Bandung', NULL, NULL, '-', NULL, NULL, 'ASEP MUHAMAD SELAMET', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3273065203090003', '2009-03-12', 1, '$2y$10$EWrVHzZF8gE93BBBJ0PCwemfJU/md0VQ0L8is4PIAg9FYRYfSejkS', 'siswa'),
(74, 814, '88ad8c4e-4383-11e5-bf76-0fc0f816b9cf', '619c6aff-d40d-4ac9-905d-243b54536570', NULL, 47, NULL, '0099627101', '24250015', NULL, NULL, 'MELATI SOLEHA', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Warja', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213196904090001', '2009-04-29', 1, '$2y$10$QyIf7xIfeQQgjQt69cs2n.b2kLnJhCbgizbAJSJr/js0C.gRlrPsq', 'siswa'),
(75, 815, 'b7743f0d-d95e-4a03-b310-10ad0d625f5b', '1d260111-0d05-4b10-a525-1eeeef43fe6b', NULL, 45, NULL, '0099393697', '252614051', NULL, NULL, 'SRI HANDAYANI KOMALASARI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ANAN SAEPULOH', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213291605090022', '2009-05-16', 1, '$2y$10$NVAhhkOCxViB3SbbJiYhdeo9PW.z2VvlmjTF5smSrDkULBkNTZpJ6', 'siswa'),
(76, 816, '9ebb4cff-ce7f-4b07-95cd-10c3cbb231b7', '0168bb86-45b1-4a2f-8beb-b147e3bf05a7', NULL, 42, NULL, '0105156912', '252614100', NULL, NULL, 'KELVIN FAUJI KAMAL', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ROSMAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213291302100008', '2010-02-13', 1, '$2y$10$CyRJEOX.1GHLQt6V0j2Yz.0WsY7Xw2nKKi5sOyqXBuZBtHRvJgZlC', 'siswa'),
(77, 817, '72399355-0274-4df4-87b2-10ed2dcf1c2f', 'f0dd79d4-b8fa-4815-b531-f65b873990ea', NULL, 43, NULL, '0096039336', '252614069', NULL, NULL, 'LUTFIA HANI RAMADANI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'AGUS SURYANA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213290209090001', '2009-09-02', 1, '$2y$10$lO6R98oamCnW398HsHIB.ujhgJxNhAE76aiqqKimpfR90AhKEFLoa', 'siswa'),
(78, 818, '53d7fe08-81eb-40b1-8017-11b08be52796', 'da90cc11-526e-48aa-b7ce-a9afbc0d8023', NULL, 41, NULL, '0105767389', '252614026', NULL, NULL, 'WIKA DILA SAFITRI', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Kariyan', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213125809100012', '2010-09-18', 1, '$2y$10$aXnnuw4ONHz0TYxxp2N4ZeifbI/VhtjObOMgjTxUEBYJLG2aqm5Cq', 'siswa'),
(79, 819, 'e35ad828-4fe4-11e5-abdd-13154e6ea4bf', '9cce8b82-f77c-4be6-be06-449e5da19e86', NULL, 48, NULL, '0099620158', '24250060', NULL, NULL, 'WILDA APRILIANA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'A. SAEPUL ANWAR', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296404090001', '2009-04-24', 1, '$2y$10$7L4UcM5vlTv5VHbPbl3qwuZSI/dO7PAIGpR5Ix/xkTmOJgHqWUqnK', 'siswa'),
(80, 820, '08f62c54-4b0d-11e5-9673-13681a869c32', 'e94587e3-0ece-43f2-b670-502c10beffe3', NULL, 50, NULL, '0094779426', '24250131', NULL, NULL, 'Aprila Fitriyani', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Deni Sudeni', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213017004090002', '2009-04-30', 1, '$2y$10$80E/BqFXVMPJoJTmJO2M6eOeNMg6VNmC4mDf9Gp2YgsUlvclMw4TO', 'siswa'),
(81, 821, 'af814a7a-4623-11e5-a5bd-137305480134', '9cce8b82-f77c-4be6-be06-449e5da19e86', NULL, 48, NULL, '0087988588', '24250031', NULL, NULL, 'Aufa Zahranti', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Deri Wahyono', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296107080003', '2008-07-21', 1, '$2y$10$BH6WjGvYv7sbdUuoUZ.7RuaP6NGg2edQctcs/en5LtK9k7quAQFhe', 'siswa'),
(82, 822, '433eac58-2b90-11e4-9662-13ec2ec83da2', '20a865c3-2d47-4cb4-90a4-885b04daa92e', NULL, 52, NULL, '0081489427', '23240003', NULL, NULL, 'ANISA NUR ISLAMIAH', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'ERWIN BAHAR', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213266608080001', '2008-08-26', 1, '$2y$10$S0dKVHaNiSsARazVkEJFJeWKSsEzcr43PS4u0DkHIbTX7jbzh963.', 'siswa'),
(83, 823, 'c584495a-3627-41d4-90d6-146da299bcbf', '1d260111-0d05-4b10-a525-1eeeef43fe6b', NULL, 45, NULL, '0098909069', '252614037', NULL, NULL, 'FITRIYAH DWI ARIANI', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Satim Mulyana', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213124912090002', '2009-12-09', 1, '$2y$10$/8uTgwFFEEageEkpoiACjeNsLRQ5YLMLH2LnryyTALSYVowtlSVXK', 'siswa'),
(84, 824, '03e85fa4-4426-4950-a0b9-14b1e1f13953', '0168bb86-45b1-4a2f-8beb-b147e3bf05a7', NULL, 42, NULL, '0108298323', '252614108', NULL, NULL, 'RAZKA AULIA PUTRI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'NANANG SETIAWAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213016308100002', '2010-08-23', 1, '$2y$10$/o/f9Kn1pfqKrITFin6kcepX7237i8W0kYquJVUFQKJi3EA8w9FDe', 'siswa'),
(85, 825, '77cf7999-bae8-486a-97d7-15d2b925b202', '005b0659-8a5e-4160-8830-9ddb091fbf00', NULL, 44, NULL, '0109803842', '252614120', NULL, NULL, 'FAHRUL FAJRIAWAN', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'WAWAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213120706100007', '2010-07-07', 1, '$2y$10$aM.7j1Fkmnpnezn4Q3aujOXWDHtBkkFFpCXUBqpA.6fHuW0KWIau2', 'siswa'),
(86, 826, 'b2d04c7d-d9df-4d19-bb77-16aa6d16ff60', '005b0659-8a5e-4160-8830-9ddb091fbf00', NULL, 44, NULL, '0102278235', '252614124', NULL, NULL, 'IHSAN AKBAR', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'WAWAN SETIAWAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213011503100001', '2010-03-15', 1, '$2y$10$x5zbt/v4BwmbZXi6ivBdHOBOGD15ulOrsc5uDgiz5SyVwGpz8rAPO', 'siswa'),
(87, 827, '4caa433e-3567-11e4-835e-172a5fc676c5', '9cce8b82-f77c-4be6-be06-449e5da19e86', NULL, 48, NULL, '0073743916', '24250036', NULL, NULL, 'FADIL MAULANA', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'DADAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213291304070002', '2007-04-13', 1, '$2y$10$.bpyWDv1cY25a8Pf00nmDuDZU8u.C2fdBWy2dIb1pgJ2.IXgnjH0.', 'siswa'),
(88, 828, 'b8d6088c-2b48-11e4-9df6-176e1d47bdae', '964b7cb7-a338-4574-814c-f5954ed784eb', NULL, 55, NULL, '0078754507', '23240082', NULL, NULL, 'ZUDIT DELISTA HASANI QOLBI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'TAJUDIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213122501070005', '2007-01-25', 1, '$2y$10$Okhf6AKQbAoLTkTmrriEnuYXrNxcOYxfLeNvkTx8hHUVGfS9e2VX6', 'siswa'),
(89, 829, '1e360982-2f79-11e4-a914-17ae9e4c66d6', 'beea584c-74a1-4013-9a57-06b34248371b', NULL, 53, NULL, '0076616824', '23240101', NULL, NULL, 'RAIHAN RIZKY NASRULLAH', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'JAJANG WARSITA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213290901070001', '2007-01-09', 1, '$2y$10$015sSJ/OAPWS15C4JbhTIukNF/pvSBsciFwscNY9OY66VCbBAkcj.', 'siswa'),
(90, 830, 'e1b6c678-3def-11e5-acbc-17bf82eb3b0c', '9cce8b82-f77c-4be6-be06-449e5da19e86', NULL, 48, NULL, '0093004898', '24250042', NULL, NULL, 'NAFADILAH MUFIDA', 'P', 'L', 'Madiun', NULL, NULL, '-', NULL, NULL, 'WIWIN ROMADLON', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3519086904090001', '2009-04-29', 1, '$2y$10$u0y7Zq1jJl/SoVOR8R63TusEHTZoeTMbk4QF1Vcro/6WfwbfKweYS', 'siswa'),
(91, 831, '98d12baa-3275-11e4-bed4-17e3ceab9f68', '6b9f4508-c1f9-46e0-b8a6-a03bacd4a708', NULL, 51, NULL, '0072707009', '23240042', NULL, NULL, 'RAISA SRI RAHAYU', 'P', 'L', 'BANDUNG', NULL, NULL, '-', NULL, NULL, 'BAHRI SAPRUDIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213295209070001', '2007-09-12', 1, '$2y$10$eXY5/GIoDFLBS4./HOP19OMy9q4dfKyYCEfPD/2o/qxdJmRyxIacG', 'siswa'),
(92, 832, '2bede2df-1b6b-4c1a-b122-18d8fa55e1ff', '20a865c3-2d47-4cb4-90a4-885b04daa92e', NULL, 52, NULL, '3070645053', '23240002', NULL, NULL, 'AMELIA RAMADHANI', 'P', 'L', 'BANDUNG', NULL, NULL, '-', NULL, NULL, '', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3204124909070007', '2007-09-09', 1, '$2y$10$CzYGBxwLInbHujlVHNy.4..dgsUmigsef.zBacF64hszsW1B7A2y.', 'siswa'),
(93, 833, 'e4b26776-685e-499d-8cf5-190396fa0a26', 'da90cc11-526e-48aa-b7ce-a9afbc0d8023', NULL, 41, NULL, '0109663264', '252614024', NULL, NULL, 'SRI LASTRI HARTINI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'CECEP RAHMAT', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296302100001', '2010-02-23', 1, '$2y$10$q8ajSYft5YPcTvEBq49BA.HP3Sh6HV1b6031JbLib79tku2./sxiu', 'siswa'),
(94, 834, 'b90b5381-4f7b-490e-9dd0-199fa70cf14a', '1d260111-0d05-4b10-a525-1eeeef43fe6b', NULL, 45, NULL, '0101638254', '252614039', NULL, NULL, 'ICA APRILLIA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'SARNITA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213124904100001', '2010-04-09', 1, '$2y$10$AVD/Vywbm/aHAwhwPjVUyOc.Y89T8b4ZxdGbH3bd4QuwMNcNXPwE6', 'siswa'),
(95, 835, '4be41de4-b388-407b-91f4-1a3ee506f161', 'f0dd79d4-b8fa-4815-b531-f65b873990ea', NULL, 43, NULL, '0097414143', '252614061', NULL, NULL, 'ARFAN DZKRI FANIANSYAH', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'NANDANG SARIPUDIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213291608100001', '2010-08-16', 1, '$2y$10$3MXJ.YkL5b6CfPKfz1p7eusOG//tRkyM9F.aBKKZy3kxxgpZpDHbC', 'siswa'),
(96, 836, '42dbc264-31f3-11e4-abc9-1b1dbeec6540', '6b9f4508-c1f9-46e0-b8a6-a03bacd4a708', NULL, 51, NULL, '0081908485', '23240030', NULL, NULL, 'ICHA INDRIANI', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Dede Koswara', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213014601080002', '2008-01-06', 1, '$2y$10$moXTBH6RaBJQfpTgIQJtZOWFl2paKuNAyo72/a0exhU48ygKgD7By', 'siswa'),
(97, 837, 'ff6827e6-329e-11e4-9a45-1b328592943e', 'beea584c-74a1-4013-9a57-06b34248371b', NULL, 53, NULL, '0073608974', '23240103', NULL, NULL, 'RAYAN ALAMSYAH', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'DEDI SAMSUL SUPRIADI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213291212070011', '2007-12-12', 1, '$2y$10$vkZROM6zbxoaxWqREtcH3uiFAmvfRNzl1bMfh1Ca9QgJZaCQPGM6i', 'siswa'),
(98, 838, '45918f8e-337e-11e4-8c4e-1b3a71e58ecd', '964b7cb7-a338-4574-814c-f5954ed784eb', NULL, 55, NULL, '0078224480', '23240058', NULL, NULL, 'CHIKA HANADILA', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Juhana', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213126711070001', '2007-11-27', 1, '$2y$10$3gQC1yEk9AuYz4FmUkKGmeOKMRiPd9/tZs.eaN7yQ/8o.qTYirNmG', 'siswa'),
(99, 839, '233d26d6-3806-11e5-9c0c-1b7b3f1afbef', '1d4ffd24-3a9e-4c03-afd8-a12ab295694a', NULL, 46, NULL, '0091822555', '24250077', NULL, NULL, 'KELVIN AGUSTIAN', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Sopian Sutisna', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213010708090002', '2009-08-07', 1, '$2y$10$KzkbHYr6iOBlo2gMo6rqCuljM64.dGsRZIOAu38YgRb24ip2r7m/G', 'siswa'),
(100, 840, '09cbe5c8-5768-451e-be61-1be225412a92', 'e94587e3-0ece-43f2-b670-502c10beffe3', NULL, 50, NULL, '3095741699', '24250146', NULL, NULL, 'RIZKA MAULIDA ROHIMA', 'P', 'L', 'NGAWI', NULL, NULL, '-', NULL, NULL, '', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3521115603090001', '2009-03-16', 1, '$2y$10$sOgvvyQbRt7Mz/ryRXryGe7k82dfmiHYD3LNHjbKLkDWegtvhezk6', 'siswa'),
(101, 841, '292234ac-4232-11e5-aa1a-1bf34008d62a', '1d4ffd24-3a9e-4c03-afd8-a12ab295694a', NULL, 46, NULL, '0085102549', '24250093', NULL, NULL, 'SISKA AULIA WATI', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'KOSIM', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213294408080001', '2008-08-04', 1, '$2y$10$Z8SWMgz7UI0v9AWf8RG.y.8wjbKGW4e4XLPToQOPaZmKr9VA/MQ3K', 'siswa'),
(102, 842, '1ca0813b-2c72-4cdb-8e99-1cfb8515e3f3', '1d260111-0d05-4b10-a525-1eeeef43fe6b', NULL, 45, NULL, '0097739175', '252614032', NULL, NULL, 'AMBAR NUR FADHILLAH', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ADE WAHYA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213295510090001', '2009-10-15', 1, '$2y$10$RcsXGoUMi4rOt6Fw2PIS5.kdvvE55SbauURyYVLMh0.KefhSVrOy.', 'siswa'),
(103, 843, '35d694e9-29a7-4b62-8cde-1d1880aa1c88', 'da90cc11-526e-48aa-b7ce-a9afbc0d8023', NULL, 41, NULL, '0104138021', '252614023', NULL, NULL, 'SITI AZIZAH DEBORAH', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'EFI RACHMAT', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213295904100002', '2010-04-19', 1, '$2y$10$bIRr2yG5E2tD8vdf/NZLYOwFCX6uUnV38jV6kAyb/cn2G8hHop0Fy', 'siswa'),
(104, 844, 'ff5b0390-1c99-42cd-add9-1d1d7fa3b89d', '005b0659-8a5e-4160-8830-9ddb091fbf00', NULL, 44, NULL, '0101524656', '252614129', NULL, NULL, 'MUHAMAD IKHSAN ZULHAKIM', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'IWAN R', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213291603100001', '2010-03-16', 1, '$2y$10$dxp8gR5a675ZYpUzpl1kjewT/KpRwdjGZ659ZROABx8.A34/B3oZ2', 'siswa'),
(105, 845, 'a376ffc0-b214-40d7-8ea2-1d8e339cd69e', '20a865c3-2d47-4cb4-90a4-885b04daa92e', NULL, 52, NULL, '0088798965', '23240047', NULL, NULL, 'SHAQILA ZINGGA ARRIZQIA SUDRAJAT', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'DADAN SUDRAJAT', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3207015104080001', '2008-04-11', 1, '$2y$10$evv6vIgXDPJfrD6k/LdSr.UNDfVoC/SJjVLSJmi2Jb1ZDA3Rwedqm', 'siswa'),
(106, 846, 'e4aaf443-c37e-4062-9733-1d8e78270265', '6b9f4508-c1f9-46e0-b8a6-a03bacd4a708', NULL, 51, NULL, '0083127372', '23240039', NULL, NULL, 'MUHAMMAD FAJAR JAENUDIN', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'CECEP JAENUDIN TOHA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213291106080001', '2008-06-11', 1, '$2y$10$zTngLG15zv8A/zEH0.TsZeMz6YsFWQrHC.3M1poQjyPB7WNvtBdXO', 'siswa'),
(107, 847, '4283a710-a82c-488c-814b-1efd2500867c', '67d9edb1-f0e2-4693-992d-3e4cccd0e1a0', NULL, 40, 13, '0083244013', '23240124', '43916B2D', '43916B2D', 'MUHAMMAD IRSYAD', 'L', 'L', 'SUBANG', '2008-06-04', NULL, '-', '085280583085', '', 'ENDANG SUTARNA', '-', NULL, '', NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213290406080001', '2008-06-04', 1, '$2y$10$S/nk4hdngdC3YtYEJ/Ib1.qFAyUaV4heNqGd8rQCxUbJaTRAKrkbO', 'siswa'),
(108, 848, 'a9d4e4a6-5b77-11e5-8475-1f2b08db0562', '2bcef959-7a06-4631-b590-f53fb9f93bc2', NULL, 49, NULL, '0088479185', '24250113', NULL, NULL, 'MARISKA LESTARI DEWI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'MUJANI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213024603080001', '2008-03-06', 1, '$2y$10$a/SaH6am4LMcS1/4sHV9M.8v/V0F8lPG4IIvsPKpEYlPTTmA3bxiW', 'siswa'),
(109, 849, '1308f656-35e0-11e4-90f0-1f4fb32ce461', '6b9f4508-c1f9-46e0-b8a6-a03bacd4a708', NULL, 51, NULL, '0086088897', '23240035', NULL, NULL, 'LIANI NURLAILASARI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ATENG CAHYUDIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213295101080002', '2008-01-11', 1, '$2y$10$C5Gmhny4g4Rl8Yl/WSkLF.PJneTldkHJCgubrpCngLXJBdpHsH4NG', 'siswa'),
(110, 850, '4c723c14-29af-11e4-aab6-1f5f78e68ec7', '68edb4cb-5f7a-4dfb-b043-d5f663a2f21b', NULL, 54, NULL, '0076277240', '23240153', NULL, NULL, 'KARINA PUSPITA DEWI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'CUCU CUMINO', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296511070001', '2007-11-25', 1, '$2y$10$XdjrII6dSvsax0DEeX50p.3fTjsh42kTIXlhsw54UXgWyplsJWc5K', 'siswa'),
(111, 851, '50a45a3b-9ea6-4302-86f5-1f5fccb0e71e', 'da90cc11-526e-48aa-b7ce-a9afbc0d8023', NULL, 41, NULL, '0098323737', '252614012', NULL, NULL, 'INTAN LUTFIA ANAISHA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, '-', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213124312090003', '2009-12-03', 1, '$2y$10$P8S/SS5dwFXylzp8ZoaEO.0Qy4uF3SPktVLlx59mjV1EmNy6hTxiO', 'siswa'),
(112, 852, 'df5f7f8e-5855-11e5-9bb5-1f72d761c4b2', '619c6aff-d40d-4ac9-905d-243b54536570', NULL, 47, NULL, '0095235273', '24250006', NULL, NULL, 'DHAVA NURFAZAR', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'SUHENDA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213293010090002', '2009-10-30', 1, '$2y$10$b.VKqiLRr2Q1DPClv2Sfweh03jpQQYqUZ7quSHSafZDfzgNntFB2K', 'siswa'),
(113, 853, 'd54b38e0-f206-11e4-9342-1f8552dd5ead', 'beea584c-74a1-4013-9a57-06b34248371b', NULL, 53, NULL, '0083314236', '23240108', NULL, NULL, 'YONGKI AGUSTIO', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'DADAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213290208080002', '2008-08-02', 1, '$2y$10$1aKAzLR9CoCd1k.g85zjUOWAHhcVIqtxvuqDwUrxSJei9yS0WKrjy', 'siswa'),
(114, 854, '3c09ca72-2815-11e4-bf86-1f867d181f5e', '68edb4cb-5f7a-4dfb-b043-d5f663a2f21b', NULL, 54, NULL, '0079938780', '23240156', NULL, NULL, 'Muhamad Saepul Aripin', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Supriatna', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213231606070002', '2007-06-16', 1, '$2y$10$/MGIuUi/Dugn5nyOCTSOUeaF/OuWxqd2H8WbkCLm.5g3Y9/.0EGWW', 'siswa'),
(115, 855, '558edbda-2b36-11e4-883a-1fbbcc444ba8', '68edb4cb-5f7a-4dfb-b043-d5f663a2f21b', NULL, 54, NULL, '0087459003', '23240151', NULL, NULL, 'IIS RISMAWATI', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'SUJANA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213266506080001', '2008-06-25', 1, '$2y$10$gWOoaNswZLsHTttFrHSfDOL8eyIZIzjGLEyJTHDx7x56Z65n9YUkW', 'siswa'),
(116, 856, '229e3b06-409a-11e5-be02-1fcfc4c9df1b', '9cce8b82-f77c-4be6-be06-449e5da19e86', NULL, 48, NULL, '0099070178', '24250059', NULL, NULL, 'WIKI WINARDI', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'AHID', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213291001090011', '2009-01-10', 1, '$2y$10$zvBag1dt9pt5DJ2t1T31ZOT446HTpWpoZn5NXZ1qkMod5IHjDJFs.', 'siswa'),
(117, 857, '304b2acf-5832-4175-8c09-225f9841738b', '619c6aff-d40d-4ac9-905d-243b54536570', NULL, 47, NULL, '0084987611', '24250028', NULL, NULL, 'Siti Zainatu Jahra', 'P', 'L', 'Jakarta', NULL, NULL, '-', NULL, NULL, 'Ence Jalil', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3173044908080003', '2008-08-09', 1, '$2y$10$TtIYI/zREEIn8UcaLXWwYu/VtUPKA1co770gxwli7aushunYJoxVi', 'siswa'),
(118, 858, 'ddc6d87a-3997-11e5-84ac-2302d1c506b6', '2bcef959-7a06-4631-b590-f53fb9f93bc2', NULL, 49, NULL, '0089386510', '24250105', NULL, NULL, 'DAFA SEPTIADI', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'GELIANO THOMAS', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213293009080001', '2008-09-30', 1, '$2y$10$WGV7hnSRPIPDXxqdaHqHuOaSHPSMjbkkzF4xoi9jqWKvll4RzBEc2', 'siswa'),
(119, 859, '413b02cf-00dc-4cb8-b131-232790f665d3', '9cce8b82-f77c-4be6-be06-449e5da19e86', NULL, 48, NULL, '0093109918', '24250166', NULL, NULL, 'NETHA JUNIAR', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, '-', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213126006080001', '2009-06-24', 1, '$2y$10$ESLnLqIjUkV8tLXFkAR.WuJTFqcTGzIM0qfiU0DYhdrzgo7IVwGj.', 'siswa'),
(120, 860, '76ac8454-3a5b-11e5-871a-235a91f2a733', '68edb4cb-5f7a-4dfb-b043-d5f663a2f21b', NULL, 54, NULL, '0096153033', '23240158', NULL, NULL, 'NAJLA KHAIRUNNISA SOPIAN', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'IYAN SOPIAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213294101090001', '2009-01-01', 1, '$2y$10$Nm/ERYgSGR5xg/5eXa7YbOBVF.TcoqfcXNAF/kH1tjIt6cR/eBFI.', 'siswa'),
(121, 861, '559d922c-3af6-11e4-b4ab-23a93f3cf167', '67d9edb1-f0e2-4693-992d-3e4cccd0e1a0', NULL, 40, NULL, '0061720192', '23240111', NULL, NULL, 'ATBIK ABIL FIRDAUS', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Nono Rukmana', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213122209060001', '2006-09-22', 1, '$2y$10$8r7OK/NOKSoJxlao0YWTn./Ik6.ZyfVVGHlrnT/RYGXGoVAQ/6gQC', 'siswa'),
(122, 862, 'af94a13a-26d3-11e4-9217-23ca679e6acf', '6b9f4508-c1f9-46e0-b8a6-a03bacd4a708', NULL, 51, NULL, '0073590014', '23240044', NULL, NULL, 'SANDRA SEFIRA MARGARETA', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Kiki Kurnia Subagja', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213014609070001', '2007-09-06', 1, '$2y$10$UTpJiPwcXlu/SloI7on.POIT3Q6L0m5rog9/mS47djIHcJ6VfqLKi', 'siswa'),
(123, 863, '721a5dfc-3337-11e4-8702-23de9b3e1991', '6b9f4508-c1f9-46e0-b8a6-a03bacd4a708', NULL, 51, NULL, '0086593724', '23240032', NULL, NULL, 'INDRI PEBTRI SAPARWATI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'WAWAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213126202080002', '2008-02-22', 1, '$2y$10$DyxBrWG7yta6g34geqkcJ.DtjwzPUogLzvv08LEyVRLwu6s5IGY7C', 'siswa'),
(124, 864, '58ef7c63-8e67-447e-a10c-240c72827ed2', '005b0659-8a5e-4160-8830-9ddb091fbf00', NULL, 44, NULL, '0099352901', '252614121', NULL, NULL, 'FANNY NURFAUZIAH', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'SATMA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296907090002', '2009-07-29', 1, '$2y$10$.ulqsgcku.xYJDIyzqAKEOucJKryLS5EMiLItv9ABsVQ.3SUkTP2e', 'siswa'),
(125, 865, '9e9616c4-db34-43fa-8c37-24b8fc0c93f9', 'beea584c-74a1-4013-9a57-06b34248371b', NULL, 53, NULL, '0086015519', '23240090', NULL, NULL, 'FARREL YUDHISTIRA IRAWAN', 'L', 'L', 'BEKASI', NULL, NULL, '-', NULL, NULL, 'DEDI IRAWAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3216090203080005', '2008-03-02', 1, '$2y$10$Wkl5.JoMqFJe.bpqNnQSOOayy33ghDYkUKmL7lsnp5RP2AwWZt5hu', 'siswa'),
(126, 866, '0340eda2-1db3-4695-b317-2599ca9a48bf', 'da90cc11-526e-48aa-b7ce-a9afbc0d8023', NULL, 41, NULL, '0107842931', '252614005', NULL, NULL, 'CANTIKA APRILIA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'HARIS ROMANSYAH', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213295304100002', '2010-04-13', 1, '$2y$10$Hc3i5e.Bpavs5MYFl1oEHuUG/6iu3SM3BvLl5XVj8qBRWhDAId.Sq', 'siswa'),
(127, 867, 'dc98de4f-1e71-4279-a9f8-25b53ba601c8', '1d260111-0d05-4b10-a525-1eeeef43fe6b', NULL, 45, NULL, '0105609408', '252614043', NULL, NULL, 'LAREINA LEGIA HAKIM', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'LUKMAN N', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213294109100010', '2010-09-01', 1, '$2y$10$L3qqPyyeioUioBjO4L0QNO2RKbZPoYTSnpSSJfRMIxRgf/NZuv6x6', 'siswa'),
(128, 868, 'cc23c990-5ddf-11e5-b5af-2713431ff04c', '2bcef959-7a06-4631-b590-f53fb9f93bc2', NULL, 49, NULL, '0073486437', '24250111', NULL, NULL, 'FIRMAN RAMADHAN', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ROHMAT HIDAYAT', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213011909070001', '2007-09-19', 1, '$2y$10$Gp4QxUinOT1dL.jXmjRMmOVLl5BJZMPk7ObjqE/BYBEhTY3Jd6HIi', 'siswa'),
(129, 869, '731d14b6-3d76-11e5-bbd5-2716ed175322', '2bcef959-7a06-4631-b590-f53fb9f93bc2', NULL, 49, NULL, '0093560392', '24250097', NULL, NULL, 'ADITYA PRATAMA', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'IWAN HIDAYAT', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213263103090002', '2009-03-31', 1, '$2y$10$0ImRLUeGiAKr1KEMz8lhxu6RezAsXHs2f/T/TNV/J7OjWJkyJe91q', 'siswa'),
(130, 870, '7c6066a4-3f7e-11e5-ae4c-2734b4e4aecc', '2bcef959-7a06-4631-b590-f53fb9f93bc2', NULL, 49, NULL, '0092412603', '24250100', NULL, NULL, 'ALDI SAHRONI', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'SOLEHUDIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213290101090001', '2009-01-01', 1, '$2y$10$l.2bDv3OwWPn6KNOonx/vOn/dx37HRzTmwgkGsqpE25U20Z51JGhm', 'siswa'),
(131, 871, '735cfece-3e48-11e4-aeef-2779a5c88a0f', '67d9edb1-f0e2-4693-992d-3e4cccd0e1a0', NULL, 40, NULL, '0083266854', '23240137', NULL, NULL, 'Zafar Shidiq', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Agus Mahpudin', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213122504080011', '2008-04-25', 1, '$2y$10$LHxNAyDRjJx3F1SlI7UsQ.0Ozlnq0pbOOyIOGYAMSwCsCU12iHa/m', 'siswa'),
(132, 872, '0a9d33b4-3276-11e4-91b1-277bed938422', '6b9f4508-c1f9-46e0-b8a6-a03bacd4a708', NULL, 51, NULL, '0076944895', '23240041', NULL, NULL, 'NOVI ANANDA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'DAWAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213125310070001', '2007-10-13', 1, '$2y$10$DEwBMiJBojYTLGhVn93yUeJxIBI2vNxvNBW7NlSPKwY0dHc7rX70K', 'siswa'),
(133, 873, 'bd6dc58a-2d17-11e4-9ed6-279f4c28b805', '20a865c3-2d47-4cb4-90a4-885b04daa92e', NULL, 52, NULL, '0087145906', '23240013', NULL, NULL, 'NADIA ERLYANTI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'LILI MARDIAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213295508080002', '2008-08-15', 1, '$2y$10$gpM.Td016HSrHp2HlBuTNOZLa799xUKtNb8WON2C2ooiRY1SqmzxK', 'siswa'),
(134, 874, 'da1553de-0e19-4c63-815f-29cbf339c424', '0168bb86-45b1-4a2f-8beb-b147e3bf05a7', NULL, 42, NULL, '0106614091', '252614088', NULL, NULL, 'AGIS MUHAMAD JANUAR', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'AGUS', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213294201100011', '2010-01-12', 1, '$2y$10$CckNTlgyn0Ff.T/dBjUlQuDk5eW.Om4XTe5T0DFqDiwX.44zA92Bm', 'siswa'),
(135, 875, '45ff26e5-9171-416c-8f2d-29dd226e0839', '1d260111-0d05-4b10-a525-1eeeef43fe6b', NULL, 45, NULL, '0105184100', '252614050', NULL, NULL, 'SESIL SULISTIANI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'SUCIPTO', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296610100002', '2010-10-26', 1, '$2y$10$gnHVhBnyMFaP.nU8jeu1v.t1zpjkIoIEg2NzJ6FnKgsREr5exWhiu', 'siswa'),
(136, 876, '0d1bdd16-ca18-4778-8ae5-29eadc0a3824', 'f0dd79d4-b8fa-4815-b531-f65b873990ea', NULL, 43, NULL, '0087825511', '252614099', NULL, NULL, 'IRFAN MULYADI', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ACENG DEDI JUNAEDI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213292311080001', '2008-11-23', 1, '$2y$10$Bqq3rAofqrFlf7uIkQK1EuCDgBI00aNNugb/fCUoIy3KO9TFIlBJO', 'siswa'),
(137, 877, '2a1b440e-00da-4673-a312-2b056ffda700', 'f0dd79d4-b8fa-4815-b531-f65b873990ea', NULL, 43, NULL, '0097873936', '252614066', NULL, NULL, 'IIS SRI NURHAYANI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'IWAN SETIAWAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213290807090002', '2009-07-08', 1, '$2y$10$kRx6lLBJInxyQiuSnBRAIeROAGbRDcLD2z461uxfrbc3gydxQwh1y', 'siswa'),
(138, 878, 'f1310f2c-2a64-11e4-bfd9-2b4550e8252a', '20a865c3-2d47-4cb4-90a4-885b04daa92e', NULL, 52, NULL, '0081981356', '23240019', NULL, NULL, 'RISTAN RAHAYU RUDIANSYAH', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Rudi Setiadi', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213124301080001', '2008-01-03', 1, '$2y$10$NA.ZE9JmvYeHcYWWVwsFguq4cqr46H2CvZJNn6iW.BxgA9VuQ8EJK', 'siswa'),
(139, 879, '09fd1ddf-fd2a-4efa-88b8-2e606040bb9f', '005b0659-8a5e-4160-8830-9ddb091fbf00', NULL, 44, NULL, '0102215859', '252614123', NULL, NULL, 'GILANG RAHMAT ANUGRAH', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'MAMAT RAHMAT', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213120203100002', '2010-03-02', 1, '$2y$10$5Rob3INr96KH90cCpXIWfucmmBAFB9GCgUFNexEizggkf3XTyvrb2', 'siswa');
INSERT INTO `tbl_siswa` (`id`, `id_user`, `dapodik_id`, `rombel_id_dapodik`, `user_id`, `kelas_id`, `jurusan_id`, `nisn`, `nis`, `rfid_uid`, `qr_code`, `nama_lengkap`, `jk`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `agama`, `alamat`, `no_hp_siswa`, `email_siswa`, `nama_ayah`, `nama_ibu`, `nama_wali`, `no_hp_ortu`, `pekerjaan_ayah`, `pekerjaan_ibu`, `status_siswa`, `foto`, `created_at`, `updated_at`, `nik`, `tgl_lahir`, `agama_id`, `password`, `role`) VALUES
(140, 880, 'b8a0cb00-2ccd-11e4-b789-2f30e78363ef', '67d9edb1-f0e2-4693-992d-3e4cccd0e1a0', NULL, 40, NULL, '0081656364', '23240130', NULL, NULL, 'RIZAL NUGRAHA', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Mumuh Mulya Sukmana', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213290706080002', '2008-06-07', 1, '$2y$10$P2SPCqgsDZBNvI65d3w05ep6tca6SAdJDKmdqBQ8uMs8CU5R/FauS', 'siswa'),
(141, 881, 'd625dd54-4163-11e5-a035-2f46aad0d87f', 'e94587e3-0ece-43f2-b670-502c10beffe3', NULL, 50, NULL, '0086242479', '24250137', NULL, NULL, 'Nailan Nikmah Lubis', 'P', 'L', 'Bekasi', NULL, NULL, '-', NULL, NULL, 'Munawir Lubis', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '1213065611080002', '2008-11-16', 1, '$2y$10$W.67oNu2iArHWlJRi5T2KeW3Fp65i9dw7qY5mXYmCIRHIpqSMf4LK', 'siswa'),
(142, 882, '1f418756-bf96-48ba-a8b7-2f6da847dafa', '005b0659-8a5e-4160-8830-9ddb091fbf00', NULL, 44, NULL, '0092694024', '252614136', NULL, NULL, 'REYFALDI WALUYO', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ANDRI WALUYO', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213291509090001', '2009-09-15', 1, '$2y$10$ruNW9mbUohv9m2IkN.oIJOe50bxajO/QURDn4wQ/B5ZijmJTyboku', 'siswa'),
(143, 883, '7b39f886-4324-11e5-a332-2f92678f5470', 'e94587e3-0ece-43f2-b670-502c10beffe3', NULL, 50, NULL, '0096392028', '24250148', NULL, NULL, 'SAILA FAUZIATUNNISA', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'SEHABUDIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213265704090001', '2009-04-17', 1, '$2y$10$we8TbhuY99Yw5NqYUf4nbeRIj0QIv1X6tcn/aUzrqs2Ph7UCOo2uG', 'siswa'),
(144, 884, '279cb84c-3323-11e4-b491-2ffc9425e30e', '68edb4cb-5f7a-4dfb-b043-d5f663a2f21b', NULL, 54, NULL, '0074650867', '23240142', NULL, NULL, 'AYU HARTATI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'UMYAR', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213126210070001', '2007-10-22', 1, '$2y$10$xPsG2yxBpGvG2G/lYXLblOdkp5uXWUpKrhnXQ9VxaUDIOEc1ZrDx2', 'siswa'),
(145, 885, 'f10a31a0-3bd8-11e5-9fa5-2ffe9f91d12d', '2bcef959-7a06-4631-b590-f53fb9f93bc2', NULL, 49, NULL, '0088223990', '24250129', NULL, NULL, 'WANDA PRAMESTA', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'RUDI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213291205080011', '2008-05-12', 1, '$2y$10$ixMyxPAw2R1bk4XEWLc.me5y50YCL1aPyfZuUUfmhfzvR5AnWp3WG', 'siswa'),
(146, 886, 'fc575aab-37c4-4089-8174-300b5c83faa3', 'f0dd79d4-b8fa-4815-b531-f65b873990ea', NULL, 43, NULL, '0106964935', '252614081', NULL, NULL, 'SYIFA AULIA PUTRI UTAMI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'UJANG AWAN AR', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213266203100001', '2010-03-22', 1, '$2y$10$5TWmA6w5wCdrVWa3QPoEs.qjy1QW947t.9Bs6iX61R0Nwihx.0.nS', 'siswa'),
(147, 887, '8c93a2a6-a314-4c4f-8218-32a31355b961', 'da90cc11-526e-48aa-b7ce-a9afbc0d8023', NULL, 41, NULL, '0102252701', '252614018', NULL, NULL, 'NURUL MUTIAROH', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'PEPEN SUPENDI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213294209100016', '2010-09-02', 1, '$2y$10$gZMuxDuoOGJMYBut.bGUmua5DCYfeqQmcl9iimIW7WKQAoTWDdOtO', 'siswa'),
(148, 888, '4e9ad9f2-3cb3-11e5-ae60-331807edb0fb', '619c6aff-d40d-4ac9-905d-243b54536570', NULL, 47, NULL, '0088297767', '24250017', NULL, NULL, 'NITA AULIA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'AGUS SUTIA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296407080001', '2008-07-24', 1, '$2y$10$4TETWXTKUCSQcNAcuXDrNeWbV4.Rind3pudd.g39ytDK/jlwWHi8m', 'siswa'),
(149, 889, '84242ccf-0318-47e3-ae46-3331bee58a05', '0168bb86-45b1-4a2f-8beb-b147e3bf05a7', NULL, 42, NULL, '0099347931', '252614112', NULL, NULL, 'SAEPUL FIKRI', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'CAHYA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213292209090001', '2009-09-22', 1, '$2y$10$inGmUs2O3PLTywW/I5opgOMrb4k5PtEbdH56JNnfehK3Cz4P5k7FC', 'siswa'),
(150, 890, '65a90fb0-143e-4209-93c4-33ad015f3af4', '1d260111-0d05-4b10-a525-1eeeef43fe6b', NULL, 45, NULL, '0097002833', '252614049', NULL, NULL, 'RIZKA ALYASYA PUTRI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ENDAN ALEP', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213295512090001', '2009-12-15', 1, '$2y$10$ivmkVZYeDwM9oyiW8k04he58CnhPgw0CRlhk82gF7j2Mt8pACzzoe', 'siswa'),
(151, 891, '9f5b2a94-460f-11e5-9dcb-33d9eb1c986d', 'da90cc11-526e-48aa-b7ce-a9afbc0d8023', NULL, 41, NULL, '0096721114', '242514028', NULL, NULL, 'Rahma Siti Ayu', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Ace', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213294709090001', '2009-09-07', 1, '$2y$10$/ipYGsnaQ3StZPEll6bH8.v6my33tT3SD0/cvBCSEHSVh/9p2luhG', 'siswa'),
(152, 892, 'c7748082-4800-4028-a9d0-348f6670a248', '005b0659-8a5e-4160-8830-9ddb091fbf00', NULL, 44, NULL, '0106646484', '252614146', NULL, NULL, 'TIARA DESVITA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'DEPIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213294601100001', '2010-01-06', 1, '$2y$10$8t1EGf731vRHTmWvHLH90ec/6Ba/tjb.7tHW7GCqHAerr8/KWHxNW', 'siswa'),
(153, 893, 'be1b6dae-3b5b-11e5-8b6f-37a944d1da12', '1d4ffd24-3a9e-4c03-afd8-a12ab295694a', NULL, 46, NULL, '0086249481', '24250075', NULL, NULL, 'GALUH IKHSAN SOPIAN', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'SUBANDI SOMANTRI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213291411090001', '2008-11-14', 1, '$2y$10$osSZDHEBaczndwNLbLkpFOtLGhKNR5fKzb94huOjio7Efw5HuKZA2', 'siswa'),
(154, 894, '5682f826-3443-11e4-b40f-37c2bde7e94d', '964b7cb7-a338-4574-814c-f5954ed784eb', NULL, 55, NULL, '0077892338', '23240059', NULL, NULL, 'CHIKAL RAMADHAN HIDAYATULLOH', 'L', 'L', 'KARAWANG', NULL, NULL, '-', NULL, NULL, 'YAYAT HIDAYATULLAH', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3215143009070002', '2007-09-30', 1, '$2y$10$IEQS9Nwvw3RBSSKPH.aPn.sXK7iSoqpAieRCleSYMUPAkU.k0fosS', 'siswa'),
(155, 895, '738f3aa6-5f84-48ed-a894-38025a39e0c6', '0168bb86-45b1-4a2f-8beb-b147e3bf05a7', NULL, 42, NULL, '0096562070', '252614087', NULL, NULL, 'ADILLA RAISYA PUTRA', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'TATANG MULYANA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213292803090001', '2009-03-28', 1, '$2y$10$h5pStI235NRt4OgXZobb5.3hAl3tp8eiXPqT/BhEF.w3X0/FfUy7W', 'siswa'),
(156, 896, '38e78862-d133-44a7-b559-38498ddba2c7', 'da90cc11-526e-48aa-b7ce-a9afbc0d8023', NULL, 41, NULL, '0109413096', '252614027', NULL, NULL, 'YUDHA NUGRAHA', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'YANYAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213292307100002', '2010-07-23', 1, '$2y$10$o/GTnxXzkqVs.SpWI2U5xOuLJqQvrtvQSawwmCkkgwANrVSPqX/rK', 'siswa'),
(157, 897, '30ea22d2-2138-473b-8e2e-38e19f9adbf9', '005b0659-8a5e-4160-8830-9ddb091fbf00', NULL, 44, NULL, '0091086226', '252614119', NULL, NULL, 'DIMAS ADI SAPUTRO', 'L', 'L', 'SUKOHARJO', NULL, NULL, '-', NULL, NULL, 'SETYAWAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3311081608090001', '2009-08-16', 1, '$2y$10$MuHVywvYwVgNtnwTfER9leuhUBG00VoY7C9Ncxs4JerJO4J8ou1c2', 'siswa'),
(158, 898, 'c5c581f2-2b5d-11e4-b443-3b3c3cc74521', '67d9edb1-f0e2-4693-992d-3e4cccd0e1a0', NULL, 40, NULL, '0086895164', '23240131', NULL, NULL, 'SELISTIA JAYA DIPURA', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'IWAN SUTARNO', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213234209080001', '2008-09-02', 1, '$2y$10$P4Jbqt.KS2E40GiS1hQ4outC3psBeZwMe1gMuyD8YCwcf2fDw4OU.', 'siswa'),
(159, 899, 'e2bafd18-5083-11e5-b5f4-3bb0c501dc76', '2bcef959-7a06-4631-b590-f53fb9f93bc2', NULL, 49, NULL, '0087899649', '24250118', NULL, NULL, 'MUHAMMAD RAYHAN AL FARIZI', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ENDAR SUNANDAR', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213291001080001', '2008-02-23', 1, '$2y$10$DwA8DTckkumf15FmoFuk5.Cbyz0jjFeWvze6l2qM1L1fRw1U9hcUq', 'siswa'),
(160, 900, '01dc8f34-3993-11e5-8a26-3be93b4f0f0e', '619c6aff-d40d-4ac9-905d-243b54536570', NULL, 47, NULL, '0082982790', '24250002', NULL, NULL, 'APRILIYA DETIANTI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'DEDIH SAMSUDIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213295704080001', '2008-04-17', 1, '$2y$10$KWc7BZeMlB561/VGLgLa7.FfgAiYgx5c7hNydLLHN/nDo64THSAE.', 'siswa'),
(161, 901, '6159e62e-5204-48ad-b156-3d1b54a74e14', 'da90cc11-526e-48aa-b7ce-a9afbc0d8023', NULL, 41, NULL, '0103836874', '252614001', NULL, NULL, 'AGNIE ELYANDI', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Rochendi', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213295810100001', '2010-10-18', 1, '$2y$10$8EBdi6PQkUKREWhnnO0mxOUt2kz6tgcjzdtS4zl1et/OK4xlgLf9G', 'siswa'),
(162, 902, '59746ec5-63da-433d-97ad-3d1cf1c576ec', 'da90cc11-526e-48aa-b7ce-a9afbc0d8023', NULL, 41, NULL, '3097937600', '252614009', NULL, NULL, 'FITRIA MAISAHAQ', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'UDIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213194612090001', '2009-12-06', 1, '$2y$10$PqkXaC1mNt4l6hDKh3I0mO2obEYVRJ455aFWc1mXCKxVYFv7QOuoS', 'siswa'),
(163, 903, 'f941a785-0120-4878-8283-3e6423e60690', '0168bb86-45b1-4a2f-8beb-b147e3bf05a7', NULL, 42, NULL, '0108373071', '252614110', NULL, NULL, 'RIKI RIVALDI', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ENDANG SUHENDA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213290506100001', '2010-06-05', 1, '$2y$10$2qsFv8VQDYU7SbejWazPJOAra3W6BgnHTMbhQGd4IBvzA2ge3X.1y', 'siswa'),
(164, 904, 'f3804ea6-a560-4083-a87b-3e888cb2b6ff', 'f0dd79d4-b8fa-4815-b531-f65b873990ea', NULL, 43, NULL, '0099514568', '252614065', NULL, NULL, 'FITRI JUITA SARI', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ADE KAHFI ALM.', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213290609090001', '2009-09-06', 1, '$2y$10$ihSUqsi5eUE8iFcJLgqUmuGEvpBMx6HPa0V.0zqLLG1h9OBhmB5Rm', 'siswa'),
(165, 905, '1428b3f6-35e4-11e4-8f61-3f0e60401c38', '964b7cb7-a338-4574-814c-f5954ed784eb', NULL, 55, NULL, '0075235265', '23240066', NULL, NULL, 'LANI HANURTIYA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'AE SUNTANA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296212070001', '2007-12-22', 1, '$2y$10$tw0EpFxBVAYU5LXfBOWHk.ntWJ3LPgiQ750/S7NkbZ3UKWzJL95Zi', 'siswa'),
(166, 906, 'd7d391f2-3b5d-11e5-8949-3f2a5e52c80c', '9cce8b82-f77c-4be6-be06-449e5da19e86', NULL, 48, NULL, '0095074022', '24250034', NULL, NULL, 'ERWIN ARYA NUGRAHA IWANI', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'IWAN SETIAWAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213013010090001', '2009-10-30', 1, '$2y$10$3F0lwR3q8r/DopGkAfCdvu56dWRtC9vyo5FWeXRSUOSxrBNIxg94.', 'siswa'),
(167, 907, 'b4c80f1e-56ae-11e5-b78e-3f425fec28c3', '619c6aff-d40d-4ac9-905d-243b54536570', NULL, 47, NULL, '0082766357', '24250019', NULL, NULL, 'NONENG KANIAWATI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ATANG', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213294709080001', '2008-09-07', 1, '$2y$10$Qy9SIpten/xtXcjeboHW.OBmpEO89T4Mgwg7XvNiOq5AsLBaAi7Uy', 'siswa'),
(168, 908, 'f4603fc8-4242-11e5-86b9-3fdc0cb2f0de', '1d4ffd24-3a9e-4c03-afd8-a12ab295694a', NULL, 46, NULL, '0081943385', '24250073', NULL, NULL, 'FAUZI', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ROSITA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213120510080001', '2008-04-06', 1, '$2y$10$Db7YHnEJ0RhUmGpCyzE/Q.vk8WBxcxEU4XE7kTdUZPhbOFm/nxoEC', 'siswa'),
(169, 909, '2a68f7c8-333e-11e4-b56b-3fe076be8b43', '67d9edb1-f0e2-4693-992d-3e4cccd0e1a0', NULL, 40, NULL, '0081310093', '23240120', NULL, NULL, 'IIN HIDAYAT', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'SARMID', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213122104080003', '2008-04-21', 1, '$2y$10$Aa7RBdiCrBqqxkfU77.iVOYaZ2H.t1Q53FH7oipjcwzTxTqUKaa4i', 'siswa'),
(170, 910, 'cb4785f0-3a5f-11e5-b189-3feaaf6ba8cd', 'e94587e3-0ece-43f2-b670-502c10beffe3', NULL, 50, NULL, '0085263627', '24250133', NULL, NULL, 'DEWI NURAENI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'TATANG ROHENDI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213295807080001', '2008-06-18', 1, '$2y$10$A4aXpjIra1EhuFs8TUr/FeqBUVNCuHTbS6EN7fkHeodPy/xDzoKj6', 'siswa'),
(171, 911, 'b0609f8d-4142-48c5-924d-401807fa027e', '005b0659-8a5e-4160-8830-9ddb091fbf00', NULL, 44, NULL, '0098204140', '252614140', NULL, NULL, 'RIZKY ROHMATUL SOPIAN', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'YAYAN SOPIAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213121311090002', '2009-11-13', 1, '$2y$10$IPFYv2Uu7NNP5H/fZtEOmeK7Ywz81nRvWdWpE7HxFCVTmdyyVRl/W', 'siswa'),
(172, 912, '075639db-f804-11eb-841c-42010aba8125', '1d4ffd24-3a9e-4c03-afd8-a12ab295694a', NULL, 46, NULL, '0084041839', '24250063', NULL, NULL, 'AGNIA RAIHANA AZZAHRA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'Usep Rahmat Hidayat', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213266910080003', '2008-10-29', 1, '$2y$10$d3WqjjiGVz0SmBNZ08X5PuKKyS45Iuv5CnMtbD4i9eTSuffoXbspS', 'siswa'),
(173, 913, '13db08a5-3514-11ec-a3a1-42010aba8125', '9cce8b82-f77c-4be6-be06-449e5da19e86', NULL, 48, NULL, '0084437229', '24250041', NULL, NULL, 'NADILAH ISTINA', 'P', 'L', 'BANDUNG', NULL, NULL, '-', NULL, NULL, 'Sarmin', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3217016410080007', '2008-10-24', 1, '$2y$10$hVUuB0TOPFOnnN7FIqaJ8.AiKpNL2HTTIXGFfIrUER5QNuv1OAfpy', 'siswa'),
(174, 914, '13c236ad-3514-11ec-a3a1-42010aba8125', '2bcef959-7a06-4631-b590-f53fb9f93bc2', NULL, 49, NULL, '0084853058', '24250126', NULL, NULL, 'SAEFUL FACHRY', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'Dede Hendra', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213292804080001', '2008-04-28', 1, '$2y$10$mr0mjS20BYapW0YFvd/GoOpvsF1KBfjqYBVJLxULtmRz6gkP8X3QO', 'siswa'),
(175, 915, '13d340c4-3514-11ec-a3a1-42010aba8125', '9cce8b82-f77c-4be6-be06-449e5da19e86', NULL, 48, NULL, '0088035039', '24250048', NULL, NULL, 'Rima Indriyani', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Sanusi', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296612080002', '2008-12-26', 1, '$2y$10$j.0kPXwMxS3LJRpevKGZgu41pwFQGJBUk62qVE0SkxpxffslTJfAC', 'siswa'),
(176, 916, '36e3d5fe-2330-11ec-a3a1-42010aba8125', 'e94587e3-0ece-43f2-b670-502c10beffe3', NULL, 50, NULL, '0085011994', '24250135', NULL, NULL, 'KHANIA NUR ANISA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'Budi Bachtiar', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213295409080001', '2008-09-14', 1, '$2y$10$M8ykLa8Qj2Mdtw0kgKSbp.MOKOdK2tiY2KBamhjyhrtw/qsgpxt/y', 'siswa'),
(177, 917, '43e8572b-2a3c-11ec-a3a1-42010aba8125', 'e94587e3-0ece-43f2-b670-502c10beffe3', NULL, 50, NULL, '0087093573', '24250134', NULL, NULL, 'IKROM RAMDHANI', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, '', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213190709080001', '2008-09-07', 1, '$2y$10$WuJJ5lFJWgNoiK8O7/tq/.CrKyP3t8ELZacOkZnpFhtAmFpu17/dC', 'siswa'),
(178, 918, '3cb22954-2d52-11ec-a3a1-42010aba8125', '1d4ffd24-3a9e-4c03-afd8-a12ab295694a', NULL, 46, NULL, '0089554475', '24250064', NULL, NULL, 'ALDI ALDIANSYAH', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'Surji Hidayat', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213292811080002', '2008-11-28', 1, '$2y$10$UkxfKZF9mnIKwbBSnPEVd.0G50VGOxiRtD02oIeJkZAc8hWXdRgq2', 'siswa'),
(179, 919, '3ce557b5-2d52-11ec-a3a1-42010aba8125', '9cce8b82-f77c-4be6-be06-449e5da19e86', NULL, 48, NULL, '0097602339', '24250058', NULL, NULL, 'VINKA AZ ZAHRA', 'P', 'L', 'GARUT', NULL, NULL, '-', NULL, NULL, 'Nasir Muhamad Zani', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3205054306090002', '2009-06-03', 1, '$2y$10$p93wwylmWEi1/e.6gp10pOyXhAlrf1J7Z4mDDb6kfaHfdgKIuTp3y', 'siswa'),
(180, 920, '3cc4cef4-2d52-11ec-a3a1-42010aba8125', '9cce8b82-f77c-4be6-be06-449e5da19e86', NULL, 48, NULL, '0088639721', '24250047', NULL, NULL, 'PUTRI FEBRIYANTI', 'P', 'L', 'BANDUNG', NULL, NULL, '-', NULL, NULL, 'Endang Suryana', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3273156502080001', '2008-02-25', 1, '$2y$10$3qmrKSEMSmMLFSMmOyv2I.mQ2yc4ubkppwaU8VpBV0pLyD9yMEYUK', 'siswa'),
(181, 921, '4173f624-3797-11ec-a3a1-42010aba8125', '9cce8b82-f77c-4be6-be06-449e5da19e86', NULL, 48, NULL, '0084971930', '24250050', NULL, NULL, 'SAFIRA SITI FAUZIAH', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'Junaedi', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213294107070168', '2008-10-25', 1, '$2y$10$LcsErk.GZuYCL4Xdy0KC7efOYNh92JINoRCzeDnTndD9.yzhWoOj2', 'siswa'),
(182, 922, '2a819c13-ff10-11eb-a965-42010aba8125', '1d4ffd24-3a9e-4c03-afd8-a12ab295694a', NULL, 46, NULL, '0095253298', '24250095', NULL, NULL, 'Siti Sopiah', 'P', 'L', 'Purwakarta', NULL, NULL, '-', NULL, NULL, '', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3214075211100001', '2010-11-12', 1, '$2y$10$CMyEAnpyLccCc/h9eU52/.3hPJ6U98Ehb5zJWvo0t.0c1CFjZ5qpS', 'siswa'),
(183, 923, '761d4923-030d-11ec-a965-42010aba8125', '619c6aff-d40d-4ac9-905d-243b54536570', NULL, 47, NULL, '0096187738', '24250024', NULL, NULL, 'RAHMA AULIA ZAHRA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'Enjang Suherman', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213014705090001', '2009-05-07', 1, '$2y$10$reQhoJS9kqbSA/Bvt4lyS.rSPIUJmaCsYIZg6RnF4dAK9LR49Tm5C', 'siswa'),
(184, 924, '5d01b144-030d-11ec-a965-42010aba8125', '619c6aff-d40d-4ac9-905d-243b54536570', NULL, 47, NULL, '0081630090', '24250025', NULL, NULL, 'RATNA SETIAWATI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'Ucu Junaedi', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213015201080001', '2008-01-12', 1, '$2y$10$beVC3bp404AreXGFA4pmzuhimx/J7JycLzC46tPJEIkYSmrJTD9Yy', 'siswa'),
(185, 925, 'a52de674-0623-11ec-a965-42010aba8125', '619c6aff-d40d-4ac9-905d-243b54536570', NULL, 47, NULL, '0084914457', '24250020', NULL, NULL, 'NOVALDI ABDUR RAHMAN ASSYAMSI', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'Rudi Kurniawan', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213122911080002', '2008-11-29', 1, '$2y$10$IEOilq8LfJCOeZhoj8/MCe.HHKwHewiK//6w9WEheSfHY4wPHAo5y', 'siswa'),
(186, 926, 'def1f32d-074d-11ec-a965-42010aba8125', '619c6aff-d40d-4ac9-905d-243b54536570', NULL, 47, NULL, '0078621758', '24250027', NULL, NULL, 'SATIRAH NUR ANISA', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Maman', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213125810070001', '2007-10-18', 1, '$2y$10$.eQCQCwOWWq6QFJOS.OVYelVGweav3hoj1k43YrZCTHEA2ODFVOr6', 'siswa'),
(187, 927, '7c3f231e-0255-11ec-a965-42010aba8125', '619c6aff-d40d-4ac9-905d-243b54536570', NULL, 47, NULL, '0096963938', '24250004', NULL, NULL, 'DEDE SYIFA SA\'ADAH', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Nana Suryana', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213014911100003', '2009-11-27', 1, '$2y$10$9U3Qk4Geuc1i5SzqY1Af8Oxlojjm7qRK9MUWefRnoPl.rz1jO.Ky2', 'siswa'),
(188, 928, '7875659a-079b-11ec-a965-42010aba8125', '619c6aff-d40d-4ac9-905d-243b54536570', NULL, 47, NULL, '0091757520', '24250011', NULL, NULL, 'HAVIZ LUTVIANA PERTIWI', 'P', 'L', 'Banyumas', NULL, NULL, '-', NULL, NULL, 'Teja Kusumawardana', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3302034708090002', '2009-08-07', 1, '$2y$10$.Rcd.TKPMVt47QyckM7nFuGB8HbILi0zmdwaOWx2ViMZiWadp6NL6', 'siswa'),
(189, 929, '3446b1c2-079d-11ec-a965-42010aba8125', 'e94587e3-0ece-43f2-b670-502c10beffe3', NULL, 50, NULL, '0088847049', '24250040', NULL, NULL, 'MUNA TAZKIYAH AWALIYAH', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'Wawan Ulwan', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213016108080001', '2008-08-21', 1, '$2y$10$wOvBeV1F7Ruj5nDST9cUouSipChNyTQyePGinTH3jEE9qReHGxnHm', 'siswa'),
(190, 930, '9679afad-07d1-11ec-a965-42010aba8125', '1d4ffd24-3a9e-4c03-afd8-a12ab295694a', NULL, 46, NULL, '0084457563', '24250091', NULL, NULL, 'RIFKI HERSYA RAMDHANI', 'L', 'L', 'SRAGEN', NULL, NULL, '-', NULL, NULL, 'Herman Suherman', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213262209080001', '2008-09-22', 1, '$2y$10$jn0UTw6bz8A9sTuxjcSfbeXPhlvDjQMOet9OuqbBCdKhEPw5bhbva', 'siswa'),
(191, 931, 'ca2f1e71-02fd-11ec-a965-42010aba8125', 'e94587e3-0ece-43f2-b670-502c10beffe3', NULL, 50, NULL, '0083658290', '24250139', NULL, NULL, 'NISYA NOVIANTI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'Saepulloh', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213016211080001', '2008-11-22', 1, '$2y$10$0R3uGaitiMjcSRJearsI0OLXsjPIaDbhA7lI3/J2AMmeBIQ1ps5cy', 'siswa'),
(192, 932, 'a04051f9-02fd-11ec-a965-42010aba8125', 'e94587e3-0ece-43f2-b670-502c10beffe3', NULL, 50, NULL, '0089705879', '24250152', NULL, NULL, 'ZALFA SABILA SABDA PERMATA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'Cucu Suhendi', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213016910080001', '2008-10-29', 1, '$2y$10$dGxOnf7H8L5pPxSUDeY3/.8KQsTh.lns95N0Uv2P6bk//oxKYVjBO', 'siswa'),
(193, 933, '8c29a7ce-0d3c-11ec-ae6a-42010aba8125', '1d4ffd24-3a9e-4c03-afd8-a12ab295694a', NULL, 46, NULL, '0089544500', '24250087', NULL, NULL, 'RENDI NUGRAHA', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Nunung Hermawan', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213121211080002', '2008-11-12', 1, '$2y$10$mNSBtAApKEqAJVFrQ4zloOQDeDMfvbsxQbgXttAHO5U8oagEWNnbm', 'siswa'),
(194, 934, 'c2d7ee41-0d3e-11ec-ae6a-42010aba8125', '2bcef959-7a06-4631-b590-f53fb9f93bc2', NULL, 49, NULL, '0083633497', '24250099', NULL, NULL, 'AGIN GINANJAR', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Nana Ruhimat', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213122808080002', '2008-08-28', 1, '$2y$10$XRIPMfLzmkKLo3JPGxGISeAGB4UU2/DB8VuwkDNowwT2cNt1YiN9W', 'siswa'),
(195, 935, 'd996bfe7-0b8e-11ec-ae6a-42010aba8125', '9cce8b82-f77c-4be6-be06-449e5da19e86', NULL, 48, NULL, '0086443351', '24250049', NULL, NULL, 'ROBIATU ADAWIYYAH', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'Ubang', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213265207080001', '2008-07-12', 1, '$2y$10$35nEoJZvlzTvQ6/hEn3rL.HKk2MJ4dIDB6ynrGNA4cakcztGS1ZlW', 'siswa'),
(196, 936, 'bc8d054b-1492-11ec-ae6a-42010aba8125', '2bcef959-7a06-4631-b590-f53fb9f93bc2', NULL, 49, NULL, '0082054596', '24250116', NULL, NULL, 'MOCH RIZKI KHOERUDIN', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'Nanan Supriatna', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213291602080001', '2008-02-16', 1, '$2y$10$5zrgAWOMBEMGCRKy3DhSzOgWBltYZlYsEQwBD.9.FHMWzSveW3.L.', 'siswa'),
(197, 937, 'd3bbde0b-0b94-11ec-ae6a-42010aba8125', '1d4ffd24-3a9e-4c03-afd8-a12ab295694a', NULL, 46, NULL, '0081863190', '24250072', NULL, NULL, 'FAHRIJAL AWALUDIN', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'Iwan Setiawan', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213120307080002', '2008-07-03', 1, '$2y$10$W9Xot6AyGqzP8NZHt.CVuOh.wvAGs1PHq4Kz/R9fj0DlB46cNdY1.', 'siswa'),
(198, 938, 'd357d7a4-0b94-11ec-ae6a-42010aba8125', 'e94587e3-0ece-43f2-b670-502c10beffe3', NULL, 50, NULL, '0085107122', '24250138', NULL, NULL, 'NINA AGUSTIN', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'Unang', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213264708080002', '2008-08-07', 1, '$2y$10$EYeDv1mnrw0qdvNXVmpW2OE.edm3qyuipvleZAwjaFNOHzMdtfddu', 'siswa'),
(199, 939, 'dc7ab04b-0b9d-11ec-ae6a-42010aba8125', '1d4ffd24-3a9e-4c03-afd8-a12ab295694a', NULL, 46, NULL, '0086461351', '24250086', NULL, NULL, 'REFFAL DAFFA SOMANTRI', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Rochmat Somantri', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213260808080002', '2008-08-08', 1, '$2y$10$1frmZ1x2zPu1goKLusxJce4HIffJ1./2gbZYYXrM80eb/j1OwfOES', 'siswa'),
(200, 940, 'dc8df156-0b9d-11ec-ae6a-42010aba8125', '1d4ffd24-3a9e-4c03-afd8-a12ab295694a', NULL, 46, NULL, '0089015802', '24250071', NULL, NULL, 'FACHRY KURNIAWAN', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Asril', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213261604080001', '2008-04-16', 1, '$2y$10$GJOPs1HtuPc3ABhJoSxjwOcpCTSEJlwyIRY/K2G2T7K50gl5KIIJi', 'siswa'),
(201, 941, '1d2bc035-0ba1-11ec-ae6a-42010aba8125', '2bcef959-7a06-4631-b590-f53fb9f93bc2', NULL, 49, NULL, '0084900006', '24250101', NULL, NULL, 'ALFIAN RIFAI SYAWA', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'Adang Ute Kusmana', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213260310080001', '2008-10-03', 1, '$2y$10$Pk6ED7OS.peTvyAhJAJHIubpO.Q3wY.nkKiFEDTM3uzzgS8Ee0Khu', 'siswa'),
(202, 942, '304c1f74-11ec-11ec-ae6a-42010aba8125', 'e94587e3-0ece-43f2-b670-502c10beffe3', NULL, 50, NULL, '0094448605', '24250140', NULL, NULL, 'Nur Azura', 'P', 'L', 'Wonosobo', NULL, NULL, '-', NULL, NULL, '', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '1108024302090001', '2009-02-03', 1, '$2y$10$884OSoPc/tb9sohcGII79OJmuAOY45Ufno27yC1ozc9u2w3QBnIGO', 'siswa'),
(203, 943, 'd18d292d-a346-11ec-a666-42010aba813c', '9cce8b82-f77c-4be6-be06-449e5da19e86', NULL, 48, NULL, '0095140710', '24250052', NULL, NULL, 'SILVA DINDA MARTIANA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'Eruh Mulyana', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213267003090001', '2009-03-30', 1, '$2y$10$fhPq/ElmeD4GeUdIm4/Qy.XNq.PbeOQK8zEZS4Yq/cvo.rD/tOciS', 'siswa'),
(204, 944, '3856743b-c536-11ec-a0aa-42010aba8149', 'e94587e3-0ece-43f2-b670-502c10beffe3', NULL, 50, NULL, '0087794465', '24250149', NULL, NULL, 'TYARA AGISTIANI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'Ade Aswara', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213016208080003', '2008-08-22', 1, '$2y$10$YuFw5jcVDyuUeyvsUsnVDOY6ICNYTG1j1n6fWZ4eZfSX3YNL.XEt2', 'siswa'),
(205, 945, 'fd716b60-c5ff-11ec-a0aa-42010aba8149', '1d4ffd24-3a9e-4c03-afd8-a12ab295694a', NULL, 46, NULL, '0097336972', '24250081', NULL, NULL, 'MUHAMMAD FAKHRI ALBANTANI', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, '', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3273300405090001', '2009-05-04', 1, '$2y$10$9Tq5Y.WaFALP5UJYh4Zv2.epZZBnolKmrs6D.wpu1V9piyv0m0ZMq', 'siswa'),
(206, 946, '755e4c24-2662-42b9-9e35-434cdc35e20b', '0168bb86-45b1-4a2f-8beb-b147e3bf05a7', NULL, 42, NULL, '0102959675', '252614096', NULL, NULL, 'GILANG REYANDI', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'WENDRI FASRIL', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213290302100002', '2010-02-03', 1, '$2y$10$0wJtJk08dRi.zseT87MPB.xRg5o3S/3MhR7I2zSaUa5rL0uo1HDHq', 'siswa'),
(207, 947, 'b0295fce-507a-11e5-b907-435bcaf4229a', '2bcef959-7a06-4631-b590-f53fb9f93bc2', NULL, 49, NULL, '0081552141', '24250098', NULL, NULL, 'AFIFAH BUTSAINAH', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'BUDHI HENRINS', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213295510080001', '2008-10-15', 1, '$2y$10$EBr6f43tWkwnZvJW5sKQ8OT/BxS6/hTUeIIqFVYJrPQBiP9SyIVNq', 'siswa'),
(208, 948, '3b8e0040-4f88-11e5-a01e-436ccc6a830d', '9cce8b82-f77c-4be6-be06-449e5da19e86', NULL, 48, NULL, '0092705324', '24250043', NULL, NULL, 'NENG SINTIA', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Sumad', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213125808090012', '2009-08-18', 1, '$2y$10$u4VqoAVVEv.uZqvCpwKuBuvvdxHXkLklji4Ycwd09OqHRsaEXjR8C', 'siswa'),
(209, 949, 'af513cd4-445a-11e4-95bb-438c91a412bc', '67d9edb1-f0e2-4693-992d-3e4cccd0e1a0', NULL, 40, NULL, '0076156529', '23240115', NULL, NULL, 'FAISAL RAMDANI', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'EDI SUPRIADI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213191110070001', '2007-10-11', 1, '$2y$10$4mT5YiaZ2BuHpS.bI7RAZezCQLlACzmnaUBoFmd7AIADGYKdia0rC', 'siswa'),
(210, 950, '7427af60-4302-11e5-9c68-43da0e7a0a89', '2bcef959-7a06-4631-b590-f53fb9f93bc2', NULL, 49, NULL, '0095841213', '24250123', NULL, NULL, 'Rival Virdaus', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Ahmad', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213291601090001', '2009-01-16', 1, '$2y$10$9siyKUqrGiLuA7Q9kK1duephuudM.EqQzzupXie6cTOon8xySb692', 'siswa'),
(211, 951, '80b07a25-909f-4eb3-8b73-454e32617eaf', '0168bb86-45b1-4a2f-8beb-b147e3bf05a7', NULL, 42, NULL, '0091609630', '252614097', NULL, NULL, 'GILANG RIFALDI', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'GANDI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213292801090001', '2009-10-28', 1, '$2y$10$28cUZjGVHbcqIdzcLvqWkuadKz7cVbo2G3hLGp8N6ZWRaL8hj5N.2', 'siswa'),
(212, 952, '74f660fa-0585-4754-9ce7-4707a0146a15', 'f0dd79d4-b8fa-4815-b531-f65b873990ea', NULL, 43, NULL, '0107934137', '252614085', NULL, NULL, 'WULAN YUNIAR', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Karman', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213124506100003', '2010-06-05', 1, '$2y$10$/WjISqiqwmoT1W2bI1ueuOhIQ6XVL7Zfy1XzfM39HNTEU0bESj3cS', 'siswa'),
(213, 953, '181487fc-335e-11e4-8127-47650576b1da', '68edb4cb-5f7a-4dfb-b043-d5f663a2f21b', NULL, 54, NULL, '0084137957', '23240159', NULL, NULL, 'RAHMA SUKMAWATI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ENDANG SUNANDI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213266004080002', '2008-04-20', 1, '$2y$10$PQY9nEXQmBNez1gFIuaHaOIrcUINlG65BU1vLQl78UBdttIaN2qCG', 'siswa'),
(214, 954, '20cab1d2-59f5-11e5-8d2c-4771acb0ad68', '619c6aff-d40d-4ac9-905d-243b54536570', NULL, 47, NULL, '0092454372', '24250008', NULL, NULL, 'DINI NURANZILI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'LILI SUMARLI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213295004090011', '2009-04-10', 1, '$2y$10$TZNGugfaQqyygU/ZySlN0.oAUjnDSsJ2JSk/Lp/Aurc/TS1xGYyhK', 'siswa'),
(215, 955, 'b1e62e6a-4328-11e4-946a-477f0e3a0b29', '964b7cb7-a338-4574-814c-f5954ed784eb', NULL, 55, NULL, '0077569074', '23240053', NULL, NULL, 'ADI MAULANA TAJUDIN KANDALAVI', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'EDI KUSDINAR', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213120606070001', '2007-06-06', 1, '$2y$10$2o66aCKGHIrkszGkYWBQTeqAxBrtOmxAh//V51VY8rg9bw61KqV1.', 'siswa'),
(216, 956, '4b70a500-2cbe-11e4-98e1-47b41aaad0f7', '964b7cb7-a338-4574-814c-f5954ed784eb', NULL, 55, NULL, '0084410760', '23240061', NULL, NULL, 'ELSA YULYANTI', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'EPI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213294107090009', '2008-07-01', 1, '$2y$10$bJYYJw7NxFCBT8aGUEgyEe03SN707rWDyyQh/szKeW53PxQmeQoyq', 'siswa'),
(217, 957, 'e6255d67-4d97-405c-81be-48f723b7c2a7', '1d260111-0d05-4b10-a525-1eeeef43fe6b', NULL, 45, NULL, '0096515836', '252614046', NULL, NULL, 'NELSI ARYANI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'YAYAT SUHAYAT', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213291311090001', '2009-11-13', 1, '$2y$10$4bUfI1KRkE4Nq6PGcJe22Odbrgqx3VZsJlFvLLAyRl8fwHtg9x2Ce', 'siswa'),
(218, 958, '1c4f4082-29a9-11e4-9383-4b4541c2dd76', '964b7cb7-a338-4574-814c-f5954ed784eb', NULL, 55, NULL, '0071082174', '23240078', NULL, NULL, 'SRI SOPIAH', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'Adang Irawan', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213294109070001', '2007-09-01', 1, '$2y$10$FDL8tSrqZV0.1BVeMjkJ.u2NOnI07dp0U6hSvRZW7GyukC6xx7CaG', 'siswa'),
(219, 959, '49a619d2-4328-11e4-9b33-4b4be7b329fb', '6b9f4508-c1f9-46e0-b8a6-a03bacd4a708', NULL, 51, NULL, '0076798160', '23240046', NULL, NULL, 'SAYLA ISTIQOMAH', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'SAIN SUMARYANA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213194703080001', '2008-03-07', 1, '$2y$10$gECOSI205xWG87GB6.HWZuxvyFQEbtJ6lQazWibazD6CUtwvI9Wqm', 'siswa'),
(220, 960, '9e01ea1e-29ac-11e4-834c-4b5c6234c79d', '67d9edb1-f0e2-4693-992d-3e4cccd0e1a0', NULL, 40, NULL, '0083708339', '23240136', NULL, NULL, 'YOGI ADITYA WARDANA', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'BOUNG', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213121705080001', '2008-05-17', 1, '$2y$10$088RA6lk1MsbIotRbGvSJeklomLSm2OdjB1j4I6DQhloMQQd0oEbu', 'siswa'),
(221, 961, '94177804-14ae-4a32-b315-4c8d9685a43c', '005b0659-8a5e-4160-8830-9ddb091fbf00', NULL, 44, NULL, '0106420213', '252614126', NULL, NULL, 'LUCKY ELVAND HABIBIE', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Syarif Hidayat', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213123003100001', '2010-03-30', 1, '$2y$10$7NCuRLmS8xV5kW0EQ9gnd.pyDEJji.o5XIuzAGnmfbrIIEfpRjmW2', 'siswa'),
(222, 962, '28a7434b-0f26-4bff-9170-4cbd541ccd85', 'f0dd79d4-b8fa-4815-b531-f65b873990ea', NULL, 43, NULL, '0108866950', '252614072', NULL, NULL, 'NUR AGNI AULIA RAMADANTI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'PENDI SUHENDI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213295208100001', '2010-08-12', 1, '$2y$10$2dY7SWdOA4QPODr13lkIjuCoATLbjIX8RLCEdHZRDziwZfx7xqTua', 'siswa'),
(223, 963, '2cee772f-65ed-4ad8-8914-4e90389faccf', '9cce8b82-f77c-4be6-be06-449e5da19e86', NULL, 48, NULL, '3097419665', '24250044', NULL, NULL, 'NIDA LISABILILHAQ', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'Ruli Rizki Riana', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213125906090002', '2009-06-19', 1, '$2y$10$pk98L2pmnF0HYlCL/xPrnepB01gzSTqv4qXU6ifCd7GvYBt5v4vhq', 'siswa'),
(224, 964, 'ba9c131f-1ca1-4ac0-9551-4f032381cf82', 'da90cc11-526e-48aa-b7ce-a9afbc0d8023', NULL, 41, NULL, '0104608263', '252614025', NULL, NULL, 'SYIFA PAUZIAH', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Ade Hidayat', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213014307100001', '2010-07-03', 1, '$2y$10$d6FW8UJcRTFTALNF5dQMDO7Es6fZWsfcaEQlz2W4kkTU7LFexJ/Rm', 'siswa'),
(225, 965, '8dbf6421-e637-4d52-9097-4f09a58d42e6', 'da90cc11-526e-48aa-b7ce-a9afbc0d8023', NULL, 41, NULL, '0092628646', '252614010', NULL, NULL, 'HERAYANTI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'KHAIRUL MUKMIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213124111090001', '2009-11-01', 1, '$2y$10$Wd4DQIvEn8CdGKp3GNsQEeIwEOXvVwKRg5wI5Ub2eIZIYTR8epLF.', 'siswa'),
(226, 966, 'ddaa66e2-5ae7-11e5-8926-4f250d6cfa0f', '619c6aff-d40d-4ac9-905d-243b54536570', NULL, 47, NULL, '0096618894', '24250014', NULL, NULL, 'LUGHWA NASYTHA', 'P', 'L', 'MEDAN', NULL, NULL, '-', NULL, NULL, 'ALMT TAUFIK HIDAYAT', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '1207045704090001', '2009-04-17', 1, '$2y$10$tbC613/FU3N/xlIB3cbroOz/7cdnSJWrZEbfcpSumhpKwn0RN8iy2', 'siswa'),
(227, 967, '65ccd714-2a77-11e4-acfe-4fa029ed0f18', '6b9f4508-c1f9-46e0-b8a6-a03bacd4a708', NULL, 51, NULL, '0082654149', '23240029', NULL, NULL, 'HILYA JAZILA', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'RAHMAT SOLIHIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213265604080001', '2008-04-16', 1, '$2y$10$nuqfVUAhpabMyFeSHUvA5.7LGiaykhetNgBMksxYYIEUupInAMonq', 'siswa'),
(228, 968, 'b039866a-480e-4569-80dd-4fdaae8ee1b2', '0168bb86-45b1-4a2f-8beb-b147e3bf05a7', NULL, 42, NULL, '0107240128', '252614095', NULL, NULL, 'GAVRILLA RAJOELINA PURBA', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'GOZALI PURBA KUSUMA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213120603100002', '2010-03-06', 1, '$2y$10$8bYKzuIp2CqH2u5LpPYpLuVFPajAT1PpkjqwK9Eg4nSKr5HVYQOgK', 'siswa'),
(229, 969, 'df902dc8-2543-11e4-a56d-53ab1b5a19db', '964b7cb7-a338-4574-814c-f5954ed784eb', NULL, 55, NULL, '0071279815', '23240075', NULL, NULL, 'SAIFUL FACHRIZAL HIDAYAT', 'L', 'L', 'BANDUNG', NULL, NULL, '-', NULL, NULL, 'YAYAT HIDAYAT', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3204091407070003', '2007-07-14', 1, '$2y$10$1YsTKOqfCh5l2tcG4IC.N.jrswPMQlGY7N9BAi/RuTo032yMrh2Fq', 'siswa'),
(230, 970, 'c03248bc-3dd4-11e5-a6f3-53d5886d8ef3', '9cce8b82-f77c-4be6-be06-449e5da19e86', NULL, 48, NULL, '0081781433', '24250037', NULL, NULL, 'FELISA ARAS', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'KARMID HARDIAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213196611080002', '2008-11-26', 1, '$2y$10$Jc9TEhZhpF/ZZ4YaTICQM.xAXi0VeOX7Qb0JD4wqvZAYDDTdXhZ9y', 'siswa'),
(231, 971, '744d0c66-3f05-11e5-97e6-53deb05b1e94', '619c6aff-d40d-4ac9-905d-243b54536570', NULL, 47, NULL, '0089042924', '24250030', NULL, NULL, 'VIKKY DWI SAPUTRA', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'WAHYU SAEPUDIN RAHMAT', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213120807080002', '2008-07-08', 1, '$2y$10$ta/PlTdLZzmOP4Zb4322Je9qJd0pIR3I.BqmEIH0d8smJJlcwUt.S', 'siswa'),
(232, 972, 'c5a92472-48fc-11e5-a6b1-5759e20dbb94', '9cce8b82-f77c-4be6-be06-449e5da19e86', NULL, 48, NULL, '0083279494', '24250033', NULL, NULL, 'Eisha Rahma Syafiya', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Andri Setiana', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213016503080001', '2008-03-25', 1, '$2y$10$EdLvClpDLO7URES9yMcWc.mQUo4UjAY6JfUTBuv8HuSev7PAEMFym', 'siswa'),
(233, 973, '3eda990c-3c37-11e5-833d-5b1bb02f5b85', '9cce8b82-f77c-4be6-be06-449e5da19e86', NULL, 48, NULL, '0084948895', '24250038', NULL, NULL, 'LUSIANI AGUSTIN PERTIWI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'DEDE WAHYUNI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213295708080001', '2008-08-17', 1, '$2y$10$OZ/c6MCxlE9Atv8qAZCkd.01GXS5eNhMHd0s0pm0xE9M.QBfk7F.G', 'siswa'),
(234, 974, '8072a99e-28ed-11e4-8203-57f1f80040b5', '6b9f4508-c1f9-46e0-b8a6-a03bacd4a708', NULL, 51, NULL, '0081135233', '23240024', NULL, NULL, 'ANDIN WULAN SUCI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ASEP JAMALUDIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213265707080001', '2008-01-17', 1, '$2y$10$pkWa9SAwejt7nIohWqBjNeQG/uipL2hGRfuH4mlhqLh2e4Wxo/KXK', 'siswa'),
(235, 975, '5f99c3d7-4803-489c-9346-58192b1ed2a0', 'f0dd79d4-b8fa-4815-b531-f65b873990ea', NULL, 43, NULL, '0093017899', '252614078', NULL, NULL, 'SANTI NOVIANSYAH', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'SANDI MARDIYANSYAH', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213124511090002', '2009-11-05', 1, '$2y$10$F0BERGVYGis34Rb5YV9W6OhXKXORENzdx7G0yXhXyGPe/Ewsp9hhG', 'siswa'),
(236, 976, '4151da0d-0719-4f53-9b93-58892389eb50', '0168bb86-45b1-4a2f-8beb-b147e3bf05a7', NULL, 42, NULL, '0095658772', '252614075', NULL, NULL, 'PARISYA ELSYAVIRA ZAHRA', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'PARIYANTO', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213035308090002', '2009-08-13', 1, '$2y$10$G/K/MKR.ZBwXe60VfvYvEezZOEUjZikKRWOiKeNMjxFJdkV66D8Pq', 'siswa'),
(237, 977, '7a57490f-273c-46f7-ab90-58a5dc4d6925', '1d260111-0d05-4b10-a525-1eeeef43fe6b', NULL, 45, NULL, '0101966881', '252614044', NULL, NULL, 'MERI KEYSHA MAHARANI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'YANA YULIO', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213015803100002', '2010-03-18', 1, '$2y$10$RuSxWAim5NaMxmsWkOAk2.YoTHZQfJEQVPezA5bDVchs80Hc2zwdm', 'siswa'),
(238, 978, 'b3631ef8-ba41-447f-b65f-59c6093b3346', 'f0dd79d4-b8fa-4815-b531-f65b873990ea', NULL, 43, NULL, '0095835217', '252614147', NULL, NULL, 'Handari Aryanto', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'YANTO', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213264708090001', '2009-08-07', 1, '$2y$10$Mxc8N.mRHNHHn/8hH7LckunNf3KQBso8rB83Ck6BweCnMsZDTKvWK', 'siswa'),
(239, 979, '36bad240-3cb0-11e5-9280-5b3e8a22961b', '2bcef959-7a06-4631-b590-f53fb9f93bc2', NULL, 49, NULL, '0081062552', '24250117', NULL, NULL, 'MUHAMAD ALY YUSUP', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ATING SARLAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213293008080002', '2008-08-30', 1, '$2y$10$5vh/A1hjNAGSoAV8ckunx.Gt7h25d8qEvpxeZjL6tFNiHiMLUBXIS', 'siswa'),
(240, 980, '4dda806c-30ca-11e4-90bd-5b73b4dfd48c', 'beea584c-74a1-4013-9a57-06b34248371b', NULL, 53, NULL, '0087944365', '23240093', NULL, NULL, 'INDRA AKBAR BUDIMAN', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'Odang Suyaman', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213040701080003', '2008-01-07', 1, '$2y$10$gQe/NRHsf/wvWI899wRyDejfcNnCvp99BS5IusoOIEtqXNNFSbnhC', 'siswa'),
(241, 981, 'db52bafa-3848-11e5-9f92-5bfabee7d8e0', 'e94587e3-0ece-43f2-b670-502c10beffe3', NULL, 50, NULL, '0094525287', '24250141', NULL, NULL, 'ORIZHA UTAMI BINTANG', 'P', 'L', 'Indramayu', NULL, NULL, '-', NULL, NULL, 'Sumedi', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3212256101090001', '2009-01-21', 1, '$2y$10$YOEdkQv.DRTOdgPeCueN0udgNg62CdOhjTfdxGczJgB776yFDqYBi', 'siswa'),
(242, 982, 'dd649569-c60d-4528-9a7c-5c9c28fc0fa2', 'f0dd79d4-b8fa-4815-b531-f65b873990ea', NULL, 43, NULL, '3116811404', '252614062', NULL, NULL, 'AURA ANZANI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'SAMI NUGRAHA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213264210100001', '2010-10-02', 1, '$2y$10$jwPDioNLdxp04ikBM1SzR.RhprhYkyZ0ZfZHI.0l6ksVFber4LJXa', 'siswa'),
(243, 983, '2521c233-0ab4-43fe-a5ea-5ee847d549a7', '1d260111-0d05-4b10-a525-1eeeef43fe6b', NULL, 45, NULL, '0097440597', '252614041', NULL, NULL, 'JUWITA PURWANTI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'AHIB MAMAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213297010090001', '2009-10-30', 1, '$2y$10$r7BNwSZVBkQhRDWnh1Zq3.uXEZq9Sni5facLyBPzdVnlkom/OVVRW', 'siswa'),
(244, 984, '39e3dbd6-2d90-11e4-a5c7-5f1be45bb1ee', '67d9edb1-f0e2-4693-992d-3e4cccd0e1a0', NULL, 40, NULL, '0072841736', '23240114', NULL, NULL, 'DEDI REFANDI', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'WAHYUDIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213293011070002', '2007-11-30', 1, '$2y$10$QH7r62pmfmqYZuLDbXEc/euuW3pLWAdk2ktawvfw9.mufPWAztsFu', 'siswa'),
(245, 985, '2ca71226-33d5-11e4-bf72-5f2b429811da', 'beea584c-74a1-4013-9a57-06b34248371b', NULL, 53, NULL, '0086737629', '23240086', NULL, NULL, 'AZKIA JULIA NATASYA', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'MAMAN SUTARMAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213016607080001', '2008-07-26', 1, '$2y$10$Gw3KRZ1/6UcbOK2BC3DxhOSsecuLL2x9yD4Qv97BauDHRJasKCYQq', 'siswa'),
(246, 986, '68d7ef82-44d6-11e5-9ad6-5f614231652c', '1d4ffd24-3a9e-4c03-afd8-a12ab295694a', NULL, 46, NULL, '0099259058', '24250080', NULL, NULL, 'MUHAMAD RAFI JANAWI', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'UCUP', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213290706090001', '2009-06-07', 1, '$2y$10$hUx7LNSPPdmLT.xs9r8e/eTzbJEgWzoWWgWoo/RbyY3SVBSY8MA3K', 'siswa'),
(247, 987, '4e770a29-80a2-49e0-954b-612aa4a26318', 'beea584c-74a1-4013-9a57-06b34248371b', NULL, 53, NULL, '3086555490', '23240095', NULL, NULL, 'M. RIJAL EFRIANO HARIRI', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'Asep Rahmat', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213291801080001', '2008-01-18', 1, '$2y$10$yu0.30OGKhaof1enpdH0YObY5mg0E/B.xYQgtehbcSlT4zjN1.CgC', 'siswa'),
(248, 988, 'fd755a0b-b195-4c17-aab5-627fc622cdf2', '005b0659-8a5e-4160-8830-9ddb091fbf00', NULL, 44, NULL, '0096775574', '252614117', NULL, NULL, 'ANGGA SABARUDIN AINUL YAQIN', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ISRA (Alm)', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213020911090004', '2009-11-09', 1, '$2y$10$BDMwKrrp9lKIVD0bMD6hqOo/xpJK4fO0rF72Aya97k6QFeRJwP3AO', 'siswa'),
(249, 989, '0e5f62f6-40cb-11e5-a5e7-631386da0900', '2bcef959-7a06-4631-b590-f53fb9f93bc2', NULL, 49, NULL, '0088660768', '24250106', NULL, NULL, 'DEVAN RYANSYAH HERYANTO', 'L', 'L', 'Bandung', NULL, NULL, '-', NULL, NULL, 'Asep Dadang Heryanto', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3273010510080002', '2008-10-05', 1, '$2y$10$9kBZQyTOYlTLLFTgOmNWju.Ol9jtJCJWHfmq3NU5Mr6DlsMBHMLEq', 'siswa'),
(250, 990, '08659740-2a0a-4017-9a70-636c732e61e6', '1d260111-0d05-4b10-a525-1eeeef43fe6b', NULL, 45, NULL, '0105089431', '252614150', NULL, NULL, 'NADIA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'DADAN MUHAMMAD RAMDAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213015102100001', '2010-02-11', 1, '$2y$10$AGnhePQkMC5eCl2RE2KxRORJ8TgQMs4PKITG0ZoIcCglsRvcYPWzq', 'siswa'),
(251, 991, 'b43f562a-56a8-11e5-bb67-63b4c6134f3c', '619c6aff-d40d-4ac9-905d-243b54536570', NULL, 47, NULL, '0092205920', '24250158', NULL, NULL, 'ALTHAF ABDULLAH', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'DEDI PURNOMO', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213290301090001', '2009-01-03', 1, '$2y$10$9mr411gA8kT4yNWkGiXwg.tE1oN2JORGc2Jg59cCDSM96e22Qo6Ce', 'siswa'),
(252, 992, '4d68171e-35e5-11e4-8b7d-63c2cf548984', '6b9f4508-c1f9-46e0-b8a6-a03bacd4a708', NULL, 51, NULL, '0077151107', '23240034', NULL, NULL, 'LANA HANURTIYAN', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'AE SUNTANA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213292212070001', '2007-12-22', 1, '$2y$10$/ryDf40ua8CWnl1Hedpd/enWAvsE9jdWoa1g54gyoec1aAvNHHWUG', 'siswa'),
(253, 993, '69cb772c-432f-11e5-b68b-63e1a49dd6f8', 'e94587e3-0ece-43f2-b670-502c10beffe3', NULL, 50, NULL, '0099494521', '24250151', NULL, NULL, 'ZAHRA CINDY REGINA', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'TARJANA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213267107090001', '2009-07-31', 1, '$2y$10$/rckZAzCDKTGi8GR1ZR0fOdc7MJedF4KFqAoMZ0YZR5SZ.la./OWC', 'siswa'),
(254, 994, 'ea98cf64-3181-11e4-9309-63f47afab3d5', '20a865c3-2d47-4cb4-90a4-885b04daa92e', NULL, 52, NULL, '0079383085', '23240021', NULL, NULL, 'SHAILA DESWITA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'SOLIHIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213294412070002', '2007-12-04', 1, '$2y$10$KPZ4zFjWocF.fSM.cs5IA.SsvGcOSoVSHhktqH.NqNaJo9FQVi/9e', 'siswa'),
(255, 995, 'd44ee46b-3a0c-4ad2-b9bd-6402fa0057fa', 'f0dd79d4-b8fa-4815-b531-f65b873990ea', NULL, 43, NULL, '0091353419', '252614074', NULL, NULL, 'NUROSIDAH SIFAUS SANIAH', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'SAHRUDIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213290107060036', '2009-05-16', 1, '$2y$10$o7a7.ItCvELfdvVUPBkWlugkLRQr4bXtM6NpsioasS860EunHFowK', 'siswa'),
(256, 996, 'bd074ede-df27-4091-a797-65118e7f650b', '0168bb86-45b1-4a2f-8beb-b147e3bf05a7', NULL, 42, NULL, '0104187910', '252614105', NULL, NULL, 'NAZIA JULIANTARI', 'P', 'L', 'CIANJUR', NULL, NULL, '-', NULL, NULL, 'DENI SUPRIADI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3203036507100006', '2010-07-25', 1, '$2y$10$WXdqEaPi2.R9Vqu5ixphvOq8kEC44m.G13Qn2vMStjYH/q0fTJEey', 'siswa'),
(257, 997, 'e34c87b6-1ade-447c-a822-66912364d02f', '005b0659-8a5e-4160-8830-9ddb091fbf00', NULL, 44, NULL, '0096442997', '252614142', NULL, NULL, 'Sakti Apriadi Purba', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'FITRIADI PURBA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213142304090001', '2009-04-23', 1, '$2y$10$qQ0naNaYwB0d2pX5VxJ1o.TxKVQULOq6FI1gUwB4Y3SwILWKbt.a2', 'siswa'),
(258, 998, '487481c0-3f67-11e5-b732-6725a1d627b2', '1d4ffd24-3a9e-4c03-afd8-a12ab295694a', NULL, 46, NULL, '0081655988', '24250074', NULL, NULL, 'FITRIA RAMADANI', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'WAWAN SETIAWAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296309080001', '2008-09-23', 1, '$2y$10$fQe.56Pdie7Vvr76y.APDuBZsjubO.5eVeJrxEZTI1fgRnqzc5EOi', 'siswa'),
(259, 999, 'bcac6285-7fb7-44d6-ad52-6746374ffd2b', '005b0659-8a5e-4160-8830-9ddb091fbf00', NULL, 44, NULL, '0109247565', '252614144', NULL, NULL, 'SYAFA ANIDYA ASIKIN', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'GINANJAR ROMLI ASIKIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213126501100003', '2010-01-25', 1, '$2y$10$VMbFcPLH2UHSgEOk3T0HVu1DbPFCAc8Kxr62FDASrxDYf2lBzFV9u', 'siswa'),
(260, 1000, '752801ea-25c0-11e4-a6ed-676bb6b5cdbf', '6b9f4508-c1f9-46e0-b8a6-a03bacd4a708', NULL, 51, NULL, '0073500705', '23240171', NULL, NULL, 'SYIFA NURAZIZAH', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'EBEN NURHIDAYAT', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213294807070002', '2007-07-08', 1, '$2y$10$1g32UX/LGo3VHB11SQc17OMBClcaiTisd1fx5IBxQrv429Vdxbzh.', 'siswa'),
(261, 1001, '1268d0fa-44e8-11e5-a569-6794763a0883', 'e94587e3-0ece-43f2-b670-502c10beffe3', NULL, 50, NULL, '0095112511', '24250144', NULL, NULL, 'Rinda Ananda Ade Putri', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Ade Rosidin', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213126307090001', '2008-06-23', 1, '$2y$10$PIEtfv/tPrswtRR9w2Q11eIiB1d45uV.paE88g2FYGc5wCrGGXuau', 'siswa');
INSERT INTO `tbl_siswa` (`id`, `id_user`, `dapodik_id`, `rombel_id_dapodik`, `user_id`, `kelas_id`, `jurusan_id`, `nisn`, `nis`, `rfid_uid`, `qr_code`, `nama_lengkap`, `jk`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `agama`, `alamat`, `no_hp_siswa`, `email_siswa`, `nama_ayah`, `nama_ibu`, `nama_wali`, `no_hp_ortu`, `pekerjaan_ayah`, `pekerjaan_ibu`, `status_siswa`, `foto`, `created_at`, `updated_at`, `nik`, `tgl_lahir`, `agama_id`, `password`, `role`) VALUES
(262, 1002, 'fd6ffb2c-3f40-11e4-8496-67a8e4d7bc09', '964b7cb7-a338-4574-814c-f5954ed784eb', NULL, 55, NULL, '0072280397', '23240065', NULL, NULL, 'LAISYAFHIRA AGHISTA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ASEP RUPYANDI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213297108070001', '2007-08-31', 1, '$2y$10$cE6zTsKFhLeVhQ/QSPvlp.Uk617VoNNwKxC02W.i00TWHjOzIhfG.', 'siswa'),
(263, 1003, '123eaa48-3cb7-11e5-8a18-67edf49f92e9', '2bcef959-7a06-4631-b590-f53fb9f93bc2', NULL, 49, NULL, '0098885424', '24250124', NULL, NULL, 'RYO ZAKARIA', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'HAMDI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213292112070001', '2007-12-21', 1, '$2y$10$ns50PjuMm4MbNqvlj.WeE.pvzuUnHWJ/nBr4TfBuMBESK7OlD0axW', 'siswa'),
(264, 1004, '012b56bd-2a7c-4827-a23e-67f2a8adb524', 'da90cc11-526e-48aa-b7ce-a9afbc0d8023', NULL, 41, NULL, '0096300952', '252614017', NULL, NULL, 'NISA NUSROTUL UMMAH', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'TAHTA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296209090002', '2009-09-22', 1, '$2y$10$M.lW.OICZi/B9J6jykuVLuQfE218JOr7UrQiR2ismuwA/vzxPNY2W', 'siswa'),
(265, 1005, '900ffda8-55d7-11e5-a9c2-6b055293989c', '9cce8b82-f77c-4be6-be06-449e5da19e86', NULL, 48, NULL, '0091340521', '24250160', NULL, NULL, 'INDI NURRAHMAYANI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ONDI SOPANDI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296803090002', '2009-03-28', 1, '$2y$10$dgqu.Wb9Bot8vgDejTfrQeBEizAsmxP7sqb2Td/ZlZ.iNB2jlR1dG', 'siswa'),
(266, 1006, '66f91dd0-4300-11e5-acb7-6b136bd77adb', '9cce8b82-f77c-4be6-be06-449e5da19e86', NULL, 48, NULL, '0099673165', '24250056', NULL, NULL, 'Tanissa Syifa Fuziyani', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Encep Adas', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213295803090011', '2009-03-18', 1, '$2y$10$qTb1Gzslvuj.URsAaRRkY.UwaZ78PlMx12RPQHlqN0C7Epn6frEDm', 'siswa'),
(267, 1007, '8373d3f0-c23b-4560-9021-6b3c3f23b68b', '20a865c3-2d47-4cb4-90a4-885b04daa92e', NULL, 52, NULL, '0082175258', '23240009', NULL, NULL, 'ENENG SUNENGSIH', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'Sukirno', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213264303080001', '2008-03-03', 1, '$2y$10$095spoJBLO5XrShVQt08SOAUfpSxkhCOt5mz1wbLIvl6YZ3xiBwkK', 'siswa'),
(268, 1008, '8ecea97a-3b70-11e5-bb04-6bd206ee71f3', '619c6aff-d40d-4ac9-905d-243b54536570', NULL, 47, NULL, '0095386642', '24250001', NULL, NULL, 'ALYA FELITSYA NAZHERA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'BENNY WIRIANA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213014505090001', '2009-05-05', 1, '$2y$10$r518ddSAGr2a3b.PcyKU3.MaV2I1tBMXIIMyr40LpSDnFV6KA68XS', 'siswa'),
(269, 1009, 'c5e84fcc-1057-4456-b7e1-6cac74165802', '0168bb86-45b1-4a2f-8beb-b147e3bf05a7', NULL, 42, NULL, '0103694457', '252614094', NULL, NULL, 'FAUZAN AZMI FIRDAUS', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'TOHA BAHARUDIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213291306100001', '2010-06-13', 1, '$2y$10$8t2S5lJWzfxdDlARo.376OD34Z7BC02z/1MULqwUImX8sejKOUbGK', 'siswa'),
(270, 1010, 'b8d81cab-f5d5-4447-ae6c-6de80beb80c9', '0168bb86-45b1-4a2f-8beb-b147e3bf05a7', NULL, 42, NULL, '3102920303', '252614113', NULL, NULL, 'Siti Najma', 'P', 'L', 'Sukabumi', NULL, NULL, '-', NULL, NULL, '', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296107090001', '2009-07-21', 1, '$2y$10$Kkegw.bO41cj6M5ykr9j3ee8v0RvNk1fLAJ1MSG04qVoao0Y0k8WW', 'siswa'),
(271, 1011, '47f5f782-3e74-11e4-a9c3-6f38e2685c9e', '67d9edb1-f0e2-4693-992d-3e4cccd0e1a0', NULL, 40, NULL, '0079050836', '23240116', NULL, NULL, 'FITRI YANI NUR AZIZAH', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'DADANG', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213297009070001', '2007-09-30', 1, '$2y$10$lY70s/bwY.S3Prrw8YTPEeeb15lmCpVCyC0erdZIzi7EIJu/DX2iO', 'siswa'),
(272, 1012, 'bec8f808-4c22-4645-bab4-75316f1696cb', '1d260111-0d05-4b10-a525-1eeeef43fe6b', NULL, 45, NULL, '0109652928', '252614042', NULL, NULL, 'KEYZA AUREL AZMI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'NANO', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296501100001', '2010-01-24', 1, '$2y$10$Jwp6mMvKPX6XF140WyfV4.6OGFi5mZAREWB6PuKNSIhQrJnnFNWUa', 'siswa'),
(273, 1013, '3659c264-4dec-11e5-8541-6f77479d8504', '1d4ffd24-3a9e-4c03-afd8-a12ab295694a', NULL, 46, NULL, '0081454235', '24250069', NULL, NULL, 'DAFFA PRADITIA', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'AGUS', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296710080001', '2008-10-27', 1, '$2y$10$uTV66.9QFTZlTtmLNxs8h.4p6OrEPU2.bq2vfOFsrec49jETsT8zC', 'siswa'),
(274, 1014, 'c78273e5-b96d-4966-834b-6ff19421c969', 'da90cc11-526e-48aa-b7ce-a9afbc0d8023', NULL, 41, NULL, '0105185493', '252614011', NULL, NULL, 'INEZ AIRA NURHIDAYAH', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'SAEPUL HIDAYAT', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213016503100001', '2010-03-25', 1, '$2y$10$ka00eT.S8idyvatEnJXnhu01ITic/a9yUfi.yQsKN8hzciTSdf4G2', 'siswa'),
(275, 1015, '914b352c-03f5-4c3c-9304-707445a16232', '1d260111-0d05-4b10-a525-1eeeef43fe6b', NULL, 45, NULL, '0104117251', '252614052', NULL, NULL, 'TAZKIYA NADA', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Jamalludin', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213195205100002', '2010-05-12', 1, '$2y$10$WE9YHKga/BQc49cO1uIYUe0sF3NdDomyhRT2YTCptlcuVIOk1O4xi', 'siswa'),
(276, 1016, '75da3282-2033-44d2-b53e-7157c6857586', '1d260111-0d05-4b10-a525-1eeeef43fe6b', NULL, 45, NULL, '0096844548', '252614047', NULL, NULL, 'Nura Nuraeni', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'SUTARNA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213124905090012', '2009-05-09', 1, '$2y$10$yYHGByrph4ARrL4DSmppaudTy/rR.E/dBNxRhGAV1hpY4/1FRsoza', 'siswa'),
(277, 1017, 'df9c5f6f-695e-4828-81f3-72520ba835a4', '0168bb86-45b1-4a2f-8beb-b147e3bf05a7', NULL, 42, NULL, '0105485252', '252614106', NULL, NULL, 'PEBRI ALDIANSYAH', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'SAHLAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213292402100001', '2010-02-24', 1, '$2y$10$nNBAh7YJZI3To7.WC4PyCu6FThYX5XTlXq8s09Y7cYje5Wd4pNOsy', 'siswa'),
(278, 1018, 'c24f26f8-331f-11e4-ba47-7316611691bd', '20a865c3-2d47-4cb4-90a4-885b04daa92e', NULL, 52, NULL, '0074275385', '23240020', NULL, NULL, 'RIVA MAORA FITRIYANI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'JUJU KARMAN WIJAYA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213126509070002', '2007-09-25', 1, '$2y$10$B5VCKMdcg5Tw3vvhkxQ4peW/jWEPQrsBddE41dHYit2wdVGVkZwd.', 'siswa'),
(279, 1019, '1d835398-507d-11e5-b02e-73353bde7f38', '2bcef959-7a06-4631-b590-f53fb9f93bc2', NULL, 49, NULL, '0082407603', '24250102', NULL, NULL, 'ALI M SIDIK', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ATANG SURYANA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213290205080001', '2008-05-02', 1, '$2y$10$ZE/NS8v7GAODlyDfvF0g4eU9G7950W6/DxaQB58jnHXix/xEtIzo.', 'siswa'),
(280, 1020, '6745720c-8ca8-46f6-8eb8-7355ffa55b8d', '964b7cb7-a338-4574-814c-f5954ed784eb', NULL, 55, NULL, '0089088553', '23240070', NULL, NULL, 'REISHA PUTRI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'JAJANG', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213026904080001', '2008-04-29', 1, '$2y$10$cItNrJN1LtKH26r8CB4nGunxq9WtSiq9psyEEaa.i5Ndvwua/q9OG', 'siswa'),
(281, 1021, 'cb456e0e-5851-11e5-9e2b-735b5c1e948a', '9cce8b82-f77c-4be6-be06-449e5da19e86', NULL, 48, NULL, '0099238794', '24250159', NULL, NULL, 'AZMI AGOESTINI', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'AGUS OMAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213292705090001', '2009-05-27', 1, '$2y$10$ZNjPvv3tkQMtG9T165EP0.QRkM.5MdtEh4kg2g2UfcmmvmPwqLFca', 'siswa'),
(282, 1022, '4c941552-4b94-11e5-86bc-7362149e01ed', '2bcef959-7a06-4631-b590-f53fb9f93bc2', NULL, 49, NULL, '0094657544', '24250125', NULL, NULL, 'SABA NABILA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ENED', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213191505090001', '2008-05-15', 1, '$2y$10$Ra5ortjupjwH2a5yxTvyP.CqTHhq3AqsSTZvmnEXUWVeFenetd4fe', 'siswa'),
(283, 1023, '0b5c8002-090e-45f4-9a4a-737cbdc3ef8a', '1d260111-0d05-4b10-a525-1eeeef43fe6b', NULL, 45, NULL, '3108674843', '252614035', NULL, NULL, 'DINDA PUTRI NURPITASARI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ARIS SETIAWAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213264309100002', '2010-09-03', 1, '$2y$10$YVNBlBd5j0Hg.8dhNw0qsuaKHs/2ILaFU1sQvWx6fpMIfD.IlF8BW', 'siswa'),
(284, 1024, '807ae8c6-4231-11e5-8cdd-7380166316d6', '1d4ffd24-3a9e-4c03-afd8-a12ab295694a', NULL, 46, NULL, '0097673325', '24250089', NULL, NULL, 'RIDWAN PERMANA', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'DADAN S MANAP', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213292908080001', '2008-08-29', 1, '$2y$10$QW94rxbWGPptpqOtq0gIMeMAXiPKvvdGJre/0pNIOZw/OfP.gbeie', 'siswa'),
(285, 1025, '6e5e8615-114f-41ed-a686-73ba9991233a', '005b0659-8a5e-4160-8830-9ddb091fbf00', NULL, 44, NULL, '0091447807', '252614131', NULL, NULL, 'MUHAMMAD RAMADHAN', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'YUSUP TARYUDIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213120109090009', '2009-09-01', 1, '$2y$10$zkdyqZ.7zowFJVZCumSRK.neLYMq.DaiAM.wt7WWuL1KTfUJ5SQky', 'siswa'),
(286, 1026, '87871362-aac5-470c-8e81-74c7a7cb78ce', '005b0659-8a5e-4160-8830-9ddb091fbf00', NULL, 44, NULL, '0099057877', '252614130', NULL, NULL, 'MUHAMAD RIZKI DEFIAN', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'OMAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213121003090001', '2009-03-10', 1, '$2y$10$bmUlz3B/4OOAu2HcIPt3sej7q/zpKx3gdhJLOT7jbd5ORd4k1O582', 'siswa'),
(287, 1027, '4d4d3d44-ac4d-43ff-b3c7-74ebc6ae3232', '005b0659-8a5e-4160-8830-9ddb091fbf00', NULL, 44, NULL, '0106771791', '252614122', NULL, NULL, 'FERDI ALDIANSYAH', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'DEDE DARNI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213122103100002', '2010-03-21', 1, '$2y$10$tEnYDbMuGxbUkraTvmQTDesnDsgwzjXkCpY3Z95tNBp.PIj7.WPSq', 'siswa'),
(288, 1028, '624d580f-f9bc-420b-b27a-75fc7913bb26', 'da90cc11-526e-48aa-b7ce-a9afbc0d8023', NULL, 41, NULL, '0104323260', '252614004', NULL, NULL, 'AURELIA SALSABIL PUTRI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'BANGBANG YOGI SAPUTRA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296903100001', '2010-03-29', 1, '$2y$10$SYM4AM8zkbnJkFTaVXxfKOa4TwYEBqMzuibeok8h664yexIISYuiG', 'siswa'),
(289, 1029, '193f9c9a-5854-11e5-8597-770c2c7fa210', '1d4ffd24-3a9e-4c03-afd8-a12ab295694a', NULL, 46, NULL, '0097793455', '24250078', NULL, NULL, 'MAZRIL IMRON', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'DODI HERYANDI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213292007090012', '2009-07-20', 1, '$2y$10$dhxlyApEg8yh06UZc0YPEOOYM8tuJPpWD9tcVzqUnqvGfqJsCUMwa', 'siswa'),
(290, 1030, '53b60370-432b-11e5-bccc-7711b17f9ef2', '1d4ffd24-3a9e-4c03-afd8-a12ab295694a', NULL, 46, NULL, '0098576761', '24250079', NULL, NULL, 'MOCH. ARI KURNIAWAN', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'CARMAN SUPENA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213260201090001', '2009-01-02', 1, '$2y$10$YdSHXBvj3lZ5oUsYwprqL.1gSDGdAoZWS5sNAcl4oaRrU7kziFBXC', 'siswa'),
(291, 1031, '0a9da0a8-337d-11e4-882a-771a5455fb37', '964b7cb7-a338-4574-814c-f5954ed784eb', NULL, 55, NULL, '0081984336', '23240076', NULL, NULL, 'SASYA APRIANI', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Dede Martono', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213125007080001', '2008-07-10', 1, '$2y$10$eAx0riS0SWzee88zSQZDu.YxM5cGEbD3Km3OtTky7yAtL0sG8r.s6', 'siswa'),
(292, 1032, 'd5ff700c-48c4-11e5-a366-774abed2a386', '619c6aff-d40d-4ac9-905d-243b54536570', NULL, 47, NULL, '0092520482', '24250003', NULL, NULL, 'CANTIKA HANA MAIDA PUTRI ANDRIANI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'Yadi Haryadi', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213265704090002', '2009-04-17', 1, '$2y$10$iwtefqbOXFA5AKiDLP6Wa.FXxiy9zgwe/FJniYAPEOtf58WrMfoKC', 'siswa'),
(293, 1033, '182904c6-2df4-11e4-9b05-77557a69084c', '6b9f4508-c1f9-46e0-b8a6-a03bacd4a708', NULL, 51, NULL, '0079435085', '23240045', NULL, NULL, 'SARAH ZIPANI AULIA', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Asep Yudiama', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213124101080001', '2007-11-28', 1, '$2y$10$q/fd08vjxz7hBkwy5BWR3e8W/GHqcPVP24fy0sufibWo34jHEB0fS', 'siswa'),
(294, 1034, '13d4f9d0-dde7-11e5-bd99-775cefc9cfa8', '9cce8b82-f77c-4be6-be06-449e5da19e86', NULL, 48, NULL, '0086458037', '24250162', NULL, NULL, 'NAYLA ARIFIANTI PUTRI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ASEP ARIFIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3217015011080002', '2008-11-10', 1, '$2y$10$dxe585/M37eSaxwmPtukYOOwWSIFXBWexAng0wt5WT8IPZ1X0hEii', 'siswa'),
(295, 1035, '5812ab55-45a0-4fff-8288-799a17acdcd9', 'f0dd79d4-b8fa-4815-b531-f65b873990ea', NULL, 43, NULL, '0093094481', '252614057', NULL, NULL, 'AFWAN FAHRUDIN', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'MAHPUDIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213292307090001', '2009-07-23', 1, '$2y$10$CpQIIpXeKmI8rMrL7i.sOOuQ/fhkviSKs80A4of6DL5Yy4LNbMLZK', 'siswa'),
(296, 1036, 'cbf92798-4558-11e5-aaff-7b05dba36e9c', '619c6aff-d40d-4ac9-905d-243b54536570', NULL, 47, NULL, '0082029069', '24250009', NULL, NULL, 'FELLA LAUDYA PUTRI EFENDI', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Pepen Efendi', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213125211080002', '2008-11-12', 1, '$2y$10$WgUG7SHHH6ur94s.0VGfiOjgNnY7bUsTXfd4tGHKYoKeyx/L0r3jm', 'siswa'),
(297, 1037, '28ab50b2-2799-11e4-80a6-7b46b3801d1f', '67d9edb1-f0e2-4693-992d-3e4cccd0e1a0', NULL, 40, NULL, '0082413282', '23240119', NULL, NULL, 'IDEA NAZRIEL ALFAHREZY', 'L', 'L', 'BANDUNG BARAT', NULL, NULL, '-', NULL, NULL, 'IWAN HERAWAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3217010903080001', '2008-03-09', 1, '$2y$10$D2IEz7H5PGCpvXGSXDi6suagXHu1Gt4J.92en1zh0lFNXDZX2zCNi', 'siswa'),
(298, 1038, '7f6bd146-4620-11e5-ace9-7b69a7535682', '2bcef959-7a06-4631-b590-f53fb9f93bc2', NULL, 49, NULL, '0094230397', '24250109', NULL, NULL, 'Dony Jamiatul Pikry', 'L', 'L', 'Garut', NULL, NULL, '-', NULL, NULL, 'Abdul Rohman', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3205070304090003', '2009-04-03', 1, '$2y$10$kGzPmXPKG6mVtpNtIyXFteB0Mo6KvUmS6ysDEbnicWJDgZ2hQqzD2', 'siswa'),
(299, 1039, '53ec72f8-44e7-11e5-8b3d-7b75f05ae28f', '005b0659-8a5e-4160-8830-9ddb091fbf00', NULL, 44, NULL, '0082211913', '252614127', NULL, NULL, 'Moch. Arul Rifanka', 'L', 'L', 'Sumedang', NULL, NULL, '-', NULL, NULL, 'Wawan Saepudin', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213120211080002', '2008-11-02', 1, '$2y$10$WxS0.cQTYCXEFz2Ujiqjp.a63jAm9dKlebK91BldhL.TSbg8VC7J.', 'siswa'),
(300, 1040, '952c4fea-3c82-11e4-80c5-7bb962d05343', '20a865c3-2d47-4cb4-90a4-885b04daa92e', NULL, 52, NULL, '0087019378', '23240004', NULL, NULL, 'DEZAN FAHREZI', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'TETEN LESMANA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213291807090001', '2008-07-18', 1, '$2y$10$klvV0njDc0mYsl7WobfkK.P1qBTNk5AG1oEt8EOzyh9RczcmG4GPe', 'siswa'),
(301, 1041, 'd4cd9988-2b2f-11e4-84f5-7bfeefddbf18', 'beea584c-74a1-4013-9a57-06b34248371b', NULL, 53, NULL, '0081305065', '23240091', NULL, NULL, 'FAZRIA FIRDAYANI PUTRI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'KOMAR', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213266901080003', '2008-01-29', 1, '$2y$10$bnX4E7H9vs1/cGTBRzpJPuffaPQVc6CwNbdQK34qm3ajnUQlRBFR2', 'siswa'),
(302, 1042, 'bd0300f3-a0b1-4845-b91e-7dd0f97dd7e0', '005b0659-8a5e-4160-8830-9ddb091fbf00', NULL, 44, NULL, '0094084951', '252614151', NULL, NULL, 'SATRIA SUGIH LESMANA', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'CECEP LESMANA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213011211090001', '2009-11-12', 1, '$2y$10$UD7ShD0vHXh.BhYLEKX87ugLRomP/vTPRY0O56V5wCJ73Y56kFNmS', 'siswa'),
(303, 1043, 'f74ec21a-380a-11e4-972e-7f3518ef5bb7', 'beea584c-74a1-4013-9a57-06b34248371b', NULL, 53, NULL, '0075009227', '23240105', NULL, NULL, 'TSABITAH FAUZIYAH', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'BUDI HENDRI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213294108070003', '2007-08-01', 1, '$2y$10$YdE1QIPpbmGYMMTR6p10ZuGbuOTlMPtRGMiNRuJrMY1vktn7vetoi', 'siswa'),
(304, 1044, '9bbc9816-3281-11e4-84c0-7f8d0bd5dcc4', '6b9f4508-c1f9-46e0-b8a6-a03bacd4a708', NULL, 51, NULL, '0073928329', '23240170', NULL, NULL, 'ANISA AGNI HIDAYAH', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'DEDI RUKMANA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213295811070001', '2007-11-18', 1, '$2y$10$hevbpwB5HkzTrk2IL38g1OogeHmMPAXDLV12fWj.t7bNwwLR.iMVi', 'siswa'),
(305, 1045, '3eb32bb6-296d-11e4-9883-7f90ee552db0', '68edb4cb-5f7a-4dfb-b043-d5f663a2f21b', NULL, 54, NULL, '0086866263', '23240163', NULL, NULL, 'ZAQIA RAHMAWATI', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Rohendi', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296303080001', '2008-03-23', 1, '$2y$10$aacyrRp3SlvxQzOFFG5tz.cI4om1w9rJnLYQo4.7kfJzTQ1AZjD66', 'siswa'),
(306, 1046, '0fcc3e16-4ce2-11e5-85b3-7fb192beb4bc', '619c6aff-d40d-4ac9-905d-243b54536570', NULL, 47, NULL, '0095597263', '24250026', NULL, NULL, 'Saskia Meika', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Hendyana', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213124502090015', '2009-02-05', 1, '$2y$10$6Ss5svW1LrKMdHWjsZMaX.Ol.30qcwHw0s2VY1DZt/S4e4/tLhxhe', 'siswa'),
(307, 1047, '1e23d9f8-3445-11e4-91f5-7fb31597fd53', '20a865c3-2d47-4cb4-90a4-885b04daa92e', NULL, 52, NULL, '0096120139', '23240023', NULL, NULL, 'WAINNASYA AYU APRILLIA', 'P', 'L', 'TULANG BAWANG', NULL, NULL, '-', NULL, NULL, 'IWAN KURNIAWAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '1801084404090004', '2009-04-04', 1, '$2y$10$k2IjMZZnACAb/dTlIn0QdOOZW65XV0TaRJ5XJe98bF2MqRyEXhGVi', 'siswa'),
(308, 1048, '2109b9d8-0de2-46f3-b207-7fed9aa3f3ca', '1d260111-0d05-4b10-a525-1eeeef43fe6b', NULL, 45, NULL, '0105941100', '252614048', NULL, NULL, 'RESYA APRYLIANI HERMAWAN', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'APIN HERMAWAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213126804100011', '2010-04-28', 1, '$2y$10$CaHOucjL.YZ45cwnoq7ATu/fRI.SmRTUB0QmgjY24VwpInKbmoOK2', 'siswa'),
(309, 1049, '3358f7fe-3f4e-11e4-845d-7ff1309ce42b', '67d9edb1-f0e2-4693-992d-3e4cccd0e1a0', NULL, 40, NULL, '0074760306', '23240128', NULL, NULL, 'RASYA MUHAMMAD AL-FATHIR', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'A.KARTIWA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213291812070001', '2007-12-18', 1, '$2y$10$EbgDjwp1xlpcTglx1Ir..uvn1wuJelQRwaD136hbTT2XUmKdKj9j.', 'siswa'),
(310, 1050, '20d597cf-4e77-402a-90b6-807061120f81', '68edb4cb-5f7a-4dfb-b043-d5f663a2f21b', NULL, 54, NULL, '0072295514', '23240147', NULL, NULL, 'ELIS SITI RAHAYU', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'MAMAN TARYAMAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213294412070001', '2007-12-04', 1, '$2y$10$bv4hLJ17gigpWtsfZWFhaeZTteUacAFpAPkjm5aVcIEQlSPf5F36K', 'siswa'),
(311, 1051, '057cc3c7-af64-4834-a024-81510528d0cd', '005b0659-8a5e-4160-8830-9ddb091fbf00', NULL, 44, NULL, '0092068468', '252614138', NULL, NULL, 'RIZKI ANDREYANSAH', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'JAJANG SUDRAJAT', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213121605090001', '2009-05-16', 1, '$2y$10$G8rfHTeCztSyvBsk9Crebe1Rtvz4oijjfdrtqGXBt6dSrU4EbFAW6', 'siswa'),
(312, 1052, '14fdb2ce-3b59-11e5-87fa-83a5d69c5945', '9cce8b82-f77c-4be6-be06-449e5da19e86', NULL, 48, NULL, '0089116346', '24250051', NULL, NULL, 'SHANIA OKTAVIANI JAMIL', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'UJANG SUTISNA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296410080001', '2008-10-24', 1, '$2y$10$B7ent0m2139vP2iSs8CwDO9P8MtvkDW5X71SQb0lA6JfSiZkxdKzS', 'siswa'),
(313, 1053, '81a06828-a581-447b-bf6e-83d24a64e38c', '1d4ffd24-3a9e-4c03-afd8-a12ab295694a', NULL, 46, NULL, '3089217263', '24250066', NULL, NULL, 'AYU PERTIWI', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Endang Hidayat', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213266808080003', '2008-08-28', 1, '$2y$10$hYKbLqNz7zYnjPcbDMWPSu6n.kC308gwUJhWoolZFB.0sdffRKjaq', 'siswa'),
(314, 1054, 'a7a15fed-35ba-40c8-ac09-85d121434242', '9cce8b82-f77c-4be6-be06-449e5da19e86', NULL, 48, NULL, '0097614563', '24250046', NULL, NULL, 'PUTRA ARDIANSYAH PRATAMA', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'YAYANG JUNI ARDIANSYAH', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213292907090001', '2009-07-29', 1, '$2y$10$8//E7dajNkmeBRBBj2LmZORtmSd6Rh2Vdfa.smdYTdvgTBSN2SJhq', 'siswa'),
(315, 1055, '4ddc585a-bd3c-4533-98ee-86f6dfd8daed', '005b0659-8a5e-4160-8830-9ddb091fbf00', NULL, 44, NULL, '0108237855', '252614118', NULL, NULL, 'DERIN PUTRA CANDRA', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Danni Candra', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213123008100003', '2010-08-30', 1, '$2y$10$Nn7TXo1gsM22lQ57sYbF/uCQ/aYsBww7TEJOBXffUQEwAo5/2tbGi', 'siswa'),
(316, 1056, '4a15e236-867b-46e5-9324-873ea582bb05', 'f0dd79d4-b8fa-4815-b531-f65b873990ea', NULL, 43, NULL, '0094793367', '252614067', NULL, NULL, 'KOMALA FITRI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'DADANG', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296009090001', '2009-09-20', 1, '$2y$10$zO1zaZ4tpt7LUHsCfjgSXuRDfbe0CrrQe4NR.Rvc7RhHzaNpOuKxW', 'siswa'),
(317, 1057, 'd7294956-06eb-4d8d-bf6c-875248d3a430', '20a865c3-2d47-4cb4-90a4-885b04daa92e', NULL, 52, NULL, '0069480093', '23240012', NULL, NULL, 'MAMAY MUHAMMAD KUSTIWA', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'MOMOD', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213190705060003', '2006-05-07', 1, '$2y$10$3zr8BESqL8zCMQjsmKwbVuloJTa56aHgDb3LaHYlSBTJhMgqvoAy6', 'siswa'),
(318, 1058, 'bf973a7c-94a4-427e-a47b-87e0721a5647', '1d260111-0d05-4b10-a525-1eeeef43fe6b', NULL, 45, NULL, '0096025919', '252614056', NULL, NULL, 'YUDHISTIRA DWI MAHESA', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ENAN SUSANTO', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213291804090001', '2009-04-18', 1, '$2y$10$uCcbZ7r87Z7l.tHVWOKSAOvOHxt/27iBUzeBXfqFVV9Yhmb9Lcg12', 'siswa'),
(319, 1059, '62c89917-6284-44ca-959f-88ef57396b2d', '1d4ffd24-3a9e-4c03-afd8-a12ab295694a', NULL, 46, NULL, '3097257455', '24250092', NULL, NULL, 'RIZWAN', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, '', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213262903090001', '2009-03-29', 1, '$2y$10$suw5AGMVClymi1GgCBil4OIex.6SaTLErEJIHlhp73ze92JyIjsW2', 'siswa'),
(320, 1060, 'c375b34c-6798-11e3-9a1d-8b546d8af901', '619c6aff-d40d-4ac9-905d-243b54536570', NULL, 47, NULL, '0073286741', '24250021', NULL, NULL, 'NOVI AYU LESTARI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'AGUS SETIAWAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213124608060001', '2006-06-15', 1, '$2y$10$MjBemJE55pOwjGtcMtRJLePnnGzgDn1eFUQtDYo3aanDZHoZRjhX.', 'siswa'),
(321, 1061, '9b6f196e-253a-11e4-bbc4-8b75c743124b', '964b7cb7-a338-4574-814c-f5954ed784eb', NULL, 55, NULL, '0087579173', '23240080', NULL, NULL, 'SYALLUNA THAHARA PUTRI', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Iwan Kustiawan', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213014702080001', '2008-02-07', 1, '$2y$10$McEo83qCKpf0MWQmjSCeTOjlACuf1RMBaYj.Ep25S8.75FU83/06u', 'siswa'),
(322, 1062, 'c5602764-dff9-4362-91de-8f2b3b25ba05', '005b0659-8a5e-4160-8830-9ddb091fbf00', NULL, 44, NULL, '0093473086', '252614143', NULL, NULL, 'SIGIT NURFAZRI', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'TEDY', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213122611090002', '2009-11-26', 1, '$2y$10$2PRBh3EoxHAxy/Yib4YKFu1d0NsNQ.NoFx//x09q0M5DcRFrCg.Pu', 'siswa'),
(323, 1063, 'a8ecc8be-2f82-11e4-a891-8f3f465dbd60', 'beea584c-74a1-4013-9a57-06b34248371b', NULL, 53, NULL, '0072414816', '23240100', NULL, NULL, 'NICKY FARHAN SALUNA', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'DASEP', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213292604070001', '2007-04-26', 1, '$2y$10$ugAgxY.spyAZF6mUehZfr.k4BXapmSZhjOE1UanvHenCU576U0bpa', 'siswa'),
(324, 1064, '709bc1da-40c1-11e5-89d5-8f946566cbb7', '619c6aff-d40d-4ac9-905d-243b54536570', NULL, 47, NULL, '0099701626', '24250022', NULL, NULL, 'PUTRI DARA AGESTA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'KALPIYAN SUGANDI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213295908090001', '2009-08-19', 1, '$2y$10$usyFKyaZCsrPDMsWzDuZTuexVA2FyuqDSHCJfMT46YYeUOp9hWKqO', 'siswa'),
(325, 1065, '0ddf3050-a827-11e4-8ddb-8fe5581270e2', '6b9f4508-c1f9-46e0-b8a6-a03bacd4a708', NULL, 51, NULL, '0079315951', '23240043', NULL, NULL, 'RASYA SALSABILA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'RAHYAT', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213126506070009', '2007-06-25', 1, '$2y$10$Hg3iAEDmEMqpv72Z9Zqex.eNfBw80gnCSLBkUQoNyiwzcWPEttKD2', 'siswa'),
(326, 1066, 'e75d249e-2cb3-11e4-9fda-8ffecf32f3f3', '68edb4cb-5f7a-4dfb-b043-d5f663a2f21b', NULL, 54, NULL, '0064407588', '23240143', NULL, NULL, 'DESIANA SUCIATI', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'AUM DAMAT', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296012060001', '2006-12-20', 1, '$2y$10$5HBl/mYIqeX5dQJAOcugfetwp3SQrUXwCVIgSABIxUIWK3IiUEn7y', 'siswa'),
(327, 1067, '486e2b10-29b3-11e4-82d8-932eb7c29450', '6b9f4508-c1f9-46e0-b8a6-a03bacd4a708', NULL, 51, NULL, '0078112470', '23240036', NULL, NULL, 'LUCKY FAUZIA SATYA', 'L', 'L', 'SUBANG', '2007-12-06', NULL, '-', '085155232366', NULL, 'YAYAN ROHYAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213290612090011', '2007-12-06', 1, '$2y$10$Rav7384yB98WwmJNSBYQjuUpt4uHkmQy4GVDz9VdZEEaJk3BGMc6a', 'siswa'),
(328, 1068, '22cb68e6-2936-11e4-928c-938f72924c56', '68edb4cb-5f7a-4dfb-b043-d5f663a2f21b', NULL, 54, NULL, '0086530445', '23240145', NULL, NULL, 'DINI FITRIYANI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'PEPEN SOPANDI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213264308080002', '2008-08-03', 1, '$2y$10$0jF/l8U1ckMJJzolaCQDHO.drf438jitBqX3go1wQR/UycIF9iOs.', 'siswa'),
(329, 1069, '67ff5d5c-25c9-11e4-9757-939172fe519c', '964b7cb7-a338-4574-814c-f5954ed784eb', NULL, 55, NULL, '0075060004', '23240081', NULL, NULL, 'YULIANTI', 'P', 'L', 'Bandung', NULL, NULL, '-', NULL, NULL, 'ADE MEMED WAHYUDI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3217015511070010', '2007-11-15', 1, '$2y$10$HTo9uV0TNJ4FfC53mHGo7.IGm843.4sc3blnNzfr1qnVVIxpPPyh6', 'siswa'),
(330, 1070, '3f12216c-3f06-11e4-b7c0-93976badcd0e', 'beea584c-74a1-4013-9a57-06b34248371b', NULL, 53, NULL, '0071447816', '23240092', NULL, NULL, 'FIKRI RAMDANI', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'DEDE', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213291811070012', '2007-11-18', 1, '$2y$10$N4teMu05IbA9zYKf9BVc.Ol9iKI4kpYFcwiSHVL24FVwKPykanutm', 'siswa'),
(331, 1071, 'c34888c2-2a7a-11e4-be19-93ae80faebc9', '6b9f4508-c1f9-46e0-b8a6-a03bacd4a708', NULL, 51, NULL, '0072145072', '23240040', NULL, NULL, 'NENG MAESAROH', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'ASEP ASIM ARIPIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213264606070002', '2008-06-06', 1, '$2y$10$Kq0hQfxwOwtIh6oRX4isxukpSV7vEHF2GjUJLslgomoxgi8uDYYJ6', 'siswa'),
(332, 1072, '0d3d766e-296c-11e4-9c4b-93b5f682023a', '20a865c3-2d47-4cb4-90a4-885b04daa92e', NULL, 52, NULL, '0075373657', '23240022', NULL, NULL, 'VINA DAMAYANTI', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Ujang Kuswanto', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296505070001', '2007-05-25', 1, '$2y$10$dZVInHeTmFjarD0/awplIOZZLlyO/dPEZpqsEIyZKMYNQZO3EcNiy', 'siswa'),
(333, 1073, 'cc22bf66-55a2-4b5b-826a-950fcc962a10', '1d4ffd24-3a9e-4c03-afd8-a12ab295694a', NULL, 46, NULL, '3093288209', '24250062', NULL, NULL, 'Abdu Rofi Djalalludin', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Sahudin', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213260904090003', '2009-04-09', 1, '$2y$10$UQ793mK/UQMkVKMmfEUZHOIMw7vk8YEatxuuwSGVTC69NkcdU3r22', 'siswa'),
(334, 1074, '0db86141-5c3b-4e53-b448-95f13f873b5f', '964b7cb7-a338-4574-814c-f5954ed784eb', NULL, 55, NULL, '0072755466', '23240056', NULL, NULL, 'AZZAHRA AULIA RAMADHANI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'JOKO SUMARNO', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3174084710071003', '2007-10-07', 1, '$2y$10$nnnWaYW7WYfXWXViMnb9lO0c0qOUbobFk6dby3UnDAwu23ZFOkAYe', 'siswa'),
(335, 1075, '99c39cf4-4a76-11e5-9d06-971c354799c6', '2bcef959-7a06-4631-b590-f53fb9f93bc2', NULL, 49, NULL, '0092685403', '24250119', NULL, NULL, 'NOVA ANGELA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'SAPRI CEPI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213267108090001', '2009-08-31', 1, '$2y$10$1VcEHKIl3bjw9PqOLLKU/uSdghmVCqjNpx97lFjN16bUN/AlUDRZq', 'siswa'),
(336, 1076, 'f1c17cca-2cc5-11e4-a513-977c2aec873e', '964b7cb7-a338-4574-814c-f5954ed784eb', NULL, 55, NULL, '0072897380', '23240079', NULL, NULL, 'SYAIDAH NURAENI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'AMAT RUHIMAT', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213264305070001', '2007-05-03', 1, '$2y$10$nj/T4T92pq4oZX5gw50X9OHZpUlxS11ygc5hUp8yhHJikvBi60GI.', 'siswa'),
(337, 1077, 'a1d25e61-8b20-4d70-85a6-9935c9d1db53', '20a865c3-2d47-4cb4-90a4-885b04daa92e', NULL, 52, NULL, '0078767293', '23240084', NULL, NULL, 'Shalma Kania Iswari', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Nana Ruhayatna', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296512080002', '2007-12-25', 1, '$2y$10$r9kMj6dcES8PJ27JM8Zc6OlXqAx871Jps36Rkyk4wFWNR0MoHXjEG', 'siswa'),
(338, 1078, '0d5de959-fab3-4a35-a427-99fc07882505', 'da90cc11-526e-48aa-b7ce-a9afbc0d8023', NULL, 41, NULL, '0094445421', '252614014', NULL, NULL, 'NAZWA AZHARY', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'ASEP YAMAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213262912090002', '2009-12-29', 1, '$2y$10$k.tImeSW4HcCdiAC3Qf7pu0.SUUg6YZZHZBlMNzo8zXuZIAlDAfo6', 'siswa'),
(339, 1079, '15bf79ff-c6c8-4456-b752-9b7bb9cf80db', '005b0659-8a5e-4160-8830-9ddb091fbf00', NULL, 44, NULL, '0094259229', '252614132', NULL, NULL, 'NENDI SUPRIATNA HIDAYAT', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'TONI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213292302090001', '2009-02-23', 1, '$2y$10$JXfIE7vHM2Qn4rDKdpwOsO/eZ9ZRckbxTSp13GRRGYKeF6eyTfABu', 'siswa'),
(340, 1080, '0405da26-4fe7-11e5-af5b-9be5bddb72c2', '619c6aff-d40d-4ac9-905d-243b54536570', NULL, 47, NULL, '0088808160', '24250012', NULL, NULL, 'HENDRAWAN', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'E SUNANDAR', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213290104040002', '2008-04-01', 1, '$2y$10$3ZRfaB38DjnZ4GPbepodte2cqtPq2ryJzw1Wkc0TeTion.v72YKA6', 'siswa'),
(341, 1081, 'f9bf63f0-3c38-11e5-8a6e-9be819468115', 'e94587e3-0ece-43f2-b670-502c10beffe3', NULL, 50, NULL, '0086969806', '24250136', NULL, NULL, 'MUHAMAD AKBAR ALQODRI', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'IING ROHIMAT', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213292009080001', '2008-09-23', 1, '$2y$10$coURqNIVTB6RNhT0twz2h.kwCubR4JhqsN2FhR2hgPaqPwhXc6sYO', 'siswa'),
(342, 1082, '0cd49cba-2a67-11e4-b99c-9f61c4c53890', '964b7cb7-a338-4574-814c-f5954ed784eb', NULL, 55, NULL, '0094229538', '23240074', NULL, NULL, 'RINI DARLINA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'WARDANA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213295303090013', '2009-03-13', 1, '$2y$10$w/tRU.VdgwFzLTL6NhXjeOT6SaCT5/7Z6zwsiFx94jpGedPUY.FXS', 'siswa'),
(343, 1083, '3a626872-2c3f-11e4-9108-9f71f5a07320', '964b7cb7-a338-4574-814c-f5954ed784eb', NULL, 55, NULL, '0076261245', '23240064', NULL, NULL, 'KENIA RISMADANI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'MULYONO', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213266809070001', '2007-09-28', 1, '$2y$10$FZGbcwgcjg4G1D.6M7i2xuIdLwpq.yaina6z7BZtxpIKfMx3v6wfS', 'siswa'),
(344, 1084, 'cb01315e-4b94-11e5-8ba8-9f9ababebbc2', '67d9edb1-f0e2-4693-992d-3e4cccd0e1a0', NULL, 40, NULL, '0082466248', '23240134', NULL, NULL, 'WILDA SITI SOLIHAT', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'DADANG SUPARMAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213124905080001', '2008-05-09', 1, '$2y$10$cN5FOmsQ8FBhhpxCa3ys5eK7mzONamqX7RWxidFNf2E//i6iP6unW', 'siswa'),
(345, 1085, '0eb7a71d-494b-46b1-becc-a027fbf0a1ea', '0168bb86-45b1-4a2f-8beb-b147e3bf05a7', NULL, 42, NULL, '0093978427', '252614102', NULL, NULL, 'MOCH. ALFIAN PARISAL', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'DUDI SETIAWAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213292106090001', '2009-06-21', 1, '$2y$10$KycR9cGDK7JaXvJqktxqqe4f9/XR0dmOCRyEqVZMlLjYP2qF3kqUy', 'siswa'),
(346, 1086, '098dc1cd-3372-4731-8408-a2b8c85438e2', '68edb4cb-5f7a-4dfb-b043-d5f663a2f21b', NULL, 54, NULL, '0086302137', '23240149', NULL, NULL, 'FRENDGA SUJATE SETIAWAN', 'L', 'L', 'JAKARTA', NULL, NULL, '-', NULL, NULL, 'Siauw Tjuk Khiun', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3173032501080001', '2008-01-25', 1, '$2y$10$0TPgaqy2pOJs1Tkcfvmbne8SXWygCRUZb85wSidtNh8cVFdp/ckoO', 'siswa'),
(347, 1087, '5c0f4992-3f2e-11e5-9f53-a34544bc316f', 'e94587e3-0ece-43f2-b670-502c10beffe3', NULL, 50, NULL, '0095416586', '24250132', NULL, NULL, 'ARKAN BAGAS ADIWITYA', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'YAYAT RUHYAT', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213120902090003', '2009-02-09', 1, '$2y$10$Pkvyg/2I6T32vMdghA1gTuQnEdskl7P8stQV7DwBMlmk9YkEJFVpW', 'siswa'),
(348, 1088, '906cb0c4-2811-11e4-a368-a369953a34d2', '6b9f4508-c1f9-46e0-b8a6-a03bacd4a708', NULL, 51, NULL, '0084208936', '23240048', NULL, NULL, 'SINTA ANJANI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'Muhamad Yusup', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213014203080002', '2008-03-02', 1, '$2y$10$a0gFIZ0tCB5hvf62/3GGvuyvx3K4hNik3VQ7ck5B8kWM.GzhByshC', 'siswa'),
(349, 1089, '233f3aa8-4610-11e5-987f-a3a7c449f6eb', 'f0dd79d4-b8fa-4815-b531-f65b873990ea', NULL, 43, NULL, '0085122410', '252614059', NULL, NULL, 'ALYA YULYA RAHMAN', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'RAHMAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213016112080003', '2008-12-21', 1, '$2y$10$i6i8ZP4EWoSOZFfRRP/23OP1U6qVAavRZrgP3qhpWH5mL1fSV63zG', 'siswa'),
(350, 1090, '5ce39764-37dd-11e4-bc39-a3d098547387', '964b7cb7-a338-4574-814c-f5954ed784eb', NULL, 55, NULL, '0085920292', '23240071', NULL, NULL, 'REVALINA ANGGRAENI', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Edwin Prayoga', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213266908080002', '2008-08-29', 1, '$2y$10$sqDnw6erB7Cjd4zDyVtnDuo0muYqTMaKVBM7LDq5dteYIethzgA2C', 'siswa'),
(351, 1091, '38171f48-3759-11e4-8ad2-a3e9793e0e0b', '6b9f4508-c1f9-46e0-b8a6-a03bacd4a708', NULL, 51, NULL, '0078034790', '23240027', NULL, NULL, 'DEA GHEFIRA SYAHNARIKI', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Okky Heryawan', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213014210070002', '2007-11-02', 1, '$2y$10$1VXVLxFNpEG5fd4uuvXmB.eg1ZTxCiAEmYJZTkjdfIGqmhRK14KjK', 'siswa'),
(352, 1092, 'e5512aac-31da-11e4-b231-a70edbf84223', '964b7cb7-a338-4574-814c-f5954ed784eb', NULL, 55, NULL, '0071651464', '23240077', NULL, NULL, 'SELA NURFITRIAH', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ASAN SUDARNA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296403950001', '2007-07-10', 1, '$2y$10$WTSDU3QdeL6j//p8Dtx/guCpcrzAUU7CKsfePuwnB96Voh9AwrnNa', 'siswa'),
(353, 1093, '51241880-46f1-11e5-a756-a72e84d27c3e', '619c6aff-d40d-4ac9-905d-243b54536570', NULL, 47, NULL, '0086396558', '24250023', NULL, NULL, 'RAHMA ALIKA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'Maman Sobari', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213125205080001', '2008-05-12', 1, '$2y$10$ID6gzsy5DBjizf.8E/SVVeGJAvNc1PBPWRuUCEAjUAtol.QXaPjTe', 'siswa'),
(354, 1094, '462d4528-3e73-11e4-aafb-a7370d5effbe', '20a865c3-2d47-4cb4-90a4-885b04daa92e', NULL, 52, NULL, '0075528719', '23240011', NULL, NULL, 'INA GUSTIANI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'UDIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296908070001', '2007-08-29', 1, '$2y$10$Jx86n5cvXOTjAi0v5vUta.E2zchQnzv9qOvh9hS32lNksTkhxL21.', 'siswa'),
(355, 1095, 'f2193880-438e-11e5-ba84-a74a6085f237', '9cce8b82-f77c-4be6-be06-449e5da19e86', NULL, 48, NULL, '0085226736', '24250053', NULL, NULL, 'SISKA JULIANTI', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'E.Sulaeman', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213015707080001', '2008-07-17', 1, '$2y$10$NQ9J74kPJe1ytyMc4iRNsO/.1fQbkshf3BrbsyNzbT9hIcMe39Zry', 'siswa'),
(356, 1096, '98ed2ff8-3e78-11e4-9ae7-a794984d3c04', '67d9edb1-f0e2-4693-992d-3e4cccd0e1a0', NULL, 40, NULL, '0097984928', '23240138', NULL, NULL, 'Zalzabila Mauluidia Rokhmah', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Endang Wardita', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213125103090001', '2009-03-11', 1, '$2y$10$zRXbJOXk/km2ixH/TeR2JOJMkrjEj3rjpXc4HPdfWvaUH5xGWBaDS', 'siswa'),
(357, 1097, 'd465a912-29b4-11e4-9867-a7a865476432', '6b9f4508-c1f9-46e0-b8a6-a03bacd4a708', NULL, 51, NULL, '0089514400', '23240026', NULL, NULL, 'DAFA NURSIFA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'NANA SUPRIATNA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213294403080001', '2008-03-04', 1, '$2y$10$dBfSywiZRwuvx4EzYcM8HeCq0gtYrTAQZtvm4GaWssTqCZkH2IXxq', 'siswa'),
(358, 1098, 'be40841e-380e-11e4-87f1-a7e8035982e6', '20a865c3-2d47-4cb4-90a4-885b04daa92e', NULL, 52, NULL, '0079306045', '23240018', NULL, NULL, 'RIRIN NURAENI', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Dadang Saepudin', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213295311070002', '2007-11-13', 1, '$2y$10$ys2b4hMbKfX9n7kpZuBjqOvibqakRDEEF.ngUJ973f1lPmOG6GXv.', 'siswa'),
(359, 1099, '50bbca1a-44d4-11e5-9101-a7f0dc68898e', '619c6aff-d40d-4ac9-905d-243b54536570', NULL, 47, NULL, '0091838124', '24250157', NULL, NULL, 'ADLY AZIBAN PURNAMA', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'M RULI PURNAMA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213290603090001', '2009-03-06', 1, '$2y$10$ixflav7cFCq.37okVZfY0e1jVyRFyYgpvydWT0EgT.HvRlaTCGSKS', 'siswa'),
(360, 1100, '3655dee2-5de6-4e53-93c9-aaa9110e0b53', 'f0dd79d4-b8fa-4815-b531-f65b873990ea', NULL, 43, NULL, '0109661298', '252614063', NULL, NULL, 'DINDA GANISTYA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ATEP SOLIHIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213295902100002', '2010-02-19', 1, '$2y$10$Yov2iHGanST9AhGwSCore.ETKJM5vRmLBPbc4z6H6akN0jpm4DE1G', 'siswa'),
(361, 1101, '01a1c73c-3ca9-11e5-af95-ab245b35fd68', '619c6aff-d40d-4ac9-905d-243b54536570', NULL, 47, NULL, '0098007627', '24250013', NULL, NULL, 'LENI APRILIANI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'YAYA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213266904040001', '2009-04-29', 1, '$2y$10$O64e5YmpP01Qbm4q9iaDfOjoKRpz1PjnTdj/Amjql5menEZ0nIxzi', 'siswa'),
(362, 1102, '33bbafc0-560e-11e5-be6a-ab310433dea2', '6b9f4508-c1f9-46e0-b8a6-a03bacd4a708', NULL, 51, NULL, '0089150351', '23240037', NULL, NULL, 'MARSYA MARSELLIA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'Saepuloh', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213015703080002', '2008-03-17', 1, '$2y$10$ZNEzCe1dV4twVK7M2JdUQu/Kw1L7mfRf2aqS8zHxfoPIdwKq9.xGO', 'siswa'),
(363, 1103, '2ee60558-2ccb-11e4-9c2c-ab40058c561f', '964b7cb7-a338-4574-814c-f5954ed784eb', NULL, 55, NULL, '0088034487', '23240057', NULL, NULL, 'CHELSEA INDRIANI', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'ACU S', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213297103080001', '2008-04-01', 1, '$2y$10$qwIyonE2ldKOcBKG0nF0QOZT.TfUHz10oyZPsH.WuGVSX4/aSQc12', 'siswa'),
(364, 1104, '33996488-5064-11e5-99c4-ab650fd0ce5f', '9cce8b82-f77c-4be6-be06-449e5da19e86', NULL, 48, NULL, '0096308560', '24250039', NULL, NULL, 'MAYLANI ALKHAROMAH SUPRYATNA', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Yaya Supriatna', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213024705090003', '2009-05-07', 1, '$2y$10$2nNIfekN1Fn8S6y1YS/WcuizktqCKhcRgIKQvVZkh5Byrw0Y3ABHi', 'siswa'),
(365, 1105, '86338470-32a2-11e4-ba1b-ab735c98afb0', '6b9f4508-c1f9-46e0-b8a6-a03bacd4a708', NULL, 51, NULL, '0089414598', '23240051', NULL, NULL, 'YENI BUNGA MAULIDA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'EMAN SULAEMAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213297103080002', '2008-03-31', 1, '$2y$10$ZTnCUDb8o/BTCA4THiGRVuBuJJ7VkeYjZ4BwrVk.kApYoO0pohvlm', 'siswa'),
(366, 1106, '9206f104-6023-459d-b8ad-acdaef3bd7e4', 'da90cc11-526e-48aa-b7ce-a9afbc0d8023', NULL, 41, NULL, '0095548291', '252614016', NULL, NULL, 'NIKKI RAHMADANI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'KARTOYO', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213234709090001', '2009-09-07', 1, '$2y$10$xmbBgnVZX47DgfNBrbZhnOboHrXc/SjNJEA6WNi0.3UhvS/ku/wDe', 'siswa'),
(367, 1107, 'c6bbd97e-481c-4196-921b-ae4f54d3bd0a', '005b0659-8a5e-4160-8830-9ddb091fbf00', NULL, 44, NULL, '0095460825', '252614145', NULL, NULL, 'TEGAR JULVIAN', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'YADI MULYADI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213292807090001', '2009-07-28', 1, '$2y$10$Zkvrhl1qfcinuVjZM5avfeSf5lFMZiczND5D1xyVxuy8DHctdat9S', 'siswa'),
(368, 1108, 'dd7535a0-b6e7-4de8-a033-ae6bb4d8fd35', 'f0dd79d4-b8fa-4815-b531-f65b873990ea', NULL, 43, NULL, '0097281099', '252614064', NULL, NULL, 'EVA JULIANTI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'EKA SETIAWAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213126306090002', '2009-07-23', 1, '$2y$10$LPXTH2eXUzPmc63CBxGWn.R0gT/YGaoVWg87oWjWbbxm6IS35Rrw2', 'siswa'),
(369, 1109, '6292f02e-2a72-11e4-9f89-af0acbd61b9c', '68edb4cb-5f7a-4dfb-b043-d5f663a2f21b', NULL, 54, NULL, '0082137837', '23240152', NULL, NULL, 'INTAN KHUMAYRA SALSABILA', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'HERYANTO', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213235605080001', '2008-05-16', 1, '$2y$10$jrvNcyr/.qY2fzFrFKU90O4I1Z43RIZfXP8PA8aFD3Q1fIXkT7/7q', 'siswa'),
(370, 1110, 'fcb2ee52-46dc-11e5-94be-af23b434f340', '619c6aff-d40d-4ac9-905d-243b54536570', NULL, 47, NULL, '0096777689', '24250016', NULL, NULL, 'NAYSHILA SUCI PUTRI SYAKIRA', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Dayat Sastra Wiguna', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213016403090001', '2009-03-24', 1, '$2y$10$6Ws.LAzsxDC8W3DdArPBseEZ4IRoL1Yw/ZPOjr7d4nDWyMZkEoAnK', 'siswa'),
(371, 1111, 'cc193ed2-389f-11e4-8322-af2ad6eb351d', '964b7cb7-a338-4574-814c-f5954ed784eb', NULL, 55, NULL, '0084092734', '23240054', NULL, NULL, 'ANISAH NURAINI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ENDANG ABDUL ARIPIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213294601080001', '2008-01-06', 1, '$2y$10$1tkOeP/D9FBXZayHZvFttO5jkobu4IRqZEhbhGCyIPZbScoGl6A2q', 'siswa'),
(372, 1112, 'ee75a58c-3c83-11e4-a741-af5b49531d84', '67d9edb1-f0e2-4693-992d-3e4cccd0e1a0', NULL, 40, NULL, '0073111180', '23240125', NULL, NULL, 'MUHAMMAD ZEIN AL - FIQRY', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'ANJAR KUSNANDAR', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213262308070005', '2007-08-23', 1, '$2y$10$1bae8lVDXjo3tSjHmrn7HOZmpbwJ/c6Q9LcU.r025m0ItiXNUxVAG', 'siswa'),
(373, 1113, '108977d8-283a-11e4-a9df-af8c9eaedf2b', '6b9f4508-c1f9-46e0-b8a6-a03bacd4a708', NULL, 51, NULL, '0078509871', '23240049', NULL, NULL, 'WEGA ANANDA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'Ade Rukmana', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213014610070001', '2007-10-06', 1, '$2y$10$BFScXcv7cNBLgFK616kqseMkA.TDMMSplRrDz6mqiXqndOiu343oC', 'siswa'),
(374, 1114, '5de5cf34-8d0e-4427-a991-af986d084ecb', 'beea584c-74a1-4013-9a57-06b34248371b', NULL, 53, NULL, '0073177019', '23240088', NULL, NULL, 'Dinyar Ari Slamet', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Aceng Rustaman', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213121210070002', '2007-10-12', 1, '$2y$10$t1gevv2Wk0WMYiqjEUKBseWigEr3X14YLZjGSzkEGprJBDN2K2F8y', 'siswa'),
(375, 1115, '9785e784-584b-11e5-b39c-af99e5e43476', '2bcef959-7a06-4631-b590-f53fb9f93bc2', NULL, 49, NULL, '0099127863', '24250104', NULL, NULL, 'AZKHA ZIYAN ALEXA', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'ALEK TARYANA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213290601090001', '2009-01-06', 1, '$2y$10$mqsLZWrQXvUMXCmR60AE5eQgWMjhwwSP2mIVTFsprhRefZghmYuqK', 'siswa'),
(376, 1116, '0ea126fe-31f0-11e4-a3c5-afa3d9168365', '67d9edb1-f0e2-4693-992d-3e4cccd0e1a0', NULL, 40, NULL, '0073749220', '23240110', NULL, NULL, 'ARYA NUGRAHA ZAELANI', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Jenal Asikin', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213012607070001', '2007-07-26', 1, '$2y$10$JFmYOARDWmarFDZqVSn4uO2CQPznsU9V9l5rGY03B9KzTt5dTGeWC', 'siswa'),
(377, 1117, '00c98ad8-3774-11e4-8202-afc3bf44eb6d', '6b9f4508-c1f9-46e0-b8a6-a03bacd4a708', NULL, 51, NULL, '0088553879', '23240028', NULL, NULL, 'DISTI TRI APRILIANI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'EDI SURATMAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213034904080003', '2008-04-09', 1, '$2y$10$UJxVYhT645WGNDhVE8izhuVVa/HVs3yeOmdWh/dRuikMjI87MN902', 'siswa'),
(378, 1118, '575c0fc0-ae79-11e4-aef4-afe8bcc22f6e', '20a865c3-2d47-4cb4-90a4-885b04daa92e', NULL, 52, NULL, '0077121997', '23240007', NULL, NULL, 'DIENDA AL ZAHWA FEBRIANTI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ATO SUDRAJAT', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213294602070022', '2007-02-06', 1, '$2y$10$Kg88LG9IBw9/0f6eZIDdQudpeeHL4IziWqbYHqRoEan6VmGrh7Yku', 'siswa'),
(379, 1119, '0626232c-7100-4232-b427-b051bdd0fa73', '1d260111-0d05-4b10-a525-1eeeef43fe6b', NULL, 45, NULL, '0107072116', '252614055', NULL, NULL, 'WINDI LEDISA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'TASLAN DUDI KUSUMA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213295601100001', '2010-01-16', 1, '$2y$10$87N7IrR2vzy7KKWMZAvpa.tJXOEWEX4cZGm8vx5ZzApWV92DGSjn6', 'siswa'),
(380, 1120, 'c7613176-5aa7-11e5-b605-b305bc6f2cf9', '9cce8b82-f77c-4be6-be06-449e5da19e86', NULL, 48, NULL, '0083325875', '24250061', NULL, NULL, 'YUNITA JULIANTI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'KOSASIH', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213297006080002', '2008-06-30', 1, '$2y$10$kRwHHn9ljhi0LkxrIHb4gucsbu.mwiK5Y/qk1J1Tc9qkgfUn4yHrW', 'siswa'),
(381, 1121, 'f57173b2-396c-11e4-80c4-b34ec757fac3', '20a865c3-2d47-4cb4-90a4-885b04daa92e', NULL, 52, NULL, '0079524275', '23240015', NULL, NULL, 'NAYALA HUMAIRA SETIYAWAN', 'P', 'L', 'KARAWANG', NULL, NULL, '-', NULL, NULL, 'DODI SETYAWAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3217065012070012', '2007-12-10', 1, '$2y$10$R0Q.4fyOiv4l4KCBCG2bNetqZKALFWbeUmVWIqIQO0bUH4ORdkMk2', 'siswa'),
(382, 1122, 'f96b141c-3e19-11e4-b09b-b36a56af1001', '20a865c3-2d47-4cb4-90a4-885b04daa92e', NULL, 52, NULL, '0088142164', '23240017', NULL, NULL, 'RAHMA AYUTIZA NURIYAH', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'LILI WIZANA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213297101080001', '2008-01-31', 1, '$2y$10$mecKulsUoVZAEGxMjOOdM.2rBbIo7SVDnvRe1QhPsJSDCwimthL9.', 'siswa'),
(383, 1123, 'c840524e-3e78-11e4-999d-b37161cb7f79', '68edb4cb-5f7a-4dfb-b043-d5f663a2f21b', NULL, 54, NULL, '0088819359', '23240150', NULL, NULL, 'HARUMIREIKA SISWARANING PRAMESI', 'P', 'L', 'BANDUNG', NULL, NULL, '-', NULL, NULL, 'WIDOWARNO', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213295508080003', '2008-08-15', 1, '$2y$10$jDCzLVHUx0aoP8EFAq38p.qjMX3QPJCt91dm5sxHYR1MF./zPKCfW', 'siswa');
INSERT INTO `tbl_siswa` (`id`, `id_user`, `dapodik_id`, `rombel_id_dapodik`, `user_id`, `kelas_id`, `jurusan_id`, `nisn`, `nis`, `rfid_uid`, `qr_code`, `nama_lengkap`, `jk`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `agama`, `alamat`, `no_hp_siswa`, `email_siswa`, `nama_ayah`, `nama_ibu`, `nama_wali`, `no_hp_ortu`, `pekerjaan_ayah`, `pekerjaan_ibu`, `status_siswa`, `foto`, `created_at`, `updated_at`, `nik`, `tgl_lahir`, `agama_id`, `password`, `role`) VALUES
(384, 1124, 'ee69f720-f410-4194-9ffa-b40daf89ea38', 'da90cc11-526e-48aa-b7ce-a9afbc0d8023', NULL, 41, NULL, '0094456151', '252614022', NULL, NULL, 'SEPTI RIANA AGUSTINA', 'P', 'L', 'BANDUNG', NULL, NULL, '-', NULL, NULL, 'Agus Sutaryana', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3273276311090003', '2009-11-23', 1, '$2y$10$KwIDw81uDFhdtCH8CdU3IOVwZxU5PCEiyfDAr0uYCWLlpcOinqfcG', 'siswa'),
(385, 1125, '3cf241cb-8f65-43ff-a0ea-b4f5d2e7dfda', 'f0dd79d4-b8fa-4815-b531-f65b873990ea', NULL, 43, NULL, '0098531724', '252614084', NULL, NULL, 'WILLDIANI OKTAVIA MAHARDICHA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'KUSNODO', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213016410090002', '2009-10-24', 1, '$2y$10$CBN8EnlTdEIkA0rj1/oS4erkJuOB85uAGkBvYY2PWgqd3UD2G.TZa', 'siswa'),
(386, 1126, 'e2817a1e-51e5-47f7-b676-b5cfb40ea33d', '67d9edb1-f0e2-4693-992d-3e4cccd0e1a0', NULL, 40, NULL, '0074104630', '23240169', NULL, NULL, 'Dede Arif Setia Budi', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Arna', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213120205080002', '2008-05-02', 1, '$2y$10$78bGtuoUnmAYXj76Ym8HvuRvV4dkhnyNN.Q/CkLt5MyXAz7TbKnvW', 'siswa'),
(387, 1127, 'aa69be6c-584f-11e5-aad2-b7529f024578', '619c6aff-d40d-4ac9-905d-243b54536570', NULL, 47, NULL, '0092360131', '24250029', NULL, NULL, 'TRI SUCI MAULIDA', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'ANAN MULYANA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213294304090002', '2009-04-03', 1, '$2y$10$N/ybgvyIpQ3ly/6K3d0pXOb.Wop4dA0BPtTKIMXsxA54ZJ0EMUuuW', 'siswa'),
(388, 1128, 'e341c93e-2969-11e4-ba4b-b79c150c5c78', '964b7cb7-a338-4574-814c-f5954ed784eb', NULL, 55, NULL, '0074516192', '23240073', NULL, NULL, 'RIKO RAHMAT HIDAYAT', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Heri Hermawan', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213292207070002', '2007-07-22', 1, '$2y$10$WOIcpeOobkKsuFDG807Al.sf8FArrJoX5mPiP3/qwOx4rr5RKhh1C', 'siswa'),
(389, 1129, 'b4ab8c75-0956-4bcc-befd-b8008455acf4', 'f0dd79d4-b8fa-4815-b531-f65b873990ea', NULL, 43, NULL, '0108212284', '252614073', NULL, NULL, 'NURAISYAH', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'AYI ABDUL MATIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213016903100001', '2010-03-29', 1, '$2y$10$EYaef0MOcQWX5VTlkSN81O4diTA8GWkhj9kBkeiProWTAbWyFoe0W', 'siswa'),
(390, 1130, 'c64082d0-9dee-4f6a-ad6a-b97c15f07ca3', 'da90cc11-526e-48aa-b7ce-a9afbc0d8023', NULL, 41, NULL, '0091643724', '252614020', NULL, NULL, 'SAFIRA AYU ANGGRAENI', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Rahmat Kurnia', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213125209090001', '2009-09-12', 1, '$2y$10$.rgZmXX38ClP0ysQB8WM5uY8076.8soHH5hlDH5icU1rMwr.Z061y', 'siswa'),
(391, 1131, '8a8d8a66-4066-11e5-a685-bb0bcf31585e', '2bcef959-7a06-4631-b590-f53fb9f93bc2', NULL, 49, NULL, '0097909281', '24250112', NULL, NULL, 'HAMDAN HUMAEDI', 'L', 'L', 'KARAWANG', NULL, NULL, '-', NULL, NULL, 'YAYAT RUHIYAT', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213291306090001', '2009-06-13', 1, '$2y$10$m/VD/2pIR8YrZjSsH5SNwextV9BK9dLbKTy7k9YxVnFrMvOmp9nDW', 'siswa'),
(392, 1132, '5621a204-4579-11e5-9138-bb35fbe7f400', '619c6aff-d40d-4ac9-905d-243b54536570', NULL, 47, NULL, '0098754716', '24250010', NULL, NULL, 'GIALUNA GEISHA AL-RASSYIFA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ISMAIL SHALEH', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213295502090001', '2009-02-15', 1, '$2y$10$WiFXe2Ev.R2KCvtJjxQPwubQgVQ2cwS5PdvFfkavVq.jk5xHNArDG', 'siswa'),
(393, 1133, '08a6adb9-5f18-44e6-8092-bb4f4be91df3', '005b0659-8a5e-4160-8830-9ddb091fbf00', NULL, 44, NULL, '0106849604', '252614135', NULL, NULL, 'REVAN JALALUDIN NUGRAHA', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'DIKDIK SUGIARTI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213122301100001', '2010-04-23', 1, '$2y$10$POiLEqIWmPZeGmUSqa/pZeC0Hpe3ucu7syhrnvpMz/.4Wnkg8AQVO', 'siswa'),
(394, 1134, '338e56a6-273e-11e4-bde9-bbadee455c4d', '964b7cb7-a338-4574-814c-f5954ed784eb', NULL, 55, NULL, '0073517122', '23240055', NULL, NULL, 'AVRILLIA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'POYAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213016304070001', '2007-04-23', 1, '$2y$10$28jXNPmXKNUpDmFDnaiC6.Y2nDbhs89CVvbisiJugOvUMH87sOu9S', 'siswa'),
(395, 1135, '2e79a786-d1cd-4009-8e5b-bce2e8bcd8c9', 'f0dd79d4-b8fa-4815-b531-f65b873990ea', NULL, 43, NULL, '3101991466', '252614060', NULL, NULL, 'Andina Puspita Wardani', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'M SHOLEH', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296706100011', '2010-06-27', 1, '$2y$10$IuESEG3wk4ruTsVVIpzCauHpWav8ydD2uaz4m/OOf3lLxfyWi21B.', 'siswa'),
(396, 1136, '23d7bc30-59c5-4b21-8163-bd574f91f8d3', 'f0dd79d4-b8fa-4815-b531-f65b873990ea', NULL, 43, NULL, '0096722681', '252614077', NULL, NULL, 'ROSSA NURHIDAYAH', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ASEP DADANG', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213124203090012', '2009-03-02', 1, '$2y$10$mosYPHUWkWN7f08Y2Q6m/e1pzjD9tuU9aZ1wYqVqhyJMmpVKYxvv.', 'siswa'),
(397, 1137, '13b09f8d-c8b9-4777-9f4d-bd898c2089d5', '0168bb86-45b1-4a2f-8beb-b147e3bf05a7', NULL, 42, NULL, '0107471446', '252614107', NULL, NULL, 'RAIHAN ASHIDIQ', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'Nono Maryono', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213012504100001', '2010-04-25', 1, '$2y$10$rQiZG2rfJXTIqqRTyt/D1uyiqkMIs8KvB00U94OaJ9FHj6KGGO2pC', 'siswa'),
(398, 1138, '55921d85-4d33-4507-9fbf-bd8cffaa0289', '005b0659-8a5e-4160-8830-9ddb091fbf00', NULL, 44, NULL, '0095424736', '252614125', NULL, NULL, 'KHAERUL IKHSAN SYIDIK', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'UMAR', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213291902090001', '2009-02-19', 1, '$2y$10$rgU04gP95amlL64Tp9d0iOCBFG3I7AMQUiALtN01xGG0YCZEAB2P6', 'siswa'),
(399, 1139, 'cc9371cb-c489-4c4a-ab7c-bda0b4a2fbfd', '0168bb86-45b1-4a2f-8beb-b147e3bf05a7', NULL, 42, NULL, '0095075126', '252614093', NULL, NULL, 'FAJAR RIZKI RAMADHAN', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ATAY', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213292708090009', '2009-08-27', 1, '$2y$10$ZLOSLGqNgqfAN4AlA/o1CelPCUMK4wXZLAafChFB5oEdhRrN7VjRi', 'siswa'),
(400, 1140, 'b9e6f744-31ab-4762-a2cd-be730a7f2006', '0168bb86-45b1-4a2f-8beb-b147e3bf05a7', NULL, 42, NULL, '0108859569', '252614098', NULL, NULL, 'IBNU DZAKI MUHADZDZIB', 'L', 'L', 'Bandung', NULL, NULL, '-', NULL, NULL, 'Rohendi', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3273151204100005', '2010-04-12', 1, '$2y$10$wSUWkLtS0D5s0NnIauXzjuBfL.fKSYvZvC.ose8LzE3cKFB9I12kK', 'siswa'),
(401, 1141, 'b476cb37-a029-474e-9970-beb60ec52478', '005b0659-8a5e-4160-8830-9ddb091fbf00', NULL, 44, NULL, '0093566012', '252614133', NULL, NULL, 'RAFI SAPUTRA', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'MAMAT NANA S', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213292412090002', '2009-12-24', 1, '$2y$10$0BxKi.go2qIHzRo.y1vkDubc/r9NeIulFkops.CzW4LJKMLa13mHS', 'siswa'),
(402, 1142, 'b89d1db7-fe81-4766-9cba-bf4698c37eee', '1d260111-0d05-4b10-a525-1eeeef43fe6b', NULL, 45, NULL, '0097065189', '252614033', NULL, NULL, 'ASMARANI EKA LESTARI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ADE SUMPENA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213015408090002', '2009-08-14', 1, '$2y$10$a/BHGtL8wAmbi7TnfgtCWOAngnlF1j2ln2p5U00eBTT/aWMcj77I.', 'siswa'),
(403, 1143, '6b6b226c-1469-4d2a-8ccd-bf67ec5ed20b', '964b7cb7-a338-4574-814c-f5954ed784eb', NULL, 55, NULL, '3071306354', '23240069', NULL, NULL, 'NURI SITI MASITOH', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, '', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213295512070001', '2007-12-15', 1, '$2y$10$66ntiSGdCIjZkpCoadZtnODrCYFvRsLrdsjyih827JAaYzId3ckX2', 'siswa'),
(404, 1144, '149bed64-5855-11e5-915e-bf7365b8d4b3', '2bcef959-7a06-4631-b590-f53fb9f93bc2', NULL, 49, NULL, '0097015578', '24250110', NULL, NULL, 'ESA SYARIF PRATAMA MULYANA', 'L', 'L', 'Bandung', NULL, NULL, '-', NULL, NULL, 'ADE TALKIM MULYANA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213291808090002', '2009-08-18', 1, '$2y$10$3YyS5ufiOXeJ67XcvWaAmOgpGhJUr2GobIiT2txgF9KVcFzldhMAm', 'siswa'),
(405, 1145, '1c31fd9e-40ea-11e4-97bd-bfb3c765bec7', '68edb4cb-5f7a-4dfb-b043-d5f663a2f21b', NULL, 54, NULL, '0073608952', '23240139', NULL, NULL, 'AINI KEYSA AURELIA SABIL', 'P', 'L', 'BANDUNG', NULL, NULL, '-', NULL, NULL, 'Dede Saputra', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213014908070004', '2007-08-09', 1, '$2y$10$Y8RjXyNavZK8NtH0K6X6M.xqmPYpHoh.cgWhEwY6Tc2vaG/R0YmG6', 'siswa'),
(406, 1146, '72d54102-3bd9-11e5-b0cb-bfbc3611876a', 'e94587e3-0ece-43f2-b670-502c10beffe3', NULL, 50, NULL, '0086662592', '24250150', NULL, NULL, 'YANTI SULASTRI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'RIAN SUTISNA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213294309080011', '2008-09-03', 1, '$2y$10$YlzFuajdDVUihYUX8Ep1EOkw5wLT5ttq2ZXmirtev2gJeKuAqWefG', 'siswa'),
(407, 1147, 'a303a1c4-942c-4887-9024-bfe613e05550', '1d260111-0d05-4b10-a525-1eeeef43fe6b', NULL, 45, NULL, '0108740547', '252614036', NULL, NULL, 'ELIN VIANA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'SUPIA NURDIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296502100001', '2010-03-22', 1, '$2y$10$xoGpP8dScDPtxyXHt8aSkO27z6eKE2yBZKg03TIQZvDYhHvl1Ioqm', 'siswa'),
(408, 1148, '4cd7fd0a-1e7e-4029-835a-c28d29418d8f', '0168bb86-45b1-4a2f-8beb-b147e3bf05a7', NULL, 42, NULL, '0091017963', '252614092', NULL, NULL, 'FAISAL GALIH IMAM MULHAQ', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'SAEPUL HIDAYAT', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213292404090003', '2009-04-24', 1, '$2y$10$eAgMd8XnCa92hQCREwGeeuP.LnqGA5iYgUQYiW55Gni3DdnNhf7Ue', 'siswa'),
(409, 1149, 'c0384106-2a78-11e4-ae5b-c31a964455d0', 'beea584c-74a1-4013-9a57-06b34248371b', NULL, 53, NULL, '0083961054', '23240096', NULL, NULL, 'MEYLIA NADINDA TRI YASNI', 'P', 'L', 'Suka Raja', NULL, NULL, '-', NULL, NULL, 'YASMAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213266605080002', '2008-05-26', 1, '$2y$10$ZHEF0LU863Z4b73ibagZBOKEfho9fUYJZm9yW8vqiDiWltOeWa1C2', 'siswa'),
(410, 1150, 'c9d20738-4615-11e5-9bf0-c36b62357095', 'e94587e3-0ece-43f2-b670-502c10beffe3', NULL, 50, NULL, '0099720568', '24250130', NULL, NULL, 'ALZENA ISAURA FIRJATULLAH', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Sobirin', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213015402090001', '2009-02-14', 1, '$2y$10$0yDM6uqlEdFt6RUcW0n1Z.m9EplnBNpeogDkDaFxpnGaKzlwZyZwi', 'siswa'),
(411, 1151, '221cc8ec-375d-11e4-9fa5-c3801f9c0ea1', '20a865c3-2d47-4cb4-90a4-885b04daa92e', NULL, 52, NULL, '0075343970', '23240010', NULL, NULL, 'FAISAL MUHAMMAD RIZKY', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Taufik Hidayat', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213010210070001', '2007-10-02', 1, '$2y$10$4DJXwaZ17aQOiDt1pF/v.e6YI7dqiRfmPPdAecNoxbHatSTKTzeX6', 'siswa'),
(412, 1152, '2315e9e8-31d8-11e4-a435-c39403bf5783', 'beea584c-74a1-4013-9a57-06b34248371b', NULL, 53, NULL, '0075051639', '23240098', NULL, NULL, 'MUHAMAD SAMI RAIHAN', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ARI MUHAMAD YASIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213292206070001', '2007-06-22', 1, '$2y$10$mga9RMtEx1VWVhQcz/KF5.30fohwKKI/UIBvrZ/k/2DYGvO3m7542', 'siswa'),
(413, 1153, 'a70a525c-b28c-451f-8a73-c3d2b7628429', '1d260111-0d05-4b10-a525-1eeeef43fe6b', NULL, 45, NULL, '0107456805', '252614040', NULL, NULL, 'IYAM DEINI', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'ADE ROHMAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213125908100001', '2010-08-19', 1, '$2y$10$iiy4AfqUW2FUXf.qjW721uiynb3PBBuLjcM2pAnKN7cAA52wFbQA6', 'siswa'),
(414, 1154, '2531a674-422e-11e5-ae89-c3fb3aadf67e', '1d4ffd24-3a9e-4c03-afd8-a12ab295694a', NULL, 46, NULL, '0091457200', '24250084', NULL, NULL, 'NENG DEWI', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'DEDEN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213294107090007', '2009-04-11', 1, '$2y$10$PwhGQuHj9xlwDTw.v3WJjehZalYXOT6rhWrJ3vEvtYW8T5Y99pRVy', 'siswa'),
(415, 1155, '2d52fc38-4110-4ae0-96aa-c45b9f9dafc5', '1d260111-0d05-4b10-a525-1eeeef43fe6b', NULL, 45, NULL, '0105283118', '252614030', NULL, NULL, 'ALIYA NUR AZIZAH', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'YAYAT H', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296009100002', '2010-09-20', 1, '$2y$10$zUEvRLP3XO.TX6UjW/rCp.TNebiPQLDOoZ6302JI5KNeal3BhpHvq', 'siswa'),
(416, 1156, '92d68159-5067-4bbd-8d0e-c59bc249f71a', '0168bb86-45b1-4a2f-8beb-b147e3bf05a7', NULL, 42, NULL, '0098704230', '252614111', NULL, NULL, 'ROBI AGUSTIAN', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'AZIZ', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213290408090001', '2009-08-04', 1, '$2y$10$DPHWT0JVLYtXe4I8152oFu12yknRjIVMOg6Gboih8Xa9x5nmIIqzS', 'siswa'),
(417, 1157, '22c3b83f-759d-4ea4-a036-c5fb3f2d2c69', '1d260111-0d05-4b10-a525-1eeeef43fe6b', NULL, 45, NULL, '0099297822', '252614031', NULL, NULL, 'ALTHAFUNISA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'IMAN RAJIMAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213264512090001', '2009-12-05', 1, '$2y$10$GuJ2Qz06Xmtg5CRH6h7Bu.WeUxlUFUoeleXdStlaGwKTCpDPrlC42', 'siswa'),
(418, 1158, 'c3416498-2962-11e4-9219-c77df58703e4', '20a865c3-2d47-4cb4-90a4-885b04daa92e', NULL, 52, NULL, '0088221226', '23240005', NULL, NULL, 'Dhila Yulshi Rolani', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Hidayat', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213295107080001', '2008-07-11', 1, '$2y$10$JWX7zYD77LlNzkLMuEtip.eN4LeIKHIprG1v90Xj6RZxCIkJzC09y', 'siswa'),
(419, 1159, '802c855e-45af-4edf-9f50-c7e136eeb22b', 'f0dd79d4-b8fa-4815-b531-f65b873990ea', NULL, 43, NULL, '0091470253', '252614082', NULL, NULL, 'TASYA KHOERUN NISYA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'DARSUM', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213297012090001', '2009-12-30', 1, '$2y$10$4S3rTdUCpUqaW7eKFVgnPuMn22DBRd4eky6dT99ri1OkhJ7BuUTZi', 'siswa'),
(420, 1160, 'fdc97275-946c-40ad-b167-ca849a331d05', 'da90cc11-526e-48aa-b7ce-a9afbc0d8023', NULL, 41, NULL, '3095314022', '252614006', NULL, NULL, 'Elfina Sally Salvia', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'DEDE HERMAN HADIAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213294411090001', '2009-11-04', 1, '$2y$10$7oXUvbzfEK7xS55glJj26u1XfLSNCg.wNgkMlYPNSTzaY4nFaB/ZW', 'siswa'),
(421, 1161, '4468f8d4-3f51-11e4-aa35-cb117fc521b7', '68edb4cb-5f7a-4dfb-b043-d5f663a2f21b', NULL, 54, NULL, '0085309974', '23240146', NULL, NULL, 'DWI ALYA PUTRI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ADE SUTISNA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296810080001', '2008-10-28', 1, '$2y$10$eXQlrBzM2QNDUuS3k23J2.iXt5LrmpFGKpnvlDBJgJIz1Dk5CpaVG', 'siswa'),
(422, 1162, '66a8d531-3aac-4e88-8c2b-cb654a6e66ae', 'f0dd79d4-b8fa-4815-b531-f65b873990ea', NULL, 43, NULL, '0098211201', '252614076', NULL, NULL, 'RISMA FATMAWATI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'SUPRIATNA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296909090001', '2009-09-29', 1, '$2y$10$ePZUgNT4TXfdTqCEHG/wR.Q.Hc8ebXw/hYon4OVP6YxfrF8v.9xKG', 'siswa'),
(423, 1163, 'bc2f2b84-4a74-4eac-bd83-cb78e6c36bf7', '68edb4cb-5f7a-4dfb-b043-d5f663a2f21b', NULL, 54, NULL, '0087004071', '23240154', NULL, NULL, 'KHANZA AJLA ROSIDIN', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'DEDE ROSIDIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296510080001', '2008-10-25', 1, '$2y$10$sOaeGNzLGHyOLuBfdG.u6.GHkhyr3OY8mLSpAO.Hix/8rwZjmxKAu', 'siswa'),
(424, 1164, '0d710674-2cc7-11e4-b2ed-cb8a9b7969fd', 'beea584c-74a1-4013-9a57-06b34248371b', NULL, 53, NULL, '0071307338', '23240094', NULL, NULL, 'KARYANA HILMAN AZIS', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'Ade', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213291111070001', '2007-11-11', 1, '$2y$10$Qfx9.foaEaCRkhVtlDmHReOOjebF4Zto81HWk0AKlHar2M0qhPtPS', 'siswa'),
(425, 1165, 'd6f8c456-3b5c-11e5-956d-cbf651f55942', '2bcef959-7a06-4631-b590-f53fb9f93bc2', NULL, 49, NULL, '0086280077', '24250127', NULL, NULL, 'SOPIAN FATHUL IMAN', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'SOLIHIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213292212090001', '2008-12-22', 1, '$2y$10$QOEZNDr0CrUOjnKtwwf0a.ShFrfK9WL/Flo1zPNBpedtLQSasybAO', 'siswa'),
(426, 1166, '89d2590a-35e8-11e4-8736-cf051cba2c83', '2bcef959-7a06-4631-b590-f53fb9f93bc2', NULL, 49, NULL, '0071470931', '24250120', NULL, NULL, 'RAKA NUGRAHA', 'L', 'L', 'BANDUNG', NULL, NULL, '-', NULL, NULL, 'KARMITA SASMITA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213120405070003', '2007-05-04', 1, '$2y$10$ES4Va6pzZZKzLo6dAN36jesWh06q.GJKtaK1WvDX5wyNRFgZ22XP6', 'siswa'),
(427, 1167, '0bf3fecc-466c-11e5-b5e0-cf803ff0dd61', '1d4ffd24-3a9e-4c03-afd8-a12ab295694a', NULL, 46, NULL, '0099869011', '24250096', NULL, NULL, 'TIARA NURAENI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'CECEP APIN SUGANDA BIN IBRAHIM', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213234402090001', '2009-02-04', 1, '$2y$10$XA1TGmLdCWnEhvhMw4RUle98t9qVifIKeNah6x2DKA2SJi7vungIm', 'siswa'),
(428, 1168, '5cb3eacc-52fe-11e5-8d14-cf96665b5f01', '1d4ffd24-3a9e-4c03-afd8-a12ab295694a', NULL, 46, NULL, '0098381210', '24250068', NULL, NULL, 'CEPI PERMANA', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'AMAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213012010090001', '2009-10-20', 1, '$2y$10$JEzY8NFqxgk1d9SOPY4cNODUqZzmGqWU3HXxF6pogOC.jigghZ/66', 'siswa'),
(429, 1169, 'bd38af90-3b6b-11e5-951a-cff1f8312249', '2bcef959-7a06-4631-b590-f53fb9f93bc2', NULL, 49, NULL, '0089994736', '24250103', NULL, NULL, 'ANDHIKA AIRLANGGA DIRGANTARA', 'L', 'L', 'BANDUNG', NULL, NULL, '-', NULL, NULL, 'ONI ROHIMAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213231408080001', '2008-08-14', 1, '$2y$10$wAfkdNt6P1hYcCpVmSWGQ..ofZB1bUqK/AUC7POi8xQZcjDvOpalK', 'siswa'),
(430, 1170, 'e4edde58-707b-4bbb-b520-d1d9f5588716', 'da90cc11-526e-48aa-b7ce-a9afbc0d8023', NULL, 41, NULL, '0104596486', '252614007', NULL, NULL, 'EUIS SUSILAWATI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'H KAMIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213295704100002', '2010-04-17', 1, '$2y$10$M/fQ3gs7YTv63m/O8ICwxOEyNJ.EMlUqfr5A.YG/phZlB6IKeKIHu', 'siswa'),
(431, 1171, 'b5226114-a61c-11e4-86e5-d31b54b7a1de', 'beea584c-74a1-4013-9a57-06b34248371b', NULL, 53, NULL, '0085832388', '23240107', NULL, NULL, 'YOGA RADITYA IRAWAN', 'L', 'L', 'GARUT', NULL, NULL, '-', NULL, NULL, 'CACA IRAWAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3205061802080002', '2008-02-18', 1, '$2y$10$uMyk9iVV95vAbPWgFQ.ASe93cRFagPlYQwxwLiglAMKvmXYhCohk.', 'siswa'),
(432, 1172, '95a7b24e-55f3-11e5-bf87-d3601a52ad25', '1d4ffd24-3a9e-4c03-afd8-a12ab295694a', NULL, 46, NULL, '0086593600', '24250076', NULL, NULL, 'HANIF MAULANA IBRAHIIM', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'IBRAHIM', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213121007080003', '2008-07-10', 1, '$2y$10$4/xlfXvP.hhOvuuohCHeUOgKSBDthfhLXPVAy.rBxs5.mTQ5GTxoW', 'siswa'),
(433, 1173, '809dc615-6599-4b0e-b91b-d3914c73bb38', '67d9edb1-f0e2-4693-992d-3e4cccd0e1a0', NULL, 40, NULL, '0071621298', '23240165', NULL, NULL, 'Marsa', 'P', 'L', 'Ketapang', NULL, NULL, '-', NULL, NULL, 'JUNAEDI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '1801145212070001', '2007-12-12', 1, '$2y$10$eQxRX7LpoUmaCjNnV9NSDe5X9m7IFMb0QY8dqkQw5CINimrRbGuK2', 'siswa'),
(434, 1174, '09022452-5853-11e5-a989-d3971100721e', '2bcef959-7a06-4631-b590-f53fb9f93bc2', NULL, 49, NULL, '0099756777', '24250107', NULL, NULL, 'DEVARA ARYA DWI PUTRA', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'WARMAN SUHERMAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213290607090001', '2009-07-06', 1, '$2y$10$VP4xQbSUQA2Mx1Kq1c.IhOxleYM57mmoOu5SrYv6yEoYBT6tWhcnK', 'siswa'),
(435, 1175, '13fecaf5-f57d-4999-a417-d4c63d2f58c7', '0168bb86-45b1-4a2f-8beb-b147e3bf05a7', NULL, 42, NULL, '0105751984', '252614101', NULL, NULL, 'LUTHFI RIZKY FEBRIAN', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'AYI WAHYUDIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213292302100001', '2010-02-23', 1, '$2y$10$ITntfBNJFuwTXM9uIJp6Fe8ykw0K6v/6g/m0M858iqtSHxRPBxQJy', 'siswa'),
(436, 1176, 'c59f0db8-5f5d-11e4-aa54-d70c0e2a7581', '20a865c3-2d47-4cb4-90a4-885b04daa92e', NULL, 52, NULL, '0077103976', '23240014', NULL, NULL, 'NAILA NURAINI', 'P', 'L', 'Garut', NULL, NULL, '-', NULL, NULL, 'andri herdianto', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3205056911070005', '2007-11-29', 1, '$2y$10$NKC8Z/CvKsdN/Tapej2MvOu6.c2SEf1WhdJ/8Kp7Tq4mZUw911WY6', 'siswa'),
(437, 1177, 'f7fa189c-356f-11e4-a9c4-d75ab0eeba6c', '68edb4cb-5f7a-4dfb-b043-d5f663a2f21b', NULL, 54, NULL, '0089103039', '23240161', NULL, NULL, 'RIDIA JUNIA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'DEDI SURYADI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213126806080001', '2008-06-28', 1, '$2y$10$dYQEb4G4y8X5VKi.nY9v.uWleTGV1pGAImiougqtDJK6rLGfOCYYG', 'siswa'),
(438, 1178, '8774795c-4a70-11e5-9128-d75f905c1912', '619c6aff-d40d-4ac9-905d-243b54536570', NULL, 47, NULL, '0084458708', '24250018', NULL, NULL, 'NITA RAHAYU', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Tamri', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213126508080001', '2008-08-25', 1, '$2y$10$i91uyJ94wfKf0MKkTvSCEuw/LBF53ZsCYZm651eiheRXxt8e6l766', 'siswa'),
(439, 1179, '8245507e-4c95-11e5-ac70-d78ab93945d1', '2bcef959-7a06-4631-b590-f53fb9f93bc2', NULL, 49, NULL, '0087785115', '24250121', NULL, NULL, 'RAMDANI', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Didi Rosadi', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213262808080001', '2008-08-28', 1, '$2y$10$SpKDkngiI.EtLw2aZryM.OigiVOZfiNK0M2lsQeJCIKL80pm8Kdje', 'siswa'),
(440, 1180, '814d9910-58fc-11e5-b650-d7b43ddce94d', '67d9edb1-f0e2-4693-992d-3e4cccd0e1a0', NULL, 40, NULL, '0075859651', '23240127', NULL, NULL, 'RADI AL HALIM', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'DADAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213011612070001', '2007-12-16', 1, '$2y$10$5l.dWEmjYCbM0MMKNCIdhumOnJKzyPj9C6V1f2kxM9bDVEDeEdfTu', 'siswa'),
(441, 1181, '3bd90936-e8e7-4607-a9fc-da2cbe0c2d33', 'f0dd79d4-b8fa-4815-b531-f65b873990ea', NULL, 43, NULL, '0103022528', '252614070', NULL, NULL, 'MILA INTAN NURAENI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ADE SUHERMAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296301100001', '2010-01-23', 1, '$2y$10$aTw0Vq80VVzrQWqFCatw8Ofbhe5vPX1f3vg8oI6i3KBaBpYCQmFcO', 'siswa'),
(442, 1182, 'b816ab54-2a76-11e4-92cf-db014b9a1ae6', '964b7cb7-a338-4574-814c-f5954ed784eb', NULL, 55, NULL, '0082653218', '23240067', NULL, NULL, 'LIVIA NURCAHYATI', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'ITA DARSITA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213265104080001', '2008-04-12', 1, '$2y$10$sXyifCLSRetW01cgmwnygu59hVyXtgNZKWhJSjiHxPWhSVL80kGhG', 'siswa'),
(443, 1183, '1c6c720e-4e0d-11e5-ab88-db107838130e', '2bcef959-7a06-4631-b590-f53fb9f93bc2', NULL, 49, NULL, '0087207573', '24250122', NULL, NULL, 'RIDWAN MAULANA', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'UJANG DEDI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213292805080013', '2008-05-28', 1, '$2y$10$bzGzSIq.JNntsYixsjUpxO/.YYSTgEwZl9s7X1geX31b8qRyGUMAG', 'siswa'),
(444, 1184, '4f828e1e-3f11-11e4-90a1-db164f75eeda', '964b7cb7-a338-4574-814c-f5954ed784eb', NULL, 55, NULL, '0085018646', '23240063', NULL, NULL, 'INTAN CAHYATI', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'SOMANTRI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213295810080002', '2008-10-18', 1, '$2y$10$YKBCiW.ZhH/hDx/U4Vcp3ubu6NEH8oMPehTbngFpJCTH0KOEUO9/y', 'siswa'),
(445, 1185, '29a1339c-39be-11e4-8ee2-db64cab1e87c', '67d9edb1-f0e2-4693-992d-3e4cccd0e1a0', NULL, 40, NULL, '0087841316', '23240123', NULL, NULL, 'MUHAMMAD ABDUL RIZKY', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'SUDRAJAT', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213013108080001', '2008-08-31', 1, '$2y$10$LeFIk5JA2brihOCN7xXo8.Xc06LoOi9oulBrzhKn1bCttswHZZvI.', 'siswa'),
(446, 1186, 'c1a16c7a-4459-11e4-8994-db653dc3ddd9', '964b7cb7-a338-4574-814c-f5954ed784eb', NULL, 55, NULL, '0073101554', '23240062', NULL, NULL, 'ELSHA AMALIA AGUSTIN', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'IYOK WARYONO', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213196508070001', '2007-08-25', 1, '$2y$10$6zrHuOcn7zPkPnpkiPeBu.qqruxSgK4teiWHC/byBGdM0MtMQmP4q', 'siswa'),
(447, 1187, 'dc9d93fa-3810-11e4-9b62-db698ffe9d25', '68edb4cb-5f7a-4dfb-b043-d5f663a2f21b', NULL, 54, NULL, '0078316650', '23240141', NULL, NULL, 'AULIA SRI RACHMAYANTI', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Maman Karso', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296206070001', '2007-06-22', 1, '$2y$10$.zvbaZt3TBcWN6L9MtsI1OypFReH2IOnusNw/DO83TQQQa7zgIWfe', 'siswa'),
(448, 1188, '3f8d73d6-3f28-11e4-bf44-db7be102170f', '67d9edb1-f0e2-4693-992d-3e4cccd0e1a0', NULL, 40, NULL, '0091783950', '23240129', NULL, NULL, 'RAYSA NURALIFAHYASIN', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'HARIS RISNANDAR', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296206090001', '2009-06-22', 1, '$2y$10$yfB1oVXDCaDCtTlWauOC3uVSFCv2Zg30X5MSrlIYG24E6B6GJjWri', 'siswa'),
(449, 1189, 'c1f353c1-503e-44f7-a689-db89febbf287', 'beea584c-74a1-4013-9a57-06b34248371b', NULL, 53, NULL, '0078861683', '23240089', NULL, NULL, 'EVANDRA ARYAN PRATAMA PUTRA', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'JENAL', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213192504070001', '2007-04-25', 1, '$2y$10$MRNqCFumRwL42ETSlLxUsele2clhVsSR02iocrWFdJpO2GMrqsJRq', 'siswa'),
(450, 1190, '5bb8136c-2cdc-11e4-a7e4-db8c33686a10', '964b7cb7-a338-4574-814c-f5954ed784eb', NULL, 55, NULL, '0073749825', '23240068', NULL, NULL, 'NUR AISYAH', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ASEP EDI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213194808070002', '2007-08-08', 1, '$2y$10$TrcTpF865Q56IytooM/xzOWVGtvsY.bGNYnRtA/MgfrpUFFUUA6Fm', 'siswa'),
(451, 1191, 'b7f16f64-299b-11e4-9ecc-db8cc0acaf86', 'beea584c-74a1-4013-9a57-06b34248371b', NULL, 53, NULL, '0072428287', '23240097', NULL, NULL, 'MUHAMAD RIAN PAUJI', 'L', 'L', 'PURWAKARTA', NULL, NULL, '-', NULL, NULL, 'H. FENDI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3214061207070003', '2007-07-12', 1, '$2y$10$AhahGN1.01d/N50zx1Z3yedXEac2Z.X8Gkb8Q74eT7t/ecQCUYHFq', 'siswa'),
(452, 1192, '62c45520-a820-11e4-b29f-db9aa26ae66f', '67d9edb1-f0e2-4693-992d-3e4cccd0e1a0', NULL, 40, NULL, '0074608829', '23240121', NULL, NULL, 'KAMELIA SOLEHAH', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'DAYAT', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213126603070014', '2007-03-26', 1, '$2y$10$w57KJXHE4ua1.vGC4X/O0OdwwqX6mDCDuXn0jwMSBLup51.B0w6Im', 'siswa'),
(453, 1193, '111c4417-21df-4025-94b8-dcb31be940a4', 'da90cc11-526e-48aa-b7ce-a9afbc0d8023', NULL, 41, NULL, '0105813984', '252614013', NULL, NULL, 'LINGGAR AGUSTIN RAMADANI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'DEDE SUTRISNO', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213292108100001', '2010-08-21', 1, '$2y$10$n/OxQexej3EInx3EuVxJcOOQTw/q7cDOon9uopLzXNAYqPZKadqn6', 'siswa'),
(454, 1194, '2b14c404-ecdc-4b15-92db-dcc36e8fa5e9', '1d4ffd24-3a9e-4c03-afd8-a12ab295694a', NULL, 46, NULL, '0088365697', '24250067', NULL, NULL, 'AZHAR NUR FAUZAN', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'Asep Rohimat', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213290610080001', '2008-10-06', 1, '$2y$10$ZTrkC./7vNqdcK6/L0NC5eo1sASCR5C0tPxgxaItQrokwzApY8BB2', 'siswa'),
(455, 1195, '9694ac70-4cb6-11e5-aa17-df70d8b5cc19', '1d4ffd24-3a9e-4c03-afd8-a12ab295694a', NULL, 46, NULL, '0087213521', '24250065', NULL, NULL, 'AUFA HANAN', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'SUHANDI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213120512080001', '2008-12-15', 1, '$2y$10$e1iIk9soa38z172CSfrG1eM486yWYxdgHhM/CnpY3MdfQodObWl5.', 'siswa'),
(456, 1196, 'fe620f78-40a9-11e5-bde3-df9888b3d703', '1d4ffd24-3a9e-4c03-afd8-a12ab295694a', NULL, 46, NULL, '0081522164', '24250085', NULL, NULL, 'RAIHAN FAZRI SUKANDAR', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'WAWAH SUKANDAR', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213122405080002', '2008-05-24', 1, '$2y$10$uJMatkGYNitb5O0iEp7DvuKvNoUWcEjduuHhouwb3tR.KzTY37u5a', 'siswa'),
(457, 1197, '8d2bef35-2cd5-451e-b566-e0513a20c4d1', '005b0659-8a5e-4160-8830-9ddb091fbf00', NULL, 44, NULL, '0091815110', '252614139', NULL, NULL, 'RIZKI KURNIA PADLI', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'AGUS', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213120107000001', '2009-04-20', 1, '$2y$10$SuqJ4ZJT0Gftcbnw0jP2pevQgk48SLUU4VO4BmaTRbgnQnwMZ0F9i', 'siswa'),
(458, 1198, '76e2bdea-40ea-11e5-aa45-e304d04fda32', '1d4ffd24-3a9e-4c03-afd8-a12ab295694a', NULL, 46, NULL, '0081279075', '24250094', NULL, NULL, 'SITI RUKMINI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'RUKMAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213294611080001', '2008-11-06', 1, '$2y$10$OS.2mXhse4eqCCAILenVkOpwXhsKYHvj0JODbUcH0HHrDOK7aiEiW', 'siswa'),
(459, 1199, 'af666b88-2a63-11e4-ac28-e324597bbef0', 'beea584c-74a1-4013-9a57-06b34248371b', NULL, 53, NULL, '0073592394', '23240087', NULL, NULL, 'DIMAS WARDANI', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'WARLAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213261105070001', '2007-05-11', 1, '$2y$10$kgyT4Qz888uHI8n.I8KuO.cdSADlZeDHSKGTiLJ8AMcPvj7xNRUZe', 'siswa'),
(460, 1200, '30523f58-4622-11e5-9218-e384d832bf2a', '1d4ffd24-3a9e-4c03-afd8-a12ab295694a', NULL, 46, NULL, '0099177587', '24250070', NULL, NULL, 'Dea Putri Rahmawati', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Karib Suherman', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213294603090002', '2009-03-06', 1, '$2y$10$98xXsjbXnnXEC2Jfgv5Dd.fkNccmMbrVjk/PTz9HuQLm0ex.JRPRy', 'siswa'),
(461, 1201, '61b6dd52-8441-463c-86a7-e4ef7a6fa4cf', 'da90cc11-526e-48aa-b7ce-a9afbc0d8023', NULL, 41, NULL, '0091840313', '252614019', NULL, NULL, 'PUTRI NUR HALIMAH', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'SOLIH', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213297012090012', '2009-12-30', 1, '$2y$10$XzEtKkElmH/VsPpV3XAR3uFLqkEkT7EjTLPeUkEIY393Ihtgo5DkS', 'siswa'),
(462, 1202, '91657b50-d640-4f39-9f89-e58cc3524e05', '1d260111-0d05-4b10-a525-1eeeef43fe6b', NULL, 45, NULL, '0091547734', '252614045', NULL, NULL, 'NAILAH LUTFHIYAH HASANAH', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ROSIDIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296405090001', '2009-05-24', 1, '$2y$10$MAKARdlqiZDIo0XfZ5.BeuA94VsEnV5hNRLEnT//fQsYLGL45LnNy', 'siswa'),
(463, 1203, 'c095741a-4fe9-11e5-86ee-e7290a0c0755', 'e94587e3-0ece-43f2-b670-502c10beffe3', NULL, 50, NULL, '0091931769', '24250143', NULL, NULL, 'RAISYA AMALIA SOPIANTI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'CEPI SOPIAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213295204090001', '2009-04-12', 1, '$2y$10$JA6EI2zzlxKdOWVaAdwyxOxncftQOF9kvPH45PLtKB6zBraPuCHnO', 'siswa'),
(464, 1204, 'dd7ac7e4-2f9a-11e4-85ba-e75891fb74af', '6b9f4508-c1f9-46e0-b8a6-a03bacd4a708', NULL, 51, NULL, '0084284364', '23240025', NULL, NULL, 'ANNISA NUR FADHILLAH', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ANDI RUSKANDI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213297101080002', '2008-01-31', 1, '$2y$10$nRb5zLR2ewuh.n.HJjvMHuDzcPPeBxlTQnI9Ikw5HWdYCA3QEzJE2', 'siswa'),
(465, 1205, '266f5210-42f7-11e5-958a-e78d5fa38e67', '619c6aff-d40d-4ac9-905d-243b54536570', NULL, 47, NULL, '0096330453', '24250005', NULL, NULL, 'DESTI NUR AZIZAH', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'SUTISNA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213294803090011', '2009-03-09', 1, '$2y$10$9o8TXPta7fNlI9.AKFLdAejXp9H5oPJLc95mIIDXUP10IPKz9GZMi', 'siswa'),
(466, 1206, 'dce862d6-461a-11e5-b0df-e7a2d0baf50e', '619c6aff-d40d-4ac9-905d-243b54536570', NULL, 47, NULL, '0084682764', '24250161', NULL, NULL, 'KANESA IRMALIA PUTRI', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Acub Andriana', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213294211080002', '2008-11-02', 1, '$2y$10$BG/q..9okH2bHOB9JaZcO.jYvIgNuAmNkIyBxBuVAYJpyJ0UNQMKm', 'siswa'),
(467, 1207, '552ccb44-29aa-11e4-a261-e7f03c9ff7c9', 'beea584c-74a1-4013-9a57-06b34248371b', NULL, 53, NULL, '0086194798', '23240164', NULL, NULL, 'KAMILA', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'MAMAN SUPARMAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213125603080001', '2008-03-16', 1, '$2y$10$eZeetDPl6wkv5rNVkvqM1.Na4UGCT8lN7Ao1KbZu54CTCfjT5ziHO', 'siswa'),
(468, 1208, 'ee6701a2-4b8d-11e5-b312-e7fe760afa2f', '9cce8b82-f77c-4be6-be06-449e5da19e86', NULL, 48, NULL, '0086336873', '24250055', NULL, NULL, 'TANIA AMALIA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'DASMITA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213266710080002', '2008-10-27', 1, '$2y$10$V7kWIcO22bwYRmKZzkzgoueneaAvMTU7xaYn255/PpTYCRzw/8aMW', 'siswa'),
(469, 1209, 'f751a185-88c4-4cd0-b5b7-e9fb8685c14a', 'da90cc11-526e-48aa-b7ce-a9afbc0d8023', NULL, 41, NULL, '0091862714', '252614015', NULL, NULL, 'NENG SAHNI SRI MULYANI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'DARMIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296610090001', '2009-10-27', 1, '$2y$10$fBNozk1xXMA7dvIdRQ4r5eLbG4lvyRHioaM1Gnix7uU2ERpKn5taO', 'siswa'),
(470, 1210, 'b8ec22f8-103a-4159-86b9-ea00b3a571da', '964b7cb7-a338-4574-814c-f5954ed784eb', NULL, 55, NULL, '0081371260', '23240060', NULL, NULL, 'DHIRA QUEEN MALIKA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'NA\'IM AHMAD SURURI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213076607080002', '2008-06-26', 1, '$2y$10$JDkTkToraI79OOvZYQoJ6OH.92gNxiLsCNISVqBk32w1yBwm0XA4S', 'siswa'),
(471, 1211, 'fa3a26f7-aeee-4f54-bb8a-ea7bc6d390e8', '0168bb86-45b1-4a2f-8beb-b147e3bf05a7', NULL, 42, NULL, '0107551785', '252614103', NULL, NULL, 'MUH RAJA AN\'ASHAR', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'AEP SETIA RAMDHANI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213293006100001', '2010-06-30', 1, '$2y$10$RuyRWpS1n409/05qxxGlceYmD1AQroBlcYsv2HF/uMcCbsRdVNrIu', 'siswa'),
(472, 1212, 'f7b4eca0-8788-4b28-94bb-eb3c3775dced', 'f0dd79d4-b8fa-4815-b531-f65b873990ea', NULL, 43, NULL, '0102406145', '252614079', NULL, NULL, 'SILVI RIANI', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'BIRIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213265406100002', '2010-06-14', 1, '$2y$10$9zWiFmZW2q8Dep9KhJ7lV.oy6r.VXd3/8pvp9rolrTJeWuaDHDIBq', 'siswa'),
(473, 1213, '4adf77c4-3fd2-11e5-9d75-ebaca9103437', 'e94587e3-0ece-43f2-b670-502c10beffe3', NULL, 50, NULL, '0083301835', '24250142', NULL, NULL, 'Putri Cahyaningrum', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Endang Sumarna', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213075811080002', '2008-11-18', 1, '$2y$10$Ij5z5FmpvMpiu073dVKAjOuwxmTEbkNM7/ecvPXlhmVDkfnfoc7tW', 'siswa'),
(474, 1214, 'c89eebf4-56ac-11e5-822b-ebb9c8d4578c', '9cce8b82-f77c-4be6-be06-449e5da19e86', NULL, 48, NULL, '0081679042', '24250045', NULL, NULL, 'OCTAVIA NURFITRIYANI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'SAEPUDIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213294410080001', '2008-10-04', 1, '$2y$10$gsho/jX3cqduZvndGcr46..Lva5F./lyu4uXNMxXsF4XvOWSIiBoW', 'siswa'),
(475, 1215, 'b4c951f1-0eef-420b-98ed-ecd79eb7ea07', '1d260111-0d05-4b10-a525-1eeeef43fe6b', NULL, 45, NULL, '0102769434', '252614053', NULL, NULL, 'VEGA DEWI PERTIWI', 'P', 'L', 'TANGERANG', NULL, NULL, '-', NULL, NULL, 'DEDI SUHENDI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213124103100001', '2010-03-01', 1, '$2y$10$rH/W228/CWAkTv9fR0mJmOIsMmGTEXCtsc9l6MOwqgXulcbdWfx6q', 'siswa'),
(476, 1216, '9e5f9c50-d297-4cab-9a20-ee9c653ea030', '0168bb86-45b1-4a2f-8beb-b147e3bf05a7', NULL, 42, NULL, '3102631710', '252614116', NULL, NULL, 'AKBAR MAULANA MALIK', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Arif Hidayat', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213232808100001', '2010-08-28', 1, '$2y$10$ZK4fsu4v3ZauNbCxQvfwZO5H.Rt/TJhByhVgYmWu7hUttkusTLLwO', 'siswa'),
(477, 1217, '05a7972a-39c2-11e4-9385-ef43a48067bf', 'beea584c-74a1-4013-9a57-06b34248371b', NULL, 53, NULL, '0083223656', '23240099', NULL, NULL, 'NAYSHA DIAN JUWITA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'JUNAIDI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213015601080003', '2008-01-16', 1, '$2y$10$Sto9I3ypmiZBCH81KfQCfe2cxTfS.rroKJk7ZkuAWCIIE/LUa8GTy', 'siswa'),
(478, 1218, 'd2fee404-3f2e-11e5-8a76-ef78f73aeeb6', '9cce8b82-f77c-4be6-be06-449e5da19e86', NULL, 48, NULL, '0093391744', '24250057', NULL, NULL, 'TIARA NAZWA AZAHRA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'JAJANG SOPIAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213124403090021', '2009-03-04', 1, '$2y$10$JUY81DBKr4l4dKbQdpWV7ecVLIMmgoxT8othbiTCdhXmLpD6K17/2', 'siswa'),
(479, 1219, 'd0103310-2968-11e4-9f6e-ef8190110e2e', '67d9edb1-f0e2-4693-992d-3e4cccd0e1a0', NULL, 40, NULL, '0075934067', '23240126', NULL, NULL, 'PUTRA WIJAYA', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Maman Wijaya', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213290808070001', '2007-08-08', 1, '$2y$10$g.DvIYPnpC7FX5qlDydYTe/BuMLR6HmXy/4HlIneGc3GcICZJPaWC', 'siswa'),
(480, 1220, 'e458c64f-dc2c-4c8f-8c72-f27059b27e23', '005b0659-8a5e-4160-8830-9ddb091fbf00', NULL, 44, NULL, '0099801316', '252614134', NULL, NULL, 'RAMDHAN MAULANA', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'YAYA HERMAYA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213011109090002', '2009-09-19', 1, '$2y$10$SZWQYKLfAkVRPhjx/zMaEeznEj88YoxnX4Pppr9LK/G/JXP7uPtIy', 'siswa'),
(481, 1221, '21f99f34-3578-11e4-95c7-f3092c3046d4', '20a865c3-2d47-4cb4-90a4-885b04daa92e', NULL, 52, NULL, '0078948244', '23240001', NULL, NULL, 'ADE ISMA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ATENG SUKAYA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213124707070014', '2007-07-07', 1, '$2y$10$pWDLlcCablfgSIJqvHlaiuM2G.BFzO4LqVytmAb/U2wFmwsGogNzq', 'siswa'),
(482, 1222, 'dedb7c48-3f60-11e4-bde5-f328ec855189', '9cce8b82-f77c-4be6-be06-449e5da19e86', NULL, 48, NULL, '0088813828', '24250032', NULL, NULL, 'DERI RIYANA SOPANDI', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'AYI SOPANDI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213290212070001', '2007-12-02', 1, '$2y$10$5/VR55wWv5uQ/sb4UL5H8OXFKLiwkBjcELIp1eudBVuNSvhClf1fu', 'siswa'),
(483, 1223, 'f4cc0e5c-2b8b-11e4-87ac-f380f2a2923e', '1d4ffd24-3a9e-4c03-afd8-a12ab295694a', NULL, 46, NULL, '0071003218', '24250082', NULL, NULL, 'NANANG SUPRIYATNA', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'IDAR SUPRIYATNA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213121610070003', '2007-10-16', 1, '$2y$10$D1yd1wQ1NptBAB/FfahC5.7lQPwFn.NOpYmKl58LolCNITre7EXdW', 'siswa'),
(484, 1224, '0e02d028-04b6-4f24-8449-f3a1a5266559', 'f0dd79d4-b8fa-4815-b531-f65b873990ea', NULL, 43, NULL, '0094156296', '252614104', NULL, NULL, 'MUHAMMAD RAFI ALFIZHA H', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'HIDAYATULLOH', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213262210090001', '2009-10-22', 1, '$2y$10$MimUT3FAVUtjA2f9Z.pXuOc0P/mNwgQuSUCyQJhMGCfUeUsPp.ioq', 'siswa'),
(485, 1225, 'c9abec1f-c64a-43d3-9896-f46e6c83d0e6', '1d260111-0d05-4b10-a525-1eeeef43fe6b', NULL, 45, NULL, '0097663284', '252614034', NULL, NULL, 'DINDA APRIANI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'DAWAN HERMAWAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296506090001', '2009-06-25', 1, '$2y$10$Vog7eSsS6WgZWU3FLJik5efffwy7pCGJnkCEV5gkPHaPJ.f5zm5cO', 'siswa'),
(486, 1226, '6fd8be16-645b-4d60-9156-f5d26f57780c', 'da90cc11-526e-48aa-b7ce-a9afbc0d8023', NULL, 41, NULL, '0097503482', '252614003', NULL, NULL, 'ASYIFA NUR AZXIA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'IWAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213294402090011', '2009-02-04', 1, '$2y$10$zaPjH7RCx1o1kV7Sp10PjOT6y6B2Xp7Mm.qf.PmbosM8XgzzGO.Gu', 'siswa'),
(487, 1227, '12c2192a-242e-11e4-a2ac-f7555b6f50f3', '68edb4cb-5f7a-4dfb-b043-d5f663a2f21b', NULL, 54, NULL, '0086399605', '23240160', NULL, NULL, 'RAHMAH ROUDOTUL ZANAH', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'WAWAN GUNAWAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213026107080001', '2008-07-21', 1, '$2y$10$Ru8fEsiZ8ULLFLpAZkYjeOIVYMqTSrmkFIBU4GKugVi9dvNnPLz3e', 'siswa'),
(488, 1228, '53b7a8d0-30b5-11e4-9e99-f775ef352944', '20a865c3-2d47-4cb4-90a4-885b04daa92e', NULL, 52, NULL, '0084101203', '23240008', NULL, NULL, 'ELYA DALVA AZIZAH', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'SUNARYA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213264304080001', '2008-04-03', 1, '$2y$10$XywIQh3FA0RVRu9AmGnYSO5nqnPOPfeNmBvU8wu5XwXFUH2GVRo0.', 'siswa'),
(489, 1229, '1781ae2a-4a71-11e5-aa47-f7d2c76cd540', '1d4ffd24-3a9e-4c03-afd8-a12ab295694a', NULL, 46, NULL, '0087510564', '24250083', NULL, NULL, 'NATASYA AYU AGUSTIN', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Cholip', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213125508080001', '2008-08-15', 1, '$2y$10$vo15yYJhQOoXCsAYp4aaaO2fHSEGaLwXvd8yFFLarc0OHwe9V3uGS', 'siswa'),
(490, 1230, '838efaec-2a78-4743-beec-f9f9c33f8543', 'f0dd79d4-b8fa-4815-b531-f65b873990ea', NULL, 43, NULL, '0099081751', '252614080', NULL, NULL, 'SINTA DESTIANAWATI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'NANA SUTISNA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213124512100007', '2009-12-05', 1, '$2y$10$anJHP9/jinNgtlCom79igOqp34QRROJaTN1u26xxYaGVxJd76l13m', 'siswa'),
(491, 1231, 'ba83c33f-c257-4b82-a5e9-fa7226fc5fd1', '0168bb86-45b1-4a2f-8beb-b147e3bf05a7', NULL, 42, NULL, '0109998749', '252614089', NULL, NULL, 'ARESKI SUHERLAN', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Ade Suherman', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213261001100004', '2010-01-10', 1, '$2y$10$LRheAon.1MkmhxOmGeRcBeeOrP5wF/m0LuXrXOncsy/6fFEo1gaqi', 'siswa'),
(492, 1232, 'ff983aa2-2a6b-11e4-8f71-fb2a42ff0400', '6b9f4508-c1f9-46e0-b8a6-a03bacd4a708', NULL, 51, NULL, '0081182486', '23240033', NULL, NULL, 'Kiki Refan Fadilah', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Koko Komara', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213121002080002', '2008-02-10', 1, '$2y$10$wYozj6v0rkT1bTeUJWucVOTvLUmVMMiXD.wSlPCXxry0tdwlQurwy', 'siswa'),
(493, 1233, '074854b3-2adc-4742-a78c-fb3c75c79b39', 'f0dd79d4-b8fa-4815-b531-f65b873990ea', NULL, 43, NULL, '0103379503', '252614058', NULL, NULL, 'ALMAYRA HILDAVINA', 'P', 'L', 'BOGOR', NULL, NULL, '-', NULL, NULL, 'EKA CHANDRA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3271046804100005', '2010-04-28', 1, '$2y$10$CC/J8u7COXEIsrvSP7V.t.ytappInCtQTy22VQhNC3FmGoA/hE1hW', 'siswa'),
(494, 1234, '7c310bc6-4a0e-11e5-b8c7-fb6592c1b9c8', '1d4ffd24-3a9e-4c03-afd8-a12ab295694a', NULL, 46, NULL, '0088869520', '24250090', NULL, NULL, 'RIFAL ATHALLAH RAFA', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Beni Mulyana', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213012310080001', '2008-10-23', 1, '$2y$10$2JqZKI7Vke59/am5D.2kM.M7iNKr3H1Lg4/pym3QhNcwJiaEMvHBS', 'siswa'),
(495, 1235, 'c1dd81df-65ab-488e-bba4-fb6ef4f9e0d2', '0168bb86-45b1-4a2f-8beb-b147e3bf05a7', NULL, 42, NULL, '0099822243', '252614114', NULL, NULL, 'TEGUH ANDIKA YUDISTIRA', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'JUHANDI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213292509090001', '2009-09-25', 1, '$2y$10$m8lZpIHa/Iqr2/kbwm1wTujSmvWRxH4qhoTfMIQu1leVfTm7LxmKC', 'siswa'),
(496, 1236, '5f79f902-329a-11e4-bf27-fbf1f158b8ee', 'beea584c-74a1-4013-9a57-06b34248371b', NULL, 53, NULL, '0075390505', '23240106', NULL, NULL, 'WIZAR HIDAYAT', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'IIP SAEPUDIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213290112070002', '2007-12-01', 1, '$2y$10$9u9wpPjVH4IYj.fx4uKdpeaPTGTasmySi9cAcyABEHLjV.CUh9FjK', 'siswa'),
(497, 1237, 'aab8bd81-96ec-42a4-8877-fdafcd89aa30', '005b0659-8a5e-4160-8830-9ddb091fbf00', NULL, 44, NULL, '0096872850', '252614141', NULL, NULL, 'RYA NURAPNI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ANO ARA', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296502090001', '2009-02-25', 1, '$2y$10$10I8jcM6QZ09zem.dnXvkOTYQSWl381lbPGR2hC/6xKKHngp0hirq', 'siswa'),
(498, 1238, '76a3fd60-866d-42c4-9afe-fdd20dfee497', 'da90cc11-526e-48aa-b7ce-a9afbc0d8023', NULL, 41, NULL, '0097552833', '252614008', NULL, NULL, 'FAIRA RAHMADANI', 'P', 'L', 'Karawang', NULL, NULL, '-', NULL, NULL, 'DADI AFANDI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213266908090002', '2009-08-29', 1, '$2y$10$uUfE3nReaBzIorc4wOx3fuPX6hzFIgWhGvqQrsuArdobTqeM1Y2dy', 'siswa'),
(499, 1239, '27e2ff61-8d08-4ace-b36c-febf769c3c9d', '0168bb86-45b1-4a2f-8beb-b147e3bf05a7', NULL, 42, NULL, '0098710025', '252614090', NULL, NULL, 'CHITA CLAUDIYA FANDELAKI', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'BERTI MUHAMAD', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213290107100024', '2009-07-27', 1, '$2y$10$ihwNiNdkDw/EyG5mn5v67OEOFMYU0VjiWN7X43tnmywsuH9XDfxK.', 'siswa'),
(500, 1240, '2ed38e47-c2fc-4660-9e33-feda95328ffc', '0168bb86-45b1-4a2f-8beb-b147e3bf05a7', NULL, 42, NULL, '0103025128', '252614115', NULL, NULL, 'VITRAN AKBAR MAULANA', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'AGUS SYARIPUDIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213290909100001', '2010-09-09', 1, '$2y$10$TTUje.0KwVR2cq5k1Dwhp.5vBTqsG4tmis8YS/J2WgsSOXsTwUwUW', 'siswa'),
(501, 1241, '241b90d2-33df-11e4-a688-ff090e39c47d', '67d9edb1-f0e2-4693-992d-3e4cccd0e1a0', NULL, 40, NULL, '0084814996', '23240132', NULL, NULL, 'SITI SOLIHAT', 'P', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Yadi', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213124802080003', '2008-02-08', 1, '$2y$10$pwDhX53L8ELALQrrU1w8zOtQIE/iZpAZl6l6oqNl3NdMq6jTp54N2', 'siswa'),
(502, 1242, '11a03458-380d-11e4-b65d-ff12d700d393', 'beea584c-74a1-4013-9a57-06b34248371b', NULL, 53, NULL, '0078214894', '23240104', NULL, NULL, 'TOPA BAMBANG MULYANA', 'L', 'L', 'Subang', NULL, NULL, '-', NULL, NULL, 'Ade Ahum', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213292010070003', '2007-10-20', 1, '$2y$10$5ukkpkASPH2Xvi3XlUbs7uyhbB.gLx7jEKpH/kdD.k.c/9Com6bFC', 'siswa'),
(503, 1243, '55f7389a-a9e7-4c18-a844-ff2f1608f709', '9cce8b82-f77c-4be6-be06-449e5da19e86', NULL, 48, NULL, '0093811929', '24250054', NULL, NULL, 'SULIS SYIFA ALTAFUNNISA', 'P', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'IRWAN HERMAWAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213294108090002', '2009-08-01', 1, '$2y$10$JNBjrklva/U4pDDnzjWMnufyz5YRuqTdilENayZyBCP2UOrBo1L.2', 'siswa'),
(504, 1244, '9fa9483a-56a1-11e5-8d7b-ffcb0d67a56e', '2bcef959-7a06-4631-b590-f53fb9f93bc2', NULL, 49, NULL, '0089918087', '24250108', NULL, NULL, 'DIKY SUHENDI', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ATANG SUHENDI', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213296202080001', '2008-02-22', 1, '$2y$10$hYoGo3h89CUDX0RA1FqP9.GuWlTRcebXFJE33g.otuRscC5V0btdK', 'siswa'),
(505, 1245, '948862ea-3e26-11e4-af3e-ffd97c9a2025', 'beea584c-74a1-4013-9a57-06b34248371b', NULL, 53, NULL, '0078489319', '23240085', NULL, NULL, 'ALIF MUHAMAD', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'ADE YANTO', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213291602070002', '2007-02-16', 1, '$2y$10$W2z01Gyw6N2ckyBeOfND9OyQg1xMaD5QXH.6WShyhoDfwkG7tjdEG', 'siswa');
INSERT INTO `tbl_siswa` (`id`, `id_user`, `dapodik_id`, `rombel_id_dapodik`, `user_id`, `kelas_id`, `jurusan_id`, `nisn`, `nis`, `rfid_uid`, `qr_code`, `nama_lengkap`, `jk`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `agama`, `alamat`, `no_hp_siswa`, `email_siswa`, `nama_ayah`, `nama_ibu`, `nama_wali`, `no_hp_ortu`, `pekerjaan_ayah`, `pekerjaan_ibu`, `status_siswa`, `foto`, `created_at`, `updated_at`, `nik`, `tgl_lahir`, `agama_id`, `password`, `role`) VALUES
(506, 1246, '0e80355a-5082-11e5-b926-ffe0737b667f', '2bcef959-7a06-4631-b590-f53fb9f93bc2', NULL, 49, NULL, '0086753270', '24250115', NULL, NULL, 'Moch Fauzi Ridwan', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'WAWAN KURNIAWAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213290105080001', '2008-05-01', 1, '$2y$10$fXvU7LGGTH0tQHvwbxQGiO8tbMRb2vyLH0FcLyqnZox8DpIBtyWKi', 'siswa'),
(507, 1247, '13221a16-388b-11e4-bd5b-ffee2a9b5ba1', '20a865c3-2d47-4cb4-90a4-885b04daa92e', NULL, 52, NULL, '0089237650', '23240016', NULL, NULL, 'PUTRI ALIEFYA VERN', 'P', 'L', 'BANDUNG', NULL, NULL, '-', NULL, NULL, 'YAYAN SOPYAN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3277014101080010', '2008-01-01', 1, '$2y$10$/qaMR8vPXI0HKrKNGWrQR.zi9.ve/tteUV2uikZcoqO6KOP6/orUC', 'siswa'),
(508, 1248, '6fa54f30-3f0f-11e4-978e-ffeec52431b0', 'beea584c-74a1-4013-9a57-06b34248371b', NULL, 53, NULL, '0075856898', '23240148', NULL, NULL, 'FAUZAN AL FIRDAUS SHIHAB', 'L', 'L', 'SUBANG', NULL, NULL, '-', NULL, NULL, 'CECEP SIHABUDIN', '-', NULL, NULL, NULL, NULL, 'Aktif', 'default.png', NULL, NULL, '3213291807070001', '2007-07-18', 1, '$2y$10$vAjyHD3faTOFTAxcuVoequG.ti2fJkBcTtmNrWCPXuvidhO6cx5tu', 'siswa');

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
-- Struktur dari tabel `tbl_soal`
--

CREATE TABLE `tbl_soal` (
  `id` int(11) NOT NULL,
  `id_bank_soal` int(11) NOT NULL,
  `tipe_soal` varchar(20) NOT NULL,
  `pertanyaan` longtext NOT NULL,
  `file_audio` varchar(255) DEFAULT NULL,
  `file_video` varchar(255) DEFAULT NULL,
  `bobot` int(11) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `file_gambar` varchar(255) DEFAULT NULL COMMENT 'Nama file gambar jika ada'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_soal`
--

INSERT INTO `tbl_soal` (`id`, `id_bank_soal`, `tipe_soal`, `pertanyaan`, `file_audio`, `file_video`, `bobot`, `created_at`, `updated_at`, `file_gambar`) VALUES
(1, 1, 'PG', '<p>\\( \\frac{2}{4} \\)<br></p>', NULL, NULL, 2, '2026-01-18 17:03:28', '2026-01-18 17:03:28', NULL),
(2, 1, 'PG', '<p>\\( \\sqrt{20} \\) <br></p>', NULL, NULL, 2, '2026-01-18 17:04:38', '2026-01-18 17:04:38', NULL),
(21, 3, 'PG', 'Siapa Presiden pertama Indonesia?<br>(Disini bisa menyisipkan gambar)<br><img src=\"http://localhost:8080/uploads/bank_soal/img_import_20260119101034_1647.png\" style=\"max-width: 100%; height: auto; margin: 10px 0;\">', NULL, NULL, 2, '2026-01-19 10:10:34', '2026-01-19 10:10:34', NULL),
(22, 3, 'PG_KOMPLEKS', 'Manakah yang termasuk buah-buahan?', NULL, NULL, 4, '2026-01-19 10:10:34', '2026-01-19 10:10:34', NULL),
(23, 3, 'MENJODOHKAN', 'Pasangkan Ibukota Provinsi berikut!', NULL, NULL, 2, '2026-01-19 10:10:35', '2026-01-19 10:10:35', NULL),
(24, 3, 'ISIAN_SINGKAT', '1 minggu ada berapa hari?', NULL, NULL, 2, '2026-01-19 10:10:35', '2026-01-19 10:10:35', NULL),
(29, 2, 'PG', 'Siapa Presiden pertama Indonesia?<br>(Disini bisa menyisipkan gambar)', NULL, NULL, 10, '2026-01-23 22:49:58', '2026-01-23 23:19:09', NULL),
(30, 2, 'PG_KOMPLEKS', 'Manakah yang termasuk buah-buahan?', NULL, NULL, 40, '2026-01-23 22:49:58', '2026-01-23 22:49:58', NULL),
(31, 2, 'MENJODOHKAN', 'Pasangkan Ibukota Provinsi berikut!', NULL, NULL, 40, '2026-01-23 22:49:58', '2026-01-23 22:49:58', NULL),
(32, 2, 'ISIAN_SINGKAT', '1 minggu ada berapa hari?', NULL, NULL, 10, '2026-01-23 22:49:58', '2026-01-23 22:49:58', NULL),
(33, 3, 'PG', '<p>ini soal ke 5</p>', NULL, NULL, 2, '2026-01-27 18:31:26', '2026-01-27 18:31:26', NULL);
INSERT INTO `tbl_soal` (`id`, `id_bank_soal`, `tipe_soal`, `pertanyaan`, `file_audio`, `file_video`, `bobot`, `created_at`, `updated_at`, `file_gambar`) VALUES
(34, 3, 'PG', '<p>ini soal gamar<img src=\"data:image/jpeg;base64,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\" data-filename=\"WhatsApp Image 2026-01-22 at 08.17.00.jpeg\" style=\"width: 325.067px;\"></p>', NULL, NULL, 2, '2026-01-27 18:45:06', '2026-01-27 18:45:06', NULL);
INSERT INTO `tbl_soal` (`id`, `id_bank_soal`, `tipe_soal`, `pertanyaan`, `file_audio`, `file_video`, `bobot`, `created_at`, `updated_at`, `file_gambar`) VALUES
(35, 3, 'PG', '<p>&nbsp;Arti dari ” أَنَا تِلْمِيْذٌ ” adalah..</p>', NULL, NULL, 2, '2026-01-27 18:47:09', '2026-01-27 18:47:09', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_tagihan`
--

CREATE TABLE `tbl_tagihan` (
  `id` int(11) NOT NULL,
  `id_jenis_bayar` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `id_kelas` int(11) DEFAULT 0,
  `nominal_tagihan` double DEFAULT 0,
  `nominal_terbayar` double DEFAULT 0,
  `status_bayar` enum('LUNAS','BELUM','CICIL') DEFAULT 'BELUM',
  `keterangan` varchar(50) DEFAULT NULL,
  `bulan_ke` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_tagihan`
--

INSERT INTO `tbl_tagihan` (`id`, `id_jenis_bayar`, `id_siswa`, `id_kelas`, `nominal_tagihan`, `nominal_terbayar`, `status_bayar`, `keterangan`, `bulan_ke`, `created_at`, `updated_at`) VALUES
(71, 3, 1, 1, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-28 13:51:07', '2026-01-28 13:51:07'),
(72, 3, 2, 1, 5000000, 70000, 'CICIL', 'Daftar Ulang 1', 0, '2026-01-28 13:51:07', '2026-01-28 15:01:25'),
(73, 3, 3, 1, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-28 13:51:07', '2026-01-28 13:51:07'),
(74, 3, 4, 1, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-28 13:51:07', '2026-01-28 13:51:07'),
(75, 3, 5, 1, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-28 13:51:07', '2026-01-28 13:51:07'),
(76, 3, 6, 2, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-28 13:51:07', '2026-01-28 13:51:07'),
(77, 3, 7, 2, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-28 13:51:07', '2026-01-28 13:51:07'),
(78, 3, 8, 2, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-28 13:51:07', '2026-01-28 13:51:07'),
(79, 3, 9, 2, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-28 13:51:07', '2026-01-28 13:51:07'),
(80, 3, 14, 1, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-28 13:51:07', '2026-01-28 13:51:07'),
(81, 3, 15, 1, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-28 13:51:07', '2026-01-28 13:51:07'),
(82, 3, 16, 1, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-28 13:51:07', '2026-01-28 13:51:07'),
(83, 3, 17, 1, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-28 13:51:07', '2026-01-28 13:51:07'),
(84, 3, 18, 2, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-28 13:51:07', '2026-01-28 13:51:07'),
(85, 3, 19, 2, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-28 13:51:07', '2026-01-28 13:51:07'),
(86, 3, 20, 2, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-28 13:51:07', '2026-01-28 13:51:07'),
(87, 3, 21, 2, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-28 13:51:07', '2026-01-28 13:51:07'),
(88, 3, 26, 1, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-28 13:51:07', '2026-01-28 13:51:07'),
(89, 3, 27, 1, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-28 13:51:07', '2026-01-28 13:51:07'),
(90, 3, 28, 1, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-28 13:51:07', '2026-01-28 13:51:07'),
(91, 3, 29, 1, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-28 13:51:07', '2026-01-28 13:51:07'),
(92, 3, 30, 2, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-28 13:51:07', '2026-01-28 13:51:07'),
(93, 3, 31, 2, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-28 13:51:07', '2026-01-28 13:51:07'),
(94, 3, 32, 2, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-28 13:51:07', '2026-01-28 13:51:07'),
(95, 3, 33, 2, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-28 13:51:07', '2026-01-28 13:51:07'),
(96, 3, 38, 1, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-28 13:51:07', '2026-01-28 13:51:07'),
(97, 3, 39, 1, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-28 13:51:07', '2026-01-28 13:51:07'),
(98, 3, 40, 1, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-28 13:51:07', '2026-01-28 13:51:07'),
(99, 3, 41, 1, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-28 13:51:07', '2026-01-28 13:51:07'),
(100, 3, 42, 2, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-28 13:51:07', '2026-01-28 13:51:07'),
(101, 3, 43, 2, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-28 13:51:07', '2026-01-28 13:51:07'),
(102, 3, 44, 2, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-28 13:51:07', '2026-01-28 13:51:07'),
(103, 3, 45, 2, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-28 13:51:07', '2026-01-28 13:51:07'),
(104, 3, 50, 1, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-28 13:51:07', '2026-01-28 13:51:07'),
(105, 3, 51, 1, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-28 13:51:07', '2026-01-28 13:51:07'),
(106, 3, 107, 40, 5000000, 5000000, 'LUNAS', 'Daftar Ulang 1', 0, '2026-01-30 19:33:11', '2026-01-30 19:53:28'),
(107, 3, 121, 40, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:33:11', '2026-01-30 19:33:11'),
(108, 3, 131, 40, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:33:11', '2026-01-30 19:33:11'),
(109, 3, 140, 40, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:33:11', '2026-01-30 19:33:11'),
(110, 3, 158, 40, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:33:11', '2026-01-30 19:33:11'),
(111, 3, 169, 40, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:33:11', '2026-01-30 19:33:11'),
(112, 3, 209, 40, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:33:11', '2026-01-30 19:33:11'),
(113, 3, 220, 40, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:33:11', '2026-01-30 19:33:11'),
(114, 3, 244, 40, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:33:11', '2026-01-30 19:33:11'),
(115, 3, 271, 40, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:33:11', '2026-01-30 19:33:11'),
(116, 3, 297, 40, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:33:11', '2026-01-30 19:33:11'),
(117, 3, 309, 40, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:33:11', '2026-01-30 19:33:11'),
(118, 3, 344, 40, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:33:11', '2026-01-30 19:33:11'),
(119, 3, 356, 40, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:33:11', '2026-01-30 19:33:11'),
(120, 3, 372, 40, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:33:11', '2026-01-30 19:33:11'),
(121, 3, 376, 40, 5000000, 3000000, 'CICIL', 'Daftar Ulang 1', 0, '2026-01-30 19:33:11', '2026-01-31 11:27:37'),
(122, 3, 386, 40, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:33:11', '2026-01-30 19:33:11'),
(123, 3, 433, 40, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:33:11', '2026-01-30 19:33:11'),
(124, 3, 440, 40, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:33:11', '2026-01-30 19:33:11'),
(125, 3, 445, 40, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:33:11', '2026-01-30 19:33:11'),
(126, 3, 448, 40, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:33:11', '2026-01-30 19:33:11'),
(127, 3, 452, 40, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:33:11', '2026-01-30 19:33:11'),
(128, 3, 479, 40, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:33:11', '2026-01-30 19:33:11'),
(129, 3, 501, 40, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:33:11', '2026-01-30 19:33:11'),
(130, 3, 59, 51, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:46:43', '2026-01-30 19:46:43'),
(131, 3, 67, 51, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:46:43', '2026-01-30 19:46:43'),
(132, 3, 69, 51, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:46:43', '2026-01-30 19:46:43'),
(133, 3, 91, 51, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:46:43', '2026-01-30 19:46:43'),
(134, 3, 96, 51, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:46:43', '2026-01-30 19:46:43'),
(135, 3, 106, 51, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:46:43', '2026-01-30 19:46:43'),
(136, 3, 109, 51, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:46:43', '2026-01-30 19:46:43'),
(137, 3, 122, 51, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:46:43', '2026-01-30 19:46:43'),
(138, 3, 123, 51, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:46:44', '2026-01-30 19:46:44'),
(139, 3, 132, 51, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:46:44', '2026-01-30 19:46:44'),
(140, 3, 219, 51, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:46:44', '2026-01-30 19:46:44'),
(141, 3, 227, 51, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:46:44', '2026-01-30 19:46:44'),
(142, 3, 234, 51, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:46:44', '2026-01-30 19:46:44'),
(143, 3, 252, 51, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:46:44', '2026-01-30 19:46:44'),
(144, 3, 260, 51, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:46:44', '2026-01-30 19:46:44'),
(145, 3, 293, 51, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:46:44', '2026-01-30 19:46:44'),
(146, 3, 304, 51, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:46:44', '2026-01-30 19:46:44'),
(147, 3, 325, 51, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:46:44', '2026-01-30 19:46:44'),
(148, 3, 327, 51, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:46:44', '2026-01-30 19:46:44'),
(149, 3, 331, 51, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:46:44', '2026-01-30 19:46:44'),
(150, 3, 348, 51, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:46:44', '2026-01-30 19:46:44'),
(151, 3, 351, 51, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:46:44', '2026-01-30 19:46:44'),
(152, 3, 357, 51, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:46:44', '2026-01-30 19:46:44'),
(153, 3, 362, 51, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:46:44', '2026-01-30 19:46:44'),
(154, 3, 365, 51, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:46:44', '2026-01-30 19:46:44'),
(155, 3, 373, 51, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:46:44', '2026-01-30 19:46:44'),
(156, 3, 377, 51, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:46:44', '2026-01-30 19:46:44'),
(157, 3, 464, 51, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:46:44', '2026-01-30 19:46:44'),
(158, 3, 492, 51, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-30 19:46:44', '2026-01-30 19:46:44'),
(159, 3, 62, 46, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-31 11:23:54', '2026-01-31 11:23:54'),
(160, 3, 99, 46, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-31 11:23:54', '2026-01-31 11:23:54'),
(161, 3, 101, 46, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-31 11:23:54', '2026-01-31 11:23:54'),
(162, 3, 153, 46, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-31 11:23:54', '2026-01-31 11:23:54'),
(163, 3, 168, 46, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-31 11:23:54', '2026-01-31 11:23:54'),
(164, 3, 172, 46, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-31 11:23:54', '2026-01-31 11:23:54'),
(165, 3, 178, 46, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-31 11:23:54', '2026-01-31 11:23:54'),
(166, 3, 182, 46, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-31 11:23:54', '2026-01-31 11:23:54'),
(167, 3, 190, 46, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-31 11:23:54', '2026-01-31 11:23:54'),
(168, 3, 193, 46, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-31 11:23:54', '2026-01-31 11:23:54'),
(169, 3, 197, 46, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-31 11:23:54', '2026-01-31 11:23:54'),
(170, 3, 199, 46, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-31 11:23:54', '2026-01-31 11:23:54'),
(171, 3, 200, 46, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-31 11:23:55', '2026-01-31 11:23:55'),
(172, 3, 205, 46, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-31 11:23:55', '2026-01-31 11:23:55'),
(173, 3, 246, 46, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-31 11:23:55', '2026-01-31 11:23:55'),
(174, 3, 258, 46, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-31 11:23:55', '2026-01-31 11:23:55'),
(175, 3, 273, 46, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-31 11:23:55', '2026-01-31 11:23:55'),
(176, 3, 284, 46, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-31 11:23:55', '2026-01-31 11:23:55'),
(177, 3, 289, 46, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-31 11:23:55', '2026-01-31 11:23:55'),
(178, 3, 290, 46, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-31 11:23:55', '2026-01-31 11:23:55'),
(179, 3, 313, 46, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-31 11:23:55', '2026-01-31 11:23:55'),
(180, 3, 319, 46, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-31 11:23:55', '2026-01-31 11:23:55'),
(181, 3, 333, 46, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-31 11:23:55', '2026-01-31 11:23:55'),
(182, 3, 414, 46, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-31 11:23:55', '2026-01-31 11:23:55'),
(183, 3, 427, 46, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-31 11:23:55', '2026-01-31 11:23:55'),
(184, 3, 428, 46, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-31 11:23:55', '2026-01-31 11:23:55'),
(185, 3, 432, 46, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-31 11:23:55', '2026-01-31 11:23:55'),
(186, 3, 454, 46, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-31 11:23:55', '2026-01-31 11:23:55'),
(187, 3, 455, 46, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-31 11:23:55', '2026-01-31 11:23:55'),
(188, 3, 456, 46, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-31 11:23:55', '2026-01-31 11:23:55'),
(189, 3, 458, 46, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-31 11:23:55', '2026-01-31 11:23:55'),
(190, 3, 460, 46, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-31 11:23:55', '2026-01-31 11:23:55'),
(191, 3, 483, 46, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-31 11:23:55', '2026-01-31 11:23:55'),
(192, 3, 489, 46, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-31 11:23:55', '2026-01-31 11:23:55'),
(193, 3, 494, 46, 5000000, 0, 'BELUM', 'Daftar Ulang 1', 0, '2026-01-31 11:23:55', '2026-01-31 11:23:55'),
(194, 4, 62, 46, 1800000, 0, 'BELUM', 'Daftar Ulang 2', 0, '2026-01-31 11:24:17', '2026-01-31 11:24:17'),
(195, 4, 99, 46, 1800000, 0, 'BELUM', 'Daftar Ulang 2', 0, '2026-01-31 11:24:18', '2026-01-31 11:24:18'),
(196, 4, 101, 46, 1800000, 0, 'BELUM', 'Daftar Ulang 2', 0, '2026-01-31 11:24:18', '2026-01-31 11:24:18'),
(197, 4, 153, 46, 1800000, 0, 'BELUM', 'Daftar Ulang 2', 0, '2026-01-31 11:24:18', '2026-01-31 11:24:18'),
(198, 4, 168, 46, 1800000, 0, 'BELUM', 'Daftar Ulang 2', 0, '2026-01-31 11:24:18', '2026-01-31 11:24:18'),
(199, 4, 172, 46, 1800000, 0, 'BELUM', 'Daftar Ulang 2', 0, '2026-01-31 11:24:18', '2026-01-31 11:24:18'),
(200, 4, 178, 46, 1800000, 0, 'BELUM', 'Daftar Ulang 2', 0, '2026-01-31 11:24:18', '2026-01-31 11:24:18'),
(201, 4, 182, 46, 1800000, 0, 'BELUM', 'Daftar Ulang 2', 0, '2026-01-31 11:24:18', '2026-01-31 11:24:18'),
(202, 4, 190, 46, 1800000, 0, 'BELUM', 'Daftar Ulang 2', 0, '2026-01-31 11:24:18', '2026-01-31 11:24:18'),
(203, 4, 193, 46, 1800000, 0, 'BELUM', 'Daftar Ulang 2', 0, '2026-01-31 11:24:18', '2026-01-31 11:24:18'),
(204, 4, 197, 46, 1800000, 0, 'BELUM', 'Daftar Ulang 2', 0, '2026-01-31 11:24:18', '2026-01-31 11:24:18'),
(205, 4, 199, 46, 1800000, 0, 'BELUM', 'Daftar Ulang 2', 0, '2026-01-31 11:24:18', '2026-01-31 11:24:18'),
(206, 4, 200, 46, 1800000, 0, 'BELUM', 'Daftar Ulang 2', 0, '2026-01-31 11:24:18', '2026-01-31 11:24:18'),
(207, 4, 205, 46, 1800000, 0, 'BELUM', 'Daftar Ulang 2', 0, '2026-01-31 11:24:18', '2026-01-31 11:24:18'),
(208, 4, 246, 46, 1800000, 0, 'BELUM', 'Daftar Ulang 2', 0, '2026-01-31 11:24:18', '2026-01-31 11:24:18'),
(209, 4, 258, 46, 1800000, 0, 'BELUM', 'Daftar Ulang 2', 0, '2026-01-31 11:24:18', '2026-01-31 11:24:18'),
(210, 4, 273, 46, 1800000, 0, 'BELUM', 'Daftar Ulang 2', 0, '2026-01-31 11:24:18', '2026-01-31 11:24:18'),
(211, 4, 284, 46, 1800000, 0, 'BELUM', 'Daftar Ulang 2', 0, '2026-01-31 11:24:18', '2026-01-31 11:24:18'),
(212, 4, 289, 46, 1800000, 0, 'BELUM', 'Daftar Ulang 2', 0, '2026-01-31 11:24:18', '2026-01-31 11:24:18'),
(213, 4, 290, 46, 1800000, 0, 'BELUM', 'Daftar Ulang 2', 0, '2026-01-31 11:24:18', '2026-01-31 11:24:18'),
(214, 4, 313, 46, 1800000, 0, 'BELUM', 'Daftar Ulang 2', 0, '2026-01-31 11:24:18', '2026-01-31 11:24:18'),
(215, 4, 319, 46, 1800000, 0, 'BELUM', 'Daftar Ulang 2', 0, '2026-01-31 11:24:18', '2026-01-31 11:24:18'),
(216, 4, 333, 46, 1800000, 0, 'BELUM', 'Daftar Ulang 2', 0, '2026-01-31 11:24:18', '2026-01-31 11:24:18'),
(217, 4, 414, 46, 1800000, 0, 'BELUM', 'Daftar Ulang 2', 0, '2026-01-31 11:24:18', '2026-01-31 11:24:18'),
(218, 4, 427, 46, 1800000, 0, 'BELUM', 'Daftar Ulang 2', 0, '2026-01-31 11:24:18', '2026-01-31 11:24:18'),
(219, 4, 428, 46, 1800000, 0, 'BELUM', 'Daftar Ulang 2', 0, '2026-01-31 11:24:18', '2026-01-31 11:24:18'),
(220, 4, 432, 46, 1800000, 0, 'BELUM', 'Daftar Ulang 2', 0, '2026-01-31 11:24:18', '2026-01-31 11:24:18'),
(221, 4, 454, 46, 1800000, 0, 'BELUM', 'Daftar Ulang 2', 0, '2026-01-31 11:24:18', '2026-01-31 11:24:18'),
(222, 4, 455, 46, 1800000, 0, 'BELUM', 'Daftar Ulang 2', 0, '2026-01-31 11:24:18', '2026-01-31 11:24:18'),
(223, 4, 456, 46, 1800000, 0, 'BELUM', 'Daftar Ulang 2', 0, '2026-01-31 11:24:18', '2026-01-31 11:24:18'),
(224, 4, 458, 46, 1800000, 0, 'BELUM', 'Daftar Ulang 2', 0, '2026-01-31 11:24:18', '2026-01-31 11:24:18'),
(225, 4, 460, 46, 1800000, 0, 'BELUM', 'Daftar Ulang 2', 0, '2026-01-31 11:24:18', '2026-01-31 11:24:18'),
(226, 4, 483, 46, 1800000, 0, 'BELUM', 'Daftar Ulang 2', 0, '2026-01-31 11:24:18', '2026-01-31 11:24:18'),
(227, 4, 489, 46, 1800000, 0, 'BELUM', 'Daftar Ulang 2', 0, '2026-01-31 11:24:18', '2026-01-31 11:24:18'),
(228, 4, 494, 46, 1800000, 0, 'BELUM', 'Daftar Ulang 2', 0, '2026-01-31 11:24:18', '2026-01-31 11:24:18');

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
-- Struktur dari tabel `tbl_transaksi`
--

CREATE TABLE `tbl_transaksi` (
  `id` int(11) NOT NULL,
  `kode_transaksi` varchar(20) DEFAULT NULL,
  `merchant_ref` varchar(50) DEFAULT NULL,
  `reference` varchar(50) DEFAULT NULL,
  `id_tagihan` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `jumlah_bayar` double NOT NULL,
  `fee_admin` double DEFAULT 0,
  `total_bayar` double DEFAULT 0,
  `status_transaksi` varchar(20) DEFAULT 'SUCCESS',
  `checkout_url` text DEFAULT NULL,
  `payment_type` varchar(50) DEFAULT 'TUNAI',
  `tanggal_bayar` datetime DEFAULT current_timestamp(),
  `petugas_id` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_transaksi`
--

INSERT INTO `tbl_transaksi` (`id`, `kode_transaksi`, `merchant_ref`, `reference`, `id_tagihan`, `id_siswa`, `jumlah_bayar`, `fee_admin`, `total_bayar`, `status_transaksi`, `checkout_url`, `payment_type`, `tanggal_bayar`, `petugas_id`, `created_at`, `updated_at`) VALUES
(12, 'TRX-26012814431078', NULL, NULL, 72, 2, 50000, 0, 0, 'SUCCESS', NULL, 'TUNAI', '2026-01-28 14:43:10', 6, '2026-01-28 14:43:10', NULL),
(13, 'TRX-26012815012570', NULL, NULL, 72, 2, 20000, 0, 0, 'SUCCESS', NULL, 'TUNAI', '2026-01-28 15:01:25', 6, '2026-01-28 15:01:25', NULL),
(14, NULL, 'INV-260130195158281', 'DEV-T39326334478OMHRG', 148, 327, 5000000, -4995750, 4250, 'UNPAID', 'https://tripay.co.id/checkout/DEV-T39326334478OMHRG', 'BRI Virtual Account', '2026-01-30 19:51:59', 0, '2026-01-30 19:51:59', NULL),
(15, NULL, 'INV-260130195215856', 'DEV-T39326334479PAV3I', 106, 107, 5000000, -4850000, 150000, 'SUCCESS', 'https://tripay.co.id/checkout/DEV-T39326334479PAV3I', 'DANA', '2026-01-30 19:52:15', 0, '2026-01-30 19:52:15', '2026-01-30 19:53:28'),
(16, 'TRX-26013111273768', NULL, NULL, 121, 376, 3000000, 0, 0, 'SUCCESS', NULL, 'TUNAI', '2026-01-31 11:27:37', 6, '2026-01-31 11:27:37', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_tugas`
--

CREATE TABLE `tbl_tugas` (
  `id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `deskripsi` text NOT NULL,
  `file_pendukung` varchar(255) DEFAULT NULL,
  `deadline` datetime NOT NULL,
  `status` int(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_tugas_kumpul`
--

CREATE TABLE `tbl_tugas_kumpul` (
  `id` int(11) NOT NULL,
  `tugas_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `file_jawaban` varchar(255) DEFAULT NULL,
  `catatan_siswa` text DEFAULT NULL,
  `nilai` int(3) DEFAULT NULL,
  `komentar_guru` text DEFAULT NULL,
  `tgl_kumpul` datetime DEFAULT current_timestamp(),
  `status_kumpul` enum('Tepat Waktu','Terlambat') DEFAULT 'Tepat Waktu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_ujian_siswa`
--

CREATE TABLE `tbl_ujian_siswa` (
  `id` int(11) NOT NULL,
  `id_jadwal` int(11) DEFAULT NULL,
  `id_bank_soal` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `waktu_mulai` datetime DEFAULT NULL,
  `waktu_selesai_seharusnya` datetime DEFAULT NULL,
  `waktu_submit` datetime DEFAULT NULL,
  `status` int(11) DEFAULT 0 COMMENT '0=Mengerjakan, 1=Selesai',
  `nilai` decimal(10,2) DEFAULT 0.00,
  `jml_benar` int(11) DEFAULT 0,
  `jml_salah` int(11) DEFAULT 0,
  `jml_kosong` int(11) DEFAULT 0,
  `ip_address` varchar(50) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `lat_siswa` varchar(50) DEFAULT NULL,
  `long_siswa` varchar(50) DEFAULT NULL,
  `jarak_meter` float DEFAULT NULL,
  `is_blocked` int(11) DEFAULT 0 COMMENT '1=Kena Banned',
  `alasan_blokir` text DEFAULT NULL,
  `is_locked` tinyint(1) DEFAULT 0 COMMENT '1=Terkunci (Timeout), 0=Aman',
  `jml_pelanggaran` int(11) DEFAULT 0 COMMENT 'Counter pelanggaran'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_ujian_siswa`
--

INSERT INTO `tbl_ujian_siswa` (`id`, `id_jadwal`, `id_bank_soal`, `id_siswa`, `waktu_mulai`, `waktu_selesai_seharusnya`, `waktu_submit`, `status`, `nilai`, `jml_benar`, `jml_salah`, `jml_kosong`, `ip_address`, `user_agent`, `lat_siswa`, `long_siswa`, `jarak_meter`, `is_blocked`, `alasan_blokir`, `is_locked`, `jml_pelanggaran`) VALUES
(38, 7, 2, 2, '2026-01-26 21:33:10', '2026-01-26 21:43:10', '2026-01-26 21:33:50', 1, 0.00, 0, 0, 0, NULL, 'Aktif', NULL, NULL, NULL, 1, NULL, 0, 3),
(46, 9, 3, 2, '2026-01-27 18:48:31', '2026-01-27 19:08:31', '2026-01-27 18:52:44', 1, 62.50, 4, 2, 1, NULL, 'Aktif', NULL, NULL, NULL, 0, NULL, 0, 0),
(49, 10, 3, 327, '2026-01-30 20:49:23', '2026-01-30 21:09:23', NULL, 0, 0.00, 0, 0, 0, NULL, 'Aktif', NULL, NULL, NULL, 0, NULL, 1, 3),
(50, 10, 3, 107, '2026-01-30 20:51:42', '2026-01-30 21:11:42', NULL, 1, 0.00, 0, 0, 0, NULL, 'Aktif', NULL, NULL, NULL, 1, NULL, 0, 3);

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
  `updated_at` datetime DEFAULT NULL,
  `active` int(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `nama_lengkap`, `username`, `email`, `nomor_wa`, `telegram_chat_id`, `password`, `last_login`, `role`, `created_at`, `updated_at`, `active`) VALUES
(3, 'Administrator', 'admin', 'admin@sekolah.sch.id', '6281234567890', '1080546870', 'admin', NULL, 'admin', '2026-01-01 00:59:01', NULL, 1),
(4, 'Administrator Utama', 'admin', 'admin@sekolah.sch.id', '628123456789', '1080546870', 'admin', NULL, 'admin', '2026-01-01 01:05:15', NULL, 1),
(6, 'Riki Wahyudin', '3213012912010002', 'rikiwahyudin52@gmail.com', '085155232366', '1080546870', '$2y$10$xcpSJL3sgw9M5u..vocMmeL5WiSAbaLzhiSp71hU46MiqLQ96afFq', NULL, 'guru', '2026-01-03 10:14:01', '2026-01-19 11:14:29', 1),
(7, 'Alpin MUldiyana', '3213234503980005', '', '', '1080546870', '$2y$10$jCGgUYPSmjeg.0sEakLcqOY/SYDsYm/Z7NI5gkH3QjIo12.Rp8YMu', NULL, 'guru', '2026-01-03 10:20:32', '2026-01-16 13:20:01', 1),
(8, 'Rian Alviana', '3213125012910010', '3213125012910010@sekolah.id', '6285155232366', '1080546870', '$2y$10$ovmzA1PpYwH.07F3CQF8B.lmbXI5iD/HcERYjZPFQFnmHHfTiuo/6', NULL, 'guru', '2026-01-03 10:28:13', '2026-01-04 14:32:38', 1),
(9, 'YUNI ROSMIANTI', '3213234503980012', 'rikiwahyudin52@gmail.com', '085155232366', '', '$2y$10$Zuzr5pZrnTjTh9Kt1HollOi83032qzzc0G/T2Ool422Lb3NczsWji', NULL, 'guru', '2026-01-03 10:53:38', '2026-01-16 13:20:23', 1),
(10, 'Super Administrator', 'admin', 'admin@sekolah.id', '081234567890', '123456789', '$2y$10$HEDl4UKWFmKd0x7ZGviw6eDh69BNrM70jW3LvJx01jBP0pseXguBS', NULL, 'admin', '2026-01-03 13:32:24', NULL, 1),
(12, 'Ani Suryani', '2024001', 'ani.suryani@sekolah.id', '085678901234', '', '$2y$10$HEDl4UKWFmKd0x7ZGviw6eDh69BNrM70jW3LvJx01jBP0pseXguBS', NULL, 'admin', '2026-01-03 13:32:24', NULL, 1),
(13, 'xoni Nas', '3213234503980097', '3213234503980097@sekolah.id', '083176915165', NULL, '$2y$10$KNmjCqjA7aZqfmmqCNBDFuXRTE.GR4ZU63meQz9H/3lmgkyBcTK.O', NULL, 'admin', '2026-01-04 14:07:06', '2026-01-04 14:07:06', 1),
(14, 'Admin Alpin', 'admin.alpin', NULL, NULL, NULL, '$2y$10$xuvJp7OKuCQ2F4jMdwSwNuO0YEdkuVhNn4lxQD8DkAR91xrMkf.Zu', NULL, 'admin', '2026-01-06 03:02:10', NULL, 1),
(687, 'AHMAD SISWA TELADAN', '0054321987', '0054321987@student.sch.id', '85155232366', '123456789', '$2y$10$06atMBqijuqd3ngO5l.hQePoPD47xe/pAAtQaJiW6vvf42Ll1ll.S', NULL, 'admin', '2026-01-18 13:54:57', '2026-01-28 16:30:20', 1),
(688, 'ADITYA PRATAMA', '81234001', '81234001@student.sch.id', '85155232366', '1080546870', '$2y$10$eyie1iYwiJtRf04WJqFozevURpI/2U/R3Xzgq8FBCSWU41iAxvY8O', NULL, 'admin', '2026-01-18 13:54:57', '2026-01-28 15:01:03', 1),
(689, 'AHMAD FAUZI', '81234002', '81234002@student.sch.id', '85155232366', '1080546870', '$2y$10$vamIH9gp/N.I6SZ4BKJEXeHoVTvZjCaj3fk3gWFddCG8MaMAqsmLC', NULL, 'admin', '2026-01-18 13:54:57', '2026-01-28 16:30:04', 1),
(690, 'AISYAH PUTRI', '81234003', '81234003@student.sch.id', '85155232366', '10003', '$2y$10$me1l4STg5JCXcRJmYHg52Ooiab4aZQO6WWytxc9E4UIoo2pLeuZWK', NULL, 'admin', '2026-01-18 13:54:57', '2026-01-28 16:30:35', 1),
(691, 'Aldi Mahendra', '81234004', '81234004@student.sch.id', '8123456004', '10004', '$2y$10$pClxaUuPmYNzEqPpEycZ4eVDviUuO8PzHgCx15J3Ost05d71tGWHW', NULL, 'admin', '2026-01-18 13:54:58', '2026-01-18 13:54:58', 1),
(692, 'Amelia Sari', '81234005', '81234005@student.sch.id', '8123456005', '10005', '$2y$10$z4ni8PNC5hC16m2.JrXMD.R4NjpK4y6wi5adU/RYu7WeoaxA3XCwq', NULL, 'admin', '2026-01-18 13:54:58', '2026-01-18 13:54:58', 1),
(693, 'Andi Saputra', '81234006', '81234006@student.sch.id', '8123456006', '10006', '$2y$10$TqcicSS3Cyx/f012JvvLLO28fxQe/hQlzSwpLLsdynx3bR2r1gnJO', NULL, 'admin', '2026-01-18 13:54:58', '2026-01-18 13:54:58', 1),
(694, 'Annisa Rahma', '81234007', '81234007@student.sch.id', '8123456007', '10007', '$2y$10$YtkOzm9u.70Dhp0GEIdIyuvU0HKbYGr6H7/sdSO42HLe0J5bqpkLu', NULL, 'admin', '2026-01-18 13:54:58', '2026-01-18 13:54:58', 1),
(695, 'Arif Hidayat', '81234008', '81234008@student.sch.id', '8123456008', '10008', '$2y$10$P1bLu7w2aSSy7ZQeUyHNyOlCE135lmYsH3fU0TNykwlo15fnyVQSK', NULL, 'admin', '2026-01-18 13:54:58', '2026-01-18 13:54:58', 1),
(696, 'Ayu Lestari', '81234009', '81234009@student.sch.id', '8123456009', '10009', '$2y$10$IKNY7FmpUNmf5bOhfmV1u.huCC2Ouf8U0U2PMEvONWICQ5SJ2GsqW', NULL, 'admin', '2026-01-18 13:54:58', '2026-01-18 13:54:58', 1),
(697, 'Bagas Kara', '81234010', '81234010@student.sch.id', '8123456010', '10010', '$2y$10$h49trK46fuQhp5dIUH9H4ORHhrC9pGTz6FUCn6KlU34xwR5S3eFt.', NULL, 'admin', '2026-01-18 13:54:58', '2026-01-18 13:54:58', 1),
(698, 'Bayu Nugraha', '81234011', '81234011@student.sch.id', '8123456011', '10011', '$2y$10$S4H5Mq/SDD/ZxBNuigFSWeogGB5Nk5tpJodOB04tcQxoHEAdOTAve', NULL, 'admin', '2026-01-18 13:54:59', '2026-01-18 13:54:59', 1),
(699, 'Bunga Citra', '81234012', '81234012@student.sch.id', '8123456012', '10012', '$2y$10$R7l3bSR8ACT6yx0upmEuYOmb9mOzrcbAiirlsEZpkmLDOtoYMS9zm', NULL, 'admin', '2026-01-18 13:54:59', '2026-01-18 13:54:59', 1),
(700, 'Cahya Ramadhan', '81234013', '81234013@student.sch.id', '8123456013', '10013', '$2y$10$Yym/3jawgKSsqflwC9X8OuRA3gIhWKauF30KDdKPj8MYSE3Vaa04K', NULL, 'admin', '2026-01-18 13:54:59', '2026-01-18 13:54:59', 1),
(701, 'Cantika Dewi', '81234014', '81234014@student.sch.id', '8123456014', '10014', '$2y$10$nd21PCI83WShIsTN6q7FduiEUlZakazwphUfL8ci63bfKG5yJClhC', NULL, 'admin', '2026-01-18 13:54:59', '2026-01-18 13:54:59', 1),
(702, 'Daffa Ardiansyah', '81234015', '81234015@student.sch.id', '8123456015', '10015', '$2y$10$L7iUk.sa06msGJHyZ0FLMOJ88lahUYH5CEyrv.WTq97zQGMlwNsK6', NULL, 'admin', '2026-01-18 13:54:59', '2026-01-18 13:54:59', 1),
(703, 'Dani Firmansyah', '81234016', '81234016@student.sch.id', '8123456016', '10016', '$2y$10$HZbDzSvaN/0dUQGw65QwYe/M2OrBphAOhT1N0GWq3eyjC2S81c8ia', NULL, 'admin', '2026-01-18 13:54:59', '2026-01-18 13:54:59', 1),
(704, 'Dea Ananda', '81234017', '81234017@student.sch.id', '8123456017', '10017', '$2y$10$6UZrj/nX48Nse9mYkRB2TORgwRj0xEnunM9UHQyW1DwpSUpbdyghy', NULL, 'admin', '2026-01-18 13:54:59', '2026-01-18 13:54:59', 1),
(705, 'Dedi Kurniawan', '81234018', '81234018@student.sch.id', '8123456018', '10018', '$2y$10$zdSkLmFjgpeeSxwmRFsZZO4KnBV7CeWLtLl3cByohVxIrzlBD3lFO', NULL, 'admin', '2026-01-18 13:54:59', '2026-01-18 13:54:59', 1),
(706, 'Dewi Sartika', '81234019', '81234019@student.sch.id', '8123456019', '10019', '$2y$10$6RQInYc7gavjnLi07Yc9OuPi5qvFtOCUcqRkDhoGIfsLFcSWbT9yK', NULL, 'admin', '2026-01-18 13:54:59', '2026-01-18 13:54:59', 1),
(707, 'Dimas Anggara', '81234020', '81234020@student.sch.id', '8123456020', '10020', '$2y$10$9JGnUgKvrxgZeTTWE.bhc.aTalDLAEuRlmAj5r1sHrIjrYLehXEg6', NULL, 'admin', '2026-01-18 13:54:59', '2026-01-18 13:54:59', 1),
(708, 'Dini Aminarti', '81234021', '81234021@student.sch.id', '8123456021', '10021', '$2y$10$hu4Qw2ltsfXvRnqYcphq.ODWz7Jz/JAlGlzKBFqxGV7nKLdTVc0Wi', NULL, 'admin', '2026-01-18 13:54:59', '2026-01-18 13:54:59', 1),
(709, 'Doni Tata', '81234022', '81234022@student.sch.id', '8123456022', '10022', '$2y$10$nqD/Tc0c8f8.K9UfJBgtQO8H63eS9cp/JZCo7YSegNWU4XWVK5xXW', NULL, 'admin', '2026-01-18 13:55:00', '2026-01-18 13:55:00', 1),
(710, 'Eka Saputra', '81234023', '81234023@student.sch.id', '8123456023', '10023', '$2y$10$uZe7QaYjb9S2r/Iq9045OOLSsE3s2LV7bazDiOeZO7.yBSY.cOONG', NULL, 'admin', '2026-01-18 13:55:00', '2026-01-18 13:55:00', 1),
(711, 'Eko Patrio', '81234024', '81234024@student.sch.id', '8123456024', '10024', '$2y$10$w2WRKe0TKBOy/d.0HiFaJOPFyScqCerFtxxL0CsTDXOq4hY/py38y', NULL, 'admin', '2026-01-18 13:55:00', '2026-01-18 13:55:00', 1),
(712, 'Elly Sugigi', '81234025', '81234025@student.sch.id', '8123456025', '10025', '$2y$10$TdB3WcCCaKAQraVLvjCztesDwPb/GYPa8e4e5E4/qwvDVNDYc7QZ2', NULL, 'admin', '2026-01-18 13:55:00', '2026-01-18 13:55:00', 1),
(713, 'Fajar Sadboy', '81234026', '81234026@student.sch.id', '8123456026', '10026', '$2y$10$F1mYYHEdOhf7e6CW5txSmOmdXjRmZw19YkGdAc6OOIEQTSkJgc9xS', NULL, 'admin', '2026-01-18 13:55:00', '2026-01-18 13:55:00', 1),
(714, 'Fani Rose', '81234027', '81234027@student.sch.id', '8123456027', '10027', '$2y$10$W1C9F4Y06X95UKEZfUnoyutq5xASRcIgr.Aw6iiwQytDpeUncUVpm', NULL, 'admin', '2026-01-18 13:55:00', '2026-01-18 13:55:00', 1),
(715, 'Farhan Ali', '81234028', '81234028@student.sch.id', '8123456028', '10028', '$2y$10$RatATm107E1rTLSWOwPQ1O0V7x.k5cNwNCBAy9nxZAUYThwanXZI.', NULL, 'admin', '2026-01-18 13:55:00', '2026-01-18 13:55:00', 1),
(716, 'Fifi Lety', '81234029', '81234029@student.sch.id', '8123456029', '10029', '$2y$10$3op1Kxqf048SNbR4G6Zaf.0KYMk05MNpIZW4WquNGB43ct9JinA1G', NULL, 'admin', '2026-01-18 13:55:00', '2026-01-18 13:55:00', 1),
(717, 'Gilang Dirga', '81234030', '81234030@student.sch.id', '8123456030', '10030', '$2y$10$mVBeOTOIEJRTKZlgsGiaOeOSP.MbM4psnXsfkg19LKl36xhp0XKnq', NULL, 'admin', '2026-01-18 13:55:00', '2026-01-18 13:55:00', 1),
(718, 'Gita Gutawa', '81234031', '81234031@student.sch.id', '8123456031', '10031', '$2y$10$A.lFejKdytHxH6r2TroyqeATIL3cDu7t5SX9FdamZ49GQS5rvXcgu', NULL, 'admin', '2026-01-18 13:55:00', '2026-01-18 13:55:00', 1),
(719, 'Guntur Bumi', '81234032', '81234032@student.sch.id', '8123456032', '10032', '$2y$10$8zfbvmyifBCnfRFTve0hX.kxiOoZnueeQwx.AJ4efGKzZ.su5sPAi', NULL, 'admin', '2026-01-18 13:55:00', '2026-01-18 13:55:00', 1),
(720, 'Hana Hanifah', '81234033', '81234033@student.sch.id', '8123456033', '10033', '$2y$10$OU00nSj3w1ex5OSc/X97P.323hQ2loY7ByimbhdiD8TPMCJpCH4Ym', NULL, 'admin', '2026-01-18 13:55:01', '2026-01-18 13:55:01', 1),
(721, 'Hari Panca', '81234034', '81234034@student.sch.id', '8123456034', '10034', '$2y$10$siM7qJvNqQHjuzkgXxPCJuaFBWcj5k8AErx8qrkWsAqt62oTjXiFu', NULL, 'admin', '2026-01-18 13:55:01', '2026-01-18 13:55:01', 1),
(722, 'Hesti Purwadinata', '81234035', '81234035@student.sch.id', '8123456035', '10035', '$2y$10$p6vg5o6fN0aemL3UtuS83ual5m0IlBEhzSmA2Gi8qqb1AaMASKWZK', NULL, 'admin', '2026-01-18 13:55:01', '2026-01-18 13:55:01', 1),
(723, 'Ian Kasela', '81234036', '81234036@student.sch.id', '8123456036', '10036', '$2y$10$xFlE/.mJmLJZdjkDjCD.v.Wa7LZQTyFgA1PMFpBd6HeU/lzvLgxaq', NULL, 'admin', '2026-01-18 13:55:01', '2026-01-18 13:55:01', 1),
(724, 'Iis Dahlia', '81234037', '81234037@student.sch.id', '8123456037', '10037', '$2y$10$Bc0KiF41T6v95siQk1jEB.3RihCPU1lhJvUfh69q5OK3K.b32ihD.', NULL, 'admin', '2026-01-18 13:55:01', '2026-01-18 13:55:01', 1),
(725, 'Indra Bekti', '81234038', '81234038@student.sch.id', '8123456038', '10038', '$2y$10$0JzOMZ4jfWRn46TrR9UDOepU5.RUY9Vhp7GCDL6hNuKRfZ0b0YGTO', NULL, 'admin', '2026-01-18 13:55:01', '2026-01-18 13:55:01', 1),
(726, 'Indah Permatasari', '81234039', '81234039@student.sch.id', '8123456039', '10039', '$2y$10$0xYSvMJ3lFPcObmofpKib.fN/0aHO3CNAe2VPbn7R6jels691UM2C', NULL, 'admin', '2026-01-18 13:55:01', '2026-01-18 13:55:01', 1),
(727, 'Jaka Sembung', '81234040', '81234040@student.sch.id', '8123456040', '10040', '$2y$10$XSGT2Gb/HogkUafIzCCfpOlQL6g2wwqTALfEHwxnFdi/KgdgTunmy', NULL, 'admin', '2026-01-18 13:55:01', '2026-01-18 13:55:01', 1),
(728, 'Juwita Bahar', '81234041', '81234041@student.sch.id', '8123456041', '10041', '$2y$10$i4MOcClwLiVPk/c6rOHK0O4Ll.IY4nR3fc7UDVt0NOIJW49yH2Z7m', NULL, 'admin', '2026-01-18 13:55:01', '2026-01-18 13:55:01', 1),
(729, 'Kevin Julio', '81234042', '81234042@student.sch.id', '8123456042', '10042', '$2y$10$oVjq7nyBoOyFD6VeZlt9AOlByfIZu2rz.YPKnZrjSiacZvHkB0Wpu', NULL, 'admin', '2026-01-18 13:55:01', '2026-01-18 13:55:01', 1),
(730, 'Kiki Amalia', '81234043', '81234043@student.sch.id', '8123456043', '10043', '$2y$10$pk0upDfIcOzQ4Ruh4HmlL.S/sAazd2cuY/WfhkOIxmrGR76XVwz5q', NULL, 'admin', '2026-01-18 13:55:01', '2026-01-18 13:55:01', 1),
(731, 'Lukman Sardi', '81234044', '81234044@student.sch.id', '8123456044', '10044', '$2y$10$SwxjtK9Tyj0UK.qoNik3HeeSXIyA1ol5tKT1Pn3eyNdBeQYO9r2Qe', NULL, 'admin', '2026-01-18 13:55:01', '2026-01-18 13:55:01', 1),
(732, 'Luna Maya', '81234045', '81234045@student.sch.id', '8123456045', '10045', '$2y$10$lI.ATHcdx1dRd58SPS9lMu5NF94T41C/mk0oY.U8EoaH2rFoC2x8O', NULL, 'admin', '2026-01-18 13:55:02', '2026-01-18 13:55:02', 1),
(733, 'Marcel Chandrawinata', '81234046', '81234046@student.sch.id', '8123456046', '10046', '$2y$10$fkRKAyljPeRSa3XPL.QqKecdIBBmS/nurGxs2rixROVlzbUcZwOea', NULL, 'admin', '2026-01-18 13:55:02', '2026-01-18 13:55:02', 1),
(734, 'Marshanda', '81234047', '81234047@student.sch.id', '8123456047', '10047', '$2y$10$hwkr3.CFBqqJqfTMlW/wseJmaTNZQNGu5lKbzPTaDL2W6csSdXsZC', NULL, 'admin', '2026-01-18 13:55:02', '2026-01-18 13:55:02', 1),
(735, 'Nabila Syakieb', '81234048', '81234048@student.sch.id', '8123456048', '10048', '$2y$10$QKSbfizHTtGprq0Q5mWeEOlYi8DpznYsMZho9xRFF85/kwmyJ1ho2', NULL, 'admin', '2026-01-18 13:55:02', '2026-01-18 13:55:02', 1),
(736, 'Raffi Ahmad', '81234049', '81234049@student.sch.id', '8123456049', '10049', '$2y$10$WBnlKouFxsgQ.yG8DpURpuJ1xdOq35qLEHgqWER7Qg8w89atxedzK', NULL, 'admin', '2026-01-18 13:55:02', '2026-01-18 13:55:02', 1),
(737, 'Raisa Andriana', '81234050', '81234050@student.sch.id', '8123456050', '10050', '$2y$10$0CvcRGZ/bTqWyujhn4a2ou7gYqnOna8mMYDWjwnLghlKTqjlOiKfG', NULL, 'admin', '2026-01-18 13:55:02', '2026-01-18 13:55:02', 1),
(791, 'Budi Santoso, S.Pd', '199001012022011001', '199001012022011001@sekolah.id', '081234567890', NULL, '$2y$10$3ChcmEd0yUVtvOu5RZwiYOtjKKGRxK6emsYMTS4pts2HDMRp5L4C6', NULL, 'guru', '2026-01-18 15:36:12', '2026-01-18 15:36:12', 1),
(792, 'REGISKA ALMAIRA OKTAFIANA', '0105197771', NULL, NULL, NULL, '$2y$10$Q2q7Fsv6/AFYkx4cd.WstOvyMOBeV69M.iCL5WNx87qS5oSBb4Twu', NULL, 'siswa', '2026-01-30 12:44:33', NULL, 1),
(793, 'MOHAMAD RIZKY FEBRIYANTO', '3106338266', NULL, NULL, NULL, '$2y$10$GImqoxZGDkdNqUyrMH4Xh.6X3GhHqyzXjiKhpDvRbr/2ZYKOiWIo6', NULL, 'siswa', '2026-01-30 12:44:33', NULL, 1),
(794, 'NAYLA OKTOVIANI ALMIRA PUTRI', '0093171796', NULL, NULL, NULL, '$2y$10$hvk7QJ6X3t8xMOULS7v/vehpOFQDm9pvFuZQeVtOdFySKcd7w9raq', NULL, 'siswa', '2026-01-30 12:44:34', NULL, 1),
(795, 'RIZKI ILHAM SATARI', '0098543075', NULL, NULL, NULL, '$2y$10$ySVSkUtYQGwNl6ZBgbL1ouaWuiEO3vSHe8/JTqNyc6njIs/KQjv4a', NULL, 'siswa', '2026-01-30 12:44:34', NULL, 1),
(796, 'UTARI WULANDARI', '0092585873', NULL, NULL, NULL, '$2y$10$jtKqvxYmcd7nduer2WgcKutLrxeiEGDZeIxjCNJHJw54EkS7LaOJC', NULL, 'siswa', '2026-01-30 12:44:34', NULL, 1),
(797, 'EVA SITI BADRIAH', '0098370552', NULL, NULL, NULL, '$2y$10$7WWmOHEaq6OciF4/ip7A5OsRtPu7lNQwB9VzqMJ/RkgqTQKeGG0Ou', NULL, 'siswa', '2026-01-30 12:44:34', NULL, 1),
(798, 'REVALINA EGISNIA', '0078422539', NULL, NULL, NULL, '$2y$10$xMuOHNLhuo5bIvLlR7k96etmEl7TyLMN6e8GnbDCFKpfdQOjprOPW', NULL, 'siswa', '2026-01-30 12:44:34', NULL, 1),
(799, 'YESA DWI PUTRI', '0085314086', NULL, NULL, NULL, '$2y$10$Jqq7UTfcS7xxJ0qaBIAEY.ThWEHQBjjw7bVsFWmL284GfhHwPTe8S', NULL, 'siswa', '2026-01-30 12:44:34', NULL, 1),
(800, 'RIKI RAHAYU', '0105643514', NULL, NULL, NULL, '$2y$10$ptPQUYeOj/.a1D2EzzTYUuOh7SBTg0gFUocLu/.fDcOnG1ffFq/JO', NULL, 'siswa', '2026-01-30 12:44:34', NULL, 1),
(801, 'SYARIF FADILLAH', '0096762404', NULL, NULL, NULL, '$2y$10$l0TeZC.O4a8anZ2s1.BAEuVBPqDed.gW4m2Zk2ouz939A2Y3bZJG.', NULL, 'siswa', '2026-01-30 12:44:35', NULL, 1),
(802, 'RIFKI RAMADANI', '0044425383', NULL, NULL, NULL, '$2y$10$NyyiKheNPw7e1SyWHUhgVe6jPARmdc7gWLmzDjqgxbIHanY8APPeq', NULL, 'siswa', '2026-01-30 12:44:35', NULL, 1),
(803, 'AFRILLIA ANGGIA SUFY', '0108073279', NULL, NULL, NULL, '$2y$10$v8JjvslNevPz76AX6tzDW.tIhTbpx.HlcfUmHbGsH2ugiqZRc.o0O', NULL, 'siswa', '2026-01-30 12:44:35', NULL, 1),
(804, 'SULAEMAN', '0082078022', NULL, NULL, NULL, '$2y$10$ugClg4R5u7vIR7D5YM1YdurU41E9i.rt8faWjZF1lWF/a7X9n9K9C', NULL, 'siswa', '2026-01-30 12:44:35', NULL, 1),
(805, 'MUHAMMAD HAFIDZ ALIMUDIN', '0085579351', NULL, NULL, NULL, '$2y$10$0dz3f5lf.pOlVhaC8DEXaeTvUD5R/YcjERf13t9HpAq0X8uKIiPe6', NULL, 'siswa', '2026-01-30 12:44:35', NULL, 1),
(806, 'RIYADI TRI SUTRISNA', '0087231267', NULL, NULL, NULL, '$2y$10$772Aurx/gRb9nEnSZLL7feAkgRHAkjaJV7es6ENjC8HfPCE.qe1Jy', NULL, 'siswa', '2026-01-30 12:44:35', NULL, 1),
(807, 'Meisya Nurul Latifah', '3077711335', NULL, NULL, NULL, '$2y$10$xcBQVxzjskvPAIEC.loDre1dymMaddvp3NO1B3AnfPYGq2gpKjKSq', NULL, 'siswa', '2026-01-30 12:44:35', NULL, 1),
(808, 'SAFITRI', '0108639103', NULL, NULL, NULL, '$2y$10$IVpgqV8IPFVVesAgGNHB9eo/McqjGE2x/iDJE9cBqiqbCTG/eMpo6', NULL, 'siswa', '2026-01-30 12:44:36', NULL, 1),
(809, 'WIFA ROSLINA', '0087870269', NULL, NULL, NULL, '$2y$10$kgNsinkCX2bLBNIowmGwpOkECfocdcfNMSEArr5jnwav4j5WNNYA6', NULL, 'siswa', '2026-01-30 12:44:36', NULL, 1),
(810, 'WENDI BAYU SUKMA', '3097880430', NULL, NULL, NULL, '$2y$10$FKqXnGheVQNhcbzqljn/rOxy4kMbPcreBnZl22BYfZdDSOqIfvAiC', NULL, 'siswa', '2026-01-30 12:44:36', NULL, 1),
(811, 'GIANT RIZKY PRATAMA', '0107193081', NULL, NULL, NULL, '$2y$10$qmZ0N/Am7Nu5PPlql5G2KejW3lR4o5Y9mCONMzZbUtd4Zpi38uqEe', NULL, 'siswa', '2026-01-30 12:44:36', NULL, 1),
(812, 'ANGGY NURWYDIATY', '0104880623', NULL, NULL, NULL, '$2y$10$jJ866l4i59XGHI4.ts2kue7HWJQ4TR1Yap5q5QtTVXvT4agXb12WO', NULL, 'siswa', '2026-01-30 12:44:36', NULL, 1),
(813, 'MAULIDA SUSILAWATI MAISYARAH', '0096121571', NULL, NULL, NULL, '$2y$10$EcuP8ueQUHWMsDsvfzV22es8qe/ps2p73BiiKdM1.m8xcrh8CgkEy', NULL, 'siswa', '2026-01-30 12:44:36', NULL, 1),
(814, 'MELATI SOLEHA', '0099627101', NULL, NULL, NULL, '$2y$10$L.BcCqMkp2yvHSkUNbauDeDVbcfdLG6dr0azJQbCMaBajeKvch0Hm', NULL, 'siswa', '2026-01-30 12:44:36', NULL, 1),
(815, 'SRI HANDAYANI KOMALASARI', '0099393697', NULL, NULL, NULL, '$2y$10$uZXENTXGpD6uxdTcgTsSGelSQcygSctYh77zBMb41OWqQ.4RujSpK', NULL, 'siswa', '2026-01-30 12:44:36', NULL, 1),
(816, 'KELVIN FAUJI KAMAL', '0105156912', NULL, NULL, NULL, '$2y$10$fQoCxJcXhQ1nNHAZioUAFe935NS1XIUbYXHqxIVdOEHLoekwR6kRG', NULL, 'siswa', '2026-01-30 12:44:37', NULL, 1),
(817, 'LUTFIA HANI RAMADANI', '0096039336', NULL, NULL, NULL, '$2y$10$1PVPsV7IjEChqHwV1zH6Y.ogdL8XCvGVyADUEvMc0p0Q3/JLivNNm', NULL, 'siswa', '2026-01-30 12:44:37', NULL, 1),
(818, 'WIKA DILA SAFITRI', '0105767389', NULL, NULL, NULL, '$2y$10$v1NUK23SUrigGrdBgoJA5uxZkvJECjDoOXVcjJffPpF6csoICSKp6', NULL, 'siswa', '2026-01-30 12:44:37', NULL, 1),
(819, 'WILDA APRILIANA', '0099620158', NULL, NULL, NULL, '$2y$10$PFyJdFiIK/F4XekB8aStAOmsEOoGHaMXHqx2QYNWSuhqTFUhP.0p6', NULL, 'siswa', '2026-01-30 12:44:37', NULL, 1),
(820, 'Aprila Fitriyani', '0094779426', NULL, NULL, NULL, '$2y$10$zDqaGCf.jYCeXNu.pAKg6uF3JB..Rj1jp.sIeH1bGyXQ7Ai39XB7S', NULL, 'siswa', '2026-01-30 12:44:37', NULL, 1),
(821, 'Aufa Zahranti', '0087988588', NULL, NULL, NULL, '$2y$10$6gvcFH.ZyGO0cmO/CWD6kOZnj8pvsf7ctIJT25sQmFtETLsGrT5RC', NULL, 'siswa', '2026-01-30 12:44:37', NULL, 1),
(822, 'ANISA NUR ISLAMIAH', '0081489427', NULL, NULL, NULL, '$2y$10$VimQnL4ELB48dM/FqQd0beDwt7haD52BXO5pz57cunR7Ns.ugd6e2', NULL, 'siswa', '2026-01-30 12:44:37', NULL, 1),
(823, 'FITRIYAH DWI ARIANI', '0098909069', NULL, NULL, NULL, '$2y$10$m.qgGlyXfkU5dIrMG1bYNezkWO/1IgRRVH7OEyl/pfEoVeNEHUFoS', NULL, 'siswa', '2026-01-30 12:44:38', NULL, 1),
(824, 'RAZKA AULIA PUTRI', '0108298323', NULL, NULL, NULL, '$2y$10$Jw/Wxb.s2uV42vSAkThSQu2tCkP7PivKnU0ax.jbkOki3.JGe2auS', NULL, 'siswa', '2026-01-30 12:44:38', NULL, 1),
(825, 'FAHRUL FAJRIAWAN', '0109803842', NULL, NULL, NULL, '$2y$10$MHJtQpqPV/JZwLu58kmQouuWraUOLrY/6E2b3RsQ9Ld/jnmGbVSyW', NULL, 'siswa', '2026-01-30 12:44:38', NULL, 1),
(826, 'IHSAN AKBAR', '0102278235', NULL, NULL, NULL, '$2y$10$dByjYfKk59iCF8bJwNHed.sxBADVAl68yD6ii5jUzLZ/tS8640X/y', NULL, 'siswa', '2026-01-30 12:44:38', NULL, 1),
(827, 'FADIL MAULANA', '0073743916', NULL, NULL, NULL, '$2y$10$gP459IZgOLliiQJiYV.5i.T8MaLD6SfpRbnlQPQ8aNvVWkrGq.XQa', NULL, 'siswa', '2026-01-30 12:44:38', NULL, 1),
(828, 'ZUDIT DELISTA HASANI QOLBI', '0078754507', NULL, NULL, NULL, '$2y$10$Nyvt0Lv2R36eMD83zGsXye71jLhJ9uZpXrbyBtVb/9DdTPY12J2N6', NULL, 'siswa', '2026-01-30 12:44:38', NULL, 1),
(829, 'RAIHAN RIZKY NASRULLAH', '0076616824', NULL, NULL, NULL, '$2y$10$JxYwZ4ZXlqM3zjHjN1MYfuRLUe6.ih8eSOcYNTLtnCZ9Th0txiShq', NULL, 'siswa', '2026-01-30 12:44:38', NULL, 1),
(830, 'NAFADILAH MUFIDA', '0093004898', NULL, NULL, NULL, '$2y$10$GfHNqyggoJpdDuB5ZDA6ceNjLRd7bK7bO0YHBGuUjkVCs.0aEnzeG', NULL, 'siswa', '2026-01-30 12:44:39', NULL, 1),
(831, 'RAISA SRI RAHAYU', '0072707009', NULL, NULL, NULL, '$2y$10$vNQkOqT6yiD5ORwP5rQb3OLbs348aPqEUVkucmw6g172SaF/nVija', NULL, 'siswa', '2026-01-30 12:44:39', NULL, 1),
(832, 'AMELIA RAMADHANI', '3070645053', NULL, NULL, NULL, '$2y$10$jvT9jJrmGmkzI4s6Nq.Wue4pJFe5IFQ7arj2VtZWQCBVHIy6uJ1nO', NULL, 'siswa', '2026-01-30 12:44:39', NULL, 1),
(833, 'SRI LASTRI HARTINI', '0109663264', NULL, NULL, NULL, '$2y$10$2fLk/PJgTtoMlpWWq9iQXOvobRWgFzUcTrL1ANOaD2WHHKb65yUEK', NULL, 'siswa', '2026-01-30 12:44:39', NULL, 1),
(834, 'ICA APRILLIA', '0101638254', NULL, NULL, NULL, '$2y$10$s6nOyOCqbq9ppJk5qZE4UuzrNJQa.M0JK3i47Miw7xYM221mWRAY2', NULL, 'siswa', '2026-01-30 12:44:39', NULL, 1),
(835, 'ARFAN DZKRI FANIANSYAH', '0097414143', NULL, NULL, NULL, '$2y$10$XGZINgQw8n2LWrULrKeZp.IvL6E8Ps/dgqLY.eRgJRKClJwOzENjW', NULL, 'siswa', '2026-01-30 12:44:39', NULL, 1),
(836, 'ICHA INDRIANI', '0081908485', NULL, NULL, NULL, '$2y$10$zbqtA3dPVOrdzoCIE59H9.qQNgUV7IONylQtiHaS.yonD0uHOnOCW', NULL, 'siswa', '2026-01-30 12:44:39', NULL, 1),
(837, 'RAYAN ALAMSYAH', '0073608974', NULL, NULL, NULL, '$2y$10$hKprH2O0yy3yglReenL.6eF0insgokU5Aj3UOx7UB0LNcZoFZ8Eu.', NULL, 'siswa', '2026-01-30 12:44:39', NULL, 1),
(838, 'CHIKA HANADILA', '0078224480', NULL, NULL, NULL, '$2y$10$H6WgjiqQcJcKiF2b8cd4FeeiGSsfHB3CoNM.KFoqArKeDQ9oPMFF2', NULL, 'siswa', '2026-01-30 12:44:40', NULL, 1),
(839, 'KELVIN AGUSTIAN', '0091822555', NULL, NULL, NULL, '$2y$10$F/5zsz6w9B3P55oSSuWoEu1lcLAaFuWNJMJeJZaE8CUGi9o/L9brS', NULL, 'siswa', '2026-01-30 12:44:40', NULL, 1),
(840, 'RIZKA MAULIDA ROHIMA', '3095741699', NULL, NULL, NULL, '$2y$10$jtrBCTsg9SgS5W/4tMIM1OssCoKLWs.9ubmeXwaF6DQnhHCqslAJq', NULL, 'siswa', '2026-01-30 12:44:40', NULL, 1),
(841, 'SISKA AULIA WATI', '0085102549', NULL, NULL, NULL, '$2y$10$xdJULH2ATfrINBVGM8UlQudDqY1meX1OTJWCf3tuFndQyiDvxIMmO', NULL, 'siswa', '2026-01-30 12:44:40', NULL, 1),
(842, 'AMBAR NUR FADHILLAH', '0097739175', NULL, NULL, NULL, '$2y$10$Y7YVDTP3f4.WQ4FvNuXMSepAeJPM./ykwv0T0LiTSowJ1.a1c2yQu', NULL, 'siswa', '2026-01-30 12:44:40', NULL, 1),
(843, 'SITI AZIZAH DEBORAH', '0104138021', NULL, NULL, NULL, '$2y$10$pD84kYN9PD/8CmmGypc3uulqq4ArmQCkk4t.zXPDuYTi1B3nsQOC.', NULL, 'siswa', '2026-01-30 12:44:40', NULL, 1),
(844, 'MUHAMAD IKHSAN ZULHAKIM', '0101524656', NULL, NULL, NULL, '$2y$10$TV8eJ/5Gsw5kMM9oIbilGe68gBkpSEelV/7NNVjoDP76IiEYWfBEq', NULL, 'siswa', '2026-01-30 12:44:41', NULL, 1),
(845, 'SHAQILA ZINGGA ARRIZQIA SUDRAJAT', '0088798965', NULL, NULL, NULL, '$2y$10$nCT8nHnAbOzztctQEBCNb.FpE9yAR9soPYx68aqKbQnVOSbHe7Ix2', NULL, 'siswa', '2026-01-30 12:44:41', NULL, 1),
(846, 'MUHAMMAD FAJAR JAENUDIN', '0083127372', NULL, NULL, NULL, '$2y$10$nGAmyF6kl7epY/5zq7b7SOoDtTVdpCD7QkBKIwbJX1ym9vGmfBM3a', NULL, 'siswa', '2026-01-30 12:44:41', NULL, 1),
(847, 'MUHAMMAD IRSYAD', '0083244013', 'muhammad.irsyad24908@smk.belajar.id', '085280583085', '1080546870', '$2y$10$TUSvfR6.DOCqJkeCPxV/uezQXEf7KksFY9ggQvkUn5epVAlSGdjR.', NULL, 'siswa', '2026-01-30 12:44:41', NULL, 1),
(848, 'MARISKA LESTARI DEWI', '0088479185', NULL, NULL, NULL, '$2y$10$X6cHHv.O8P7NW8P6bUbyPuJbrGDOGO.yQW0NnPwuML5qGwKHE6uFy', NULL, 'siswa', '2026-01-30 12:44:41', NULL, 1),
(849, 'LIANI NURLAILASARI', '0086088897', NULL, NULL, NULL, '$2y$10$tzm/KCxPHE3EUSrn4OyV7.45j2DqHVYJyCDHk9sIZ6uHQ0Jt8n0qC', NULL, 'siswa', '2026-01-30 12:44:41', NULL, 1),
(850, 'KARINA PUSPITA DEWI', '0076277240', NULL, NULL, NULL, '$2y$10$WRF/cehmrj17hbaIkUnaMOUC5cOHRtzPJSOXcrWlrb478DrI6rEE6', NULL, 'siswa', '2026-01-30 12:44:41', NULL, 1),
(851, 'INTAN LUTFIA ANAISHA', '0098323737', NULL, NULL, NULL, '$2y$10$ugIpxa2QffWgy6ShKPluA.t5c.TqaeVHHBe7du0Z6eEzia.5t4uvS', NULL, 'siswa', '2026-01-30 12:44:41', NULL, 1),
(852, 'DHAVA NURFAZAR', '0095235273', NULL, NULL, NULL, '$2y$10$ExIxTZDvvcuzYkNycTejg.Gi34/S2Pw5fWC3I/9eV6DEeEJx/y5T6', NULL, 'siswa', '2026-01-30 12:44:42', NULL, 1),
(853, 'YONGKI AGUSTIO', '0083314236', NULL, NULL, NULL, '$2y$10$a.0FVvULZvZhF5IKDQNhMOcKCrICoisRyCaMcnuxxN98A1zK2VGC2', NULL, 'siswa', '2026-01-30 12:44:42', NULL, 1),
(854, 'Muhamad Saepul Aripin', '0079938780', NULL, NULL, NULL, '$2y$10$Fnpbih3Q0LpsUb.DQhoeT.AZ8.HTGqGNu.IvKpkSu1kAlnpdLFYtq', NULL, 'siswa', '2026-01-30 12:44:42', NULL, 1),
(855, 'IIS RISMAWATI', '0087459003', NULL, NULL, NULL, '$2y$10$DdD9pyJ8/TOLL1DLUHrZpuhfDQU/qQATMHDwoMMWUl6k/bPpbPcUy', NULL, 'siswa', '2026-01-30 12:44:42', NULL, 1),
(856, 'WIKI WINARDI', '0099070178', NULL, NULL, NULL, '$2y$10$0mKcijuGEYCGJPhwKNj3DOZM8EQK64MbP9WVKC1jFLtls0JfhAaqS', NULL, 'siswa', '2026-01-30 12:44:42', NULL, 1),
(857, 'Siti Zainatu Jahra', '0084987611', NULL, NULL, NULL, '$2y$10$YDNlfK17Frfc2kmXY/6Rb.c3cM7Vje2FCcNsGLY02n8JWdI6dEBdu', NULL, 'siswa', '2026-01-30 12:44:42', NULL, 1),
(858, 'DAFA SEPTIADI', '0089386510', NULL, NULL, NULL, '$2y$10$OQkA2jS4Qu29z1hzZ/qDFOpRj396KsrS.isk9mXPNmZihvfEPp9KG', NULL, 'siswa', '2026-01-30 12:44:42', NULL, 1),
(859, 'NETHA JUNIAR', '0093109918', NULL, NULL, NULL, '$2y$10$hfjksCdR6HXWVy3TnflaF.iXgY.9M/00NFUGgPgGGLo.98g1ZrzqG', NULL, 'siswa', '2026-01-30 12:44:43', NULL, 1),
(860, 'NAJLA KHAIRUNNISA SOPIAN', '0096153033', NULL, NULL, NULL, '$2y$10$lGX1.ox.M/Jgwy4ylTg5a.hYbMT9sTR.k3G2s0joqo.kGfJIM7kN2', NULL, 'siswa', '2026-01-30 12:44:43', NULL, 1),
(861, 'ATBIK ABIL FIRDAUS', '0061720192', NULL, NULL, NULL, '$2y$10$MkSDTjzna8J7s.E9J6OlwumGBHlVEsRYLhqoJZ81vZv0u4pefBxzq', NULL, 'siswa', '2026-01-30 12:44:43', NULL, 1),
(862, 'SANDRA SEFIRA MARGARETA', '0073590014', NULL, NULL, NULL, '$2y$10$IGfAjV1GTxTI36U.L8Zr4.GpjFC1Wm9fQFvAV/GNWNwZbRDas43K6', NULL, 'siswa', '2026-01-30 12:44:43', NULL, 1),
(863, 'INDRI PEBTRI SAPARWATI', '0086593724', NULL, NULL, NULL, '$2y$10$IV.x5e4//Nu3OotEgtblq.pl5c4/r4IWRZCKJjfBpDTPg/CFpRpsC', NULL, 'siswa', '2026-01-30 12:44:43', NULL, 1),
(864, 'FANNY NURFAUZIAH', '0099352901', NULL, NULL, NULL, '$2y$10$n0cd1lL4em3uVItDAHY4du1pzwWoKOvNkmY5UkVzP0..YMHHA2pxq', NULL, 'siswa', '2026-01-30 12:44:43', NULL, 1),
(865, 'FARREL YUDHISTIRA IRAWAN', '0086015519', NULL, NULL, NULL, '$2y$10$M1Gq3Hcjj745pOWer2XHX.jL8RePL837NhvhMDkZUtR0Te6OT6V1y', NULL, 'siswa', '2026-01-30 12:44:43', NULL, 1),
(866, 'CANTIKA APRILIA', '0107842931', NULL, NULL, NULL, '$2y$10$cIqUMdROD4vR1kYM256hI.eYfj39nDpLVQ74VsIHmRI7v5XK2xBy.', NULL, 'siswa', '2026-01-30 12:44:44', NULL, 1),
(867, 'LAREINA LEGIA HAKIM', '0105609408', NULL, NULL, NULL, '$2y$10$CIwiApRl1svCJMPr.af4IujwLHVjnYcpZ1uuKveiOSl3va/Opeqky', NULL, 'siswa', '2026-01-30 12:44:44', NULL, 1),
(868, 'FIRMAN RAMADHAN', '0073486437', NULL, NULL, NULL, '$2y$10$fOUhO1GoAQyzPG0EcNVwCOAwZbeXEv1KdpikOIcZ6FfE5xURKhGym', NULL, 'siswa', '2026-01-30 12:44:44', NULL, 1),
(869, 'ADITYA PRATAMA', '0093560392', NULL, NULL, NULL, '$2y$10$EBWeFR6ul24uR2s8pMmvnOUJBCEIR/oUSRPNgIoip/N7BbwO4ft6G', NULL, 'siswa', '2026-01-30 12:44:44', NULL, 1),
(870, 'ALDI SAHRONI', '0092412603', NULL, NULL, NULL, '$2y$10$ZHBBRint8HNFqLxvIbuAmu8gp0lwfXl8wKFjepXNMy0bwGEQmjZIi', NULL, 'siswa', '2026-01-30 12:44:44', NULL, 1),
(871, 'Zafar Shidiq', '0083266854', NULL, NULL, NULL, '$2y$10$fHIuGGPPspr7qNDxg2pMg.7quYpZzcetLk9m0PQYQ747oew.ZurCG', NULL, 'siswa', '2026-01-30 12:44:44', NULL, 1),
(872, 'NOVI ANANDA', '0076944895', NULL, NULL, NULL, '$2y$10$ZLgAR/l/bF47.RGM45fCIOxyoQ5WQ/.YsntkSYzIz2gDQ2iFyGSUG', NULL, 'siswa', '2026-01-30 12:44:45', NULL, 1),
(873, 'NADIA ERLYANTI', '0087145906', NULL, NULL, NULL, '$2y$10$8uoT0nSKEW/xHtaXK5fyRur1OFx93gg5PEJAF6aQ6uKyoU8zQWO1G', NULL, 'siswa', '2026-01-30 12:44:45', NULL, 1),
(874, 'AGIS MUHAMAD JANUAR', '0106614091', NULL, NULL, NULL, '$2y$10$iev0TTropLbYUDUWWCn4V.Oehx5iMEzBjnlaSIM0ZUJQzw5CFB3mO', NULL, 'siswa', '2026-01-30 12:44:45', NULL, 1),
(875, 'SESIL SULISTIANI', '0105184100', NULL, NULL, NULL, '$2y$10$4qi3BOyxAVWOzz7.kPQ8VOdALVMt0LHPfryGhuJXysId0q5t1F3ZC', NULL, 'siswa', '2026-01-30 12:44:45', NULL, 1),
(876, 'IRFAN MULYADI', '0087825511', NULL, NULL, NULL, '$2y$10$NokGmdjWteNXwW3QB764i.pvaQjlP6HtvER7bDWvAUQe1lBl.l/cG', NULL, 'siswa', '2026-01-30 12:44:45', NULL, 1),
(877, 'IIS SRI NURHAYANI', '0097873936', NULL, NULL, NULL, '$2y$10$/53XzKV8C.tIiJdDi5QEs.MeewZDX7A0p78Hqrv6oneAOQYqXis/y', NULL, 'siswa', '2026-01-30 12:44:45', NULL, 1),
(878, 'RISTAN RAHAYU RUDIANSYAH', '0081981356', NULL, NULL, NULL, '$2y$10$VDzgHpudnhREvZmXWhkCg.77P0p5QFZRmi5qt0kfwOBN0/pDFfJZO', NULL, 'siswa', '2026-01-30 12:44:45', NULL, 1),
(879, 'GILANG RAHMAT ANUGRAH', '0102215859', NULL, NULL, NULL, '$2y$10$PYHqy53bFqs/64ZCXh9VauuTvH8gO2KBeZR.aMsBEGGLuaWtak9AO', NULL, 'siswa', '2026-01-30 12:44:46', NULL, 1),
(880, 'RIZAL NUGRAHA', '0081656364', NULL, NULL, NULL, '$2y$10$ZaR8l2TT8CnETomyC5.A1ugUufh34bhnvg5945fkWkl5GXf6YBKvK', NULL, 'siswa', '2026-01-30 12:44:46', NULL, 1),
(881, 'Nailan Nikmah Lubis', '0086242479', NULL, NULL, NULL, '$2y$10$5SSFsGl3.XrBF2kZ5jeMMuaihRlIpMyFSr8QG4lyFj4YreXCpk0X2', NULL, 'siswa', '2026-01-30 12:44:46', NULL, 1),
(882, 'REYFALDI WALUYO', '0092694024', NULL, NULL, NULL, '$2y$10$6OOhQTH1JnTEMTxtbYQFhOXkvkBgAOgLjsymXMtZ8Dkl3.VLS7JoK', NULL, 'siswa', '2026-01-30 12:44:46', NULL, 1),
(883, 'SAILA FAUZIATUNNISA', '0096392028', NULL, NULL, NULL, '$2y$10$fPnoJ8/q8kmYWnKqHiaG4Og3IGx.zOHo1id1QFzbEUPBESC2x0ljO', NULL, 'siswa', '2026-01-30 12:44:46', NULL, 1),
(884, 'AYU HARTATI', '0074650867', NULL, NULL, NULL, '$2y$10$bkC8S.XzpfdB28G9Bs8xG.uvEAevEq4Gqz1ffqPz6roJmQ/ULc1zC', NULL, 'siswa', '2026-01-30 12:44:46', NULL, 1),
(885, 'WANDA PRAMESTA', '0088223990', NULL, NULL, NULL, '$2y$10$vDSQSMlCq9mPLYu/KVunl.BuNsqflfr36zDc12jBmp2OJqNphY7Ze', NULL, 'siswa', '2026-01-30 12:44:46', NULL, 1),
(886, 'SYIFA AULIA PUTRI UTAMI', '0106964935', NULL, NULL, NULL, '$2y$10$QOsgBjI4m1o5pQYsZP1pleqPyDqSAv7Nueq7aYM0QbKgmlcwywfLa', NULL, 'siswa', '2026-01-30 12:44:47', NULL, 1),
(887, 'NURUL MUTIAROH', '0102252701', NULL, NULL, NULL, '$2y$10$J37yfsgKEO90AOsWygbRO.aUUbl8TGvy5maNYtaenbGnfLzAL0ULy', NULL, 'siswa', '2026-01-30 12:44:47', NULL, 1),
(888, 'NITA AULIA', '0088297767', NULL, NULL, NULL, '$2y$10$8XOS034l8HLcKXW2XugWUO7Snw.B7nboS077oOd2np3J5K1QFeltq', NULL, 'siswa', '2026-01-30 12:44:47', NULL, 1),
(889, 'SAEPUL FIKRI', '0099347931', NULL, NULL, NULL, '$2y$10$4TPEvI9wF0pdQmeZByknP.mqNm/EDALV/oi9o5lLjZQ0HAbxkWsD.', NULL, 'siswa', '2026-01-30 12:44:47', NULL, 1),
(890, 'RIZKA ALYASYA PUTRI', '0097002833', NULL, NULL, NULL, '$2y$10$6dSGjOEk2wXJ155BSRuGhOEKuhTvJ.kXD3XRhUYfTGCWVF9Hlfz4e', NULL, 'siswa', '2026-01-30 12:44:47', NULL, 1),
(891, 'Rahma Siti Ayu', '0096721114', NULL, NULL, NULL, '$2y$10$meD2BtzQcQNLAQu9VfucXeW0v/RqCb3QsNR5xPkBQMOP0V8V/Z/EK', NULL, 'siswa', '2026-01-30 12:44:47', NULL, 1),
(892, 'TIARA DESVITA', '0106646484', NULL, NULL, NULL, '$2y$10$gAp7qahifhBi55gBRJ6/0.f7J6e6LXY3hbP4eqcXpf4jBj7rqgEIq', NULL, 'siswa', '2026-01-30 12:44:47', NULL, 1),
(893, 'GALUH IKHSAN SOPIAN', '0086249481', NULL, NULL, NULL, '$2y$10$QyH8rgM/Gi6db/ns1hxQAeTAit9dNlyJI3z49IkyU694f2FL.87JG', NULL, 'siswa', '2026-01-30 12:44:48', NULL, 1),
(894, 'CHIKAL RAMADHAN HIDAYATULLOH', '0077892338', NULL, NULL, NULL, '$2y$10$fvmyb7ut662N6UeH48blzO4///qsBHCB2GlvtEuUuKvq4daVdD1Ny', NULL, 'siswa', '2026-01-30 12:44:48', NULL, 1),
(895, 'ADILLA RAISYA PUTRA', '0096562070', NULL, NULL, NULL, '$2y$10$RW3yllkXRnIDycKMKFimqOYw297EgNeKx0WVgZetfw/2pTyXcOM1e', NULL, 'siswa', '2026-01-30 12:44:48', NULL, 1),
(896, 'YUDHA NUGRAHA', '0109413096', NULL, NULL, NULL, '$2y$10$5YARNGTclRTKIyuEglq6DeTAnnHPJRD0VV8fBuSbEvkRSM6HkJ9X.', NULL, 'siswa', '2026-01-30 12:44:48', NULL, 1),
(897, 'DIMAS ADI SAPUTRO', '0091086226', NULL, NULL, NULL, '$2y$10$jpfdnsUFBq68S1x1/53UFeEg5Y0FOmjDljY.qTamKdSczA4Ti3qWi', NULL, 'siswa', '2026-01-30 12:44:48', NULL, 1),
(898, 'SELISTIA JAYA DIPURA', '0086895164', NULL, NULL, NULL, '$2y$10$8bh6qzsjZf8ZlQ9uYGJslemX4FME2LL2KCJzjGICcIUKsrIMYz/RS', NULL, 'siswa', '2026-01-30 12:44:48', NULL, 1),
(899, 'MUHAMMAD RAYHAN AL FARIZI', '0087899649', NULL, NULL, NULL, '$2y$10$mpiwMnOqx0qoIelSysNmGOMmIMv/SbFEtn8N0kTAf9.Bb2qIbPgj2', NULL, 'siswa', '2026-01-30 12:44:48', NULL, 1),
(900, 'APRILIYA DETIANTI', '0082982790', NULL, NULL, NULL, '$2y$10$WqZGKhNxB1n56QQi0ROG0.yvrxFQBb6cjz0RcDTv0Oytbjlv/QnNS', NULL, 'siswa', '2026-01-30 12:44:49', NULL, 1),
(901, 'AGNIE ELYANDI', '0103836874', NULL, NULL, NULL, '$2y$10$l3TFD.JlU3aUW6ABeY6Y8OQjou30LBL9VpioKPcA/SdcbHeQ8l6WG', NULL, 'siswa', '2026-01-30 12:44:49', NULL, 1),
(902, 'FITRIA MAISAHAQ', '3097937600', NULL, NULL, NULL, '$2y$10$.Yw9yiIqElpQKlEzJ2oO6e9ze1fkjof3Lh.lyNkCwR0Meu3izbzfS', NULL, 'siswa', '2026-01-30 12:44:49', NULL, 1),
(903, 'RIKI RIVALDI', '0108373071', NULL, NULL, NULL, '$2y$10$ifT54LZpbp5ClgkcT4iTve6Dbmc/EORzlnauvlL3QY8n4hCEx/TwG', NULL, 'siswa', '2026-01-30 12:44:49', NULL, 1),
(904, 'FITRI JUITA SARI', '0099514568', NULL, NULL, NULL, '$2y$10$q/6rO1xhZKCX/VE6X0JxQuA8SCrxZnDNa6DwMm8o1FNHTsRI6H4m6', NULL, 'siswa', '2026-01-30 12:44:49', NULL, 1),
(905, 'LANI HANURTIYA', '0075235265', NULL, NULL, NULL, '$2y$10$MIIa4RPb3t0CTLqB/XxoLOB01LefGr72WSNCkFX6t9v40FPqdUyve', NULL, 'siswa', '2026-01-30 12:44:49', NULL, 1),
(906, 'ERWIN ARYA NUGRAHA IWANI', '0095074022', NULL, NULL, NULL, '$2y$10$RhGdiBkc/iOefAKEzCDux.M06hgaav6Ty5TicljkSBpd/4SE7dGA2', NULL, 'siswa', '2026-01-30 12:44:49', NULL, 1),
(907, 'NONENG KANIAWATI', '0082766357', NULL, NULL, NULL, '$2y$10$9qPQoGtFtmYKUXcNNO6OZ.5u3rpeoN0pzXXGG3wwpzlG.2.MWVVdC', NULL, 'siswa', '2026-01-30 12:44:50', NULL, 1),
(908, 'FAUZI', '0081943385', NULL, NULL, NULL, '$2y$10$SA/.8u0N6Kk3CUHgYFCbquoT58IskcZwAlzBNX7.1w5fD1HbNtZWa', NULL, 'siswa', '2026-01-30 12:44:50', NULL, 1),
(909, 'IIN HIDAYAT', '0081310093', NULL, NULL, NULL, '$2y$10$r2j7BdDafAxkwNSsct1FPOaAywSCWeEpIVBbjHKT/nXUzFIfMjxw.', NULL, 'siswa', '2026-01-30 12:44:50', NULL, 1),
(910, 'DEWI NURAENI', '0085263627', NULL, NULL, NULL, '$2y$10$3FKVvumObrSJ3Rx8CNbG5uqpf/zmvL42EQcT1yzdYH.PVjvhBT8xe', NULL, 'siswa', '2026-01-30 12:44:50', NULL, 1),
(911, 'RIZKY ROHMATUL SOPIAN', '0098204140', NULL, NULL, NULL, '$2y$10$BaSc34PZ/Af2q5bOtHKHJeNjzkTpZooLgvLgnxq1NonznYdALyeiK', NULL, 'siswa', '2026-01-30 12:44:50', NULL, 1),
(912, 'AGNIA RAIHANA AZZAHRA', '0084041839', NULL, NULL, NULL, '$2y$10$PCmiNVdysIZF.CxNQ2DNVuxeOGRiHbZAy3t2LdKSV37111Uacutzu', NULL, 'siswa', '2026-01-30 12:44:50', NULL, 1),
(913, 'NADILAH ISTINA', '0084437229', NULL, NULL, NULL, '$2y$10$DxCBZcC1Lei1WBTyUJKHFeuCkD0ZF/WB0mEEwZ7NbeblL6K5fsyWa', NULL, 'siswa', '2026-01-30 12:44:50', NULL, 1),
(914, 'SAEFUL FACHRY', '0084853058', NULL, NULL, NULL, '$2y$10$B7QTj4UQJcJwQbKAlydXw.BbpJy.JMTT9cfd5YYFQ6zHZFkNDDBc.', NULL, 'siswa', '2026-01-30 12:44:51', NULL, 1),
(915, 'Rima Indriyani', '0088035039', NULL, NULL, NULL, '$2y$10$bWUMNsaapPA/EPHK0OloN.EtY9BMyghqJWz/0S8HuFOdp9i1yoX/u', NULL, 'siswa', '2026-01-30 12:44:51', NULL, 1),
(916, 'KHANIA NUR ANISA', '0085011994', NULL, NULL, NULL, '$2y$10$L62bAalBpC6SzSeftnHp0uQsvJrgrxBYLXEhaA4GLBEkL9rSAMoHO', NULL, 'siswa', '2026-01-30 12:44:51', NULL, 1),
(917, 'IKROM RAMDHANI', '0087093573', NULL, NULL, NULL, '$2y$10$n7z926LRhvzDv1Fc0/UfNewWjvzaiEDdcEPb/ArOcGXnm8xRgapSm', NULL, 'siswa', '2026-01-30 12:44:51', NULL, 1),
(918, 'ALDI ALDIANSYAH', '0089554475', NULL, NULL, NULL, '$2y$10$Z.FuYbYGUDKtDe9EIzjt2utKY8mBIPKsSqABhTlJAhT7VSFVA6Y/e', NULL, 'siswa', '2026-01-30 12:44:51', NULL, 1),
(919, 'VINKA AZ ZAHRA', '0097602339', NULL, NULL, NULL, '$2y$10$bhY6URpwgHRHU2asgic.A.MF8ID0VwmdXwri/mSiB5PTcGr6x8W1.', NULL, 'siswa', '2026-01-30 12:44:51', NULL, 1),
(920, 'PUTRI FEBRIYANTI', '0088639721', NULL, NULL, NULL, '$2y$10$v9i3fp4h7CDeic9XHNa2Z.xlKMue138sncy4Oyhnxe8EeWp3r5OI2', NULL, 'siswa', '2026-01-30 12:44:51', NULL, 1),
(921, 'SAFIRA SITI FAUZIAH', '0084971930', NULL, NULL, NULL, '$2y$10$IEYoTlDgdRMn3ZpoFNLJ4O7F.y9c8BwrWYktGfsq56UhSikRw4/9C', NULL, 'siswa', '2026-01-30 12:44:52', NULL, 1),
(922, 'Siti Sopiah', '0095253298', NULL, NULL, NULL, '$2y$10$lrodm6ImngqPGLPA3zfgsupkAfD9YZwqefh1QLWbHdA1P7pfgYpX2', NULL, 'siswa', '2026-01-30 12:44:52', NULL, 1),
(923, 'RAHMA AULIA ZAHRA', '0096187738', NULL, NULL, NULL, '$2y$10$CvB0QpJEF3IaQN2.VFv2nuzXy/S8MBkVBYylY1Gnv/1ch51KFEIYW', NULL, 'siswa', '2026-01-30 12:44:52', NULL, 1),
(924, 'RATNA SETIAWATI', '0081630090', NULL, NULL, NULL, '$2y$10$D.h3pS/LYRJod4Ba5wl2YuugJMz7EfJYP4oSPc0jb1Hbumk3xJRPa', NULL, 'siswa', '2026-01-30 12:44:52', NULL, 1),
(925, 'NOVALDI ABDUR RAHMAN ASSYAMSI', '0084914457', NULL, NULL, NULL, '$2y$10$HkR5lW/ZF7LON.R6TlhxHO7Xnzw4gZi6lb2GErQXVrJiTgJVQ2Eim', NULL, 'siswa', '2026-01-30 12:44:52', NULL, 1),
(926, 'SATIRAH NUR ANISA', '0078621758', NULL, NULL, NULL, '$2y$10$h1NJHkUSeWlAcpbWygIyP.3x0VzgbrGdaHNKEIJ6C7.o/PaDrD7Ii', NULL, 'siswa', '2026-01-30 12:44:53', NULL, 1),
(927, 'DEDE SYIFA SA\'ADAH', '0096963938', NULL, NULL, NULL, '$2y$10$RoydmMesVTatjvK96JVcduAcjl1nOjCKLY9B9x9Q6WO/VzfI1qhLK', NULL, 'siswa', '2026-01-30 12:44:53', NULL, 1),
(928, 'HAVIZ LUTVIANA PERTIWI', '0091757520', NULL, NULL, NULL, '$2y$10$MQucoe6JhSUq8nhMmHxOYulujO/X5Z2H.kzUxjWFM/kNPvVUSjKlG', NULL, 'siswa', '2026-01-30 12:44:53', NULL, 1),
(929, 'MUNA TAZKIYAH AWALIYAH', '0088847049', NULL, NULL, NULL, '$2y$10$aaczlLtGLpnY0DC2xnFGVOtCV7cn4tYw6S29b9BvZXvuo8zQmFKSa', NULL, 'siswa', '2026-01-30 12:44:53', NULL, 1),
(930, 'RIFKI HERSYA RAMDHANI', '0084457563', NULL, NULL, NULL, '$2y$10$X/hM2WuAIy/jdLjDp4VV6um9tcVC1QcAUy4CBPlLcwdffQeQKXAzi', NULL, 'siswa', '2026-01-30 12:44:53', NULL, 1),
(931, 'NISYA NOVIANTI', '0083658290', NULL, NULL, NULL, '$2y$10$I4GjBnSoZf8SaamRC.SUeecAHf67CoUHQZH26tchE8EmdeEsvFSS.', NULL, 'siswa', '2026-01-30 12:44:53', NULL, 1),
(932, 'ZALFA SABILA SABDA PERMATA', '0089705879', NULL, NULL, NULL, '$2y$10$99TdYivIE.hoX.vv9m6MqujhrZV6cTvAUdp223/EF.M/yOUWrQflu', NULL, 'siswa', '2026-01-30 12:44:53', NULL, 1),
(933, 'RENDI NUGRAHA', '0089544500', NULL, NULL, NULL, '$2y$10$F0vIphxrAqTlVkDNjfgZWOwPjWvE9wvPnxf5IeTb5c4uvXjKE6uLy', NULL, 'siswa', '2026-01-30 12:44:54', NULL, 1),
(934, 'AGIN GINANJAR', '0083633497', NULL, NULL, NULL, '$2y$10$XcHv.c0DVs1O.TMpjoaCqO1iHaRJeEZQdDa0rfJdkIhYqnvu3vPaS', NULL, 'siswa', '2026-01-30 12:44:54', NULL, 1),
(935, 'ROBIATU ADAWIYYAH', '0086443351', NULL, NULL, NULL, '$2y$10$ZmPIi5JmK3br/xu6rebWleJFaOlw6lMGEa6MEEkQ3Q1MUZQnOcLzm', NULL, 'siswa', '2026-01-30 12:44:54', NULL, 1),
(936, 'MOCH RIZKI KHOERUDIN', '0082054596', NULL, NULL, NULL, '$2y$10$oZTEykkR9vEN4zEDYqBR3Oo5t8C4Ylh2/KNVwKqf3IpCM6yzWS/EO', NULL, 'siswa', '2026-01-30 12:44:54', NULL, 1),
(937, 'FAHRIJAL AWALUDIN', '0081863190', NULL, NULL, NULL, '$2y$10$ygIo1b7RwnTZ48sCG4jH4.8NT1n4dnNA47oIdcRLmWQNImODQuoR.', NULL, 'siswa', '2026-01-30 12:44:54', NULL, 1),
(938, 'NINA AGUSTIN', '0085107122', NULL, NULL, NULL, '$2y$10$B8l.6cy53HAlFzBjZ4zS3.GotMiuAlZSz28fCLOEAwNpfKFoCZbs2', NULL, 'siswa', '2026-01-30 12:44:54', NULL, 1),
(939, 'REFFAL DAFFA SOMANTRI', '0086461351', NULL, NULL, NULL, '$2y$10$RLtlnrYskSTWeQKxqcXlCOuGwmXzAsTo4PFk9/EJAQQxEyLNSlmh2', NULL, 'siswa', '2026-01-30 12:44:54', NULL, 1),
(940, 'FACHRY KURNIAWAN', '0089015802', NULL, NULL, NULL, '$2y$10$7AmV1Vhe3qE3.b/smRFEy.4kiJ7xOfVEKbB3FC2BQu5h81gxPa6gm', NULL, 'siswa', '2026-01-30 12:44:55', NULL, 1),
(941, 'ALFIAN RIFAI SYAWA', '0084900006', NULL, NULL, NULL, '$2y$10$azS3uuQbt0MbQnmYYvPYFOoUw6hRolYvUHth4gsoZ1DYW3kEYqKTW', NULL, 'siswa', '2026-01-30 12:44:55', NULL, 1),
(942, 'Nur Azura', '0094448605', NULL, NULL, NULL, '$2y$10$XWYjAttnjQ0yEWBKMzdCSuIMPjQTvcOD68Qw07xd/5dsbonAC099.', NULL, 'siswa', '2026-01-30 12:44:55', NULL, 1),
(943, 'SILVA DINDA MARTIANA', '0095140710', NULL, NULL, NULL, '$2y$10$lMjnB.cEE2pf2k/4WvxxhOWC8n1oR07mrAI8qbz.kGIy7HQ1Yga5u', NULL, 'siswa', '2026-01-30 12:44:55', NULL, 1),
(944, 'TYARA AGISTIANI', '0087794465', NULL, NULL, NULL, '$2y$10$UISoHNLHRglboJppqn4/x.W.w/w4vPOFuWZwk0GHE0nZLh.aAdoVO', NULL, 'siswa', '2026-01-30 12:44:55', NULL, 1),
(945, 'MUHAMMAD FAKHRI ALBANTANI', '0097336972', NULL, NULL, NULL, '$2y$10$lHOXsSO/F7nrKcFsEEBEyO6Z7BSubgQawrm.QnWtCCVBOEAjNKdvG', NULL, 'siswa', '2026-01-30 12:44:55', NULL, 1),
(946, 'GILANG REYANDI', '0102959675', NULL, NULL, NULL, '$2y$10$CdAYLPzwDuhOGM6yQVGB5O.QhBtekB9k3sibussPcZLn29JOVC8pi', NULL, 'siswa', '2026-01-30 12:44:55', NULL, 1),
(947, 'AFIFAH BUTSAINAH', '0081552141', NULL, NULL, NULL, '$2y$10$VGFDN27FwkaJsLT9tHX5A.wCxxWHXZ.gIZydySiwD2D6T18HtdPtq', NULL, 'siswa', '2026-01-30 12:44:55', NULL, 1),
(948, 'NENG SINTIA', '0092705324', NULL, NULL, NULL, '$2y$10$A5Noa8Ee/w2fWDXTZOXmH.qIVIbA/ZgS3Hs/pXSaA8cGjcrnU.0bW', NULL, 'siswa', '2026-01-30 12:44:56', NULL, 1),
(949, 'FAISAL RAMDANI', '0076156529', NULL, NULL, NULL, '$2y$10$bXQT9jrUI4zALik0SHPaVuL5vI53.BXVYRM7gvoqALkOsavFwa/u2', NULL, 'siswa', '2026-01-30 12:44:56', NULL, 1),
(950, 'Rival Virdaus', '0095841213', NULL, NULL, NULL, '$2y$10$cbmv3HiVjHBCQRKupronSOG8fJ53CwoSM4V3MtyA5hRPj8gpDKAyK', NULL, 'siswa', '2026-01-30 12:44:56', NULL, 1),
(951, 'GILANG RIFALDI', '0091609630', NULL, NULL, NULL, '$2y$10$ujvHmuEq6nizX7INVnEnnes6rDIG8UwwKrGbnIbXMaWFmpYWCyGDq', NULL, 'siswa', '2026-01-30 12:44:56', NULL, 1),
(952, 'WULAN YUNIAR', '0107934137', NULL, NULL, NULL, '$2y$10$dosDTfkFDx.RirFs1bRoDuMaMOke8PLYC.qg75HMxoqrNTJUd6X2K', NULL, 'siswa', '2026-01-30 12:44:56', NULL, 1),
(953, 'RAHMA SUKMAWATI', '0084137957', NULL, NULL, NULL, '$2y$10$gnkcLdwOx3EKeOfVMkKTzO8CjR3t6RyJkw.dZSFMjIKEN3lHFiD6K', NULL, 'siswa', '2026-01-30 12:44:56', NULL, 1),
(954, 'DINI NURANZILI', '0092454372', NULL, NULL, NULL, '$2y$10$RFK7wxV.k/fOS/wYnIuDb.FjxQKVUwOYWrOYbW4gbL9aj.vGEbmYG', NULL, 'siswa', '2026-01-30 12:44:56', NULL, 1),
(955, 'ADI MAULANA TAJUDIN KANDALAVI', '0077569074', NULL, NULL, NULL, '$2y$10$7DQqg6yJU87esZa0SOXcU.ifTa4o8aeKAeYeOY78ycL5QYiNE.GxW', NULL, 'siswa', '2026-01-30 12:44:57', NULL, 1),
(956, 'ELSA YULYANTI', '0084410760', NULL, NULL, NULL, '$2y$10$lUJsr/k.jGCCeCvRhGfNI.LPS5.OXUxs6NXvOQWVvvUmPBu79UczK', NULL, 'siswa', '2026-01-30 12:44:57', NULL, 1),
(957, 'NELSI ARYANI', '0096515836', NULL, NULL, NULL, '$2y$10$7Ueyi7KLpX1GJD1/A3pZtOpzZ10WuogziIjVMJ3rkdKo9ozj/4Tty', NULL, 'siswa', '2026-01-30 12:44:57', NULL, 1),
(958, 'SRI SOPIAH', '0071082174', NULL, NULL, NULL, '$2y$10$YzRWqs9oyxncxRwtWFNSfuGMHeVuL6.OWnmASOtNQ9qkwWOyVIwH6', NULL, 'siswa', '2026-01-30 12:44:57', NULL, 1),
(959, 'SAYLA ISTIQOMAH', '0076798160', NULL, NULL, NULL, '$2y$10$3./.SQIImJvMCoZ5.HI8bewUbcvk.k.GQi2/.WSLwkFLyxZdvNy9m', NULL, 'siswa', '2026-01-30 12:44:57', NULL, 1),
(960, 'YOGI ADITYA WARDANA', '0083708339', NULL, NULL, NULL, '$2y$10$mEnon7.xYuuIWgg2wJEA3eu8ctJ6wmGyib56T5MGLw7BfC2Jtn/jy', NULL, 'siswa', '2026-01-30 12:44:57', NULL, 1),
(961, 'LUCKY ELVAND HABIBIE', '0106420213', NULL, NULL, NULL, '$2y$10$jCa.E903z0GsvmB53CNY8ul.dHZtgAnuc6IFS7Ld4UFQmZgyPYHpK', NULL, 'siswa', '2026-01-30 12:44:57', NULL, 1),
(962, 'NUR AGNI AULIA RAMADANTI', '0108866950', NULL, NULL, NULL, '$2y$10$YtJ2rFR7MSq.PXBG9P/sHepfCWKehpz0jC.tb/5F5pTRLpd2/Lrs.', NULL, 'siswa', '2026-01-30 12:44:58', NULL, 1),
(963, 'NIDA LISABILILHAQ', '3097419665', NULL, NULL, NULL, '$2y$10$FYXwZeD9oeecQTzYzahrrOkOgkss4.7h9g8xUoGWqESsToR6Si8Ha', NULL, 'siswa', '2026-01-30 12:44:58', NULL, 1),
(964, 'SYIFA PAUZIAH', '0104608263', NULL, NULL, NULL, '$2y$10$2K1UvnZ/tJqoSdAzgACH9OX6h.i4WrFGSmbgw/AgA0vo7ZrMjP1XK', NULL, 'siswa', '2026-01-30 12:44:58', NULL, 1),
(965, 'HERAYANTI', '0092628646', NULL, NULL, NULL, '$2y$10$wO9X55m9Rjl/irkOs2Mkt.xEA20LDPNJ9jZ3OxVh8fooQoSY535U.', NULL, 'siswa', '2026-01-30 12:44:58', NULL, 1),
(966, 'LUGHWA NASYTHA', '0096618894', NULL, NULL, NULL, '$2y$10$2Id8jm6S5ABmVK5VO9R.ju50G0F.1y5OHNm/oM8KGDbZNUpi4yV9m', NULL, 'siswa', '2026-01-30 12:44:58', NULL, 1),
(967, 'HILYA JAZILA', '0082654149', NULL, NULL, NULL, '$2y$10$kC1b4Ip.F5.SRC5LMdycP.oazUwEIFOYtu7OPYw9jlXgj3AYmwhLO', NULL, 'siswa', '2026-01-30 12:44:58', NULL, 1),
(968, 'GAVRILLA RAJOELINA PURBA', '0107240128', NULL, NULL, NULL, '$2y$10$bx3XYZEuJ5hSN2ZwzK2CGONH1Q2U7vgSNDgZsVARddRnj6GL82b/q', NULL, 'siswa', '2026-01-30 12:44:58', NULL, 1),
(969, 'SAIFUL FACHRIZAL HIDAYAT', '0071279815', NULL, NULL, NULL, '$2y$10$m0VEDSmYC7eOi7EOzMcowuUBpmellR4/xponPqXVF9rYLYXTk.8AG', NULL, 'siswa', '2026-01-30 12:44:58', NULL, 1),
(970, 'FELISA ARAS', '0081781433', NULL, NULL, NULL, '$2y$10$8YR45n3uZSwJ1XGaKqdE5OSKndceqlXxkKESMzHlaJaR2v6GjnzG6', NULL, 'siswa', '2026-01-30 12:44:59', NULL, 1),
(971, 'VIKKY DWI SAPUTRA', '0089042924', NULL, NULL, NULL, '$2y$10$.XKJ2lrX3Bh667BRZ5KPFesbA11W.mTVjyPGx1oOxonMS2XGCzNIq', NULL, 'siswa', '2026-01-30 12:44:59', NULL, 1),
(972, 'Eisha Rahma Syafiya', '0083279494', NULL, NULL, NULL, '$2y$10$2MC9bB2ZyHC5RBr99mhQZOI7co8UOyszShxbqsix7dAQ5.u2a64oi', NULL, 'siswa', '2026-01-30 12:44:59', NULL, 1),
(973, 'LUSIANI AGUSTIN PERTIWI', '0084948895', NULL, NULL, NULL, '$2y$10$ma3JOM/wXdINcaaFG9YBY.kEFMCzfN44w.y//3y1B1.3MgfyFHNky', NULL, 'siswa', '2026-01-30 12:44:59', NULL, 1),
(974, 'ANDIN WULAN SUCI', '0081135233', NULL, NULL, NULL, '$2y$10$wDRyoCCmmehXMsrWNM2lKuH0qWjHyCDwBHOSeuN2w8p9tMq9lGOEm', NULL, 'siswa', '2026-01-30 12:44:59', NULL, 1),
(975, 'SANTI NOVIANSYAH', '0093017899', NULL, NULL, NULL, '$2y$10$CHEUMoP8GR/GHIgmpIui9eEwneZsAo1TCEf.TMPcVOsSecVwiJtXq', NULL, 'siswa', '2026-01-30 12:44:59', NULL, 1),
(976, 'PARISYA ELSYAVIRA ZAHRA', '0095658772', NULL, NULL, NULL, '$2y$10$hVHFtpqF1znZeTcmxmxo9OEzTdj.taBoicwVXKsz7ut4S5Hg2FPd.', NULL, 'siswa', '2026-01-30 12:44:59', NULL, 1),
(977, 'MERI KEYSHA MAHARANI', '0101966881', NULL, NULL, NULL, '$2y$10$KHQ2pKDIT.gaoEOkhTidHOrmT08gRv1yITg2pep0hbQHt2pYbq5hu', NULL, 'siswa', '2026-01-30 12:45:00', NULL, 1),
(978, 'Handari Aryanto', '0095835217', NULL, NULL, NULL, '$2y$10$TUG8soGUeWq1iyI5zEj.5eBiV9O5KKKKHvJcWcFjcm7zxgx6Bi6GS', NULL, 'siswa', '2026-01-30 12:45:00', NULL, 1),
(979, 'MUHAMAD ALY YUSUP', '0081062552', NULL, NULL, NULL, '$2y$10$XScCuIxVlDgf4w/EoSAbg.mjGLHfZ4G48hDs60tDrkaqWiZi0p0RS', NULL, 'siswa', '2026-01-30 12:45:00', NULL, 1),
(980, 'INDRA AKBAR BUDIMAN', '0087944365', NULL, NULL, NULL, '$2y$10$1vnNU760rAwzlROTgpEJsOom0ukjX4SI4zcDssba3.6AQxM9Qrooe', NULL, 'siswa', '2026-01-30 12:45:00', NULL, 1),
(981, 'ORIZHA UTAMI BINTANG', '0094525287', NULL, NULL, NULL, '$2y$10$k3FZ..5tZo1LD5/bdr/zJOUT.UHSt2ssDJBWGlsRhGFhIAV7uSptO', NULL, 'siswa', '2026-01-30 12:45:00', NULL, 1),
(982, 'AURA ANZANI', '3116811404', NULL, NULL, NULL, '$2y$10$rXxpRuJBBCtccj3FbHSIl.3alFO9ZJxBhGKH3mfWm.AbjdJMv/rPm', NULL, 'siswa', '2026-01-30 12:45:00', NULL, 1),
(983, 'JUWITA PURWANTI', '0097440597', NULL, NULL, NULL, '$2y$10$Rr/9nZi/wDEZociyB0FT3uXFgPuZ8ktqx/mDvTwvXsVN2QH9VBR6S', NULL, 'siswa', '2026-01-30 12:45:00', NULL, 1),
(984, 'DEDI REFANDI', '0072841736', NULL, NULL, NULL, '$2y$10$fnpdChcMVr7/N1PiBmv1XuVsNZM1uRXb5eIfdOPs9/ZMcixpvOVce', NULL, 'siswa', '2026-01-30 12:45:01', NULL, 1),
(985, 'AZKIA JULIA NATASYA', '0086737629', NULL, NULL, NULL, '$2y$10$4X0IIJRBJwZeUMwxL/yHYegU2cNyqEKUXxulYK4CrvgTtTaFG9TlS', NULL, 'siswa', '2026-01-30 12:45:01', NULL, 1),
(986, 'MUHAMAD RAFI JANAWI', '0099259058', NULL, NULL, NULL, '$2y$10$FMirg9xKSJZlH4EZkvvekeg5NXfa2wRjOdKJ/jLmb5oaoVQPfgT4u', NULL, 'siswa', '2026-01-30 12:45:01', NULL, 1),
(987, 'M. RIJAL EFRIANO HARIRI', '3086555490', NULL, NULL, NULL, '$2y$10$xw9CBCJx2RQaxPAY0Y8FdOc6g.ca2MftgJBdOn9xWGmn6ikdL2tIm', NULL, 'siswa', '2026-01-30 12:45:01', NULL, 1),
(988, 'ANGGA SABARUDIN AINUL YAQIN', '0096775574', NULL, NULL, NULL, '$2y$10$IRnLNmgrOd9v7sJF9rWmqO6nNH6VIKgswrtM0ds2hTxniNZRcHC7m', NULL, 'siswa', '2026-01-30 12:45:01', NULL, 1),
(989, 'DEVAN RYANSYAH HERYANTO', '0088660768', NULL, NULL, NULL, '$2y$10$jipOVuOgX6p9BnWtZJ/OKOFAyqI/UAGaTKnzONYheJwhSuo31CXay', NULL, 'siswa', '2026-01-30 12:45:01', NULL, 1),
(990, 'NADIA', '0105089431', NULL, NULL, NULL, '$2y$10$TmGDi3hk0HX641jwT4N2K.us.XlYISWEYpmtPj48Aib5fAn/TK77C', NULL, 'siswa', '2026-01-30 12:45:01', NULL, 1),
(991, 'ALTHAF ABDULLAH', '0092205920', NULL, NULL, NULL, '$2y$10$vJb3L4ipOru2jUb0XSYyO.ncK39gu8pdXUZ0v2oeLGQ.tLyIG.9x2', NULL, 'siswa', '2026-01-30 12:45:02', NULL, 1),
(992, 'LANA HANURTIYAN', '0077151107', NULL, NULL, NULL, '$2y$10$fo8IvIUxyPi2t7lELRtk9eAoiUN5cM8bXCIlvO2XyytyFHvJtP3VO', NULL, 'siswa', '2026-01-30 12:45:02', NULL, 1),
(993, 'ZAHRA CINDY REGINA', '0099494521', NULL, NULL, NULL, '$2y$10$pFduojOdJ0NHRSGG8vquZefsnAM6aNERQQsyA7LFWFq68HySPt1YG', NULL, 'siswa', '2026-01-30 12:45:02', NULL, 1),
(994, 'SHAILA DESWITA', '0079383085', NULL, NULL, NULL, '$2y$10$nY1KNeFX.6Ee12WaLusUpOykR47Qo13yAa3mxMibZFciISLbJ.g7y', NULL, 'siswa', '2026-01-30 12:45:02', NULL, 1),
(995, 'NUROSIDAH SIFAUS SANIAH', '0091353419', NULL, NULL, NULL, '$2y$10$KXRdtf/AHoNr3OztUraW/ele8eIIhzvwcxEcs72vnYIo4iwTJBfae', NULL, 'siswa', '2026-01-30 12:45:02', NULL, 1),
(996, 'NAZIA JULIANTARI', '0104187910', NULL, NULL, NULL, '$2y$10$OTTGegRs.EmQZA0MMPbJa.iY4FTpeKd/Pz0VugSXbujTsBAmN9DxC', NULL, 'siswa', '2026-01-30 12:45:02', NULL, 1),
(997, 'Sakti Apriadi Purba', '0096442997', NULL, NULL, NULL, '$2y$10$WA8zXwvjqRUD2JQaEdmtHuYMR0vXPxDtgtWkoNVUD2wErNuG38HGa', NULL, 'siswa', '2026-01-30 12:45:02', NULL, 1),
(998, 'FITRIA RAMADANI', '0081655988', NULL, NULL, NULL, '$2y$10$7mY8fA7IzdZNcMkWFT6zFOP.iLtyglv/PlBrKXtLTgeVUPM1MYLEK', NULL, 'siswa', '2026-01-30 12:45:03', NULL, 1),
(999, 'SYAFA ANIDYA ASIKIN', '0109247565', NULL, NULL, NULL, '$2y$10$rdYuzMSket4dopmhUKv4aeW4J.wYmG52N2jyjDnw.E6Y9lzD3SVJC', NULL, 'siswa', '2026-01-30 12:45:03', NULL, 1),
(1000, 'SYIFA NURAZIZAH', '0073500705', NULL, NULL, NULL, '$2y$10$hQlQVUoIAGSvlCdajh2oTeVUz/DbJlDlc64XQQ1VfdyWUD0oGM5vm', NULL, 'siswa', '2026-01-30 12:45:03', NULL, 1),
(1001, 'Rinda Ananda Ade Putri', '0095112511', NULL, NULL, NULL, '$2y$10$laVRFXsuF2rFLAhK5BOfxO7RDod7ycaCj09L3ANUO6WTV5A6pIRtS', NULL, 'siswa', '2026-01-30 12:45:03', NULL, 1),
(1002, 'LAISYAFHIRA AGHISTA', '0072280397', NULL, NULL, NULL, '$2y$10$yTN4ptGEPDeYU0qifVMhSeS7OS4Fwc5adZU/HUSiZc0iHNoBrjApi', NULL, 'siswa', '2026-01-30 12:45:03', NULL, 1),
(1003, 'RYO ZAKARIA', '0098885424', NULL, NULL, NULL, '$2y$10$2tD3D4z4DN7GPRrNOSDVy.HjRQ2zlORHIcVPnZyoPnXycTMSKbhqu', NULL, 'siswa', '2026-01-30 12:45:03', NULL, 1),
(1004, 'NISA NUSROTUL UMMAH', '0096300952', NULL, NULL, NULL, '$2y$10$9a3WBgZ7irVT80NXW9XMNeDOv3uv6Jn11tashyjPQkWpEbzJ9ubLC', NULL, 'siswa', '2026-01-30 12:45:03', NULL, 1),
(1005, 'INDI NURRAHMAYANI', '0091340521', NULL, NULL, NULL, '$2y$10$hBA4kci/L21UFPCpbEUQdeHM7yR9P2qcfgUx6wuct/Sv2/9H4vjAW', NULL, 'siswa', '2026-01-30 12:45:03', NULL, 1),
(1006, 'Tanissa Syifa Fuziyani', '0099673165', NULL, NULL, NULL, '$2y$10$fiTgW6rKzir0L6MJsOJdme5omutoS2oetBtnjIYmxfhhvFBf7I2.S', NULL, 'siswa', '2026-01-30 12:45:04', NULL, 1),
(1007, 'ENENG SUNENGSIH', '0082175258', NULL, NULL, NULL, '$2y$10$mwnm1QiUo0K9dmI0Ifoe0ebUaJU7OeT7VZ5hI0eyDwYLCyVtl6pcW', NULL, 'siswa', '2026-01-30 12:45:04', NULL, 1),
(1008, 'ALYA FELITSYA NAZHERA', '0095386642', NULL, NULL, NULL, '$2y$10$3yFuL.jZ8fSkzQdmohXrmuTl9KVpK6IrQg5BjI99DMzOHGsMIlnlC', NULL, 'siswa', '2026-01-30 12:45:04', NULL, 1);
INSERT INTO `tbl_users` (`id`, `nama_lengkap`, `username`, `email`, `nomor_wa`, `telegram_chat_id`, `password`, `last_login`, `role`, `created_at`, `updated_at`, `active`) VALUES
(1009, 'FAUZAN AZMI FIRDAUS', '0103694457', NULL, NULL, NULL, '$2y$10$ZGtNbZoBm.eQX8AUklK/KupBHVjGmorF6VSkEH.xJqJlhv6R2rWGS', NULL, 'siswa', '2026-01-30 12:45:04', NULL, 1),
(1010, 'Siti Najma', '3102920303', NULL, NULL, NULL, '$2y$10$0YZCmVwVyOllTQXYF6L.teta/gUMG43O7ydIZoNnTCVTf8M7nA1ui', NULL, 'siswa', '2026-01-30 12:45:04', NULL, 1),
(1011, 'FITRI YANI NUR AZIZAH', '0079050836', NULL, NULL, NULL, '$2y$10$OVsA94suYynVrFtD0jWmeuCjg1C/bBcZn68wxHNfMauxbgBtR4CZ6', NULL, 'siswa', '2026-01-30 12:45:04', NULL, 1),
(1012, 'KEYZA AUREL AZMI', '0109652928', NULL, NULL, NULL, '$2y$10$gKNGdnmy4iqWrWxxkonpke1Bvi8Gxuw3YnZ/3xeWq9JuTrtn2w2R6', NULL, 'siswa', '2026-01-30 12:45:04', NULL, 1),
(1013, 'DAFFA PRADITIA', '0081454235', NULL, NULL, NULL, '$2y$10$qAtCMHEJGUR6vEM3v3M23uGSU/9ZQk/kd/UaL7pD8C03Mz3TPAW3q', NULL, 'siswa', '2026-01-30 12:45:05', NULL, 1),
(1014, 'INEZ AIRA NURHIDAYAH', '0105185493', NULL, NULL, NULL, '$2y$10$HX8yZkg/6XtylO9TtCMuZOV3J9oLx/2oUg/OEMQ8eOe3tASkCFPWq', NULL, 'siswa', '2026-01-30 12:45:05', NULL, 1),
(1015, 'TAZKIYA NADA', '0104117251', NULL, NULL, NULL, '$2y$10$CQqByQJEzyCRjIF9RohWs.xNm3jFA7S.me3.eije7EIz2owHXcg/G', NULL, 'siswa', '2026-01-30 12:45:05', NULL, 1),
(1016, 'Nura Nuraeni', '0096844548', NULL, NULL, NULL, '$2y$10$n8XdPAOmkl3EQeFcbmSC0uNtdYS2a0P/JxYbD3zMcO9m8lripFvt6', NULL, 'siswa', '2026-01-30 12:45:05', NULL, 1),
(1017, 'PEBRI ALDIANSYAH', '0105485252', NULL, NULL, NULL, '$2y$10$bNSDQ8F6v2AN2Y1uEjbiROpvOhxwGVDphZGkM6Gw3f8PgGnYo17kS', NULL, 'siswa', '2026-01-30 12:45:05', NULL, 1),
(1018, 'RIVA MAORA FITRIYANI', '0074275385', NULL, NULL, NULL, '$2y$10$geQ2Av.dao7lCESEPNY9Ie9N820MFiKfo6kdFTgNQdPhzcVnFNbiS', NULL, 'siswa', '2026-01-30 12:45:05', NULL, 1),
(1019, 'ALI M SIDIK', '0082407603', NULL, NULL, NULL, '$2y$10$T9lY/QDDdGT3yB2XJSQeqOAmXhTkoXWsiieP4VD/KEYQEPt1NqTN6', NULL, 'siswa', '2026-01-30 12:45:05', NULL, 1),
(1020, 'REISHA PUTRI', '0089088553', NULL, NULL, NULL, '$2y$10$5gyD5L79Lqli8TNcvCIQWuFzCD.A4AtQNmrZCz0B641oY4DBBLPb2', NULL, 'siswa', '2026-01-30 12:45:06', NULL, 1),
(1021, 'AZMI AGOESTINI', '0099238794', NULL, NULL, NULL, '$2y$10$Itgpn9dWuw7t4s1CpnNxmeAVIWOOGRZ3lCH0VlfXo37TquuHRbsIq', NULL, 'siswa', '2026-01-30 12:45:06', NULL, 1),
(1022, 'SABA NABILA', '0094657544', NULL, NULL, NULL, '$2y$10$BjBNzW4ZtlHU8JfF/YY8JOouaPuu10kqmgp4v2fGxo/87rzGISfuS', NULL, 'siswa', '2026-01-30 12:45:06', NULL, 1),
(1023, 'DINDA PUTRI NURPITASARI', '3108674843', NULL, NULL, NULL, '$2y$10$wOCBomPWu.90Qx7SX4iRQem0m.tuVKNa0xNGNE1INE.Kpx67Q7e/a', NULL, 'siswa', '2026-01-30 12:45:06', NULL, 1),
(1024, 'RIDWAN PERMANA', '0097673325', NULL, NULL, NULL, '$2y$10$GFyTG4/5pbh9rmyQ0QGNtO7bfaFvhTCMa6thtiMzUS/xezrykB0aK', NULL, 'siswa', '2026-01-30 12:45:06', NULL, 1),
(1025, 'MUHAMMAD RAMADHAN', '0091447807', NULL, NULL, NULL, '$2y$10$rMIUF/RLbNCMAHfx7YRpdeYO0YF5mjXfIJ0iIbGekGZgbGRyeiBFa', NULL, 'siswa', '2026-01-30 12:45:06', NULL, 1),
(1026, 'MUHAMAD RIZKI DEFIAN', '0099057877', NULL, NULL, NULL, '$2y$10$N4p4nJ5QIU/OA95FuejhVuEi6wGP4oS2BCHNo/O4aS4Y0oXhaOdji', NULL, 'siswa', '2026-01-30 12:45:06', NULL, 1),
(1027, 'FERDI ALDIANSYAH', '0106771791', NULL, NULL, NULL, '$2y$10$xErGy37mk3sJ/aKqEOVVducqiHcZZ2s7ts1nCLecg5.fpbuj5meua', NULL, 'siswa', '2026-01-30 12:45:07', NULL, 1),
(1028, 'AURELIA SALSABIL PUTRI', '0104323260', NULL, NULL, NULL, '$2y$10$ImAJWblIHtMSOVwWOHyJEeRcBS0RiGbo//CLJpD0J5P1HwNo08L/.', NULL, 'siswa', '2026-01-30 12:45:07', NULL, 1),
(1029, 'MAZRIL IMRON', '0097793455', NULL, NULL, NULL, '$2y$10$r0O9OJ3wIASB.l8yUs2DsO9/q3hmFnErEvIYvxDVZLmx9Y4XWoPta', NULL, 'siswa', '2026-01-30 12:45:07', NULL, 1),
(1030, 'MOCH. ARI KURNIAWAN', '0098576761', NULL, NULL, NULL, '$2y$10$mTEB93pa1JE3KzbXmQ8Nz.Ji.A9Ep57XXXTbopN.wB8GenjoI5hmS', NULL, 'siswa', '2026-01-30 12:45:07', NULL, 1),
(1031, 'SASYA APRIANI', '0081984336', NULL, NULL, NULL, '$2y$10$Ii/5Qdi1.1U3VTsR02mu8u/CrEM5cIPssmW7vEAHuViiZQzRJbncm', NULL, 'siswa', '2026-01-30 12:45:07', NULL, 1),
(1032, 'CANTIKA HANA MAIDA PUTRI ANDRIANI', '0092520482', NULL, NULL, NULL, '$2y$10$mKEJn2tIK8WEBKbjewlDXeM72/n75.PR59.RlptOyQpAU62pWK2je', NULL, 'siswa', '2026-01-30 12:45:07', NULL, 1),
(1033, 'SARAH ZIPANI AULIA', '0079435085', NULL, NULL, NULL, '$2y$10$GlesAMMkHr0eCIoJuWoBEO6jBizV6G0QQRCPHDeEYDCHvqz40FhRW', NULL, 'siswa', '2026-01-30 12:45:07', NULL, 1),
(1034, 'NAYLA ARIFIANTI PUTRI', '0086458037', NULL, NULL, NULL, '$2y$10$9Zrb0FtFFEXIH19sGmNup.n1vTUtgmGmlRYsdqzSSGZjY2WqAKElO', NULL, 'siswa', '2026-01-30 12:45:07', NULL, 1),
(1035, 'AFWAN FAHRUDIN', '0093094481', NULL, NULL, NULL, '$2y$10$y2ewIlOwfgp9eVIup9OmG.0WdN1u74Usunv/3Y7w3Dig8PjlwDxh6', NULL, 'siswa', '2026-01-30 12:45:08', NULL, 1),
(1036, 'FELLA LAUDYA PUTRI EFENDI', '0082029069', NULL, NULL, NULL, '$2y$10$R64NtIIuObj4JHRssmtwEerRYCQczM02S9rjAReP3WcZfuKFxan32', NULL, 'siswa', '2026-01-30 12:45:08', NULL, 1),
(1037, 'IDEA NAZRIEL ALFAHREZY', '0082413282', NULL, NULL, NULL, '$2y$10$d1Z9yM3TSoWznwl14dQkkufH1/JLScH37IaV58UYu0rApuSwyBSLK', NULL, 'siswa', '2026-01-30 12:45:08', NULL, 1),
(1038, 'Dony Jamiatul Pikry', '0094230397', NULL, NULL, NULL, '$2y$10$SLA8xvSu/b6v2gqUujvXBuygyYFag6m8nK/xKQt2fC80tHUZevyzW', NULL, 'siswa', '2026-01-30 12:45:08', NULL, 1),
(1039, 'Moch. Arul Rifanka', '0082211913', NULL, NULL, NULL, '$2y$10$kexN7LU/xQ7fFqaw6mnQd.TmBepUi0lGgw8W4H59ZjePpuRS2smPe', NULL, 'siswa', '2026-01-30 12:45:08', NULL, 1),
(1040, 'DEZAN FAHREZI', '0087019378', NULL, NULL, NULL, '$2y$10$7klpCD779gSuu8/Zf5qro.Aa0.1xor/Ih4gM6/iGer4slx4Y1gFjK', NULL, 'siswa', '2026-01-30 12:45:08', NULL, 1),
(1041, 'FAZRIA FIRDAYANI PUTRI', '0081305065', NULL, NULL, NULL, '$2y$10$VPuYsq/JdhQPIt/ThviO1ONcVlgaWGWoa7UwC6Mh.7H1sJUvOovUi', NULL, 'siswa', '2026-01-30 12:45:08', NULL, 1),
(1042, 'SATRIA SUGIH LESMANA', '0094084951', NULL, NULL, NULL, '$2y$10$FoDxJH7l0c0XeY24nUaIiOlWxNF9fxChlbWokusNDSoWDYfMQex0.', NULL, 'siswa', '2026-01-30 12:45:09', NULL, 1),
(1043, 'TSABITAH FAUZIYAH', '0075009227', NULL, NULL, NULL, '$2y$10$n3SV0LL8zZFTdntoYuGgZu1W4o3tBl7ZmXg9cLJz3A9xsas6wfzoC', NULL, 'siswa', '2026-01-30 12:45:09', NULL, 1),
(1044, 'ANISA AGNI HIDAYAH', '0073928329', NULL, NULL, NULL, '$2y$10$gZwnMD8mv5lK4F1pL87ai.aQxKOs1/bLwgeJEb4Cg79eTC6YnK/EG', NULL, 'siswa', '2026-01-30 12:45:09', NULL, 1),
(1045, 'ZAQIA RAHMAWATI', '0086866263', NULL, NULL, NULL, '$2y$10$4TN.Ghe/IXA8auZ7m5iJdusJmmcN3F65TMreLSDrg/wErTXPZAE5.', NULL, 'siswa', '2026-01-30 12:45:09', NULL, 1),
(1046, 'Saskia Meika', '0095597263', NULL, NULL, NULL, '$2y$10$mg8iHL79Kqc..6HKihFtDutgatWPWoR1WKiG9Y4/MX9jpwHprcl1W', NULL, 'siswa', '2026-01-30 12:45:09', NULL, 1),
(1047, 'WAINNASYA AYU APRILLIA', '0096120139', NULL, NULL, NULL, '$2y$10$Y3FU8nMXJUify.dcDFJJ2e5f08lFeXfnsOzVzj.bO7e8eCsEk.2/W', NULL, 'siswa', '2026-01-30 12:45:09', NULL, 1),
(1048, 'RESYA APRYLIANI HERMAWAN', '0105941100', NULL, NULL, NULL, '$2y$10$HrmLM6Cghrt1jlpVr.G6qe4Xm5V022G4h1pFdv6mEGid/wzrUyux2', NULL, 'siswa', '2026-01-30 12:45:09', NULL, 1),
(1049, 'RASYA MUHAMMAD AL-FATHIR', '0074760306', NULL, NULL, NULL, '$2y$10$xEdxmLYR9COm689bln3Mg.JUJLMdGxB6tDYsPJA/ya6ZaiChdS7aW', NULL, 'siswa', '2026-01-30 12:45:10', NULL, 1),
(1050, 'ELIS SITI RAHAYU', '0072295514', NULL, NULL, NULL, '$2y$10$ulizFl9cM0svyj.JcZkJ0uUQHQnQJqkodDTe/gwoPEwG5FR9I6wGm', NULL, 'siswa', '2026-01-30 12:45:10', NULL, 1),
(1051, 'RIZKI ANDREYANSAH', '0092068468', NULL, NULL, NULL, '$2y$10$5uFmdhsMYxnV8WnNvA2tNOCnCikLzdIu9bJcGmiNzMGClTNxknTT.', NULL, 'siswa', '2026-01-30 12:45:10', NULL, 1),
(1052, 'SHANIA OKTAVIANI JAMIL', '0089116346', NULL, NULL, NULL, '$2y$10$L7zpdRq5Pcb9DFnFIGjAuORmPJl/fuJdHHHY2qUx9ZbNuF07tfKpS', NULL, 'siswa', '2026-01-30 12:45:10', NULL, 1),
(1053, 'AYU PERTIWI', '3089217263', NULL, NULL, NULL, '$2y$10$Hoc4I.sY6bS3jkK3.fikEOmm7LYfZUXmmzkI7u3sUd.h.YnjIJvAC', NULL, 'siswa', '2026-01-30 12:45:10', NULL, 1),
(1054, 'PUTRA ARDIANSYAH PRATAMA', '0097614563', NULL, NULL, NULL, '$2y$10$71rVYGlldAPiulYwBI9y/.stbL2tMUewegah5KgyUBL6QXi0gxNnS', NULL, 'siswa', '2026-01-30 12:45:10', NULL, 1),
(1055, 'DERIN PUTRA CANDRA', '0108237855', NULL, NULL, NULL, '$2y$10$08DSH7trrdFMiRlz1HY41uAyq/s0vhPIBVQQSfkVyZy7bFv.iz44G', NULL, 'siswa', '2026-01-30 12:45:10', NULL, 1),
(1056, 'KOMALA FITRI', '0094793367', NULL, NULL, NULL, '$2y$10$0wUJoqFsdNo21SeiRUiTZOgx3Bu7oplRNeJdbVnfGGuZIuRfp4VSu', NULL, 'siswa', '2026-01-30 12:45:10', NULL, 1),
(1057, 'MAMAY MUHAMMAD KUSTIWA', '0069480093', NULL, NULL, NULL, '$2y$10$d6I6j0x1aC35yf2XkTQ3f.73WlFdkebgv11tRYkv0RKiPAP.ox3yy', NULL, 'siswa', '2026-01-30 12:45:11', NULL, 1),
(1058, 'YUDHISTIRA DWI MAHESA', '0096025919', NULL, NULL, NULL, '$2y$10$PAMoEHZCD4DyTe/bRRB46OGYPCwn.nxPzzKJx5BW3zd6MoVnecIW6', NULL, 'siswa', '2026-01-30 12:45:11', NULL, 1),
(1059, 'RIZWAN', '3097257455', NULL, NULL, NULL, '$2y$10$uBHmGP3bemRfu4tux/uwruBxyZ59vFp0JYktB7SdVoYLspGmClKpO', NULL, 'siswa', '2026-01-30 12:45:11', NULL, 1),
(1060, 'NOVI AYU LESTARI', '0073286741', NULL, NULL, NULL, '$2y$10$BNaUFqCP.oLlzynLjGvu7eQ44/MYo462pokmFPjjIbc9Z8tXog8sa', NULL, 'siswa', '2026-01-30 12:45:11', NULL, 1),
(1061, 'SYALLUNA THAHARA PUTRI', '0087579173', NULL, NULL, NULL, '$2y$10$IEqMtBecnsHywLsjYi67uOmTIJ3hb9Lhb318ekjvehV6Hx147tGr.', NULL, 'siswa', '2026-01-30 12:45:11', NULL, 1),
(1062, 'SIGIT NURFAZRI', '0093473086', NULL, NULL, NULL, '$2y$10$QiUsab7HnGu6AUoMUOejLe.gk9eQP81rHS3tqymHbKDkXN0nrn/T6', NULL, 'siswa', '2026-01-30 12:45:11', NULL, 1),
(1063, 'NICKY FARHAN SALUNA', '0072414816', NULL, NULL, NULL, '$2y$10$xxDFPMnYHNNUmLoFp010HuB62Bn7jj/iehoSb2XruJ2Kv9wmIwJpa', NULL, 'siswa', '2026-01-30 12:45:11', NULL, 1),
(1064, 'PUTRI DARA AGESTA', '0099701626', NULL, NULL, NULL, '$2y$10$3b38TypeN.yBjK0xyA4uLeQ8gl0JnUZSI1VEVJZX1oYKXtIsBQDMS', NULL, 'siswa', '2026-01-30 12:45:12', NULL, 1),
(1065, 'RASYA SALSABILA', '0079315951', NULL, NULL, NULL, '$2y$10$TmvIqGdhry2/uD8abvv4AeEiZoe25Sr33vFb0mmzfs7jrydSghn3e', NULL, 'siswa', '2026-01-30 12:45:12', NULL, 1),
(1066, 'DESIANA SUCIATI', '0064407588', NULL, NULL, NULL, '$2y$10$o7tyX30aeHqUErwCY3SM.OQBMBgpX1eA1whmtulWPGSzL8w4/kx/G', NULL, 'siswa', '2026-01-30 12:45:12', NULL, 1),
(1067, 'LUCKY FAUZIA SATYA', '0078112470', 'rikiwahyudin52@gmail.com', '085155232366', '1080546870', '$2y$10$CId63U5F5DsmPJVXWffbX.Lizys6ysh3LXB2Nilz99Ayjl.fDheVe', NULL, 'siswa', '2026-01-30 12:45:12', NULL, 1),
(1068, 'DINI FITRIYANI', '0086530445', NULL, NULL, NULL, '$2y$10$0E.Pbdk2BSf1hKJmsYJISeaaZFAG/G8taY6hNuaqZcu06//CJb5JO', NULL, 'siswa', '2026-01-30 12:45:12', NULL, 1),
(1069, 'YULIANTI', '0075060004', NULL, NULL, NULL, '$2y$10$lS8I6hPuvzmH2fQQelR3le7MUgI297h1lSLoloVZIGOodyxgpaDZC', NULL, 'siswa', '2026-01-30 12:45:12', NULL, 1),
(1070, 'FIKRI RAMDANI', '0071447816', NULL, NULL, NULL, '$2y$10$PHqKJzYoHi2FIUem67ueke6tV1TOk2BkDLp/Il2QwUubdG38vBXCm', NULL, 'siswa', '2026-01-30 12:45:12', NULL, 1),
(1071, 'NENG MAESAROH', '0072145072', NULL, NULL, NULL, '$2y$10$3reRChnomWbll9wr1uHaxul5b9jutgZedoFC4l8XsVMAItPx12xpG', NULL, 'siswa', '2026-01-30 12:45:13', NULL, 1),
(1072, 'VINA DAMAYANTI', '0075373657', NULL, NULL, NULL, '$2y$10$D/rB4jyx4NJtZ9Rt/K7EYeu4PTsz4wq3BeFVpGtkAENjqdCby04Ce', NULL, 'siswa', '2026-01-30 12:45:13', NULL, 1),
(1073, 'Abdu Rofi Djalalludin', '3093288209', NULL, NULL, NULL, '$2y$10$tdmxM6F8Q1mZ38apzClH3uVBAOJtVD2WaKOGFit12Je8g/9AGUv.a', NULL, 'siswa', '2026-01-30 12:45:13', NULL, 1),
(1074, 'AZZAHRA AULIA RAMADHANI', '0072755466', NULL, NULL, NULL, '$2y$10$RyaY2hL8cl3prL4Y1MOzTO2J4Kij8TbtLNJM2yDvwDnxG8wJuqKzi', NULL, 'siswa', '2026-01-30 12:45:13', NULL, 1),
(1075, 'NOVA ANGELA', '0092685403', NULL, NULL, NULL, '$2y$10$qPE4n1RnC.SkxgYccNL9rOy7pwquAeZNP0gccQLIs4vKSILY1Xp0C', NULL, 'siswa', '2026-01-30 12:45:13', NULL, 1),
(1076, 'SYAIDAH NURAENI', '0072897380', NULL, NULL, NULL, '$2y$10$7mzvnzryrbPaok0/ErThIO2Ys5Euja0tSGGvh6fEOcsOQ.RQkMYKG', NULL, 'siswa', '2026-01-30 12:45:13', NULL, 1),
(1077, 'Shalma Kania Iswari', '0078767293', NULL, NULL, NULL, '$2y$10$pa19KALe91jfQJql8hxMuOlTIaCUnB45e/CNg1DI.5IoNMWKJKL/K', NULL, 'siswa', '2026-01-30 12:45:13', NULL, 1),
(1078, 'NAZWA AZHARY', '0094445421', NULL, NULL, NULL, '$2y$10$ph6HSp8pUTFObT1PCVcbMOSDukmTQr.h9Co2hWNppiWHmebgu6LhS', NULL, 'siswa', '2026-01-30 12:45:14', NULL, 1),
(1079, 'NENDI SUPRIATNA HIDAYAT', '0094259229', NULL, NULL, NULL, '$2y$10$hFBVVUJtg1IphK9WcbrrTeC5kdEuXSI9Ve5rZ9/I91pLfNFd.0gsq', NULL, 'siswa', '2026-01-30 12:45:14', NULL, 1),
(1080, 'HENDRAWAN', '0088808160', NULL, NULL, NULL, '$2y$10$IXcyWBHDYZRhlzjjNnqFXOq1eUTYukmKxskZimQTKHRdnKkbLGTvW', NULL, 'siswa', '2026-01-30 12:45:14', NULL, 1),
(1081, 'MUHAMAD AKBAR ALQODRI', '0086969806', NULL, NULL, NULL, '$2y$10$Qnt1JoFIS81riKoRx9tddeSm8R3Zy5HJNnAfDiEs.PyxJzVRDJ49O', NULL, 'siswa', '2026-01-30 12:45:14', NULL, 1),
(1082, 'RINI DARLINA', '0094229538', NULL, NULL, NULL, '$2y$10$QWVFkDCwNmOEAJYooJODDOGY0itHAv3O3XJGst33zv6UoV11cM.mO', NULL, 'siswa', '2026-01-30 12:45:14', NULL, 1),
(1083, 'KENIA RISMADANI', '0076261245', NULL, NULL, NULL, '$2y$10$Uk4Jvetc6c1wDTEqFdp3mus5v2pLpXQ095QkyF4Py5gFts4YVBdn.', NULL, 'siswa', '2026-01-30 12:45:14', NULL, 1),
(1084, 'WILDA SITI SOLIHAT', '0082466248', NULL, NULL, NULL, '$2y$10$bymzwCX7x4/DaA5pw58pxez0xDUudIKavYcSZ.8GEWaJeliyF/qBG', NULL, 'siswa', '2026-01-30 12:45:14', NULL, 1),
(1085, 'MOCH. ALFIAN PARISAL', '0093978427', NULL, NULL, NULL, '$2y$10$HESoxNowZnppmBIcUbyV/eLdBZsqHPyD34ON546F0VOY8EaJUWSyG', NULL, 'siswa', '2026-01-30 12:45:14', NULL, 1),
(1086, 'FRENDGA SUJATE SETIAWAN', '0086302137', NULL, NULL, NULL, '$2y$10$kz9b2VJl09TIobwr5N6k2.1Y6JOiSCbNdpetgiB3nX1Rlzb/SvtVC', NULL, 'siswa', '2026-01-30 12:45:15', NULL, 1),
(1087, 'ARKAN BAGAS ADIWITYA', '0095416586', NULL, NULL, NULL, '$2y$10$4xKDwXSdmUmXD3rub8Wb5udtuSzNWYxaTNwzh9p67o3/VeokjL33e', NULL, 'siswa', '2026-01-30 12:45:15', NULL, 1),
(1088, 'SINTA ANJANI', '0084208936', NULL, NULL, NULL, '$2y$10$JwBK.ZVFtY5lSXVnf/ksuuCaviDkAuG5Ygee5z0.I6sEGmI8S7p3W', NULL, 'siswa', '2026-01-30 12:45:15', NULL, 1),
(1089, 'ALYA YULYA RAHMAN', '0085122410', NULL, NULL, NULL, '$2y$10$mjBODIsQbedtgXv4Z4JI7OajlSHurBZINIeVSW42UpuRzE9PtMtZ6', NULL, 'siswa', '2026-01-30 12:45:15', NULL, 1),
(1090, 'REVALINA ANGGRAENI', '0085920292', NULL, NULL, NULL, '$2y$10$RlYPAJbrw3fyNeJH3eHVpu6OS3.D2ymB43VCjUQlidkAyEodDF472', NULL, 'siswa', '2026-01-30 12:45:15', NULL, 1),
(1091, 'DEA GHEFIRA SYAHNARIKI', '0078034790', NULL, NULL, NULL, '$2y$10$CpbYRE9K7Zzk4R9e4vGrzevDT0ceWJ97zBFglITX2uTVFdfH4aGvK', NULL, 'siswa', '2026-01-30 12:45:15', NULL, 1),
(1092, 'SELA NURFITRIAH', '0071651464', NULL, NULL, NULL, '$2y$10$i7ntmcEasNo5/n/gQDAxiu/6CF/5khA7R0HCUR13/yofv7DXgXsDO', NULL, 'siswa', '2026-01-30 12:45:15', NULL, 1),
(1093, 'RAHMA ALIKA', '0086396558', NULL, NULL, NULL, '$2y$10$997v8CREQJ8AxOzxCvVm0uLoFOCIl5Mzz9HxgInqS6cguIuOWClpm', NULL, 'siswa', '2026-01-30 12:45:16', NULL, 1),
(1094, 'INA GUSTIANI', '0075528719', NULL, NULL, NULL, '$2y$10$QEXfkV7nOEI21KkAIdcLv.xVjsFSXM34j2dpjI/e1n33nv5Jpjiju', NULL, 'siswa', '2026-01-30 12:45:16', NULL, 1),
(1095, 'SISKA JULIANTI', '0085226736', NULL, NULL, NULL, '$2y$10$3UuaHUC4N.tXpiW7pq8MY.fIa3Jap2.vnZTpWOSUn1X3zqzwT7AvS', NULL, 'siswa', '2026-01-30 12:45:16', NULL, 1),
(1096, 'Zalzabila Mauluidia Rokhmah', '0097984928', NULL, NULL, NULL, '$2y$10$kex9vImee8xAlBCiYhT9quccXQUXKDr9t2XMEfOlWw5OgBVNJXs4K', NULL, 'siswa', '2026-01-30 12:45:16', NULL, 1),
(1097, 'DAFA NURSIFA', '0089514400', NULL, NULL, NULL, '$2y$10$zbWwwglYhxl8zxdrHMLkP..zq8MUD/avKXk1lXOLTK0v0M.3prc36', NULL, 'siswa', '2026-01-30 12:45:16', NULL, 1),
(1098, 'RIRIN NURAENI', '0079306045', NULL, NULL, NULL, '$2y$10$oSDIMcB5VD7Rpyx73EloWOzjvhKwNB0gsy3UJdY/i78SUT1dKjhIm', NULL, 'siswa', '2026-01-30 12:45:16', NULL, 1),
(1099, 'ADLY AZIBAN PURNAMA', '0091838124', NULL, NULL, NULL, '$2y$10$DlBZ6UHM5Jz4gvKHDUtSkui2WQBuNWnw4WHwVxO72skWYSF9GsP2i', NULL, 'siswa', '2026-01-30 12:45:16', NULL, 1),
(1100, 'DINDA GANISTYA', '0109661298', NULL, NULL, NULL, '$2y$10$ojIFYsis44nv5c/FAwE34.B2aI2n4pNsmUJUDE2.F5VACiUOJRe2K', NULL, 'siswa', '2026-01-30 12:45:17', NULL, 1),
(1101, 'LENI APRILIANI', '0098007627', NULL, NULL, NULL, '$2y$10$9q80atJ7nd4r93xGNGj7auT7rjeeaFpHLyx0ZbMNK6zR7zjkpM.Pi', NULL, 'siswa', '2026-01-30 12:45:17', NULL, 1),
(1102, 'MARSYA MARSELLIA', '0089150351', NULL, NULL, NULL, '$2y$10$PURnzxqOs6qky1RvpuooOex8SjOeBG45Hcqj.Hz4saT7aHvSmzawy', NULL, 'siswa', '2026-01-30 12:45:17', NULL, 1),
(1103, 'CHELSEA INDRIANI', '0088034487', NULL, NULL, NULL, '$2y$10$1pXwqUVctZa4eiyR9f/n8upB40TUO2lqeIdJBkm1vIfy3XdzprofS', NULL, 'siswa', '2026-01-30 12:45:17', NULL, 1),
(1104, 'MAYLANI ALKHAROMAH SUPRYATNA', '0096308560', NULL, NULL, NULL, '$2y$10$RPomp6dsUnBzvZLfXGl3LulBh2WmfZRce7bNmANDmkF83VPfOnEo.', NULL, 'siswa', '2026-01-30 12:45:17', NULL, 1),
(1105, 'YENI BUNGA MAULIDA', '0089414598', NULL, NULL, NULL, '$2y$10$Qt/Plj6VnM.fCU1ct45uy.mquLuQ0F76aHnRatsKc6598OICo7jXK', NULL, 'siswa', '2026-01-30 12:45:17', NULL, 1),
(1106, 'NIKKI RAHMADANI', '0095548291', NULL, NULL, NULL, '$2y$10$REakPMqW1sSyQSrnyDnIl..2cipbIk/C5FOgMf16g9GoNVdCJNrSG', NULL, 'siswa', '2026-01-30 12:45:17', NULL, 1),
(1107, 'TEGAR JULVIAN', '0095460825', NULL, NULL, NULL, '$2y$10$EQ8iScahoQzej2UAQWeR0.ttcdHDT0WiGPPEnAc7.sjT6BEoxM6S6', NULL, 'siswa', '2026-01-30 12:45:17', NULL, 1),
(1108, 'EVA JULIANTI', '0097281099', NULL, NULL, NULL, '$2y$10$1pvobFJU25HCt4vaD7YIReC90KWA4k8LxvXOSLu1y6ttnNa9QBv4m', NULL, 'siswa', '2026-01-30 12:45:18', NULL, 1),
(1109, 'INTAN KHUMAYRA SALSABILA', '0082137837', NULL, NULL, NULL, '$2y$10$fm3h6v01waXM5zoY6TcI6O2A3hQnIk7L8RyMBvvjCeb56CRpf4Ncm', NULL, 'siswa', '2026-01-30 12:45:18', NULL, 1),
(1110, 'NAYSHILA SUCI PUTRI SYAKIRA', '0096777689', NULL, NULL, NULL, '$2y$10$jEPlQyT.CEcqq3bYLBw9K.ZjN78WshWwNsmiVIQSWvsXRgmVme75y', NULL, 'siswa', '2026-01-30 12:45:18', NULL, 1),
(1111, 'ANISAH NURAINI', '0084092734', NULL, NULL, NULL, '$2y$10$/EF0/CCmQVDOksmeJyMDeOJvNUrLMgrQUdcUn.xlNYlUvwgTXcu2m', NULL, 'siswa', '2026-01-30 12:45:18', NULL, 1),
(1112, 'MUHAMMAD ZEIN AL - FIQRY', '0073111180', NULL, NULL, NULL, '$2y$10$0ro4t4W4nbDFcBqLL4IN4.N25Ro0ZinORvVCbbpF4uTD2ktYgdlD2', NULL, 'siswa', '2026-01-30 12:45:18', NULL, 1),
(1113, 'WEGA ANANDA', '0078509871', NULL, NULL, NULL, '$2y$10$U/u6Ezki8dvA6Qa3Ed5b4.gEMmQByPwQ89OhsBEifvhfCxHERSvna', NULL, 'siswa', '2026-01-30 12:45:18', NULL, 1),
(1114, 'Dinyar Ari Slamet', '0073177019', NULL, NULL, NULL, '$2y$10$2J5.h8ap5RBDYlkjzBhN0eeQx8fCLcegDXpi2Ndah/aZLe8BTNLe6', NULL, 'siswa', '2026-01-30 12:45:18', NULL, 1),
(1115, 'AZKHA ZIYAN ALEXA', '0099127863', NULL, NULL, NULL, '$2y$10$Y7fKoqAA2eS9Hrg/SN3V.edcZ719HrjVjjfPFHvTnr5Eb0dne2UsO', NULL, 'siswa', '2026-01-30 12:45:19', NULL, 1),
(1116, 'ARYA NUGRAHA ZAELANI', '0073749220', NULL, NULL, NULL, '$2y$10$wlBJFMYPvSjYnkzmZ3EhquP9ITu.pwqWxoS.Ak3EptPNaedgiyNDK', NULL, 'siswa', '2026-01-30 12:45:19', NULL, 1),
(1117, 'DISTI TRI APRILIANI', '0088553879', NULL, NULL, NULL, '$2y$10$ZhFGIwmle0j3CJ4l3LdYMu5cZwnrbXzeu/ub/BwIbYm69U4V/7exS', NULL, 'siswa', '2026-01-30 12:45:19', NULL, 1),
(1118, 'DIENDA AL ZAHWA FEBRIANTI', '0077121997', NULL, NULL, NULL, '$2y$10$dP7BZs0uZU3GPyqseWxJ0.1i.osbxG73IMWou8EsOfUHXe2IIEXvi', NULL, 'siswa', '2026-01-30 12:45:19', NULL, 1),
(1119, 'WINDI LEDISA', '0107072116', NULL, NULL, NULL, '$2y$10$hTy4LSswGmR3HAxQoiPobeDvI0atAc1wDjb6DuN8G.SqOCIJjr6m.', NULL, 'siswa', '2026-01-30 12:45:19', NULL, 1),
(1120, 'YUNITA JULIANTI', '0083325875', NULL, NULL, NULL, '$2y$10$XTSxPEWtdZcqANNthbe2OO1usIulWGzeczR6ZX2GdpLbzJKkro7bS', NULL, 'siswa', '2026-01-30 12:45:19', NULL, 1),
(1121, 'NAYALA HUMAIRA SETIYAWAN', '0079524275', NULL, NULL, NULL, '$2y$10$rG3tKMnVdw2up6GAr1vJ3O77bnpheRu8bgRU7ratcpt14AVsr1P0y', NULL, 'siswa', '2026-01-30 12:45:19', NULL, 1),
(1122, 'RAHMA AYUTIZA NURIYAH', '0088142164', NULL, NULL, NULL, '$2y$10$7C4RtUe.Cx0MmJqd2/DFCe.7NyXPxBG5bQ/.vTKl4ZmSbATEJUeni', NULL, 'siswa', '2026-01-30 12:45:20', NULL, 1),
(1123, 'HARUMIREIKA SISWARANING PRAMESI', '0088819359', NULL, NULL, NULL, '$2y$10$f.YaRaGSkSq1st.THu.pIeAoRPnhc32TqOJYu4u7UBNQyT/kkzLXm', NULL, 'siswa', '2026-01-30 12:45:20', NULL, 1),
(1124, 'SEPTI RIANA AGUSTINA', '0094456151', NULL, NULL, NULL, '$2y$10$jGAN3l.H82XdufW5db9Nl.5zW7F/CzcdgTJrBwXuGmBUBEaZFLXki', NULL, 'siswa', '2026-01-30 12:45:20', NULL, 1),
(1125, 'WILLDIANI OKTAVIA MAHARDICHA', '0098531724', NULL, NULL, NULL, '$2y$10$Ymia78WXB9jvF.z.MCZ69O/3HdBpmiL79fBJL1wWvhgITum3.P3ui', NULL, 'siswa', '2026-01-30 12:45:20', NULL, 1),
(1126, 'Dede Arif Setia Budi', '0074104630', NULL, NULL, NULL, '$2y$10$OxT8HtEsZYHuob0h2n176.Fpy7jOQhVZVvSjPvJQ9ucQyu1D35RaW', NULL, 'siswa', '2026-01-30 12:45:20', NULL, 1),
(1127, 'TRI SUCI MAULIDA', '0092360131', NULL, NULL, NULL, '$2y$10$RLlacS3DTiDqENp4JTUpp.Oxmx0rogDuxZbP.yeLYTF6BtPBqO1Cq', NULL, 'siswa', '2026-01-30 12:45:20', NULL, 1),
(1128, 'RIKO RAHMAT HIDAYAT', '0074516192', NULL, NULL, NULL, '$2y$10$QnhtFrinET2lmV0EB9mB5.vg5MvcCk.VY2a4EhSQGol4nvlSEEf32', NULL, 'siswa', '2026-01-30 12:45:20', NULL, 1),
(1129, 'NURAISYAH', '0108212284', NULL, NULL, NULL, '$2y$10$OpOhjckSTlgi7k3o7BE/ze2IuzwWojfPIXFYlD0scrDIX0.iRVJaG', NULL, 'siswa', '2026-01-30 12:45:20', NULL, 1),
(1130, 'SAFIRA AYU ANGGRAENI', '0091643724', NULL, NULL, NULL, '$2y$10$QHZPNueNa2u.iHfsEFMzg.Cg39g5Cvdo8yXNfgXMeO0xLgFpsii4q', NULL, 'siswa', '2026-01-30 12:45:21', NULL, 1),
(1131, 'HAMDAN HUMAEDI', '0097909281', NULL, NULL, NULL, '$2y$10$lutUjWbkFHlqhxJk.jKC6uqh31I/1FYQLUp/iB0U2cfPVuLljAQkC', NULL, 'siswa', '2026-01-30 12:45:21', NULL, 1),
(1132, 'GIALUNA GEISHA AL-RASSYIFA', '0098754716', NULL, NULL, NULL, '$2y$10$4xBBVQsUwG19./Qikv8hcODCn4DYhapyHyFdQM2OfrVAK8g4MA6SW', NULL, 'siswa', '2026-01-30 12:45:21', NULL, 1),
(1133, 'REVAN JALALUDIN NUGRAHA', '0106849604', NULL, NULL, NULL, '$2y$10$465fVuPV.9u.gpPpwJi0HO5ega77nJDlkx8lr9RYFPugpQJ9ffzrG', NULL, 'siswa', '2026-01-30 12:45:21', NULL, 1),
(1134, 'AVRILLIA', '0073517122', NULL, NULL, NULL, '$2y$10$1DydovJ9F0o1vRe9NanxTeXWO5KHqnC/IEQDIyM8j4166OSYzyubi', NULL, 'siswa', '2026-01-30 12:45:21', NULL, 1),
(1135, 'Andina Puspita Wardani', '3101991466', NULL, NULL, NULL, '$2y$10$FLy60a9qU4w4WE49bs6zwOIz5HJCUH3QF7jvMH8pSToqEOftZCqnW', NULL, 'siswa', '2026-01-30 12:45:21', NULL, 1),
(1136, 'ROSSA NURHIDAYAH', '0096722681', NULL, NULL, NULL, '$2y$10$Q8W946ucE/XUduZSZiTPre.xWmq.6zN5le8nLAVfGAZIb7LwuOSJe', NULL, 'siswa', '2026-01-30 12:45:21', NULL, 1),
(1137, 'RAIHAN ASHIDIQ', '0107471446', NULL, NULL, NULL, '$2y$10$b62qYpx1HsnP7UdRnb/dOOTZkf0CgHeSPIMHvesnEAIQk8QgvZ/vq', NULL, 'siswa', '2026-01-30 12:45:22', NULL, 1),
(1138, 'KHAERUL IKHSAN SYIDIK', '0095424736', NULL, NULL, NULL, '$2y$10$Nyuwo2SOXGCmvJpgj/hYou4ZrQA7rP2qJPzKzAmFT0fyCPaNMN4ri', NULL, 'siswa', '2026-01-30 12:45:22', NULL, 1),
(1139, 'FAJAR RIZKI RAMADHAN', '0095075126', NULL, NULL, NULL, '$2y$10$XNppD/QDA8KM7W.0lu5xauyb763crl4NLQKZHAzDCvZtVoujhGSBi', NULL, 'siswa', '2026-01-30 12:45:22', NULL, 1),
(1140, 'IBNU DZAKI MUHADZDZIB', '0108859569', NULL, NULL, NULL, '$2y$10$gSdNFoemmipZFfmLwG8cfuVPwyXQ3yk3Fa2OrC/Rz7eWIHh5qzcQi', NULL, 'siswa', '2026-01-30 12:45:22', NULL, 1),
(1141, 'RAFI SAPUTRA', '0093566012', NULL, NULL, NULL, '$2y$10$KeOgip.8yE7I7M64zRxDrOwLjNq5jIyfnc0F5Vw2CQXiDsxKq5G8q', NULL, 'siswa', '2026-01-30 12:45:22', NULL, 1),
(1142, 'ASMARANI EKA LESTARI', '0097065189', NULL, NULL, NULL, '$2y$10$rO68G9MYvH22n8X.P5UwCOjOH/efgyMDLvqOqDoxkzSiP9YqlUV7u', NULL, 'siswa', '2026-01-30 12:45:22', NULL, 1),
(1143, 'NURI SITI MASITOH', '3071306354', NULL, NULL, NULL, '$2y$10$ENFmLKx.6URDQaBNBNZUNuVjz5tPm1N5qHSmqnNoZar3Tc0X4xJNu', NULL, 'siswa', '2026-01-30 12:45:22', NULL, 1),
(1144, 'ESA SYARIF PRATAMA MULYANA', '0097015578', NULL, NULL, NULL, '$2y$10$NoIV2UPZ8xiqxaE.uVpeMunv65iBcwGm5TsogxYIoZlAfhT8LbLx2', NULL, 'siswa', '2026-01-30 12:45:23', NULL, 1),
(1145, 'AINI KEYSA AURELIA SABIL', '0073608952', NULL, NULL, NULL, '$2y$10$K.ojadjtwbSi22.rZsynH.31otyWT8LTWAPDxtgx.mc4BuaeHo7nK', NULL, 'siswa', '2026-01-30 12:45:23', NULL, 1),
(1146, 'YANTI SULASTRI', '0086662592', NULL, NULL, NULL, '$2y$10$0AcDiAyW5jZXXV/iUJBl6ufWjabfvhY7K26VQ3i65/2ltMu4cufVq', NULL, 'siswa', '2026-01-30 12:45:23', NULL, 1),
(1147, 'ELIN VIANA', '0108740547', NULL, NULL, NULL, '$2y$10$.uWPblOjGjTwQOwRiF6N6.dRQOhqyNTesM7RvgInpmp.aVje01hoO', NULL, 'siswa', '2026-01-30 12:45:23', NULL, 1),
(1148, 'FAISAL GALIH IMAM MULHAQ', '0091017963', NULL, NULL, NULL, '$2y$10$pjpzic6ZJoWsUzeuUyP5T.oR3f5Bark/viRXp01M.BKH0YInJcb7S', NULL, 'siswa', '2026-01-30 12:45:23', NULL, 1),
(1149, 'MEYLIA NADINDA TRI YASNI', '0083961054', NULL, NULL, NULL, '$2y$10$4W7FWIDy0riHTN037aFR3O.knlD9yVQyZvHjPKb1X4JQuBRMehQ52', NULL, 'siswa', '2026-01-30 12:45:23', NULL, 1),
(1150, 'ALZENA ISAURA FIRJATULLAH', '0099720568', NULL, NULL, NULL, '$2y$10$EwUQn7Bf4fAcHf8CVVOee.iUb9M/nFuT2zqsN/vRP5XhbyHaS7VFq', NULL, 'siswa', '2026-01-30 12:45:23', NULL, 1),
(1151, 'FAISAL MUHAMMAD RIZKY', '0075343970', NULL, NULL, NULL, '$2y$10$CeJmF0JqWzWs/kuMMH47sOZrq/j/W9Zwtzg60i/hMPAIuPOp1JazS', NULL, 'siswa', '2026-01-30 12:45:24', NULL, 1),
(1152, 'MUHAMAD SAMI RAIHAN', '0075051639', NULL, NULL, NULL, '$2y$10$BdAiQwGMbyieb8GfB4n3HOZH6lvGi9gydNWFlArTrRHdTDXkdVlVW', NULL, 'siswa', '2026-01-30 12:45:24', NULL, 1),
(1153, 'IYAM DEINI', '0107456805', NULL, NULL, NULL, '$2y$10$CiXfDHmhYgVSXj4Qij1Lre41v8AWdt82RUC0fn0JOPzHDB4jT4bbi', NULL, 'siswa', '2026-01-30 12:45:24', NULL, 1),
(1154, 'NENG DEWI', '0091457200', NULL, NULL, NULL, '$2y$10$nFDrImtZ4uOCTw7BDXlPsu8BmlQttLzxpHqZKckOYu1zjN1KgxpCq', NULL, 'siswa', '2026-01-30 12:45:24', NULL, 1),
(1155, 'ALIYA NUR AZIZAH', '0105283118', NULL, NULL, NULL, '$2y$10$ucEYVKCmU7KPiGjOxODHseSXj8Yn1oQO8Xl9V7Wv4ktVDZ1q6UPXS', NULL, 'siswa', '2026-01-30 12:45:24', NULL, 1),
(1156, 'ROBI AGUSTIAN', '0098704230', NULL, NULL, NULL, '$2y$10$7ulMklH7GfUILDutfF5ubOVJlwcHunwXXDIlZ3Tp5IdLoqpRgDcfy', NULL, 'siswa', '2026-01-30 12:45:24', NULL, 1),
(1157, 'ALTHAFUNISA', '0099297822', NULL, NULL, NULL, '$2y$10$TUsjGG75CozYN3yie6If2.Y9lPHb9W84jzIBGhqsHyRqIP8YpmAr.', NULL, 'siswa', '2026-01-30 12:45:24', NULL, 1),
(1158, 'Dhila Yulshi Rolani', '0088221226', NULL, NULL, NULL, '$2y$10$xM.hv2xMOZt8fzg2uPzGRei12oqiei8QvNmaDVJpA6UxBnJXaF0Iy', NULL, 'siswa', '2026-01-30 12:45:25', NULL, 1),
(1159, 'TASYA KHOERUN NISYA', '0091470253', NULL, NULL, NULL, '$2y$10$Z1IqkKqZfJZoasC.7z1uAuSQIvpAM5bWWvX.4A3qgx1wIXaNs2hji', NULL, 'siswa', '2026-01-30 12:45:25', NULL, 1),
(1160, 'Elfina Sally Salvia', '3095314022', NULL, NULL, NULL, '$2y$10$esoKckRo1sp0Oor2.h3rZ.Eo6mMtm7nnih1NMVEogklxJFvC3vene', NULL, 'siswa', '2026-01-30 12:45:25', NULL, 1),
(1161, 'DWI ALYA PUTRI', '0085309974', NULL, NULL, NULL, '$2y$10$GgHZrJI1MJfmqCMs9P/aBOFVIMSwWEr4ofN6IeJPleS5nwMvOgARG', NULL, 'siswa', '2026-01-30 12:45:25', NULL, 1),
(1162, 'RISMA FATMAWATI', '0098211201', NULL, NULL, NULL, '$2y$10$UBASMYlhMTDaGNxXAvnQTOg240d1ziehgib7eSLvhGSVIXCWRHC06', NULL, 'siswa', '2026-01-30 12:45:25', NULL, 1),
(1163, 'KHANZA AJLA ROSIDIN', '0087004071', NULL, NULL, NULL, '$2y$10$swYjxB5/8sPNhEAfId6TrOpnBjKr202rcqRkuviyd39GBzMgoLMd.', NULL, 'siswa', '2026-01-30 12:45:25', NULL, 1),
(1164, 'KARYANA HILMAN AZIS', '0071307338', NULL, NULL, NULL, '$2y$10$yYtVKjKC7ZXuu5X59gS33.3EltRkySoSTGINJFLuZUZWNhd.QmhcW', NULL, 'siswa', '2026-01-30 12:45:25', NULL, 1),
(1165, 'SOPIAN FATHUL IMAN', '0086280077', NULL, NULL, NULL, '$2y$10$.U4aP4kFI5J8bpxZVgDvT.WHxJCDHeSbXzZWnskPZzs9TYGi94vOS', NULL, 'siswa', '2026-01-30 12:45:25', NULL, 1),
(1166, 'RAKA NUGRAHA', '0071470931', NULL, NULL, NULL, '$2y$10$8stkKB0kiCi0B7nAv0utJ.l6n6djv3976JAUBTDh/7ympgzZlPhpm', NULL, 'siswa', '2026-01-30 12:45:26', NULL, 1),
(1167, 'TIARA NURAENI', '0099869011', NULL, NULL, NULL, '$2y$10$yawSD8IovPfi/E3GxbA7VebPDSllNoFZ2CU4PxEtI8h57xZG1vQ/y', NULL, 'siswa', '2026-01-30 12:45:26', NULL, 1),
(1168, 'CEPI PERMANA', '0098381210', NULL, NULL, NULL, '$2y$10$J6UihQTRBcTc/UFZwkQYy.f2wwsOjClX9Lnk.rZveP8n1zHLA3JE2', NULL, 'siswa', '2026-01-30 12:45:26', NULL, 1),
(1169, 'ANDHIKA AIRLANGGA DIRGANTARA', '0089994736', NULL, NULL, NULL, '$2y$10$1k4DlKEiZKJrbJAQW0TCZO/VfOCIXeGblYjZ2/xCHElsp7vYWe0ZW', NULL, 'siswa', '2026-01-30 12:45:26', NULL, 1),
(1170, 'EUIS SUSILAWATI', '0104596486', NULL, NULL, NULL, '$2y$10$j/i3VEUyOOLNCC4gK8KQreMzYIrwhawqz5BD/VDYa6oApCQonwI8e', NULL, 'siswa', '2026-01-30 12:45:26', NULL, 1),
(1171, 'YOGA RADITYA IRAWAN', '0085832388', NULL, NULL, NULL, '$2y$10$UWy5TkwSPiJulMmQQ3MRiuwsgu0GIy5bA8WzeobYp1rWX52h79esG', NULL, 'siswa', '2026-01-30 12:45:26', NULL, 1),
(1172, 'HANIF MAULANA IBRAHIIM', '0086593600', NULL, NULL, NULL, '$2y$10$xPFoQ6ABjuhZwDXnbgNiL.CPLUUgjLT6jn3bIEIx8LRgyEcCQUFbW', NULL, 'siswa', '2026-01-30 12:45:26', NULL, 1),
(1173, 'Marsa', '0071621298', NULL, NULL, NULL, '$2y$10$/bLt3X/Yca8YoG/PqEHbfO3zb6rBqRPKBsO4e48JIMiaHeUkPM9Ri', NULL, 'siswa', '2026-01-30 12:45:27', NULL, 1),
(1174, 'DEVARA ARYA DWI PUTRA', '0099756777', NULL, NULL, NULL, '$2y$10$UO4sYDB1jEpX7kmt9sSJtugqntV9HRTacJj4z46a/8L9hz1102qaK', NULL, 'siswa', '2026-01-30 12:45:27', NULL, 1),
(1175, 'LUTHFI RIZKY FEBRIAN', '0105751984', NULL, NULL, NULL, '$2y$10$pHbMK7KeA6LiieaXjkthAOuj6zBDQi7sWQAZ5.DYyyern2xH4f8Ai', NULL, 'siswa', '2026-01-30 12:45:27', NULL, 1),
(1176, 'NAILA NURAINI', '0077103976', NULL, NULL, NULL, '$2y$10$LJ6M6ZMzsdmfwqm4.UI4W.9pPFw4t9WhuX9/yqcKVmd.rf1ZrU36q', NULL, 'siswa', '2026-01-30 12:45:27', NULL, 1),
(1177, 'RIDIA JUNIA', '0089103039', NULL, NULL, NULL, '$2y$10$2Rz886AZWMWWkh4upD2CpuyA4OjK/2akVYFVNA79GjUIr1dJ29DiG', NULL, 'siswa', '2026-01-30 12:45:27', NULL, 1),
(1178, 'NITA RAHAYU', '0084458708', NULL, NULL, NULL, '$2y$10$tRVI1sUkzwLfUep3SPzfMO2h71pvkrBdxP0xdgLR1OPCdWNCey/lu', NULL, 'siswa', '2026-01-30 12:45:27', NULL, 1),
(1179, 'RAMDANI', '0087785115', NULL, NULL, NULL, '$2y$10$0i0EysgsZJwf9YEP0sUOMOKNznhdvNQDJHEuMiu5d7b3d60lnnzb6', NULL, 'siswa', '2026-01-30 12:45:27', NULL, 1),
(1180, 'RADI AL HALIM', '0075859651', NULL, NULL, NULL, '$2y$10$A8CB3v2wWeW3rssxGfaPqOuOw8aZ9oK8zih9sLIGedTMT2Yy41dXC', NULL, 'siswa', '2026-01-30 12:45:28', NULL, 1),
(1181, 'MILA INTAN NURAENI', '0103022528', NULL, NULL, NULL, '$2y$10$X7a5lX7DRJTSrQzNJwIrYuu.e/5HYdZ1tX95ydiM4TcwDU/FvrMam', NULL, 'siswa', '2026-01-30 12:45:28', NULL, 1),
(1182, 'LIVIA NURCAHYATI', '0082653218', NULL, NULL, NULL, '$2y$10$N51/MT33qKLZP5VM9iJujeOxW5iphBTiLxP8Ek12NuLgfesGmdYBG', NULL, 'siswa', '2026-01-30 12:45:28', NULL, 1),
(1183, 'RIDWAN MAULANA', '0087207573', NULL, NULL, NULL, '$2y$10$IQu3.buTY/5PBlDTzgJZJuLetcgrGHcvkt8p9Qwimzh95floSDBSi', NULL, 'siswa', '2026-01-30 12:45:28', NULL, 1),
(1184, 'INTAN CAHYATI', '0085018646', NULL, NULL, NULL, '$2y$10$TTh0/q1IKTgoRuJej0RrAua71d.yz1w6HxV73gN9xSoNlV9RYxtQC', NULL, 'siswa', '2026-01-30 12:45:28', NULL, 1),
(1185, 'MUHAMMAD ABDUL RIZKY', '0087841316', NULL, NULL, NULL, '$2y$10$zfWyvsWNl84soaj.oXtobOaTOWdzsx9QTnmRdvr9qGbfMTmACQD4S', NULL, 'siswa', '2026-01-30 12:45:28', NULL, 1),
(1186, 'ELSHA AMALIA AGUSTIN', '0073101554', NULL, NULL, NULL, '$2y$10$m7SpPQnaBiKVm3vcdNq77eRVLpXQupBq50gBZd39Y.8pTcQ9WYqLS', NULL, 'siswa', '2026-01-30 12:45:28', NULL, 1),
(1187, 'AULIA SRI RACHMAYANTI', '0078316650', NULL, NULL, NULL, '$2y$10$ZDP2Nr21mOkxfNoD5y5uauhMYmLMGNIP45zWo1YZGyJHBQ/lqvioC', NULL, 'siswa', '2026-01-30 12:45:29', NULL, 1),
(1188, 'RAYSA NURALIFAHYASIN', '0091783950', NULL, NULL, NULL, '$2y$10$Q47FpHrvM99eDsPdzJtvaOLsxwmdwFfYvRl3BUVRoyxnpg9DQupw2', NULL, 'siswa', '2026-01-30 12:45:29', NULL, 1),
(1189, 'EVANDRA ARYAN PRATAMA PUTRA', '0078861683', NULL, NULL, NULL, '$2y$10$3OHHXeymfd98MBKw7OSh/.VhglgCZoFwrQ2gt5l3VyYbrCCIK5lKO', NULL, 'siswa', '2026-01-30 12:45:29', NULL, 1),
(1190, 'NUR AISYAH', '0073749825', NULL, NULL, NULL, '$2y$10$8MBvRc1xnXSCI97SP9c4.uTsHd0MmIBza44dceAvatW9g1so/HwPa', NULL, 'siswa', '2026-01-30 12:45:29', NULL, 1),
(1191, 'MUHAMAD RIAN PAUJI', '0072428287', NULL, NULL, NULL, '$2y$10$ZRlhV8PhgMflYe60nkS/h.Vy3EWP4gTqNmLLxcAmzLYzM/hZnsB9a', NULL, 'siswa', '2026-01-30 12:45:29', NULL, 1),
(1192, 'KAMELIA SOLEHAH', '0074608829', NULL, NULL, NULL, '$2y$10$1HVgcFQK41UX3BKfUelJkeuSiQvDoiG8bFP2Ki0l2okITy9/2ZMpa', NULL, 'siswa', '2026-01-30 12:45:29', NULL, 1),
(1193, 'LINGGAR AGUSTIN RAMADANI', '0105813984', NULL, NULL, NULL, '$2y$10$Umu1SZVN7JLYImF5QoDhM.jcYkshgPITdgQrXF0JwOr8WH.4XhMIC', NULL, 'siswa', '2026-01-30 12:45:29', NULL, 1),
(1194, 'AZHAR NUR FAUZAN', '0088365697', NULL, NULL, NULL, '$2y$10$cG9cW/nKja1sWUFLIWhdtuoy62u7PN7l/7ep3M2eXjgbkFJl4EGj6', NULL, 'siswa', '2026-01-30 12:45:29', NULL, 1),
(1195, 'AUFA HANAN', '0087213521', NULL, NULL, NULL, '$2y$10$VwvpdjAGTBisbT921kDpXuB5vPF/cRNj5f2t2PaOiqBn8ZKc0DQ.O', NULL, 'siswa', '2026-01-30 12:45:30', NULL, 1),
(1196, 'RAIHAN FAZRI SUKANDAR', '0081522164', NULL, NULL, NULL, '$2y$10$haPJOas98UZr3L7JBabGy.XtkCmUyN1Gu7akMwp4X5j9TxHkR7fGW', NULL, 'siswa', '2026-01-30 12:45:30', NULL, 1),
(1197, 'RIZKI KURNIA PADLI', '0091815110', NULL, NULL, NULL, '$2y$10$NtR8xbQO4C9ll4X/3ehbTOtXxXpnd6W2AXiXDObHXblFpzfG1XP8e', NULL, 'siswa', '2026-01-30 12:45:30', NULL, 1),
(1198, 'SITI RUKMINI', '0081279075', NULL, NULL, NULL, '$2y$10$qA1RdV30OKGXb9FjXezWxOfklNHW9F6seHTpBFWOkQblbTFFD3Vjm', NULL, 'siswa', '2026-01-30 12:45:30', NULL, 1),
(1199, 'DIMAS WARDANI', '0073592394', NULL, NULL, NULL, '$2y$10$KLqvX3CiLNpLykY6UAjq.ePDO4PUhuP3IimW62NgI06HkyGRHhaKW', NULL, 'siswa', '2026-01-30 12:45:30', NULL, 1),
(1200, 'Dea Putri Rahmawati', '0099177587', NULL, NULL, NULL, '$2y$10$CiRUqRl..42EblL38MejGeCwLHtfzsOWBBxb3pgBoE63M3y5XrliG', NULL, 'siswa', '2026-01-30 12:45:30', NULL, 1),
(1201, 'PUTRI NUR HALIMAH', '0091840313', NULL, NULL, NULL, '$2y$10$yGoTT8Rv/VwIN2RuS2XhRuVhKbL1GZGaVS/KM50CpGoc6hG9XLBcG', NULL, 'siswa', '2026-01-30 12:45:30', NULL, 1),
(1202, 'NAILAH LUTFHIYAH HASANAH', '0091547734', NULL, NULL, NULL, '$2y$10$Ao6uB7.i1UKVWVJk3BIryOccdMtj.RDoT1J5DE.ySFKZIZ35F6TYm', NULL, 'siswa', '2026-01-30 12:47:44', NULL, 1),
(1203, 'RAISYA AMALIA SOPIANTI', '0091931769', NULL, NULL, NULL, '$2y$10$YZSfJI5trOqoN6xMBCOHTOYvfwfXqsZZVmrsTyk2NDnpLYf.NB.nK', NULL, 'siswa', '2026-01-30 12:47:45', NULL, 1),
(1204, 'ANNISA NUR FADHILLAH', '0084284364', NULL, NULL, NULL, '$2y$10$Jt7QrAiq30H/BBl45TxY8u0BhPs3YGky1H3xhMNHrKSVOtYo6Iwi6', NULL, 'siswa', '2026-01-30 12:47:45', NULL, 1),
(1205, 'DESTI NUR AZIZAH', '0096330453', NULL, NULL, NULL, '$2y$10$Y8erzQGcODwa/0OPJfErCu2Vk4c0zYXYNwk18jZ2jiyXsBTXBv7bK', NULL, 'siswa', '2026-01-30 12:47:45', NULL, 1),
(1206, 'KANESA IRMALIA PUTRI', '0084682764', NULL, NULL, NULL, '$2y$10$uBGJl/9ViPWpaQjnKNIp7.5lf7lSpEDtyqa3QxCWjTivaAFL81cIO', NULL, 'siswa', '2026-01-30 12:47:45', NULL, 1),
(1207, 'KAMILA', '0086194798', NULL, NULL, NULL, '$2y$10$UvQQy8WI794w51SEhr5sm.LNaKGRORMGRJRHjONFKG27MzL8LywMu', NULL, 'siswa', '2026-01-30 12:47:45', NULL, 1),
(1208, 'TANIA AMALIA', '0086336873', NULL, NULL, NULL, '$2y$10$7Gm5Egfo3ZoOd8d.Ct14o.dYa7AlbN/R1.wuW4pVGRdnbDaUIZwlC', NULL, 'siswa', '2026-01-30 12:47:45', NULL, 1),
(1209, 'NENG SAHNI SRI MULYANI', '0091862714', NULL, NULL, NULL, '$2y$10$2kyjrKom0zVN71vYW5zS/OwCduSY/v9x3o1oqJcqpRrnyQb/k57bm', NULL, 'siswa', '2026-01-30 12:47:45', NULL, 1),
(1210, 'DHIRA QUEEN MALIKA', '0081371260', NULL, NULL, NULL, '$2y$10$4rhcBSO/wbrq7wMpZyg4oeo2hBzHzAXtV59KSzD31GPKhPlf9EtKa', NULL, 'siswa', '2026-01-30 12:47:46', NULL, 1),
(1211, 'MUH RAJA AN\'ASHAR', '0107551785', NULL, NULL, NULL, '$2y$10$HCRsJMTxcogArVPtZe8Hb.jjm3qlWIZmD0mxFz.AlBZ8Dl5kaBl5G', NULL, 'siswa', '2026-01-30 12:47:46', NULL, 1),
(1212, 'SILVI RIANI', '0102406145', NULL, NULL, NULL, '$2y$10$sXOIkA6D4aWQk3Dv6Rabs.ZajfF0ZfeLXfj6mmiQv/ThxEkkcGVWO', NULL, 'siswa', '2026-01-30 12:47:46', NULL, 1),
(1213, 'Putri Cahyaningrum', '0083301835', NULL, NULL, NULL, '$2y$10$IAJ1CxvYa6lbEDO/T70jgOacsQUtnJ1yV.lA.ggCoYSQ2ScmYBVJe', NULL, 'siswa', '2026-01-30 12:47:46', NULL, 1),
(1214, 'OCTAVIA NURFITRIYANI', '0081679042', NULL, NULL, NULL, '$2y$10$SwLEn6o5VtUTXvW/pm/uiePwGLEAKAcd0HFO1tl7g3JUUTXbohy.m', NULL, 'siswa', '2026-01-30 12:47:46', NULL, 1),
(1215, 'VEGA DEWI PERTIWI', '0102769434', NULL, NULL, NULL, '$2y$10$aubV5Knwsr.V52uq4tFV/.DAezmS9teGTbqV28gb.gdLqVk0F3cGW', NULL, 'siswa', '2026-01-30 12:47:46', NULL, 1),
(1216, 'AKBAR MAULANA MALIK', '3102631710', NULL, NULL, NULL, '$2y$10$KJPI8M/b834jYyZLJgBMae0hfrotz5q0IFAGDQSxr1BxhXZhDA7eO', NULL, 'siswa', '2026-01-30 12:47:46', NULL, 1),
(1217, 'NAYSHA DIAN JUWITA', '0083223656', NULL, NULL, NULL, '$2y$10$w2oujAQBf0WIQ/45rNisPOHKH8SueVL0GbuEFwRBeWBQKrnbjw79a', NULL, 'siswa', '2026-01-30 12:47:47', NULL, 1),
(1218, 'TIARA NAZWA AZAHRA', '0093391744', NULL, NULL, NULL, '$2y$10$OdcMzR.04OaVriwhvA5LAeJWp/gLvmXm3WEGCE0kNPmvLLMIykx8m', NULL, 'siswa', '2026-01-30 12:47:47', NULL, 1),
(1219, 'PUTRA WIJAYA', '0075934067', NULL, NULL, NULL, '$2y$10$ZPXyeF09IVPkg0OWQwML8Ocn94SVFpysEptLi1VEIYUTxUmm7qy4C', NULL, 'siswa', '2026-01-30 12:47:47', NULL, 1),
(1220, 'RAMDHAN MAULANA', '0099801316', NULL, NULL, NULL, '$2y$10$ek.cbAQnmwMl95z84ghdH.V90rEq3cjn1GBwKZpuHDijCm.GnCwuC', NULL, 'siswa', '2026-01-30 12:47:47', NULL, 1),
(1221, 'ADE ISMA', '0078948244', NULL, NULL, NULL, '$2y$10$Aub/mwixGP6RVeAz5H0Ff.I6oWU9Eeq0IJL/hw66Eg13TqaBGRqJi', NULL, 'siswa', '2026-01-30 12:47:47', NULL, 1),
(1222, 'DERI RIYANA SOPANDI', '0088813828', NULL, NULL, NULL, '$2y$10$bzXJOeTp8YXcRJHxbZgtVePXyLBRgaUzcCRktz8zNyEUP4wP5HNXi', NULL, 'siswa', '2026-01-30 12:47:47', NULL, 1),
(1223, 'NANANG SUPRIYATNA', '0071003218', NULL, NULL, NULL, '$2y$10$3RttCWpxByQ3ET5vfWH8xORiOrJSPl0AnuK9FM4cldoBb0nkSU7b.', NULL, 'siswa', '2026-01-30 12:47:47', NULL, 1),
(1224, 'MUHAMMAD RAFI ALFIZHA H', '0094156296', NULL, NULL, NULL, '$2y$10$ITaGlhUR5NhNJtbSCbD70u/55Ha9ANNXadkt545XJJge3oofwK3j2', NULL, 'siswa', '2026-01-30 12:47:47', NULL, 1),
(1225, 'DINDA APRIANI', '0097663284', NULL, NULL, NULL, '$2y$10$toTVRO84bTwMf49kz75Hee8eUxYWoa3gqsXVxgqLQCgQU6mm5h.FC', NULL, 'siswa', '2026-01-30 12:47:48', NULL, 1),
(1226, 'ASYIFA NUR AZXIA', '0097503482', NULL, NULL, NULL, '$2y$10$G9jgqulBkNi0mWXXNOf1xOGStJ1PlO0BlJ2qcU4vdCUNaFlKqc.iS', NULL, 'siswa', '2026-01-30 12:47:48', NULL, 1),
(1227, 'RAHMAH ROUDOTUL ZANAH', '0086399605', NULL, NULL, NULL, '$2y$10$nI/z5xi/NOAGFi0J3xiI2OoXekjgREwkOAhQ6YOM91UY9bapRFpx6', NULL, 'siswa', '2026-01-30 12:47:48', NULL, 1),
(1228, 'ELYA DALVA AZIZAH', '0084101203', NULL, NULL, NULL, '$2y$10$iDufDRxYEIUNczrS27mEDOW.3lz9vwTm0KzdkWrbxjEmN0r29rClC', NULL, 'siswa', '2026-01-30 12:47:48', NULL, 1),
(1229, 'NATASYA AYU AGUSTIN', '0087510564', NULL, NULL, NULL, '$2y$10$7fY45BrSSqZlbPARu9sRwObfmtrz77yIKAqxiXlUBM8m4HdZiXS9C', NULL, 'siswa', '2026-01-30 12:47:48', NULL, 1),
(1230, 'SINTA DESTIANAWATI', '0099081751', NULL, NULL, NULL, '$2y$10$f/nnBIIaBovh.ZljSdfPJ.RuaVaT/PlWRtg7cUpCPjoD.Ps/hwYSG', NULL, 'siswa', '2026-01-30 12:47:48', NULL, 1),
(1231, 'ARESKI SUHERLAN', '0109998749', NULL, NULL, NULL, '$2y$10$i1DlgaYNB9qJYCMtp2FPn.0eu3ixCryZOi43xppvOtJxrolkhhBLS', NULL, 'siswa', '2026-01-30 12:47:48', NULL, 1),
(1232, 'Kiki Refan Fadilah', '0081182486', NULL, NULL, NULL, '$2y$10$iCE6P0XXYrnY897UMOPW..Oy0c.vO09dONp46rp9upvGp1g8sC5ry', NULL, 'siswa', '2026-01-30 12:47:49', NULL, 1),
(1233, 'ALMAYRA HILDAVINA', '0103379503', NULL, NULL, NULL, '$2y$10$TxP4xJt/izXDSqjkXmECJO.WKKuhbkRbqLRvHmJPlEsGtPgsqj4SS', NULL, 'siswa', '2026-01-30 12:47:49', NULL, 1),
(1234, 'RIFAL ATHALLAH RAFA', '0088869520', NULL, NULL, NULL, '$2y$10$0638AdnGb.gQO2DEuv57..YZ08izM5Pe7IKH7lwYMLwkkyN9x0HgG', NULL, 'siswa', '2026-01-30 12:47:49', NULL, 1),
(1235, 'TEGUH ANDIKA YUDISTIRA', '0099822243', NULL, NULL, NULL, '$2y$10$avHRkiqEcjaYm6dUd4428.Y/JhWaSPSrQ9NK4qdpivL2YrJH1i.wy', NULL, 'siswa', '2026-01-30 12:47:49', NULL, 1),
(1236, 'WIZAR HIDAYAT', '0075390505', NULL, NULL, NULL, '$2y$10$FLABar5PI9WOdJ50XeIEleMDSm3Kb7TTr5wkSyX//EWxj2mKsAnve', NULL, 'siswa', '2026-01-30 12:47:49', NULL, 1),
(1237, 'RYA NURAPNI', '0096872850', NULL, NULL, NULL, '$2y$10$1dYiyI0kiUCZvCLRe5rSueFphvi3Q5F.v4ioTRtqTHNB5woDH0EIa', NULL, 'siswa', '2026-01-30 12:47:49', NULL, 1),
(1238, 'FAIRA RAHMADANI', '0097552833', NULL, NULL, NULL, '$2y$10$28ags3hW5YxhEhKve.nxteKk5CTvIWeFKibKEQXsz.GNg0JMpw8mC', NULL, 'siswa', '2026-01-30 12:47:49', NULL, 1),
(1239, 'CHITA CLAUDIYA FANDELAKI', '0098710025', NULL, NULL, NULL, '$2y$10$JETspf48XnL6tYJRcEIn0.RgsQef0qd110ZZivRBjyE97zRT9GhBm', NULL, 'siswa', '2026-01-30 12:47:50', NULL, 1),
(1240, 'VITRAN AKBAR MAULANA', '0103025128', NULL, NULL, NULL, '$2y$10$s6R2cwPyh4ZJ57V56fqT3OGqYu/uJ2Ha57xsXurhdQ0nVAtnWLCVi', NULL, 'siswa', '2026-01-30 12:47:50', NULL, 1),
(1241, 'SITI SOLIHAT', '0084814996', NULL, NULL, NULL, '$2y$10$BuacJE1yRnswh9NlQZ4du.pMB0yIyqX7jSAnZXMpyWDvP9HqtI9Oq', NULL, 'siswa', '2026-01-30 12:47:50', NULL, 1),
(1242, 'TOPA BAMBANG MULYANA', '0078214894', NULL, NULL, NULL, '$2y$10$Zu1d9TvNpM9V0tXIIoC5K.g5Gt7ieTobkdbI9P3k5NuqFrBkNnylO', NULL, 'siswa', '2026-01-30 12:47:50', NULL, 1),
(1243, 'SULIS SYIFA ALTAFUNNISA', '0093811929', NULL, NULL, NULL, '$2y$10$TvaycI8VKWWCHsobFP5cJukD.Q23bUGFIpkBtXOLuw7Zk4HIxbwQi', NULL, 'siswa', '2026-01-30 12:47:50', NULL, 1),
(1244, 'DIKY SUHENDI', '0089918087', NULL, NULL, NULL, '$2y$10$HvHt1gDr6gsp683bJSOLDucGVnd3VqyNG89p/5KcrbU9OwqilVLBu', NULL, 'siswa', '2026-01-30 12:47:50', NULL, 1),
(1245, 'ALIF MUHAMAD', '0078489319', NULL, NULL, NULL, '$2y$10$965OuETRrvRLAQrpG/K3..fpgCRNwrG8TZNHSr6Tc7V7MLnDdwwmC', NULL, 'siswa', '2026-01-30 12:47:50', NULL, 1),
(1246, 'Moch Fauzi Ridwan', '0086753270', NULL, NULL, NULL, '$2y$10$WT4J8ZnBpde.OJn3ksWhRulNs2MJQvF5/GSR.Hypj6./2/SAvIMQq', NULL, 'siswa', '2026-01-30 12:47:51', NULL, 1),
(1247, 'PUTRI ALIEFYA VERN', '0089237650', NULL, NULL, NULL, '$2y$10$8g/pApFI79eUr/pmaCkSeeO.5dvyMUEAovUukTegpGfTBTtUdVq16', NULL, 'siswa', '2026-01-30 12:47:51', NULL, 1),
(1248, 'FAUZAN AL FIRDAUS SHIHAB', '0075856898', NULL, NULL, NULL, '$2y$10$.G5c8suIKB4Aog80RzVXgOJufQtqXGpKoYA51H10E0qxyF/2W2oTi', NULL, 'siswa', '2026-01-30 12:47:51', NULL, 1),
(1249, 'Ade Sunandar', '4134761663200033', NULL, NULL, NULL, '$2y$10$c/ZDta9DX9HbXuBGFt4gcu/npwmWN3VqP9zmuwqH9C3HVxW4txzSu', NULL, 'guru', '2026-01-30 12:49:03', NULL, 1),
(1250, 'Aep Saepudin', '6935773674130192', NULL, NULL, NULL, '$2y$10$StT7TcaDanFkIBjqd5sh7O2B8Lk5bO0lZVo0O2gwYsQiPJTFFIdDy', NULL, 'guru', '2026-01-30 12:49:03', NULL, 1),
(1251, 'Agun Gunawan', '1739779680130102', NULL, NULL, NULL, '$2y$10$d2wctLGZ5hp6h0KQap9pieGGQkT988iRmXskk6KBZ38fQhXzCElbK', NULL, 'guru', '2026-01-30 12:49:03', NULL, 1),
(1252, 'Aripin', '2750763664130152', NULL, NULL, NULL, '$2y$10$QAmVC7ivaoFcRRWsY0CP2uyldylMoslr8yEfdAU89tqoFvBrNqSZ.', NULL, 'guru', '2026-01-30 12:49:03', NULL, 1),
(1253, 'CICA ROIHATUL JANAH', '3213086612960004', NULL, NULL, NULL, '$2y$10$JZpQiCNyrcUaP3eXr0eNMOU.HKmNNDoWCCnRrwIHe1jpvRQw1G9O.', NULL, 'guru', '2026-01-30 12:49:03', NULL, 1),
(1254, 'FIKRI MAEDAN FAHMI', '6435769670230313', NULL, NULL, NULL, '$2y$10$r2wXtYrNxJotL4DWITFMOe9Ad2tS1h8Kk6HDlijat10vL1DAxIKrm', NULL, 'guru', '2026-01-30 12:49:03', NULL, 1),
(1255, 'FITRI ANJANI SAEPUDIN', '2453774675230182', NULL, NULL, NULL, '$2y$10$WRoGbRAC4XSFQ2O2nrIX8.nGhaHD8Hb0b7aOkFxboAivAcOFs9WXW', NULL, 'guru', '2026-01-30 12:49:03', NULL, 1),
(1256, 'IIK TAOPIK HASAN', '8148759660200023', NULL, NULL, NULL, '$2y$10$gH8RIOxcjZ4YPSvXCoTSsOfffbzqC4I/H6NP0p/1yxjLRGM1AYLzq', NULL, 'guru', '2026-01-30 12:49:03', NULL, 1),
(1257, 'Irma Purwanti', '4141776677230233', NULL, NULL, NULL, '$2y$10$VF4vaeDlxFtSbU6DSgo5Vu792z.3yYF19KEZ.xz3tgBxpH198moF.', NULL, 'guru', '2026-01-30 12:49:03', NULL, 1),
(1258, 'Iwan Setiawan', '1136761662130133', NULL, NULL, NULL, '$2y$10$3gV2qBcPElcuwHE8W0.0u.VJwxgcplwMZT6T77wUHXQfNAG66NdUe', NULL, 'guru', '2026-01-30 12:49:03', NULL, 1),
(1259, 'M. D. AMUNG SUNARYA, S.AG', '2539752653120002', NULL, NULL, NULL, '$2y$10$MlYSLEyQ0eeOG.86yTWQAOaCHnLE7DMU4RlKY8MOPsPbJBFq1wZXy', NULL, 'guru', '2026-01-30 12:49:04', NULL, 1),
(1260, 'Nurfitria', '4859768669230202', NULL, NULL, NULL, '$2y$10$551rAOSeJWgHQ.m3VSJu.eZgRkfNTguC3PVNsljCZAaDSBi0nSt9q', NULL, 'guru', '2026-01-30 12:49:04', NULL, 1),
(1261, 'RIAN ALVIANA', '0649775676130212', NULL, NULL, NULL, '$2y$10$bjuZ9wqLMOTcro5lui3GiOYSjzioTnZO41OkPiwn/OSIq2euENzY6', NULL, 'guru', '2026-01-30 12:49:04', NULL, 1),
(1262, 'Rijal Agustian', '9144771672130073', NULL, NULL, NULL, '$2y$10$tMdMVMPXmNapdFVPr/fnVubmydwgn51FtwUeuuNU5ywZR3L.c3x.m', NULL, 'guru', '2026-01-30 12:49:04', NULL, 1),
(1263, 'Riki Wahyudin', '3561779680130043', NULL, NULL, NULL, '$2y$10$dMmNRIwFdnnUVTYcJUaMiO.2arDa2xEnq0oIV1ITeYCrhQa9XnN3e', NULL, 'guru', '2026-01-30 12:49:04', NULL, 1),
(1264, 'RIRI FEBRIANA', '7559775676230122', NULL, NULL, NULL, '$2y$10$I57OMRiVffqbMNcHCWjr/.tbvmEr1zfQHXozWWuexS1saMd3qPbcO', NULL, 'guru', '2026-01-30 12:49:04', NULL, 1),
(1265, 'Saepudin', '3205190607890001', NULL, NULL, NULL, '$2y$10$8oCWdt51FVNp3vNZEUD59OLwuZgoYOTnRfdhFs/MfjS6c3ia4vqr6', NULL, 'guru', '2026-01-30 12:49:04', NULL, 1),
(1266, 'SHALEH AFIF', '3212230301960003', NULL, NULL, NULL, '$2y$10$Y8xZPS8GGZjuW/3dtsoyeuo9rgKbUfhGleu6.rlhv6n0eatDZyQxe', NULL, 'guru', '2026-01-30 12:49:04', NULL, 1),
(1267, 'Siti Nurjanah', '5655768669230452', NULL, NULL, NULL, '$2y$10$AHncYuWoJtNxgEVsui3HcuKZbkHelOCqAoX8L.joR7i3lgy9Q7fcO', NULL, 'guru', '2026-01-30 12:49:04', NULL, 1),
(1268, 'SIVA ISTIKOMAH', '7252772673230213', NULL, NULL, NULL, '$2y$10$pAasjMY23ufJ3pNP.ire9uhtva4d1nxVpaax3JQhAGbJoanBKqkUS', NULL, 'guru', '2026-01-30 12:49:04', NULL, 1),
(1269, 'Suparman', '1756741644200022', NULL, NULL, NULL, '$2y$10$NNIftRag.N4RI3VaTB2y5.ZDGoWqSWMK8ah2BbnAVP3fDnVgSFF4e', NULL, 'guru', '2026-01-30 12:49:04', NULL, 1),
(1270, 'Triana Nugraha', '8142770671130063', NULL, NULL, NULL, '$2y$10$dMslY1uTyXHMGIpRRTbbke.1vPKBXRJ4TfJEqlArN6JaQMnaytnW2', NULL, 'guru', '2026-01-30 12:49:04', NULL, 1),
(1271, 'YUYUM SUMYATI', '4034776677230203', NULL, NULL, NULL, '$2y$10$3s4TrlmmT8s5h9jn8hllgO1POwt9wgcw3X59WpF8QAp65q1N/XWEK', NULL, 'guru', '2026-01-30 12:49:04', NULL, 1),
(1272, 'Ade Sunandar', '3213280802830001', NULL, NULL, NULL, '$2y$10$rs8eGi5p66p/3FVMsVUxYe5HcTNTv4UnzYOVF.GPpRivotHdFAZFO', NULL, 'guru', '2026-01-30 15:36:16', NULL, 1),
(1273, 'Aep Saepudin', '3213010306950004', NULL, NULL, NULL, '$2y$10$zTzU9ouAXmhNUuPr2m8ZEuq6BYton4J8pCWbZaecO.Ql7JM5.nMkC', NULL, 'guru', '2026-01-30 15:36:16', NULL, 1),
(1274, 'Agun Gunawan', '3213191010970001', '', NULL, '1080546870', '$2y$10$Tr75UD5eGSu53M/cOTUVhOXdA27L7yMYnNtoZoL48tSQGqr5J1xVq', NULL, 'guru', '2026-01-30 15:36:17', NULL, 1),
(1275, 'Aripin', '3213121804850003', NULL, NULL, NULL, '$2y$10$4v5AfOTo.cejx8AtxfrHO.QAzDMj979yZkVMSFkJEksWXT64WmYyy', NULL, 'guru', '2026-01-30 15:36:17', NULL, 1),
(1276, 'FIKRI MAEDAN FAHMI', '3213294311910001', NULL, NULL, NULL, '$2y$10$a4x6yvYmVdQqPXi8lksLB.sreCB6jI2rxLuaTROhp2LXoMAP3ZX5S', NULL, 'guru', '2026-01-30 15:36:17', NULL, 1),
(1277, 'FITRI ANJANI SAEPUDIN', '3213026101960005', NULL, NULL, NULL, '$2y$10$3EHUlnoo5S7oBssly3jtOe1Od8SbXkh5.d.W83tUBZksmkwtEUp7K', NULL, 'guru', '2026-01-30 15:36:17', NULL, 1),
(1278, 'IIK TAOPIK HASAN', '3213021608810010', NULL, NULL, NULL, '$2y$10$6Y2MdpqhCHCVvIG77P8pJOprK6sdfpsEwCQOU1jtR0U7cawj8FdLy', NULL, 'guru', '2026-01-30 15:36:17', NULL, 1),
(1279, 'Irma Purwanti', '3213264908880001', NULL, NULL, NULL, '$2y$10$9aKGMuyGIsp2dTWRaBl7mOo4VDYAvhf6SfDHEm5bUpk2ldaf9Qwdy', NULL, 'guru', '2026-01-30 15:36:17', NULL, 1),
(1280, 'Iwan Setiawan', '3213020408830007', NULL, NULL, NULL, '$2y$10$xvYF8dHMZjIsHsxaR6y91.TXhAnXmR7hCm3hHM2tdBJYd3U2p8Sga', NULL, 'guru', '2026-01-30 15:36:17', NULL, 1),
(1281, 'M. D. AMUNG SUNARYA, S.AG', '3213120702740001', NULL, NULL, NULL, '$2y$10$.ZC3scU.aIX3tb9VDQeI2eNlTUh9GFGh69UljOwCbVzWzt6cp47Lu', NULL, 'guru', '2026-01-30 15:36:17', NULL, 1),
(1282, 'Nurfitria', '3213026705900001', NULL, NULL, NULL, '$2y$10$XtS8v4L8GqY5/s.SSg3ye.2G6YJWhh62y/xSihicw4sZIuaxQVcnm', NULL, 'guru', '2026-01-30 15:36:17', NULL, 1),
(1283, 'RIAN ALVIANA', '3213011703970002', NULL, NULL, NULL, '$2y$10$VPPC.9naHl12r.oD//mbJ.e5am6KwLVXNA2ieLMkKS/80awstWNcu', NULL, 'guru', '2026-01-30 15:36:17', NULL, 1),
(1284, 'Rijal Agustian', '3213021208930003', NULL, NULL, NULL, '$2y$10$a81iS9cajoixMKjdR1bnouKJ70cE3xBfsQbgJQttmZ08SCcGWhBn6', NULL, 'guru', '2026-01-30 15:36:17', NULL, 1),
(1285, 'RIRI FEBRIANA', '3213016702970002', NULL, NULL, NULL, '$2y$10$kDlzYoGf3vNBHPGw0JHHR.1mtaEvyzuqtYuYRyFo3xx0QbsiFwGQu', NULL, 'guru', '2026-01-30 15:36:17', NULL, 1),
(1286, 'Siti Nurjanah', '3213125005900004', NULL, NULL, NULL, '$2y$10$5PXkN7Cs2LUhHthFs7EiY.iEILNWJC3EDat4pzU/VX8W8BiKTMfSa', NULL, 'guru', '2026-01-30 15:36:17', NULL, 1),
(1287, 'SIVA ISTIKOMAH', '3213196009940001', NULL, NULL, NULL, '$2y$10$Eq1G1c1mvVR0HY3AVuyS6OwsItsbjcToPheNwL710Xaiioxfkghj2', NULL, 'guru', '2026-01-30 15:36:18', NULL, 1),
(1288, 'Suparman', '3213262404630002', NULL, NULL, NULL, '$2y$10$fI8.H6MEiTZPhqQqbchXZu3ifRXmtCFy9geGEKGY4jDufAq6eUa.C', NULL, 'guru', '2026-01-30 15:36:18', NULL, 1),
(1289, 'Triana Nugraha', '3213121008920005', NULL, NULL, NULL, '$2y$10$aI8LNzWhbXfcY0UHVfJNZOWHsmSLTdjWMsbbCkkogPGyjchJCFvNO', NULL, 'guru', '2026-01-30 15:36:18', NULL, 1),
(1290, 'YUYUM SUMYATI', '3213194207980003', NULL, NULL, NULL, '$2y$10$IU9rNq9FgyBjwRA8NU3bc.b3OBOtbvn7B2zZZRpd4M1zdjTOJY/tC', NULL, 'guru', '2026-01-30 15:36:18', NULL, 1);

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
(46, 14, 1),
(47, 14, 8),
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
(156, 791, 8),
(178, 6, 1),
(179, 6, 2),
(180, 6, 3),
(181, 6, 4),
(182, 6, 5),
(183, 6, 6),
(184, 6, 7),
(185, 6, 8),
(186, 6, 9),
(187, 6, 10),
(188, 6, 11),
(197, 8, 6),
(198, 8, 7),
(199, 8, 8),
(200, 8, 9),
(201, 8, 10),
(202, 792, 11),
(203, 793, 11),
(204, 794, 11),
(205, 795, 11),
(206, 796, 11),
(207, 797, 11),
(208, 798, 11),
(209, 799, 11),
(210, 800, 11),
(211, 801, 11),
(212, 802, 11),
(213, 803, 11),
(214, 804, 11),
(215, 805, 11),
(216, 806, 11),
(217, 807, 11),
(218, 808, 11),
(219, 809, 11),
(220, 810, 11),
(221, 811, 11),
(222, 812, 11),
(223, 813, 11),
(224, 814, 11),
(225, 815, 11),
(226, 816, 11),
(227, 817, 11),
(228, 818, 11),
(229, 819, 11),
(230, 820, 11),
(231, 821, 11),
(232, 822, 11),
(233, 823, 11),
(234, 824, 11),
(235, 825, 11),
(236, 826, 11),
(237, 827, 11),
(238, 828, 11),
(239, 829, 11),
(240, 830, 11),
(241, 831, 11),
(242, 832, 11),
(243, 833, 11),
(244, 834, 11),
(245, 835, 11),
(246, 836, 11),
(247, 837, 11),
(248, 838, 11),
(249, 839, 11),
(250, 840, 11),
(251, 841, 11),
(252, 842, 11),
(253, 843, 11),
(254, 844, 11),
(255, 845, 11),
(256, 846, 11),
(257, 847, 11),
(258, 848, 11),
(259, 849, 11),
(260, 850, 11),
(261, 851, 11),
(262, 852, 11),
(263, 853, 11),
(264, 854, 11),
(265, 855, 11),
(266, 856, 11),
(267, 857, 11),
(268, 858, 11),
(269, 859, 11),
(270, 860, 11),
(271, 861, 11),
(272, 862, 11),
(273, 863, 11),
(274, 864, 11),
(275, 865, 11),
(276, 866, 11),
(277, 867, 11),
(278, 868, 11),
(279, 869, 11),
(280, 870, 11),
(281, 871, 11),
(282, 872, 11),
(283, 873, 11),
(284, 874, 11),
(285, 875, 11),
(286, 876, 11),
(287, 877, 11),
(288, 878, 11),
(289, 879, 11),
(290, 880, 11),
(291, 881, 11),
(292, 882, 11),
(293, 883, 11),
(294, 884, 11),
(295, 885, 11),
(296, 886, 11),
(297, 887, 11),
(298, 888, 11),
(299, 889, 11),
(300, 890, 11),
(301, 891, 11),
(302, 892, 11),
(303, 893, 11),
(304, 894, 11),
(305, 895, 11),
(306, 896, 11),
(307, 897, 11),
(308, 898, 11),
(309, 899, 11),
(310, 900, 11),
(311, 901, 11),
(312, 902, 11),
(313, 903, 11),
(314, 904, 11),
(315, 905, 11),
(316, 906, 11),
(317, 907, 11),
(318, 908, 11),
(319, 909, 11),
(320, 910, 11),
(321, 911, 11),
(322, 912, 11),
(323, 913, 11),
(324, 914, 11),
(325, 915, 11),
(326, 916, 11),
(327, 917, 11),
(328, 918, 11),
(329, 919, 11),
(330, 920, 11),
(331, 921, 11),
(332, 922, 11),
(333, 923, 11),
(334, 924, 11),
(335, 925, 11),
(336, 926, 11),
(337, 927, 11),
(338, 928, 11),
(339, 929, 11),
(340, 930, 11),
(341, 931, 11),
(342, 932, 11),
(343, 933, 11),
(344, 934, 11),
(345, 935, 11),
(346, 936, 11),
(347, 937, 11),
(348, 938, 11),
(349, 939, 11),
(350, 940, 11),
(351, 941, 11),
(352, 942, 11),
(353, 943, 11),
(354, 944, 11),
(355, 945, 11),
(356, 946, 11),
(357, 947, 11),
(358, 948, 11),
(359, 949, 11),
(360, 950, 11),
(361, 951, 11),
(362, 952, 11),
(363, 953, 11),
(364, 954, 11),
(365, 955, 11),
(366, 956, 11),
(367, 957, 11),
(368, 958, 11),
(369, 959, 11),
(370, 960, 11),
(371, 961, 11),
(372, 962, 11),
(373, 963, 11),
(374, 964, 11),
(375, 965, 11),
(376, 966, 11),
(377, 967, 11),
(378, 968, 11),
(379, 969, 11),
(380, 970, 11),
(381, 971, 11),
(382, 972, 11),
(383, 973, 11),
(384, 974, 11),
(385, 975, 11),
(386, 976, 11),
(387, 977, 11),
(388, 978, 11),
(389, 979, 11),
(390, 980, 11),
(391, 981, 11),
(392, 982, 11),
(393, 983, 11),
(394, 984, 11),
(395, 985, 11),
(396, 986, 11),
(397, 987, 11),
(398, 988, 11),
(399, 989, 11),
(400, 990, 11),
(401, 991, 11),
(402, 992, 11),
(403, 993, 11),
(404, 994, 11),
(405, 995, 11),
(406, 996, 11),
(407, 997, 11),
(408, 998, 11),
(409, 999, 11),
(410, 1000, 11),
(411, 1001, 11),
(412, 1002, 11),
(413, 1003, 11),
(414, 1004, 11),
(415, 1005, 11),
(416, 1006, 11),
(417, 1007, 11),
(418, 1008, 11),
(419, 1009, 11),
(420, 1010, 11),
(421, 1011, 11),
(422, 1012, 11),
(423, 1013, 11),
(424, 1014, 11),
(425, 1015, 11),
(426, 1016, 11),
(427, 1017, 11),
(428, 1018, 11),
(429, 1019, 11),
(430, 1020, 11),
(431, 1021, 11),
(432, 1022, 11),
(433, 1023, 11),
(434, 1024, 11),
(435, 1025, 11),
(436, 1026, 11),
(437, 1027, 11),
(438, 1028, 11),
(439, 1029, 11),
(440, 1030, 11),
(441, 1031, 11),
(442, 1032, 11),
(443, 1033, 11),
(444, 1034, 11),
(445, 1035, 11),
(446, 1036, 11),
(447, 1037, 11),
(448, 1038, 11),
(449, 1039, 11),
(450, 1040, 11),
(451, 1041, 11),
(452, 1042, 11),
(453, 1043, 11),
(454, 1044, 11),
(455, 1045, 11),
(456, 1046, 11),
(457, 1047, 11),
(458, 1048, 11),
(459, 1049, 11),
(460, 1050, 11),
(461, 1051, 11),
(462, 1052, 11),
(463, 1053, 11),
(464, 1054, 11),
(465, 1055, 11),
(466, 1056, 11),
(467, 1057, 11),
(468, 1058, 11),
(469, 1059, 11),
(470, 1060, 11),
(471, 1061, 11),
(472, 1062, 11),
(473, 1063, 11),
(474, 1064, 11),
(475, 1065, 11),
(476, 1066, 11),
(477, 1067, 11),
(478, 1068, 11),
(479, 1069, 11),
(480, 1070, 11),
(481, 1071, 11),
(482, 1072, 11),
(483, 1073, 11),
(484, 1074, 11),
(485, 1075, 11),
(486, 1076, 11),
(487, 1077, 11),
(488, 1078, 11),
(489, 1079, 11),
(490, 1080, 11),
(491, 1081, 11),
(492, 1082, 11),
(493, 1083, 11),
(494, 1084, 11),
(495, 1085, 11),
(496, 1086, 11),
(497, 1087, 11),
(498, 1088, 11),
(499, 1089, 11),
(500, 1090, 11),
(501, 1091, 11),
(502, 1092, 11),
(503, 1093, 11),
(504, 1094, 11),
(505, 1095, 11),
(506, 1096, 11),
(507, 1097, 11),
(508, 1098, 11),
(509, 1099, 11),
(510, 1100, 11),
(511, 1101, 11),
(512, 1102, 11),
(513, 1103, 11),
(514, 1104, 11),
(515, 1105, 11),
(516, 1106, 11),
(517, 1107, 11),
(518, 1108, 11),
(519, 1109, 11),
(520, 1110, 11),
(521, 1111, 11),
(522, 1112, 11),
(523, 1113, 11),
(524, 1114, 11),
(525, 1115, 11),
(526, 1116, 11),
(527, 1117, 11),
(528, 1118, 11),
(529, 1119, 11),
(530, 1120, 11),
(531, 1121, 11),
(532, 1122, 11),
(533, 1123, 11),
(534, 1124, 11),
(535, 1125, 11),
(536, 1126, 11),
(537, 1127, 11),
(538, 1128, 11),
(539, 1129, 11),
(540, 1130, 11),
(541, 1131, 11),
(542, 1132, 11),
(543, 1133, 11),
(544, 1134, 11),
(545, 1135, 11),
(546, 1136, 11),
(547, 1137, 11),
(548, 1138, 11),
(549, 1139, 11),
(550, 1140, 11),
(551, 1141, 11),
(552, 1142, 11),
(553, 1143, 11),
(554, 1144, 11),
(555, 1145, 11),
(556, 1146, 11),
(557, 1147, 11),
(558, 1148, 11),
(559, 1149, 11),
(560, 1150, 11),
(561, 1151, 11),
(562, 1152, 11),
(563, 1153, 11),
(564, 1154, 11),
(565, 1155, 11),
(566, 1156, 11),
(567, 1157, 11),
(568, 1158, 11),
(569, 1159, 11),
(570, 1160, 11),
(571, 1161, 11),
(572, 1162, 11),
(573, 1163, 11),
(574, 1164, 11),
(575, 1165, 11),
(576, 1166, 11),
(577, 1167, 11),
(578, 1168, 11),
(579, 1169, 11),
(580, 1170, 11),
(581, 1171, 11),
(582, 1172, 11),
(583, 1173, 11),
(584, 1174, 11),
(585, 1175, 11),
(586, 1176, 11),
(587, 1177, 11),
(588, 1178, 11),
(589, 1179, 11),
(590, 1180, 11),
(591, 1181, 11),
(592, 1182, 11),
(593, 1183, 11),
(594, 1184, 11),
(595, 1185, 11),
(596, 1186, 11),
(597, 1187, 11),
(598, 1188, 11),
(599, 1189, 11),
(600, 1190, 11),
(601, 1191, 11),
(602, 1192, 11),
(603, 1193, 11),
(604, 1194, 11),
(605, 1195, 11),
(606, 1196, 11),
(607, 1197, 11),
(608, 1198, 11),
(609, 1199, 11),
(610, 1200, 11),
(611, 1201, 11),
(612, 1202, 11),
(613, 1203, 11),
(614, 1204, 11),
(615, 1205, 11),
(616, 1206, 11),
(617, 1207, 11),
(618, 1208, 11),
(619, 1209, 11),
(620, 1210, 11),
(621, 1211, 11),
(622, 1212, 11),
(623, 1213, 11),
(624, 1214, 11),
(625, 1215, 11),
(626, 1216, 11),
(627, 1217, 11),
(628, 1218, 11),
(629, 1219, 11),
(630, 1220, 11),
(631, 1221, 11),
(632, 1222, 11),
(633, 1223, 11),
(634, 1224, 11),
(635, 1225, 11),
(636, 1226, 11),
(637, 1227, 11),
(638, 1228, 11),
(639, 1229, 11),
(640, 1230, 11),
(641, 1231, 11),
(642, 1232, 11),
(643, 1233, 11),
(644, 1234, 11),
(645, 1235, 11),
(646, 1236, 11),
(647, 1237, 11),
(648, 1238, 11),
(649, 1239, 11),
(650, 1240, 11),
(651, 1241, 11),
(652, 1242, 11),
(653, 1243, 11),
(654, 1244, 11),
(655, 1245, 11),
(656, 1246, 11),
(657, 1247, 11),
(658, 1248, 11),
(659, 1249, 8),
(660, 1250, 8),
(661, 1251, 8),
(662, 1252, 8),
(663, 1253, 8),
(664, 1254, 8),
(665, 1255, 8),
(666, 1256, 8),
(667, 1257, 8),
(668, 1258, 8),
(669, 1259, 8),
(670, 1260, 8),
(671, 1261, 8),
(672, 1262, 8),
(673, 1263, 8),
(674, 1264, 8),
(675, 1265, 8),
(676, 1266, 8),
(677, 1267, 8),
(678, 1268, 8),
(679, 1269, 8),
(680, 1270, 8),
(681, 1271, 8),
(682, 1272, 8),
(683, 1273, 8),
(684, 1274, 8),
(685, 1275, 8),
(686, 1276, 8),
(687, 1277, 8),
(688, 1278, 8),
(689, 1279, 8),
(690, 1280, 8),
(691, 1281, 8),
(692, 1282, 8),
(693, 1283, 8),
(694, 1284, 8),
(695, 1285, 8),
(696, 1286, 8),
(697, 1287, 8),
(698, 1288, 8),
(699, 1289, 8),
(700, 1290, 8);

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
-- Indeks untuk tabel `tbl_absensi_mapel`
--
ALTER TABLE `tbl_absensi_mapel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_jurnal` (`id_jurnal`),
  ADD KEY `id_siswa` (`id_siswa`);

--
-- Indeks untuk tabel `tbl_anggota_ekskul`
--
ALTER TABLE `tbl_anggota_ekskul`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_bank_soal`
--
ALTER TABLE `tbl_bank_soal`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_bank_soal_kelas`
--
ALTER TABLE `tbl_bank_soal_kelas`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_dapodik_setting`
--
ALTER TABLE `tbl_dapodik_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_divisi`
--
ALTER TABLE `tbl_divisi`
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
-- Indeks untuk tabel `tbl_jadwal_kelas`
--
ALTER TABLE `tbl_jadwal_kelas`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_jadwal_ujian`
--
ALTER TABLE `tbl_jadwal_ujian`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_jam_master`
--
ALTER TABLE `tbl_jam_master`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_jam_sekolah`
--
ALTER TABLE `tbl_jam_sekolah`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_jawaban_siswa`
--
ALTER TABLE `tbl_jawaban_siswa`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_jenis_bayar`
--
ALTER TABLE `tbl_jenis_bayar`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_jenis_pengeluaran`
--
ALTER TABLE `tbl_jenis_pengeluaran`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_jenis_ujian`
--
ALTER TABLE `tbl_jenis_ujian`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_jurnal`
--
ALTER TABLE `tbl_jurnal`
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
-- Indeks untuk tabel `tbl_log_keuangan`
--
ALTER TABLE `tbl_log_keuangan`
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
-- Indeks untuk tabel `tbl_materi`
--
ALTER TABLE `tbl_materi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guru_id` (`guru_id`),
  ADD KEY `mapel_id` (`mapel_id`),
  ADD KEY `kelas_id` (`kelas_id`);

--
-- Indeks untuk tabel `tbl_nilai`
--
ALTER TABLE `tbl_nilai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_jadwal` (`id_jadwal`),
  ADD KEY `id_siswa` (`id_siswa`);

--
-- Indeks untuk tabel `tbl_opsi_soal`
--
ALTER TABLE `tbl_opsi_soal`
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
-- Indeks untuk tabel `tbl_pengeluaran`
--
ALTER TABLE `tbl_pengeluaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_divisi` (`id_divisi`),
  ADD KEY `id_jenis` (`id_jenis`);

--
-- Indeks untuk tabel `tbl_perizinan`
--
ALTER TABLE `tbl_perizinan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_pos_bayar`
--
ALTER TABLE `tbl_pos_bayar`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_presensi`
--
ALTER TABLE `tbl_presensi`
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
-- Indeks untuk tabel `tbl_ruang_peserta`
--
ALTER TABLE `tbl_ruang_peserta`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unik_peserta` (`id_siswa`);

--
-- Indeks untuk tabel `tbl_sekolah`
--
ALTER TABLE `tbl_sekolah`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_sesi`
--
ALTER TABLE `tbl_sesi`
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
-- Indeks untuk tabel `tbl_soal`
--
ALTER TABLE `tbl_soal`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_tagihan`
--
ALTER TABLE `tbl_tagihan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_jenis_bayar` (`id_jenis_bayar`),
  ADD KEY `id_siswa` (`id_siswa`);

--
-- Indeks untuk tabel `tbl_tahun_ajaran`
--
ALTER TABLE `tbl_tahun_ajaran`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_transaksi`
--
ALTER TABLE `tbl_transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_tagihan` (`id_tagihan`);

--
-- Indeks untuk tabel `tbl_tugas`
--
ALTER TABLE `tbl_tugas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guru_id` (`guru_id`),
  ADD KEY `mapel_id` (`mapel_id`),
  ADD KEY `kelas_id` (`kelas_id`);

--
-- Indeks untuk tabel `tbl_tugas_kumpul`
--
ALTER TABLE `tbl_tugas_kumpul`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tugas_id` (`tugas_id`),
  ADD KEY `siswa_id` (`siswa_id`);

--
-- Indeks untuk tabel `tbl_ujian_siswa`
--
ALTER TABLE `tbl_ujian_siswa`
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
-- AUTO_INCREMENT untuk tabel `tbl_absensi_mapel`
--
ALTER TABLE `tbl_absensi_mapel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT untuk tabel `tbl_anggota_ekskul`
--
ALTER TABLE `tbl_anggota_ekskul`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tbl_bank_soal`
--
ALTER TABLE `tbl_bank_soal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `tbl_bank_soal_kelas`
--
ALTER TABLE `tbl_bank_soal_kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `tbl_dapodik_setting`
--
ALTER TABLE `tbl_dapodik_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `tbl_divisi`
--
ALTER TABLE `tbl_divisi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT untuk tabel `tbl_jadwal`
--
ALTER TABLE `tbl_jadwal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `tbl_jadwal_kelas`
--
ALTER TABLE `tbl_jadwal_kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT untuk tabel `tbl_jadwal_ujian`
--
ALTER TABLE `tbl_jadwal_ujian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `tbl_jam_master`
--
ALTER TABLE `tbl_jam_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `tbl_jam_sekolah`
--
ALTER TABLE `tbl_jam_sekolah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `tbl_jawaban_siswa`
--
ALTER TABLE `tbl_jawaban_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=223;

--
-- AUTO_INCREMENT untuk tabel `tbl_jenis_bayar`
--
ALTER TABLE `tbl_jenis_bayar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `tbl_jenis_pengeluaran`
--
ALTER TABLE `tbl_jenis_pengeluaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `tbl_jenis_ujian`
--
ALTER TABLE `tbl_jenis_ujian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `tbl_jurnal`
--
ALTER TABLE `tbl_jurnal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `tbl_jurusan`
--
ALTER TABLE `tbl_jurusan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `tbl_kegiatan`
--
ALTER TABLE `tbl_kegiatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tbl_kelas`
--
ALTER TABLE `tbl_kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT untuk tabel `tbl_log_keuangan`
--
ALTER TABLE `tbl_log_keuangan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `tbl_mapel`
--
ALTER TABLE `tbl_mapel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=254;

--
-- AUTO_INCREMENT untuk tabel `tbl_mapel_jurusan`
--
ALTER TABLE `tbl_mapel_jurusan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1495;

--
-- AUTO_INCREMENT untuk tabel `tbl_materi`
--
ALTER TABLE `tbl_materi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tbl_nilai`
--
ALTER TABLE `tbl_nilai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tbl_opsi_soal`
--
ALTER TABLE `tbl_opsi_soal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

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
-- AUTO_INCREMENT untuk tabel `tbl_pengeluaran`
--
ALTER TABLE `tbl_pengeluaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tbl_perizinan`
--
ALTER TABLE `tbl_perizinan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tbl_pos_bayar`
--
ALTER TABLE `tbl_pos_bayar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `tbl_presensi`
--
ALTER TABLE `tbl_presensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `tbl_prestasi`
--
ALTER TABLE `tbl_prestasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tbl_ruangan`
--
ALTER TABLE `tbl_ruangan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `tbl_ruang_peserta`
--
ALTER TABLE `tbl_ruang_peserta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `tbl_sekolah`
--
ALTER TABLE `tbl_sekolah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `tbl_sesi`
--
ALTER TABLE `tbl_sesi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `tbl_siswa`
--
ALTER TABLE `tbl_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=509;

--
-- AUTO_INCREMENT untuk tabel `tbl_slider`
--
ALTER TABLE `tbl_slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tbl_soal`
--
ALTER TABLE `tbl_soal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT untuk tabel `tbl_tagihan`
--
ALTER TABLE `tbl_tagihan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=229;

--
-- AUTO_INCREMENT untuk tabel `tbl_tahun_ajaran`
--
ALTER TABLE `tbl_tahun_ajaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `tbl_transaksi`
--
ALTER TABLE `tbl_transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `tbl_tugas`
--
ALTER TABLE `tbl_tugas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tbl_tugas_kumpul`
--
ALTER TABLE `tbl_tugas_kumpul`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tbl_ujian_siswa`
--
ALTER TABLE `tbl_ujian_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT untuk tabel `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1291;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=701;

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

--
-- Ketidakleluasaan untuk tabel `tbl_absensi_mapel`
--
ALTER TABLE `tbl_absensi_mapel`
  ADD CONSTRAINT `tbl_absensi_mapel_ibfk_1` FOREIGN KEY (`id_jurnal`) REFERENCES `tbl_jurnal` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_absensi_mapel_ibfk_2` FOREIGN KEY (`id_siswa`) REFERENCES `tbl_siswa` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tbl_materi`
--
ALTER TABLE `tbl_materi`
  ADD CONSTRAINT `tbl_materi_ibfk_1` FOREIGN KEY (`guru_id`) REFERENCES `tbl_guru` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_materi_ibfk_2` FOREIGN KEY (`mapel_id`) REFERENCES `tbl_mapel` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_materi_ibfk_3` FOREIGN KEY (`kelas_id`) REFERENCES `tbl_kelas` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tbl_pengeluaran`
--
ALTER TABLE `tbl_pengeluaran`
  ADD CONSTRAINT `tbl_pengeluaran_ibfk_1` FOREIGN KEY (`id_divisi`) REFERENCES `tbl_divisi` (`id`),
  ADD CONSTRAINT `tbl_pengeluaran_ibfk_2` FOREIGN KEY (`id_jenis`) REFERENCES `tbl_jenis_pengeluaran` (`id`);

--
-- Ketidakleluasaan untuk tabel `tbl_tagihan`
--
ALTER TABLE `tbl_tagihan`
  ADD CONSTRAINT `tbl_tagihan_ibfk_1` FOREIGN KEY (`id_jenis_bayar`) REFERENCES `tbl_jenis_bayar` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_tagihan_ibfk_2` FOREIGN KEY (`id_siswa`) REFERENCES `tbl_siswa` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tbl_transaksi`
--
ALTER TABLE `tbl_transaksi`
  ADD CONSTRAINT `tbl_transaksi_ibfk_1` FOREIGN KEY (`id_tagihan`) REFERENCES `tbl_tagihan` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tbl_tugas`
--
ALTER TABLE `tbl_tugas`
  ADD CONSTRAINT `tbl_tugas_ibfk_1` FOREIGN KEY (`guru_id`) REFERENCES `tbl_guru` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_tugas_ibfk_2` FOREIGN KEY (`mapel_id`) REFERENCES `tbl_mapel` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_tugas_ibfk_3` FOREIGN KEY (`kelas_id`) REFERENCES `tbl_kelas` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tbl_tugas_kumpul`
--
ALTER TABLE `tbl_tugas_kumpul`
  ADD CONSTRAINT `tbl_tugas_kumpul_ibfk_1` FOREIGN KEY (`tugas_id`) REFERENCES `tbl_tugas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_tugas_kumpul_ibfk_2` FOREIGN KEY (`siswa_id`) REFERENCES `tbl_siswa` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
