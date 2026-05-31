<h2>Редактирование статьи</h2>

<!-- Выводим сообщение об ошибке, если есть -->
<?php if ($error !== ''): ?>
    <p class="message error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>

<!-- Выводим сообщение об успехе, если есть -->
<?php if ($success !== ''): ?>
    <p class="message success"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>

<!-- Форма редактирования. action ведёт на тот же URL, что и GET-запрос -->
<form method="post" action="/drainglistpiha/lab-10-edit-article/article/<?= $article->id ?>/edit" class="article-form">
    <div class="form-group">
        <label for="name">Название статьи</label>
        <input
            type="text"
            id="name"
            name="name"
            value="<?= htmlspecialchars($article->name, ENT_QUOTES, 'UTF-8') ?>"
            required
        >
    </div>

    <div class="form-group">
        <label for="text">Текст статьи</label>
        <textarea id="text" name="text" rows="10" required><?= htmlspecialchars($article->text, ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>

    <button type="submit">Сохранить</button>
</form>

<p>
    <a href="/drainglistpiha/lab-10-edit-article/articles/<?= $article->id ?>">← Вернуться к статье</a>
</p>