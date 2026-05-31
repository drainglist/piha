<?php
$pageTitle = $title ?? 'Мой блог';
if (trim($pageTitle) === '') {
    $pageTitle = 'Мой блог';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <!-- ВАЖНО: правильный путь к CSS -->
    <link rel="stylesheet" href="/drainglistpiha/kursach/styles.css">
</head>
<body>

<table class="layout">
    <!-- ШАПКА -->
    <tr>
        <td colspan="2" class="header">
            <img class="logo" src="/drainglistpiha/kursach/logo.png" alt="logo">
            <span class="blog-title">Мой блог</span>
            <span class="header-spacer"></span>
        </td>
    </tr>

    <!-- ОСНОВНОЕ СОДЕРЖИМОЕ И МЕНЮ -->
    <tr>
        <td><?= $content ?></td>
        <td width="300px" class="sidebar">
            <div class="sidebarHeader">Меню</div>
            <ul>
                <li><a href="/drainglistpiha/kursach/">Главная</a></li>
                <li><a href="/drainglistpiha/kursach/articles">Статьи</a></li>
                <li><a href="/drainglistpiha/kursach/articles/create">➕ Добавить статью</a></li>
                <li><a href="/drainglistpiha/kursach/articles/1">Статья №1</a></li>
                <li><a href="/drainglistpiha/kursach/articles/2">Статья №2</a></li>
                <li><a href="/drainglistpiha/kursach/article/1/edit">✏️ Редактировать статью №1</a></li>
                <li><a href="/drainglistpiha/kursach/article/2/edit">✏️ Редактировать статью №2</a></li>
            </ul>
        </td>
    </tr>

    <!-- ПОДВАЛ -->
    <tr>
        <td class="footer" colspan="2">Все права защищены (c) Мой блог</td>
    </tr>
</table>

</body>
</html>