<?php
// Тестовый URL-адрес стороннего сервера, к которому мы будем отправлять запрос.
// httpbin.org — это специальный сервис, который возвращает заголовки для тестов.
$url = 'https://httpbin.org/get';

// get_headers() — встроенная функция PHP. Она делает быстрый HTTP-запрос к указанному URL 
// и возвращает массив со всеми заголовками, которые прислал удаленный сервер в ответ.
$headers = get_headers($url);

// Проверяем результат работы функции на ошибку (например, если нет интернета или URL не существует).
if ($headers === false) {
    $headersOutput = 'Не удалось получить заголовки.';
} else {
    // print_r($headers, true) — преобразует массив заголовков в читаемый текстовый вид.
    // Параметр 'true' заставляет функцию не выводить текст сразу на экран, а сохранить его в переменную.
    $headersOutput = print_r($headers, true);
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>get_headers</title>
    <!-- Подключение внешнего файла стилей CSS, который лежит в этой же папке -->
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <header class="header">
        <img src="logo.png" alt="logo" class="logo">
        <div class="title">Результат работы функции get_headers</div>
        <div class="header-spacer"></div>
    </header>

    <main class="main">
        <section class="card">
            <h1>Заголовки ответа сервера</h1>

            <p class="description">
                <!-- htmlspecialchars() — важнейшая функция безопасности. Она преобразует спецсимволы 
                     (например, '<' и '>') в безопасные HTML-сущности. Защищает сайт от XSS-атак (внедрения чужого кода) -->
                URL для проверки: <?= htmlspecialchars($url) ?>
            </p>

            <!-- textarea — текстовое поле. Атрибут readonly запрещает пользователю редактировать текст внутри -->
            <textarea class="headers-result" readonly><?= htmlspecialchars($headersOutput) ?></textarea>

            <!-- Обычная ссылка-кнопка для возврата на главную страницу с формой ввода -->
            <a class="link-button" href="./index.php">
                Вернуться к форме
            </a>
        </section>
    </main>

    <footer class="footer">
        задание для самостоятельно работы
    </footer>
</body>
</html>
