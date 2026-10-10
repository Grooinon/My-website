<?php

/**
 * TemanBelajar - Platform Tutor Sebaya Mahasiswa
 * Front Controller Entry Point
 */

require_once dirname(__DIR__) . '/app/bootstrap.php';
require_once dirname(__DIR__) . '/routes/web.php';

use Routes\Router;

try {
    Router::dispatch();
} catch (\Throwable $e) {
    if (env('APP_DEBUG', false)) {
        http_response_code(500);
        echo "<div style='font-family: sans-serif; padding: 2rem; background: #fff1f2; color: #9f1239; border-left: 6px solid #e11d48; margin: 2rem;'>";
        echo "<h2>Aplikasi Mengalami Kendala (Mode Debug)</h2>";
        echo "<p><strong>Pesan:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . " (Baris " . $e->getLine() . ")</p>";
        echo "<pre style='background:#ffe4e6; padding:1rem; border-radius:6px; overflow:auto;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
        echo "</div>";
    } else {
        http_response_code(500);
        view('errors/500', ['title' => 'Terjadi Kesalahan Server - 500']);
    }
}
