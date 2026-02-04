<?php

require_once 'BaseModel.php';

class Users extends BaseModel
{
    public function __construct(Database $db)
    {
        parent::__construct($db, 'users');
    }

    public function createUser(array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        return $this->create($data);
    }

    public function login($username, $password)
    {
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

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role']    = $user['role'];
        $_SESSION['name']    = $user['full_name'];

        return true;
    }

    public function getApprovedUsers($orderBy = 'id', $direction = 'DESC')
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE status = 'approved'
                ORDER BY {$orderBy} {$direction}";

        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function approveUser($id)
    {
        return $this->update($id, ['status' => 'approved']);
    }

    public function changeRole($id, $role)
    {
        return $this->update($id, ['role' => $role]);
    }
}
