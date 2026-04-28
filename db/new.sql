-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 28, 2026 at 07:44 PM
-- Server version: 10.6.24-MariaDB-cll-lve
-- PHP Version: 8.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pstxmyid_disposisi`
--

-- --------------------------------------------------------

--
-- Table structure for table `acara`
--

CREATE TABLE `acara` (
  `id` int(10) UNSIGNED NOT NULL,
  `nomor_agenda` varchar(30) NOT NULL COMMENT 'Relasi ke surat_masuk_v2.nomor_agenda',
  `tanggal_acara` date NOT NULL,
  `jam_acara` time NOT NULL,
  `tempat_acara` varchar(255) NOT NULL,
  `perihal_acara` varchar(255) NOT NULL,
  `catatan_acara` text DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `acara`
--

INSERT INTO `acara` (`id`, `nomor_agenda`, `tanggal_acara`, `jam_acara`, `tempat_acara`, `perihal_acara`, `catatan_acara`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'SM-2026-002', '2026-04-30', '08:00:00', 'owek', 'owek acara perihal', 'catatan owek', 1, '2026-04-27 14:23:57', '2026-04-27 19:55:19'),
(2, 'SM-2026-003', '2026-04-30', '08:00:00', 'Ruang Tiga Negeri', 'Sosialisasi', 'Memberi sambutan', 1, '2026-04-27 19:22:41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `agenda_kegiatan`
--

CREATE TABLE `agenda_kegiatan` (
  `id` int(10) UNSIGNED NOT NULL,
  `surat_masuk_id` int(10) UNSIGNED DEFAULT NULL,
  `disposisi_id` int(10) UNSIGNED DEFAULT NULL,
  `tanggal` date NOT NULL,
  `jam` time DEFAULT NULL,
  `tempat` varchar(255) DEFAULT NULL,
  `perihal` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `disposisi_penerima`
--

CREATE TABLE `disposisi_penerima` (
  `id` int(10) UNSIGNED NOT NULL,
  `disposisi_id` int(10) UNSIGNED NOT NULL,
  `nama_penerima` varchar(150) NOT NULL,
  `status_kirim` enum('belum','sudah') NOT NULL DEFAULT 'belum',
  `tgl_kirim` datetime DEFAULT NULL,
  `nama_pengirim` varchar(150) DEFAULT NULL,
  `foto_bukti_kirim` varchar(255) DEFAULT NULL,
  `ttd_pengirim` varchar(255) DEFAULT NULL,
  `status_terima` enum('belum','sudah') NOT NULL DEFAULT 'belum',
  `tgl_terima` datetime DEFAULT NULL,
  `nama_penerima_ttd` varchar(150) DEFAULT NULL,
  `foto_bukti_terima` varchar(255) DEFAULT NULL,
  `ttd_penerima` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `disposisi_penerima`
--

INSERT INTO `disposisi_penerima` (`id`, `disposisi_id`, `nama_penerima`, `status_kirim`, `tgl_kirim`, `nama_pengirim`, `foto_bukti_kirim`, `ttd_pengirim`, `status_terima`, `tgl_terima`, `nama_penerima_ttd`, `foto_bukti_terima`, `ttd_penerima`, `created_at`, `updated_at`) VALUES
(1, 4, 'BPKAD', 'sudah', '2026-04-22 22:17:12', 'KURIR JNE', NULL, 'uploads/ttd/ttd_kirim_69e8e678d32cb.png', 'sudah', '2026-04-22 22:19:55', 'faqih iqbal', 'uploads/bukti/terima_69e8e71b1dd2c.jpg', 'uploads/ttd/ttd_terima_69e8e71b1edf0.png', '2026-04-22 22:16:45', '2026-04-22 22:19:55'),
(2, 4, 'BKPPD', 'sudah', '2026-04-22 22:20:20', 'kurir jne', NULL, 'uploads/ttd/ttd_kirim_69e8e734e626d.png', 'sudah', '2026-04-22 22:20:29', 'oye', NULL, 'uploads/ttd/ttd_terima_69e8e73d5dc42.png', '2026-04-22 22:16:45', '2026-04-22 22:20:29'),
(4, 5, 'Naufal', 'sudah', '2026-04-22 22:26:26', 'Caraka', 'uploads/bukti/kirim_69e8e8a285bb1.jpg', 'uploads/ttd/ttd_kirim_69e8e8a2b686a.png', 'sudah', '2026-04-22 22:27:29', 'Eliza', 'uploads/bukti/terima_69e8e8e155ddd.jpg', 'uploads/ttd/ttd_terima_69e8e8e1583f6.png', '2026-04-22 22:24:37', '2026-04-22 22:27:29'),
(5, 6, 'sdfa', 'sudah', '2026-04-23 18:57:22', 'Adi', 'uploads/bukti/kirim_69ea0921f24dd.jpg', 'uploads/ttd/ttd_kirim_69ea09228bc41.png', 'sudah', '2026-04-23 18:57:49', 'Hada', 'uploads/bukti/terima_69ea0939b0494.jpg', 'uploads/ttd/ttd_terima_69ea093d680b9.png', '2026-04-23 10:24:58', '2026-04-23 18:57:49'),
(7, 8, 'Bkpsdm', 'sudah', '2026-04-23 21:44:37', 'Cholidin', NULL, NULL, 'sudah', '2026-04-23 21:46:46', 'Dwi', 'uploads/bukti/terima_69ea30d697011.jpg', NULL, '2026-04-23 21:37:35', '2026-04-23 21:46:46'),
(8, 8, 'Bapperida', 'sudah', '2026-04-23 21:45:07', 'Cholidin', NULL, NULL, 'sudah', '2026-04-23 22:04:44', 'Yayak', 'uploads/bukti/terima_69ea35081c8d5.jpg', 'uploads/ttd/ttd_terima_69ea350c7bca6.png', '2026-04-23 21:37:35', '2026-04-23 22:04:44'),
(9, 7, 'Yayak', 'sudah', '2026-04-23 21:49:21', 'Cholidin', 'uploads/bukti/kirim_69ea31717b5d2.jpg', 'uploads/ttd/ttd_kirim_69ea31717f84e.png', 'sudah', '2026-04-23 21:50:48', 'Tatang', 'uploads/bukti/terima_69ea31c84f32e.jpg', 'uploads/ttd/ttd_terima_69ea31c864a8d.png', '2026-04-23 21:48:10', '2026-04-23 21:50:48'),
(10, 9, 'Nabila', 'sudah', '2026-04-24 14:29:54', 'Kholidin', 'uploads/bukti/kirim_69eb1bf26b570.jpeg', 'uploads/ttd/ttd_kirim_69eb1bf26be08.png', 'sudah', '2026-04-24 14:33:53', 'Andi', 'uploads/bukti/terima_69eb1cce693da.jpg', 'uploads/ttd/ttd_terima_69eb1ce1312cd.png', '2026-04-24 14:26:35', '2026-04-24 14:33:53'),
(11, 10, 'agus', 'sudah', '2026-04-24 14:36:34', 'Kholidin', 'uploads/bukti/kirim_69eb1d82f27a0.jpeg', 'uploads/ttd/ttd_kirim_69eb1d82f363b.png', 'sudah', '2026-04-24 14:37:30', 'Andi', 'uploads/bukti/terima_69eb1db53eda9.jpg', 'uploads/ttd/ttd_terima_69eb1dbab8703.png', '2026-04-24 14:35:55', '2026-04-24 14:37:30'),
(12, 11, 'Naufal', 'sudah', '2026-04-25 13:17:33', 'Agus', NULL, 'uploads/ttd/ttd_kirim_69ec5c7d4459c.png', 'sudah', '2026-04-25 13:18:50', 'Naufal', 'uploads/bukti/terima_69ec5cbf30233.jpg', 'uploads/ttd/ttd_terima_69ec5ccaa950d.png', '2026-04-25 13:16:53', '2026-04-25 13:18:50'),
(13, 12, 'ORang nya', 'sudah', '2026-04-26 22:21:17', 'Adi', NULL, NULL, 'sudah', '2026-04-27 19:14:01', 'oye', 'uploads/bukti/terima_69ef53099fb7a.jpg', 'uploads/ttd/ttd_terima_69ee2d7d8fb7d.png', '2026-04-26 22:20:55', '2026-04-27 19:14:01');

-- --------------------------------------------------------

--
-- Table structure for table `disposisi_surat`
--

CREATE TABLE `disposisi_surat` (
  `id` int(10) UNSIGNED NOT NULL,
  `nomor_disposisi` varchar(30) NOT NULL DEFAULT '',
  `surat_masuk_id` int(10) UNSIGNED DEFAULT NULL,
  `mode_surat` enum('agenda','manual') NOT NULL DEFAULT 'agenda',
  `manual_asal_berkas` varchar(150) DEFAULT NULL,
  `manual_nomor_surat` varchar(100) DEFAULT NULL,
  `manual_perihal` varchar(255) DEFAULT NULL,
  `tanggal_disposisi` date NOT NULL,
  `perintah` text NOT NULL,
  `catatan` text DEFAULT NULL,
  `status` enum('draft','dikirim','diterima','selesai') NOT NULL DEFAULT 'draft',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `disposisi_surat`
--

INSERT INTO `disposisi_surat` (`id`, `nomor_disposisi`, `surat_masuk_id`, `mode_surat`, `manual_asal_berkas`, `manual_nomor_surat`, `manual_perihal`, `tanggal_disposisi`, `perintah`, `catatan`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(4, 'DSP-2026-001', 1, 'agenda', NULL, NULL, NULL, '2026-04-22', 'owekamdaos spodkasd a do ad as', 'askdsa md dka sdkoas asdo as da', 'selesai', 1, '2026-04-22 22:16:45', '2026-04-22 22:20:29'),
(5, 'DSP-2026-002', 1, 'agenda', NULL, NULL, NULL, '2026-04-22', 'Jejsnd', 'Jdjdjx', 'selesai', 1, '2026-04-22 22:23:11', '2026-04-22 22:29:06'),
(6, 'DSP-2026-003', 1, 'agenda', NULL, NULL, NULL, '2026-04-23', 'fdsafdf', 'asdfasf', 'selesai', 1, '2026-04-23 10:24:58', '2026-04-23 21:11:17'),
(7, 'DSP-2026-004', 1, 'agenda', NULL, NULL, NULL, '2026-04-23', 'Mohon difollow up hari jumat', '', 'selesai', 1, '2026-04-23 21:12:22', '2026-04-23 21:50:48'),
(8, 'DSP-2026-005', 1, 'agenda', NULL, NULL, NULL, '2026-04-23', 'Tindaklanjuti', '', 'selesai', 1, '2026-04-23 21:37:35', '2026-04-23 21:47:51'),
(9, 'DSP-2026-006', 4, 'agenda', NULL, NULL, NULL, '2026-04-24', 'mohon di tindaklanjuti', '', 'selesai', 1, '2026-04-24 14:26:35', '2026-04-24 14:33:53'),
(10, 'DSP-2026-007', 5, 'agenda', NULL, NULL, NULL, '2026-04-24', 'ewestest', 'setsf', 'selesai', 1, '2026-04-24 14:35:55', '2026-04-24 14:37:30'),
(11, 'DSP-2026-008', NULL, 'manual', 'DINDIK', 'DDK/25/1/005/2026', 'Permohonan pinjam gedung', '2026-04-25', 'Mohon ditindak lanjut', '', 'selesai', 1, '2026-04-25 13:16:53', '2026-04-25 13:18:50'),
(12, 'DSP-2026-009', NULL, 'manual', 'Disperindag', '80/123/123/22', 'Testing perihal', '2026-04-26', 'tanpa perintah', 'oke catatan', 'selesai', 1, '2026-04-26 22:20:55', '2026-04-26 22:21:33');

-- --------------------------------------------------------

--
-- Table structure for table `penomoran_surat`
--

CREATE TABLE `penomoran_surat` (
  `id` int(10) UNSIGNED NOT NULL,
  `jenis_surat_slug` varchar(100) NOT NULL,
  `jenis_surat_label` varchar(150) NOT NULL,
  `kode_keamanan` varchar(20) NOT NULL,
  `nomor_urut` int(11) NOT NULL,
  `catatan` text DEFAULT NULL,
  `kode_klasifikasi` varchar(50) NOT NULL,
  `kode_umum` varchar(50) DEFAULT NULL,
  `nomor_surat` varchar(100) NOT NULL DEFAULT '',
  `tahun` int(11) NOT NULL,
  `tanggal_surat` date NOT NULL,
  `perihal` varchar(255) NOT NULL,
  `pengolah` varchar(150) NOT NULL,
  `no_wa_pengolah` varchar(15) NOT NULL DEFAULT '0',
  `tujuan` varchar(255) NOT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `penomoran_surat`
--

INSERT INTO `penomoran_surat` (`id`, `jenis_surat_slug`, `jenis_surat_label`, `kode_keamanan`, `nomor_urut`, `catatan`, `kode_klasifikasi`, `kode_umum`, `nomor_surat`, `tahun`, `tanggal_surat`, `perihal`, `pengolah`, `no_wa_pengolah`, `tujuan`, `created_by`, `created_at`, `updated_at`) VALUES
(2, 'setda-surat-keluar', 'SETDA - Surat Keluar', 'B', 1, '', '800.1.11.1', 'UM', 'B/1/800.1.11.1/UM/2026', 2026, '2026-04-20', 'SPT An. MUHAMMAD HASNAIN HAIKAL Melaksanakan tugas sbg koordinator driver di lingkup setda  tgl 2 januari 2026\"', 'Bag. Umum', '0', 'ybsk', 1, '2026-04-20 22:14:25', '2026-04-22 21:38:47'),
(3, 'umum-sppd', 'UMUM - SPPD', 'B', 1, 'oye uye', '005.65', 'UM', '005.65/1/2026', 2026, '2026-04-23', 'Tanpa perihal jelas oke', 'Saya pengolahnya', '0', 'Tanpa tujuan jelas', 1, '2026-04-23 18:55:25', '2026-04-23 18:55:49'),
(4, 'setda-surat-keluar', 'SETDA - Surat Keluar', 'B', 2, '', '800', 'UM', 'B/2/800/UM/2026', 2026, '2026-04-23', 'Undangan Upacara Hari Otonomi Daerah', 'Bkpsdm', '0', 'Seluruh OPD', 1, '2026-04-23 21:22:47', NULL),
(6, 'setda-surat-keluar', 'SETDA - Surat Keluar', '', 3, 'oke catatan', '800.1.23', NULL, '/3/800.1.23//2026', 2026, '2026-04-24', 'hal saya tidak tahu', 'adiu', '89783273111', 'tujuan jonggol', 1, '2026-04-24 16:05:55', '2026-04-24 16:08:13'),
(7, 'setda-surat-keluar', 'SETDA - Surat Keluar', 'B', 4, 'Dana operasional lomba hari anak', '090', NULL, 'B/4/090/2026', 2026, '2026-04-25', 'Test', 'Bendahara', '895338390372', 'Dindik', 1, '2026-04-25 13:13:24', NULL),
(8, 'setda-surat-keluar', 'SETDA - Surat Keluar', 'B', 5, '', '800', NULL, 'B/5/800/2026', 2026, '2026-04-27', 'Penilaian ekinerja', 'Bkpsdm', '85325259373', 'Seluruh opd', 1, '2026-04-27 08:57:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `surat_masuk`
--

CREATE TABLE `surat_masuk` (
  `id` int(10) UNSIGNED NOT NULL,
  `kategori` enum('permohonan','undangan','lainnya') NOT NULL DEFAULT 'lainnya',
  `nomor_agenda` varchar(30) NOT NULL,
  `asal_surat` varchar(255) NOT NULL,
  `tanggal_surat` date NOT NULL,
  `nomor_surat` varchar(100) NOT NULL,
  `perihal` varchar(255) NOT NULL,
  `asal_berkas` varchar(150) DEFAULT NULL,
  `file_surat` varchar(255) DEFAULT NULL,
  `tanggal_terima` date NOT NULL,
  `catatan` text DEFAULT NULL,
  `status` enum('masuk','didisposisi','selesai') NOT NULL DEFAULT 'masuk',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `surat_masuk`
--

INSERT INTO `surat_masuk` (`id`, `kategori`, `nomor_agenda`, `asal_surat`, `tanggal_surat`, `nomor_surat`, `perihal`, `asal_berkas`, `file_surat`, `tanggal_terima`, `catatan`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'permohonan', 'SM-2026-001', 'BKPSDMM', '2026-04-21', '800.1.11.1/68/2025', 'SPT kepala sub bagian PBJ bagian pengadaan dan administrasi pembangunan Sekda', 'Dikirim langsung', NULL, '2026-04-21', 'Sudah diagenda nomor 1420', 'selesai', 1, '2026-04-21 21:14:37', '2026-04-23 21:50:48'),
(2, 'permohonan', 'SM-2026-002', 'BKPSDM', '2026-04-23', 'asdf', 'asdfasdf', 'Srikandi', NULL, '2026-04-23', 'asdfasdfa', 'masuk', 1, '2026-04-23 10:25:54', NULL),
(3, 'permohonan', 'SM-2026-003', 'Lsm guyub rukun', '2026-04-23', 'Lsm/gybrukun/23/4/26', 'Permohonan bantuan snack', 'Eksternal', NULL, '2026-04-23', '', 'masuk', 1, '2026-04-23 21:58:49', '2026-04-23 21:59:47'),
(4, 'permohonan', 'SM-2026-004', 'Kominfo', '2026-04-24', 'KNF/2/2026', 'fasilitasi hostin', 'Walikota', NULL, '2026-04-24', 'sudah di approve', 'selesai', 1, '2026-04-24 14:24:41', '2026-04-24 14:33:53'),
(5, 'undangan', 'SM-2026-005', 'dgstg', '2026-04-24', 'sfsd/eegsdg', 'sdfsdf', 'sdfsf', NULL, '2026-04-24', 'sfdsf', 'selesai', 1, '2026-04-24 14:35:34', '2026-04-24 14:37:30'),
(6, 'permohonan', 'SM-2026-006', 'Kominfo', '2026-04-24', 'KNF/5/2026', 'fasilitasi server', 'Walikota', NULL, '2026-04-24', '', 'masuk', 1, '2026-04-24 14:45:55', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `surat_masuk_v2`
--

CREATE TABLE `surat_masuk_v2` (
  `id` int(10) UNSIGNED NOT NULL,
  `kategori` enum('permohonan','undangan','lainnya') NOT NULL DEFAULT 'lainnya',
  `sifat` enum('sangat_segera','segera','rahasia','biasa') NOT NULL DEFAULT 'biasa',
  `nomor_agenda` varchar(30) NOT NULL,
  `asal_surat` varchar(255) NOT NULL,
  `tanggal_surat` date NOT NULL,
  `nomor_surat` varchar(100) NOT NULL,
  `perihal` varchar(255) NOT NULL,
  `asal_berkas` varchar(150) DEFAULT NULL,
  `tanggal_terima` date NOT NULL,
  `diteruskan_kepada` text DEFAULT NULL COMMENT 'Daftar penerima disposisi, dipisah newline',
  `instruksi_tanggapan` tinyint(1) NOT NULL DEFAULT 0,
  `instruksi_proses_lanjut` tinyint(1) NOT NULL DEFAULT 0,
  `instruksi_koordinasi` tinyint(1) NOT NULL DEFAULT 0,
  `instruksi_lainnya` varchar(255) DEFAULT NULL,
  `catatan_disposisi` text DEFAULT NULL,
  `status` enum('masuk','dicetak','didisposisi','selesai') NOT NULL DEFAULT 'masuk',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `surat_masuk_v2`
--

INSERT INTO `surat_masuk_v2` (`id`, `kategori`, `sifat`, `nomor_agenda`, `asal_surat`, `tanggal_surat`, `nomor_surat`, `perihal`, `asal_berkas`, `tanggal_terima`, `diteruskan_kepada`, `instruksi_tanggapan`, `instruksi_proses_lanjut`, `instruksi_koordinasi`, `instruksi_lainnya`, `catatan_disposisi`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'permohonan', 'segera', 'SM-2026-001', 'Kemenag', '2026-04-27', 'KMG/01/008/2026', 'Keberangkatan haji', 'Kemenag', '2026-04-27', NULL, 0, 0, 0, NULL, NULL, 'dicetak', 1, '2026-04-27 08:34:22', '2026-04-27 13:22:12'),
(2, 'undangan', 'rahasia', 'SM-2026-002', 'adsad', '2026-04-27', '1231/34', 'asdasd', 'asdsad', '2026-04-27', NULL, 0, 0, 0, NULL, NULL, 'dicetak', 1, '2026-04-27 14:24:09', '2026-04-27 14:24:25'),
(3, 'undangan', 'segera', 'SM-2026-003', 'Nabilah', '2026-04-27', 'HWH/81/404/2026', 'Undangan Sosialisasi', '', '2026-04-27', 'Kasubag Umum', 0, 0, 1, 'Hadir dan memberikan sambutan', NULL, 'didisposisi', 1, '2026-04-27 19:23:49', '2026-04-27 19:56:41');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `hak_akses` enum('admin','user') NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `username`, `password`, `hak_akses`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Administrator Sekda', 'admin', '$2y$12$crwdo.3i8f9KYwShkIMuZubMSTDFm9QddEaft6aN.L1tQLPI3nm02', 'admin', 1, '2026-04-13 23:20:23', NULL),
(2, 'Operator Umum', 'user', '$2y$12$IfwaynEuLzmMV3w.9wGOVe0l5btyLc4YA5qXnq7NRDISelr4jegr.', 'user', 1, '2026-04-13 23:20:23', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acara`
--
ALTER TABLE `acara`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_acara_nomor_agenda` (`nomor_agenda`);

--
-- Indexes for table `agenda_kegiatan`
--
ALTER TABLE `agenda_kegiatan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_agenda_surat_masuk` (`surat_masuk_id`),
  ADD KEY `fk_agenda_disposisi` (`disposisi_id`);

--
-- Indexes for table `disposisi_penerima`
--
ALTER TABLE `disposisi_penerima`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_penerima_disposisi` (`disposisi_id`);

--
-- Indexes for table `disposisi_surat`
--
ALTER TABLE `disposisi_surat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_nomor_disposisi` (`nomor_disposisi`),
  ADD KEY `fk_disposisi_surat_masuk` (`surat_masuk_id`);

--
-- Indexes for table `penomoran_surat`
--
ALTER TABLE `penomoran_surat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_jenis_tahun_nomor` (`jenis_surat_slug`,`tahun`,`nomor_urut`);

--
-- Indexes for table `surat_masuk`
--
ALTER TABLE `surat_masuk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_nomor_agenda` (`nomor_agenda`);

--
-- Indexes for table `surat_masuk_v2`
--
ALTER TABLE `surat_masuk_v2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `acara`
--
ALTER TABLE `acara`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `agenda_kegiatan`
--
ALTER TABLE `agenda_kegiatan`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `disposisi_penerima`
--
ALTER TABLE `disposisi_penerima`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `disposisi_surat`
--
ALTER TABLE `disposisi_surat`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `penomoran_surat`
--
ALTER TABLE `penomoran_surat`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `surat_masuk`
--
ALTER TABLE `surat_masuk`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `surat_masuk_v2`
--
ALTER TABLE `surat_masuk_v2`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `agenda_kegiatan`
--
ALTER TABLE `agenda_kegiatan`
  ADD CONSTRAINT `fk_agenda_disposisi` FOREIGN KEY (`disposisi_id`) REFERENCES `disposisi_surat` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_agenda_surat_masuk` FOREIGN KEY (`surat_masuk_id`) REFERENCES `surat_masuk` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `disposisi_penerima`
--
ALTER TABLE `disposisi_penerima`
  ADD CONSTRAINT `fk_penerima_disposisi` FOREIGN KEY (`disposisi_id`) REFERENCES `disposisi_surat` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `disposisi_surat`
--
ALTER TABLE `disposisi_surat`
  ADD CONSTRAINT `fk_disposisi_surat_masuk` FOREIGN KEY (`surat_masuk_id`) REFERENCES `surat_masuk` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
