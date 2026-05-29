<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <!-- Заголовок страницы (экранирован для безопасности) -->
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/drainglistpiha/lab-07-routing/styles.css">
</head>
<body>

<table class="layout">
    <!-- Шапка сайта -->
    <tr>
        <td colspan="2" class="header">
            <img class="logo" src="/drainglistpiha/lab-07-routing/logo.png" alt="logo">
            <span class="blog-title">Мой блог</span>
            <span class="header-spacer"></span>
        </td>
    </tr>

    <tr>
        <!-- Основной контент -->
        <td>
            <?= $content ?>
        </td>

        <!-- Боковое меню -->
        <td width="300px" class="sidebar">
            <div class="sidebarHeader">Меню</div>
            <ul>
                <li><a href="/drainglistpiha/lab-07-routing/">Главная страница</a></li>
                <li><a href="/drainglistpiha/lab-07-routing/about-me">Обо мне</a></li>
                <li><a href="/drainglistpiha/lab-07-routing/bye/Егор">Попрощаться с Егором</a></li>
            </ul>
        </td>
    </tr>

    <!-- Подвал -->
    <tr>
        <td class="footer" colspan="2">Все права защищены (c) Мой блог</td>
    </tr>
</table>

</body>
</html>