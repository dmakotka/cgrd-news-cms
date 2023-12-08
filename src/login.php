<?php

use Models\User;
use Models\SessionHandler;

require_once __DIR__ . '/../vendor/autoload.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pdo = require_once __DIR__ . '/config/database.php';
$twig = require_once __DIR__ . '/config/twig.php';

$sessionHandler = new SessionHandler();
$user = new User($pdo, $sessionHandler);
$username = $password = $username_err = $password_err = $login_err = "";

if ($user->isUserLoggedIn()) {
    header("Location: admin.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($username_err) && empty($password_err)) {
        if ($user->login($username, $password)) {
            header("Location: admin.php");
            exit;
        } else {
            $login_err = "Invalid username or password.";
        }
    }
}

echo $twig->render('login.html', [
    'username' => $username,
    'username_err' => $username_err,
    'password_err' => $password_err,
    'login_err' => $login_err,
]);
