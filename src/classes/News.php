<?php

namespace Models;

use PDO;
use PDOException;

class News
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllNews(): array
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM news ORDER BY created_at DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle exception or log error as appropriate
            return [];
        }
    }

    public function createNews(string $title, string $description): int
    {
        $sql = "INSERT INTO news (title, description) VALUES (:title, :description)";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':title' => $title, ':description' => $description]);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            // Handle exception or log error as appropriate
            return 0;
        }
    }

    public function getNewsById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM news WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function updateNews(int $id, string $title, string $description): bool
    {
        $sql = "UPDATE news SET title = :title, description = :description WHERE id = :id";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id, ':title' => $title, ':description' => $description]);
            return true;
        } catch (PDOException $e) {
            // Handle exception or log error as appropriate
            return false;
        }
    }

    public function deleteNews(int $id): bool
    {
        $sql = "DELETE FROM news WHERE id = :id";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (PDOException $e) {
            // Handle exception or log error as appropriate
            return false;
        }
    }
}
