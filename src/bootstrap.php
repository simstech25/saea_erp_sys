<?php
// Display errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define absolute project root
define('BASE_PATH', dirname(__DIR__)); // points to saea_erp_sys/

// Load config
require_once BASE_PATH . '/src/config/config.php';

// Manually require Database class (core dependency)
require_once BASE_PATH . '/src/classes/Database.php';

// Autoloader for other classes
spl_autoload_register(function ($className) {
    $file = BASE_PATH . '/src/classes/' . $className . '.php';
    if (file_exists($file)) {
        require_once $file;
    } else {
        // Optional: Log or debug missing classes
        error_log("Autoloader: Class $className not found at $file");
    }
});

// Start session ONCE
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Create database connection
$db = new Database();