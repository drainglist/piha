<?php

declare(strict_types=1);

/**
 * Класс для работы с базой данных SQLite
 * Использует паттерн "Одиночка" – одно подключение на весь скрипт
 */
class Database
{
    private static ?PDO $connection = null;  // Статическое подключение

    /**
     * Получить подключение к базе данных
     */
    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            // Папка для БД (на уровень выше от папки src)
            $dataDir = dirname(__DIR__, 2) . '/data';
            
            if (!is_dir($dataDir)) {
                mkdir($dataDir, 0777, true);  // Создаём папку data
            }
            
            $dbPath = $dataDir . '/blog.sqlite';  // Путь к файлу БД
            
            self::$connection = new PDO('sqlite:' . $dbPath);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            self::createTables();     // Создаём таблицы
            self::insertDemoData();   // Вставляем тестовые данные
        }
        
        return self::$connection;
    }

    /**
     * Создаёт таблицы users и articles (если их нет)
     */
    private static function createTables(): void
    {
        $db = self::$connection;
        
        // Таблица пользователей
        $db->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nickname VARCHAR(128) NOT NULL UNIQUE,
                email VARCHAR(255) NOT NULL UNIQUE,
                is_confirmed INTEGER NOT NULL DEFAULT 0,
                role VARCHAR(20) NOT NULL,
                password_hash VARCHAR(255) NOT NULL,
                auth_token VARCHAR(255) NOT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            )
        ");
        
        // Таблица статей
        $db->exec("
            CREATE TABLE IF NOT EXISTS articles (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                author_id INTEGER NOT NULL,
                name VARCHAR(255) NOT NULL,
                text TEXT NOT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    /**
     * Заполняет таблицы тестовыми данными (если они пустые)
     */
    private static function insertDemoData(): void
    {
        $db = self::$connection;
        
        // Проверяем, есть ли пользователи
        $usersCount = (int) $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
        
        if ($usersCount === 0) {
            $db->exec("
                INSERT INTO users 
                    (nickname, email, is_confirmed, role, password_hash, auth_token, created_at)
                VALUES
                    ('admin', 'admin@gmail.com', 1, 'admin', 'hash1', 'token1', CURRENT_TIMESTAMP),
                    ('user', 'user@gmail.com', 1, 'user', 'hash2', 'token2', CURRENT_TIMESTAMP)
            ");
        }
        
        // Проверяем, есть ли статьи
        $articlesCount = (int) $db->query("SELECT COUNT(*) FROM articles")->fetchColumn();
        
        if ($articlesCount === 0) {
            $db->exec("
                INSERT INTO articles
                    (author_id, name, text, created_at)
                VALUES
                    (1, 'Статья №1', 'Текст первой статьи.', CURRENT_TIMESTAMP),
                    (1, 'Статья №2', 'Текст второй статьи.', CURRENT_TIMESTAMP)
            ");
        }
    }
}