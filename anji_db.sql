-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 18, 2025 at 08:15 AM
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
-- Database: `anji_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-pacpoom.a|127.0.0.1', 'i:1;', 1752814003),
('laravel-cache-pacpoom.a|127.0.0.1:timer', 'i:1752814003;', 1752814003),
('laravel-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:30:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:10:\"view roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:12:\"create roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:10:\"edit roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:12:\"delete roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:16:\"view permissions\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:18:\"create permissions\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:16:\"edit permissions\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:18:\"delete permissions\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:10:\"view users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:12:\"manage users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:10:\"view posts\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:12:\"create posts\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:10:\"edit posts\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:12:\"delete posts\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:13:\"publish posts\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:15:\"unpublish posts\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:12:\"manage menus\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:12:\"view vendors\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:14:\"create vendors\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:12:\"edit vendors\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:14:\"delete vendors\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:10:\"view parts\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:12:\"create parts\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:10:\"edit parts\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:12:\"delete parts\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:10:\"edit users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:12:\"delete users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:20:\"create part requests\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:22:\"view all part requests\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:21:\"approve part requests\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:3:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:4:\"user\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:2:\"mg\";s:1:\"c\";s:3:\"web\";}}}', 1752899565);

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
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `route` varchar(255) DEFAULT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `permission_name` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `title`, `icon`, `route`, `parent_id`, `permission_name`, `order`, `created_at`, `updated_at`) VALUES
(1, 'Dashboard', 'dashboard', 'dashboard', NULL, NULL, 1, '2025-07-17 20:09:13', '2025-07-17 20:09:13'),
(2, 'Profile', 'person', 'profile.edit', NULL, NULL, 99, '2025-07-17 20:09:13', '2025-07-17 20:09:13'),
(3, 'Management', 'settings', NULL, NULL, NULL, 2, '2025-07-17 20:09:13', '2025-07-17 20:09:13'),
(4, 'Vendor Management', 'store', NULL, NULL, NULL, 2, '2025-07-17 20:09:13', '2025-07-17 20:09:13'),
(5, 'Vendor Master', 'contacts_product', 'vendors.index', 4, 'view vendors', 1, '2025-07-17 20:09:13', '2025-07-17 20:09:13'),
(6, 'User Management', 'group', 'users.index', 3, 'view users', 1, '2025-07-17 20:09:13', '2025-07-17 20:09:13'),
(7, 'Role Management', 'admin_panel_settings', 'roles.index', 3, 'view roles', 2, '2025-07-17 20:09:13', '2025-07-17 20:09:13'),
(8, 'Permission Management', 'policy', 'permissions.index', 3, 'view permissions', 3, '2025-07-17 20:09:13', '2025-07-17 20:09:13'),
(9, 'Menu Management', 'menu', 'menus.index', 3, 'manage menus', 4, '2025-07-17 20:09:13', '2025-07-17 20:09:13'),
(10, 'Master Data', 'database', NULL, NULL, NULL, 2, '2025-07-17 20:09:13', '2025-07-17 20:09:13'),
(11, 'Part Master', 'category', 'parts.index', 10, 'view parts', 1, '2025-07-17 20:09:13', '2025-07-17 20:09:13'),
(12, 'Operations', 'list_alt', NULL, NULL, NULL, 2, '2025-07-17 20:09:13', '2025-07-17 20:09:13'),
(13, 'Part Request', 'add_shopping_cart', 'part-requests.create', 12, 'create part requests', 1, '2025-07-17 20:09:13', '2025-07-17 20:09:13'),
(14, 'Request List', 'receipt_long', 'part-requests.index', 12, 'view all part requests', 2, '2025-07-17 20:09:13', '2025-07-17 20:09:13');

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_07_17_012241_create_permission_tables', 1),
(5, '2025_07_17_064111_create_menus_table', 1),
(6, '2025_07_17_081158_create_vendors_table', 1),
(7, '2025_07_17_084103_add_attachment_path_to_vendors_table', 1),
(8, '2025_07_17_085007_add_register_and_expire_dates_to_vendors_table', 1),
(9, '2025_07_17_091843_modify_vendor_code_in_vendors_table', 1),
(10, '2025_07_18_023832_create_parts_table', 1),
(11, '2025_07_18_025220_add_model_no_to_parts_table', 2),
(12, '2025_07_18_030204_create_part_requests_table', 3),
(13, '2025_07_18_031330_add_attachment_path_to_part_requests_table', 4),
(14, '2025_07_18_032509_add_delivery_fields_to_part_requests_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(3, 'App\\Models\\User', 3);

-- --------------------------------------------------------

--
-- Table structure for table `parts`
--

CREATE TABLE `parts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `part_number` varchar(255) NOT NULL,
  `part_name_thai` varchar(255) DEFAULT NULL,
  `part_name_eng` varchar(255) DEFAULT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `model_no` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parts`
--

INSERT INTO `parts` (`id`, `part_number`, `part_name_thai`, `part_name_eng`, `unit`, `model_no`, `created_at`, `updated_at`) VALUES
(23, 'FOC00320', 'Bracket ASM', 'Bracket ASM', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(24, 'FOC00292', 'Green Lever', 'Green Lever', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(25, 'FOC00149	', 'CLIP-F/TNK FIL PIPE', 'CLIP-F/TNK FIL PIPE', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(26, 'FOC00331', 'Owner manual', 'Owner manual', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(27, 'FOC00112', 'NAVIGATION ASM-END MDL-N/A', 'NAVIGATION ASM-END MDL-N/A', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(28, 'FOC00317', 'ESS for VS HEV', 'ESS for VS HEV', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(29, 'FOC00192	', 'FILTER ASM-FUEL', 'FILTER ASM-FUEL', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(30, 'FOC00133	', 'Cover of Control ASM-A /TRNS', 'Cover of Control ASM-A /TRNS', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(31, 'FOC00235	', 'Sunroof rail BRKT', 'Sunroof rail BRKT', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(32, 'FOC00192', 'FILTER ASM-FUEL', 'FILTER ASM-FUEL', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(33, 'FOC00332', 'Black cover (GEAR SUB)', 'Black cover (GEAR SUB)', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(34, 'FOC00333', 'Tube Grease Klubersynth LE 44-31', 'Tube Grease Klubersynth LE 44-31', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(35, 'FOC00256	', 'HARNESS FOR MODULE BATT ZSHEV', 'HARNESS FOR MODULE BATT ZSHEV', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(36, 'FOC00149', 'CLIP-F/TNK FIL PIPE', 'CLIP-F/TNK FIL PIPE', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(37, 'FOC00315', 'VIP SUPER CHARGE CARD', 'VIP SUPER CHARGE CARD', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(38, 'FOC00332	', 'Black cover (GEAR SUB)', 'Black cover (GEAR SUB)', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(39, 'FOC00334', 'DISPLAY ASM', 'DISPLAY ASM', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(40, 'FOC00335', 'BRACKET', 'BRACKET', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(41, 'FOC00335	', 'BRACKET', 'BRACKET', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(42, 'FOC00187	', 'CHARGER ASM-ON BOARD', 'CHARGER ASM-ON BOARD', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(43, 'FOC00235', 'Sunroof rail BRKT', 'Sunroof rail BRKT', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(44, 'FOC00279	', 'NAVIGATION ASM-END MDL-N/A', 'NAVIGATION ASM-END MDL-N/A', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(45, 'FOC00187', 'CHARGER ASM-ON BOARD', 'CHARGER ASM-ON BOARD', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(46, 'FOC00313', 'Weight Sensor for Seat 2nd row', 'Weight Sensor for Seat 2nd row', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(47, 'FOC00012', 'สายไฟพร้อมไดโอดพร้อมสายรัด', 'สายไฟพร้อมไดโอดพร้อมสายรัด', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-17 21:44:42'),
(48, 'FOC00274', 'TUBE-EDU BREATH PLUG & PLUG ASM-EDU BREATH', 'TUBE-EDU BREATH PLUG & PLUG ASM-EDU BREATH', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(49, 'FOC00318', 'HOSE ASM-P/B BOOS VAC', 'HOSE ASM-P/B BOOS VAC', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(50, 'FOC00338', 'EGR valve', 'EGR valve', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(51, 'FOC00344', 'FLUID-A/TRNS', 'FLUID-A/TRNS', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(52, 'FOC00331	', 'Owner manual', 'Owner manual', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(53, 'FOC00087	', 'NAVIGATION ASM-SYS END MDL N/A', 'NAVIGATION ASM-SYS END MDL N/A', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(54, 'FOC00318	', 'HOSE ASM-P/B BOOS VAC', 'HOSE ASM-P/B BOOS VAC', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(55, 'FOC00344	', 'FLUID-A/TRNS', 'FLUID-A/TRNS', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(56, 'FOC00353', 'VALVE-C/VLV BODY BALL CHK', 'VALVE-C/VLV BODY BALL CHK', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(57, 'FOC00087', 'NAVIGATION ASM-SYS END MDL N/A', 'NAVIGATION ASM-SYS END MDL N/A', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(58, 'FOC00353	', 'VALVE-C/VLV BODY BALL CHK', 'VALVE-C/VLV BODY BALL CHK', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(59, 'FOC00292	', 'Green Lever', 'Green Lever', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(60, 'FOC00320	', 'Bracket ASM', 'Bracket ASM', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(61, 'FOC00339', 'NAVIGATION ASM-END MDL-N/A (AVN)', 'NAVIGATION ASM-END MDL-N/A (AVN)', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(62, 'FOC00336	', 'SENSOR?TIRE PRESS MOT', 'SENSOR?TIRE PRESS MOT', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(63, 'FOC00337	', 'NUT (FOR SENSOR?TIRE PRESS MOT)', 'NUT (FOR SENSOR?TIRE PRESS MOT)', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00'),
(64, 'FOC00350', 'HOOD MGZS EV - COLOR BLACK', 'HOOD MGZS EV - COLOR BLACK', 'PCS', 'MG', '2025-07-18 04:00:00', '2025-07-18 04:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `part_requests`
--

CREATE TABLE `part_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `part_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `required_date` date NOT NULL,
  `reason` text DEFAULT NULL,
  `attachment_path` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `delivery_date` date DEFAULT NULL,
  `arrival_date` date DEFAULT NULL,
  `delivery_document_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `part_requests`
--

INSERT INTO `part_requests` (`id`, `user_id`, `part_id`, `quantity`, `required_date`, `reason`, `attachment_path`, `status`, `delivery_date`, `arrival_date`, `delivery_document_path`, `created_at`, `updated_at`) VALUES
(5, 1, 32, 10, '2025-07-18', 'Test', NULL, 'pending', NULL, NULL, NULL, '2025-07-17 21:57:46', '2025-07-17 21:57:46'),
(6, 1, 36, 5, '2025-07-18', NULL, NULL, 'delivery', NULL, NULL, NULL, '2025-07-17 21:58:44', '2025-07-17 21:58:55');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view roles', 'web', '2025-07-17 19:47:56', '2025-07-17 19:47:56'),
(2, 'create roles', 'web', '2025-07-17 19:47:56', '2025-07-17 19:47:56'),
(3, 'edit roles', 'web', '2025-07-17 19:47:56', '2025-07-17 19:47:56'),
(4, 'delete roles', 'web', '2025-07-17 19:47:56', '2025-07-17 19:47:56'),
(5, 'view permissions', 'web', '2025-07-17 19:47:56', '2025-07-17 19:47:56'),
(6, 'create permissions', 'web', '2025-07-17 19:47:56', '2025-07-17 19:47:56'),
(7, 'edit permissions', 'web', '2025-07-17 19:47:56', '2025-07-17 19:47:56'),
(8, 'delete permissions', 'web', '2025-07-17 19:47:56', '2025-07-17 19:47:56'),
(9, 'view users', 'web', '2025-07-17 19:47:56', '2025-07-17 19:47:56'),
(10, 'manage users', 'web', '2025-07-17 19:47:56', '2025-07-17 19:47:56'),
(11, 'view posts', 'web', '2025-07-17 19:47:56', '2025-07-17 19:47:56'),
(12, 'create posts', 'web', '2025-07-17 19:47:56', '2025-07-17 19:47:56'),
(13, 'edit posts', 'web', '2025-07-17 19:47:56', '2025-07-17 19:47:56'),
(14, 'delete posts', 'web', '2025-07-17 19:47:56', '2025-07-17 19:47:56'),
(15, 'publish posts', 'web', '2025-07-17 19:47:56', '2025-07-17 19:47:56'),
(16, 'unpublish posts', 'web', '2025-07-17 19:47:56', '2025-07-17 19:47:56'),
(17, 'manage menus', 'web', '2025-07-17 19:47:56', '2025-07-17 19:47:56'),
(18, 'view vendors', 'web', '2025-07-17 19:47:56', '2025-07-17 19:47:56'),
(19, 'create vendors', 'web', '2025-07-17 19:47:56', '2025-07-17 19:47:56'),
(20, 'edit vendors', 'web', '2025-07-17 19:47:56', '2025-07-17 19:47:56'),
(21, 'delete vendors', 'web', '2025-07-17 19:47:56', '2025-07-17 19:47:56'),
(22, 'view parts', 'web', '2025-07-17 19:47:56', '2025-07-17 19:47:56'),
(23, 'create parts', 'web', '2025-07-17 19:47:56', '2025-07-17 19:47:56'),
(24, 'edit parts', 'web', '2025-07-17 19:47:56', '2025-07-17 19:47:56'),
(25, 'delete parts', 'web', '2025-07-17 19:47:56', '2025-07-17 19:47:56'),
(26, 'edit users', 'web', '2025-07-17 20:09:12', '2025-07-17 20:09:12'),
(27, 'delete users', 'web', '2025-07-17 20:09:12', '2025-07-17 20:09:12'),
(28, 'create part requests', 'web', '2025-07-17 20:09:12', '2025-07-17 20:09:12'),
(29, 'view all part requests', 'web', '2025-07-17 20:09:12', '2025-07-17 20:09:12'),
(30, 'approve part requests', 'web', '2025-07-17 20:09:12', '2025-07-17 20:09:12');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', '2025-07-17 19:47:56', '2025-07-17 19:47:56'),
(2, 'user', 'web', '2025-07-17 19:47:56', '2025-07-17 19:47:56'),
(3, 'mg', 'web', '2025-07-17 21:29:02', '2025-07-17 21:29:09');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(9, 2),
(10, 1),
(11, 1),
(11, 2),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(22, 3),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(28, 3),
(29, 1),
(29, 3),
(30, 1);

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

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('e8VPpJedMVhNJPDetljITwb92xJ3sZq7mG065CqV', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoieGJhZXNWQzhYVVJkWnNqMndnM3VZU2ozTmxiR0prN2d5VkJ2TjNNZSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1752819196);

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
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@example.com', NULL, '$2y$12$wmwJ1UbqcfVI4VlyvA.NiuacS9g1xacL/aDkrOgndzQwNQ7iM3QuS', 'IE9cpY5blKSw2pgRzfNilR2oqxg7sObtKJ861piq0fKLuAJDUoDO1gEkIe8K', '2025-07-17 19:47:57', '2025-07-17 19:47:57'),
(3, 'Passaporn.b', 'passaporn.bo@mgthai.com', NULL, '$2y$12$Ua6ZMGoGNOHIXUrQv4OLLerbru7Qk/K/XnrW1ot4Zui4L8EaZzdeS', NULL, '2025-07-17 21:28:45', '2025-07-17 21:28:45');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vendor_code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `attachment_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `register_date` date DEFAULT NULL,
  `expire_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

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
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menus_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `parts`
--
ALTER TABLE `parts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `parts_part_number_unique` (`part_number`);

--
-- Indexes for table `part_requests`
--
ALTER TABLE `part_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `part_requests_user_id_foreign` (`user_id`),
  ADD KEY `part_requests_part_id_foreign` (`part_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vendors_vendor_code_unique` (`vendor_code`);

--
-- AUTO_INCREMENT for dumped tables
--

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
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `parts`
--
ALTER TABLE `parts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `part_requests`
--
ALTER TABLE `part_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `menus_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `part_requests`
--
ALTER TABLE `part_requests`
  ADD CONSTRAINT `part_requests_part_id_foreign` FOREIGN KEY (`part_id`) REFERENCES `parts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `part_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
