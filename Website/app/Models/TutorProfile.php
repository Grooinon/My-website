<?php

namespace App\Models;

use App\Helpers\Database;

class TutorProfile
{
    public static function findById(int $id): ?array
    {
        return Database::queryOne(
            "SELECT tp.*, u.full_name, u.email, u.nim, u.avatar, u.graduation_year, u.phone,
                    sp.name AS program_name, sp.code AS program_code, sp.faculty
             FROM tutor_profiles tp
             JOIN users u ON tp.user_id = u.id
             LEFT JOIN study_programs sp ON u.study_program_id = sp.id
             WHERE tp.id = ? AND u.is_active = 1",
            [$id]
        );
    }

    public static function findByUserId(int $userId): ?array
    {
        return Database::queryOne("SELECT * FROM tutor_profiles WHERE user_id = ?", [$userId]);
    }

    public static function updateOrCreate(int $userId, array $data): int
    {
        $existing = self::findByUserId($userId);
        $time = Database::getDriver() === 'sqlite' ? "datetime('now')" : "NOW()";

        $hourlyRate = (float) ($data['hourly_rate'] ?? 0);
        $isFree = !empty($data['is_free']) || $hourlyRate <= 0 ? 1 : 0;
        if ($isFree) {
            $hourlyRate = 0.00;
        }

        if ($existing) {
            Database::execute(
                "UPDATE tutor_profiles SET
                    headline = ?, bio = ?, academic_achievements = ?, teaching_experience = ?,
                    preferred_method = ?, hourly_rate = ?, is_free = ?, is_available = ?,
                    updated_at = {$time}
                 WHERE user_id = ?",
                [
                    $data['headline'] ?? '',
                    $data['bio'] ?? '',
                    $data['academic_achievements'] ?? '',
                    $data['teaching_experience'] ?? '',
                    $data['preferred_method'] ?? 'both',
                    $hourlyRate,
                    $isFree,
                    isset($data['is_available']) ? (int) $data['is_available'] : 1,
                    $userId
                ]
            );
            return (int) $existing['id'];
        }

        Database::execute(
            "INSERT INTO tutor_profiles (
                user_id, headline, bio, academic_achievements, teaching_experience,
                preferred_method, hourly_rate, is_free, is_available, is_verified, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1, {$time}, {$time})",
            [
                $userId,
                $data['headline'] ?? '',
                $data['bio'] ?? '',
                $data['academic_achievements'] ?? '',
                $data['teaching_experience'] ?? '',
                $data['preferred_method'] ?? 'both',
                $hourlyRate,
                $isFree,
                isset($data['is_available']) ? (int) $data['is_available'] : 1
            ]
        );

        $id = (int) Database::lastInsertId();

        // Update user is_tutor flag
        User::setTutorStatus($userId, true);

        return $id;
    }

    public static function getSubjects(int $tutorProfileId): array
    {
        return Database::query(
            "SELECT ts.*, s.name AS subject_name, s.code AS subject_code, s.semester,
                    sp.name AS program_name
             FROM tutor_subjects ts
             JOIN study_subjects s ON ts.study_subject_id = s.id
             JOIN study_programs sp ON s.study_program_id = sp.id
             WHERE ts.tutor_profile_id = ?
             ORDER BY s.name ASC",
            [$tutorProfileId]
        );
    }

    public static function addSubject(int $tutorProfileId, int $subjectId, string $grade = 'A', ?string $notes = null): bool
    {
        try {
            $sql = "INSERT INTO tutor_subjects (tutor_profile_id, study_subject_id, grade, notes) VALUES (?, ?, ?, ?)";
            return Database::execute($sql, [$tutorProfileId, $subjectId, $grade, $notes]) > 0;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public static function removeSubject(int $tutorProfileId, int $subjectId): bool
    {
        return Database::execute(
            "DELETE FROM tutor_subjects WHERE tutor_profile_id = ? AND study_subject_id = ?",
            [$tutorProfileId, $subjectId]
        ) > 0;
    }

    public static function getAvailability(int $tutorProfileId): array
    {
        return Database::query(
            "SELECT * FROM tutor_availability WHERE tutor_profile_id = ? AND is_active = 1 ORDER BY 
             CASE day_of_week
                WHEN 'Senin' THEN 1
                WHEN 'Selasa' THEN 2
                WHEN 'Rabu' THEN 3
                WHEN 'Kamis' THEN 4
                WHEN 'Jumat' THEN 5
                WHEN 'Sabtu' THEN 6
                WHEN 'Minggu' THEN 7
             END, start_time ASC",
            [$tutorProfileId]
        );
    }

    public static function setAvailability(int $tutorProfileId, array $slots): void
    {
        Database::execute("DELETE FROM tutor_availability WHERE tutor_profile_id = ?", [$tutorProfileId]);

        $stmt = Database::getConnection()->prepare(
            "INSERT INTO tutor_availability (tutor_profile_id, day_of_week, start_time, end_time, is_active)
             VALUES (?, ?, ?, ?, 1)"
        );

        foreach ($slots as $slot) {
            if (!empty($slot['day_of_week']) && !empty($slot['start_time']) && !empty($slot['end_time'])) {
                $stmt->execute([
                    $tutorProfileId,
                    $slot['day_of_week'],
                    $slot['start_time'],
                    $slot['end_time']
                ]);
            }
        }
    }

    public static function getFeaturedTutors(int $limit = 4): array
    {
        $sql = "SELECT tp.*, u.full_name, u.avatar, u.graduation_year,
                       sp.name AS program_name, sp.code AS program_code,
                       COALESCE(AVG(r.rating), 0) AS avg_rating,
                       COUNT(r.id) AS review_count
                FROM tutor_profiles tp
                JOIN users u ON tp.user_id = u.id
                LEFT JOIN study_programs sp ON u.study_program_id = sp.id
                LEFT JOIN reviews r ON r.tutor_id = u.id AND r.is_visible = 1
                WHERE u.is_active = 1 AND tp.is_available = 1
                GROUP BY tp.id
                ORDER BY avg_rating DESC, review_count DESC, tp.id DESC
                LIMIT {$limit}";

        $tutors = Database::query($sql);
        foreach ($tutors as &$tutor) {
            $tutor['subjects'] = self::getSubjects((int) $tutor['id']);
        }
        return $tutors;
    }

    public static function getAllWithFilters(array $filters = [], int $page = 1, int $perPage = 9): array
    {
        $offset = ($page - 1) * $perPage;
        $params = [];

        $where = ["u.is_active = 1", "u.is_tutor = 1"];

        if (!empty($filters['search'])) {
            $search = '%' . trim($filters['search']) . '%';
            $where[] = "(u.full_name LIKE ? OR tp.headline LIKE ? OR tp.bio LIKE ? OR EXISTS (
                SELECT 1 FROM tutor_subjects ts2 
                JOIN study_subjects ss2 ON ts2.study_subject_id = ss2.id 
                WHERE ts2.tutor_profile_id = tp.id AND (ss2.name LIKE ? OR ss2.code LIKE ?)
            ))";
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }

        if (!empty($filters['program_id'])) {
            $where[] = "u.study_program_id = ?";
            $params[] = (int) $filters['program_id'];
        }

        if (!empty($filters['subject_id'])) {
            $where[] = "EXISTS (SELECT 1 FROM tutor_subjects ts3 WHERE ts3.tutor_profile_id = tp.id AND ts3.study_subject_id = ?)";
            $params[] = (int) $filters['subject_id'];
        }

        if (!empty($filters['method']) && $filters['method'] !== 'all') {
            $where[] = "(tp.preferred_method = ? OR tp.preferred_method = 'both')";
            $params[] = $filters['method'];
        }

        if (isset($filters['price_type']) && $filters['price_type'] !== '') {
            if ($filters['price_type'] === 'free') {
                $where[] = "tp.is_free = 1";
            } elseif ($filters['price_type'] === 'paid') {
                $where[] = "tp.is_free = 0";
            }
        }

        if (isset($filters['available']) && $filters['available'] !== '') {
            $where[] = "tp.is_available = ?";
            $params[] = (int) $filters['available'];
        }

        $whereClause = implode(' AND ', $where);

        // Sorting options
        $orderBy = "avg_rating DESC, review_count DESC, tp.id DESC";
        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'rating_desc':
                    $orderBy = "avg_rating DESC, review_count DESC";
                    break;
                case 'name_asc':
                    $orderBy = "u.full_name ASC";
                    break;
                case 'price_asc':
                    $orderBy = "tp.hourly_rate ASC";
                    break;
                case 'newest':
                    $orderBy = "tp.id DESC";
                    break;
            }
        }

        $havingClause = "";
        if (!empty($filters['min_rating'])) {
            $minRating = (float) $filters['min_rating'];
            $havingClause = "HAVING avg_rating >= {$minRating}";
        }

        $sql = "SELECT tp.*, u.full_name, u.avatar, u.graduation_year,
                       sp.name AS program_name, sp.code AS program_code,
                       COALESCE(AVG(r.rating), 0) AS avg_rating,
                       COUNT(r.id) AS review_count
                FROM tutor_profiles tp
                JOIN users u ON tp.user_id = u.id
                LEFT JOIN study_programs sp ON u.study_program_id = sp.id
                LEFT JOIN reviews r ON r.tutor_id = u.id AND r.is_visible = 1
                WHERE {$whereClause}
                GROUP BY tp.id
                {$havingClause}
                ORDER BY {$orderBy}
                LIMIT {$perPage} OFFSET {$offset}";

        $tutors = Database::query($sql, $params);

        foreach ($tutors as &$tutor) {
            $tutor['subjects'] = self::getSubjects((int) $tutor['id']);
            $tutor['availability'] = self::getAvailability((int) $tutor['id']);
        }

        return $tutors;
    }

    public static function countWithFilters(array $filters = []): int
    {
        $params = [];
        $where = ["u.is_active = 1", "u.is_tutor = 1"];

        if (!empty($filters['search'])) {
            $search = '%' . trim($filters['search']) . '%';
            $where[] = "(u.full_name LIKE ? OR tp.headline LIKE ? OR tp.bio LIKE ? OR EXISTS (
                SELECT 1 FROM tutor_subjects ts2 
                JOIN study_subjects ss2 ON ts2.study_subject_id = ss2.id 
                WHERE ts2.tutor_profile_id = tp.id AND (ss2.name LIKE ? OR ss2.code LIKE ?)
            ))";
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }

        if (!empty($filters['program_id'])) {
            $where[] = "u.study_program_id = ?";
            $params[] = (int) $filters['program_id'];
        }

        if (!empty($filters['subject_id'])) {
            $where[] = "EXISTS (SELECT 1 FROM tutor_subjects ts3 WHERE ts3.tutor_profile_id = tp.id AND ts3.study_subject_id = ?)";
            $params[] = (int) $filters['subject_id'];
        }

        if (!empty($filters['method']) && $filters['method'] !== 'all') {
            $where[] = "(tp.preferred_method = ? OR tp.preferred_method = 'both')";
            $params[] = $filters['method'];
        }

        if (isset($filters['price_type']) && $filters['price_type'] !== '') {
            if ($filters['price_type'] === 'free') {
                $where[] = "tp.is_free = 1";
            } elseif ($filters['price_type'] === 'paid') {
                $where[] = "tp.is_free = 0";
            }
        }

        if (isset($filters['available']) && $filters['available'] !== '') {
            $where[] = "tp.is_available = ?";
            $params[] = (int) $filters['available'];
        }

        $whereClause = implode(' AND ', $where);

        $sql = "SELECT COUNT(DISTINCT tp.id) FROM tutor_profiles tp
                JOIN users u ON tp.user_id = u.id
                LEFT JOIN study_programs sp ON u.study_program_id = sp.id
                WHERE {$whereClause}";

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }
}
