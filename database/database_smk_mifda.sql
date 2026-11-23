-- ========================================================
-- Database SMK MIFDA - Export untuk MySQL / phpMyAdmin
-- Generated: 2026-11-23 13:42:15
-- ========================================================

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
START TRANSACTION;
SET time_zone = '+00:00';

-- --------------------------------------------------------
-- Struktur tabel `migrations`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data untuk tabel `migrations`
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_11_13_000001_create_school_management_tables', 2),
(5, '2026_11_23_000001_create_monthly_attendances_table', 3);

-- --------------------------------------------------------
-- Struktur tabel `users`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'guru',
  `phone` varchar(50) DEFAULT NULL,
  `identifier_number` varchar(50) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data untuk tabel `users`
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `username`, `role`, `phone`, `identifier_number`, `avatar`) VALUES
(1, 'Administrator TU', 'admin@sekolah.sch.id', NULL, '$2y$12$rDj1cjqk.0/couewyBGpb.46hT/8oIKf7Zp4im.mdSiojzouZwb3O', NULL, '2026-11-13 10:20:19', '2026-11-23 12:46:13', 'admin', 'admin', '081234567890', 'ADM-001', NULL),
(2, 'Hj. Ratna Indrayani, S.Pd., M.Psi.', 'bk@sekolah.sch.id', NULL, '$2y$12$JzDEOnEquCCdfrzEEPibJOgdVQHjvq0z6Lnc3LkUhKhHKp7zj6WQ.', NULL, '2026-11-13 10:20:20', '2026-11-23 12:46:14', 'guru_bk', 'bk', '081234567891', '19800720 200604 2 011', NULL),
(3, 'Ahmad Syukron, S.Kom.', 'syukron@sekolah.sch.id', NULL, '$2y$12$rfZXT0FhXF4e7lnQSgB4luoDyw9D.o7MiNXnmPndRfwsazPPJHYsW', NULL, '2026-11-13 10:20:20', '2026-11-23 12:46:14', 'wali_kelas', 'guru', '081234567892', '19850912 201001 1 014', NULL),
(4, 'Dewi Lestari, S.Pd.', 'dewi@sekolah.sch.id', NULL, '$2y$12$ZTdFPQaovYV1ppMkO6829eIGtu9Thh8vAO4LtKZYsd.3czKDzK50q', NULL, '2026-11-13 10:20:21', '2026-11-23 12:46:15', 'guru_piket', 'guru', '081234567893', '19900214 201502 2 008', NULL),
(5, 'Gilang Arya Dewantara (Siswa/Ortu)', 'gilang.siswa@sekolah.sch.id', NULL, '$2y$12$OO05j39e8hXjfaHOgxi10OvTYrHRYRbJW/wakeVnQsUvaRjN.cIiC', NULL, '2026-11-13 10:20:26', '2026-11-23 12:46:15', 'siswa1', 'siswa_ortu', '081234567007', '0081234567', NULL);

-- --------------------------------------------------------
-- Struktur tabel `password_reset_tokens`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Struktur tabel `sessions`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data untuk tabel `sessions`
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('sCmcG9DGvVXQY1MZk4UqXNIOaJf1ggV3e0VhyDVX', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJpRUJyUjFlYkswYklhZFN4Q01vdVVQOU1GdEg0eG53ZjZqbzJFR1VGIiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvbG9jYWxob3N0OjgwMDBcL3Zpb2xhdGlvbnNcL2NyZWF0ZSIsInJvdXRlIjoidmlvbGF0aW9ucy5jcmVhdGUifSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjIsInBhc3N3b3JkX2hhc2hfd2ViIjoiMmU2Y2NlYjVmMDZmOGViZjAwZmViNjM1NDk2NTg4OGQ4OGExNGE4NDBlZTZiYTFiMDVkOTA2ZjVhMzBlNzQzOCJ9', 1795438873);

-- --------------------------------------------------------
-- Struktur tabel `school_settings`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `school_settings`;
CREATE TABLE `school_settings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_name` varchar(255) NOT NULL DEFAULT 'SMK MIFDA',
  `school_code` varchar(255) DEFAULT NULL,
  `school_address` varchar(255) DEFAULT 'Jl. Raya MIFDA No. 01',
  `school_phone` varchar(255) DEFAULT '021-1234567',
  `school_email` varchar(255) DEFAULT 'smkmifda@sch.id',
  `principal_name` varchar(255) DEFAULT 'Drs. H. Ahmad Fauzi, M.Pd.',
  `principal_nip` varchar(255) DEFAULT '19700101 199512 1 001',
  `counseling_head_name` varchar(255) DEFAULT 'Siti Nurhaliza, S.Pd., Kons.',
  `counseling_head_nip` varchar(255) DEFAULT '19820510 200801 2 005',
  `active_academic_year` varchar(50) NOT NULL DEFAULT '2026/2027',
  `active_semester` enum('Ganjil','Genap') NOT NULL DEFAULT 'Ganjil',
  `wa_gateway_token` varchar(255) DEFAULT NULL,
  `wa_gateway_endpoint` varchar(255) DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data untuk tabel `school_settings`
INSERT INTO `school_settings` (`id`, `school_name`, `school_code`, `school_address`, `school_phone`, `school_email`, `principal_name`, `principal_nip`, `counseling_head_name`, `counseling_head_nip`, `active_academic_year`, `active_semester`, `wa_gateway_token`, `wa_gateway_endpoint`, `logo_path`, `created_at`, `updated_at`) VALUES
(1, 'SMK / SMA NEGERI 1 TELADAN BANGSA', 'NPSN-20261101', 'Jl. Pendidikan Karakter No. 45, Kompleks Pendidikan, Jakarta', '(021) 7788-9900', 'admin@smkn1teladan.sch.id', 'Drs. H. Bambang Sudirman, M.M.', '19680315 199403 1 004', 'Hj. Ratna Indrayani, S.Pd., M.Psi.', '19800720 200604 2 011', '2026/2027', 'Ganjil', NULL, NULL, NULL, '2026-11-13 10:20:19', '2026-11-23 12:46:12');

-- --------------------------------------------------------
-- Struktur tabel `classes`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `classes`;
CREATE TABLE `classes` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `level` varchar(255) NOT NULL,
  `major` varchar(255) DEFAULT NULL,
  `homeroom_teacher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `academic_year` varchar(50) NOT NULL DEFAULT '2026/2027',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `classes_homeroom_teacher_id_foreign` (`homeroom_teacher_id`),
  CONSTRAINT `classes_homeroom_teacher_id_foreign` FOREIGN KEY (`homeroom_teacher_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data untuk tabel `classes`
INSERT INTO `classes` (`id`, `name`, `level`, `major`, `homeroom_teacher_id`, `academic_year`, `created_at`, `updated_at`) VALUES
(1, 'X RPL 1', 'X', 'Rekayasa Perangkat Lunak', 3, '2026/2027', '2026-11-13 10:20:24', '2026-11-13 10:20:24'),
(2, 'X TKJ 1', 'X', 'Teknik Komputer Jaringan', 4, '2026/2027', '2026-11-13 10:20:25', '2026-11-13 10:20:25'),
(3, 'XI RPL 1', 'XI', 'Rekayasa Perangkat Lunak', NULL, '2026/2027', '2026-11-13 10:20:25', '2026-11-13 10:20:25'),
(4, 'XI TKJ 1', 'XI', 'Teknik Komputer Jaringan', NULL, '2026/2027', '2026-11-13 10:20:25', '2026-11-13 10:20:25'),
(5, 'XII RPL 1', 'XII', 'Rekayasa Perangkat Lunak', NULL, '2026/2027', '2026-11-13 10:20:25', '2026-11-13 10:20:25');

-- --------------------------------------------------------
-- Struktur tabel `students`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `students`;
CREATE TABLE `students` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nisn` varchar(20) NOT NULL,
  `nis` varchar(20) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `gender` enum('L','P') NOT NULL DEFAULT 'L',
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `parent_name` varchar(255) DEFAULT NULL,
  `parent_phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('aktif','lulus','mutasi','drop_out') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `students_nisn_unique` (`nisn`),
  UNIQUE KEY `students_nis_unique` (`nis`),
  KEY `students_class_id_foreign` (`class_id`),
  KEY `students_user_id_foreign` (`user_id`),
  CONSTRAINT `students_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data untuk tabel `students`
INSERT INTO `students` (`id`, `nisn`, `nis`, `name`, `gender`, `class_id`, `phone`, `parent_name`, `parent_phone`, `address`, `photo`, `user_id`, `status`, `created_at`, `updated_at`) VALUES
(1, '0081234561', '26001', 'Aditya Pratama Putra', 'L', 1, '081399887700', 'Bambang Supriyanto', '081234567001', 'Jl. Mawar No. 12, Jakarta', NULL, NULL, 'aktif', '2026-11-13 10:20:25', '2026-11-13 10:20:25'),
(2, '0081234562', '26002', 'Alya Rahmadhani', 'P', 1, '081399887701', 'H. Gunawan Santoso', '081234567002', 'Jl. Melati Blok C No. 5, Jakarta', NULL, NULL, 'aktif', '2026-11-13 10:20:25', '2026-11-13 10:20:25'),
(3, '0081234563', '26003', 'Bintang Ramadhan', 'L', 1, '081399887702', 'Rahmat Hidayat', '081234567003', 'Jl. Kenanga No. 88, Jakarta', NULL, NULL, 'aktif', '2026-11-13 10:20:25', '2026-11-13 10:20:25'),
(4, '0081234564', '26004', 'Chelsea Olivia Kusuma', 'P', 1, '081399887703', 'Kusuma Wardhana', '081234567004', 'Jl. Cempaka No. 14, Jakarta', NULL, NULL, 'aktif', '2026-11-13 10:20:25', '2026-11-13 10:20:25'),
(5, '0081234565', '26005', 'Dwi Cahyo Nugroho', 'L', 1, '081399887704', 'Suryanto', '081234567005', 'Jl. Anggrek No. 20, Jakarta', NULL, NULL, 'aktif', '2026-11-13 10:20:26', '2026-11-13 10:20:26'),
(6, '0081234566', '26006', 'Fajar Maulana Malik', 'L', 1, '081399887705', 'Malik Ibrahim', '081234567006', 'Jl. Kemuning No. 3, Jakarta', NULL, NULL, 'aktif', '2026-11-13 10:20:26', '2026-11-13 10:20:26'),
(7, '0081234567', '26007', 'Gilang Arya Dewantara', 'L', 1, '081399887706', 'Agus Dewantara', '081234567007', 'Jl. Dahlia No. 45, Jakarta', NULL, 5, 'aktif', '2026-11-13 10:20:26', '2026-11-13 10:20:26'),
(8, '0081234568', '26008', 'Hana Fitria Azzahra', 'P', 1, '081399887707', 'M. Mansyur', '081234567008', 'Jl. Flamboyan No. 19, Jakarta', NULL, NULL, 'aktif', '2026-11-13 10:20:26', '2026-11-13 10:20:26'),
(9, '0081234569', '26009', 'Ilham Wahyudi Saputra', 'L', 1, '081399887708', 'Wahyudi', '081234567009', 'Jl. Teratai No. 60, Jakarta', NULL, NULL, 'aktif', '2026-11-13 10:20:27', '2026-11-13 10:20:27'),
(10, '0081234570', '26010', 'Jessica Anindya Putri', 'P', 1, '081399887709', 'Eko Prasetyo', '081234567010', 'Jl. Bougenville No. 22, Jakarta', NULL, NULL, 'aktif', '2026-11-13 10:20:27', '2026-11-13 10:20:27'),
(11, '0081234571', '26011', 'Kevin Rizky Ananda', 'L', 2, '081399887710', 'Ananda Ruslan', '081234567011', 'Jl. Garuda No. 11, Jakarta', NULL, NULL, 'aktif', '2026-11-13 10:20:27', '2026-11-13 10:20:27'),
(12, '0081234572', '26012', 'Lestari Wulandari', 'P', 2, '081399887711', 'Joko Widodo', '081234567012', 'Jl. Rajawali No. 7, Jakarta', NULL, NULL, 'aktif', '2026-11-13 10:20:27', '2026-11-13 10:20:27'),
(13, '0081234573', '26013', 'Muhammad Farhan Kamil', 'L', 2, '081399887712', 'Kamil Siddiq', '081234567013', 'Jl. Merpati No. 33, Jakarta', NULL, NULL, 'aktif', '2026-11-13 10:20:27', '2026-11-13 10:20:27'),
(14, '0081234574', '26014', 'Nabila Syakieb', 'P', 2, '081399887713', 'Syakieb Ali', '081234567014', 'Jl. Cendrawasih No. 15, Jakarta', NULL, NULL, 'aktif', '2026-11-13 10:20:27', '2026-11-13 10:20:27'),
(15, '0081234575', '26015', 'Oki Setiawan', 'L', 2, '081399887714', 'Budi Setiawan', '081234567015', 'Jl. Kasuari No. 9, Jakarta', NULL, NULL, 'aktif', '2026-11-13 10:20:27', '2026-11-13 10:20:27'),
(16, '0071234581', '25001', 'Panji Gumilang Pangestu', 'L', 3, '081399887715', 'Pangestu Tri', '081234567016', 'Jl. Diponegoro No. 101, Jakarta', NULL, NULL, 'aktif', '2026-11-13 10:20:27', '2026-11-13 10:20:27'),
(17, '0071234582', '25002', 'Qonita Salma', 'P', 3, '081399887716', 'Salma Harun', '081234567017', 'Jl. Sudirman No. 55, Jakarta', NULL, NULL, 'aktif', '2026-11-13 10:20:27', '2026-11-13 10:20:27'),
(18, '0071234583', '25003', 'Rendi Septian', 'L', 3, '081399887717', 'Septian Nur', '081234567018', 'Jl. Thamrin No. 80, Jakarta', NULL, NULL, 'aktif', '2026-11-13 10:20:28', '2026-11-13 10:20:28'),
(19, '0071234584', '25004', 'Siti Aisyah Humaira', 'P', 3, '081399887718', 'Lukman Hakim', '081234567019', 'Jl. Gatot Subroto No. 4, Jakarta', NULL, NULL, 'aktif', '2026-11-13 10:20:28', '2026-11-13 10:20:28'),
(20, '0071234585', '25005', 'Teguh Prakoso', 'L', 3, '081399887719', 'Prakoso Hadi', '081234567020', 'Jl. Rasuna Said No. 12, Jakarta', NULL, NULL, 'aktif', '2026-11-13 10:20:28', '2026-11-13 10:20:28');

-- --------------------------------------------------------
-- Struktur tabel `attendance_records`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `attendance_records`;
CREATE TABLE `attendance_records` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `status` enum('hadir','sakit','izin','alpa','terlambat') NOT NULL DEFAULT 'hadir',
  `notes` varchar(255) DEFAULT NULL,
  `recorded_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attendance_records_student_id_date_unique` (`student_id`,`date`),
  KEY `attendance_records_class_id_date_index` (`class_id`,`date`),
  KEY `attendance_records_recorded_by_foreign` (`recorded_by`),
  CONSTRAINT `attendance_records_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attendance_records_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attendance_records_recorded_by_foreign` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Struktur tabel `monthly_attendances`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `monthly_attendances`;
CREATE TABLE `monthly_attendances` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `month` tinyint(3) UNSIGNED NOT NULL,
  `year` smallint(5) UNSIGNED NOT NULL,
  `academic_year` varchar(50) DEFAULT '2026/2027',
  `effective_days` smallint(5) UNSIGNED NOT NULL DEFAULT 22,
  `hadir` smallint(5) UNSIGNED NOT NULL DEFAULT 22,
  `sakit` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `izin` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `alpa` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `notes` varchar(255) DEFAULT NULL,
  `recorded_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `monthly_attendances_student_id_month_year_unique` (`student_id`,`month`,`year`),
  KEY `monthly_attendances_class_id_month_year_index` (`class_id`,`month`,`year`),
  KEY `monthly_attendances_recorded_by_foreign` (`recorded_by`),
  CONSTRAINT `monthly_attendances_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `monthly_attendances_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `monthly_attendances_recorded_by_foreign` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data untuk tabel `monthly_attendances`
INSERT INTO `monthly_attendances` (`id`, `student_id`, `class_id`, `month`, `year`, `academic_year`, `effective_days`, `hadir`, `sakit`, `izin`, `alpa`, `notes`, `recorded_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 11, 2026, '2026/2027', 22, 17, 0, 5, 0, NULL, 1, '2026-11-23 12:46:15', '2026-11-23 12:49:07'),
(2, 2, 1, 11, 2026, '2026/2027', 22, 21, 0, 0, 1, 'Perlu konfirmasi orang tua', 1, '2026-11-23 12:46:15', '2026-11-23 12:49:07'),
(3, 3, 1, 11, 2026, '2026/2027', 22, 22, 0, 0, 0, NULL, 1, '2026-11-23 12:46:16', '2026-11-23 12:49:08'),
(4, 4, 1, 11, 2026, '2026/2027', 22, 22, 0, 0, 0, NULL, 1, '2026-11-23 12:46:16', '2026-11-23 12:49:08'),
(5, 5, 1, 11, 2026, '2026/2027', 22, 22, 0, 0, 0, NULL, 1, '2026-11-23 12:46:16', '2026-11-23 12:49:08'),
(6, 6, 1, 11, 2026, '2026/2027', 22, 21, 0, 0, 1, 'Perlu konfirmasi orang tua', 1, '2026-11-23 12:46:16', '2026-11-23 12:49:08'),
(7, 7, 1, 11, 2026, '2026/2027', 22, 17, 2, 1, 2, 'Perlu konfirmasi orang tua', 1, '2026-11-23 12:46:16', '2026-11-23 12:49:08'),
(8, 8, 1, 11, 2026, '2026/2027', 22, 22, 0, 0, 0, NULL, 1, '2026-11-23 12:46:16', '2026-11-23 12:49:08'),
(9, 9, 1, 11, 2026, '2026/2027', 22, 22, 0, 0, 0, NULL, 1, '2026-11-23 12:46:16', '2026-11-23 12:49:08'),
(10, 10, 1, 11, 2026, '2026/2027', 22, 22, 0, 0, 0, NULL, 1, '2026-11-23 12:46:16', '2026-11-23 12:49:08'),
(11, 11, 2, 11, 2026, '2026/2027', 22, 22, 0, 0, 0, NULL, 3, '2026-11-23 12:46:16', '2026-11-23 12:46:16'),
(12, 12, 2, 11, 2026, '2026/2027', 22, 22, 0, 0, 0, NULL, 3, '2026-11-23 12:46:17', '2026-11-23 12:46:17'),
(13, 13, 2, 11, 2026, '2026/2027', 22, 22, 0, 0, 0, NULL, 3, '2026-11-23 12:46:17', '2026-11-23 12:46:17'),
(14, 14, 2, 11, 2026, '2026/2027', 22, 21, 0, 1, 0, NULL, 3, '2026-11-23 12:46:17', '2026-11-23 12:46:17'),
(15, 15, 2, 11, 2026, '2026/2027', 22, 21, 1, 0, 0, NULL, 3, '2026-11-23 12:46:17', '2026-11-23 12:46:17'),
(16, 16, 3, 11, 2026, '2026/2027', 22, 22, 0, 0, 0, NULL, 3, '2026-11-23 12:46:17', '2026-11-23 12:46:17'),
(17, 17, 3, 11, 2026, '2026/2027', 22, 20, 1, 1, 0, NULL, 3, '2026-11-23 12:46:17', '2026-11-23 12:46:17'),
(18, 18, 3, 11, 2026, '2026/2027', 22, 20, 1, 0, 1, 'Perlu konfirmasi orang tua', 3, '2026-11-23 12:46:17', '2026-11-23 12:46:17'),
(19, 19, 3, 11, 2026, '2026/2027', 22, 22, 0, 0, 0, NULL, 3, '2026-11-23 12:46:17', '2026-11-23 12:46:17'),
(20, 20, 3, 11, 2026, '2026/2027', 22, 21, 1, 0, 0, NULL, 3, '2026-11-23 12:46:18', '2026-11-23 12:46:18');

-- --------------------------------------------------------
-- Struktur tabel `violation_categories`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `violation_categories`;
CREATE TABLE `violation_categories` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data untuk tabel `violation_categories`
INSERT INTO `violation_categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Kedisiplinan & Waktu', 'Pelanggaran terkait ketepatan waktu dan aturan kehadiran harian.', '2026-11-13 10:20:22', '2026-11-13 10:20:22'),
(2, 'Kerapian & Atribut', 'Pelanggaran pakaian seragam, atribut kelengkapan, dan penampilan fisik.', '2026-11-13 10:20:22', '2026-11-13 10:20:22'),
(3, 'Etika & Sopan Santun', 'Tingkah laku terhadap guru, teman, staf, dan lingkungan sekolah.', '2026-11-13 10:20:23', '2026-11-13 10:20:23'),
(4, 'Pelanggaran Berat & Norma Hukum', 'Tindakan yang melanggar hukum, ketertiban umum, dan norma kesusilaan.', '2026-11-13 10:20:24', '2026-11-13 10:20:24');

-- --------------------------------------------------------
-- Struktur tabel `violation_types`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `violation_types`;
CREATE TABLE `violation_types` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `points` int(11) NOT NULL DEFAULT 5,
  `default_sanction` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `violation_types_category_id_foreign` (`category_id`),
  CONSTRAINT `violation_types_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `violation_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data untuk tabel `violation_types`
INSERT INTO `violation_types` (`id`, `category_id`, `name`, `points`, `default_sanction`, `created_at`, `updated_at`) VALUES
(1, 1, 'Terlambat hadir ke sekolah (10 - 30 menit)', 5, 'Teguran lisan & piket kebersihan', '2026-11-13 10:20:22', '2026-11-13 10:20:22'),
(2, 1, 'Terlambat lebih dari 30 menit tanpa keterangan sah', 10, 'Surat teguran & pembinaan wali kelas', '2026-11-13 10:20:22', '2026-11-13 10:20:22'),
(3, 1, 'Keluar gerbang sekolah saat jam pelajaran tanpa izin', 20, 'Tugas mandiri di perpustakaan & SP 1', '2026-11-13 10:20:22', '2026-11-13 10:20:22'),
(4, 1, 'Membolos jam pelajaran tertentu (Cabut)', 15, 'Pembinaan guru mata pelajaran & wali kelas', '2026-11-13 10:20:22', '2026-11-13 10:20:22'),
(5, 1, 'Tidak masuk sekolah tanpa kabar/keterangan (Alpa)', 10, 'Konfirmasi orang tua via telepon', '2026-11-13 10:20:22', '2026-11-13 10:20:22'),
(6, 2, 'Pakaian seragam tidak dimasukkan / tidak sesuai hari', 5, 'Teguran & perapian seragam langsung', '2026-11-13 10:20:22', '2026-11-13 10:20:22'),
(7, 2, 'Tidak memakai dasi, sabuk, atau topi saat upacara', 5, 'Barisan khusus upacara', '2026-11-13 10:20:22', '2026-11-13 10:20:22'),
(8, 2, 'Memakai sepatu dan kaos kaki tidak sesuai ketentuan', 5, 'Teguran & penggantian kaos kaki', '2026-11-13 10:20:23', '2026-11-13 10:20:23'),
(9, 2, 'Rambut gondrong/diwarnai bagi siswa putra', 10, 'Diberi batas waktu 2 hari untuk potong rapi', '2026-11-13 10:20:23', '2026-11-13 10:20:23'),
(10, 2, 'Menggunakan perhiasan/make-up berlebihan bagi siswi', 5, 'Penghapusan make-up di tempat', '2026-11-13 10:20:23', '2026-11-13 10:20:23'),
(11, 2, 'Memakai tato temporer/permanen atau tindik bagi putra', 25, 'Pembinaan khusus BK & panggilan ortu', '2026-11-13 10:20:23', '2026-11-13 10:20:23'),
(12, 3, 'Makan/minum di dalam kelas saat KBM berlangsung tanpa izin', 5, 'Teguran guru pengajar', '2026-11-13 10:20:23', '2026-11-13 10:20:23'),
(13, 3, 'Bermain HP/Game saat jam pelajaran berlangsung', 10, 'HP disita dan diambil saat pulang sekolah', '2026-11-13 10:20:23', '2026-11-13 10:20:23'),
(14, 3, 'Berbicara kasar/tidak sopan kepada guru atau tenaga kependidikan', 30, 'Permintaan maaf tertulis & pembinaan BK', '2026-11-13 10:20:23', '2026-11-13 10:20:23'),
(15, 3, 'Melakukan perundungan/bullying secara verbal terhadap teman', 35, 'Mediasi BK & surat pernyataan bermaterai', '2026-11-13 10:20:23', '2026-11-13 10:20:23'),
(16, 3, 'Membuang sampah sembarangan / merusak tanaman sekolah', 10, 'Operasi semut membersihkan taman sekolah', '2026-11-13 10:20:24', '2026-11-13 10:20:24'),
(17, 4, 'Membawa/merokok atau vape di lingkungan sekolah & radius 100m', 40, 'Sita barang bukti & Surat Panggilan Orang Tua', '2026-11-13 10:20:24', '2026-11-13 10:20:24'),
(18, 4, 'Terlibat perkelahian/tawuran antar pelajar', 75, 'Skorsing 1 minggu & SP 3', '2026-11-13 10:20:24', '2026-11-13 10:20:24'),
(19, 4, 'Membawa senjata tajam atau benda berbahaya ke sekolah', 80, 'Penyitaan, pemanggilan ortu, koordinasi aparat', '2026-11-13 10:20:24', '2026-11-13 10:20:24'),
(20, 4, 'Mencuri barang milik sekolah atau teman', 60, 'Ganti rugi barang & pembinaan ketat BK', '2026-11-13 10:20:24', '2026-11-13 10:20:24'),
(21, 4, 'Membawa/mengonsumsi miras atau narkoba', 100, 'Dikembalikan ke orang tua / proses hukum', '2026-11-13 10:20:24', '2026-11-13 10:20:24'),
(22, 4, 'Melakukan tindakan asusila / menyebarkan konten pornografi', 85, 'Sidang dewan kehormatan & SP 3', '2026-11-13 10:20:24', '2026-11-13 10:20:24');

-- --------------------------------------------------------
-- Struktur tabel `student_violations`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `student_violations`;
CREATE TABLE `student_violations` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `violation_type_id` bigint(20) UNSIGNED NOT NULL,
  `violation_date` date NOT NULL,
  `violation_time` time DEFAULT NULL,
  `points` int(11) NOT NULL,
  `reporter_id` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `proof_image` varchar(255) DEFAULT NULL,
  `status` enum('menunggu_tindakan','dalam_pembinaan','selesai') NOT NULL DEFAULT 'menunggu_tindakan',
  `follow_up_action` text DEFAULT NULL,
  `handled_by` bigint(20) UNSIGNED DEFAULT NULL,
  `handled_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_violations_student_id_violation_date_index` (`student_id`,`violation_date`),
  KEY `student_violations_violation_type_id_foreign` (`violation_type_id`),
  KEY `student_violations_reporter_id_foreign` (`reporter_id`),
  KEY `student_violations_handled_by_foreign` (`handled_by`),
  CONSTRAINT `student_violations_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `student_violations_violation_type_id_foreign` FOREIGN KEY (`violation_type_id`) REFERENCES `violation_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `student_violations_reporter_id_foreign` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `student_violations_handled_by_foreign` FOREIGN KEY (`handled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data untuk tabel `student_violations`
INSERT INTO `student_violations` (`id`, `student_id`, `violation_type_id`, `violation_date`, `violation_time`, `points`, `reporter_id`, `notes`, `proof_image`, `status`, `follow_up_action`, `handled_by`, `handled_at`, `created_at`, `updated_at`) VALUES
(11, 7, 1, '2026-11-11 00:00:00', '07:25:00', 5, 4, 'Terlambat masuk sekolah saat pintu gerbang sudah ditutup.', NULL, 'selesai', 'Diberi pembinaan lisan oleh guru piket dan membersihkan koridor.', 4, '2026-11-11 12:46:18', '2026-11-23 12:46:18', '2026-11-23 12:46:18'),
(12, 7, 13, '2026-11-15 00:00:00', '10:15:00', 10, 3, 'Asyik main game Mobile Legends saat pelajaran matematika berlangsung.', NULL, 'selesai', 'HP diamankan selama KBM dan diambil setelah jam pulang.', 3, '2026-11-15 12:46:18', '2026-11-23 12:46:18', '2026-11-23 12:46:18'),
(13, 7, 17, '2026-11-21 00:00:00', '12:45:00', 40, 3, 'Kedapatan merokok di area belakang kantin sekolah bersama teman.', NULL, 'dalam_pembinaan', 'Diterbitkan Surat Panggilan Orang Tua untuk pembinaan lanjutan di BK.', 2, '2026-11-22 12:46:18', '2026-11-23 12:46:18', '2026-11-23 12:46:18'),
(14, 5, 4, '2026-11-19 00:00:00', '11:00:00', 15, 3, 'Tidak masuk ke kelas pada jam ke-5 dan berada di warung luar pagar.', NULL, 'dalam_pembinaan', 'Peringatan lisan oleh Wali Kelas.', 3, '2026-11-20 12:46:18', '2026-11-23 12:46:18', '2026-11-23 12:46:18'),
(15, 11, 1, '2026-11-08 00:00:00', '07:20:00', 5, 4, 'Terlambat 10 menit.', NULL, 'selesai', NULL, 4, NULL, '2026-11-23 12:46:18', '2026-11-23 12:46:18');

-- --------------------------------------------------------
-- Struktur tabel `point_restorations`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `point_restorations`;
CREATE TABLE `point_restorations` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `restored_points` int(11) NOT NULL DEFAULT 5,
  `restoration_date` date NOT NULL,
  `reason` varchar(255) NOT NULL,
  `notes` text DEFAULT NULL,
  `officer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `point_restorations_student_id_foreign` (`student_id`),
  KEY `point_restorations_officer_id_foreign` (`officer_id`),
  CONSTRAINT `point_restorations_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `point_restorations_officer_id_foreign` FOREIGN KEY (`officer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data untuk tabel `point_restorations`
INSERT INTO `point_restorations` (`id`, `student_id`, `restored_points`, `restoration_date`, `reason`, `notes`, `officer_id`, `created_at`, `updated_at`) VALUES
(3, 11, 5, '2026-11-18 00:00:00', 'Konseling dan partisipasi aktif sebagai petugas paduan suara upacara bendera.', 'Siswa menunjukkan perubahan sikap positif dan disiplin.', 2, '2026-11-23 12:46:18', '2026-11-23 12:46:18');

-- --------------------------------------------------------
-- Struktur tabel `sanction_thresholds`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `sanction_thresholds`;
CREATE TABLE `sanction_thresholds` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `min_points` int(11) NOT NULL,
  `action_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `requires_summons` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data untuk tabel `sanction_thresholds`
INSERT INTO `sanction_thresholds` (`id`, `min_points`, `action_name`, `description`, `requires_summons`, `created_at`, `updated_at`) VALUES
(1, 15, 'Peringatan Lisan & Pembinaan Wali Kelas', 'Siswa dipanggil oleh Wali Kelas untuk diberi teguran lisan dan pencatatan komitmen disiplin.', 0, '2026-11-13 10:20:21', '2026-11-13 10:20:21'),
(2, 30, 'Surat Peringatan I (SP 1) & Bimbingan BK', 'Diterbitkan Surat Peringatan I, siswa wajib mengikuti sesi bimbingan konseling di ruang BK.', 0, '2026-11-13 10:20:21', '2026-11-13 10:20:21'),
(3, 50, 'Surat Peringatan II (SP 2) & Pemanggilan Orang Tua I', 'Diterbitkan SP II dan Surat Panggilan Orang Tua tahap 1 untuk klarifikasi dan penandatanganan surat perjanjian.', 1, '2026-11-13 10:20:21', '2026-11-13 10:20:21'),
(4, 75, 'Surat Peringatan III (SP 3) & Pemanggilan Orang Tua Terakhir', 'Diterbitkan SP III, orang tua diundang menghadap Kepala Sekolah dan Tim Ketertiban Sekolah.', 1, '2026-11-13 10:20:21', '2026-11-13 10:20:21'),
(5, 100, 'Skorsing / Sidang Dewan Guru / Dikembalikan ke Orang Tua', 'Tindakan tegas skorsing sementara atau dikembalikan kepada orang tua melalui sidang dewan guru.', 1, '2026-11-13 10:20:21', '2026-11-13 10:20:21');

-- --------------------------------------------------------
-- Struktur tabel `parent_summons`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `parent_summons`;
CREATE TABLE `parent_summons` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `letter_number` varchar(255) NOT NULL,
  `summons_type` varchar(255) NOT NULL DEFAULT 'Surat Panggilan Orang Tua I',
  `summons_date` date NOT NULL,
  `summons_time` time NOT NULL DEFAULT '08:30:00',
  `meeting_place` varchar(255) NOT NULL DEFAULT 'Ruang Bimbingan Konseling (BK)',
  `reason` text NOT NULL,
  `total_points_snapshot` int(11) NOT NULL,
  `status` enum('diterbitkan','hadir','dijadwalkan_ulang','tidak_hadir','selesai') NOT NULL DEFAULT 'diterbitkan',
  `meeting_result` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `parent_summons_letter_number_unique` (`letter_number`),
  KEY `parent_summons_student_id_foreign` (`student_id`),
  KEY `parent_summons_created_by_foreign` (`created_by`),
  CONSTRAINT `parent_summons_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `parent_summons_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data untuk tabel `parent_summons`
INSERT INTO `parent_summons` (`id`, `student_id`, `letter_number`, `summons_type`, `summons_date`, `summons_time`, `meeting_place`, `reason`, `total_points_snapshot`, `status`, `meeting_result`, `created_by`, `created_at`, `updated_at`) VALUES
(4, 7, '014/SP-BK/SMK/XI/2026', 'Surat Panggilan Orang Tua I (SP 2)', '2026-11-25 00:00:00', '09:00', 'Ruang Bimbingan & Konseling (BK)', 'Akumulasi poin pelanggaran mencapai 55 poin (termasuk pelanggaran membawa dan merokok di lingkungan sekolah). Diperlukan klarifikasi dan penandatanganan surat komitmen bersama.', 55, 'diterbitkan', NULL, 2, '2026-11-23 12:46:18', '2026-11-23 12:46:18');

SET FOREIGN_KEY_CHECKS=1;
COMMIT;
