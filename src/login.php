<?php
session_start();

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/twig.php';
require_once __DIR__ . '/classes/User.php';

$user = new User($pdo);
$username = $password = $username_err = $password_err = $login_err = "";

if ($user->isUserLoggedIn()) {
    header("location: admin.php");
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
            header("location: admin.php");
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
