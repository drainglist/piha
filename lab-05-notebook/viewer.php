<?php
// Защита от прямого доступа к файлу (можно открыть только через index.php)
if (basename($_SERVER['SCRIPT_NAME']) !== 'index.php') {
    http_response_code(404);
    exit;
}

/**
 * Функция отображения списка контактов (просмотр)
 * @param PDO $pdo Подключение к базе данных
 * @param string $sort Поле сортировки (created, surname, birth_date)
 * @param int $page Номер страницы (для пагинации)
 * @return string HTML-код таблицы с контактами
 */
function renderViewer(PDO $pdo, string $sort, int $page): string
{
    // Соответствие: ключ сортировки => SQL-выражение ORDER BY
    $sortMap = [
        'created' => 'id ASC',                    // По порядку добавления (ID)
        'surname' => 'surname ASC, name ASC',     // По фамилии, затем по имени
        'birth_date' => 'birth_date ASC',         // По дате рождения
    ];

    // Если пришёл неизвестный ключ сортировки - используем 'created'
    if (!array_key_exists($sort, $sortMap)) {
        $sort = 'created';
    }

    // Пагинация: 10 записей на страницу
    $limit = 10;
    $offset = ($page - 1) * $limit;  // Смещение (с какой записи начинать)

    // Получаем общее количество записей в таблице
    $total = (int)$pdo->query("SELECT COUNT(*) FROM contacts")->fetchColumn();
    
    // Вычисляем количество страниц (округляем вверх)
    $totalPages = max(1, (int)ceil($total / $limit));

    // Если текущая страница больше максимальной - корректируем
    if ($page > $totalPages) {
        $page = $totalPages;
        $offset = ($page - 1) * $limit;
    }

    // SQL-запрос с сортировкой и пагинацией
    $sql = "
        SELECT *
        FROM contacts
        ORDER BY {$sortMap[$sort]}   -- Сортировка (динамическая)
        LIMIT :limit OFFSET :offset   -- Ограничение количества записей
    ";

    // Подготавливаем и выполняем запрос
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);   // LIMIT (целое число)
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT); // OFFSET (целое число)
    $stmt->execute();

    // Получаем записи для текущей страницы
    $contacts = $stmt->fetchAll();

    // Начинаем буферизацию вывода
    ob_start();
    ?>

    <h1>Записная книжка</h1>

    <!-- Если записей нет - показываем сообщение -->
    <?php if (count($contacts) === 0): ?>
        <p class="empty">Записей пока нет.</p>
    <?php else: ?>
        <!-- Таблица со списком контактов -->
        <table class="contacts-table">
            <thead>
                <tr>
                    <th>№</th>           <!-- Порядковый номер -->
                    <th>Фамилия</th>
                    <th>Имя</th>
                    <th>Отчество</th>
                    <th>Пол</th>
                    <th>Дата рождения</th>
                    <th>Телефон</th>
                    <th>Адрес</th>
                    <th>E-mail</th>
                    <th>Комментарий</th>
                </tr>
            </thead>

            <tbody>
                <!-- Выводим каждую запись строкой таблицы -->
                <?php foreach ($contacts as $index => $contact): ?>
                    <tr>
                        <!-- Номер строки (с учётом пагинации) -->
                        <td><?= $offset + $index + 1 ?></td>
                        <td><?= e($contact['surname']) ?></td>
                        <td><?= e($contact['name']) ?></td>
                        <td><?= e($contact['lastname'] ?? '') ?></td>
                        <td><?= e($contact['gender'] ?? '') ?></td>
                        <td><?= e($contact['birth_date'] ?? '') ?></td>
                        <td><?= e($contact['phone'] ?? '') ?></td>
                        <td><?= e($contact['address'] ?? '') ?></td>
                        <td><?= e($contact['email'] ?? '') ?></td>
                        <td><?= e($contact['comment'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Пагинация (если больше одной страницы) -->
        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a
                        class="page-link <?= $page === $i ? 'active' : '' ?>"
                        href="./index.php?action=view&sort=<?= e($sort) ?>&page=<?= $i ?>"
                    >
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php
    // Возвращаем сгенерированный HTML
    return ob_get_clean();
}