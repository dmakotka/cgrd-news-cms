<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once __DIR__ . '/../../vendor/autoload.php';

$loader = new FilesystemLoader(__DIR__ . '/../templates');
$twig = new Environment($loader, [
    // 'cache' => __DIR__ . '/path/to/compilation_cache', // Uncomment and define path if needed
    'debug' => true, // Enable debug during development
]);

return $twig;
