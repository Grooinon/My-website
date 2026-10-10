<?php

namespace App\Helpers;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;
    private static string $activeDriver = 'mysql';

    public static function getConnection(): PDO
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        $config = require dirname(__DIR__, 2) . '/config/database.php';
        $default = $config['default'] ?? 'mysql';

        try {
            if ($default === 'mysql') {
                $c = $config['connections']['mysql'];
                $dsn = "mysql:host={$c['host']};port={$c['port']};dbname={$c['database']};charset={$c['charset']}";
                self::$instance = new PDO($dsn, $c['username'], $c['password'], $c['options']);
                self::$activeDriver = 'mysql';
            } else {
                $c = $config['connections']['sqlite'];
                $dbDir = dirname($c['database']);
                if (!is_dir($dbDir)) {
                    mkdir($dbDir, 0777, true);
                }
                $dsn = "sqlite:{$c['database']}";
                self::$instance = new PDO($dsn, null, null, $c['options']);
                self::$instance->exec('PRAGMA foreign_keys = ON;');
                self::$activeDriver = 'sqlite';
            }
        } catch (PDOException $e) {
            // If MySQL failed to connect, try falling back to SQLite if in development
            if ($default === 'mysql') {
                try {
                    $c = $config['connections']['sqlite'];
                    $dbDir = dirname($c['database']);
                    if (!is_dir($dbDir)) {
                        mkdir($dbDir, 0777, true);
                    }
                    $dsn = "sqlite:{$c['database']}";
                    self::$instance = new PDO($dsn, null, null, $c['options']);
                    self::$instance->exec('PRAGMA foreign_keys = ON;');
                    self::$activeDriver = 'sqlite';
                    return self::$instance;
                } catch (PDOException $e2) {
                    throw new PDOException("Database connection error: " . $e->getMessage() . " | Fallback error: " . $e2->getMessage());
                }
            }
            throw $e;
        }

        return self::$instance;
    }

    public static function getDriver(): string
    {
        if (self::$instance === null) {
            self::getConnection();
        }
        return self::$activeDriver;
    }

    public static function query(string $sql, array $params = []): array
    {
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function queryOne(string $sql, array $params = []): ?array
    {
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public static function execute(string $sql, array $params = []): int
    {
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    public static function lastInsertId(): string
    {
        return self::getConnection()->lastInsertId();
    }

    public static function beginTransaction(): bool
    {
        return self::getConnection()->beginTransaction();
    }

    public static function commit(): bool
    {
        return self::getConnection()->commit();
    }

    public static function rollBack(): bool
    {
        return self::getConnection()->rollBack();
    }
}
