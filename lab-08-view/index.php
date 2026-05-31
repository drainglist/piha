<?php

declare(strict_types=1);  // Строгий режим типов

// Подключаем контроллер
require __DIR__ . '/controllers/BlogController.php';

// Базовый путь к проекту (важно для сервера)
$basePath = '/drainglistpiha/lab-08-view';

// Проверяет, начинается ли строка с нужного префикса
function routeStartsWith(string $text, string $prefix): bool
{
    return substr($text, 0, strlen($prefix)) === $prefix;
}

// Получаем чистый путь без базовой части
function getRoutePath(string $basePath): string
{
    // Берём путь из URL
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

    // Если это корень проекта
    if ($path === $basePath) {
        return '/';
    }

    // Отрезаем базовый путь
    if (routeStartsWith($path, $basePath . '/')) {
        $path = substr($path, strlen($basePath));
    }

    return $path === '' ? '/' : $path;
}

// Получаем путь
$path = getRoutePath($basePath);

// Создаём контроллер
$controller = new BlogController();

// ========== МАРШРУТЫ ==========

// Главная страница
if ($path === '/') {
    $controller->index();
    exit;
}

// Страница "Обо мне"
if ($path === '/about-me') {
    $controller->aboutMe();
    exit;
}

// Приветствие: /hello/Имя
if (preg_match('#^/hello/([^/]+)$#u', $path, $matches)) {
    $username = rawurldecode($matches[1]);  // Декодируем имя
    $controller->sayHello($username);
    exit;
}

// Прощание: /bye/Имя
if (preg_match('#^/bye/([^/]+)$#u', $path, $matches)) {
    $name = rawurldecode($matches[1]);
    $controller->sayBye($name);
    exit;
}

// Если ничего не подошло - 404
$controller->notFound();