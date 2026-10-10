<?php

namespace App\Models;

use App\Helpers\Database;

class Review
{
    public static function findById(int $id): ?array
    {
        return Database::queryOne("SELECT * FROM reviews WHERE id = ?", [$id]);
    }

    public static function findBySessionId(int $sessionId): ?array
    {
        return Database::queryOne("SELECT * FROM reviews WHERE study_session_id = ?", [$sessionId]);
    }

    public static function canUserReview(int $sessionId, int $studentId): array
    {
        $session = Database::queryOne(
            "SELECT id, student_id, tutor_id, status FROM study_sessions WHERE id = ?",
            [$sessionId]
        );

        if (!$session) {
            return ['allowed' => false, 'message' => 'Sesi belajar tidak ditemukan.'];
        }

        if ((int) $session['student_id'] !== $studentId) {
            return ['allowed' => false, 'message' => 'Anda tidak memiliki hak untuk mengulas sesi belajar ini.'];
        }

        if ($session['status'] !== 'completed') {
            return ['allowed' => false, 'message' => 'Ulasan hanya dapat diberikan setelah sesi belajar selesai dilaksanakan.'];
        }

        $existing = self::findBySessionId($sessionId);
        if ($existing) {
            return ['allowed' => false, 'message' => 'Anda sudah memberikan ulasan untuk sesi belajar ini.'];
        }

        return ['allowed' => true, 'session' => $session];
    }

    public static function create(array $data): int
    {
        $time = Database::getDriver() === 'sqlite' ? "datetime('now')" : "NOW()";

        Database::execute(
            "INSERT INTO reviews (study_session_id, student_id, tutor_id, rating, comment, is_visible, created_at)
             VALUES (?, ?, ?, ?, ?, 1, {$time})",
            [
                (int) $data['study_session_id'],
                (int) $data['student_id'],
                (int) $data['tutor_id'],
                (int) $data['rating'],
                trim($data['comment'])
            ]
        );

        return (int) Database::lastInsertId();
    }

    public static function getForTutor(int $tutorId, int $limit = 10): array
    {
        return Database::query(
            "SELECT r.*, stu.full_name AS student_name, stu.avatar AS student_avatar,
                    sub.name AS subject_name
             FROM reviews r
             JOIN users stu ON r.student_id = stu.id
             JOIN study_sessions ss ON r.study_session_id = ss.id
             JOIN study_subjects sub ON ss.study_subject_id = sub.id
             WHERE r.tutor_id = ? AND r.is_visible = 1
             ORDER BY r.created_at DESC
             LIMIT {$limit}",
            [$tutorId]
        );
    }

    public static function getAllAdmin(int $page = 1, int $perPage = 15): array
    {
        $offset = ($page - 1) * $perPage;
        return Database::query(
            "SELECT r.*, 
                    stu.full_name AS student_name, stu.nim AS student_nim,
                    tut.full_name AS tutor_name, tut.nim AS tutor_nim,
                    sub.name AS subject_name,
                    ss.session_code
             FROM reviews r
             JOIN users stu ON r.student_id = stu.id
             JOIN users tut ON r.tutor_id = tut.id
             JOIN study_sessions ss ON r.study_session_id = ss.id
             JOIN study_subjects sub ON ss.study_subject_id = sub.id
             ORDER BY r.id DESC
             LIMIT {$perPage} OFFSET {$offset}"
        );
    }

    public static function countAllAdmin(): int
    {
        return (int) Database::queryOne("SELECT COUNT(*) AS c FROM reviews")['c'];
    }

    public static function toggleVisibility(int $id): bool
    {
        return Database::execute(
            "UPDATE reviews SET is_visible = CASE WHEN is_visible = 1 THEN 0 ELSE 1 END WHERE id = ?",
            [$id]
        ) > 0;
    }

    public static function delete(int $id): bool
    {
        return Database::execute("DELETE FROM reviews WHERE id = ?", [$id]) > 0;
    }
}
