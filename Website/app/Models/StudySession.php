<?php

namespace App\Models;

use App\Helpers\Database;

class StudySession
{
    public static function findById(int $id): ?array
    {
        return Database::queryOne(
            "SELECT ss.*, 
                    stu.full_name AS student_name, stu.email AS student_email, stu.nim AS student_nim, stu.avatar AS student_avatar, stu.phone AS student_phone,
                    tut.full_name AS tutor_name, tut.email AS tutor_email, tut.nim AS tutor_nim, tut.avatar AS tutor_avatar, tut.phone AS tutor_phone,
                    sub.name AS subject_name, sub.code AS subject_code,
                    r.id AS review_id, r.rating, r.comment AS review_comment
             FROM study_sessions ss
             JOIN users stu ON ss.student_id = stu.id
             JOIN users tut ON ss.tutor_id = tut.id
             JOIN study_subjects sub ON ss.study_subject_id = sub.id
             LEFT JOIN reviews r ON r.study_session_id = ss.id
             WHERE ss.id = ?",
            [$id]
        );
    }

    public static function findByCode(string $code): ?array
    {
        return Database::queryOne(
            "SELECT ss.*, 
                    stu.full_name AS student_name,
                    tut.full_name AS tutor_name,
                    sub.name AS subject_name, sub.code AS subject_code
             FROM study_sessions ss
             JOIN users stu ON ss.student_id = stu.id
             JOIN users tut ON ss.tutor_id = tut.id
             JOIN study_subjects sub ON ss.study_subject_id = sub.id
             WHERE ss.session_code = ?",
            [trim($code)]
        );
    }

    /**
     * Check if a tutor already has an accepted session conflicting with the specified time slot.
     * Overlap formula: (existing.start_time < new_end_time AND existing.end_time > new_start_time)
     */
    public static function hasCollision(int $tutorId, string $sessionDate, string $startTime, string $endTime, ?int $excludeSessionId = null): bool
    {
        $sql = "SELECT id, session_code, start_time, end_time 
                FROM study_sessions 
                WHERE tutor_id = ? 
                  AND session_date = ? 
                  AND status = 'accepted'
                  AND (start_time < ? AND end_time > ?)";
        
        $params = [$tutorId, $sessionDate, $endTime, $startTime];

        if ($excludeSessionId !== null) {
            $sql .= " AND id != ?";
            $params[] = $excludeSessionId;
        }

        $conflicts = Database::query($sql, $params);
        return !empty($conflicts);
    }

    public static function create(array $data): int
    {
        // Generate unique code: TB-YYYYMM-XXXX
        $datePrefix = date('Ym');
        $randomSuffix = strtoupper(substr(bin2hex(random_bytes(3)), 0, 4));
        $sessionCode = "TB-{$datePrefix}-{$randomSuffix}";

        $time = Database::getDriver() === 'sqlite' ? "datetime('now')" : "NOW()";

        Database::execute(
            "INSERT INTO study_sessions (
                session_code, student_id, tutor_id, study_subject_id, session_date,
                start_time, end_time, duration_minutes, learning_method, location_or_link,
                notes, status, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', {$time}, {$time})",
            [
                $sessionCode,
                (int) $data['student_id'],
                (int) $data['tutor_id'],
                (int) $data['study_subject_id'],
                $data['session_date'],
                $data['start_time'],
                $data['end_time'],
                (int) ($data['duration_minutes'] ?? 60),
                $data['learning_method'],
                $data['location_or_link'] ?? null,
                $data['notes'] ?? null
            ]
        );

        return (int) Database::lastInsertId();
    }

    public static function updateStatus(int $id, string $status, ?string $reason = null): bool
    {
        $time = Database::getDriver() === 'sqlite' ? "datetime('now')" : "NOW()";
        return Database::execute(
            "UPDATE study_sessions SET status = ?, rejection_reason = ?, updated_at = {$time} WHERE id = ?",
            [$status, $reason, $id]
        ) > 0;
    }

    public static function getForStudent(int $studentId, ?string $status = null, int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        $params = [$studentId];
        $where = "ss.student_id = ?";

        if (!empty($status) && $status !== 'all') {
            $where .= " AND ss.status = ?";
            $params[] = $status;
        }

        $sql = "SELECT ss.*, 
                       tut.full_name AS tutor_name, tut.email AS tutor_email, tut.avatar AS tutor_avatar, tut.phone AS tutor_phone,
                       sub.name AS subject_name, sub.code AS subject_code,
                       r.id AS review_id, r.rating AS review_rating
                FROM study_sessions ss
                JOIN users tut ON ss.tutor_id = tut.id
                JOIN study_subjects sub ON ss.study_subject_id = sub.id
                LEFT JOIN reviews r ON r.study_session_id = ss.id
                WHERE {$where}
                ORDER BY ss.session_date DESC, ss.start_time DESC
                LIMIT {$perPage} OFFSET {$offset}";

        return Database::query($sql, $params);
    }

    public static function countForStudent(int $studentId, ?string $status = null): int
    {
        $params = [$studentId];
        $where = "student_id = ?";

        if (!empty($status) && $status !== 'all') {
            $where .= " AND status = ?";
            $params[] = $status;
        }

        $stmt = Database::getConnection()->prepare("SELECT COUNT(*) FROM study_sessions WHERE {$where}");
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    public static function getForTutor(int $tutorId, ?string $status = null, int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        $params = [$tutorId];
        $where = "ss.tutor_id = ?";

        if (!empty($status) && $status !== 'all') {
            $where .= " AND ss.status = ?";
            $params[] = $status;
        }

        $sql = "SELECT ss.*, 
                       stu.full_name AS student_name, stu.email AS student_email, stu.avatar AS student_avatar, stu.phone AS student_phone, stu.nim AS student_nim,
                       sub.name AS subject_name, sub.code AS subject_code,
                       r.rating AS review_rating, r.comment AS review_comment
                FROM study_sessions ss
                JOIN users stu ON ss.student_id = stu.id
                JOIN study_subjects sub ON ss.study_subject_id = sub.id
                LEFT JOIN reviews r ON r.study_session_id = ss.id
                WHERE {$where}
                ORDER BY ss.session_date DESC, ss.start_time DESC
                LIMIT {$perPage} OFFSET {$offset}";

        return Database::query($sql, $params);
    }

    public static function countForTutor(int $tutorId, ?string $status = null): int
    {
        $params = [$tutorId];
        $where = "tutor_id = ?";

        if (!empty($status) && $status !== 'all') {
            $where .= " AND status = ?";
            $params[] = $status;
        }

        $stmt = Database::getConnection()->prepare("SELECT COUNT(*) FROM study_sessions WHERE {$where}");
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    public static function getAllAdmin(array $filters = [], int $page = 1, int $perPage = 15): array
    {
        $offset = ($page - 1) * $perPage;
        $params = [];
        $where = ["1=1"];

        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $where[] = "ss.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['search'])) {
            $s = '%' . trim($filters['search']) . '%';
            $where[] = "(ss.session_code LIKE ? OR stu.full_name LIKE ? OR tut.full_name LIKE ? OR sub.name LIKE ?)";
            $params[] = $s;
            $params[] = $s;
            $params[] = $s;
            $params[] = $s;
        }

        $whereClause = implode(' AND ', $where);

        $sql = "SELECT ss.*, 
                       stu.full_name AS student_name, stu.nim AS student_nim,
                       tut.full_name AS tutor_name, tut.nim AS tutor_nim,
                       sub.name AS subject_name, sub.code AS subject_code
                FROM study_sessions ss
                JOIN users stu ON ss.student_id = stu.id
                JOIN users tut ON ss.tutor_id = tut.id
                JOIN study_subjects sub ON ss.study_subject_id = sub.id
                WHERE {$whereClause}
                ORDER BY ss.id DESC
                LIMIT {$perPage} OFFSET {$offset}";

        return Database::query($sql, $params);
    }

    public static function countAllAdmin(array $filters = []): int
    {
        $params = [];
        $where = ["1=1"];

        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $where[] = "ss.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['search'])) {
            $s = '%' . trim($filters['search']) . '%';
            $where[] = "(ss.session_code LIKE ? OR stu.full_name LIKE ? OR tut.full_name LIKE ? OR sub.name LIKE ?)";
            $params[] = $s;
            $params[] = $s;
            $params[] = $s;
            $params[] = $s;
        }

        $whereClause = implode(' AND ', $where);

        $sql = "SELECT COUNT(*)
                FROM study_sessions ss
                JOIN users stu ON ss.student_id = stu.id
                JOIN users tut ON ss.tutor_id = tut.id
                JOIN study_subjects sub ON ss.study_subject_id = sub.id
                WHERE {$whereClause}";

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    public static function getStudentStats(int $studentId): array
    {
        return [
            'total_requests' => (int) Database::queryOne("SELECT COUNT(*) AS c FROM study_sessions WHERE student_id = ?", [$studentId])['c'],
            'pending_requests' => (int) Database::queryOne("SELECT COUNT(*) AS c FROM study_sessions WHERE student_id = ? AND status = 'pending'", [$studentId])['c'],
            'upcoming_sessions' => (int) Database::queryOne("SELECT COUNT(*) AS c FROM study_sessions WHERE student_id = ? AND status = 'accepted' AND session_date >= date('now')", [$studentId])['c'],
            'completed_sessions' => (int) Database::queryOne("SELECT COUNT(*) AS c FROM study_sessions WHERE student_id = ? AND status = 'completed'", [$studentId])['c'],
        ];
    }

    public static function getTutorStats(int $tutorId): array
    {
        return [
            'total_requests' => (int) Database::queryOne("SELECT COUNT(*) AS c FROM study_sessions WHERE tutor_id = ?", [$tutorId])['c'],
            'pending_requests' => (int) Database::queryOne("SELECT COUNT(*) AS c FROM study_sessions WHERE tutor_id = ? AND status = 'pending'", [$tutorId])['c'],
            'upcoming_sessions' => (int) Database::queryOne("SELECT COUNT(*) AS c FROM study_sessions WHERE tutor_id = ? AND status = 'accepted' AND session_date >= date('now')", [$tutorId])['c'],
            'completed_sessions' => (int) Database::queryOne("SELECT COUNT(*) AS c FROM study_sessions WHERE tutor_id = ? AND status = 'completed'", [$tutorId])['c'],
            'avg_rating' => round((float) (Database::queryOne("SELECT AVG(rating) AS a FROM reviews WHERE tutor_id = ? AND is_visible = 1", [$tutorId])['a'] ?? 0), 1),
            'total_reviews' => (int) Database::queryOne("SELECT COUNT(*) AS c FROM reviews WHERE tutor_id = ? AND is_visible = 1", [$tutorId])['c'],
        ];
    }

    public static function getPlatformStats(): array
    {
        return [
            'total_students' => (int) Database::queryOne("SELECT COUNT(*) AS c FROM users WHERE role = 'student' AND is_active = 1")['c'],
            'total_tutors' => (int) Database::queryOne("SELECT COUNT(*) AS c FROM users WHERE is_tutor = 1 AND is_active = 1")['c'],
            'total_subjects' => (int) Database::queryOne("SELECT COUNT(*) AS c FROM study_subjects")['c'],
            'total_sessions_completed' => (int) Database::queryOne("SELECT COUNT(*) AS c FROM study_sessions WHERE status = 'completed'")['c'],
            'total_reviews' => (int) Database::queryOne("SELECT COUNT(*) AS c FROM reviews WHERE is_visible = 1")['c'],
            'avg_satisfaction' => round((float) (Database::queryOne("SELECT AVG(rating) AS a FROM reviews WHERE is_visible = 1")['a'] ?? 5.0), 1),
        ];
    }
}
