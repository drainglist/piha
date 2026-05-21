<?php
// __DIR__ — это встроенная константа, которая возвращает полный путь к текущей папке.
// Здесь мы формируем абсолютный путь к файлу конфигурации '.env'.
$envPath = __DIR__ . '/.env';

// Проверяем, существует ли файл .env на сервере.
if (file_exists($envPath)) {
    // Функция parse_ini_file считывает файл конфигурации и превращает его в ассоциативный массив.
    $env = parse_ini_file($envPath);
} else {
    // Если файла .env нет, создаем пустой массив, чтобы не вызвать ошибку при чтении данных.
    $env = [];
}

// Пытаемся достать имя студента из массива $env по ключу 'STUDENT_NAME'.
// Оператор '??' (null-coalescing) подставит текст справа, если ключ не найден или равен null.
$studentName = $env['STUDENT_NAME'] ?? 'Голубев Егор Денисович';

// Получаем текущую дату (день.месяц.год) и время (часы:минуты:секунды) в формате строк.
$currentDate = date("d.m.Y");
$currentTime = date("H:i:s");

// Создаем обычный массив со списком приветственных сообщений.
$messages = [
    "Hello, World!",
    "PHP генерирует этот текст динамически.",
    "Это первая лабораторная"
];

// array_rand выбирает случайный КЛЮЧ (индекс) из массива $messages.
// Затем мы достаем само сообщение по этому случайному ключу.
$randomMessage = $messages[array_rand($messages)];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hello, World! — Лабораторная работа</title>

    <style>
        /* Сброс стандартных отступов браузера */
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            color: #1f2937;
        }

        /* Шапка страницы */
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 40px;
            background: rgba(32, 62, 169, 0.6); 
            border-bottom: 1px solid #e5e7eb;
        }

        .logo {
            max-height: 50px;
            width: auto;
        }

        .title {
            flex: 1; 
            text-align: center;
            font-size: 22px;
            font-weight: 700;
            color: #ffffff;
        }

        /* Основной контент страницы */
        main {
            min-height: calc(100vh - 160px); 
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        /* Белая карточка */
        .card {
            max-width: 600px;
            width: 100%;
            padding: 32px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        .card h1 {
            margin-top: 0;
            font-size: 36px;
            color: #111827;
        }

        /* Случайное сообщение */
        .message {
            font-size: 22px;
            margin: 24px 0;
            color: #d5001c;
            font-weight: 700;
        }

        .info {
            line-height: 1.7;
            color: #4b5563;
        }

        /* Подвал сайта */
        footer {
            padding: 20px;
            text-align: center;
            background: #111827;
            color: #ffffff;
        }
    </style>
</head>
<body>
    <header>
        <img class="logo" src="logo.png" alt="Логотип">
        <div class="title">Домашняя работа: Hello, World!</div>
        <div></div> 
    </header>

    <main>
        <section class="card">
            <h1>Hello, World!</h1>

            <div class="message">
                <?= $randomMessage ?>
            </div>

            <div class="info">
                <p>Студент: <?= $studentName ?></p>
                <p>Сегодня: <?= $currentDate ?></p>
                <p>Текущее время сервера: <?= $currentTime ?></p>
            </div>
        </section>
    </main>

    <footer>
        задание для самостоятельной работы
    </footer>
</body>
</html>
