<?php

declare(strict_types=1);

$config = require __DIR__ . '/config.php';

$root = dirname(__DIR__);

spl_autoload_register(function (string $class) use ($root): void {
    $paths = [
        $root . '/Model/' . $class . '.php',
        $root . '/Controller/' . $class . '.php',
    ];
    foreach ($paths as $file) {
        if (is_file($file)) {
            require_once $file;
            return;
        }
    }
});

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
