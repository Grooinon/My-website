<?php

namespace App\Middleware;

use App\Helpers\Csrf;
use App\Helpers\Flash;

class CsrfMiddleware
{
    public static function handle(): bool
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        if (in_array($method, ['POST', 'PUT', 'DELETE', 'PATCH'])) {
            $token = $_POST['_csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
            if (!Csrf::validate($token)) {
                Flash::error('Sesi keamanan telah kedaluwarsa. Silakan muat ulang halaman dan coba kembali.');
                http_response_code(419);
                redirect($_SERVER['HTTP_REFERER'] ?? '/');
                return false;
            }
        }
        return true;
    }
}
