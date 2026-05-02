-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2026 at 09:25 AM
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
-- Database: `bengkel_mobil`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_id` bigint(20) UNSIGNED NOT NULL,
  `booking_date` date NOT NULL,
  `booking_time` time NOT NULL,
  `status` enum('pending','confirmed','in_progress','completed','cancelled','paid') NOT NULL,
  `complaint` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `service_id`, `vehicle_id`, `booking_date`, `booking_time`, `status`, `complaint`, `created_at`, `updated_at`) VALUES
(26, 15, 4, 24, '2026-04-29', '17:44:00', 'paid', 'chat nya luntur', '2026-04-29 03:44:23', '2026-04-29 03:46:34'),
(27, 15, 2, 25, '2026-04-29', '17:57:00', 'paid', 'addd', '2026-04-29 03:57:52', '2026-04-29 06:18:35'),
(29, 15, 2, 27, '2026-04-30', '22:44:00', 'cancelled', 'tolong mati lampu', '2026-04-29 08:49:29', '2026-04-29 08:50:14'),
(30, 15, 3, 28, '2026-04-29', '22:53:00', 'paid', 'dfghj', '2026-04-29 08:53:06', '2026-04-29 08:56:06'),
(31, 16, 2, 29, '2026-04-29', '23:20:00', 'paid', 'kdskhdsk', '2026-04-29 09:20:53', '2026-04-29 09:32:13'),
(32, 16, 5, 29, '2026-04-30', '01:03:00', 'paid', 'jgcghgv', '2026-04-29 10:03:58', '2026-04-29 10:08:41'),
(33, 16, 1, 31, '2026-04-30', '01:04:00', 'pending', 'ug ihjoknml\'', '2026-04-29 10:04:49', '2026-04-29 10:04:49');

-- --------------------------------------------------------

--
-- Table structure for table `booking_service`
--

CREATE TABLE `booking_service` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `pesan` text NOT NULL,
  `pengirim` enum('user','admin') NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chats`
--

INSERT INTO `chats` (`id`, `user_id`, `pesan`, `pengirim`, `read_at`, `created_at`, `updated_at`) VALUES
(2, 3, 'coba cek dulu ada paku ga?', 'admin', NULL, '2026-04-11 02:10:45', '2026-04-11 02:10:45'),
(5, 3, 'iyaa', 'user', '2026-04-29 03:21:39', '2026-04-11 02:53:35', '2026-04-29 03:21:39'),
(7, 3, 'lh', 'user', '2026-04-29 03:21:39', '2026-04-11 03:05:33', '2026-04-29 03:21:39'),
(9, 3, 'plis', 'user', '2026-04-29 03:21:39', '2026-04-11 03:17:04', '2026-04-29 03:21:39'),
(11, 3, 'mn', 'user', '2026-04-29 03:21:39', '2026-04-11 03:24:49', '2026-04-29 03:21:39'),
(15, 3, 'lahh\'', 'user', '2026-04-29 03:21:39', '2026-04-11 03:36:10', '2026-04-29 03:21:39'),
(16, 3, 'boop;', 'admin', NULL, '2026-04-11 03:37:45', '2026-04-11 03:37:45'),
(29, 15, 'halloo', 'admin', '2026-04-29 03:48:57', '2026-04-29 03:48:47', '2026-04-29 03:48:57'),
(30, 15, 'iyaap', 'user', '2026-04-29 03:49:18', '2026-04-29 03:49:03', '2026-04-29 03:49:18'),
(31, 15, 'i', 'admin', '2026-04-29 03:50:32', '2026-04-29 03:50:12', '2026-04-29 03:50:32'),
(32, 15, 'oke', 'user', '2026-04-29 03:53:03', '2026-04-29 03:52:53', '2026-04-29 03:53:03');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 1),
(3, '2026_04_01_010354_create_sessions_table', 1),
(4, '2026_04_11_000003_create_users_table', 1),
(5, '2026_04_11_081741_create_vehicles_table', 1),
(6, '2026_04_11_081742_create_services_table', 1),
(7, '2026_04_11_081743_create_bookings_table', 1),
(8, '2026_04_11_081744_create_booking_service_table', 1),
(9, '2026_04_11_081745_create_spareparts_table', 1),
(10, '2026_04_11_081745_create_transactions_table', 1),
(11, '2026_04_11_081747_create_transaction_spareparts_table', 1),
(12, '2026_04_11_081748_create_payments_table', 1),
(13, '9999_12_31_999999_create_chats_table', 1),
(14, '2026_04_11_081746_create_payments_table', 2),
(15, '2026_04_16_090000_update_spareparts_price_columns', 3),
(16, '2026_04_19_000001_add_read_at_to_chats_table', 4),
(17, '2026_04_29_100725_add_otp_fields_to_users_table', 5),
(18, '2026_04_29_000001_create_vehicle_brands_table', 6),
(19, '2026_04_29_000002_create_vehicle_models_table', 6),
(20, '2026_04_29_000003_add_master_vehicle_columns_to_vehicles_table', 7),
(21, '2026_04_29_000004_add_unique_user_plate_to_vehicles_table', 8);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` bigint(20) UNSIGNED NOT NULL,
  `payment_date` date NOT NULL,
  `amount_paid` decimal(12,2) NOT NULL,
  `payment_method` enum('cash','transfer','qris') NOT NULL,
  `payment_status` enum('unpaid','paid','partial') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `transaction_id`, `payment_date`, `amount_paid`, `payment_method`, `payment_status`, `created_at`, `updated_at`) VALUES
(34, 38, '2026-04-29', 445000.00, 'transfer', 'paid', '2026-04-29 03:46:34', '2026-04-29 03:46:34'),
(35, 39, '2026-04-29', 299000.00, 'transfer', 'paid', '2026-04-29 06:18:35', '2026-04-29 06:18:35'),
(36, 40, '2026-04-29', 290000.00, 'qris', 'paid', '2026-04-29 07:08:58', '2026-04-29 07:08:58'),
(37, 41, '2026-04-29', 435000.00, 'cash', 'paid', '2026-04-29 07:50:44', '2026-04-29 07:50:44'),
(38, 43, '2026-04-29', 299000.00, 'transfer', 'paid', '2026-04-29 08:14:02', '2026-04-29 08:14:02'),
(39, 44, '2026-04-29', 210000.00, 'qris', 'paid', '2026-04-29 08:56:06', '2026-04-29 08:56:06'),
(40, 45, '2026-04-29', 745000.00, 'transfer', 'paid', '2026-04-29 09:32:13', '2026-04-29 09:32:13'),
(41, 47, '2026-04-29', 170000.00, 'qris', 'paid', '2026-04-29 10:08:41', '2026-04-29 10:08:41'),
(42, 46, '2026-04-29', 440000.00, 'transfer', 'paid', '2026-04-29 10:17:50', '2026-04-29 10:17:50');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `estimated_time` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `service_name`, `description`, `price`, `estimated_time`, `created_at`, `updated_at`) VALUES
(1, 'Ganti Aki', 'Menggant aki mobil yang sudah lemah atau mati', 300000.00, 26, '2026-04-12 19:36:52', '2026-04-13 05:46:04'),
(2, 'Ganti Kampas Rem', 'Pengecekan dan penggantian komponen rem seperti kampas dan minyak rem', 250000.00, 25, '2026-04-13 04:51:50', '2026-04-13 05:47:13'),
(3, 'Ganti Oli Mesin', 'Mengganti oli dengan yang baru agar tetep mulus dan tidak cepat aus', 165000.00, 20, '2026-04-13 05:40:37', '2026-04-13 05:47:52'),
(4, 'Spooring & Balancing', 'Meluruskan Roda dan Menyeimbangkan ban agar stabil', 245000.00, 45, '2026-04-13 05:43:39', '2026-04-13 05:43:39'),
(5, 'Pengecekan Ringgan', 'Cek kondisi umum mobil', 25000.00, 15, '2026-04-13 06:10:13', '2026-04-13 06:10:13'),
(6, 'Pengecekan Menyeluruh', 'Cek lebih detail', 125000.00, 43, '2026-04-13 06:11:09', '2026-04-13 07:12:08');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `spareparts`
--

CREATE TABLE `spareparts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `brand` varchar(255) NOT NULL,
  `stock` int(11) NOT NULL,
  `harga_beli` decimal(12,2) NOT NULL DEFAULT 0.00,
  `harga_jual` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `spareparts`
--

INSERT INTO `spareparts` (`id`, `name`, `brand`, `stock`, `harga_beli`, `harga_jual`, `created_at`, `updated_at`, `gambar`) VALUES
(1, 'Oli', 'Mobil Super', 4, 40000.00, 45000.00, NULL, '2026-04-29 10:02:07', 'spareparts/1776085492_oli.jpg'),
(2, 'Rem Tromol Mobil', 'Wuling', 2, 35000.00, 49000.00, NULL, '2026-04-29 08:12:47', 'spareparts/1776085458_rem.jpg'),
(3, 'Knalpot', 'PCXT', 3, 195000.00, 200000.00, '2026-04-13 04:18:06', '2026-04-29 10:01:23', 'spareparts/1776079086_knalpot.jpg'),
(4, 'Kampas Rem', 'JAC J7 330', 5, 445000.00, 450000.00, '2026-04-13 04:49:58', '2026-04-29 09:23:31', 'spareparts/1776080998_kampasrem.jpg'),
(6, 'Aki Mobil', 'Aramon', 5, 179000.00, 190000.00, '2026-04-20 03:37:43', '2026-04-29 07:10:52', 'spareparts/1776681463_4.-Amaron-300x300.jpg'),
(7, 'Ban Mobil', 'Dunlop', 8, 125000.00, 145000.00, '2026-04-29 09:59:23', '2026-04-29 10:07:44', 'spareparts/1777481963_ban.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` bigint(20) UNSIGNED NOT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL,
  `comment` text NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `admin_note` text DEFAULT NULL,
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `user_id`, `booking_id`, `transaction_id`, `rating`, `comment`, `status`, `admin_note`, `reviewed_by`, `reviewed_at`, `created_at`, `updated_at`) VALUES
(7, 15, 26, 38, 1, 'jelekkkkkkkk', 'rejected', NULL, 3, '2026-04-29 03:47:49', '2026-04-29 03:47:37', '2026-04-29 03:47:49'),
(8, 16, 31, 45, 5, 'sangat bagusss', 'approved', NULL, 3, '2026-04-29 10:05:37', '2026-04-29 10:03:03', '2026-04-29 10:05:37');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `mekanik_id` bigint(20) UNSIGNED NOT NULL,
  `kasir_id` bigint(20) UNSIGNED NOT NULL,
  `total_service` decimal(12,2) NOT NULL,
  `total_sparepart` decimal(12,2) NOT NULL,
  `grand_total` decimal(12,2) NOT NULL,
  `status` enum('pending','partial','paid') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`items`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `booking_id`, `service_id`, `mekanik_id`, `kasir_id`, `total_service`, `total_sparepart`, `grand_total`, `status`, `payment_method`, `items`, `created_at`, `updated_at`) VALUES
(38, 26, 4, 6, 5, 245000.00, 200000.00, 445000.00, 'paid', NULL, '[{\"sparepart_id\":3,\"sparepart_name\":\"knalpot\",\"harga_beli\":\"200000\",\"jumlah_beli\":1,\"subtotal\":200000}]', '2026-04-29 03:45:48', '2026-04-29 03:46:34'),
(39, 27, 2, 6, 5, 250000.00, 49000.00, 299000.00, 'paid', NULL, '[{\"sparepart_id\":2,\"sparepart_name\":\"Rem Tromol Mobil\",\"harga_beli\":\"49000\",\"jumlah_beli\":1,\"subtotal\":49000}]', '2026-04-29 03:58:48', '2026-04-29 06:18:35'),
(40, 26, 4, 6, 5, 245000.00, 45000.00, 290000.00, 'paid', NULL, '[{\"sparepart_id\":1,\"sparepart_name\":\"Oli\",\"harga_beli\":\"45000\",\"jumlah_beli\":1,\"subtotal\":45000}]', '2026-04-29 07:07:12', '2026-04-29 07:08:58'),
(41, 26, 4, 12, 5, 245000.00, 190000.00, 435000.00, 'paid', NULL, '[{\"sparepart_id\":6,\"sparepart_name\":\"Aki Mobil\",\"harga_beli\":\"190000\",\"jumlah_beli\":1,\"subtotal\":190000}]', '2026-04-29 07:10:52', '2026-04-29 07:50:44'),
(43, 27, 2, 6, 5, 250000.00, 49000.00, 299000.00, 'paid', NULL, '[{\"sparepart_id\":2,\"sparepart_name\":\"Rem Tromol Mobil\",\"harga_beli\":\"49000\",\"jumlah_beli\":1,\"subtotal\":49000}]', '2026-04-29 08:12:47', '2026-04-29 08:14:02'),
(44, 30, 3, 12, 5, 165000.00, 45000.00, 210000.00, 'paid', NULL, '[{\"sparepart_id\":1,\"sparepart_name\":\"Oli\",\"harga_beli\":\"45000\",\"jumlah_beli\":1,\"subtotal\":45000}]', '2026-04-29 08:53:58', '2026-04-29 08:56:06'),
(45, 31, 2, 6, 5, 250000.00, 495000.00, 745000.00, 'paid', NULL, '[{\"sparepart_id\":4,\"sparepart_name\":\"Kampas Rem\",\"harga_beli\":\"450000\",\"jumlah_beli\":1,\"subtotal\":450000},{\"sparepart_id\":1,\"sparepart_name\":\"Oli\",\"harga_beli\":\"45000\",\"jumlah_beli\":1,\"subtotal\":45000}]', '2026-04-29 09:23:31', '2026-04-29 09:32:13'),
(46, 31, 2, 6, 5, 250000.00, 190000.00, 440000.00, 'paid', NULL, '[{\"sparepart_id\":1,\"sparepart_name\":\"Oli\",\"harga_beli\":\"45000\",\"jumlah_beli\":1,\"subtotal\":45000},{\"sparepart_id\":7,\"sparepart_name\":\"Ban Mobil\",\"harga_beli\":\"145000\",\"jumlah_beli\":1,\"subtotal\":145000}]', '2026-04-29 10:02:07', '2026-04-29 10:17:50'),
(47, 32, 5, 6, 5, 25000.00, 145000.00, 170000.00, 'paid', NULL, '[{\"sparepart_id\":7,\"sparepart_name\":\"Ban Mobil\",\"harga_beli\":\"145000\",\"jumlah_beli\":1,\"subtotal\":145000}]', '2026-04-29 10:07:44', '2026-04-29 10:08:41');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_spareparts`
--

CREATE TABLE `transaction_spareparts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` bigint(20) UNSIGNED NOT NULL,
  `sparepart_id` bigint(20) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaction_spareparts`
--

INSERT INTO `transaction_spareparts` (`id`, `transaction_id`, `sparepart_id`, `qty`, `price`, `subtotal`, `created_at`, `updated_at`) VALUES
(84, 38, 3, 1, 200000.00, 200000.00, '2026-04-29 03:45:48', '2026-04-29 03:45:48'),
(85, 39, 2, 1, 49000.00, 49000.00, '2026-04-29 03:58:48', '2026-04-29 03:58:48'),
(86, 40, 1, 1, 45000.00, 45000.00, '2026-04-29 07:07:12', '2026-04-29 07:07:12'),
(87, 41, 6, 1, 190000.00, 190000.00, '2026-04-29 07:10:52', '2026-04-29 07:10:52'),
(88, 43, 2, 1, 49000.00, 49000.00, '2026-04-29 08:12:47', '2026-04-29 08:12:47'),
(89, 44, 1, 1, 45000.00, 45000.00, '2026-04-29 08:53:58', '2026-04-29 08:53:58'),
(90, 45, 4, 1, 450000.00, 450000.00, '2026-04-29 09:23:31', '2026-04-29 09:23:31'),
(91, 45, 1, 1, 45000.00, 45000.00, '2026-04-29 09:23:31', '2026-04-29 09:23:31'),
(92, 46, 1, 1, 45000.00, 45000.00, '2026-04-29 10:02:07', '2026-04-29 10:02:07'),
(93, 46, 7, 1, 145000.00, 145000.00, '2026-04-29 10:02:07', '2026-04-29 10:02:07'),
(94, 47, 7, 1, 145000.00, 145000.00, '2026-04-29 10:07:44', '2026-04-29 10:07:44');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','mekanik','kasir','customer','owner') NOT NULL DEFAULT 'customer',
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `otp_code` varchar(6) DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `phone`, `address`, `photo`, `otp_code`, `otp_expires_at`, `is_verified`, `remember_token`, `created_at`, `updated_at`) VALUES
(3, 'Nurul Admin', 'admin@gmail.com', NULL, '$2y$12$O.EL8woGGYqSTsxxB2Cvme5bm/CIAPh9.u9UH427nswm7bbCrsy7q', 'admin', '08998995332', 'cibinong98', 'profiles/1775897540-22.jpg', NULL, NULL, 0, '7b342y0KfDL9mjJ0iIdoANETqIpQGByLmnGARRpTdRsv8vyLU6S3qvn45YpS', '2026-04-11 01:49:26', '2026-04-12 20:13:46'),
(5, 'sijaaa', 'kasir@gmail.com', NULL, '$2y$12$byxON./07Ez/sBlQUxafUeiByBeSvS45kUNJgN2cBJFSWy9LOB/jK', 'kasir', '045369473', 'bogooor', NULL, NULL, NULL, 0, NULL, '2026-04-12 20:05:15', '2026-04-12 20:05:15'),
(6, 'kampak', 'mekanik@gmail.com', NULL, '$2y$12$tUgPJsAFByFQGffW8U1.Tug87keM4mWg7L363jt9lwTgtAu.YtolK', 'mekanik', '045369473', 'cibinong', 'profiles/1776494940-28.jpg', NULL, NULL, 0, NULL, '2026-04-12 20:05:46', '2026-04-17 23:49:00'),
(7, 'sijjaa', 'sija@gmail.com', NULL, '$2y$12$mHde16laz4pMw.NMDWasP.PQKxyod9VjnZjZPkGonbyNaJyAEczFe', 'owner', '045369473', 'cibinong', NULL, NULL, NULL, 0, NULL, '2026-04-13 07:42:30', '2026-04-13 07:42:30'),
(12, 'SIja1', 'mekanik1@gmail.com', NULL, '$2y$12$Uxhqi5nj5u9WWiWDjsehTO1tEyKS0qOEgNL18F6g36BZOkXd48ZHO', 'mekanik', '08542457909', 'cibinong111', 'profiles/1776652236-27.jpg', NULL, NULL, 0, 'VxbGOJm8MNNzZrgGibkLCycCY717kc4w4ZbTfC3sHFhdyYassSS3kNrKoiZE', '2026-04-19 19:29:01', '2026-04-19 19:30:36'),
(15, 'Nurul Istinafiah', 'nrulistii@gmail.com', NULL, '$2y$12$.VKXWXOKkRcmy2JnkYT5LeFT0x8GKdpt4JkpqKzHhMNrANuY2Fy7q', 'customer', '08998995332', 'Bogor', 'photo_clients/zr7zbLEinegvI6lE9ifBLMtZmvLFhuvoeaBeXu9N.jpg', NULL, NULL, 1, NULL, '2026-04-29 03:39:47', '2026-04-29 09:08:32'),
(16, 'berrull--_--', 'nurulistinafiah0@gmail.com', NULL, '$2y$12$Q1cCGzQ6MlI9hDPfw.Ll1.T5.KzQseWyuXl5F8IvS2Uz5ckimJg3i', 'customer', '08998995332', 'cibinong', 'profiles/jV26skPwZTzE9ICrUUj5McmLmowbRmRMjtm5sjWG.jpg', NULL, NULL, 1, NULL, '2026-04-29 09:19:31', '2026-04-29 10:13:30');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_brand_id` bigint(20) UNSIGNED DEFAULT NULL,
  `vehicle_model_id` bigint(20) UNSIGNED DEFAULT NULL,
  `brand` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `year` year(4) NOT NULL,
  `license_plate` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `user_id`, `vehicle_brand_id`, `vehicle_model_id`, `brand`, `model`, `year`, `license_plate`, `color`, `created_at`, `updated_at`) VALUES
(24, 15, NULL, NULL, 'honda', 'stailo', '2019', 'F 5677 HJ', 'biru', '2026-04-29 03:44:23', '2026-04-29 03:44:23'),
(25, 15, NULL, NULL, 'honda', 'stailo', '2019', 'F 5677 GH', 'biru', '2026-04-29 03:57:52', '2026-04-29 03:57:52'),
(27, 15, 2, 8, 'Honda', 'Civic', '2017', 'F 2314 HJ', 'biru', '2026-04-29 08:49:29', '2026-04-29 08:49:29'),
(28, 15, 3, 13, 'Daihatsu', 'Ayla', '2019', 'F 5790 KL', 'black', '2026-04-29 08:53:06', '2026-04-29 08:53:06'),
(29, 16, 6, 27, 'Nissan', 'Serena', '2019', 'F 5888 KT', 'black', '2026-04-29 09:20:53', '2026-04-29 09:20:53'),
(31, 16, 2, 9, 'Honda', 'HR-V', '2021', 'B 6789 JK', 'black', '2026-04-29 10:04:49', '2026-04-29 10:04:49');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_brands`
--

CREATE TABLE `vehicle_brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicle_brands`
--

INSERT INTO `vehicle_brands` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Toyota', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(2, 'Honda', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(3, 'Daihatsu', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(4, 'Suzuki', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(5, 'Mitsubishi', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(6, 'Nissan', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(7, 'Hyundai', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(8, 'Wuling', '2026-04-29 08:42:12', '2026-04-29 08:42:12');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_models`
--

CREATE TABLE `vehicle_models` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_brand_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicle_models`
--

INSERT INTO `vehicle_models` (`id`, `vehicle_brand_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Avanza', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(2, 1, 'Innova', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(3, 1, 'Fortuner', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(4, 1, 'Yaris', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(5, 1, 'Rush', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(6, 2, 'Brio', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(7, 2, 'Jazz', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(8, 2, 'Civic', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(9, 2, 'HR-V', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(10, 2, 'CR-V', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(11, 3, 'Xenia', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(12, 3, 'Terios', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(13, 3, 'Ayla', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(14, 3, 'Sigra', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(15, 3, 'Gran Max', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(16, 4, 'Ertiga', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(17, 4, 'Carry', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(18, 4, 'Baleno', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(19, 4, 'XL7', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(20, 4, 'Ignis', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(21, 5, 'Xpander', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(22, 5, 'Pajero Sport', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(23, 5, 'Triton', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(24, 5, 'L300', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(25, 6, 'Livina', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(26, 6, 'March', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(27, 6, 'Serena', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(28, 6, 'X-Trail', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(29, 7, 'Stargazer', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(30, 7, 'Creta', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(31, 7, 'Palisade', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(32, 7, 'Santa Fe', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(33, 8, 'Confero', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(34, 8, 'Cortez', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(35, 8, 'Almaz', '2026-04-29 08:42:12', '2026-04-29 08:42:12'),
(36, 8, 'Air EV', '2026-04-29 08:42:12', '2026-04-29 08:42:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookings_user_id_foreign` (`user_id`),
  ADD KEY `bookings_vehicle_id_foreign` (`vehicle_id`);

--
-- Indexes for table `booking_service`
--
ALTER TABLE `booking_service`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_service_booking_id_foreign` (`booking_id`),
  ADD KEY `booking_service_service_id_foreign` (`service_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chats_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_transaction_id_foreign` (`transaction_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `spareparts`
--
ALTER TABLE `spareparts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_id` (`transaction_id`),
  ADD KEY `fk_testimonials_user` (`user_id`),
  ADD KEY `fk_testimonials_booking` (`booking_id`),
  ADD KEY `fk_testimonials_reviewer` (`reviewed_by`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_booking_id_foreign` (`booking_id`),
  ADD KEY `transactions_mekanik_id_foreign` (`mekanik_id`),
  ADD KEY `transactions_kasir_id_foreign` (`kasir_id`),
  ADD KEY `fk_transactions_service` (`service_id`);

--
-- Indexes for table `transaction_spareparts`
--
ALTER TABLE `transaction_spareparts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_spareparts_transaction_id_foreign` (`transaction_id`),
  ADD KEY `transaction_spareparts_sparepart_id_foreign` (`sparepart_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vehicles_user_plate_unique` (`user_id`,`license_plate`),
  ADD KEY `vehicles_vehicle_brand_id_foreign` (`vehicle_brand_id`),
  ADD KEY `vehicles_vehicle_model_id_foreign` (`vehicle_model_id`),
  ADD KEY `vehicles_user_id_license_plate_index` (`user_id`,`license_plate`);

--
-- Indexes for table `vehicle_brands`
--
ALTER TABLE `vehicle_brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vehicle_brands_name_unique` (`name`);

--
-- Indexes for table `vehicle_models`
--
ALTER TABLE `vehicle_models`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vehicle_models_vehicle_brand_id_name_unique` (`vehicle_brand_id`,`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `booking_service`
--
ALTER TABLE `booking_service`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `spareparts`
--
ALTER TABLE `spareparts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `transaction_spareparts`
--
ALTER TABLE `transaction_spareparts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `vehicle_brands`
--
ALTER TABLE `vehicle_brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `vehicle_models`
--
ALTER TABLE `vehicle_models`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`);

--
-- Constraints for table `booking_service`
--
ALTER TABLE `booking_service`
  ADD CONSTRAINT `booking_service_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_service_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `chats_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`);

--
-- Constraints for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD CONSTRAINT `fk_testimonials_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_testimonials_reviewer` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_testimonials_transaction` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_testimonials_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_transactions_service` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`),
  ADD CONSTRAINT `transactions_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`),
  ADD CONSTRAINT `transactions_kasir_id_foreign` FOREIGN KEY (`kasir_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `transactions_mekanik_id_foreign` FOREIGN KEY (`mekanik_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `transaction_spareparts`
--
ALTER TABLE `transaction_spareparts`
  ADD CONSTRAINT `transaction_spareparts_sparepart_id_foreign` FOREIGN KEY (`sparepart_id`) REFERENCES `spareparts` (`id`),
  ADD CONSTRAINT `transaction_spareparts_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `vehicles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `vehicles_vehicle_brand_id_foreign` FOREIGN KEY (`vehicle_brand_id`) REFERENCES `vehicle_brands` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `vehicles_vehicle_model_id_foreign` FOREIGN KEY (`vehicle_model_id`) REFERENCES `vehicle_models` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `vehicle_models`
--
ALTER TABLE `vehicle_models`
  ADD CONSTRAINT `vehicle_models_vehicle_brand_id_foreign` FOREIGN KEY (`vehicle_brand_id`) REFERENCES `vehicle_brands` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
