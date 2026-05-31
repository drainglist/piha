<?php

declare(strict_types=1);

// Базовый путь к проекту (должен совпадать с index.php)
$basePath = '/drainglistpiha/lab-10-edit-article';

/**
 * Проверяет, начинается ли строка с префикса
 */
function startsWith(string $text, string $prefix): bool
{
    return substr($text, 0, strlen($prefix)) === $prefix;
}

/**
 * Нормализует путь: отрезает базовую часть
 * Пример: /drainglistpiha/lab-10-edit-article/styles.css → /styles.css
 */
function normalizePath(string $path, string $basePath): string
{
    if ($path === $basePath) return '/';
    if (startsWith($path, $basePath . '/')) {
        $path = substr($path, strlen($basePath));
    }
    return $path === '' ? '/' : $path;
}

/**
 * Отдаёт статические файлы (CSS, картинки, JS)
 * Если файл найден – выводит его и завершает скрипт
 */
function serveStaticFile(string $path, string $basePath): void
{
    $path = normalizePath($path, $basePath);
    $labRoot = realpath(__DIR__);
    $file = realpath(__DIR__ . $path);

    // Если файл существует и лежит в папке проекта
    if ($file !== false && is_file($file) && startsWith($file, $labRoot)) {
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        
        // Типы файлов и соответствующие Content-Type
        $contentTypes = [
            'css' => 'text/css',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
        ];
        
        if (isset($contentTypes[$ext])) {
            header('Content-Type: ' . $contentTypes[$ext]);
        }
        
        readfile($file);  // Выводим содержимое файла
        exit;             // Завершаем скрипт
    }
}

$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
serveStaticFile($requestPath, $basePath);
require __DIR__ . '/index.php';