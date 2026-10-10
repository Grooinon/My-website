<?php

require_once dirname(__DIR__) . '/app/bootstrap.php';

use App\Helpers\Database;
use App\Helpers\Sanitizer;

echo "====================================================\n";
echo " TemanBelajar - CLI Pembuatan Akun Admin Pertama\n";
echo "====================================================\n\n";

$options = getopt('', ['name:', 'email:', 'password:', 'nim:']);

$name = $options['name'] ?? null;
$email = $options['email'] ?? null;
$password = $options['password'] ?? null;
$nim = $options['nim'] ?? null;

if (!$name) {
    echo "Nama Lengkap Admin : ";
    $name = trim(fgets(STDIN));
}

if (!$email) {
    echo "Email Admin        : ";
    $email = trim(fgets(STDIN));
}

if (!$password) {
    echo "Password (min 8 kr): ";
    $password = trim(fgets(STDIN));
}

if (!$nim) {
    echo "NIM / ID Admin     : ";
    $nim = trim(fgets(STDIN));
}

if (empty($name) || empty($email) || empty($password) || empty($nim)) {
    echo "\n[ERROR] Semua kolom (nama, email, password, nim) wajib diisi!\n";
    exit(1);
}

if (!Sanitizer::isValidEmail($email)) {
    echo "\n[ERROR] Format email tidak valid: {$email}\n";
    exit(1);
}

if (strlen($password) < 6) {
    echo "\n[ERROR] Password minimal 6 karakter!\n";
    exit(1);
}

try {
    $existing = Database::queryOne("SELECT id FROM users WHERE email = ? OR nim = ?", [$email, $nim]);
    if ($existing) {
        echo "\n[ERROR] Email atau NIM/ID Admin sudah terdaftar di sistem!\n";
        exit(1);
    }

    $hash = password_hash($password, PASSWORD_BCRYPT);
    $driver = Database::getDriver();

    Database::execute(
        "INSERT INTO users (full_name, email, password, nim, role, is_tutor, is_active, graduation_year, created_at)
         VALUES (?, ?, ?, ?, 'admin', 0, 1, ?, datetime('now'))",
        [$name, $email, $hash, $nim, (int)date('Y')]
    );

    $adminId = Database::lastInsertId();

    // Create student profile record for consistency
    Database::execute(
        "INSERT INTO student_profiles (user_id, bio, interest_topics) VALUES (?, 'Administrator Utama Sistem TemanBelajar.', 'Manajemen Sistem')",
        [$adminId]
    );

    // Log activity
    Database::execute(
        "INSERT INTO activity_logs (user_id, action, details, ip_address, user_agent) VALUES (?, 'admin_created_cli', 'Akun admin berhasil dibuat via CLI', '127.0.0.1', 'CLI')",
        [$adminId]
    );

    echo "\n[SUKSES] Akun Admin berhasil dibuat!\n";
    echo "ID       : {$adminId}\n";
    echo "Nama     : {$name}\n";
    echo "Email    : {$email}\n";
    echo "Role     : admin\n";
    echo "Silakan login di halaman web /login\n\n";

} catch (\Exception $e) {
    echo "\n[ERROR] Terjadi kegagalan saat membuat akun admin: " . $e->getMessage() . "\n";
    exit(1);
}
