<?php

declare(strict_types=1);

/**
 * Модель "Статья"
 * Представляет одну запись из таблицы articles
 */
class Article
{
    public int $id;         // ID статьи
    public int $authorId;   // ID автора
    public string $name;    // Название статьи
    public string $text;    // Текст статьи
    public string $createdAt; // Дата создания

    /**
     * Конструктор: создаёт объект из строки БД
     */
    public function __construct(array $row)
    {
        $this->id = (int) $row['id'];
        $this->authorId = (int) $row['author_id'];
        $this->name = $row['name'];
        $this->text = $row['text'];
        $this->createdAt = $row['created_at'];
    }

    /**
     * Найти все статьи (сортировка: новые сверху)
     */
    public static function findAll(): array
    {
        $db = Database::getConnection();
        $statement = $db->query('SELECT * FROM articles ORDER BY id DESC');
        
        $articles = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $articles[] = new self($row);
        }
        return $articles;
    }

    /**
     * Найти статью по ID
     */
    public static function findById(int $id): ?self
    {
        $db = Database::getConnection();
        $statement = $db->prepare('SELECT * FROM articles WHERE id = :id');
        $statement->execute(['id' => $id]);
        
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if ($row === false) return null;
        
        return new self($row);
    }

    /**
     * Обновить статью (новое название и текст)
     * Метод добавлен специально для 10-й лабораторной
     */
    public function update(string $name, string $text): void
    {
        $db = Database::getConnection();
        
        // Подготовленный запрос для обновления
        $statement = $db->prepare('
            UPDATE articles
            SET name = :name, text = :text
            WHERE id = :id
        ');
        
        $statement->execute([
            'id' => $this->id,
            'name' => $name,
            'text' => $text,
        ]);
        
        // Обновляем текущий объект
        $this->name = $name;
        $this->text = $text;
    }
}