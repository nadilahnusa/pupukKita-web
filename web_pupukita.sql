-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Apr 14, 2026 at 01:05 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `web_pupukita`
--

-- --------------------------------------------------------

--
-- Table structure for table `distribusi`
--

CREATE TABLE `distribusi` (
  `id` int(11) NOT NULL,
  `petani_id` int(11) NOT NULL,
  `pupuk_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `token` varchar(20) NOT NULL,
  `status` enum('pending','completed','expired') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `verified_at` datetime DEFAULT NULL,
  `taken_at` datetime DEFAULT NULL,
  `total_harga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `distribusi`
--

INSERT INTO `distribusi` (`id`, `petani_id`, `pupuk_id`, `jumlah`, `tanggal`, `token`, `status`, `created_at`, `verified_at`, `taken_at`, `total_harga`) VALUES
(1, 1, 1, 40, '2026-03-01', 'ABC123', 'completed', '2026-04-09 09:23:45', NULL, '2026-04-09 09:25:39', 92000),
(2, 1, 2, 30, '2026-03-02', 'DEF456', 'completed', '2026-04-09 09:23:45', NULL, NULL, 67500),
(3, 2, 1, 50, '2026-03-01', 'GHI789', 'pending', '2026-04-09 09:23:45', NULL, NULL, 115000),
(4, 3, 3, 20, '2026-03-03', 'JKL012', 'completed', '2026-04-09 09:23:45', NULL, NULL, 10000),
(5, 1, 1, 10, '2026-04-14', '5FB83B', 'pending', '2026-04-12 01:11:36', NULL, NULL, 23000),
(6, 1, 3, 10, '2026-04-15', '98350D', 'pending', '2026-04-12 01:13:57', NULL, NULL, 5000),
(7, 1, 1, 10, '2026-04-23', '0C7FB3', 'pending', '2026-04-12 01:35:34', NULL, NULL, 23000),
(8, 1, 2, 10, '2026-04-22', '942520', 'pending', '2026-04-14 14:04:07', NULL, NULL, 22500),
(9, 1, 2, 12, '2026-04-28', '7CF020', 'pending', '2026-04-14 14:10:21', NULL, NULL, 27000);

-- --------------------------------------------------------

--
-- Table structure for table `kuota_pupuk`
--

CREATE TABLE `kuota_pupuk` (
  `id` int(11) NOT NULL,
  `petani_id` int(11) NOT NULL,
  `pupuk_id` int(11) NOT NULL,
  `kuota` int(11) NOT NULL,
  `tahun` year(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kuota_pupuk`
--

INSERT INTO `kuota_pupuk` (`id`, `petani_id`, `pupuk_id`, `kuota`, `tahun`) VALUES
(1, 1, 1, 100, '2026'),
(2, 1, 2, 80, '2026'),
(3, 1, 3, 50, '2026'),
(4, 2, 1, 120, '2026'),
(5, 2, 2, 90, '2026'),
(6, 2, 3, 60, '2026'),
(7, 3, 1, 70, '2026'),
(8, 3, 2, 60, '2026'),
(9, 3, 3, 40, '2026');

-- --------------------------------------------------------

--
-- Table structure for table `petani`
--

CREATE TABLE `petani` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `luas_tanah` decimal(5,2) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` enum('Aktif','Nonaktif') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `petani`
--

INSERT INTO `petani` (`id`, `nama`, `nik`, `alamat`, `luas_tanah`, `user_id`, `status`) VALUES
(1, 'Budi', '8888888888888888', 'Ponorogo', 1.50, 3, 'Aktif'),
(2, 'Siti', '2222222222222222', 'Ponorogo', 2.00, 4, 'Aktif'),
(3, 'Andi', '3333333333333333', 'Ponorogo', 1.20, 5, 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `pupuk`
--

CREATE TABLE `pupuk` (
  `id` int(11) NOT NULL,
  `nama_pupuk` varchar(100) DEFAULT NULL,
  `jenis` varchar(50) DEFAULT NULL,
  `harga_kg` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pupuk`
--

INSERT INTO `pupuk` (`id`, `nama_pupuk`, `jenis`, `harga_kg`) VALUES
(1, 'Urea', 'Subsidi', 2300),
(2, 'Phonska', 'Subsidi', 2250),
(3, 'Organik', 'Subsidi', 500);

-- --------------------------------------------------------

--
-- Table structure for table `stok_pupuk`
--

CREATE TABLE `stok_pupuk` (
  `id` int(11) NOT NULL,
  `pupuk_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL
) ;

--
-- Dumping data for table `stok_pupuk`
--

INSERT INTO `stok_pupuk` (`id`, `pupuk_id`, `jumlah`) VALUES
(1, 1, 460),
(2, 2, 400),
(3, 3, 300);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `role` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nik`, `nama`, `password`, `role`) VALUES
(1, '1234567890123456', 'Sukamto', '123', 'super_admin'),
(2, '9999999999999999', 'Subarjo', '123', 'admin_gudang'),
(3, '8888888888888888', 'Budi', '123', 'petani'),
(4, '2222222222222222', 'Siti', '123', 'petani'),
(5, '3333333333333333', 'Andi', '123', 'petani');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `distribusi`
--
ALTER TABLE `distribusi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `petani_id` (`petani_id`),
  ADD KEY `pupuk_id` (`pupuk_id`);

--
-- Indexes for table `kuota_pupuk`
--
ALTER TABLE `kuota_pupuk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `petani_id_2` (`petani_id`,`pupuk_id`,`tahun`),
  ADD KEY `petani_id` (`petani_id`),
  ADD KEY `pupuk_id` (`pupuk_id`);

--
-- Indexes for table `petani`
--
ALTER TABLE `petani`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_petani_user` (`user_id`);

--
-- Indexes for table `pupuk`
--
ALTER TABLE `pupuk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stok_pupuk`
--
ALTER TABLE `stok_pupuk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pupuk_id` (`pupuk_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nik` (`nik`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `distribusi`
--
ALTER TABLE `distribusi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `kuota_pupuk`
--
ALTER TABLE `kuota_pupuk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `petani`
--
ALTER TABLE `petani`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pupuk`
--
ALTER TABLE `pupuk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `stok_pupuk`
--
ALTER TABLE `stok_pupuk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `distribusi`
--
ALTER TABLE `distribusi`
  ADD CONSTRAINT `distribusi_ibfk_1` FOREIGN KEY (`petani_id`) REFERENCES `petani` (`id`),
  ADD CONSTRAINT `distribusi_ibfk_2` FOREIGN KEY (`pupuk_id`) REFERENCES `pupuk` (`id`);

--
-- Constraints for table `kuota_pupuk`
--
ALTER TABLE `kuota_pupuk`
  ADD CONSTRAINT `kuota_pupuk_ibfk_1` FOREIGN KEY (`petani_id`) REFERENCES `petani` (`id`),
  ADD CONSTRAINT `kuota_pupuk_ibfk_2` FOREIGN KEY (`pupuk_id`) REFERENCES `pupuk` (`id`);

--
-- Constraints for table `petani`
--
ALTER TABLE `petani`
  ADD CONSTRAINT `fk_petani_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `stok_pupuk`
--
ALTER TABLE `stok_pupuk`
  ADD CONSTRAINT `stok_pupuk_ibfk_1` FOREIGN KEY (`pupuk_id`) REFERENCES `pupuk` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
