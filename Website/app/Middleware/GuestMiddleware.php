<?php

namespace App\Middleware;

use App\Helpers\Auth;

class GuestMiddleware
{
    public static function handle(): bool
    {
        if (Auth::check()) {
            if (Auth::isAdmin()) {
                redirect('/admin/dashboard');
            } else {
                redirect('/student/dashboard');
            }
            return false;
        }

        return true;
    }
}
