-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Nov 25, 2026 at 05:14 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_toko`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id_log` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `activity` varchar(20) NOT NULL,
  `module` varchar(50) NOT NULL,
  `record_info` varchar(255) DEFAULT NULL,
  `waktu` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id_log`, `username`, `activity`, `module`, `record_info`, `waktu`) VALUES
(1, 'admin', 'CREATE', 'Produk', 'Menambah: Yougurt', '2026-11-25 23:53:21'),
(2, 'admin', 'LOGOUT', 'Auth', 'User logout', '2026-11-25 23:54:07'),
(3, 'admin', 'LOGOUT', 'Auth', 'User logout', '2026-11-25 23:54:22'),
(4, 'owner', 'LOGOUT', 'Auth', 'User logout', '2026-11-25 23:55:50'),
(5, 'admin', 'LOGIN', 'Auth', 'Login sebagai admin', '2026-11-25 23:55:53'),
(6, 'admin', 'LOGOUT', 'Auth', 'User logout', '2026-11-25 23:56:45'),
(7, 'kasir', 'LOGIN', 'Auth', 'Login sebagai kasir', '2026-11-25 23:56:55'),
(8, 'kasir', 'CREATE', 'Transaksi', 'Transaksi #3, total Rp20000', '2026-11-25 23:57:21'),
(9, 'kasir', 'LOGOUT', 'Auth', 'User logout', '2026-11-25 23:57:56'),
(10, 'owner', 'LOGIN', 'Auth', 'Login sebagai owner', '2026-11-25 23:58:06'),
(11, 'owner', 'LOGOUT', 'Auth', 'User logout', '2026-11-25 23:59:42'),
(12, 'kasir', 'LOGIN', 'Auth', 'Login sebagai kasir', '2026-11-25 23:59:48'),
(13, 'kasir', 'LOGOUT', 'Auth', 'User logout', '2026-11-26 00:00:06'),
(14, 'admin', 'LOGIN', 'Auth', 'Login sebagai admin', '2026-11-26 00:00:12'),
(15, 'admin', 'DELETE', 'Produk', 'Menghapus produk: Mayones', '2026-11-26 00:01:06'),
(16, 'admin', 'LOGOUT', 'Auth', 'User logout', '2026-11-26 00:01:45'),
(17, 'kasir', 'LOGIN', 'Auth', 'Login sebagai kasir', '2026-11-26 00:01:50'),
(18, 'kasir', 'LOGOUT', 'Auth', 'User logout', '2026-11-26 00:04:32');

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id_detail` int NOT NULL,
  `id_transaksi` int NOT NULL,
  `id_produk` int NOT NULL,
  `qty` int NOT NULL,
  `subtotal` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id_detail`, `id_transaksi`, `id_produk`, `qty`, `subtotal`) VALUES
(1, 1, 4, 1, 65000),
(2, 1, 5, 1, 8000),
(3, 2, 3, 1, 6000),
(4, 3, 8, 2, 20000);

-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

CREATE TABLE `karyawan` (
  `id_karyawan` int NOT NULL,
  `nama_karyawan` varchar(100) NOT NULL,
  `jabatan` varchar(50) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`id_karyawan`, `nama_karyawan`, `jabatan`, `no_hp`) VALUES
(1, 'Sari Dewi', 'Kasir', '081234567801'),
(2, 'Andi Wijaya', 'Gudang', '081234567802'),
(3, 'Budi Santoso', 'Admin', '081234567803'),
(4, 'Rina Marlina', 'Owner', '081234567804');

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int NOT NULL,
  `nama_pelanggan` varchar(100) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama_pelanggan`, `no_hp`, `alamat`) VALUES
(1, 'Budi', '081298765432', 'Jl. Merdeka No. 10'),
(2, 'Citra', '081298765433', 'Jl. Sudirman No. 5'),
(3, 'Dedi', '081298765434', 'Jl. Gatot Subroto No. 2');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int NOT NULL,
  `id_transaksi` int NOT NULL,
  `jumlah_bayar` int NOT NULL,
  `metode` varchar(30) DEFAULT 'Tunai',
  `tanggal_bayar` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `harga` int NOT NULL,
  `stok` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `nama_produk`, `kategori`, `harga`, `stok`) VALUES
(3, 'Teh Botol Sosro', 'Minuman', 6000, 79),
(4, 'Beras 5kg', 'Sembako', 65000, 39),
(5, 'Sabun Mandi', 'Kebutuhan Rumah', 8000, 59),
(8, 'Yougurt', 'Minuman', 10000, 28);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_penjualan`
--

CREATE TABLE `transaksi_penjualan` (
  `id_transaksi` int NOT NULL,
  `id_pelanggan` int DEFAULT NULL,
  `id_karyawan` int DEFAULT NULL,
  `tanggal` datetime DEFAULT CURRENT_TIMESTAMP,
  `total` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaksi_penjualan`
--

INSERT INTO `transaksi_penjualan` (`id_transaksi`, `id_pelanggan`, `id_karyawan`, `tanggal`, `total`) VALUES
(1, 1, 2, '2026-11-08 01:31:42', 73000),
(2, 3, 4, '2026-11-08 01:55:45', 6000),
(3, 2, 2, '2026-11-25 23:57:21', 20000);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','kasir','gudang','owner') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `role`) VALUES
(1, 'admin', '$2y$10$dpigrPiQBxtMb3nfb4qZdOWUoQ/sgThoEmXjKkEvWP.sL1X6m34ge', 'admin'),
(2, 'kasir', '$2y$10$XtZDC5MypCX6dfIevYx1CehBUHN0NGfSHVhe7gN8aJ.m9xsp1FuGG', 'kasir'),
(3, 'gudang', '$2y$10$GUxJrgxZwaK1Zz8ThwHLqup68n5ZqigdJHRNmC7UX0TpMOdPNhugi', 'gudang'),
(4, 'owner', '$2y$10$PM.13HMEf1tWvgDjuHsF9ePt.xca9qwDn4QPxhneOmcNPFoDq4XGu', 'owner');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id_log`);

--
-- Indexes for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_transaksi` (`id_transaksi`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`id_karyawan`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_transaksi` (`id_transaksi`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`);

--
-- Indexes for table `transaksi_penjualan`
--
ALTER TABLE `transaksi_penjualan`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_pelanggan` (`id_pelanggan`),
  ADD KEY `id_karyawan` (`id_karyawan`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id_log` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id_detail` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `karyawan`
--
ALTER TABLE `karyawan`
  MODIFY `id_karyawan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `transaksi_penjualan`
--
ALTER TABLE `transaksi_penjualan`
  MODIFY `id_transaksi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi_penjualan` (`id_transaksi`),
  ADD CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`);

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi_penjualan` (`id_transaksi`);

--
-- Constraints for table `transaksi_penjualan`
--
ALTER TABLE `transaksi_penjualan`
  ADD CONSTRAINT `transaksi_penjualan_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`),
  ADD CONSTRAINT `transaksi_penjualan_ibfk_2` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id_karyawan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
