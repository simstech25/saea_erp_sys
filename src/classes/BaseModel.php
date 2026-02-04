<?php

class BaseModel {

    protected $conn;
    protected $table;

    public function __construct(Database $db, $table) {
        $this->conn = $db->conn;
        $this->table = $table;
    }

    // 1️⃣ Get all records
    public function getAll($orderBy = 'id', $direction = 'DESC') {
        $sql = "SELECT * FROM {$this->table} ORDER BY $orderBy $direction";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // 2️⃣ Get single record by id
    public function get($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $record = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $record;
    }

    // 3️⃣ Create record
    // $data = associative array: ['column1' => value1, 'column2' => value2, ...]
    public function create($data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        $types = str_repeat("s", count($data)); // assuming all strings, can customize later

        $stmt = $this->conn->prepare("INSERT INTO {$this->table} ($columns) VALUES ($placeholders)");

        $stmt->bind_param($types, ...array_values($data));
        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        return $id;
    }

   // 4️⃣ Update record
// $data = associative array: ['column1' => value1, 'column2' => value2, ...]
public function update($id, $data) {
    $set = [];
    foreach ($data as $col => $val) {
        $set[] = "$col=?";
    }
    $setStr = implode(", ", $set);
    $types = str_repeat("s", count($data)); // assuming all strings

    $stmt = $this->conn->prepare("UPDATE {$this->table} SET $setStr WHERE id=?");

    // Merge values + id to avoid positional argument after unpacking
    $params = array_merge(array_values($data), [$id]);
    $stmt->bind_param($types . "i", ...$params);

    $stmt->execute();
    $stmt->close();
    return true;
}


    // 5️⃣ Delete record by id
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        return true;
    }

    // 6️⃣ Count all records
    public function count() {
        $result = $this->conn->query("SELECT COUNT(*) AS total FROM {$this->table}");
        $row = $result->fetch_assoc();
        return $row['total'];
    }
}
