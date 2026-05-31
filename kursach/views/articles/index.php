<h2>Список статей</h2>

<!-- Кнопка добавления новой статьи -->
<p>
    <a href="/drainglistpiha/kursach/articles/create">➕ Добавить новую статью</a>
</p>

<?php if (empty($articles)): ?>
    <p>Статей пока нет. Будьте первым, кто напишет статью!</p>
<?php else: ?>
    <?php foreach ($articles as $article): ?>
        <article class="article-card">
            <!-- Заголовок статьи - ссылка на полную версию -->
            <h3>
                <a href="/drainglistpiha/kursach/articles/<?= $article->id ?>">
                    <?= htmlspecialchars($article->name, ENT_QUOTES, 'UTF-8') ?>
                </a>
            </h3>

            <!-- Краткий анонс (первые 120 символов) -->
            <p>
                <?= htmlspecialchars(mb_substr($article->text, 0, 120), ENT_QUOTES, 'UTF-8') ?>...
            </p>

            <!-- Дата создания -->
            <p class="date">
                📅 Дата создания: <?= htmlspecialchars($article->createdAt, ENT_QUOTES, 'UTF-8') ?>
            </p>

            <!-- Действия со статьёй -->
            <div class="actions">
                <a href="/drainglistpiha/kursach/article/<?= $article->id ?>/edit">✏️ Редактировать</a>

                <!-- Форма удаления статьи (POST запрос) -->
                <form
                    method="post"
                    action="/drainglistpiha/kursach/articles/<?= $article->id ?>/delete"
                    class="inline-form"
                    onsubmit="return confirm('Удалить статью? Вместе со статьёй удалятся все её комментарии.');"
                >
                    <button type="submit" class="danger-button">🗑️ Удалить</button>
                </form>
            </div>
        </article>
    <?php endforeach; ?>
<?php endif; ?>