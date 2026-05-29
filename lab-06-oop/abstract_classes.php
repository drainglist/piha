<?php

declare(strict_types=1);

// Абстрактный класс Human
abstract class HumanAbstract
{
    private string $name;  // Имя (приватное)

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    // Абстрактные методы (должны быть реализованы в наследниках)
    abstract public function getGreetings(): string;
    abstract public function getMyNameIs(): string;

    // Метод представления (работает через абстрактные методы)
    public function introduceYourself(): string
    {
        return $this->getGreetings() . '! ' . $this->getMyNameIs() . ' ' . $this->getName() . '.';
    }
}

// Русский человек
class RussianHuman extends HumanAbstract
{
    public function getGreetings(): string
    {
        return 'Привет';
    }

    public function getMyNameIs(): string
    {
        return 'Меня зовут';
    }
}

// Английский человек
class EnglishHuman extends HumanAbstract
{
    public function getGreetings(): string
    {
        return 'Hello';
    }

    public function getMyNameIs(): string
    {
        return 'My name is';
    }
}

// Создаём объекты
$ivan = new RussianHuman('Егор');
$john = new EnglishHuman('Jessy');

// Выводим результат
echo $ivan->introduceYourself() . PHP_EOL;
echo $john->introduceYourself() . PHP_EOL;