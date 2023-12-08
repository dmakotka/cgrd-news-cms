<?php

use Models\News;
use PHPUnit\Framework\TestCase;

class NewsTest extends TestCase
{
    private $pdo;
    private $news;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->news = new News($this->pdo);
    }

    public function testGetAllNews()
    {
        // Given
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetchAll')->willReturn([
            ['id' => 1, 'title' => 'Test News 1', 'description' => 'Description 1'],
            ['id' => 2, 'title' => 'Test News 2', 'description' => 'Description 2']
        ]);
        $this->pdo->method('query')->willReturn($stmt);

        // When & Then
        $newsItems = $this->news->getAllNews();
        $this->assertCount(2, $newsItems);
        $this->assertEquals('Test News 1', $newsItems[0]['title']);
    }

    public function testCreateNews()
    {
        // Given
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())->method('execute');
        $this->pdo->method('prepare')->willReturn($stmt);
        $this->pdo->method('lastInsertId')->willReturn('1');

        // When & Then
        $newsId = $this->news->createNews('Test News', 'Test Description');
        $this->assertEquals('1', $newsId);
    }

    public function testUpdateNews()
    {
        // Given
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())->method('execute');
        $this->pdo->method('prepare')->willReturn($stmt);

        // When & Then
        $this->news->updateNews(1, 'Updated News', 'Updated Description');
    }

    public function testDeleteNews()
    {
        // Given
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())->method('execute');
        $this->pdo->method('prepare')->willReturn($stmt);

        // When & Then
        $this->news->deleteNews(1);
    }
}
