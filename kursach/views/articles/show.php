<?php if ($article === null): ?>
    <!-- Если статья не найдена -->
    <h2>404</h2>
    <p>Статья не найдена.</p>

<?php else: ?>
    <!-- ==================== СТАТЬЯ ==================== -->
    <article class="article-page">
        <h2><?= htmlspecialchars($article->name, ENT_QUOTES, 'UTF-8') ?></h2>

        <!-- Информация об авторе -->
        <p class="author">
            👤 Автор:
            <?php if ($author !== null): ?>
                <strong><?= htmlspecialchars($author->nickname, ENT_QUOTES, 'UTF-8') ?></strong>
            <?php else: ?>
                <strong>не найден</strong>
            <?php endif; ?>
        </p>

        <!-- Дата создания -->
        <p class="date">
            📅 Дата создания: <?= htmlspecialchars($article->createdAt, ENT_QUOTES, 'UTF-8') ?>
        </p>

        <!-- Текст статьи (nl2br превращает переносы строк в <br>) -->
        <p>
            <?= nl2br(htmlspecialchars($article->text, ENT_QUOTES, 'UTF-8')) ?>
        </p>

        <!-- Действия со статьёй -->
        <div class="actions">
            <a href="/drainglistpiha/kursach/article/<?= $article->id ?>/edit">✏️ Редактировать</a>

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

    <hr>

    <!-- ==================== КОММЕНТАРИИ ==================== -->
    <section class="comments">
        <h2>💬 Комментарии</h2>

        <!-- Сообщение об ошибке при пустом комментарии -->
        <?php if (isset($_GET['comment_error']) && $_GET['comment_error'] === 'empty'): ?>
            <p class="message error">❌ Комментарий не может быть пустым.</p>
        <?php endif; ?>

        <!-- Список комментариев -->
        <?php if (empty($comments)): ?>
            <p>Комментариев пока нет. Будьте первым, кто оставит комментарий!</p>
        <?php else: ?>
            <?php foreach ($comments as $comment): ?>
                <!-- Якорь для перехода к комментарию после добавления/редактирования -->
                <div class="comment" id="comment<?= $comment->id ?>">
                    <!-- Шапка комментария: автор + дата -->
                    <p class="comment-meta">
                        <strong>
                            <?= htmlspecialchars($comment->authorNickname ?? 'Неизвестный автор', ENT_QUOTES, 'UTF-8') ?>
                        </strong>
                        <span>
                            📅 <?= htmlspecialchars($comment->createdAt, ENT_QUOTES, 'UTF-8') ?>
                        </span>
                    </p>

                    <!-- Текст комментария -->
                    <p>
                        <?= nl2br(htmlspecialchars($comment->text, ENT_QUOTES, 'UTF-8')) ?>
                    </p>

                    <!-- Действия с комментарием -->
                    <div class="actions">
                        <a href="/drainglistpiha/kursach/comments/<?= $comment->id ?>/edit">✏️ Редактировать</a>

                        <form
                            method="post"
                            action="/drainglistpiha/kursach/comments/<?= $comment->id ?>/delete"
                            class="inline-form"
                            onsubmit="return confirm('Удалить комментарий?');"
                        >
                            <button type="submit" class="danger-button">🗑️ Удалить</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- ==================== ФОРМА ДОБАВЛЕНИЯ КОММЕНТАРИЯ ==================== -->
        <h3>✏️ Добавить комментарий</h3>

        <form method="post" action="/drainglistpiha/kursach/articles/<?= $article->id ?>/comments" class="comment-form">
            <div class="form-group">
                <label for="text">Текст комментария</label>
                <textarea id="text" name="text" rows="5" placeholder="Напишите свой комментарий..." required></textarea>
            </div>

            <button type="submit">💬 Отправить комментарий</button>
        </form>
    </section>

    <!-- Ссылка назад -->
    <p>
        <a href="/drainglistpiha/kursach/">← Назад к списку статей</a>
    </p>

<?php endif; ?>