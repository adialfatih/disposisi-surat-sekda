-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 21, 2026 at 02:08 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aplikasi_disposisi_surat`
--
CREATE DATABASE IF NOT EXISTS `aplikasi_disposisi_surat` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `aplikasi_disposisi_surat`;

-- --------------------------------------------------------

--
-- Table structure for table `agenda_kegiatan`
--

DROP TABLE IF EXISTS `agenda_kegiatan`;
CREATE TABLE `agenda_kegiatan` (
  `id` int(10) UNSIGNED NOT NULL,
  `surat_masuk_id` int(10) UNSIGNED DEFAULT NULL,
  `disposisi_id` int(10) UNSIGNED DEFAULT NULL,
  `tanggal` date NOT NULL,
  `jam` time DEFAULT NULL,
  `tempat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `perihal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `disposisi_surat`
--

DROP TABLE IF EXISTS `disposisi_surat`;
CREATE TABLE `disposisi_surat` (
  `id` int(10) UNSIGNED NOT NULL,
  `surat_masuk_id` int(10) UNSIGNED NOT NULL,
  `tujuan_disposisi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_disposisi` date NOT NULL,
  `perintah` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('belum_ditindaklanjuti','sedang_diproses','selesai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_ditindaklanjuti',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penomoran_surat`
--

DROP TABLE IF EXISTS `penomoran_surat`;
CREATE TABLE `penomoran_surat` (
  `id` int(10) UNSIGNED NOT NULL,
  `jenis_surat_slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_surat_label` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_keamanan` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomor_urut` int(11) NOT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_klasifikasi` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_umum` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nomor_surat` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `tahun` int(11) NOT NULL,
  `tanggal_surat` date NOT NULL,
  `perihal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pengolah` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tujuan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `penomoran_surat`
--

INSERT INTO `penomoran_surat` (`id`, `jenis_surat_slug`, `jenis_surat_label`, `kode_keamanan`, `nomor_urut`, `catatan`, `kode_klasifikasi`, `kode_umum`, `nomor_surat`, `tahun`, `tanggal_surat`, `perihal`, `pengolah`, `tujuan`, `created_by`, `created_at`, `updated_at`) VALUES
(2, 'setda-surat-keluar', 'SETDA - Surat Keluar', 'B', 1, '', '800.1.11.1', 'UM', 'B/1/800.1.11.1/UM/2026', 2026, '2026-04-20', 'SPT An. MUHAMMAD HASNAIN HAIKAL Melaksanakan tugas sbg koordinator driver di lingkup setda  tgl 2 januari 2026\"', 'Bag. Umum', 'ybs', 1, '2026-04-20 22:14:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `surat_masuk`
--

DROP TABLE IF EXISTS `surat_masuk`;
CREATE TABLE `surat_masuk` (
  `id` int(10) UNSIGNED NOT NULL,
  `kategori` enum('permohonan','undangan','lainnya') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'lainnya',
  `nomor_agenda` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `asal_surat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_surat` date NOT NULL,
  `nomor_surat` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `perihal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `asal_berkas` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_surat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_terima` date NOT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('masuk','didisposisi','selesai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'masuk',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `hak_akses` enum('admin','user') NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
-- Indexes for table `agenda_kegiatan`
--
ALTER TABLE `agenda_kegiatan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_agenda_surat_masuk` (`surat_masuk_id`),
  ADD KEY `fk_agenda_disposisi` (`disposisi_id`);

--
-- Indexes for table `disposisi_surat`
--
ALTER TABLE `disposisi_surat`
  ADD PRIMARY KEY (`id`),
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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agenda_kegiatan`
--
ALTER TABLE `agenda_kegiatan`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `disposisi_surat`
--
ALTER TABLE `disposisi_surat`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `penomoran_surat`
--
ALTER TABLE `penomoran_surat`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `surat_masuk`
--
ALTER TABLE `surat_masuk`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

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
-- Constraints for table `disposisi_surat`
--
ALTER TABLE `disposisi_surat`
  ADD CONSTRAINT `fk_disposisi_surat_masuk` FOREIGN KEY (`surat_masuk_id`) REFERENCES `surat_masuk` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
