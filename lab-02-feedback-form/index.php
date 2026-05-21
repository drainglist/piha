<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Form</title>
    <!-- Подключение файла стилей CSS -->
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <header class="header">
        <img class='logo' src="logo.png" alt="logo">
        <div class="title">Домашняя работа: Feedback Form</div>
        <div class="header-spacer"></div>
    </header>

    <main class="main">
        <section class="card">
            <h1>Форма обратной связи</h1>

            <!-- Тег form собирает данные. method="POST" отправляет их скрытно в теле запроса -->
            <form class="form" action="https://httpbin.org" method="POST">
                
                <!-- Поле ввода имени -->
                <label class="form-field">
                    <span>Имя пользователя</span>
                    <input 
                        type="text" 
                        name="username" 
                        placeholder="Введите имя" 
                        required
                    >
                </label>

                <!-- Поле ввода почты с автоматической валидацией формата -->
                <label class="form-field">
                    <span>E-mail пользователя</span>
                    <input 
                        type="email" 
                        name="email" 
                        placeholder="example@mail.com" 
                        required
                    >
                </label>

                <!-- Выпадающий список выбора типа обращения -->
                <label class="form-field">
                    <span>Тип обращения</span>
                    <select name="request_type" required>
                        <option value="">Выберите тип обращения</option>
                        <option value="complaint">Жалоба</option>
                        <option value="suggestion">Предложение</option>
                        <option value="thanks">Благодарность</option>
                    </select>
                </label>

                <!-- Многострочное текстовое поле -->
                <label class="form-field">
                    <span>Текст обращения</span>
                    <textarea 
                        name="message" 
                        rows="6" 
                        placeholder="Введите текст обращения" 
                        required
                    ></textarea>
                </label>

                <!-- Группа чекбоксов. Квадратные скобки [] передают выбранные ответы на сервер в виде массива -->
                <fieldset class="checkbox-group">
                    <legend>Вариант ответа</legend>

                    <label>
                        <input type="checkbox" name="response_type[]" value="sms">
                        СМС
                    </label>

                    <label>
                        <input type="checkbox" name="response_type[]" value="email">
                        E-mail
                    </label>
                </fieldset>

                <!-- Кнопка отправки формы на сервер httpbin.org -->
                <button class="button" type="submit">
                    Отправить
                </button>
            </form>

            <!-- Кнопка-ссылка для перехода на вторую страницу -->
            <a class="link-button" href="./headers.php">
                Перейти на 2 страницу
            </a>
        </section>
    </main>

    <footer class="footer">
        задание для самостоятельно работы
    </footer>
</body>
</html>
