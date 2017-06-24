-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 16, 2017 at 09:35 AM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sidikjari`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `nip` int(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`nip`, `username`, `password`) VALUES
(121402105, 'anna', 'anna');

-- --------------------------------------------------------

--
-- Table structure for table `data_anggota`
--

CREATE TABLE `data_anggota` (
  `id_user` int(100) NOT NULL,
  `nim` int(9) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `jk` enum('Laki-laki','Perempuan') NOT NULL,
  `alamat` varchar(200) NOT NULL,
  `nope` int(13) NOT NULL,
  `data_sidik` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `data_anggota`
--

INSERT INTO `data_anggota` (`id_user`, `nim`, `nama`, `jk`, `alamat`, `nope`, `data_sidik`) VALUES
(2, 12312, 'berhasil dua', 'Laki-laki', 'sdsd', 23234, 'hgjhghgkjhgkjhgkjhgjhgnmvmnhfjhdhreksDFGHJXZBVCMNGAFSKUDFASmdfmHagkyzgfdkuyasdjgasjdytkueyfdemjghDFlasdpadyasfdjAFSMDGFWKUTDFGMASDVNBAjhasgdjsadfnsda'),
(4, 121402021, 'Mayya', 'Laki-laki', 'Medan', 876, 'jdjdjdjdjdjdjdjdjdjdjdjdjdjjdjdjdjdjdjdjdjdjdjdjdjdjdjjdjdjdjdjdjdjdjdjdjdjdjdjdjjdjdjdjdjdjdjdjdjdjdjdjdjdjjdjdjdjdjdjdjdjdjdjdjdjdjdjjdjdjdjdjdjdjdjdjdjdjdjdjdjjdjdjdjdjdjdjdjdjdjdjdjdjdjjdjdjdjdjdjdjdj');

-- --------------------------------------------------------

--
-- Table structure for table `log_anggota`
--

CREATE TABLE `log_anggota` (
  `no` int(253) NOT NULL,
  `id_user` int(100) NOT NULL,
  `id_hari` int(10) NOT NULL,
  `id_sensor` int(2) NOT NULL,
  `tgl` date NOT NULL,
  `jam` time NOT NULL,
  `keterangan` enum('H','A') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `log_anggota`
--

INSERT INTO `log_anggota` (`no`, `id_user`, `id_hari`, `id_sensor`, `tgl`, `jam`, `keterangan`) VALUES
(1, 2, 0, 2, '0000-00-00', '00:00:00', 'A'),
(2, 2, 0, 4, '0000-00-00', '00:00:00', 'A'),
(3, 2, 3, 4, '2017-06-15', '00:00:00', 'A'),
(4, 2, 4, 5, '2017-06-15', '20:08:45', 'A'),
(5, 2, 0, 4, '2017-06-16', '13:53:15', 'A'),
(6, 2, 0, 6, '2017-06-16', '13:54:36', 'A'),
(7, 2, 0, 3, '2017-06-16', '14:02:22', 'A'),
(8, 4, 0, 3, '2017-06-16', '14:14:21', 'H');

--
-- Triggers `log_anggota`
--
DELIMITER $$
CREATE TRIGGER `get_tanggal` BEFORE INSERT ON `log_anggota` FOR EACH ROW BEGIN
	IF NEW.tgl = '0000-00-00' AND NEW.jam = '00:00:00'
    THEN
    	SET NEW.tgl = CURRENT_DATE();
        SET NEW.jam = CURRENT_TIME();
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bulan`
--

CREATE TABLE `tbl_bulan` (
  `id_bln` int(2) NOT NULL,
  `nama_bln` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_bulan`
--

INSERT INTO `tbl_bulan` (`id_bln`, `nama_bln`) VALUES
(1, 'Januari'),
(2, 'Februari'),
(3, 'Maret'),
(4, 'April'),
(5, 'Mei'),
(6, 'Juni'),
(7, 'Juli'),
(8, 'Agustus'),
(9, 'September'),
(10, 'Oktober'),
(11, 'November'),
(12, 'Desember');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hari`
--

CREATE TABLE `tbl_hari` (
  `id_hari` int(10) NOT NULL,
  `nama_hari` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_hari`
--

INSERT INTO `tbl_hari` (`id_hari`, `nama_hari`) VALUES
(1, 'Senin'),
(2, 'Selasa'),
(3, 'Rabu'),
(4, 'Kamis'),
(5, 'Jumat'),
(6, 'Sabtu'),
(7, 'Minggu');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data_anggota`
--
ALTER TABLE `data_anggota`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `log_anggota`
--
ALTER TABLE `log_anggota`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `tbl_bulan`
--
ALTER TABLE `tbl_bulan`
  ADD PRIMARY KEY (`id_bln`);

--
-- Indexes for table `tbl_hari`
--
ALTER TABLE `tbl_hari`
  ADD PRIMARY KEY (`id_hari`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `log_anggota`
--
ALTER TABLE `log_anggota`
  MODIFY `no` int(253) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
