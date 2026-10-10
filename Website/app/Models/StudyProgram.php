<?php

namespace App\Models;

use App\Helpers\Database;

class StudyProgram
{
    public static function all(): array
    {
        return Database::query("SELECT * FROM study_programs ORDER BY name ASC");
    }

    public static function findById(int $id): ?array
    {
        return Database::queryOne("SELECT * FROM study_programs WHERE id = ?", [$id]);
    }

    public static function create(array $data): int
    {
        Database::execute(
            "INSERT INTO study_programs (code, name, faculty) VALUES (?, ?, ?)",
            [trim($data['code']), trim($data['name']), trim($data['faculty'])]
        );
        return (int) Database::lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        return Database::execute(
            "UPDATE study_programs SET code = ?, name = ?, faculty = ? WHERE id = ?",
            [trim($data['code']), trim($data['name']), trim($data['faculty']), $id]
        ) >= 0;
    }

    public static function delete(int $id): bool
    {
        return Database::execute("DELETE FROM study_programs WHERE id = ?", [$id]) > 0;
    }
}
