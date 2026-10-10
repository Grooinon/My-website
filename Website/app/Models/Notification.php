<?php

namespace App\Models;

use App\Helpers\Database;

class Notification
{
    public static function create(int $userId, string $title, string $message, ?string $link = null, string $type = 'info'): int
    {
        $time = Database::getDriver() === 'sqlite' ? "datetime('now')" : "NOW()";

        Database::execute(
            "INSERT INTO notifications (user_id, title, message, link, type, is_read, created_at)
             VALUES (?, ?, ?, ?, ?, 0, {$time})",
            [$userId, $title, $message, $link, $type]
        );

        return (int) Database::lastInsertId();
    }

    public static function getForUser(int $userId, int $limit = 15): array
    {
        return Database::query(
            "SELECT * FROM notifications WHERE user_id = ? ORDER BY id DESC LIMIT {$limit}",
            [$userId]
        );
    }

    public static function getUnreadCount(int $userId): int
    {
        return (int) Database::queryOne(
            "SELECT COUNT(*) AS c FROM notifications WHERE user_id = ? AND is_read = 0",
            [$userId]
        )['c'];
    }

    public static function markAsRead(int $id, int $userId): bool
    {
        return Database::execute(
            "UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?",
            [$id, $userId]
        ) > 0;
    }

    public static function markAllAsRead(int $userId): bool
    {
        return Database::execute(
            "UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0",
            [$userId]
        ) >= 0;
    }
}
