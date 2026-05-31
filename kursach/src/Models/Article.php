<?php

declare(strict_types=1);

/**
 * Модель "Статья"
 */
class Article
{
    public int $id;
    public int $authorId;
    public string $name;
    public string $text;
    public string $createdAt;

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
        if ($row === false) return null;
        return new self($row);
    }

    // Создать новую статью
    public static function create(int $authorId, string $name, string $text): int
    {
        $db = Database::getConnection();
        $statement = $db->prepare('
            INSERT INTO articles (author_id, name, text, created_at)
            VALUES (:author_id, :name, :text, CURRENT_TIMESTAMP)
        ');
        $statement->execute(['author_id' => $authorId, 'name' => $name, 'text' => $text]);
        return (int) $db->lastInsertId();
    }

    // Обновить статью
    public function update(string $name, string $text): void
    {
        $db = Database::getConnection();
        $statement = $db->prepare('UPDATE articles SET name = :name, text = :text WHERE id = :id');
        $statement->execute(['id' => $this->id, 'name' => $name, 'text' => $text]);
        $this->name = $name;
        $this->text = $text;
    }

    // Удалить статью (и все комментарии)
    public function delete(): void
    {
        $db = Database::getConnection();
        $db->prepare('DELETE FROM comments WHERE article_id = :article_id')->execute(['article_id' => $this->id]);
        $db->prepare('DELETE FROM articles WHERE id = :id')->execute(['id' => $this->id]);
    }
}