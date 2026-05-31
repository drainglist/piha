<?php

declare(strict_types=1);

// Базовый путь
$basePath = '/drainglistpiha/lab-08-view';

// Проверяет начало строки
function startsWith(string $text, string $prefix): bool
{
    return substr($text, 0, strlen($prefix)) === $prefix;
}

// Нормализует путь (отрезает базовый)
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
    $root = realpath(__DIR__);
    $file = realpath(__DIR__ . $path);

    // Проверка безопасности
    if ($root === false || $file === false) return;
    if (!startsWith($file, $root) || !is_file($file)) return;

    // Определяем тип файла по расширению
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    $types = [
        'css' => 'text/css',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
    ];

    if (isset($types[$ext])) {
        header('Content-Type: ' . $types[$ext]);
    }

    readfile($file);  // Выводим файл
    exit;
}

$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
serveStaticFile($requestPath, $basePath);
require __DIR__ . '/index.php';