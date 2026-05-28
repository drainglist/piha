<?php
// Защита от прямого доступа к файлу (можно открыть только через index.php)
if (basename($_SERVER['SCRIPT_NAME']) !== 'index.php') {
    http_response_code(404);
    exit;
}

/**
 * Функция отображения и обработки формы добавления контакта
 * @param PDO $pdo Подключение к базе данных
 * @return string HTML-код страницы добавления
 */
function renderAdd(PDO $pdo): string
{
    // Переменные для сообщения пользователю
    $message = '';
    $messageClass = '';

    // Значения полей формы по умолчанию (пустые)
    $values = [
        'surname' => '',
        'name' => '',
        'lastname' => '',
        'gender' => '',
        'date' => '',
        'phone' => '',
        'location' => '',
        'email' => '',
        'comment' => '',
    ];

    // Обработка POST-запроса (отправка формы)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_action'] ?? '') === 'add') {
        // Заполняем массив данными из формы
        foreach ($values as $key => $value) {
            $values[$key] = trim($_POST[$key] ?? '');
        }

        // Проверка обязательных полей (фамилия и имя)
        if ($values['surname'] === '' || $values['name'] === '') {
            $message = 'Ошибка: запись не добавлена';
            $messageClass = 'error';
        } else {
            try {
                // Подготовка SQL-запроса для вставки данных
                $stmt = $pdo->prepare("
                    INSERT INTO contacts (
                        surname,
                        name,
                        lastname,
                        gender,
                        birth_date,
                        phone,
                        address,
                        email,
                        comment
                    )
                    VALUES (
                        :surname,
                        :name,
                        :lastname,
                        :gender,
                        :birth_date,
                        :phone,
                        :address,
                        :email,
                        :comment
                    )
                ");

                // Выполнение запроса с подстановкой значений
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
                ]);

                // Успех: сообщение и очистка полей
                $message = 'Запись добавлена';
                $messageClass = 'success';

                // Сбрасываем значения полей
                foreach ($values as $key => $value) {
                    $values[$key] = '';
                }
            } catch (Throwable $error) {
                // Ошибка БД
                $message = 'Ошибка: запись не добавлена';
                $messageClass = 'error';
            }
        }
    }

    // Начинаем буферизацию вывода (чтобы вернуть HTML строкой)
    ob_start();
    ?>

    <h1>Добавление записи</h1>

    <!-- Вывод сообщения об успехе/ошибке -->
    <?php if ($message !== ''): ?>
        <p class="message <?= e($messageClass) ?>">
            <?= e($message) ?>
        </p>
    <?php endif; ?>

    <!-- Форма добавления контакта -->
    <form class="contact-form" method="post" action="./index.php?action=add">
        <input type="hidden" name="form_action" value="add">

        <!-- Фамилия (обязательное поле) -->
        <div class="form-row">
            <label>Фамилия</label>
            <input type="text" name="surname" value="<?= e($values['surname']) ?>" required>
        </div>

        <!-- Имя (обязательное поле) -->
        <div class="form-row">
            <label>Имя</label>
            <input type="text" name="name" value="<?= e($values['name']) ?>" required>
        </div>

        <!-- Отчество -->
        <div class="form-row">
            <label>Отчество</label>
            <input type="text" name="lastname" value="<?= e($values['lastname']) ?>">
        </div>

        <!-- Пол (выпадающий список) -->
        <div class="form-row">
            <label>Пол</label>
            <select name="gender">
                <option value="">Выберите пол</option>
                <option value="мужской" <?= $values['gender'] === 'мужской' ? 'selected' : '' ?>>мужской</option>
                <option value="женский" <?= $values['gender'] === 'женский' ? 'selected' : '' ?>>женский</option>
            </select>
        </div>

        <!-- Дата рождения (тип date даёт календарик) -->
        <div class="form-row">
            <label>Дата рождения</label>
            <input type="date" name="date" value="<?= e($values['date']) ?>">
        </div>

        <!-- Телефон -->
        <div class="form-row">
            <label>Телефон</label>
            <input type="text" name="phone" value="<?= e($values['phone']) ?>">
        </div>

        <!-- Адрес -->
        <div class="form-row">
            <label>Адрес</label>
            <input type="text" name="location" value="<?= e($values['location']) ?>">
        </div>

        <!-- Email (с валидацией типа email) -->
        <div class="form-row">
            <label>E-mail</label>
            <input type="email" name="email" value="<?= e($values['email']) ?>">
        </div>

        <!-- Комментарий (многострочное поле) -->
        <div class="form-row">
            <label>Комментарий</label>
            <textarea name="comment"><?= e($values['comment']) ?></textarea>
        </div>

        <!-- Кнопка отправки -->
        <button class="form-btn" type="submit">Добавить</button>
    </form>

    <?php
    // Возвращаем накопленный HTML и очищаем буфер
    return ob_get_clean();
}