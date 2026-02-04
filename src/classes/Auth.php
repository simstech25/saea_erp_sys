<?php

class Auth {

    private $conn;

    // DB injected from bootstrap
    public function __construct(Database $db) {
        $this->conn = $db->conn;
    }

    // 1️⃣ Check if user is logged in
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    // 2️⃣ Force login
    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            header("Location: /public/login.php");
            exit;
        }
    }

    // 3️⃣ Role-based access control
    public function requireRole($role) {
        if (!$this->isLoggedIn() || $_SESSION['role'] !== $role) {
            die("Access denied");
        }
    }

    // 4️⃣ Allow multiple roles (admin OR sales)
    public function requireAnyRole(array $roles) {
        if (
            !$this->isLoggedIn() ||
            !in_array($_SESSION['role'], $roles)
        ) {
            die("Access denied");
        }
    }

    // 5️⃣ Get logged-in user ID
    public function userId() {
        return $_SESSION['user_id'] ?? null;
    }

    // 6️⃣ Get logged-in user role
    public function userRole() {
        return $_SESSION['role'] ?? null;
    }

    // 7️⃣ Logout helper
    public function logout() {
        session_destroy();
        header("Location: /public/login.php");
        exit;
    }
}
