<?php

namespace App\Middleware;

use App\Helpers\Auth;
use App\Helpers\Flash;

class AuthMiddleware
{
    public static function handle(): bool
    {
        if (!Auth::check()) {
            Flash::error('Silakan masuk ke akun Anda terlebih dahulu untuk mengakses halaman ini.');
            $currentUri = $_SERVER['REQUEST_URI'] ?? '/';
            $_SESSION['return_url'] = $currentUri;
            redirect('/login');
            return false;
        }

        $user = Auth::user();
        if (!$user || empty($user['is_active'])) {
            Auth::logout();
            Flash::error('Akun Anda dinonaktifkan oleh administrator. Silakan hubungi bagian akademik.');
            redirect('/login');
            return false;
        }

        return true;
    }
}
