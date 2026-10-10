<?php

use App\Helpers\Env;

return [
    'name' => Env::get('APP_NAME', 'TemanBelajar'),
    'tagline' => 'Belajar Bersama, Berkembang Bersama.',
    'env' => Env::get('APP_ENV', 'development'),
    'debug' => (bool) Env::get('APP_DEBUG', true),
    'url' => Env::get('APP_URL', 'http://localhost:8000'),
    'timezone' => Env::get('APP_TIMEZONE', 'Asia/Jakarta'),
    'session_lifetime' => (int) Env::get('SESSION_LIFETIME', 7200),
    'csrf_token_lifetime' => (int) Env::get('CSRF_TOKEN_LIFETIME', 3600),
];
