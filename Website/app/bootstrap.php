<?php

// Check minimum PHP version
if (version_compare(PHP_VERSION, '8.2.0', '<')) {
    exit('Aplikasi TemanBelajar membutuhkan PHP versi 8.2 atau yang lebih baru.');
}

// Custom PSR-4 Autoloader
spl_autoload_register(function ($class) {
    $prefixes = [
        'App\\' => __DIR__ . '/',
        'Routes\\' => dirname(__DIR__) . '/routes/',
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) === 0) {
            $relativeClass = substr($class, $len);
            $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
});

// Load helper functions
require_once __DIR__ . '/Helpers/functions.php';

// Load environment configuration
$envFile = dirname(__DIR__) . '/.env';
if (file_exists($envFile)) {
    \App\Helpers\Env::load($envFile);
}

// Load App Configuration
$config = require dirname(__DIR__) . '/config/app.php';

// Set Timezone
date_default_timezone_set($config['timezone'] ?? 'Asia/Jakarta');

// Error Reporting
if ($config['debug'] ?? true) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(0);
}

// Session security configuration
if (session_status() === PHP_SESSION_NONE) {
    $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
                (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);

    session_set_cookie_params([
        'lifetime' => $config['session_lifetime'] ?? 7200,
        'path' => '/',
        'domain' => '',
        'secure' => $isSecure,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);

    ini_set('session.use_strict_mode', '1');
    session_start();
}
