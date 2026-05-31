<?php

declare(strict_types=1);  // Строгая типизация

// ==================== АВТОЗАГРУЗКА КЛАССОВ ====================
// Функция сама подключает нужные файлы при создании объекта
spl_autoload_register(function (string $className): void {
    // Возможные пути, где могут лежать классы
    $paths = [
        __DIR__ . '/src/Controllers/' . $className . '.php',  // Контроллеры
        __DIR__ . '/src/Database/' . $className . '.php',     // База данных
        __DIR__ . '/src/Models/' . $className . '.php',       // Модели
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require $path;  // Подключаем файл
            return;        // Выходим, чтобы не искать дальше
        }
    }
});

// ==================== БАЗОВЫЙ ПУТЬ ====================
// ВАЖНО! Должен совпадать с реальным путём на сервере
$basePath = '/drainglistpiha/lab-10-edit-article';

/**
 * Проверяет, начинается ли строка с определённого префикса
 */
function routeStartsWith(string $text, string $prefix): bool
{
    return substr($text, 0, strlen($prefix)) === $prefix;
}

/**
 * Получает чистый путь из URL, отрезая базовую часть
 * Пример: /drainglistpiha/lab-10-edit-article/articles/1 → /articles/1
 */
function getRoutePath(string $basePath): string
{
    // Получаем путь из URL (например, /drainglistpiha/lab-10-edit-article/articles/1)
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

    // Если путь совпадает с базовым → это корень сайта
    if ($path === $basePath) {
        return '/';
    }

    // Если путь начинается с базового + слеш, отрезаем базовую часть
    if (routeStartsWith($path, $basePath . '/')) {
        $path = substr($path, strlen($basePath));
    }

    // Если осталась пустая строка → возвращаем корень
    return $path === '' ? '/' : $path;
}

// Получаем чистый путь (например, /, /articles, /articles/1, /article/1/edit)
$path = getRoutePath($basePath);

// Создаём объект контроллера статей
$controller = new ArticlesController();

// ==================== МАРШРУТЫ ====================

// Корень сайта или /articles → список статей
if ($path === '/' || $path === '/articles') {
    $controller->index();
    exit;
}

// /articles/1, /articles/2 → показ одной статьи
if (preg_match('#^/articles/(\d+)$#', $path, $matches)) {
    $articleId = (int) $matches[1];
    $controller->show($articleId);
    exit;
}

// /article/1/edit → форма редактирования статьи (маршрут по заданию)
if (preg_match('#^/article/(\d+)/edit$#', $path, $matches)) {
    $articleId = (int) $matches[1];
    $controller->edit($articleId);
    exit;
}

// Если ни один маршрут не подошёл → 404
$controller->notFound();