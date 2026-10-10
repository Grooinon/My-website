<?php

namespace App\Models;

use App\Helpers\Database;

class StudentProfile
{
    public static function findByUserId(int $userId): ?array
    {
        return Database::queryOne("SELECT * FROM student_profiles WHERE user_id = ?", [$userId]);
    }

    public static function updateOrCreate(int $userId, array $data): bool
    {
        $existing = self::findByUserId($userId);
        $time = Database::getDriver() === 'sqlite' ? "datetime('now')" : "NOW()";

        if ($existing) {
            return Database::execute(
                "UPDATE student_profiles SET bio = ?, interest_topics = ?, updated_at = {$time} WHERE user_id = ?",
                [$data['bio'] ?? null, $data['interest_topics'] ?? null, $userId]
            ) >= 0;
        }

        return Database::execute(
            "INSERT INTO student_profiles (user_id, bio, interest_topics, created_at) VALUES (?, ?, ?, {$time})",
            [$userId, $data['bio'] ?? null, $data['interest_topics'] ?? null]
        ) > 0;
    }
}
