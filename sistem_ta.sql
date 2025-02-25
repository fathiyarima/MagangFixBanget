-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 25, 2025 at 09:06 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistem_ta`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `nama_admin` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(25) NOT NULL,
  `nomor_telepon` varchar(25) NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `nama_admin`, `username`, `password`, `nomor_telepon`, `create_at`) VALUES
(1, 'admin', 'admin', 'admin', '', '2025-02-25 08:03:46');

-- --------------------------------------------------------

--
-- Table structure for table `dosen_pembimbing`
--

CREATE TABLE `dosen_pembimbing` (
  `id_dosen` int(11) NOT NULL,
  `nama_dosen` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `pass` varchar(100) NOT NULL,
  `nomor_telepon` varchar(25) NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `nip` varchar(255) NOT NULL,
  `prodi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id_mahasiswa` int(11) NOT NULL,
  `nama_mahasiswa` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `pass` varchar(100) NOT NULL,
  `nim` varchar(25) NOT NULL,
  `prodi` varchar(100) NOT NULL,
  `kelas` varchar(25) NOT NULL,
  `sistem_magang` blob NOT NULL,
  `nomor_telepon` varchar(25) NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `tema` varchar(255) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `dosen_pembimbing` varchar(255) NOT NULL,
  `form_pendaftaran_persetujuan_tema_TA` blob DEFAULT NULL,
  `bukti_pembayaran_TA` blob DEFAULT NULL,
  `bukti_transkip_nilai_TA` blob DEFAULT NULL,
  `bukti_kelulusan_magang_TA` blob DEFAULT NULL,
  `form_pendaftaran_sempro_seminar` blob DEFAULT NULL,
  `lembar_persetujuan_proposal_ta_seminar` blob DEFAULT NULL,
  `buku_konsultasi_ta_seminar` blob DEFAULT NULL,
  `lembar_berita_acara_seminar` blob DEFAULT NULL,
  `lembar_persetujuan_laporan_ta_ujian` blob DEFAULT NULL,
  `form_pendaftaran_ujian_ta_ujian` blob DEFAULT NULL,
  `lembar_kehadiran_sempro_ujian` blob DEFAULT NULL,
  `buku_konsultasi_ta_ujian` blob DEFAULT NULL,
  `lembar_hasil_nilai_dosbim1_nilai` blob DEFAULT NULL,
  `lembar_hasil_nilai_dosbim2_nilai` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa_dosen`
--

CREATE TABLE `mahasiswa_dosen` (
  `id_mahasiswa` int(11) NOT NULL,
  `id_dosen` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notif`
--

CREATE TABLE `notif` (
  `id` int(11) NOT NULL,
  `admin` int(11) DEFAULT NULL,
  `id_dosen` int(11) DEFAULT NULL,
  `id_mahasiswa` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status_admin` enum('unread','read') NOT NULL DEFAULT 'unread',
  `status_dosen` enum('unread','read') NOT NULL DEFAULT 'unread',
  `status_mahasiswa` enum('unread','read') NOT NULL DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seminar_proposal`
--

CREATE TABLE `seminar_proposal` (
  `id_mahasiswa` int(11) DEFAULT NULL,
  `dosen_pembimbing` enum('nama_dosen1','nama_dosen1') NOT NULL,
  `penyaji_seminar` varchar(100) NOT NULL,
  `kehadiran` int(10) NOT NULL,
  `sppsp` varchar(255) NOT NULL,
  `lbta` varchar(255) NOT NULL,
  `tanggal_disetujui` date NOT NULL,
  `status_seminar` enum('dijadwalkan','ditunda','selesai') NOT NULL,
  `tanggal_seminar` date NOT NULL,
  `lembar_persetujuan_proposal_ta_seminar` tinyint(1) NOT NULL DEFAULT 0,
  `buku_konsultasi_ta_seminar` tinyint(1) NOT NULL DEFAULT 0,
  `lembar_berita_acara_seminar` tinyint(1) NOT NULL DEFAULT 0,
  `form_pendaftaran_sempro_seminar` tinyint(1) NOT NULL DEFAULT 0,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tugas_akhir`
--

CREATE TABLE `tugas_akhir` (
  `id_mahasiswa` int(11) NOT NULL,
  `id_ta` int(11) NOT NULL,
  `tema` varchar(100) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `alasan` varchar(255) NOT NULL,
  `tujuan` varchar(255) NOT NULL,
  `file_ta` varchar(255) NOT NULL,
  `status_pengajuan` enum('Disetujui','Revisi','Ditolak') NOT NULL,
  `alasan_revisi` varchar(255) NOT NULL,
  `status_tanggapan` tinyint(1) NOT NULL,
  `tanggal_pengajuan` date NOT NULL,
  `tanggal_disetujui` date NOT NULL,
  `dosen_pembimbing` varchar(255) NOT NULL,
  `form_pendaftaran_persetujuan_tema_ta` tinyint(1) NOT NULL DEFAULT 0,
  `bukti_pembayaran_ta` tinyint(1) NOT NULL DEFAULT 0,
  `bukti_transkip_nilai_ta` tinyint(1) NOT NULL DEFAULT 0,
  `bukti_kelulusan_magang_ta` tinyint(1) NOT NULL DEFAULT 0,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ujian`
--

CREATE TABLE `ujian` (
  `id_mahasiswa` int(11) DEFAULT NULL,
  `id_ujian` int(11) NOT NULL,
  `pernyataan_persetujuan` varchar(255) DEFAULT NULL,
  `tanggal_disetujui` date DEFAULT NULL,
  `tanggal_ujian` date DEFAULT NULL,
  `pembimbing` enum('nama_dosen1','nama_dosen2') DEFAULT NULL,
  `lbta` varchar(255) DEFAULT NULL,
  `penguji` enum('nama_dosen1','nama_dosen2','nama_dosen3') DEFAULT NULL,
  `nilai` int(100) DEFAULT NULL,
  `status_ujian` enum('dijadwalkan','selesai') DEFAULT NULL,
  `lembar_persetujuan_laporan_ta_ujian` tinyint(1) DEFAULT 0,
  `form_pendaftaran_ujian_ta_ujian` tinyint(1) DEFAULT 0,
  `lembar_kehadiran_sempro_ujian` tinyint(1) DEFAULT 0,
  `buku_konsultasi_ta_ujian` tinyint(1) DEFAULT 0,
  `lembar_hasil_nilai_dosbim1_nilai` tinyint(1) DEFAULT 0,
  `lembar_hasil_nilai_dosbim2_nilai` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `verifikasi_dokumen`
--

CREATE TABLE `verifikasi_dokumen` (
  `id_mahasiswa` int(11) NOT NULL,
  `bukti_pembayaran_TA` tinyint(1) NOT NULL DEFAULT 0,
  `bukti_transkip_nilai_TA` tinyint(1) NOT NULL DEFAULT 0,
  `bukti_kelulusan_magang_TA` tinyint(1) NOT NULL DEFAULT 0,
  `form_pendaftaran_persetujuan_tema_TA` tinyint(1) NOT NULL DEFAULT 0,
  `form_pendaftaran_sempro_seminar` tinyint(1) NOT NULL DEFAULT 0,
  `lembar_persetujuan_proposal_ta_seminar` tinyint(1) NOT NULL DEFAULT 0,
  `buku_konsultasi_ta_seminar` tinyint(1) NOT NULL DEFAULT 0,
  `lembar_berita_acara_seminar` tinyint(1) NOT NULL DEFAULT 0,
  `lembar_persetujuan_laporan_ta_ujian` tinyint(1) NOT NULL DEFAULT 0,
  `form_pendaftaran_ujian_ta_ujian` tinyint(1) NOT NULL DEFAULT 0,
  `lembar_kehadiran_sempro_ujian` tinyint(1) NOT NULL DEFAULT 0,
  `buku_konsultasi_ta_ujian` tinyint(1) NOT NULL DEFAULT 0,
  `lembar_hasil_nilai_dosbim1_nilai` tinyint(1) NOT NULL DEFAULT 0,
  `lembar_hasil_nilai_dosbim2_nilai` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `dosen_pembimbing`
--
ALTER TABLE `dosen_pembimbing`
  ADD PRIMARY KEY (`id_dosen`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id_mahasiswa`);

--
-- Indexes for table `mahasiswa_dosen`
--
ALTER TABLE `mahasiswa_dosen`
  ADD PRIMARY KEY (`id_mahasiswa`,`id_dosen`),
  ADD KEY `id_dosen` (`id_dosen`);

--
-- Indexes for table `notif`
--
ALTER TABLE `notif`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_mahasiswa` (`id_mahasiswa`),
  ADD KEY `id_dosen` (`id_dosen`),
  ADD KEY `fk_notif_admin` (`admin`);

--
-- Indexes for table `seminar_proposal`
--
ALTER TABLE `seminar_proposal`
  ADD KEY `id_mahasiswa` (`id_mahasiswa`);

--
-- Indexes for table `tugas_akhir`
--
ALTER TABLE `tugas_akhir`
  ADD PRIMARY KEY (`id_ta`),
  ADD UNIQUE KEY `id_mahasiswa_2` (`id_mahasiswa`),
  ADD KEY `id_mahasiswa` (`id_mahasiswa`);

--
-- Indexes for table `ujian`
--
ALTER TABLE `ujian`
  ADD PRIMARY KEY (`id_ujian`),
  ADD KEY `id_mahasiswa` (`id_mahasiswa`);

--
-- Indexes for table `verifikasi_dokumen`
--
ALTER TABLE `verifikasi_dokumen`
  ADD KEY `fk_verifikasi_mahasiswa` (`id_mahasiswa`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dosen_pembimbing`
--
ALTER TABLE `dosen_pembimbing`
  MODIFY `id_dosen` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id_mahasiswa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notif`
--
ALTER TABLE `notif`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tugas_akhir`
--
ALTER TABLE `tugas_akhir`
  MODIFY `id_ta` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `mahasiswa_dosen`
--
ALTER TABLE `mahasiswa_dosen`
  ADD CONSTRAINT `mahasiswa_dosen_ibfk_1` FOREIGN KEY (`id_mahasiswa`) REFERENCES `mahasiswa` (`id_mahasiswa`) ON DELETE CASCADE,
  ADD CONSTRAINT `mahasiswa_dosen_ibfk_2` FOREIGN KEY (`id_dosen`) REFERENCES `dosen_pembimbing` (`id_dosen`);

--
-- Constraints for table `notif`
--
ALTER TABLE `notif`
  ADD CONSTRAINT `fk_notif_admin` FOREIGN KEY (`admin`) REFERENCES `admin` (`id_admin`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `notif_ibfk_1` FOREIGN KEY (`id_mahasiswa`) REFERENCES `mahasiswa` (`id_mahasiswa`) ON DELETE CASCADE,
  ADD CONSTRAINT `notif_ibfk_2` FOREIGN KEY (`id_dosen`) REFERENCES `dosen_pembimbing` (`id_dosen`) ON DELETE CASCADE;

--
-- Constraints for table `seminar_proposal`
--
ALTER TABLE `seminar_proposal`
  ADD CONSTRAINT `seminar_proposal_ibfk_1` FOREIGN KEY (`id_mahasiswa`) REFERENCES `mahasiswa` (`id_mahasiswa`) ON DELETE CASCADE;

--
-- Constraints for table `tugas_akhir`
--
ALTER TABLE `tugas_akhir`
  ADD CONSTRAINT `fk_id_mahasiswa` FOREIGN KEY (`id_mahasiswa`) REFERENCES `mahasiswa` (`id_mahasiswa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ujian`
--
ALTER TABLE `ujian`
  ADD CONSTRAINT `ujian_ibfk_1` FOREIGN KEY (`id_mahasiswa`) REFERENCES `mahasiswa` (`id_mahasiswa`) ON DELETE CASCADE;

--
-- Constraints for table `verifikasi_dokumen`
--
ALTER TABLE `verifikasi_dokumen`
  ADD CONSTRAINT `fk_verifikasi_mahasiswa` FOREIGN KEY (`id_mahasiswa`) REFERENCES `mahasiswa` (`id_mahasiswa`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
