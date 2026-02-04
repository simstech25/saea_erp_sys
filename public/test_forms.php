<?php
require_once 'BaseModel.php';

class Users extends BaseModel {

    public function __construct(Database $db) {
        parent::__construct($db, 'users');
    }

    /* =========================
       CREATE USER (with hashing)
       ========================= */
    public function addUser($full_name, $username, $email, $password, $role = 'user') {

        $data = [
            'full_name' => $full_name,
            'username'  => $username,
            'email'     => $email,
            'password'  => password_hash($password, PASSWORD_BCRYPT),
            'role'      => $role,
            'status'    => 'approved' // auto-approve for now
        ];

        return $this->create($data);
    }

    /* =========================
       LOGIN
       ========================= */
    public function login($username, $password) {

        $stmt = $this->conn->prepare(
            "SELECT * FROM {$this->table} WHERE username = ? LIMIT 1"
        );
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$user) {
            return false;
        }

        if ($user['status'] !== 'approved') {
            return 'pending';
        }

        if (!password_verify($password, $user['password'])) {
            return false;
        }

        // session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role']    = $user['role'];
        $_SESSION['name']    = $user['full_name'];

        return true;
    }

    /* =========================
       GET ALL USERS
       ========================= */
    public function getAllUsers() {
        $result = $this->conn->query("SELECT * FROM {$this->table}");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /* =========================
       APPROVE USER
       ========================= */
    public function approveUser($id) {
        return $this->update($id, ['status' => 'approved']);
    }

    /* =========================
       CHANGE ROLE
       ========================= */
    public function changeRole($id, $role) {
        return $this->update($id, ['role' => $role]);
    }
}
