<?php

namespace Models;

use PDO;
use PDOException;

class User
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
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
        return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
    }

    private function initializeSession(array $user): void
    {
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        
        // Regenerate session ID to prevent session fixation
        session_regenerate_id();
    }
}
