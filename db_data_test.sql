-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 24, 2022 at 03:17 AM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 8.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_data_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `barangs`
--

CREATE TABLE `barangs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` char(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Kode Barang',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama Barang',
  `price` decimal(8,2) NOT NULL DEFAULT 0.00 COMMENT 'Harga Barang',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `barangs`
--

INSERT INTO `barangs` (`id`, `code`, `name`, `price`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'A001', 'Barang A', '200000.00', NULL, '2022-08-23 09:43:13', '2022-08-23 09:44:05'),
(2, 'C025', 'Barang B', '350000.00', NULL, '2022-08-23 09:43:52', '2022-08-23 09:43:52'),
(3, 'A102', 'Barang C', '125000.00', NULL, '2022-08-23 09:44:47', '2022-08-23 09:44:47'),
(4, 'A301', 'Barang D', '300000.00', NULL, '2022-08-23 09:45:03', '2022-08-23 09:45:03'),
(5, 'B221', 'Barang E', '300000.00', NULL, '2022-08-23 09:45:18', '2022-08-23 09:45:18');

-- --------------------------------------------------------

--
-- Table structure for table `m_customers`
--

CREATE TABLE `m_customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` char(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Kode Pelanggan',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama Pelanggan',
  `phone` bigint(20) NOT NULL COMMENT 'Nomor Telephone Pelanggan',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_customers`
--

INSERT INTO `m_customers` (`id`, `code`, `name`, `phone`, `created_at`, `updated_at`) VALUES
(1, 'C001', 'Muhammad Satrio Nugroho', 81273536358, '2022-08-23 09:46:39', '2022-08-23 09:47:29'),
(2, 'C002', 'Cust B', 81273536359, '2022-08-23 09:47:13', '2022-08-23 09:47:13'),
(3, 'C003', 'Cust A', 81273536351, '2022-08-23 09:47:49', '2022-08-23 09:47:49'),
(4, 'C004', 'Cust C', 81273536352, '2022-08-23 09:48:00', '2022-08-23 09:48:00'),
(5, 'C005', 'Cust D', 81273536353, '2022-08-23 09:48:14', '2022-08-23 09:48:14');

-- --------------------------------------------------------

--
-- Table structure for table `t_sales`
--

CREATE TABLE `t_sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` char(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Kode Penjualan',
  `date_of_sale` timestamp NULL DEFAULT NULL COMMENT 'Tanggal Penjualan',
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `sub_total` decimal(50,2) NOT NULL DEFAULT 0.00 COMMENT 'Sub Total',
  `discount` decimal(20,2) NOT NULL DEFAULT 0.00 COMMENT 'Diskon',
  `shipping` decimal(8,2) NOT NULL DEFAULT 0.00 COMMENT 'Ongkir',
  `grand_total` decimal(50,2) NOT NULL DEFAULT 0.00 COMMENT 'Total Bayar',
  `status` enum('0','1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `reason` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_sales`
--

INSERT INTO `t_sales` (`id`, `code`, `date_of_sale`, `customer_id`, `sub_total`, `discount`, `shipping`, `grand_total`, `status`, `reason`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, '20220823-001', '2022-08-23 17:35:00', 1, '4400000.00', '1000.00', '20000.00', '4419000.00', '0', NULL, '2022-08-23 11:07:43', '2022-08-23 11:07:43', NULL),
(3, '20220823-002', '2022-08-23 18:07:00', 3, '2250000.00', '1000.00', '20000.00', '2269000.00', '1', 'adadada', '2022-08-23 11:08:45', '2022-08-23 12:36:01', NULL),
(4, '20220823-003', '2022-08-25 05:30:00', 2, '2000000.00', '1000000.00', '100000.00', '1100000.00', '0', NULL, '2022-08-23 12:40:45', '2022-08-23 12:40:45', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_sales_det`
--

CREATE TABLE `t_sales_det` (
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 9 COMMENT 'Jumlah Barang',
  `price_before` decimal(20,2) NOT NULL DEFAULT 0.00,
  `discount_pcs` decimal(30,2) NOT NULL DEFAULT 0.00 COMMENT 'Diskon per barang',
  `grand_total` decimal(30,2) NOT NULL DEFAULT 0.00 COMMENT 'Total Harga',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_sales_det`
--

INSERT INTO `t_sales_det` (`sale_id`, `product_id`, `qty`, `price_before`, `discount_pcs`, `grand_total`, `created_at`, `updated_at`) VALUES
(2, 1, 10, '200000.00', '20.00', '1600000.00', NULL, NULL),
(2, 2, 10, '350000.00', '20.00', '2800000.00', NULL, NULL),
(3, 3, 20, '125000.00', '10.00', '2250000.00', NULL, NULL),
(4, 3, 20, '125000.00', '20.00', '2000000.00', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barangs`
--
ALTER TABLE `barangs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `barangs_code_unique` (`code`);

--
-- Indexes for table `m_customers`
--
ALTER TABLE `m_customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `m_customers_code_unique` (`code`),
  ADD UNIQUE KEY `m_customers_phone_unique` (`phone`);

--
-- Indexes for table `t_sales`
--
ALTER TABLE `t_sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `t_sales_code_unique` (`code`),
  ADD KEY `t_sales_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `t_sales_det`
--
ALTER TABLE `t_sales_det`
  ADD PRIMARY KEY (`sale_id`,`product_id`),
  ADD KEY `t_sales_det_product_id_foreign` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barangs`
--
ALTER TABLE `barangs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `m_customers`
--
ALTER TABLE `m_customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `t_sales`
--
ALTER TABLE `t_sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `t_sales`
--
ALTER TABLE `t_sales`
  ADD CONSTRAINT `t_sales_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `m_customers` (`id`);

--
-- Constraints for table `t_sales_det`
--
ALTER TABLE `t_sales_det`
  ADD CONSTRAINT `t_sales_det_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `barangs` (`id`),
  ADD CONSTRAINT `t_sales_det_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `t_sales` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
