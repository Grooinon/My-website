<?php

namespace App\Helpers;

class Env
{
    private static array $variables = [];
    private static bool $loaded = false;

    public static function load(string $path): void
    {
        if (self::$loaded || !file_exists($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            if (str_contains($line, '=')) {
                [$key, $value] = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);

                // Strip quotes
                if ((str_starts_with($value, '"') && str_ends_with($value, '"')) ||
                    (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
                    $value = substr($value, 1, -1);
                }

                // Handle boolean / null strings
                $lower = strtolower($value);
                if ($lower === 'true') {
                    $value = true;
                } elseif ($lower === 'false') {
                    $value = false;
                } elseif ($lower === 'null') {
                    $value = null;
                }

                self::$variables[$key] = $value;
                if (!array_key_exists($key, $_ENV)) {
                    $_ENV[$key] = $value;
                }
            }
        }

        self::$loaded = true;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return self::$variables[$key] ?? $_ENV[$key] ?? getenv($key) ?: $default;
    }
}
