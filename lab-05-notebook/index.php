<?php
// Строгая типизация (включены проверки типов)
declare(strict_types=1);

// ==================== НАСТРОЙКА БАЗЫ ДАННЫХ ====================

// Папка для хранения файла базы данных SQLite
$dbDir = __DIR__ . '/data';

// Создаём папку data, если её нет (права 0777 - чтение/запись для всех)
if (!is_dir($dbDir)) {
    mkdir($dbDir, 0777, true);
}

// Подключаемся к SQLite базе данных (файл notebook.sqlite)
$pdo = new PDO('sqlite:' . $dbDir . '/notebook.sqlite');

// Настройка PDO: выбрасывать исключения при ошибках
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Режим выборки по умолчанию - ассоциативный массив
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

// Создаём таблицу contacts, если она ещё не существует
$pdo->exec("
    CREATE TABLE IF NOT EXISTS contacts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,  -- Уникальный ID, автоинкремент
        surname TEXT NOT NULL,                  -- Фамилия (обязательное поле)
        name TEXT NOT NULL,                     -- Имя (обязательное поле)
        lastname TEXT,                          -- Отчество
        gender TEXT,                            -- Пол (мужской/женский)
        birth_date TEXT,                        -- Дата рождения (в формате YYYY-MM-DD)
        phone TEXT,                             -- Номер телефона
        address TEXT,                           -- Адрес
        email TEXT,                             -- Email
        comment TEXT,                           -- Комментарий
        created_at TEXT DEFAULT CURRENT_TIMESTAMP  -- Дата создания записи
    )
");

// ==================== ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ ====================

/**
 * Экранирование HTML-символов для защиты от XSS-атак
 * @param string $value Строка для экранирования
 * @return string Безопасная строка для вывода в HTML
 */
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// ==================== ПОДКЛЮЧЕНИЕ МОДУЛЕЙ ====================
// Подключаем файлы с функциями для разных страниц
require_once __DIR__ . '/menu.php';        // Меню навигации
require_once __DIR__ . '/viewer.php';      // Просмотр списка контактов
require_once __DIR__ . '/add.php';         // Добавление контакта
require_once __DIR__ . '/edit.php';        // Редактирование контакта
require_once __DIR__ . '/delete.php';      // Удаление контакта

// ==================== ОПРЕДЕЛЕНИЕ ДЕЙСТВИЯ ====================
// Допустимые действия (просмотр, добавление, редактирование, удаление)
$allowedActions = ['view', 'add', 'edit', 'delete'];

// Берём действие из GET-параметра, по умолчанию 'view'
$action = $_GET['action'] ?? 'view';

// Если действие недопустимое - заменяем на 'view'
if (!in_array($action, $allowedActions, true)) {
    $action = 'view';
}

// Параметры для просмотра списка (только для action=view)
$sort = $_GET['sort'] ?? 'created';  // Сортировка (по умолчанию по дате)
$page = (int)($_GET['page'] ?? 1);   // Номер страницы (пагинация)

if ($page < 1) {
    $page = 1;  // Минимум 1 страница
}

// ==================== ГЕНЕРАЦИЯ КОНТЕНТА ====================
$content = '';  // HTML-код основной части страницы

// Вызываем соответствующую функцию в зависимости от действия
if ($action === 'view') {
    $content = renderViewer($pdo, $sort, $page);
}

if ($action === 'add') {
    $content = renderAdd($pdo);
}

if ($action === 'edit') {
    $content = renderEdit($pdo);
}

if ($action === 'delete') {
    $content = renderDelete($pdo);
}
?>

<!-- ==================== HTML-РАЗМЕТКА ==================== -->
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Notebook</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <!-- Шапка сайта -->
    <header class="header">
        <img class="logo" src="logo.png" alt="logo">
        <div class="title">Домашняя работа: Notebook</div>
        <div class="header-spacer"></div>
    </header>

    <!-- Основное содержимое -->
    <main class="main">
        <!-- Меню навигации (из menu.php) -->
        <?= getMenu() ?>

        <!-- Контент страницы (add/edit/delete/view) -->
        <section class="content">
            <?= $content ?>
        </section>
    </main>

    <!-- Подвал -->
    <footer class="footer">
        задание для самостоятельно работы
    </footer>
</body>
</html>