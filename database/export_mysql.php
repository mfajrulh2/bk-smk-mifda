<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$tableDDL = [
    'migrations' => "CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    'users' => "CREATE TABLE `users` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    'password_reset_tokens' => "CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    'sessions' => "CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    'school_settings' => "CREATE TABLE `school_settings` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    'classes' => "CREATE TABLE `classes` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    'students' => "CREATE TABLE `students` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    'attendance_records' => "CREATE TABLE `attendance_records` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    'monthly_attendances' => "CREATE TABLE `monthly_attendances` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    'violation_categories' => "CREATE TABLE `violation_categories` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    'violation_types' => "CREATE TABLE `violation_types` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    'student_violations' => "CREATE TABLE `student_violations` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    'point_restorations' => "CREATE TABLE `point_restorations` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    'sanction_thresholds' => "CREATE TABLE `sanction_thresholds` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `min_points` int(11) NOT NULL,
  `action_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `requires_summons` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    'parent_summons' => "CREATE TABLE `parent_summons` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"
];

$sql = "-- ========================================================\n";
$sql .= "-- Database SMK MIFDA - Export untuk MySQL / phpMyAdmin\n";
$sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
$sql .= "-- ========================================================\n\n";
$sql .= "SET FOREIGN_KEY_CHECKS=0;\n";
$sql .= "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n";
$sql .= "START TRANSACTION;\n";
$sql .= "SET time_zone = '+00:00';\n\n";

foreach ($tableDDL as $tableName => $ddl) {
    if (!Schema::hasTable($tableName)) continue;

    $sql .= "-- --------------------------------------------------------\n";
    $sql .= "-- Struktur tabel `$tableName`\n";
    $sql .= "-- --------------------------------------------------------\n";
    $sql .= "DROP TABLE IF EXISTS `$tableName`;\n";
    $sql .= $ddl . "\n\n";

    $rows = DB::table($tableName)->get();
    if ($rows->count() > 0) {
        $cols = Schema::getColumnListing($tableName);
        $colList = '`' . implode('`, `', $cols) . '`';

        $sql .= "-- Data untuk tabel `$tableName`\n";
        $sql .= "INSERT INTO `$tableName` ($colList) VALUES\n";
        $valueRows = [];
        foreach ($rows as $row) {
            $vals = [];
            foreach ($cols as $col) {
                $val = $row->$col;
                if (is_null($val)) {
                    $vals[] = 'NULL';
                } elseif (is_numeric($val) && !is_string($val)) {
                    $vals[] = $val;
                } else {
                    $vals[] = "'" . addslashes((string)$val) . "'";
                }
            }
            $valueRows[] = '(' . implode(', ', $vals) . ')';
        }
        $sql .= implode(",\n", $valueRows) . ";\n\n";
    }
}

$sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
$sql .= "COMMIT;\n";

file_put_contents(__DIR__ . '/database_smk_mifda.sql', $sql);
echo "SUCCESS: database/database_smk_mifda.sql regenerated successfully. Size: " . strlen($sql) . " bytes\n";
