<?php

declare(strict_types=1);

/**
 * Контроллер комментариев
 * Управляет всеми действиями с комментариями
 */
class CommentsController
{
    private int $currentUserId = 1;  // Временно используем ID=1 (admin)

    /**
     * Добавление комментария
     * URL: /articles/1/comments (POST)
     */
    public function store(int $articleId): void
    {
        $article = Article::findById($articleId);
        if ($article === null) {
            $this->notFound();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /drainglistpiha/kursach/articles/' . $articleId);
            exit;
        }

        $text = trim($_POST['text'] ?? '');

        if ($text === '') {
            header('Location: /drainglistpiha/kursach/articles/' . $articleId . '?comment_error=empty');
            exit;
        }

        $commentId = Comment::create($articleId, $this->currentUserId, $text);
        
        // Перенаправляем обратно к статье и прокручиваем к новому комментарию
        header('Location: /drainglistpiha/kursach/articles/' . $articleId . '#comment' . $commentId);
        exit;
    }

    /**
     * Редактирование комментария
     * URL: /comments/1/edit
     */
    public function edit(int $commentId): void
    {
        $comment = Comment::findById($commentId);
        if ($comment === null) {
            $this->notFound();
            return;
        }

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $text = trim($_POST['text'] ?? '');

            if ($text === '') {
                $error = 'Текст комментария не должен быть пустым.';
            } else {
                $comment->update($text);
                $success = 'Комментарий успешно обновлён.';
            }
        }

        $this->render('comments/edit.php', [
            'comment' => $comment,
            'error' => $error,
            'success' => $success,
        ], 'Редактирование комментария');
    }

    /**
     * Удаление комментария
     * URL: /comments/1/delete
     */
    public function delete(int $commentId): void
    {
        $comment = Comment::findById($commentId);
        if ($comment === null) {
            $this->notFound();
            return;
        }

        $articleId = $comment->articleId;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /drainglistpiha/kursach/articles/' . $articleId . '#comment' . $commentId);
            exit;
        }

        $comment->delete();
        header('Location: /drainglistpiha/kursach/articles/' . $articleId);
        exit;
    }

    private function notFound(): void
    {
        http_response_code(404);
        echo '404. Страница не найдена.';
    }

    private function render(string $view, array $params = [], ?string $title = null): void
    {
        extract($params);
        ob_start();
        require __DIR__ . '/../../views/' . $view;
        $content = ob_get_clean();
        $pageTitle = $title;
        require __DIR__ . '/../../main.php';
    }
}