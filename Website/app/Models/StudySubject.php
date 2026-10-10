<?php

namespace App\Models;

use App\Helpers\Database;

class StudySubject
{
    public static function all(): array
    {
        return Database::query(
            "SELECT s.*, sp.name AS program_name, sp.code AS program_code
             FROM study_subjects s
             JOIN study_programs sp ON s.study_program_id = sp.id
             ORDER BY sp.name ASC, s.semester ASC, s.name ASC"
        );
    }

    public static function findById(int $id): ?array
    {
        return Database::queryOne(
            "SELECT s.*, sp.name AS program_name, sp.code AS program_code
             FROM study_subjects s
             JOIN study_programs sp ON s.study_program_id = sp.id
             WHERE s.id = ?",
            [$id]
        );
    }

    public static function getByProgramId(int $programId): array
    {
        return Database::query(
            "SELECT * FROM study_subjects WHERE study_program_id = ? ORDER BY semester ASC, name ASC",
            [$programId]
        );
    }

    public static function getPopularSubjects(int $limit = 6): array
    {
        return Database::query(
            "SELECT s.*, sp.name AS program_name, sp.code AS program_code,
                    COUNT(DISTINCT ts.tutor_profile_id) AS tutor_count,
                    COUNT(DISTINCT ss.id) AS session_count
             FROM study_subjects s
             JOIN study_programs sp ON s.study_program_id = sp.id
             LEFT JOIN tutor_subjects ts ON s.id = ts.study_subject_id
             LEFT JOIN study_sessions ss ON s.id = ss.study_subject_id
             GROUP BY s.id
             ORDER BY tutor_count DESC, session_count DESC, s.name ASC
             LIMIT {$limit}"
        );
    }

    public static function create(array $data): int
    {
        Database::execute(
            "INSERT INTO study_subjects (study_program_id, code, name, semester, description) VALUES (?, ?, ?, ?, ?)",
            [
                (int) $data['study_program_id'],
                trim($data['code']),
                trim($data['name']),
                (int) $data['semester'],
                trim($data['description'] ?? '')
            ]
        );
        return (int) Database::lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        return Database::execute(
            "UPDATE study_subjects SET study_program_id = ?, code = ?, name = ?, semester = ?, description = ? WHERE id = ?",
            [
                (int) $data['study_program_id'],
                trim($data['code']),
                trim($data['name']),
                (int) $data['semester'],
                trim($data['description'] ?? ''),
                $id
            ]
        ) >= 0;
    }

    public static function delete(int $id): bool
    {
        return Database::execute("DELETE FROM study_subjects WHERE id = ?", [$id]) > 0;
    }
}
