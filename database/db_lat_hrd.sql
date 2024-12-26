-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 26, 2024 at 10:11 AM
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
-- Database: `db_lat_hrd_dummy`
--

-- --------------------------------------------------------

--
-- Table structure for table `cuti`
--

CREATE TABLE `cuti` (
  `id_cuti` varchar(11) NOT NULL,
  `idpeg` varchar(10) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `daritgl` date DEFAULT NULL,
  `sampaitgl` date DEFAULT NULL,
  `lamacuti` int(11) DEFAULT NULL,
  `alasan` varchar(1300) DEFAULT NULL,
  `ditetapkan` varchar(50) NOT NULL,
  `pembuat_surat` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departemen`
--

CREATE TABLE `departemen` (
  `iddep` varchar(4) NOT NULL,
  `departemen` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `izin`
--

CREATE TABLE `izin` (
  `id_izin` varchar(11) NOT NULL,
  `idpeg` varchar(10) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jam` time DEFAULT NULL,
  `alasan` varchar(1300) DEFAULT NULL,
  `ditetapkan` varchar(50) NOT NULL,
  `pembuat_surat` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jabatan`
--

CREATE TABLE `jabatan` (
  `idjab` varchar(4) NOT NULL,
  `jabatan` varchar(50) DEFAULT NULL,
  `iddep` varchar(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `iduser` int(11) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `foto` varchar(55) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `namausaha`
--

CREATE TABLE `namausaha` (
  `idusaha` varchar(5) NOT NULL,
  `nama` varchar(35) DEFAULT NULL,
  `alamat` varchar(150) DEFAULT NULL,
  `notelepon` varchar(14) DEFAULT NULL,
  `fax` varchar(14) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `npwp` varchar(50) DEFAULT NULL,
  `bank` varchar(35) DEFAULT NULL,
  `noaccount` varchar(25) DEFAULT NULL,
  `atasnama` varchar(35) DEFAULT NULL,
  `pimpinan` varchar(35) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE `pegawai` (
  `idpeg` varchar(10) NOT NULL,
  `iddep` varchar(4) DEFAULT NULL,
  `idjab` varchar(4) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `telepon` varchar(15) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `gaji` int(11) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `jkelamin` varchar(10) DEFAULT NULL,
  `skerja` varchar(10) DEFAULT NULL,
  `cuti` int(11) DEFAULT NULL,
  `jenjangpendidikan` varchar(3) DEFAULT NULL,
  `tglkerja` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `foto` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penghargaan`
--

CREATE TABLE `penghargaan` (
  `id_penghargaan` varchar(11) NOT NULL,
  `idpeg` varchar(10) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `alasan` varchar(1300) DEFAULT NULL,
  `ditetapkan` varchar(50) NOT NULL,
  `pembuat_surat` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `peringatan`
--

CREATE TABLE `peringatan` (
  `id_peringatan` varchar(11) NOT NULL,
  `idpeg` varchar(10) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `alasan` varchar(1300) DEFAULT NULL,
  `ditetapkan` varchar(50) NOT NULL,
  `pembuat_surat` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cuti`
--
ALTER TABLE `cuti`
  ADD PRIMARY KEY (`id_cuti`),
  ADD KEY `idpeg` (`idpeg`);

--
-- Indexes for table `departemen`
--
ALTER TABLE `departemen`
  ADD PRIMARY KEY (`iddep`);

--
-- Indexes for table `izin`
--
ALTER TABLE `izin`
  ADD PRIMARY KEY (`id_izin`),
  ADD KEY `idpeg` (`idpeg`);

--
-- Indexes for table `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`idjab`),
  ADD KEY `fk_iddep` (`iddep`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`iduser`);

--
-- Indexes for table `namausaha`
--
ALTER TABLE `namausaha`
  ADD PRIMARY KEY (`idusaha`);

--
-- Indexes for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`idpeg`),
  ADD KEY `iddep` (`iddep`),
  ADD KEY `idjab` (`idjab`);

--
-- Indexes for table `penghargaan`
--
ALTER TABLE `penghargaan`
  ADD PRIMARY KEY (`id_penghargaan`),
  ADD KEY `idpeg` (`idpeg`);

--
-- Indexes for table `peringatan`
--
ALTER TABLE `peringatan`
  ADD PRIMARY KEY (`id_peringatan`),
  ADD KEY `idpeg` (`idpeg`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `iduser` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cuti`
--
ALTER TABLE `cuti`
  ADD CONSTRAINT `cuti_ibfk_1` FOREIGN KEY (`idpeg`) REFERENCES `pegawai` (`idpeg`);

--
-- Constraints for table `izin`
--
ALTER TABLE `izin`
  ADD CONSTRAINT `izin_ibfk_1` FOREIGN KEY (`idpeg`) REFERENCES `pegawai` (`idpeg`);

--
-- Constraints for table `jabatan`
--
ALTER TABLE `jabatan`
  ADD CONSTRAINT `fk_iddep` FOREIGN KEY (`iddep`) REFERENCES `departemen` (`iddep`);

--
-- Constraints for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD CONSTRAINT `pegawai_ibfk_1` FOREIGN KEY (`iddep`) REFERENCES `departemen` (`iddep`),
  ADD CONSTRAINT `pegawai_ibfk_2` FOREIGN KEY (`idjab`) REFERENCES `jabatan` (`idjab`);

--
-- Constraints for table `penghargaan`
--
ALTER TABLE `penghargaan`
  ADD CONSTRAINT `penghargaan_ibfk_1` FOREIGN KEY (`idpeg`) REFERENCES `pegawai` (`idpeg`);

--
-- Constraints for table `peringatan`
--
ALTER TABLE `peringatan`
  ADD CONSTRAINT `peringatan_ibfk_1` FOREIGN KEY (`idpeg`) REFERENCES `pegawai` (`idpeg`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
