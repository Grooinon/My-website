<?php

namespace App\Middleware;

use App\Helpers\Auth;
use App\Helpers\View;

class AdminMiddleware
{
    public static function handle(): bool
    {
        if (!AuthMiddleware::handle()) {
            return false;
        }

        if (!Auth::isAdmin()) {
            http_response_code(403);
            view('errors/403', ['title' => 'Akses Ditolak - 403'], 'layouts/main');
            exit;
        }

        return true;
    }
}
