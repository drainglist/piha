<?php
// Защита от прямого доступа к файлу (можно открыть только через index.php)
if (basename($_SERVER['SCRIPT_NAME']) !== 'index.php') {
    http_response_code(404);
    exit;
}

/**
 * Формирует инициалы из имени и отчества
 * Пример: getInitials('Иван', 'Петрович') -> 'И. П.'
 * @param string $name Имя
 * @param string $lastname Отчество
 * @return string Инициалы (первая буква имени и первая буква отчества с точками)
 */
function getInitials(string $name, string $lastname): string
{
    // Берём первую букву имени, если имя не пустое
    $firstInitial = $name !== '' ? mb_substr($name, 0, 1) . '.' : '';
    
    // Берём первую букву отчества, если отчество не пустое
    $secondInitial = $lastname !== '' ? mb_substr($lastname, 0, 1) . '.' : '';

    // Объединяем и обрезаем лишние пробелы
    return trim($firstInitial . ' ' . $secondInitial);
}

/**
 * Функция отображения и обработки удаления контакта
 * @param PDO $pdo Подключение к базе данных
 * @return string HTML-код страницы удаления
 */
function renderDelete(PDO $pdo): string
{
    $message = '';  // Сообщение о результате удаления

    // Проверяем, передан ли ID для удаления
    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];  // Приводим к целому числу (безопасность)

        // Сначала получаем данные контакта, чтобы показать что удалили
        $stmt = $pdo->prepare("SELECT * FROM contacts WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $contact = $stmt->fetch();

        // Если контакт существует - удаляем
        if ($contact) {
            $deleteStmt = $pdo->prepare("DELETE FROM contacts WHERE id = :id");
            $deleteStmt->execute([':id' => $id]);

            // Сообщение с фамилией удалённого контакта
            $message = 'Запись с фамилией ' . e($contact['surname']) . ' удалена';
        }
    }

    // Получаем ВСЕ контакты, отсортированные по фамилии и имени
    $contacts = $pdo
        ->query("SELECT * FROM contacts ORDER BY surname ASC, name ASC")
        ->fetchAll();

    // Начинаем буферизацию вывода
    ob_start();
    ?>

    <h1>Удаление записи</h1>

    <!-- Показываем сообщение об успешном удалении -->
    <?php if ($message !== ''): ?>
        <p class="message success"><?= $message ?></p>
    <?php endif; ?>

    <!-- Если записей нет -->
    <?php if (count($contacts) === 0): ?>
        <p class="empty">Записей пока нет.</p>
    <?php else: ?>
        <!-- Список ссылок для удаления -->
        <div class="delete-list">
            <?php foreach ($contacts as $contact): ?>
                <a
                    class="delete-link"
                    href="./index.php?action=delete&id=<?= (int)$contact['id'] ?>"
                >
                    <!-- Выводим: Фамилия И. О. -->
                    <?= e($contact['surname'] . ' ' . getInitials($contact['name'], $contact['lastname'] ?? '')) ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php
    // Возвращаем HTML и очищаем буфер
    return ob_get_clean();
}