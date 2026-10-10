<?php

namespace App\Models;

use App\Helpers\Database;

class User
{
    public static function findById(int $id): ?array
    {
        return Database::queryOne(
            "SELECT u.*, sp.name AS program_name, sp.code AS program_code, sp.faculty
             FROM users u
             LEFT JOIN study_programs sp ON u.study_program_id = sp.id
             WHERE u.id = ?",
            [$id]
        );
    }

    public static function findByEmail(string $email): ?array
    {
        return Database::queryOne(
            "SELECT u.*, sp.name AS program_name
             FROM users u
             LEFT JOIN study_programs sp ON u.study_program_id = sp.id
             WHERE LOWER(u.email) = LOWER(?)",
            [trim($email)]
        );
    }

    public static function findByNim(string $nim): ?array
    {
        return Database::queryOne("SELECT * FROM users WHERE nim = ?", [trim($nim)]);
    }

    public static function create(array $data): int
    {
        Database::execute(
            "INSERT INTO users (full_name, email, password, nim, study_program_id, graduation_year, role, is_tutor, is_active, phone, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, ?, " . (Database::getDriver() === 'sqlite' ? "datetime('now')" : "NOW()") . ")",
            [
                $data['full_name'],
                $data['email'],
                password_hash($data['password'], PASSWORD_BCRYPT),
                $data['nim'],
                !empty($data['study_program_id']) ? (int) $data['study_program_id'] : null,
                (int) $data['graduation_year'],
                $data['role'] ?? 'student',
                !empty($data['is_tutor']) ? 1 : 0,
                $data['phone'] ?? null
            ]
        );

        return (int) Database::lastInsertId();
    }

    public static function updateProfile(int $id, array $data): bool
    {
        $fields = [
            'full_name = ?',
            'phone = ?',
            'study_program_id = ?',
            'graduation_year = ?'
        ];
        $params = [
            $data['full_name'],
            $data['phone'] ?? null,
            !empty($data['study_program_id']) ? (int) $data['study_program_id'] : null,
            (int) $data['graduation_year']
        ];

        if (isset($data['avatar'])) {
            $fields[] = 'avatar = ?';
            $params[] = $data['avatar'];
        }

        if (Database::getDriver() === 'sqlite') {
            $fields[] = "updated_at = datetime('now')";
        } else {
            $fields[] = "updated_at = NOW()";
        }

        $params[] = $id;

        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
        return Database::execute($sql, $params) >= 0;
    }

    public static function changePassword(int $id, string $newPassword): bool
    {
        $hash = password_hash($newPassword, PASSWORD_BCRYPT);
        $timeUpdate = Database::getDriver() === 'sqlite' ? "datetime('now')" : "NOW()";
        return Database::execute("UPDATE users SET password = ?, updated_at = {$timeUpdate} WHERE id = ?", [$hash, $id]) > 0;
    }

    public static function setTutorStatus(int $id, bool $isTutor): bool
    {
        $val = $isTutor ? 1 : 0;
        return Database::execute("UPDATE users SET is_tutor = ? WHERE id = ?", [$val, $id]) >= 0;
    }

    public static function toggleActiveStatus(int $id): bool
    {
        return Database::execute(
            "UPDATE users SET is_active = CASE WHEN is_active = 1 THEN 0 ELSE 1 END WHERE id = ?",
            [$id]
        ) > 0;
    }

    public static function countAll(array $filters = []): int
    {
        $sql = "SELECT COUNT(*) FROM users WHERE 1=1";
        $params = [];

        if (!empty($filters['role'])) {
            $sql .= " AND role = ?";
            $params[] = $filters['role'];
        }

        if (isset($filters['is_tutor']) && $filters['is_tutor'] !== '') {
            $sql .= " AND is_tutor = ?";
            $params[] = (int) $filters['is_tutor'];
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $sql .= " AND is_active = ?";
            $params[] = (int) $filters['is_active'];
        }

        if (!empty($filters['search'])) {
            $search = '%' . $filters['search'] . '%';
            $sql .= " AND (full_name LIKE ? OR email LIKE ? OR nim LIKE ?)";
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    public static function getAll(array $filters = [], int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT u.*, sp.name AS program_name, sp.code AS program_code
                FROM users u
                LEFT JOIN study_programs sp ON u.study_program_id = sp.id
                WHERE 1=1";
        $params = [];

        if (!empty($filters['role'])) {
            $sql .= " AND u.role = ?";
            $params[] = $filters['role'];
        }

        if (isset($filters['is_tutor']) && $filters['is_tutor'] !== '') {
            $sql .= " AND u.is_tutor = ?";
            $params[] = (int) $filters['is_tutor'];
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $sql .= " AND u.is_active = ?";
            $params[] = (int) $filters['is_active'];
        }

        if (!empty($filters['search'])) {
            $search = '%' . $filters['search'] . '%';
            $sql .= " AND (u.full_name LIKE ? OR u.email LIKE ? OR u.nim LIKE ?)";
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }

        $sql .= " ORDER BY u.id DESC LIMIT {$perPage} OFFSET {$offset}";

        return Database::query($sql, $params);
    }
}
