<?php

declare(strict_types=1);

class BlogController
{
    // Главная страница
    public function index(): void
    {
        $content = '
            <h2>Статья 1</h2>
            <p>Текст первой статьи</p>
            <hr>
            <h2>Статья 2</h2>
            <p>Текст второй статьи</p>
        ';
        $this->render($content);
    }

    // Страница "Обо мне"
    public function aboutMe(): void
    {
        $content = '
            <h2>Обо мне</h2>
            <p>Привет! Это страница с краткой информацией обо мне.</p>
            <p>Текст обо мне</p>
        ';
        $this->render($content, 'Обо мне');
    }

    // Приветствие пользователя по имени
    public function sayHello(string $username): void
    {
        // Защита от XSS
        $safeName = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
        $content = '
            <h2>Страница приветствия</h2>
            <p>Привет, ' . $safeName . '!</p>
        ';
        $this->render($content, 'Страница приветствия');
    }

    // Прощание с пользователем
    public function sayBye(string $name): void
    {
        $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $content = '
            <h2>Страница прощания</h2>
            <p>Пока, ' . $safeName . '</p>
        ';
        $this->render($content, 'Страница прощания');
    }

    // Страница 404
    public function notFound(): void
    {
        http_response_code(404);
        $content = '
            <h2>404</h2>
            <p>Страница не найдена.</p>
        ';
        $this->render($content, '404');
    }

    // Рендеринг шаблона
    private function render(string $content, ?string $title = null): void
    {
        $pageTitle = $title;  // Передаём заголовок в шаблон
        require __DIR__ . '/../main.php';
    }
}