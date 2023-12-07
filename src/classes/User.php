<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../../vendor/autoload.php';

class User
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function login($username, $password)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            return true;
        } else {
            return false;
        }
    }

    public function isUserLoggedIn()
    {
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
            return true;
        } else {
            return false;
        }
    }
}
