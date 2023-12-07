<?php

class News
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllNews()
    {
        $stmt = $this->pdo->query("SELECT * FROM news ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createNews($title, $description)
    {
        $sql = "INSERT INTO news (title, description) VALUES (:title, :description)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':title' => $title, ':description' => $description]);
        return $this->pdo->lastInsertId();
    }

    public function getNewsById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM news WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateNews($id, $title, $description)
    {
        $sql = "UPDATE news SET title = :title, description = :description WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id, ':title' => $title, ':description' => $description]);
    }

    public function deleteNews($id)
    {
        $sql = "DELETE FROM news WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}
