<?php

declare(strict_types=1);

// Автозагрузка классов (сама находит нужные файлы)
spl_autoload_register(function (string $className): void {
    $paths = [
        __DIR__ . '/src/Controllers/' . $className . '.php',
        __DIR__ . '/src/Database/' . $className . '.php',
        __DIR__ . '/src/Models/' . $className . '.php',
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require $path;
            return;
        }
    }
});

// ===== ВАЖНО: правильный базовый путь =====
$basePath = '/drainglistpiha/lab-09-orm';  // ← ИСПРАВЛЕНО

// Проверяет начало строки
function routeStartsWith(string $text, string $prefix): bool
{
    return substr($text, 0, strlen($prefix)) === $prefix;
}

// Получаем чистый путь без базовой части
function getRoutePath(string $basePath): string
{
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

    if ($path === $basePath) {
        return '/';
    }

    if (routeStartsWith($path, $basePath . '/')) {
        $path = substr($path, strlen($basePath));
    }

    return $path === '' ? '/' : $path;
}

$path = getRoutePath($basePath);

// Создаём контроллер
$controller = new ArticlesController();

// ========== МАРШРУТЫ ==========
// Список статей
if ($path === '/' || $path === '/articles') {
    $controller->index();
    exit;
}

// Одна статья: /articles/1
if (preg_match('#^/articles/(\d+)$#', $path, $matches)) {
    $articleId = (int) $matches[1];
    $controller->show($articleId);
    exit;
}

// 404
$controller->notFound();