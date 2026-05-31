<?php

declare(strict_types=1);

// Базовый путь к проекту
$basePath = '/drainglistpiha/kursach';

function startsWith(string $text, string $prefix): bool
{
    return substr($text, 0, strlen($prefix)) === $prefix;
}

function normalizePath(string $path, string $basePath): string
{
    if ($path === $basePath) return '/';
    if (startsWith($path, $basePath . '/')) {
        $path = substr($path, strlen($basePath));
    }
    return $path === '' ? '/' : $path;
}

// Отдаёт статические файлы (CSS, картинки)
function serveStaticFile(string $path, string $basePath): void
{
    $path = normalizePath($path, $basePath);
    $labRoot = realpath(__DIR__);
    $file = realpath(__DIR__ . $path);

    if ($file !== false && is_file($file) && startsWith($file, $labRoot)) {
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $types = ['css' => 'text/css', 'png' => 'image/png', 'jpg' => 'image/jpeg'];
        if (isset($types[$ext])) header('Content-Type: ' . $types[$ext]);
        readfile($file);
        exit;
    }
}

$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
serveStaticFile($requestPath, $basePath);
require __DIR__ . '/index.php';