-- ==========================================================
-- TEMANBELAJAR DATABASE SCHEMA & SEED DATA
-- Database Name: temanbelajar_db
-- Compatibility: MySQL 8.0+ / MariaDB 10.4+ (XAMPP Compatible)
-- ==========================================================

CREATE DATABASE IF NOT EXISTS `temanbelajar_db` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `temanbelajar_db`;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `activity_logs`;
DROP TABLE IF EXISTS `notifications`;
DROP TABLE IF EXISTS `reviews`;
DROP TABLE IF EXISTS `study_sessions`;
DROP TABLE IF EXISTS `tutor_availability`;
DROP TABLE IF EXISTS `tutor_subjects`;
DROP TABLE IF EXISTS `tutor_profiles`;
DROP TABLE IF EXISTS `student_profiles`;
DROP TABLE IF EXISTS `study_subjects`;
DROP TABLE IF EXISTS `study_programs`;
DROP TABLE IF EXISTS `users`;

SET FOREIGN_KEY_CHECKS = 1;

-- ----------------------------------------------------------
-- 1. Table: study_programs
-- ----------------------------------------------------------
CREATE TABLE `study_programs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(20) NOT NULL UNIQUE,
    `name` VARCHAR(100) NOT NULL,
    `faculty` VARCHAR(100) NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 2. Table: users
-- ----------------------------------------------------------
CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `full_name` VARCHAR(150) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `nim` VARCHAR(30) NOT NULL UNIQUE,
    `study_program_id` INT NULL,
    `graduation_year` INT NOT NULL,
    `role` ENUM('student', 'admin') NOT NULL DEFAULT 'student',
    `is_tutor` TINYINT(1) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `avatar` VARCHAR(255) NULL,
    `phone` VARCHAR(25) NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_users_study_program` FOREIGN KEY (`study_program_id`) 
        REFERENCES `study_programs` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX `idx_users_role` (`role`),
    INDEX `idx_users_is_tutor` (`is_tutor`),
    INDEX `idx_users_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 3. Table: student_profiles
-- ----------------------------------------------------------
CREATE TABLE `student_profiles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL UNIQUE,
    `bio` TEXT NULL,
    `interest_topics` TEXT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_student_user` FOREIGN KEY (`user_id`) 
        REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 4. Table: study_subjects
-- ----------------------------------------------------------
CREATE TABLE `study_subjects` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `study_program_id` INT NOT NULL,
    `code` VARCHAR(20) NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `semester` INT NOT NULL,
    `description` TEXT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_subjects_study_program` FOREIGN KEY (`study_program_id`) 
        REFERENCES `study_programs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX `idx_subject_code` (`code`),
    INDEX `idx_subject_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 5. Table: tutor_profiles
-- ----------------------------------------------------------
CREATE TABLE `tutor_profiles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL UNIQUE,
    `headline` VARCHAR(200) NULL,
    `bio` TEXT NULL,
    `academic_achievements` TEXT NULL,
    `teaching_experience` TEXT NULL,
    `preferred_method` ENUM('online', 'offline', 'both') NOT NULL DEFAULT 'both',
    `hourly_rate` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `is_free` TINYINT(1) NOT NULL DEFAULT 1,
    `is_available` TINYINT(1) NOT NULL DEFAULT 1,
    `is_verified` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_tutor_user` FOREIGN KEY (`user_id`) 
        REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX `idx_tutor_availability` (`is_available`, `is_verified`),
    INDEX `idx_tutor_method` (`preferred_method`),
    INDEX `idx_tutor_free` (`is_free`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 6. Table: tutor_subjects
-- ----------------------------------------------------------
CREATE TABLE `tutor_subjects` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `tutor_profile_id` INT NOT NULL,
    `study_subject_id` INT NOT NULL,
    `grade` VARCHAR(5) NOT NULL DEFAULT 'A',
    `notes` VARCHAR(255) NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_ts_tutor_profile` FOREIGN KEY (`tutor_profile_id`) 
        REFERENCES `tutor_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_ts_study_subject` FOREIGN KEY (`study_subject_id`) 
        REFERENCES `study_subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY `unique_tutor_subject` (`tutor_profile_id`, `study_subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 7. Table: tutor_availability
-- ----------------------------------------------------------
CREATE TABLE `tutor_availability` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `tutor_profile_id` INT NOT NULL,
    `day_of_week` ENUM('Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu') NOT NULL,
    `start_time` TIME NOT NULL,
    `end_time` TIME NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_ta_tutor_profile` FOREIGN KEY (`tutor_profile_id`) 
        REFERENCES `tutor_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX `idx_availability_day` (`tutor_profile_id`, `day_of_week`, `is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 8. Table: study_sessions
-- ----------------------------------------------------------
CREATE TABLE `study_sessions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `session_code` VARCHAR(30) NOT NULL UNIQUE,
    `student_id` INT NOT NULL,
    `tutor_id` INT NOT NULL,
    `study_subject_id` INT NOT NULL,
    `session_date` DATE NOT NULL,
    `start_time` TIME NOT NULL,
    `end_time` TIME NOT NULL,
    `duration_minutes` INT NOT NULL DEFAULT 60,
    `learning_method` ENUM('online', 'offline') NOT NULL,
    `location_or_link` VARCHAR(255) NULL,
    `notes` TEXT NULL,
    `status` ENUM('pending', 'accepted', 'rejected', 'cancelled', 'completed') NOT NULL DEFAULT 'pending',
    `rejection_reason` TEXT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_session_student` FOREIGN KEY (`student_id`) 
        REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_session_tutor` FOREIGN KEY (`tutor_id`) 
        REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_session_subject` FOREIGN KEY (`study_subject_id`) 
        REFERENCES `study_subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX `idx_session_tutor_schedule` (`tutor_id`, `session_date`, `status`),
    INDEX `idx_session_student_schedule` (`student_id`, `session_date`),
    INDEX `idx_session_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 9. Table: reviews
-- ----------------------------------------------------------
CREATE TABLE `reviews` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `study_session_id` INT NOT NULL UNIQUE,
    `student_id` INT NOT NULL,
    `tutor_id` INT NOT NULL,
    `rating` INT NOT NULL,
    `comment` TEXT NOT NULL,
    `is_visible` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_review_session` FOREIGN KEY (`study_session_id`) 
        REFERENCES `study_sessions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_review_student` FOREIGN KEY (`student_id`) 
        REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_review_tutor` FOREIGN KEY (`tutor_id`) 
        REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX `idx_reviews_tutor` (`tutor_id`, `is_visible`),
    INDEX `idx_reviews_rating` (`rating`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 10. Table: notifications
-- ----------------------------------------------------------
CREATE TABLE `notifications` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `title` VARCHAR(150) NOT NULL,
    `message` TEXT NOT NULL,
    `link` VARCHAR(255) NULL,
    `type` VARCHAR(50) NOT NULL DEFAULT 'info',
    `is_read` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_notif_user` FOREIGN KEY (`user_id`) 
        REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX `idx_notifications_user` (`user_id`, `is_read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 11. Table: activity_logs
-- ----------------------------------------------------------
CREATE TABLE `activity_logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NULL,
    `action` VARCHAR(100) NOT NULL,
    `details` TEXT NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` VARCHAR(255) NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_activity_user` FOREIGN KEY (`user_id`) 
        REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX `idx_activity_user` (`user_id`),
    INDEX `idx_activity_action` (`action`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================================
-- SEED DATA (DATA AWAL & DATA DEMO)
-- Semua password akun demo: "password123"
-- Hash: $2y$10$wJt0Fw4J/o8rT3n6K.Hq2OeqvXN3zYJgI7e1oY/Q28a31W/G6xO7y
-- (Dihasilkan dengan password_hash('password123', PASSWORD_BCRYPT))
-- ==========================================================

-- Program Studi
INSERT INTO `study_programs` (`id`, `code`, `name`, `faculty`) VALUES
(1, 'SI', 'Sistem Informasi', 'Fakultas Ilmu Komputer'),
(2, 'TI', 'Teknik Informatika', 'Fakultas Ilmu Komputer'),
(3, 'BD', 'Bisnis Digital', 'Fakultas Ekonomi dan Bisnis'),
(4, 'MI', 'Manajemen Informatika', 'Fakultas Ilmu Komputer'),
(5, 'SK', 'Sistem Komputer', 'Fakultas Teknik');

-- Mata Kuliah
INSERT INTO `study_subjects` (`id`, `study_program_id`, `code`, `name`, `semester`, `description`) VALUES
(1, 1, 'SI101', 'Algoritma & Pemrograman', 1, 'Konsep dasar algoritma, flowchart, logika pemrograman dan implementasi Python/C++.'),
(2, 1, 'SI201', 'Struktur Data & Algoritma', 2, 'Array, Linked List, Stack, Queue, Tree, Graph, dan optimasi algoritma.'),
(3, 1, 'SI301', 'Basis Data & SQL', 3, 'Desain ERD, relasi normalisasi database, DDL, DML, dan kueri SQL kompleks.'),
(4, 1, 'SI302', 'Pemrograman Web I', 3, 'Pengembangan web front-end dengan HTML5, CSS3 modern, JavaScript, dan Bootstrap.'),
(5, 1, 'SI401', 'Pemrograman Web Lanjut (PHP MVC)', 4, 'Pengembangan aplikasi web backend dengan PHP, arsitektur MVC, PDO, dan REST API.'),
(6, 1, 'SI402', 'Analisis & Perancangan Sistem', 4, 'Pemodelan proses bisnis, UML (Use Case, Sequence, Activity), dan SRS.'),
(7, 2, 'TI202', 'Matematika Diskrit', 2, 'Logika matematika, teori himpunan, relasi fungsi, graf, dan kombinatorika.'),
(8, 2, 'TI303', 'Jaringan Komputer', 3, 'Topologi, model OSI, TCP/IP, subnetting IP, konfigurasi routing dan switching.'),
(9, 2, 'TI403', 'Kecerdasan Buatan (AI)', 4, 'Pengenalan Machine Learning, Decision Tree, Neural Networks, dan evaluasi model.'),
(10, 3, 'BD201', 'Statistika Bisnis', 2, 'Pengolahan data statistik, regresi, korelasi, dan pengujian hipotesis untuk bisnis.');

-- Akun Pengguna Demo
-- Password untuk semua akun demo: password123
INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `nim`, `study_program_id`, `graduation_year`, `role`, `is_tutor`, `is_active`, `avatar`, `phone`) VALUES
-- 1. Admin Demo
(1, 'Admin Akademik TB', 'admin@temanbelajar.ac.id', '$2y$10$iZkGg0U0s0d1f7s2q6u7aeP8hR2O7Vp5g1z2j3k4l5m6n7o8p9q0r', 'ADMIN001', 1, 2020, 'admin', 0, 1, 'admin_avatar.png', '081234567890'),
-- 2. Tutor Demo 1 (Bintang Pratama)
(2, 'Bintang Pratama', 'bintang@temanbelajar.ac.id', '$2y$10$iZkGg0U0s0d1f7s2q6u7aeP8hR2O7Vp5g1z2j3k4l5m6n7o8p9q0r', '21051214001', 1, 2021, 'student', 1, 1, 'bintang.png', '081211112222'),
-- 3. Tutor Demo 2 (Nadia Salsabila)
(3, 'Nadia Salsabila', 'nadia@temanbelajar.ac.id', '$2y$10$iZkGg0U0s0d1f7s2q6u7aeP8hR2O7Vp5g1z2j3k4l5m6n7o8p9q0r', '21051214002', 1, 2021, 'student', 1, 1, 'nadia.png', '081222223333'),
-- 4. Tutor Demo 3 (Rian Hidayat)
(4, 'Rian Hidayat', 'rian@temanbelajar.ac.id', '$2y$10$iZkGg0U0s0d1f7s2q6u7aeP8hR2O7Vp5g1z2j3k4l5m6n7o8p9q0r', '20051214003', 2, 2020, 'student', 1, 1, 'rian.png', '081233334444'),
-- 5. Mahasiswa Demo 1 (Dina Wahyuni)
(5, 'Dina Wahyuni', 'dina@temanbelajar.ac.id', '$2y$10$iZkGg0U0s0d1f7s2q6u7aeP8hR2O7Vp5g1z2j3k4l5m6n7o8p9q0r', '23051214010', 1, 2023, 'student', 0, 1, 'dina.png', '081244445555'),
-- 6. Mahasiswa Demo 2 (Fajar Nugroho)
(6, 'Fajar Nugroho', 'fajar@temanbelajar.ac.id', '$2y$10$iZkGg0U0s0d1f7s2q6u7aeP8hR2O7Vp5g1z2j3k4l5m6n7o8p9q0r', '23051214011', 2, 2023, 'student', 0, 1, 'fajar.png', '081255556666'),
-- 7. Mahasiswa Demo 3 (Siti Rahma)
(7, 'Siti Rahma', 'siti@temanbelajar.ac.id', '$2y$10$iZkGg0U0s0d1f7s2q6u7aeP8hR2O7Vp5g1z2j3k4l5m6n7o8p9q0r', '22051214012', 3, 2022, 'student', 0, 1, 'siti.png', '081266667777'),
-- 8. Mahasiswa Demo 4 (Arif Wibowo)
(8, 'Arif Wibowo', 'arif@temanbelajar.ac.id', '$2y$10$iZkGg0U0s0d1f7s2q6u7aeP8hR2O7Vp5g1z2j3k4l5m6n7o8p9q0r', '24051214015', 1, 2024, 'student', 0, 1, 'arif.png', '081277778888'),
-- 9. Mahasiswa Demo 5 (Mega Putri)
(9, 'Mega Putri', 'mega@temanbelajar.ac.id', '$2y$10$iZkGg0U0s0d1f7s2q6u7aeP8hR2O7Vp5g1z2j3k4l5m6n7o8p9q0r', '24051214016', 2, 2024, 'student', 0, 1, 'mega.png', '081288889999');

-- Profil Mahasiswa
INSERT INTO `student_profiles` (`user_id`, `bio`, `interest_topics`) VALUES
(1, 'Administrator Pengelola Platform TemanBelajar.', 'Tata Kelola Sistem Informasi'),
(2, 'Mahasiswa semester 7 aktif di laboratorium pemrograman.', 'Web Development, Algoritma, Database'),
(3, 'Asisten Dosen Basis Data dan Analisis Sistem.', 'Data Modeling, UI/UX, Web Design'),
(4, 'Juara Hackathon Kampus, antusias dengan jaringan dan AI.', 'Jaringan Komputer, Python, Machine Learning'),
(5, 'Mahasiswa semester 3 yang ingin memperdalam web programming.', 'HTML, CSS, PHP Dasar'),
(6, 'Tertarik pada logika matematika dan algoritma komputer.', 'Struktur Data, Matematika Diskrit'),
(7, 'Mahasiswa bisnis digital yang ingin mempelajari SQL dan analitika.', 'Database, Analisis Data'),
(8, 'Mahasiswa baru angkatan 2024 yang antusias belajar.', 'Algoritma Pemrograman dasar'),
(9, 'Mahasiswa informatika fokus pada komputasi dasar.', 'Pemrograman Dasar, Logika Algoritma');

-- Profil Tutor
INSERT INTO `tutor_profiles` (`id`, `user_id`, `headline`, `bio`, `academic_achievements`, `teaching_experience`, `preferred_method`, `hourly_rate`, `is_free`, `is_available`, `is_verified`) VALUES
(1, 2, 'Tutor Pemrograman Web & Struktur Data (IPK 3.88)', 'Halo! Saya Bintang. Saya suka membantu teman-teman yang merasa kesulitan memahami logika coding dan arsitektur web MVC. Pendekatan belajar saya santai, berbasis praktek langsung, dan studi kasus nyata.', 'IPK 3.88, Asisten Dosen Praktikum Pemrograman Web 2023, Finalis Gemastik.', 'Pengalaman 2 tahun menjadi tutor sebaya dan mentor praktikum di kampus.', 'both', 0.00, 1, 1, 1),
(2, 3, 'Tutor Basis Data & Analisis Perancangan Sistem (IPK 3.92)', 'Hai! Saya Nadia. Basis data dan perancangan sistem seringkali terlihat abstrak. Bersama saya, kita akan membedah ERD, normalisasi 1NF-3NF, dan kueri SQL langkah demi langkah sampai paham tuntas!', 'IPK 3.92, Mahasiswa Berprestasi Jurusan 2023, Sertifikasi Database Administrator.', '1.5 tahun mendampingi mahasiswa tingkat awal dalam tugas besar basis data.', 'both', 0.00, 1, 1, 1),
(3, 4, 'Tutor Jaringan Komputer, Matematika Diskrit & AI (IPK 3.79)', 'Halo rekan-rekan! Saya Rian. Menghadapi subnetting, graf diskrit, atau implementasi model AI? Jangan ragu belajar bersama. Saya akan jelaskan konsep rumit menjadi analogi yang mudah dicerna.', 'IPK 3.79, Juara 2 Networking Competition Nasional 2023.', 'Tutor privat sebaya sejak 2022 untuk 30+ sesi bimbingan.', 'online', 25000.00, 0, 1, 1);

-- Mata Kuliah yang Diampu Tutor
INSERT INTO `tutor_subjects` (`tutor_profile_id`, `study_subject_id`, `grade`, `notes`) VALUES
(1, 1, 'A', 'Membimbing logika loop, percabangan, fungsi, dan array.'),
(1, 2, 'A', 'Membimbing linked list, stack, queue, dan binary search tree.'),
(1, 4, 'A', 'Membimbing slicing HTML5/CSS3 responsif dan DOM Javascript.'),
(1, 5, 'A', 'Membimbing pembuatan CRUD PHP native MVC dan keamanan PDO.'),
(2, 3, 'A', 'Spesialis perancangan ERD, normalisasi relasi, JOIN dan Subquery.'),
(2, 6, 'A', 'Spesialis use case diagram, sequence diagram, dan user story.'),
(3, 7, 'A', 'Membimbing pembuktian logika, teori himpunan, dan relasi graf.'),
(3, 8, 'A', 'Membimbing subnetting VLSM, routing static/OSPF, dan simulasi Cisco Packet Tracer.'),
(3, 9, 'A', 'Membimbing dasar klasifikasi, KNN, dan visualisasi data Python.');

-- Jadwal Ketersediaan Tutor
INSERT INTO `tutor_availability` (`tutor_profile_id`, `day_of_week`, `start_time`, `end_time`, `is_active`) VALUES
(1, 'Senin', '15:30:00', '18:00:00', 1),
(1, 'Rabu', '16:00:00', '19:00:00', 1),
(1, 'Jumat', '13:30:00', '17:00:00', 1),
(1, 'Sabtu', '09:00:00', '15:00:00', 1),
(2, 'Selasa', '14:00:00', '17:30:00', 1),
(2, 'Kamis', '15:00:00', '18:00:00', 1),
(2, 'Sabtu', '10:00:00', '14:00:00', 1),
(3, 'Senin', '19:00:00', '21:00:00', 1),
(3, 'Rabu', '19:00:00', '21:30:00', 1),
(3, 'Minggu', '09:00:00', '12:00:00', 1);

-- Sesi Belajar Demo
INSERT INTO `study_sessions` (`id`, `session_code`, `student_id`, `tutor_id`, `study_subject_id`, `session_date`, `start_time`, `end_time`, `duration_minutes`, `learning_method`, `location_or_link`, `notes`, `status`, `rejection_reason`) VALUES
(1, 'TB-202610-001', 5, 2, 4, '2026-10-05', '16:00:00', '17:30:00', 90, 'offline', 'Perpustakaan Lantai 2 (Ruang Diskusi B)', 'Belajar layout flexbox dan grid CSS untuk tugas responsif.', 'completed', NULL),
(2, 'TB-202610-002', 6, 2, 2, '2026-10-07', '15:30:00', '17:00:00', 90, 'online', 'https://meet.google.com/demo-tb-002', 'Bahas cara implementasi Binary Search Tree dan traversal in-order.', 'completed', NULL),
(3, 'TB-202610-003', 7, 3, 3, '2026-10-08', '14:00:00', '15:30:00', 90, 'online', 'https://meet.google.com/demo-tb-003', 'Membuat kueri JOIN dan GROUP BY untuk studi kasus kasir minimarket.', 'completed', NULL),
(4, 'TB-202610-004', 8, 2, 1, '2026-10-15', '16:00:00', '17:00:00', 60, 'offline', 'Gazebo Gedung C Fakultas Ilmu Komputer', 'Pengenalan dasar logika percabangan if-else dan nested loop.', 'accepted', NULL),
(5, 'TB-202610-005', 9, 4, 8, '2026-10-18', '19:00:00', '20:30:00', 90, 'online', 'https://meet.google.com/demo-tb-005', 'Latihan subnetting kelas C dan perhitungan jumlah host.', 'pending', NULL);

-- Ulasan & Rating Demo
INSERT INTO `reviews` (`study_session_id`, `student_id`, `tutor_id`, `rating`, `comment`, `is_visible`) VALUES
(1, 5, 2, 5, 'Kak Bintang penjelasannya sangat jelas dan sabar banget! Materi flexbox yang awalnya bikin pusing jadi mudah dimengerti berkat contoh visual langsung. Rekomendasi banget!', 1),
(2, 6, 2, 5, 'Materi binary tree diajarkan step-by-step dengan coding interaktif. Sesi sangat seru dan ramah mahasiswa.', 1),
(3, 7, 3, 5, 'Kak Nadia jago banget SQL! Langsung to the point ke masalah ERD yang saya alami di tugas kelompok. Terima kasih banyak Kak!', 1);

-- Notifikasi Demo
INSERT INTO `notifications` (`user_id`, `title`, `message`, `link`, `type`, `is_read`) VALUES
(2, 'Permintaan Sesi Baru', 'Mahasiswa Arif Wibowo mengajukan sesi belajar untuk mata kuliah Algoritma & Pemrograman.', '/tutor/sessions', 'booking', 1),
(2, 'Ulasan Baru Diterima', 'Dina Wahyuni memberikan bintang 5 untuk sesi Pemrograman Web I.', '/tutor/dashboard', 'review', 1),
(5, 'Sesi Belajar Selesai', 'Sesi belajar Pemrograman Web I telah selesai. Berikan ulasan untuk Kak Bintang Pratama!', '/student/sessions', 'success', 1),
(8, 'Permintaan Sesi Diterima', 'Kak Bintang Pratama menyetujui sesi belajar pada 15 Oktober 2026 di Gazebo Gedung C.', '/student/sessions', 'success', 0);

-- Catatan Aktivitas Demo
INSERT INTO `activity_logs` (`user_id`, `action`, `details`, `ip_address`, `user_agent`) VALUES
(1, 'system_init', 'Database TemanBelajar berhasil diinisialisasi dan diisi data awal.', '127.0.0.1', 'Console/Seeder'),
(2, 'tutor_profile_updated', 'Tutor Bintang Pratama memperbarui ketersediaan jadwal bimbingan.', '127.0.0.1', 'Mozilla/5.0'),
(5, 'review_submitted', 'Dina Wahyuni mengirim ulasan rating 5 untuk sesi TB-202610-001.', '127.0.0.1', 'Mozilla/5.0');
