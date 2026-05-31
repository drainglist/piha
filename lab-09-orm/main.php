<?php
// Заголовок страницы (если не передан - "Мой блог")
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
    <!-- ===== ВАЖНО: правильный путь к CSS ===== -->
    <link rel="stylesheet" href="/drainglistpiha/lab-09-orm/styles.css">
</head>
<body>

<table class="layout">
    <!-- Шапка -->
    <tr>
        <td colspan="2" class="header">
            <!-- ===== ВАЖНО: правильный путь к лого ===== -->
            <img class="logo" src="/drainglistpiha/lab-09-orm/logo.png" alt="logo">
            <span class="blog-title">Мой блог</span>
            <span class="header-spacer"></span>
        </td>
    </tr>

    <!-- Основное содержимое и меню -->
    <tr>
        <td><?= $content ?></td>
        <td width="300px" class="sidebar">
            <div class="sidebarHeader">Меню</div>
            <ul>
                <!-- ===== ВАЖНО: правильные ссылки ===== -->
                <li><a href="/drainglistpiha/lab-09-orm/">Главная</a></li>
                <li><a href="/drainglistpiha/lab-09-orm/articles">Статьи</a></li>
                <li><a href="/drainglistpiha/lab-09-orm/articles/1">Статья №1</a></li>
                <li><a href="/drainglistpiha/lab-09-orm/articles/2">Статья №2</a></li>
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