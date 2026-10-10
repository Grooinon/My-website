<?php

namespace App\Middleware;

use App\Helpers\Auth;
use App\Helpers\Flash;

class TutorMiddleware
{
    public static function handle(): bool
    {
        if (!AuthMiddleware::handle()) {
            return false;
        }

        if (!Auth::isTutor()) {
            Flash::info('Anda belum mengaktifkan profil tutor. Silakan lengkapi profil tutor Anda untuk mulai mengajar.');
            redirect('/tutor/profile/edit');
            return false;
        }

        return true;
    }
}
