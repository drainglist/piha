<h2>✏️ Редактирование комментария</h2>

<!-- Вывод сообщения об ошибке -->
<?php if ($error !== ''): ?>
    <p class="message error">❌ <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>

<!-- Вывод сообщения об успехе -->
<?php if ($success !== ''): ?>
    <p class="message success">✅ <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>

<!-- Форма редактирования комментария -->
<form method="post" action="/drainglistpiha/kursach/comments/<?= $comment->id ?>/edit" class="comment-form">
    <div class="form-group">
        <label for="text">Текст комментария</label>
        <textarea id="text" name="text" rows="7" required><?= htmlspecialchars($comment->text, ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>

    <button type="submit">💾 Сохранить изменения</button>
</form>

<!-- Ссылка назад (прямо к этому комментарию на странице статьи) -->
<p>
    <a href="/drainglistpiha/kursach/articles/<?= $comment->articleId ?>#comment<?= $comment->id ?>">
        ← Вернуться к статье
    </a>
</p>