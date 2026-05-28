<?php
// Защита от прямого доступа к файлу (можно открыть только через index.php)
if (basename($_SERVER['SCRIPT_NAME']) !== 'index.php') {
    http_response_code(404);
    exit;
}

/**
 * Функция отображения и обработки редактирования контакта
 * @param PDO $pdo Подключение к базе данных
 * @return string HTML-код страницы редактирования
 */
function renderEdit(PDO $pdo): string
{
    $message = '';        // Текст сообщения пользователю
    $messageClass = '';   // Класс для стилизации (success/error)

    // ==================== ОБРАБОТКА ОТПРАВКИ ФОРМЫ ====================
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_action'] ?? '') === 'edit') {
        $id = (int)($_POST['id'] ?? 0);  // ID редактируемой записи

        // Собираем данные из формы
        $values = [
            'surname' => trim($_POST['surname'] ?? ''),
            'name' => trim($_POST['name'] ?? ''),
            'lastname' => trim($_POST['lastname'] ?? ''),
            'gender' => trim($_POST['gender'] ?? ''),
            'date' => trim($_POST['date'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'location' => trim($_POST['location'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'comment' => trim($_POST['comment'] ?? ''),
        ];

        // Валидация: ID должен быть положительным, фамилия и имя обязательны
        if ($id <= 0 || $values['surname'] === '' || $values['name'] === '') {
            $message = 'Ошибка: запись не изменена';
            $messageClass = 'error';
        } else {
            try {
                // Обновление данных в БД
                $stmt = $pdo->prepare("
                    UPDATE contacts
                    SET
                        surname = :surname,
                        name = :name,
                        lastname = :lastname,
                        gender = :gender,
                        birth_date = :birth_date,
                        phone = :phone,
                        address = :address,
                        email = :email,
                        comment = :comment
                    WHERE id = :id
                ");

                $stmt->execute([
                    ':surname' => $values['surname'],
                    ':name' => $values['name'],
                    ':lastname' => $values['lastname'],
                    ':gender' => $values['gender'],
                    ':birth_date' => $values['date'],
                    ':phone' => $values['phone'],
                    ':address' => $values['location'],
                    ':email' => $values['email'],
                    ':comment' => $values['comment'],
                    ':id' => $id,
                ]);

                $message = 'Запись изменена';
                $messageClass = 'success';

                // Обновляем ID в GET, чтобы отобразить актуальные данные
                $_GET['id'] = (string)$id;
            } catch (Throwable $error) {
                $message = 'Ошибка: запись не изменена';
                $messageClass = 'error';
            }
        }
    }

    // ==================== ПОЛУЧЕНИЕ СПИСКА КОНТАКТОВ ====================
    $contacts = $pdo
        ->query("SELECT * FROM contacts ORDER BY surname ASC, name ASC")
        ->fetchAll();

    // Если записей нет - показываем сообщение
    if (count($contacts) === 0) {
        return '<h1>Редактирование записи</h1><p class="empty">Записей пока нет.</p>';
    }

    // ==================== ОПРЕДЕЛЕНИЕ ТЕКУЩЕГО КОНТАКТА ====================
    $currentId = (int)($_GET['id'] ?? $contacts[0]['id']);  // Берем из GET или первый в списке

    $stmt = $pdo->prepare("SELECT * FROM contacts WHERE id = :id");
    $stmt->execute([':id' => $currentId]);
    $currentContact = $stmt->fetch();

    // Если контакт не найден - берем первый из списка
    if (!$currentContact) {
        $currentContact = $contacts[0];
        $currentId = (int)$currentContact['id'];
    }

    // ==================== HTML-ВЕРСТКА ====================
    ob_start();
    ?>

    <h1>Редактирование записи</h1>

    <!-- Сообщение об успехе/ошибке -->
    <?php if ($message !== ''): ?>
        <p class="message <?= e($messageClass) ?>">
            <?= e($message) ?>
        </p>
    <?php endif; ?>

    <div class="edit-layout">
        <!-- Боковая панель со списком всех контактов -->
        <aside class="records-list">
            <?php foreach ($contacts as $contact): ?>
                <a
                    class="record-link <?= (int)$contact['id'] === $currentId ? 'current' : '' ?>"
                    href="./index.php?action=edit&id=<?= (int)$contact['id'] ?>"
                >
                    <?= e($contact['surname'] . ' ' . $contact['name']) ?>
                </a>
            <?php endforeach; ?>
        </aside>

        <!-- Форма редактирования выбранного контакта -->
        <form class="contact-form" method="post" action="./index.php?action=edit&id=<?= $currentId ?>">
            <input type="hidden" name="form_action" value="edit">
            <input type="hidden" name="id" value="<?= $currentId ?>">

            <!-- Фамилия (обязательное поле) -->
            <div class="form-row">
                <label>Фамилия</label>
                <input type="text" name="surname" value="<?= e($currentContact['surname']) ?>" required>
            </div>

            <!-- Имя (обязательное поле) -->
            <div class="form-row">
                <label>Имя</label>
                <input type="text" name="name" value="<?= e($currentContact['name']) ?>" required>
            </div>

            <!-- Отчество -->
            <div class="form-row">
                <label>Отчество</label>
                <input type="text" name="lastname" value="<?= e($currentContact['lastname'] ?? '') ?>">
            </div>

            <!-- Пол (выпадающий список) -->
            <div class="form-row">
                <label>Пол</label>
                <select name="gender">
                    <option value="">Выберите пол</option>
                    <option value="мужской" <?= ($currentContact['gender'] ?? '') === 'мужской' ? 'selected' : '' ?>>мужской</option>
                    <option value="женский" <?= ($currentContact['gender'] ?? '') === 'женский' ? 'selected' : '' ?>>женский</option>
                </select>
            </div>

            <!-- Дата рождения -->
            <div class="form-row">
                <label>Дата рождения</label>
                <input type="date" name="date" value="<?= e($currentContact['birth_date'] ?? '') ?>">
            </div>

            <!-- Телефон -->
            <div class="form-row">
                <label>Телефон</label>
                <input type="text" name="phone" value="<?= e($currentContact['phone'] ?? '') ?>">
            </div>

            <!-- Адрес -->
            <div class="form-row">
                <label>Адрес</label>
                <input type="text" name="location" value="<?= e($currentContact['address'] ?? '') ?>">
            </div>

            <!-- Email -->
            <div class="form-row">
                <label>E-mail</label>
                <input type="email" name="email" value="<?= e($currentContact['email'] ?? '') ?>">
            </div>

            <!-- Комментарий -->
            <div class="form-row">
                <label>Комментарий</label>
                <textarea name="comment"><?= e($currentContact['comment'] ?? '') ?></textarea>
            </div>

            <!-- Кнопка сохранения -->
            <button class="form-btn" type="submit">Сохранить</button>
        </form>
    </div>

    <?php
    return ob_get_clean();
}