<?php
// Если заголовок не передан, используем "Мой блог"
$pageTitle = $pageTitle ?? 'Мой блог';
if (trim($pageTitle) === '') {
    $pageTitle = 'Мой блог';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/drainglistpiha/lab-08-view/styles.css">
</head>
<body>

<table class="layout">
    <!-- Шапка -->
    <tr>
        <td colspan="2" class="header">
            <img class="logo" src="/drainglistpiha/lab-08-view/logo.png" alt="logo">
            <span class="blog-title"><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></span>
            <span class="header-spacer"></span>
        </td>
    </tr>

    <!-- Основное содержимое и меню -->
    <tr>
        <td><?= $content ?></td>
        <td width="300px" class="sidebar">
            <div class="sidebarHeader">Меню</div>
            <ul>
                <li><a href="/drainglistpiha/lab-08-view/">Главная</a></li>
                <li><a href="/drainglistpiha/lab-08-view/about-me">Обо мне</a></li>
                <li><a href="/drainglistpiha/lab-08-view/hello/Иван">Поздороваться с Иваном</a></li>
                <li><a href="/drainglistpiha/lab-08-view/bye/Иван">Попрощаться с Иваном</a></li>
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