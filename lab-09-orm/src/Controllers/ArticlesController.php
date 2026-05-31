<?php

declare(strict_types=1);

class ArticlesController
{
    // Список всех статей
    public function index(): void
    {
        $articles = Article::findAll();  // Берём все статьи из БД

        $this->render('articles/index.php', [
            'articles' => $articles,
        ]);
    }

    // Показ одной статьи
    public function show(int $id): void
    {
        $article = Article::findById($id);  // Находим статью по ID

        // Если статьи нет - 404
        if ($article === null) {
            $this->notFound();
            return;
        }

        // Получаем автора статьи (по authorId)
        $author = User::findById($article->authorId);

        $this->render('articles/show.php', [
            'article' => $article,
            'author' => $author,
        ], $article->name);
    }

    // Страница 404
    public function notFound(): void
    {
        http_response_code(404);

        $this->render('articles/show.php', [
            'article' => null,
            'author' => null,
        ], '404');
    }

    // Рендеринг (отображение страницы)
    private function render(string $view, array $params = [], ?string $title = null): void
    {
        extract($params);  // Превращает ключи массива в переменные

        ob_start();
        require __DIR__ . '/../../views/' . $view;
        $content = ob_get_clean();

        $pageTitle = $title;  // Передаём заголовок в шаблон
        require __DIR__ . '/../../main.php';
    }
}