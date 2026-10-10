<?php

namespace App\Helpers;

class Csrf
{
    private const SESSION_KEY = '_csrf_token';
    private const SESSION_TIME_KEY = '_csrf_token_time';

    public static function generateToken(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $lifetime = (int) Env::get('CSRF_TOKEN_LIFETIME', 3600);
        $currentTime = time();

        if (
            empty($_SESSION[self::SESSION_KEY]) ||
            empty($_SESSION[self::SESSION_TIME_KEY]) ||
            ($currentTime - $_SESSION[self::SESSION_TIME_KEY] > $lifetime)
        ) {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
            $_SESSION[self::SESSION_TIME_KEY] = $currentTime;
        }

        return $_SESSION[self::SESSION_KEY];
    }

    public static function getToken(): string
    {
        return self::generateToken();
    }

    public static function validate(?string $token): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($token) || empty($_SESSION[self::SESSION_KEY])) {
            return false;
        }

        return hash_equals($_SESSION[self::SESSION_KEY], $token);
    }

    public static function field(): string
    {
        $token = htmlspecialchars(self::getToken(), ENT_QUOTES, 'UTF-8');
        return '<input type="hidden" name="_csrf_token" value="' . $token . '">';
    }
}
