<?php

declare(strict_types=1);

class User
{
    public int $id;
    public string $nickname;
    public string $email;
    public bool $isConfirmed;
    public string $role;
    public string $createdAt;

    // Конструктор из строки БД
    public function __construct(array $row)
    {
        $this->id = (int) $row['id'];
        $this->nickname = $row['nickname'];
        $this->email = $row['email'];
        $this->isConfirmed = (bool) $row['is_confirmed'];
        $this->role = $row['role'];
        $this->createdAt = $row['created_at'];
    }

    // Найти пользователя по ID
    public static function findById(int $id): ?self
    {
        $db = Database::getConnection();
        $statement = $db->prepare('SELECT * FROM users WHERE id = :id');
        $statement->execute(['id' => $id]);

        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            return null;
        }
        return new self($row);
    }
}