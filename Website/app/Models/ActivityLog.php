<?php

namespace App\Models;

use App\Helpers\Database;
use App\Helpers\Auth;

class ActivityLog
{
    public static function log(?int $userId, string $action, ?string $details = null): void
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $ua = substr($_SERVER['HTTP_USER_AGENT'] ?? 'CLI', 0, 255);
        $time = Database::getDriver() === 'sqlite' ? "datetime('now')" : "NOW()";

        if ($userId === null && Auth::check()) {
            $userId = Auth::id();
        }

        try {
            Database::execute(
                "INSERT INTO activity_logs (user_id, action, details, ip_address, user_agent, created_at)
                 VALUES (?, ?, ?, ?, ?, {$time})",
                [$userId, $action, $details, $ip, $ua]
            );
        } catch (\Exception $e) {
            // Fail silently so activity logging never breaks user requests
        }
    }

    public static function getLatest(int $page = 1, int $perPage = 20): array
    {
        $offset = ($page - 1) * $perPage;
        return Database::query(
            "SELECT al.*, u.full_name, u.email, u.role
             FROM activity_logs al
             LEFT JOIN users u ON al.user_id = u.id
             ORDER BY al.id DESC
             LIMIT {$perPage} OFFSET {$offset}"
        );
    }

    public static function countAll(): int
    {
        return (int) Database::queryOne("SELECT COUNT(*) AS c FROM activity_logs")['c'];
    }
}
