<?php

use Models\User;
use Models\News;

require_once __DIR__ . '/../vendor/autoload.php';

// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pdo = require_once __DIR__ . '/config/database.php';
$twig = require_once __DIR__ . '/config/twig.php';

$user = new User($pdo);
$news = new News($pdo);

// Redirect to login if not logged in
if (!$user->isUserLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Handle AJAX Requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header('Content-Type: application/json');
    $action = $_POST['action'] ?? '';
    $id = $_POST['id'] ?? null;

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
                $success = $news->updateNews($id, $title, $description);
                echo json_encode(['success' => $success, 'message' => $success ? "News updated successfully!" : "Failed to update news."]);
                break;

            case 'delete':
                $success = $news->deleteNews($id);
                echo json_encode(['success' => $success, 'message' => $success ? "News deleted successfully!" : "Failed to delete news."]);
                break;

            default:
                echo json_encode(['success' => false, 'message' => "No action was specified."]);
                break;
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => "Error: " . $e->getMessage()]);
    }

    exit;
}

// Regular Page Request
$allNews = $news->getAllNews();
echo $twig->render('admin.html', ['news' => $allNews]);
