<?php

require_once dirname(__DIR__) . '/app/bootstrap.php';

use App\Helpers\Database;

echo "====================================================\n";
echo " TemanBelajar Database Migration & Seeder\n";
echo "====================================================\n";

try {
    $pdo = Database::getConnection();
    $driver = Database::getDriver();
    echo "Terkoneksi ke database driver: [{$driver}]\n\n";

    if ($driver === 'sqlite') {
        echo "Menyiapkan skema tabel untuk SQLite...\n";
        
        $pdo->exec("PRAGMA foreign_keys = OFF;");

        $tables = [
            "DROP TABLE IF EXISTS activity_logs",
            "DROP TABLE IF EXISTS notifications",
            "DROP TABLE IF EXISTS reviews",
            "DROP TABLE IF EXISTS study_sessions",
            "DROP TABLE IF EXISTS tutor_availability",
            "DROP TABLE IF EXISTS tutor_subjects",
            "DROP TABLE IF EXISTS tutor_profiles",
            "DROP TABLE IF EXISTS student_profiles",
            "DROP TABLE IF EXISTS study_subjects",
            "DROP TABLE IF EXISTS study_programs",
            "DROP TABLE IF EXISTS users"
        ];
        foreach ($tables as $drop) {
            $pdo->exec($drop);
        }

        // 1. study_programs
        $pdo->exec("CREATE TABLE study_programs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            code TEXT NOT NULL UNIQUE,
            name TEXT NOT NULL,
            faculty TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        // 2. users
        $pdo->exec("CREATE TABLE users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            full_name TEXT NOT NULL,
            email TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL,
            nim TEXT NOT NULL UNIQUE,
            study_program_id INTEGER NULL,
            graduation_year INTEGER NOT NULL,
            role TEXT NOT NULL DEFAULT 'student',
            is_tutor INTEGER NOT NULL DEFAULT 0,
            is_active INTEGER NOT NULL DEFAULT 1,
            avatar TEXT NULL,
            phone TEXT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (study_program_id) REFERENCES study_programs (id) ON DELETE SET NULL
        )");

        // 3. student_profiles
        $pdo->exec("CREATE TABLE student_profiles (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL UNIQUE,
            bio TEXT NULL,
            interest_topics TEXT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
        )");

        // 4. study_subjects
        $pdo->exec("CREATE TABLE study_subjects (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            study_program_id INTEGER NOT NULL,
            code TEXT NOT NULL,
            name TEXT NOT NULL,
            semester INTEGER NOT NULL,
            description TEXT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (study_program_id) REFERENCES study_programs (id) ON DELETE CASCADE
        )");

        // 5. tutor_profiles
        $pdo->exec("CREATE TABLE tutor_profiles (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL UNIQUE,
            headline TEXT NULL,
            bio TEXT NULL,
            academic_achievements TEXT NULL,
            teaching_experience TEXT NULL,
            preferred_method TEXT NOT NULL DEFAULT 'both',
            hourly_rate REAL NOT NULL DEFAULT 0.00,
            is_free INTEGER NOT NULL DEFAULT 1,
            is_available INTEGER NOT NULL DEFAULT 1,
            is_verified INTEGER NOT NULL DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
        )");

        // 6. tutor_subjects
        $pdo->exec("CREATE TABLE tutor_subjects (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            tutor_profile_id INTEGER NOT NULL,
            study_subject_id INTEGER NOT NULL,
            grade TEXT NOT NULL DEFAULT 'A',
            notes TEXT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (tutor_profile_id) REFERENCES tutor_profiles (id) ON DELETE CASCADE,
            FOREIGN KEY (study_subject_id) REFERENCES study_subjects (id) ON DELETE CASCADE,
            UNIQUE (tutor_profile_id, study_subject_id)
        )");

        // 7. tutor_availability
        $pdo->exec("CREATE TABLE tutor_availability (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            tutor_profile_id INTEGER NOT NULL,
            day_of_week TEXT NOT NULL,
            start_time TEXT NOT NULL,
            end_time TEXT NOT NULL,
            is_active INTEGER NOT NULL DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (tutor_profile_id) REFERENCES tutor_profiles (id) ON DELETE CASCADE
        )");

        // 8. study_sessions
        $pdo->exec("CREATE TABLE study_sessions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            session_code TEXT NOT NULL UNIQUE,
            student_id INTEGER NOT NULL,
            tutor_id INTEGER NOT NULL,
            study_subject_id INTEGER NOT NULL,
            session_date TEXT NOT NULL,
            start_time TEXT NOT NULL,
            end_time TEXT NOT NULL,
            duration_minutes INTEGER NOT NULL DEFAULT 60,
            learning_method TEXT NOT NULL,
            location_or_link TEXT NULL,
            notes TEXT NULL,
            status TEXT NOT NULL DEFAULT 'pending',
            rejection_reason TEXT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (student_id) REFERENCES users (id) ON DELETE CASCADE,
            FOREIGN KEY (tutor_id) REFERENCES users (id) ON DELETE CASCADE,
            FOREIGN KEY (study_subject_id) REFERENCES study_subjects (id) ON DELETE CASCADE
        )");

        // 9. reviews
        $pdo->exec("CREATE TABLE reviews (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            study_session_id INTEGER NOT NULL UNIQUE,
            student_id INTEGER NOT NULL,
            tutor_id INTEGER NOT NULL,
            rating INTEGER NOT NULL,
            comment TEXT NOT NULL,
            is_visible INTEGER NOT NULL DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (study_session_id) REFERENCES study_sessions (id) ON DELETE CASCADE,
            FOREIGN KEY (student_id) REFERENCES users (id) ON DELETE CASCADE,
            FOREIGN KEY (tutor_id) REFERENCES users (id) ON DELETE CASCADE
        )");

        // 10. notifications
        $pdo->exec("CREATE TABLE notifications (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            title TEXT NOT NULL,
            message TEXT NOT NULL,
            link TEXT NULL,
            type TEXT NOT NULL DEFAULT 'info',
            is_read INTEGER NOT NULL DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
        )");

        // 11. activity_logs
        $pdo->exec("CREATE TABLE activity_logs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NULL,
            action TEXT NOT NULL,
            details TEXT NULL,
            ip_address TEXT NULL,
            user_agent TEXT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL
        )");

        $pdo->exec("PRAGMA foreign_keys = ON;");
        echo "Skema tabel SQLite berhasil dibuat.\n";

    } else {
        echo "Menjalankan skrip SQL untuk MySQL...\n";
        $sql = file_get_contents(__DIR__ . '/temanbelajar_db.sql');
        $pdo->exec($sql);
        echo "Skrip SQL MySQL berhasil dieksekusi.\n";
    }

    // Insert seeds if SQLite (MySQL sql script already has seeds included)
    if ($driver === 'sqlite') {
        echo "Mengisi data awal dan data demo untuk SQLite...\n";
        
        // Study Programs
        $programs = [
            [1, 'SI', 'Sistem Informasi', 'Fakultas Ilmu Komputer'],
            [2, 'TI', 'Teknik Informatika', 'Fakultas Ilmu Komputer'],
            [3, 'BD', 'Bisnis Digital', 'Fakultas Ekonomi dan Bisnis'],
            [4, 'MI', 'Manajemen Informatika', 'Fakultas Ilmu Komputer'],
            [5, 'SK', 'Sistem Komputer', 'Fakultas Teknik'],
        ];
        $stmtProg = $pdo->prepare("INSERT INTO study_programs (id, code, name, faculty) VALUES (?, ?, ?, ?)");
        foreach ($programs as $p) {
            $stmtProg->execute($p);
        }

        // Study Subjects
        $subjects = [
            [1, 1, 'SI101', 'Algoritma & Pemrograman', 1, 'Konsep dasar algoritma, flowchart, logika pemrograman dan implementasi Python/C++.'],
            [2, 1, 'SI201', 'Struktur Data & Algoritma', 2, 'Array, Linked List, Stack, Queue, Tree, Graph, dan optimasi algoritma.'],
            [3, 1, 'SI301', 'Basis Data & SQL', 3, 'Desain ERD, relasi normalisasi database, DDL, DML, dan kueri SQL kompleks.'],
            [4, 1, 'SI302', 'Pemrograman Web I', 3, 'Pengembangan web front-end dengan HTML5, CSS3 modern, JavaScript, dan Bootstrap.'],
            [5, 1, 'SI401', 'Pemrograman Web Lanjut (PHP MVC)', 4, 'Pengembangan aplikasi web backend dengan PHP, arsitektur MVC, PDO, dan REST API.'],
            [6, 1, 'SI402', 'Analisis & Perancangan Sistem', 4, 'Pemodelan proses bisnis, UML (Use Case, Sequence, Activity), dan SRS.'],
            [7, 2, 'TI202', 'Matematika Diskrit', 2, 'Logika matematika, teori himpunan, relasi fungsi, graf, dan kombinatorika.'],
            [8, 2, 'TI303', 'Jaringan Komputer', 3, 'Topologi, model OSI, TCP/IP, subnetting IP, konfigurasi routing dan switching.'],
            [9, 2, 'TI403', 'Kecerdasan Buatan (AI)', 4, 'Pengenalan Machine Learning, Decision Tree, Neural Networks, dan evaluasi model.'],
            [10, 3, 'BD201', 'Statistika Bisnis', 2, 'Pengolahan data statistik, regresi, korelasi, dan pengujian hipotesis untuk bisnis.'],
        ];
        $stmtSub = $pdo->prepare("INSERT INTO study_subjects (id, study_program_id, code, name, semester, description) VALUES (?, ?, ?, ?, ?, ?)");
        foreach ($subjects as $s) {
            $stmtSub->execute($s);
        }

        // Demo Passwords
        $hashedPassword = password_hash('password123', PASSWORD_BCRYPT);

        // Users
        $users = [
            [1, 'Admin Akademik TB', 'admin@temanbelajar.ac.id', $hashedPassword, 'ADMIN001', 1, 2020, 'admin', 0, 1, 'admin_avatar.png', '081234567890'],
            [2, 'Bintang Pratama', 'bintang@temanbelajar.ac.id', $hashedPassword, '21051214001', 1, 2021, 'student', 1, 1, 'bintang.png', '081211112222'],
            [3, 'Nadia Salsabila', 'nadia@temanbelajar.ac.id', $hashedPassword, '21051214002', 1, 2021, 'student', 1, 1, 'nadia.png', '081222223333'],
            [4, 'Rian Hidayat', 'rian@temanbelajar.ac.id', $hashedPassword, '20051214003', 2, 2020, 'student', 1, 1, 'rian.png', '081233334444'],
            [5, 'Dina Wahyuni', 'dina@temanbelajar.ac.id', $hashedPassword, '23051214010', 1, 2023, 'student', 0, 1, 'dina.png', '081244445555'],
            [6, 'Fajar Nugroho', 'fajar@temanbelajar.ac.id', $hashedPassword, '23051214011', 2, 2023, 'student', 0, 1, 'fajar.png', '081255556666'],
            [7, 'Siti Rahma', 'siti@temanbelajar.ac.id', $hashedPassword, '22051214012', 3, 2022, 'student', 0, 1, 'siti.png', '081266667777'],
            [8, 'Arif Wibowo', 'arif@temanbelajar.ac.id', $hashedPassword, '24051214015', 1, 2024, 'student', 0, 1, 'arif.png', '081277778888'],
            [9, 'Mega Putri', 'mega@temanbelajar.ac.id', $hashedPassword, '24051214016', 2, 2024, 'student', 0, 1, 'mega.png', '081288889999'],
        ];
        $stmtUser = $pdo->prepare("INSERT INTO users (id, full_name, email, password, nim, study_program_id, graduation_year, role, is_tutor, is_active, avatar, phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($users as $u) {
            $stmtUser->execute($u);
        }

        // Student profiles
        $studentProfiles = [
            [1, 'Administrator Pengelola Platform TemanBelajar.', 'Tata Kelola Sistem Informasi'],
            [2, 'Mahasiswa semester 7 aktif di laboratorium pemrograman.', 'Web Development, Algoritma, Database'],
            [3, 'Asisten Dosen Basis Data dan Analisis Sistem.', 'Data Modeling, UI/UX, Web Design'],
            [4, 'Juara Hackathon Kampus, antusias dengan jaringan dan AI.', 'Jaringan Komputer, Python, Machine Learning'],
            [5, 'Mahasiswa semester 3 yang ingin memperdalam web programming.', 'HTML, CSS, PHP Dasar'],
            [6, 'Tertarik pada logika matematika dan algoritma komputer.', 'Struktur Data, Matematika Diskrit'],
            [7, 'Mahasiswa bisnis digital yang ingin mempelajari SQL dan analitika.', 'Database, Analisis Data'],
            [8, 'Mahasiswa baru angkatan 2024 yang antusias belajar.', 'Algoritma Pemrograman dasar'],
            [9, 'Mahasiswa informatika fokus pada komputasi dasar.', 'Pemrograman Dasar, Logika Algoritma'],
        ];
        $stmtSP = $pdo->prepare("INSERT INTO student_profiles (user_id, bio, interest_topics) VALUES (?, ?, ?)");
        foreach ($studentProfiles as $sp) {
            $stmtSP->execute($sp);
        }

        // Tutor Profiles
        $tutorProfiles = [
            [1, 2, 'Tutor Pemrograman Web & Struktur Data (IPK 3.88)', 'Halo! Saya Bintang. Saya suka membantu teman-teman yang merasa kesulitan memahami logika coding dan arsitektur web MVC. Pendekatan belajar saya santai, berbasis praktek langsung, dan studi kasus nyata.', 'IPK 3.88, Asisten Dosen Praktikum Pemrograman Web 2023, Finalis Gemastik.', 'Pengalaman 2 tahun menjadi tutor sebaya dan mentor praktikum di kampus.', 'both', 0.00, 1, 1, 1],
            [2, 3, 'Tutor Basis Data & Analisis Perancangan Sistem (IPK 3.92)', 'Hai! Saya Nadia. Basis data dan perancangan sistem seringkali terlihat abstrak. Bersama saya, kita akan membedah ERD, normalisasi 1NF-3NF, dan kueri SQL langkah demi langkah sampai paham tuntas!', 'IPK 3.92, Mahasiswa Berprestasi Jurusan 2023, Sertifikasi Database Administrator.', '1.5 tahun mendampingi mahasiswa tingkat awal dalam tugas besar basis data.', 'both', 0.00, 1, 1, 1],
            [3, 4, 'Tutor Jaringan Komputer, Matematika Diskrit & AI (IPK 3.79)', 'Halo rekan-rekan! Saya Rian. Menghadapi subnetting, graf diskrit, atau implementasi model AI? Jangan ragu belajar bersama. Saya akan jelaskan konsep rumit menjadi analogi yang mudah dicerna.', 'IPK 3.79, Juara 2 Networking Competition Nasional 2023.', 'Tutor privat sebaya sejak 2022 untuk 30+ sesi bimbingan.', 'online', 25000.00, 0, 1, 1],
        ];
        $stmtTP = $pdo->prepare("INSERT INTO tutor_profiles (id, user_id, headline, bio, academic_achievements, teaching_experience, preferred_method, hourly_rate, is_free, is_available, is_verified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($tutorProfiles as $tp) {
            $stmtTP->execute($tp);
        }

        // Tutor subjects
        $tutorSubjects = [
            [1, 1, 'A', 'Membimbing logika loop, percabangan, fungsi, dan array.'],
            [1, 2, 'A', 'Membimbing linked list, stack, queue, dan binary search tree.'],
            [1, 4, 'A', 'Membimbing slicing HTML5/CSS3 responsif dan DOM Javascript.'],
            [1, 5, 'A', 'Membimbing pembuatan CRUD PHP native MVC dan keamanan PDO.'],
            [2, 3, 'A', 'Spesialis perancangan ERD, normalisasi relasi, JOIN dan Subquery.'],
            [2, 6, 'A', 'Spesialis use case diagram, sequence diagram, dan user story.'],
            [3, 7, 'A', 'Membimbing pembuktian logika, teori himpunan, dan relasi graf.'],
            [3, 8, 'A', 'Membimbing subnetting VLSM, routing static/OSPF, dan simulasi Cisco Packet Tracer.'],
            [3, 9, 'A', 'Membimbing dasar klasifikasi, KNN, dan visualisasi data Python.'],
        ];
        $stmtTS = $pdo->prepare("INSERT INTO tutor_subjects (tutor_profile_id, study_subject_id, grade, notes) VALUES (?, ?, ?, ?)");
        foreach ($tutorSubjects as $ts) {
            $stmtTS->execute($ts);
        }

        // Tutor availability
        $availabilities = [
            [1, 'Senin', '15:30:00', '18:00:00', 1],
            [1, 'Rabu', '16:00:00', '19:00:00', 1],
            [1, 'Jumat', '13:30:00', '17:00:00', 1],
            [1, 'Sabtu', '09:00:00', '15:00:00', 1],
            [2, 'Selasa', '14:00:00', '17:30:00', 1],
            [2, 'Kamis', '15:00:00', '18:00:00', 1],
            [2, 'Sabtu', '10:00:00', '14:00:00', 1],
            [3, 'Senin', '19:00:00', '21:00:00', 1],
            [3, 'Rabu', '19:00:00', '21:30:00', 1],
            [3, 'Minggu', '09:00:00', '12:00:00', 1],
        ];
        $stmtAv = $pdo->prepare("INSERT INTO tutor_availability (tutor_profile_id, day_of_week, start_time, end_time, is_active) VALUES (?, ?, ?, ?, ?)");
        foreach ($availabilities as $av) {
            $stmtAv->execute($av);
        }

        // Study sessions
        $sessions = [
            [1, 'TB-202610-001', 5, 2, 4, '2026-10-05', '16:00:00', '17:30:00', 90, 'offline', 'Perpustakaan Lantai 2 (Ruang Diskusi B)', 'Belajar layout flexbox dan grid CSS untuk tugas responsif.', 'completed', null],
            [2, 'TB-202610-002', 6, 2, 2, '2026-10-07', '15:30:00', '17:00:00', 90, 'online', 'https://meet.google.com/demo-tb-002', 'Bahas cara implementasi Binary Search Tree dan traversal in-order.', 'completed', null],
            [3, 'TB-202610-003', 7, 3, 3, '2026-10-08', '14:00:00', '15:30:00', 90, 'online', 'https://meet.google.com/demo-tb-003', 'Membuat kueri JOIN dan GROUP BY untuk studi kasus kasir minimarket.', 'completed', null],
            [4, 'TB-202610-004', 8, 2, 1, '2026-10-15', '16:00:00', '17:00:00', 60, 'offline', 'Gazebo Gedung C Fakultas Ilmu Komputer', 'Pengenalan dasar logika percabangan if-else dan nested loop.', 'accepted', null],
            [5, 'TB-202610-005', 9, 4, 8, '2026-10-18', '19:00:00', '20:30:00', 90, 'online', 'https://meet.google.com/demo-tb-005', 'Latihan subnetting kelas C dan perhitungan jumlah host.', 'pending', null],
        ];
        $stmtSes = $pdo->prepare("INSERT INTO study_sessions (id, session_code, student_id, tutor_id, study_subject_id, session_date, start_time, end_time, duration_minutes, learning_method, location_or_link, notes, status, rejection_reason) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($sessions as $ses) {
            $stmtSes->execute($ses);
        }

        // Reviews
        $reviews = [
            [1, 5, 2, 5, 'Kak Bintang penjelasannya sangat jelas dan sabar banget! Materi flexbox yang awalnya bikin pusing jadi mudah dimengerti berkat contoh visual langsung. Rekomendasi banget!', 1],
            [2, 6, 2, 5, 'Materi binary tree diajarkan step-by-step dengan coding interaktif. Sesi sangat seru dan ramah mahasiswa.', 1],
            [3, 7, 3, 5, 'Kak Nadia jago banget SQL! Langsung to the point ke masalah ERD yang saya alami di tugas kelompok. Terima kasih banyak Kak!', 1],
        ];
        $stmtRev = $pdo->prepare("INSERT INTO reviews (study_session_id, student_id, tutor_id, rating, comment, is_visible) VALUES (?, ?, ?, ?, ?, ?)");
        foreach ($reviews as $rev) {
            $stmtRev->execute($rev);
        }

        // Notifications
        $notifs = [
            [2, 'Permintaan Sesi Baru', 'Mahasiswa Arif Wibowo mengajukan sesi belajar untuk mata kuliah Algoritma & Pemrograman.', '/tutor/sessions', 'booking', 1],
            [2, 'Ulasan Baru Diterima', 'Dina Wahyuni memberikan bintang 5 untuk sesi Pemrograman Web I.', '/tutor/dashboard', 'review', 1],
            [5, 'Sesi Belajar Selesai', 'Sesi belajar Pemrograman Web I telah selesai. Berikan ulasan untuk Kak Bintang Pratama!', '/student/sessions', 'success', 1],
            [8, 'Permintaan Sesi Diterima', 'Kak Bintang Pratama menyetujui sesi belajar pada 15 Oktober 2026 di Gazebo Gedung C.', '/student/sessions', 'success', 0],
        ];
        $stmtNot = $pdo->prepare("INSERT INTO notifications (user_id, title, message, link, type, is_read) VALUES (?, ?, ?, ?, ?, ?)");
        foreach ($notifs as $n) {
            $stmtNot->execute($n);
        }

        // Activity Logs
        $logs = [
            [1, 'system_init', 'Database TemanBelajar berhasil diinisialisasi dan diisi data awal.', '127.0.0.1', 'Console/Seeder'],
            [2, 'tutor_profile_updated', 'Tutor Bintang Pratama memperbarui ketersediaan jadwal bimbingan.', '127.0.0.1', 'Mozilla/5.0'],
            [5, 'review_submitted', 'Dina Wahyuni mengirim ulasan rating 5 untuk sesi TB-202610-001.', '127.0.0.1', 'Mozilla/5.0'],
        ];
        $stmtLog = $pdo->prepare("INSERT INTO activity_logs (user_id, action, details, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)");
        foreach ($logs as $l) {
            $stmtLog->execute($l);
        }

        echo "Data demo SQLite berhasil diinjeksi!\n";
    }

    echo "\n[SUKSES] Database migration and seeding selesai secara sempurna!\n";
    echo "====================================================\n";

} catch (\Exception $e) {
    echo "\n[ERROR] " . $e->getMessage() . "\n";
    exit(1);
}
