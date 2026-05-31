<?php
// Устанавливаем заголовок страницы (если не передан - "Мой блог")
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
    <link rel="stylesheet" href="/drainglistpiha/lab-10-edit-article/styles.css">
</head>
<body>

<table class="layout">
    <!-- ШАПКА САЙТА -->
    <tr>
        <td colspan="2" class="header">
            <!-- ВАЖНО: правильный путь к логотипу -->
            <img class="logo" src="/drainglistpiha/lab-10-edit-article/logo.png" alt="logo">
            <span class="blog-title">Мой блог</span>
            <span class="header-spacer"></span>
        </td>
    </tr>

    <!-- ОСНОВНАЯ ЧАСТЬ + МЕНЮ -->
    <tr>
        <td><?= $content ?></td>
        <td width="300px" class="sidebar">
            <div class="sidebarHeader">Меню</div>
            <ul>
                <!-- ВСЕ ССЫЛКИ ДОЛЖНЫ БЫТЬ С ПОЛНЫМ ПУТЁМ -->
                <li><a href="/drainglistpiha/lab-10-edit-article/">Главная</a></li>
                <li><a href="/drainglistpiha/lab-10-edit-article/articles">Статьи</a></li>
                <li><a href="/drainglistpiha/lab-10-edit-article/articles/1">Статья №1</a></li>
                <li><a href="/drainglistpiha/lab-10-edit-article/articles/2">Статья №2</a></li>
                <li><a href="/drainglistpiha/lab-10-edit-article/article/1/edit">Редактировать статью №1</a></li>
                <li><a href="/drainglistpiha/lab-10-edit-article/article/2/edit">Редактировать статью №2</a></li>
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