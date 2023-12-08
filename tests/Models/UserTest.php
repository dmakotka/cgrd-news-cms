<?php

use Models\User;
use Models\ISessionHandler;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private $pdo;
    private $sessionHandler;
    private $user;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->sessionHandler = $this->createMock(ISessionHandler::class);
        $this->user = new User($this->pdo, $this->sessionHandler);
    }

    public function testLoginSuccess()
    {
        // Given
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetch')
            ->willReturn([
                'id' => 1,
                'username' => 'testUser',
                'password' => password_hash('testPass', PASSWORD_DEFAULT)
            ]);
        $this->pdo->method('prepare')
            ->willReturn($stmt);
        $this->sessionHandler->expects($this->exactly(3))
            ->method('setSessionData')
            ->withConsecutive(
                [$this->equalTo('loggedin'), $this->equalTo(true)],
                [$this->equalTo('user_id'), $this->equalTo(1)],
                [$this->equalTo('username'), $this->equalTo('testUser')]
            );

        // When
        $this->sessionHandler->expects($this->once())
            ->method('regenerateSessionId');

        // Then
        $this->assertTrue($this->user->login('testUser', 'testPass'));
    }

    public function testLoginFailure()
    {
        // Given
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetch')->willReturn(false);

        // When
        $this->pdo->method('prepare')->willReturn($stmt);

        // Then
        $this->assertFalse($this->user->login('invalidUser', 'invalidPass'));
    }
}
