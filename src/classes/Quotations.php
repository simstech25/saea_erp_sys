<?php
require_once 'BaseModel.php';

class Quotations extends BaseModel {

    public function __construct(Database $db) {
        parent::__construct($db, 'quotations');
    }

    // Custom create with file handling
    public function createQuotation($data, $files = []) {
        // Handle file uploads
        if (!empty($files['rfq_file'])) {
            $data['rfq_file'] = $this->uploadFile($files['rfq_file'], 'rfq');
        }
        
        return $this->create($data);
    }

    // Update quotation with files
    public function updateQuotation($id, $data, $files = []) {
        // Handle file uploads
        if (!empty($files['excel_file'])) {
            $data['excel_file'] = $this->uploadFile($files['excel_file'], 'excel');
            $data['status'] = 'Pending Processing';
        }
        
        if (!empty($files['pdf_file'])) {
            $data['pdf_file'] = $this->uploadFile($files['pdf_file'], 'pdf');
            $data['status'] = 'Sent';
        }
        
        return $this->update($id, $data);
    }

    // Submit for approval
    public function submitForApproval($id) {
        return $this->update($id, [
            'status' => 'Pending Approval',
            'submitted_at' => date('Y-m-d H:i:s')
        ]);
    }

    // Approve quotation
    public function approveQuotation($id, $approved_by) {
        return $this->update($id, [
            'status' => 'Approved',
            'approved_by' => $approved_by,
            'approved_at' => date('Y-m-d H:i:s')
        ]);
    }

    // Mark as sent (when PDF sent to client)
    public function markAsSent($id, $pdfFile = null) {
        $data = ['status' => 'Sent'];
        if ($pdfFile) {
            $data['pdf_file'] = $pdfFile;
        }
        return $this->update($id, $data);
    }

    // Private method to handle file uploads
    private function uploadFile($file, $type) {
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/saea_erp_sys/uploads/quotations/';
        
        // Create directory if not exists
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileName = $type . '_' . time() . '_' . basename($file['name']);
        $targetPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return 'uploads/quotations/' . $fileName;
        }
        
        return null;
    }

    // Get all quotations with client info
    public function getAllWithClients($orderBy = 'created_at', $direction = 'DESC') {
        // FIXED LINE: changed c.company_name to c.name
        $sql = "SELECT q.*, c.name as client_name 
                FROM {$this->table} q 
                LEFT JOIN clients c ON q.client_id = c.id 
                ORDER BY $orderBy $direction";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}