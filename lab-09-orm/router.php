<?php

declare(strict_types=1);

// ===== ВАЖНО: правильный базовый путь =====
$basePath = '/drainglistpiha/lab-09-orm';  // ← ИСПРАВЛЕНО

function startsWith(string $text, string $prefix): bool
{
    return substr($text, 0, strlen($prefix)) === $prefix;
}

function normalizePath(string $path, string $basePath): string
{
    if ($path === $basePath) {
        return '/';
    }

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

    // Ищем файл в папке лабораторной
    $file = realpath(__DIR__ . $path);

    if ($file !== false && is_file($file) && startsWith($file, $labRoot)) {
        $extension = pathinfo($file, PATHINFO_EXTENSION);

        $contentTypes = [
            'css' => 'text/css',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
        ];

        if (isset($contentTypes[$extension])) {
            header('Content-Type: ' . $contentTypes[$extension]);
        }

        readfile($file);
        exit;
    }
}

$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

serveStaticFile($requestPath, $basePath);

require __DIR__ . '/index.php';