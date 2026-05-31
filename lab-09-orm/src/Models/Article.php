<?php

declare(strict_types=1);

class Article
{
    public int $id;
    public int $authorId;
    public string $name;
    public string $text;
    public string $createdAt;

    // Конструктор из строки БД
    public function __construct(array $row)
    {
        $this->id = (int) $row['id'];
        $this->authorId = (int) $row['author_id'];
        $this->name = $row['name'];
        $this->text = $row['text'];
        $this->createdAt = $row['created_at'];
    }

    // Найти все статьи
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

    // Найти статью по ID
    public static function findById(int $id): ?self
    {
        $db = Database::getConnection();
        $statement = $db->prepare('SELECT * FROM articles WHERE id = :id');
        $statement->execute(['id' => $id]);

        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            return null;
        }
        return new self($row);
    }
}