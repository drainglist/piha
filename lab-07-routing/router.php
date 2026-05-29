<?php

declare(strict_types=1);

// Базовый путь к проекту
$basePath = '/drainglistpiha/lab-07-routing';

// Проверяет начало строки
function startsWith(string $text, string $prefix): bool
{
    return substr($text, 0, strlen($prefix)) === $prefix;
}

// Нормализует путь (отрезает базовый путь)
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

// Отдаёт статические файлы (css, js, изображения)
function serveStaticFile(string $path, string $basePath): void
{
    $path = normalizePath($path, $basePath);

    $root = realpath(__DIR__);
    $file = realpath(__DIR__ . $path);

    // Проверка безопасности: файл должен быть внутри папки проекта
    if ($root === false || $file === false) {
        return;
    }

    if (!startsWith($file, $root) || !is_file($file)) {
        return;
    }

    // Определяем тип контента по расширению
    $extension = pathinfo($file, PATHINFO_EXTENSION);

    $contentTypes = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
    ];

    // Отправляем правильный Content-Type
    if (isset($contentTypes[$extension])) {
        header('Content-Type: ' . $contentTypes[$extension]);
    }

    // Выводим файл и завершаем скрипт
    readfile($file);
    exit;
}

// Получаем путь из запроса
$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

// Если это статический файл - отдаём его и завершаем работу
serveStaticFile($requestPath, $basePath);

// Если не статический файл - подключаем роутер (index.php)
require __DIR__ . '/index.php';