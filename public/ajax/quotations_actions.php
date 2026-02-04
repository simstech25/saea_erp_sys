<?php
require_once '../../src/bootstrap.php';
header('Content-Type: application/json');

// Auth is autoloaded
$auth = new Auth($db);

if (!$auth->isLoggedIn()) {
    echo json_encode(['success'=>false,'message'=>'Unauthorized']);
    exit;
}

// Quotations is autoloaded
$quotations = new Quotations($db);

$data = json_decode(file_get_contents('php://input'), true);
// ... rest of code

// Create Quotations object
require_once BASE_PATH . '/src/classes/BaseModel.php';
require_once BASE_PATH . '/src/classes/Quotations.php';
$quotations = new Quotations($db);

$data = json_decode(file_get_contents('php://input'), true);

$id = $data['id'] ?? null;
$action = $data['action'] ?? '';

switch($action){
    case 'approve':
        $quotations->approveQuotation($id);
        echo json_encode(['success'=>true,'message'=>'Quote approved']);
        break;
    case 'markSent':
        $quotations->markAsSent($id, ''); // Add empty string for pdf_file
        echo json_encode(['success'=>true,'message'=>'Quote marked as sent']);
        break;
    case 'delete':
        $quotations->delete($id);
        echo json_encode(['success'=>true,'message'=>'Quote deleted']);
        break;
    default:
        echo json_encode(['success'=>false,'message'=>'Unknown action']);
}