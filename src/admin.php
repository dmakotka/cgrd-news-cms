<?php

use Models\User;
use Models\News;
use Models\SessionHandler;

require_once __DIR__ . '/../vendor/autoload.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pdo = require_once __DIR__ . '/config/database.php';
$twig = require_once __DIR__ . '/config/twig.php';

$sessionHandler = new SessionHandler();
$user = new User($pdo, $sessionHandler);
$news = new News($pdo);

if (!$user->isUserLoggedIn()) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header('Content-Type: application/json');
    $action = $_POST['action'] ?? '';

    try {
        switch ($action) {
            case 'create':
                $title = $_POST['title'] ?? '';
                $description = $_POST['description'] ?? '';
                $newsId = $news->createNews($title, $description);
                echo json_encode(['success' => true, 'message' => "News created successfully!", 'id' => $newsId]);
                break;

            case 'update':
                $title = $_POST['title'] ?? '';
                $description = $_POST['description'] ?? '';
                $news->updateNews($_POST['id'], $title, $description);
                echo json_encode(['success' => true, 'message' => "News updated successfully!"]);
                break;

            case 'delete':
                $news->deleteNews($_POST['id']);
                echo json_encode(['success' => true, 'message' => "News deleted successfully!"]);
                break;

            default:
                echo json_encode(['success' => false, 'message' => "Invalid action specified."]);
                break;
        }
    } catch (\PDOException $e) {
        // error_log($e->getMessage());
        echo json_encode(['success' => false, 'message' => "Database error occurred."]);
    } catch (\Exception $e) {
        // error_log($e->getMessage());
        echo json_encode(['success' => false, 'message' => "An error occurred."]);
    }

    exit;
}

$allNews = $news->getAllNews();
echo $twig->render('admin.html', ['news' => $allNews]);
