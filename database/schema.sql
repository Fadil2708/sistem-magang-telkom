-- ============================================================
-- DATABASE: if0_42251225_magang_telkom
-- SISTEM MAGANG & PKL TELKOM SUKABUMI
-- ============================================================

CREATE TABLE `users` (
    `id` CHAR(36) NOT NULL PRIMARY KEY,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('admin', 'supervisor', 'intern') NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `email_verified_at` TIMESTAMP NULL,
    `remember_token` VARCHAR(100) NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `password_reset_tokens` (
    `email` VARCHAR(255) NOT NULL PRIMARY KEY,
    `token` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sessions` (
    `id` VARCHAR(255) NOT NULL PRIMARY KEY,
    `user_id` VARCHAR(36) NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `payload` LONGTEXT NOT NULL,
    `last_activity` INT NOT NULL,
    INDEX `sessions_user_id_index` (`user_id`),
    INDEX `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `intern_profiles` (
    `id` CHAR(36) NOT NULL PRIMARY KEY,
    `user_id` CHAR(36) NOT NULL UNIQUE,
    `full_name` VARCHAR(255) NULL,
    `phone` VARCHAR(20) NULL,
    `address` TEXT NULL,
    `date_of_birth` DATE NULL,
    `institution_name` VARCHAR(255) NULL,
    `institution_type` ENUM('university', 'vocational', 'highschool') NULL,
    `major` VARCHAR(255) NULL,
    `student_id` VARCHAR(100) NULL,
    `photo_url` VARCHAR(500) NULL,
    `cv_url` VARCHAR(500) NULL,
    `cover_letter_url` VARCHAR(500) NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `intern_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `supervisor_profiles` (
    `id` CHAR(36) NOT NULL PRIMARY KEY,
    `user_id` CHAR(36) NOT NULL UNIQUE,
    `full_name` VARCHAR(255) NULL,
    `employee_id` VARCHAR(100) NULL,
    `division` VARCHAR(255) NULL,
    `position` VARCHAR(255) NULL,
    `phone` VARCHAR(20) NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `supervisor_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `vacancies` (
    `id` CHAR(36) NOT NULL PRIMARY KEY,
    `created_by` CHAR(36) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `division` VARCHAR(255) NULL,
    `description` TEXT NOT NULL,
    `qualifications` TEXT NOT NULL,
    `quota` INT UNSIGNED NOT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `application_deadline` DATE NOT NULL,
    `status` ENUM('draft', 'open', 'closed') NOT NULL DEFAULT 'draft',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `vacancies_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `applications` (
    `id` CHAR(36) NOT NULL PRIMARY KEY,
    `intern_id` CHAR(36) NOT NULL,
    `vacancy_id` CHAR(36) NOT NULL,
    `status` ENUM('submitted', 'under_review', 'interview_scheduled', 'accepted', 'rejected', 'cancelled') NOT NULL DEFAULT 'submitted',
    `interview_date` TIMESTAMP NULL,
    `rejection_reason` TEXT NULL,
    `admin_notes` TEXT NULL,
    `applied_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    UNIQUE KEY `applications_intern_id_vacancy_id_unique` (`intern_id`, `vacancy_id`),
    CONSTRAINT `applications_intern_id_foreign` FOREIGN KEY (`intern_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `applications_vacancy_id_foreign` FOREIGN KEY (`vacancy_id`) REFERENCES `vacancies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `internships` (
    `id` CHAR(36) NOT NULL PRIMARY KEY,
    `application_id` CHAR(36) NOT NULL UNIQUE,
    `intern_id` CHAR(36) NOT NULL,
    `supervisor_id` CHAR(36) NULL,
    `vacancy_id` CHAR(36) NOT NULL,
    `actual_start_date` DATE NULL,
    `actual_end_date` DATE NULL,
    `status` ENUM('active', 'completed', 'terminated') NOT NULL DEFAULT 'active',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `internships_application_id_foreign` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `internships_intern_id_foreign` FOREIGN KEY (`intern_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `internships_supervisor_id_foreign` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `internships_vacancy_id_foreign` FOREIGN KEY (`vacancy_id`) REFERENCES `vacancies` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `logbooks` (
    `id` CHAR(36) NOT NULL PRIMARY KEY,
    `internship_id` CHAR(36) NOT NULL,
    `intern_id` CHAR(36) NOT NULL,
    `activity_date` DATE NOT NULL,
    `activities` TEXT NOT NULL,
    `output` TEXT NOT NULL,
    `validation_status` ENUM('draft', 'submitted', 'approved', 'revision_requested') NOT NULL DEFAULT 'draft',
    `supervisor_notes` TEXT NULL,
    `reviewed_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `logbooks_internship_id_foreign` FOREIGN KEY (`internship_id`) REFERENCES `internships` (`id`) ON DELETE CASCADE,
    CONSTRAINT `logbooks_intern_id_foreign` FOREIGN KEY (`intern_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `final_reports` (
    `id` CHAR(36) NOT NULL PRIMARY KEY,
    `internship_id` CHAR(36) NOT NULL UNIQUE,
    `intern_id` CHAR(36) NOT NULL,
    `title` VARCHAR(500) NOT NULL,
    `file_url` VARCHAR(500) NOT NULL,
    `file_size_kb` INT UNSIGNED NULL,
    `submitted_at` TIMESTAMP NULL,
    `supervisor_approval` ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    `approved_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `final_reports_internship_id_foreign` FOREIGN KEY (`internship_id`) REFERENCES `internships` (`id`) ON DELETE CASCADE,
    CONSTRAINT `final_reports_intern_id_foreign` FOREIGN KEY (`intern_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `evaluations` (
    `id` CHAR(36) NOT NULL PRIMARY KEY,
    `internship_id` CHAR(36) NOT NULL UNIQUE,
    `supervisor_id` CHAR(36) NOT NULL,
    `soft_skill_score` DECIMAL(5,2) NOT NULL DEFAULT 0,
    `hard_skill_score` DECIMAL(5,2) NOT NULL DEFAULT 0,
    `attendance_score` DECIMAL(5,2) NOT NULL DEFAULT 0,
    `attitude_score` DECIMAL(5,2) NOT NULL DEFAULT 0,
    `final_score` DECIMAL(5,2) NOT NULL DEFAULT 0,
    `grade` ENUM('A', 'B', 'C', 'D') NULL,
    `remarks` TEXT NULL,
    `evaluated_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `evaluations_internship_id_foreign` FOREIGN KEY (`internship_id`) REFERENCES `internships` (`id`) ON DELETE CASCADE,
    CONSTRAINT `evaluations_supervisor_id_foreign` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `certificates` (
    `id` CHAR(36) NOT NULL PRIMARY KEY,
    `internship_id` CHAR(36) NOT NULL UNIQUE,
    `intern_id` CHAR(36) NOT NULL,
    `certificate_number` VARCHAR(100) NOT NULL UNIQUE,
    `issued_by` CHAR(36) NOT NULL,
    `final_score` DECIMAL(5,2) NOT NULL,
    `grade` ENUM('A', 'B', 'C', 'D') NOT NULL,
    `qr_code_token` VARCHAR(255) NOT NULL UNIQUE,
    `qr_code_url` VARCHAR(500) NULL,
    `certificate_file_url` VARCHAR(500) NULL,
    `issued_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `certificates_internship_id_foreign` FOREIGN KEY (`internship_id`) REFERENCES `internships` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `certificates_intern_id_foreign` FOREIGN KEY (`intern_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `certificates_issued_by_foreign` FOREIGN KEY (`issued_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `testimonials` (
    `id` CHAR(36) NOT NULL PRIMARY KEY,
    `intern_id` CHAR(36) NOT NULL,
    `internship_id` CHAR(36) NOT NULL,
    `rating` TINYINT UNSIGNED NOT NULL,
    `content` TEXT NOT NULL,
    `is_published` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `testimonials_intern_id_foreign` FOREIGN KEY (`intern_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `testimonials_internship_id_foreign` FOREIGN KEY (`internship_id`) REFERENCES `internships` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `registration_invites` (
    `id` CHAR(36) NOT NULL PRIMARY KEY,
    `code` VARCHAR(32) NOT NULL UNIQUE,
    `role` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NULL,
    `used_at` TIMESTAMP NULL,
    `expires_at` TIMESTAMP NULL,
    `created_by` CHAR(36) NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `audit_logs` (
    `id` CHAR(36) NOT NULL PRIMARY KEY,
    `user_id` CHAR(36) NULL,
    `action` VARCHAR(255) NOT NULL,
    `auditable_type` VARCHAR(255) NOT NULL,
    `auditable_id` CHAR(36) NOT NULL,
    `old_values` JSON NULL,
    `new_values` JSON NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    INDEX `audit_logs_auditable_type_auditable_id_index` (`auditable_type`, `auditable_id`),
    INDEX `audit_logs_action_index` (`action`),
    CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `site_settings` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `key` VARCHAR(255) NOT NULL UNIQUE,
    `value` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `faqs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `question` VARCHAR(255) NOT NULL,
    `answer` TEXT NOT NULL,
    `sort_order` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `skills` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `category` VARCHAR(50) NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `intern_profile_skill` (
    `intern_profile_id` CHAR(36) NOT NULL,
    `skill_id` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`intern_profile_id`, `skill_id`),
    CONSTRAINT `ips_intern_profile_id_foreign` FOREIGN KEY (`intern_profile_id`) REFERENCES `intern_profiles` (`id`) ON DELETE CASCADE,
    CONSTRAINT `ips_skill_id_foreign` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `personal_access_tokens` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `tokenable_type` VARCHAR(255) NOT NULL,
    `tokenable_id` CHAR(36) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `token` VARCHAR(64) NOT NULL UNIQUE,
    `abilities` TEXT NULL,
    `last_used_at` TIMESTAMP NULL,
    `expires_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    INDEX `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`, `tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache` (
    `key` VARCHAR(255) NOT NULL PRIMARY KEY,
    `value` MEDIUMTEXT NOT NULL,
    `expiration` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
    `key` VARCHAR(255) NOT NULL PRIMARY KEY,
    `owner` VARCHAR(255) NOT NULL,
    `expiration` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `queue` VARCHAR(255) NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `attempts` TINYINT UNSIGNED NOT NULL,
    `reserved_at` INT UNSIGNED NULL,
    `available_at` INT UNSIGNED NOT NULL,
    `created_at` INT UNSIGNED NOT NULL,
    INDEX `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `job_batches` (
    `id` VARCHAR(255) NOT NULL PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `total_jobs` INT NOT NULL,
    `pending_jobs` INT NOT NULL,
    `failed_jobs` INT NOT NULL,
    `failed_job_ids` LONGTEXT NOT NULL,
    `options` MEDIUMTEXT NULL,
    `cancelled_at` INT NULL,
    `created_at` INT NOT NULL,
    `finished_at` INT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `uuid` VARCHAR(255) NOT NULL UNIQUE,
    `connection` TEXT NOT NULL,
    `queue` TEXT NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `exception` LONGTEXT NOT NULL,
    `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
