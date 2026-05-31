<?php

declare(strict_types=1);

/**
 * Модель "Комментарий"
 */
class Comment
{
    public int $id;
    public int $authorId;
    public int $articleId;
    public string $text;
    public string $createdAt;
    public ?string $authorNickname;  // Имя автора (JOIN с users)

    public function __construct(array $row)
    {
        $this->id = (int) $row['id'];
        $this->authorId = (int) $row['author_id'];
        $this->articleId = (int) $row['article_id'];
        $this->text = $row['text'];
        $this->createdAt = $row['created_at'];
        $this->authorNickname = $row['author_nickname'] ?? null;
    }

    // Найти комментарий по ID (с именем автора)
    public static function findById(int $id): ?self
    {
        $db = Database::getConnection();
        $statement = $db->prepare('
            SELECT comments.*, users.nickname AS author_nickname
            FROM comments
            LEFT JOIN users ON users.id = comments.author_id
            WHERE comments.id = :id
        ');
        $statement->execute(['id' => $id]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if ($row === false) return null;
        return new self($row);
    }

    // Найти все комментарии к статье (с именами авторов)
    public static function findByArticleId(int $articleId): array
    {
        $db = Database::getConnection();
        $statement = $db->prepare('
            SELECT comments.*, users.nickname AS author_nickname
            FROM comments
            LEFT JOIN users ON users.id = comments.author_id
            WHERE comments.article_id = :article_id
            ORDER BY comments.id ASC
        ');
        $statement->execute(['article_id' => $articleId]);
        $comments = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $comments[] = new self($row);
        }
        return $comments;
    }

    // Создать новый комментарий
    public static function create(int $articleId, int $authorId, string $text): int
    {
        $db = Database::getConnection();
        $statement = $db->prepare('
            INSERT INTO comments (author_id, article_id, text, created_at)
            VALUES (:author_id, :article_id, :text, CURRENT_TIMESTAMP)
        ');
        $statement->execute([
            'author_id' => $authorId,
            'article_id' => $articleId,
            'text' => $text,
        ]);
        return (int) $db->lastInsertId();
    }

    // Обновить комментарий
    public function update(string $text): void
    {
        $db = Database::getConnection();
        $statement = $db->prepare('UPDATE comments SET text = :text WHERE id = :id');
        $statement->execute(['id' => $this->id, 'text' => $text]);
        $this->text = $text;
    }

    // Удалить комментарий
    public function delete(): void
    {
        $db = Database::getConnection();
        $db->prepare('DELETE FROM comments WHERE id = :id')->execute(['id' => $this->id]);
    }
}