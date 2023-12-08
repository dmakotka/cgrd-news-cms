<?php

namespace Models;

use PDO;
use PDOException;

class User
{
    private PDO $pdo;
    private ISessionHandler $sessionHandler;

    public function __construct(PDO $pdo, ISessionHandler $sessionHandler)
    {
        $this->pdo = $pdo;
        $this->sessionHandler = $sessionHandler;
    }

    public function login(string $username, string $password): bool
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $this->initializeSession($user);
                return true;
            }

            return false;
        } catch (PDOException $e) {
            // Log and handle the exception
            // error_log($e->getMessage());
            // Alternatively, rethrow or handle as per the application's error handling policy
            throw new \RuntimeException('Unable to complete login operation.');
        }
    }

    public function isUserLoggedIn(): bool
    {
        return $this->sessionHandler->isLoggedIn();
    }

    private function initializeSession(array $user): void
    {
        $this->sessionHandler->setSessionData('loggedin', true);
        $this->sessionHandler->setSessionData('user_id', $user['id']);
        $this->sessionHandler->setSessionData('username', $user['username']);
        $this->sessionHandler->regenerateSessionId();
    }
}
