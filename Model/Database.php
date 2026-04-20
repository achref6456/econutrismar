<?php

declare(strict_types=1);

class Database
{
    private static ?PDO $pdo = null;

    public static function getConnection(): PDO
    {
        if (self::$pdo === null) {
            $cfg = require __DIR__ . '/config.php';
            $d = $cfg['db'];
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                $d['host'],
                $d['name'],
                $d['charset']
            );
            self::$pdo = new PDO($dsn, $d['user'], $d['password'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }
        return self::$pdo;
    }
}
