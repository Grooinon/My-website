<?php

use App\Helpers\Env;
use App\Helpers\Csrf;
use App\Helpers\Flash;
use App\Helpers\Auth;
use App\Helpers\View;
use App\Helpers\Sanitizer;

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        return Env::get($key, $default);
    }
}

if (!function_exists('e')) {
    function e(?string $value): string
    {
        return Sanitizer::escape($value ?? '');
    }
}

if (!function_exists('view')) {
    function view(string $viewPath, array $data = [], ?string $layout = 'layouts/main'): void
    {
        View::render($viewPath, $data, $layout);
    }
}

if (!function_exists('redirect')) {
    function redirect(string $path, int $statusCode = 302): void
    {
        $url = str_starts_with($path, 'http://') || str_starts_with($path, 'https://')
            ? $path
            : url($path);

        header("Location: {$url}", true, $statusCode);
        exit;
    }
}

if (!function_exists('base_url')) {
    function base_url(string $path = ''): string
    {
        // Detect script path for XAMPP subfolder support
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $subDir = dirname($scriptName);
        if ($subDir === '/' || $subDir === '\\') {
            $subDir = '';
        }

        $base = rtrim($subDir, '/');
        $path = ltrim($path, '/');

        return $path !== '' ? "{$base}/{$path}" : ($base !== '' ? $base : '/');
    }
}

if (!function_exists('url')) {
    function url(string $path = ''): string
    {
        return base_url($path);
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        return base_url('assets/' . ltrim($path, '/'));
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        return Csrf::getToken();
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return Csrf::field();
    }
}

if (!function_exists('flash')) {
    function flash(?string $type = null, ?string $message = null): mixed
    {
        if ($type !== null && $message !== null) {
            Flash::set($type, $message);
            return null;
        }
        return Flash::get();
    }
}

if (!function_exists('auth')) {
    function auth(): ?array
    {
        return Auth::user();
    }
}

if (!function_exists('old')) {
    function old(string $key, mixed $default = ''): mixed
    {
        return $_SESSION['_old_input'][$key] ?? $default;
    }
}

if (!function_exists('set_old')) {
    function set_old(array $data): void
    {
        $_SESSION['_old_input'] = $data;
    }
}

if (!function_exists('clear_old')) {
    function clear_old(): void
    {
        unset($_SESSION['_old_input']);
    }
}

if (!function_exists('format_rupiah')) {
    function format_rupiah(float|int $amount): string
    {
        if ($amount <= 0) {
            return 'Gratis';
        }
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}

if (!function_exists('format_date_id')) {
    function format_date_id(?string $date): string
    {
        if (!$date) return '-';
        $timestamp = strtotime($date);
        $months = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        $days = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];
        $dayName = $days[date('l', $timestamp)] ?? '';
        $day = date('j', $timestamp);
        $month = $months[(int) date('n', $timestamp)] ?? '';
        $year = date('Y', $timestamp);
        return "{$dayName}, {$day} {$month} {$year}";
    }
}

if (!function_exists('format_time_id')) {
    function format_time_id(?string $time): string
    {
        if (!$time) return '-';
        return date('H:i', strtotime($time)) . ' WIB';
    }
}
