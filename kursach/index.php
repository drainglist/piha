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
$basePath = '/drainglistpiha/kursach';

/**
 * Проверяет, начинается ли строка с определённого префикса
 */
function routeStartsWith(string $text, string $prefix): bool
{
    return substr($text, 0, strlen($prefix)) === $prefix;
}

/**
 * Получает чистый путь из URL, отрезая базовую часть
 */
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

// Получаем чистый путь
$path = getRoutePath($basePath);

// Создаём контроллеры
$articlesController = new ArticlesController();
$commentsController = new CommentsController();

// ==================== МАРШРУТЫ ====================

// Главная страница и список статей
if ($path === '/' || $path === '/articles') {
    $articlesController->index();
    exit;
}

// Создание новой статьи
if ($path === '/articles/create') {
    $articlesController->create();
    exit;
}

// Удаление статьи
if (preg_match('#^/articles/(\d+)/delete$#', $path, $matches)) {
    $articleId = (int) $matches[1];
    $articlesController->delete($articleId);
    exit;
}

// Добавление комментария
if (preg_match('#^/articles/(\d+)/comments$#', $path, $matches)) {
    $articleId = (int) $matches[1];
    $commentsController->store($articleId);
    exit;
}

// Редактирование комментария
if (preg_match('#^/comments/(\d+)/edit$#', $path, $matches)) {
    $commentId = (int) $matches[1];
    $commentsController->edit($commentId);
    exit;
}

// Удаление комментария
if (preg_match('#^/comments/(\d+)/delete$#', $path, $matches)) {
    $commentId = (int) $matches[1];
    $commentsController->delete($commentId);
    exit;
}

// Просмотр одной статьи
if (preg_match('#^/articles/(\d+)$#', $path, $matches)) {
    $articleId = (int) $matches[1];
    $articlesController->show($articleId);
    exit;
}

// Редактирование статьи
if (preg_match('#^/article/(\d+)/edit$#', $path, $matches)) {
    $articleId = (int) $matches[1];
    $articlesController->edit($articleId);
    exit;
}

// Если ничего не подошло - 404
$articlesController->notFound();