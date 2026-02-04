<?php
require_once 'BaseModel.php';

class Clients extends BaseModel {

    public function __construct(Database $db) {
        parent::__construct($db, 'clients');
    }

    // Add client
    public function addClient($data) {
        return $this->create($data);
    }

    // Update client
    public function updateClient($id, $data) {
        return $this->update($id, $data);
    }

    // Approve or extra client-specific logic can go here
}
