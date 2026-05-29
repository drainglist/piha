<?php

declare(strict_types=1);  // Строгий режим типов

/**
 * Контроллер блога - обрабатывает все действия на сайте
 */
class BlogController
{
    /**
     * Главная страница - показывает список статей
     */
    public function index(): void
    {
        // Контент главной страницы
        $content = '
            <h2>Статья 1</h2>
            <p>Текст первой статьи</p>
            <hr>

            <h2>Статья 2</h2>
            <p>Текст второй статьи</p>
        ';

        // Отправляем на рендер (отображение)
        $this->render('Главная страница', $content);
    }

    /**
     * Страница "Обо мне"
     */
    public function aboutMe(): void
    {
        // Контент страницы о себе
        $content = '
            <h2>Обо мне</h2>
            <p>Привет! Это страница с краткой информацией обо мне.</p>
            <p>Текст обо мне</p>
        ';

        $this->render('Обо мне', $content);
    }

    /**
     * Страница прощания с пользователем
     * @param string $name Имя пользователя (из URL)
     */
    public function sayBye(string $name): void
    {
        // Защита от XSS-атак (экранируем спецсимволы)
        $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');

        // Контент страницы прощания
        $content = '
            <h2>Страница прощания</h2>
            <p>Пока, ' . $safeName . '</p>
        ';

        $this->render('Пока, ' . $safeName, $content);
    }

    /**
     * Страница 404 (не найдено)
     * Вызывается, когда нет подходящего маршрута
     */
    public function notFound(): void
    {
        // Отправляем HTTP-статус 404
        http_response_code(404);

        // Контент страницы ошибки
        $content = '
            <h2>404</h2>
            <p>Страница не найдена.</p>
        ';

        $this->render('404', $content);
    }

    /**
     * Рендеринг (отображение) страницы
     * @param string $title Заголовок страницы
     * @param string $content Основной контент страницы
     */
    private function render(string $title, string $content): void
    {
        // Подключаем шаблон main.php (он находится на уровень выше)
        require __DIR__ . '/../main.php';
    }
}