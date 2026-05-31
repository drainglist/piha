<h2>✏️ Редактирование статьи</h2>

<!-- Вывод сообщения об ошибке -->
<?php if ($error !== ''): ?>
    <p class="message error">❌ <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>

<!-- Вывод сообщения об успехе -->
<?php if ($success !== ''): ?>
    <p class="message success">✅ <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>

<!-- Форма редактирования статьи -->
<form method="post" action="/drainglistpiha/kursach/article/<?= $article->id ?>/edit" class="article-form">
    <!-- Поле "Название" -->
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

    <!-- Поле "Текст" -->
    <div class="form-group">
        <label for="text">Текст статьи</label>
        <textarea id="text" name="text" rows="12" required><?= htmlspecialchars($article->text, ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>

    <button type="submit">💾 Сохранить изменения</button>
</form>

<!-- Ссылки -->
<p>
    <a href="/drainglistpiha/kursach/articles/<?= $article->id ?>">← Вернуться к статье</a>
</p>
<p>
    <a href="/drainglistpiha/kursach/articles">← Назад к списку статей</a>
</p>