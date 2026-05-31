<?php

declare(strict_types=1);

/**
 * Контроллер статей
 * Управляет всеми действиями со статьями
 */
class ArticlesController
{
    /**
     * Показать список всех статей
     * URL: / или /articles
     */
    public function index(): void
    {
        $articles = Article::findAll();  // Получаем все статьи
        $this->render('articles/index.php', ['articles' => $articles]);
    }

    /**
     * Показать одну статью (с комментариями)
     * URL: /articles/1
     */
    public function show(int $id): void
    {
        $article = Article::findById($id);
        if ($article === null) {
            $this->notFound();
            return;
        }

        $author = User::findById($article->authorId);     // Автор статьи
        $comments = Comment::findByArticleId($article->id); // Комментарии

        $this->render('articles/show.php', [
            'article' => $article,
            'author' => $author,
            'comments' => $comments,
        ], $article->name);
    }

    /**
     * Создание новой статьи
     * URL: /articles/create
     */
    public function create(): void
    {
        $error = '';
        $name = '';
        $text = '';
        $currentUserId = 1;  // Временно используем ID=1 (admin)

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $text = trim($_POST['text'] ?? '');

            if ($name === '' || $text === '') {
                $error = 'Название и текст статьи не должны быть пустыми.';
            } else {
                $articleId = Article::create($currentUserId, $name, $text);
                header('Location: /drainglistpiha/kursach/articles/' . $articleId);
                exit;
            }
        }

        $this->render('articles/create.php', [
            'error' => $error,
            'name' => $name,
            'text' => $text,
        ], 'Создание статьи');
    }

    /**
     * Редактирование статьи
     * URL: /article/1/edit
     */
    public function edit(int $id): void
    {
        $article = Article::findById($id);
        if ($article === null) {
            $this->notFound();
            return;
        }

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $text = trim($_POST['text'] ?? '');

            if ($name === '' || $text === '') {
                $error = 'Название и текст статьи не должны быть пустыми.';
            } else {
                $article->update($name, $text);
                $success = 'Статья успешно обновлена.';
            }
        }

        $this->render('articles/edit.php', [
            'article' => $article,
            'error' => $error,
            'success' => $success,
        ], 'Редактирование статьи');
    }

    /**
     * Удаление статьи (вместе со всеми комментариями)
     * URL: /articles/1/delete
     */
    public function delete(int $id): void
    {
        $article = Article::findById($id);
        if ($article === null) {
            $this->notFound();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /drainglistpiha/kursach/articles/' . $id);
            exit;
        }

        $article->delete();  // Удаляем статью (комментарии удаляются каскадом)
        header('Location: /drainglistpiha/kursach/articles');
        exit;
    }

    /**
     * Страница 404
     */
    public function notFound(): void
    {
        http_response_code(404);
        $this->render('articles/show.php', [
            'article' => null,
            'author' => null,
            'comments' => [],
        ], '404');
    }

    /**
     * Рендеринг шаблона
     */
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