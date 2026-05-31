<?php

declare(strict_types=1);

/**
 * Контроллер статей
 * Обрабатывает все запросы, связанные со статьями
 */
class ArticlesController
{
    /**
     * Показать список всех статей
     * URL: / или /articles
     */
    public function index(): void
    {
        $articles = Article::findAll();  // Получаем все статьи из БД
        $this->render('articles/index.php', ['articles' => $articles]);
    }

    /**
     * Показать одну статью по ID
     * URL: /articles/1
     */
    public function show(int $id): void
    {
        $article = Article::findById($id);  // Ищем статью
        
        if ($article === null) {
            $this->notFound();  // Если нет – 404
            return;
        }
        
        $author = User::findById($article->authorId);  // Получаем автора
        
        $this->render('articles/show.php', [
            'article' => $article,
            'author' => $author,
        ], $article->name);
    }

    /**
     * Редактирование статьи
     * URL: /article/1/edit
     * Поддерживает GET (показать форму) и POST (сохранить изменения)
     */
    public function edit(int $id): void
    {
        $article = Article::findById($id);  // Ищем статью
        
        if ($article === null) {
            $this->notFound();
            return;
        }
        
        $error = '';    // Сообщение об ошибке
        $success = '';  // Сообщение об успехе
        
        // Если форма отправлена методом POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');   // Название статьи
            $text = trim($_POST['text'] ?? '');   // Текст статьи
            
            // Проверка на пустые поля
            if ($name === '' || $text === '') {
                $error = 'Название и текст статьи не должны быть пустыми.';
            } else {
                $article->update($name, $text);  // Обновляем статью в БД
                $success = 'Статья успешно обновлена.';
            }
        }
        
        // Рендерим форму редактирования
        $this->render('articles/edit.php', [
            'article' => $article,
            'error' => $error,
            'success' => $success,
        ], 'Редактирование статьи');
    }

    /**
     * Страница 404 (не найдено)
     */
    public function notFound(): void
    {
        http_response_code(404);  // Отправляем HTTP-статус 404
        $this->render('articles/show.php', [
            'article' => null,
            'author' => null,
        ], '404');
    }

    /**
     * Рендеринг (отображение) страницы
     * @param string $view   Путь к шаблону (относительно папки views)
     * @param array $params  Параметры для шаблона (станут переменными)
     * @param string|null $title Заголовок страницы
     */
    private function render(string $view, array $params = [], ?string $title = null): void
    {
        extract($params);  // ['article' => $article] → $article = ...
        
        ob_start();  // Начинаем буферизацию вывода
        require __DIR__ . '/../../views/' . $view;  // Подключаем шаблон
        $content = ob_get_clean();  // Сохраняем вывод в переменную $content
        
        $pageTitle = $title;  // Передаём заголовок в шаблон
        require __DIR__ . '/../../main.php';  // Подключаем главный шаблон
    }
}