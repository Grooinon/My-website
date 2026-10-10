<?php

namespace Routes;

use App\Helpers\View;
use App\Middleware\CsrfMiddleware;

class Router
{
    private static array $routes = [];

    public static function get(string $path, array|callable $handler, array $middlewares = []): void
    {
        self::addRoute('GET', $path, $handler, $middlewares);
    }

    public static function post(string $path, array|callable $handler, array $middlewares = []): void
    {
        // By default, add CSRF validation for POST requests unless opted out
        $middlewares = array_merge([CsrfMiddleware::class], $middlewares);
        self::addRoute('POST', $path, $handler, $middlewares);
    }

    private static function addRoute(string $method, string $path, array|callable $handler, array $middlewares = []): void
    {
        self::$routes[] = [
            'method' => $method,
            'path' => '/' . trim($path, '/'),
            'handler' => $handler,
            'middlewares' => $middlewares,
        ];
    }

    public static function dispatch(): void
    {
        $requestMethod = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        
        // Handle method spoofing (PUT, DELETE via POST)
        if ($requestMethod === 'POST' && isset($_POST['_method'])) {
            $spoofed = strtoupper($_POST['_method']);
            if (in_array($spoofed, ['PUT', 'DELETE', 'PATCH'])) {
                $requestMethod = $spoofed;
            }
        }

        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $parsedUri = parse_url($uri, PHP_URL_PATH);

        // Normalize URI by removing base folder when running under XAMPP subdirectories
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $baseDir = dirname($scriptName);
        if ($baseDir !== '/' && $baseDir !== '\\' && str_starts_with($parsedUri, $baseDir)) {
            $parsedUri = substr($parsedUri, strlen($baseDir));
        }

        // Also handle /index.php prefix if present
        if (str_starts_with($parsedUri, '/index.php')) {
            $parsedUri = substr($parsedUri, 10);
        }

        $requestPath = '/' . trim($parsedUri ?: '/', '/');

        foreach (self::$routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }

            // Convert route path to regex with param capture
            // e.g. /tutors/{id} -> #^/tutors/(?P<id>[^/]+)$#
            $pattern = preg_replace('#\{([a-zA-Z0-9_]+)\}#', '(?P<$1>[^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $requestPath, $matches)) {
                // Filter named parameters
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                // Execute middlewares
                foreach ($route['middlewares'] as $middleware) {
                    if (is_callable($middleware)) {
                        $proceed = $middleware();
                    } elseif (class_exists($middleware) && method_exists($middleware, 'handle')) {
                        $proceed = $middleware::handle();
                    } else {
                        $proceed = true;
                    }

                    if ($proceed === false) {
                        return;
                    }
                }

                // Execute handler
                $handler = $route['handler'];
                if (is_callable($handler)) {
                    call_user_func_array($handler, $params);
                    return;
                }

                if (is_array($handler) && count($handler) === 2) {
                    [$class, $method] = $handler;
                    if (class_exists($class)) {
                        $controller = new $class();
                        if (method_exists($controller, $method)) {
                            call_user_func_array([$controller, $method], $params);
                            return;
                        }
                    }
                }

                http_response_code(500);
                echo "Handler tidak valid untuk rute ini.";
                return;
            }
        }

        // 404 Not Found
        http_response_code(404);
        view('errors/404', ['title' => 'Halaman Tidak Ditemukan - 404']);
    }
}
