<?php

declare(strict_types=1);

class Cat
{
    private string $name;   // Имя (приватное)
    private string $color;  // Цвет (приватное, добавлен по ТЗ)

    // Конструктор принимает имя и цвет
    public function __construct(string $name, string $color)
    {
        $this->name = $name;
        $this->color = $color;
    }

    // Геттер для имени
    public function getName(): string
    {
        return $this->name;
    }

    // Геттер для цвета (по ТЗ)
    public function getColor(): string
    {
        return $this->color;
    }

    // Приветствие с указанием цвета (по ТЗ)
    public function sayHello(): string
    {
        return 'Мяу! Меня зовут ' . $this->getName() . '. Я ' . $this->getColor() . ' цвета.';
    }
}

// Создаём кошку с именем и цветом
$cat = new Cat('Мика', 'рыжая');

echo $cat->sayHello() . PHP_EOL;