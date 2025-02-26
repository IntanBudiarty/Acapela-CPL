-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2024 at 11:33 AM
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
-- Database: `cpl_ppsi`
--

-- --------------------------------------------------------

--
-- Table structure for table `bobotcpls`
--

CREATE TABLE `bobotcpls` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tahun_ajaran_id` bigint(20) UNSIGNED NOT NULL,
  `mata_kuliah_id` bigint(20) UNSIGNED NOT NULL,
  `cpl_id` bigint(20) UNSIGNED NOT NULL,
  `cpmk_id` bigint(20) UNSIGNED NOT NULL,
  `btp_id` bigint(20) UNSIGNED NOT NULL,
  `semester` enum('1','2') NOT NULL,
  `bobot_cpl` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bobotcpls`
--

INSERT INTO `bobotcpls` (`id`, `tahun_ajaran_id`, `mata_kuliah_id`, `cpl_id`, `cpmk_id`, `btp_id`, `semester`, `bobot_cpl`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 1, '1', 25.00, '2024-11-21 10:15:32', '2024-11-21 10:15:32');

-- --------------------------------------------------------

--
-- Table structure for table `btps`
--

CREATE TABLE `btps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tahun_ajaran_id` bigint(20) UNSIGNED NOT NULL,
  `mata_kuliah_id` bigint(20) UNSIGNED NOT NULL,
  `cpmk_id` bigint(20) UNSIGNED NOT NULL,
  `dosen_admin_id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `semester` enum('1','2') NOT NULL,
  `kategori` int(11) NOT NULL,
  `bobot` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `btps`
--

INSERT INTO `btps` (`id`, `tahun_ajaran_id`, `mata_kuliah_id`, `cpmk_id`, `dosen_admin_id`, `nama`, `semester`, `kategori`, `bobot`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 2, 'Tak tau pun', '1', 1, 25.00, '2024-11-21 09:12:40', '2024-11-21 10:14:56'),
(2, 1, 1, 1, 2, 'Tak tau pun', '1', 2, 25.00, '2024-11-21 10:14:16', '2024-11-21 10:14:16'),
(3, 1, 1, 1, 2, 'Tak tau pun', '1', 3, 50.00, '2024-11-21 10:14:44', '2024-11-21 10:14:44');

-- --------------------------------------------------------

--
-- Table structure for table `cpls`
--

CREATE TABLE `cpls` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_cpl` varchar(255) NOT NULL,
  `nama_cpl` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cpls`
--

INSERT INTO `cpls` (`id`, `kode_cpl`, `nama_cpl`, `created_at`, `updated_at`) VALUES
(1, 'CPL-01', 'Mampu memahami, menganalisis, dan menilai konsep dasar dan peran sistem informasi dalam mengelola data dan memberikan rekomendasi pengambilan keputusan pada proses dan sistem organisasi.', '2024-11-19 09:26:33', '2024-11-19 09:26:33');

-- --------------------------------------------------------

--
-- Table structure for table `cpmks`
--

CREATE TABLE `cpmks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mata_kuliah_id` bigint(20) UNSIGNED NOT NULL,
  `kode_cpmk` varchar(255) NOT NULL,
  `nama_cpmk` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cpmks`
--

INSERT INTO `cpmks` (`id`, `mata_kuliah_id`, `kode_cpmk`, `nama_cpmk`, `created_at`, `updated_at`) VALUES
(1, 1, 'CPMK01', 'Mampu memahami konsep dasar sistem informasi', '2024-11-19 09:40:08', '2024-11-19 09:40:08'),
(2, 1, 'CPMK02', 'Mampu menganalisis proses dan sistem organisasi', '2024-11-19 10:51:31', '2024-11-19 10:51:31');

-- --------------------------------------------------------

--
-- Table structure for table `detail_cpls`
--

CREATE TABLE `detail_cpls` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cpl_id` bigint(20) UNSIGNED NOT NULL,
  `cpmk_id` bigint(20) UNSIGNED NOT NULL,
  `nama_cpmk` varchar(255) NOT NULL,
  `mata_kuliah_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `detail_cpls`
--

INSERT INTO `detail_cpls` (`id`, `cpl_id`, `cpmk_id`, `nama_cpmk`, `mata_kuliah_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '1', 1, '2024-11-19 09:40:33', '2024-11-19 09:40:33'),
(4, 1, 2, '', 2, '2024-11-19 21:38:16', '2024-11-19 21:38:16');

-- --------------------------------------------------------

--
-- Table structure for table `dosen`
--

CREATE TABLE `dosen` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `nip` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dosen_admins`
--

CREATE TABLE `dosen_admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nip` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dosen_admins`
--

INSERT INTO `dosen_admins` (`id`, `user_id`, `nip`, `nama`, `created_at`, `updated_at`) VALUES
(1, 1, '198701272012122001', 'Endina Putri Purwandari, S.T., M.Kom.', '2021-10-03 00:43:45', '2024-11-19 09:32:40'),
(2, 2, '198112222008011011', 'Aan Erlansari, S.T., M.Eng.', '2021-10-03 00:43:45', '2024-11-19 09:30:16'),
(3, 3, '198906232018031001', 'Ferzha Putra Utama, S.T., M.Eng.', '2024-11-19 09:31:08', '2024-11-19 09:31:08'),
(4, 4, '199201312019031010', 'Andang Wijanarko, S.Kom., M.Kom.', '2024-11-19 09:31:35', '2024-11-19 09:31:35'),
(5, 5, '199411232020122021', 'Nurul Renaningtias, S.T., M.Kom.', '2024-11-19 09:32:03', '2024-11-19 09:32:03');

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
-- Table structure for table `kcpls`
--

CREATE TABLE `kcpls` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tahun_ajaran_id` bigint(20) UNSIGNED NOT NULL,
  `mahasiswa_id` bigint(20) UNSIGNED NOT NULL,
  `mata_kuliah_id` bigint(20) UNSIGNED NOT NULL,
  `bobotcpl_id` bigint(20) UNSIGNED NOT NULL,
  `cpl_id` bigint(20) UNSIGNED NOT NULL,
  `kode_cpl` varchar(255) NOT NULL,
  `semester` enum('1','2') NOT NULL,
  `nilai_cpl` double(8,2) DEFAULT 0.00,
  `bobot_cpl` double(8,2) DEFAULT 0.00,
  `urutan` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kcpmks`
--

CREATE TABLE `kcpmks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tahun_ajaran_id` bigint(20) UNSIGNED NOT NULL,
  `btp_id` bigint(20) UNSIGNED NOT NULL,
  `mahasiswa_id` bigint(20) UNSIGNED NOT NULL,
  `mata_kuliah_id` bigint(20) UNSIGNED NOT NULL,
  `cpmk_id` bigint(20) UNSIGNED NOT NULL,
  `kode_cpmk` varchar(255) NOT NULL,
  `semester` enum('1','2') NOT NULL,
  `nilai_kcpmk` double(8,2) DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `k_r_s`
--

CREATE TABLE `k_r_s` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mahasiswa_id` bigint(20) UNSIGNED NOT NULL,
  `tahun_ajaran_id` bigint(20) UNSIGNED NOT NULL,
  `mata_kuliah_id` bigint(20) UNSIGNED NOT NULL,
  `semester` enum('1','2') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `k_r_s`
--

INSERT INTO `k_r_s` (`id`, `mahasiswa_id`, `tahun_ajaran_id`, `mata_kuliah_id`, `semester`, `created_at`, `updated_at`) VALUES
(4, 1, 1, 2, '1', '2024-11-21 10:06:40', '2024-11-21 10:06:40');

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswas`
--

CREATE TABLE `mahasiswas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nim` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `angkatan` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mahasiswas`
--

INSERT INTO `mahasiswas` (`id`, `nim`, `nama`, `angkatan`, `created_at`, `updated_at`) VALUES
(1, 'G1F022048', 'Intan Budiarty', '2022', '2024-11-19 09:37:40', '2024-11-19 09:37:40'),
(2, 'G1F022066', 'Nabila Wijaya', '2022', '2024-11-19 09:37:40', '2024-11-19 09:37:40');

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa_mata_kuliah`
--

CREATE TABLE `mahasiswa_mata_kuliah` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mahasiswa_id` bigint(20) UNSIGNED NOT NULL,
  `mata_kuliah_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mahasiswa_mata_kuliah`
--

INSERT INTO `mahasiswa_mata_kuliah` (`id`, `mahasiswa_id`, `mata_kuliah_id`, `created_at`, `updated_at`) VALUES
(1, 1, 2, NULL, NULL),
(2, 1, 1, NULL, NULL),
(3, 2, 1, NULL, NULL),
(4, 2, 2, NULL, NULL),
(5, 1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mata_kuliahs`
--

CREATE TABLE `mata_kuliahs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `kelas` varchar(255) DEFAULT NULL,
  `sks` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `dosen_pengampu_1` varchar(255) NOT NULL,
  `dosen_pengampu_2` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mata_kuliahs`
--

INSERT INTO `mata_kuliahs` (`id`, `kode`, `nama`, `kelas`, `sks`, `semester`, `dosen_pengampu_1`, `dosen_pengampu_2`, `created_at`, `updated_at`) VALUES
(1, 'MK01', 'Konsep Sistem Informasi', 'A', 2, 2, '2', '3', '2024-11-19 09:34:45', '2024-11-19 09:34:45'),
(2, 'MK02', 'Pengantar Teknologi Informasi', 'A', 2, 1, '2', '5', '2024-11-19 09:35:30', '2024-11-19 09:35:30');

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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2021_09_15_145733_create_permission_tables', 1),
(6, '2021_09_16_134442_create_dosen_admins_table', 1),
(7, '2021_09_18_053329_create_mahasiswas_table', 1),
(8, '2021_09_18_064245_create_tahun_ajarans_table', 1),
(9, '2021_09_18_092200_create_mata_kuliahs_table', 1),
(10, '2021_09_18_130328_create_k_r_s_table', 1),
(11, '2021_09_19_101106_create_cpls_table', 1),
(12, '2021_09_19_121810_create_cpmks_table', 1),
(13, '2021_09_19_192006_create_btps_table', 1),
(14, '2021_09_22_100017_create_bobotcpls_table', 1),
(15, '2021_09_23_080102_create_nilais_table', 1),
(16, '2021_09_30_075503_create_kcpmks_table', 1),
(17, '2021_10_04_080823_create_kcpls_table', 1),
(18, '2021_10_09_090018_create_rolesmks_table', 1),
(19, '2024_11_09_141341_add_nip_to_users_table', 1),
(20, '2024_11_09_150332_add_dosen_pengampu_to_mata_kuliah_table', 1),
(21, '2024_11_09_160316_create_dosen_table', 1),
(22, '2024_11_11_191105_create_mahasiswa_mata_kuliah_table', 1),
(23, '2024_11_14_182609_create_detail_cpls_table', 1),
(24, '2024_11_19_172459_add_cpl_id_to_detail_cpls_table', 2);

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
(2, 'App\\Models\\User', 3),
(2, 'App\\Models\\User', 4),
(2, 'App\\Models\\User', 5);

-- --------------------------------------------------------

--
-- Table structure for table `nilais`
--

CREATE TABLE `nilais` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mahasiswa_id` bigint(20) UNSIGNED NOT NULL,
  `btp_id` bigint(20) UNSIGNED NOT NULL,
  `nilai` double(8,2) DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
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

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'admin', 'web', '2024-11-19 09:21:50', '2024-11-19 09:21:50'),
(2, 'dosen', 'web', '2024-11-19 09:21:50', '2024-11-19 09:21:50');

-- --------------------------------------------------------

--
-- Table structure for table `rolesmks`
--

CREATE TABLE `rolesmks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tahun_ajaran_id` bigint(20) UNSIGNED NOT NULL,
  `mata_kuliah_id` bigint(20) UNSIGNED NOT NULL,
  `dosen_admin_id` bigint(20) UNSIGNED NOT NULL,
  `semester` enum('1','2') NOT NULL,
  `status` enum('koordinator','pengampu') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rolesmks`
--

INSERT INTO `rolesmks` (`id`, `tahun_ajaran_id`, `mata_kuliah_id`, `dosen_admin_id`, `semester`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, '1', 'koordinator', '2024-11-19 09:36:02', '2024-11-19 09:36:02');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tahun_ajarans`
--

CREATE TABLE `tahun_ajarans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tahun` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tahun_ajarans`
--

INSERT INTO `tahun_ajarans` (`id`, `tahun`, `created_at`, `updated_at`) VALUES
(1, '2017/2018', '2024-11-19 09:33:13', '2024-11-19 09:33:13'),
(2, '2018/2019', '2024-11-19 09:33:22', '2024-11-19 09:33:22'),
(3, '2020/2021', '2024-11-19 09:33:28', '2024-11-19 09:33:28'),
(4, '2021/2022', '2024-11-19 09:33:32', '2024-11-19 09:33:32'),
(5, '2022/2023', '2024-11-19 09:33:45', '2024-11-19 09:33:45'),
(6, '2023/2024', '2024-11-19 09:33:52', '2024-11-19 09:33:52'),
(7, '2024/2025', '2024-11-19 09:33:57', '2024-11-19 09:33:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `nip` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `status`, `remember_token`, `created_at`, `updated_at`, `nip`) VALUES
(1, 'admin', '$2y$10$cXORYCg.1bVWRvsI//KkPuO2SzyOiOnps84AtqVjyO6F1fQJKoTU6', 'Admin', NULL, '2024-11-19 09:21:50', '2024-11-19 09:32:40', NULL),
(2, 'Aan Erlansari', '$2y$10$lSgvmbpne7mUWFRorehYVOs4SOfG4Jz5QhEhUefwuoM4eb0G8lGny', 'Dosen', NULL, '2024-11-19 09:21:51', '2024-11-21 09:16:09', '198112222008011011'),
(3, 'dosen1', '$2y$10$bbwc7uuB59i6ssiANOLXzunJZD0VdMnr.4UNO0PckfngBxkV4Dyk6', 'Dosen', NULL, '2024-11-19 09:31:08', '2024-11-19 09:31:08', NULL),
(4, 'dosen2', '$2y$10$2ruGMJU1BbR4Vf80molwcuDYMNABVR1iv2Ehf19kHcDRMpQiUw1kS', 'Dosen', NULL, '2024-11-19 09:31:35', '2024-11-19 09:31:35', NULL),
(5, 'dosen3', '$2y$10$HZarcKf.ahVNhTpOMMFKAevA.3KPt2rX.1QpaWvQKqCUlmFg9bi5m', 'Dosen', NULL, '2024-11-19 09:32:03', '2024-11-19 09:32:03', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bobotcpls`
--
ALTER TABLE `bobotcpls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `btps`
--
ALTER TABLE `btps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cpls`
--
ALTER TABLE `cpls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cpmks`
--
ALTER TABLE `cpmks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `detail_cpls`
--
ALTER TABLE `detail_cpls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detail_cpls_cpmk_id_foreign` (`cpmk_id`),
  ADD KEY `detail_cpls_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  ADD KEY `detail_cpls_cpl_id_foreign` (`cpl_id`);

--
-- Indexes for table `dosen`
--
ALTER TABLE `dosen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dosen_nip_unique` (`nip`);

--
-- Indexes for table `dosen_admins`
--
ALTER TABLE `dosen_admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `kcpls`
--
ALTER TABLE `kcpls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kcpmks`
--
ALTER TABLE `kcpmks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `k_r_s`
--
ALTER TABLE `k_r_s`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mahasiswas`
--
ALTER TABLE `mahasiswas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mahasiswa_mata_kuliah`
--
ALTER TABLE `mahasiswa_mata_kuliah`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mahasiswa_mata_kuliah_mahasiswa_id_foreign` (`mahasiswa_id`),
  ADD KEY `mahasiswa_mata_kuliah_mata_kuliah_id_foreign` (`mata_kuliah_id`);

--
-- Indexes for table `mata_kuliahs`
--
ALTER TABLE `mata_kuliahs`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `nilais`
--
ALTER TABLE `nilais`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `rolesmks`
--
ALTER TABLE `rolesmks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `tahun_ajarans`
--
ALTER TABLE `tahun_ajarans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_nip_unique` (`nip`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bobotcpls`
--
ALTER TABLE `bobotcpls`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `btps`
--
ALTER TABLE `btps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cpls`
--
ALTER TABLE `cpls`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cpmks`
--
ALTER TABLE `cpmks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `detail_cpls`
--
ALTER TABLE `detail_cpls`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `dosen`
--
ALTER TABLE `dosen`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dosen_admins`
--
ALTER TABLE `dosen_admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kcpls`
--
ALTER TABLE `kcpls`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kcpmks`
--
ALTER TABLE `kcpmks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `k_r_s`
--
ALTER TABLE `k_r_s`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `mahasiswas`
--
ALTER TABLE `mahasiswas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `mahasiswa_mata_kuliah`
--
ALTER TABLE `mahasiswa_mata_kuliah`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `mata_kuliahs`
--
ALTER TABLE `mata_kuliahs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `nilais`
--
ALTER TABLE `nilais`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rolesmks`
--
ALTER TABLE `rolesmks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tahun_ajarans`
--
ALTER TABLE `tahun_ajarans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_cpls`
--
ALTER TABLE `detail_cpls`
  ADD CONSTRAINT `detail_cpls_cpl_id_foreign` FOREIGN KEY (`cpl_id`) REFERENCES `cpls` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_cpls_cpmk_id_foreign` FOREIGN KEY (`cpmk_id`) REFERENCES `cpmks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_cpls_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliahs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mahasiswa_mata_kuliah`
--
ALTER TABLE `mahasiswa_mata_kuliah`
  ADD CONSTRAINT `mahasiswa_mata_kuliah_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mahasiswa_mata_kuliah_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliahs` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
