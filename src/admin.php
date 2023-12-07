<?php
session_start();

require_once 'config/database.php';
require_once 'config/twig.php';
require_once 'classes/User.php';
require_once 'classes/News.php';

$user = new User($pdo);
$news = new News($pdo);

// Redirect to login if not logged in
if (!$user->isUserLoggedIn()) {
    header("location: login.php");
    exit;
}

// Handle AJAX Requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $id = $_POST['id'] ?? null;
    $response = ['success' => false, 'message' => ''];

    try {
        if ($action === 'create') {
            $news->createNews($title, $description);
            $response = ['success' => true, 'message' => "News created successfully!"];
        } elseif ($action === 'update' && $id) {
            $news->updateNews($id, $title, $description);
            $response = ['success' => true, 'message' => "News updated successfully!"];
        } elseif ($action === 'delete' && $id) {
            $news->deleteNews($id);
            $response = ['success' => true, 'message' => "News deleted successfully!"];
        }
    } catch (Exception $e) {
        $response = ['success' => false, 'message' => "Error: " . $e->getMessage()];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Regular Page Request
$allNews = $news->getAllNews();
echo $twig->render('admin.html', ['news' => $allNews]);
