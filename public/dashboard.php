<?php
require_once '../src/bootstrap.php';

// Create Auth object here (autoloader will load Auth.php)
$auth = new Auth($db);

if (!$auth->isLoggedIn()) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SAEa ERP Dashboard</title>
    <link rel="stylesheet" href="../public/css/dashboard.css">
    <script defer src="../public/js/dashboard.js"></script>
</head>
<body>

<div class="dashboard">

    <!-- Sidebar -->
    <aside class="sidebar">
        <h2>SAEa ERP</h2>
        <ul>
            <li onclick="loadPage('home')">Dashboard</li>
            <li onclick="loadPage('users')">Users</li>
            <li onclick="loadPage('quotations')">Quotations</li>
            <li onclick="loadPage('module_name')">Module Name</li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="content" id="content">
        <h2>Welcome, <?php echo $_SESSION['name']; ?></h2>
        <p>Select a module from the left menu.</p>
    </main>

</div>

</body>
</html>