<?php
require_once '../../src/bootstrap.php';

// Create Auth object (autoloaded)
$auth = new Auth($db);

if (!$auth->isLoggedIn()) {
    exit('Unauthorized');
}

$page = $_GET['page'] ?? '';

switch ($page) {
    case 'home':
        include 'partials/home.php';
        break;

    case 'users':
        // Autoloader loads Users.php automatically
        $users = new Users($db);
        include 'partials/users_manage.php';
        break;

    case 'quotations':
        // Autoloader loads BaseModel.php and Quotations.php
        $quotations = new Quotations($db);
        include 'partials/quotations_manage.php';
        break;

    default:
        echo "<p>Page not found</p>";
}