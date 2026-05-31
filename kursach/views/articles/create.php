<h2>📝 Создание новой статьи</h2>

<!-- Вывод сообщения об ошибке -->
<?php if ($error !== ''): ?>
    <p class="message error">❌ <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>

<!-- Форма создания статьи -->
<form method="post" action="/drainglistpiha/kursach/articles/create" class="article-form">
    <!-- Поле "Название" -->
    <div class="form-group">
        <label for="name">Название статьи</label>
        <input
            type="text"
            id="name"
            name="name"
            value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
            placeholder="Введите заголовок статьи..."
            required
        >
    </div>

    <!-- Поле "Текст" -->
    <div class="form-group">
        <label for="text">Текст статьи</label>
        <textarea id="text" name="text" rows="12" placeholder="Введите текст статьи..." required><?= htmlspecialchars($text, ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>

    <button type="submit">✅ Создать статью</button>
</form>

<!-- Ссылка назад -->
<p>
    <a href="/drainglistpiha/kursach/articles">← Назад к списку статей</a>
</p>