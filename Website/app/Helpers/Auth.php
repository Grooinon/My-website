<?php

namespace App\Helpers;

class Auth
{
    private static ?array $cachedUser = null;

    public static function check(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return !empty($_SESSION['user_id']);
    }

    public static function id(): ?int
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['user_id'] ?? null;
    }

    public static function user(): ?array
    {
        if (!self::check()) {
            return null;
        }

        if (self::$cachedUser !== null) {
            return self::$cachedUser;
        }

        $userId = self::id();
        $user = Database::queryOne(
            "SELECT u.*, sp.name as program_name, sp.code as program_code 
             FROM users u 
             LEFT JOIN study_programs sp ON u.study_program_id = sp.id 
             WHERE u.id = ? AND u.is_active = 1",
            [$userId]
        );

        if (!$user) {
            self::logout();
            return null;
        }

        self::$cachedUser = $user;
        return $user;
    }

    public static function role(): ?string
    {
        $user = self::user();
        return $user['role'] ?? null;
    }

    public static function isAdmin(): bool
    {
        return self::role() === 'admin';
    }

    public static function isTutor(): bool
    {
        $user = self::user();
        return !empty($user['is_tutor']);
    }

    public static function login(array $user): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Regenerate session ID to prevent session fixation attacks
        if (!headers_sent()) {
            session_regenerate_id(true);
        }

        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['user_role'] = $user['role'] ?? 'student';
        $_SESSION['user_name'] = $user['full_name'] ?? '';
        $_SESSION['user_email'] = $user['email'] ?? '';
        $_SESSION['logged_in_time'] = time();

        self::$cachedUser = $user;
    }

    public static function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];

        if (ini_get("session.use_cookies") && !headers_sent()) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();
        self::$cachedUser = null;
    }

    public static function refresh(): void
    {
        self::$cachedUser = null;
    }
}
