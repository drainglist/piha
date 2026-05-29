<?php

declare(strict_types=1);

// Родительский класс Lesson
class Lesson
{
    private string $title;
    private string $text;
    private string $homework;

    public function __construct(string $title, string $text, string $homework)
    {
        $this->title = $title;
        $this->text = $text;
        $this->homework = $homework;
    }

    // Геттеры и сеттеры
    public function getTitle(): string { return $this->title; }
    public function setTitle(string $title): void { $this->title = $title; }
    public function getText(): string { return $this->text; }
    public function setText(string $text): void { $this->text = $text; }
    public function getHomework(): string { return $this->homework; }
    public function setHomework(string $homework): void { $this->homework = $homework; }
}

// Наследник - платный урок (по ТЗ)
class PaidLesson extends Lesson
{
    private float $price;  // Цена (новое свойство)

    // Конструктор: всё из родителя + цена
    public function __construct(string $title, string $text, string $homework, float $price)
    {
        parent::__construct($title, $text, $homework);
        $this->price = $price;
    }

    // Геттер и сеттер для цены
    public function getPrice(): float { return $this->price; }
    public function setPrice(float $price): void { $this->price = $price; }
}

// Создаём объект строго по ТЗ
$lesson = new PaidLesson(
    'Урок о наследовании в PHP',      // заголовок
    'Чипинкос',              // текст
    'Ура',  // домашка
    99.90                              // цена
);

// Выводим объект через var_dump()
var_dump($lesson);