<?php
require_once __DIR__ . '/../../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
$twig = new \Twig\Environment($loader, [
    // 'cache' => 'path/to/compilation_cache',
    'debug' => true, // Enable debug during development
]);
