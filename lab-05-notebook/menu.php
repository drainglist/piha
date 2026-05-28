<?php
// Защита от прямого доступа к файлу (можно открыть только через index.php)
if (basename($_SERVER['SCRIPT_NAME']) !== 'index.php') {
    http_response_code(404);
    exit;
}

/**
 * Функция генерации HTML-меню навигации
 * @return string HTML-код меню
 */
function getMenu(): string
{
    // Текущее действие (просмотр, добавление, редактирование, удаление)
    $action = $_GET['action'] ?? 'view';
    
    // Текущая сортировка (для страницы просмотра)
    $sort = $_GET['sort'] ?? 'created';

    // Допустимые действия
    $allowedActions = ['view', 'add', 'edit', 'delete'];

    // Если пришло недопустимое действие - подменяем на 'view'
    if (!in_array($action, $allowedActions, true)) {
        $action = 'view';
    }

    // Пункты главного меню (ключ => название)
    $items = [
        'view' => 'Просмотр',
        'add' => 'Добавление записи',
        'edit' => 'Редактирование записи',
        'delete' => 'Удаление записи',
    ];

    // Пункты подменю для сортировки (только на странице просмотра)
    $sortItems = [
        'created' => 'По порядку добавления',
        'surname' => 'По фамилии',
        'birth_date' => 'По дате рождения',
    ];

    // Начинаем буферизацию вывода
    ob_start();
    ?>

    <nav class="menu">
        <!-- ГЛАВНОЕ МЕНЮ -->
        <div class="main-menu">
            <?php foreach ($items as $key => $label): ?>
                <a
                    class="menu-link <?= $action === $key ? 'active' : '' ?>"
                    href="./index.php?action=<?= e($key) ?>"
                >
                    <?= e($label) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- ПОДМЕНЮ (только на странице просмотра) -->
        <?php if ($action === 'view'): ?>
            <div class="submenu">
                <?php foreach ($sortItems as $key => $label): ?>
                    <a
                        class="menu-link small <?= $sort === $key ? 'active' : '' ?>"
                        href="./index.php?action=view&sort=<?= e($key) ?>&page=1"
                    >
                        <?= e($label) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </nav>

    <?php
    // Возвращаем сгенерированный HTML и очищаем буфер
    return ob_get_clean();
}
