<?php

// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Clear session data
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: login.php");
exit;
