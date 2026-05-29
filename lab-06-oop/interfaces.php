<?php

declare(strict_types=1);

// Интерфейс для фигур с площадью
interface CalculateSquare
{
    public function calculateSquare(): float;
}

// Круг (реализует интерфейс)
class Circle implements CalculateSquare
{
    private float $radius;

    public function __construct(float $radius)
    {
        $this->radius = $radius;
    }

    public function calculateSquare(): float
    {
        return pi() * $this->radius * $this->radius;
    }
}

// Прямоугольник (реализует интерфейс)
class Rectangle implements CalculateSquare
{
    private float $width;
    private float $height;

    public function __construct(float $width, float $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    public function calculateSquare(): float
    {
        return $this->width * $this->height;
    }
}

// Пользователь (НЕ реализует интерфейс)
class User
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}

// Функция вывода информации (использует get_class() по ТЗ)
function printSquareInfo(object $object): void
{
    $className = get_class($object);  // get_class() - по требованию ТЗ

    if ($object instanceof CalculateSquare) {
        echo 'Объект класса ' . $className . ' реализует интерфейс CalculateSquare.' . PHP_EOL;
        echo 'Площадь: ' . round($object->calculateSquare(), 2) . PHP_EOL;
    } else {
        // Сообщение для объектов без интерфейса
        echo 'Объект класса ' . $className . ' не реализует интерфейс CalculateSquare.' . PHP_EOL;
    }
}

// Массив объектов для проверки
$objects = [
    new Circle(5),
    new Rectangle(4, 6),
    new User('Егор'),
];

// Выводим результат
foreach ($objects as $object) {
    printSquareInfo($object);
    echo PHP_EOL;
}