<?php
session_start();
require_once '../includes/config.php';

header('Content-Type: application/json');

// Check if logged in
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$filename = $input['filename'] ?? '';

if (empty($filename)) {
    echo json_encode(['error' => 'Filename not provided']);
    exit;
}

// Security check - only allow .sql files
if (pathinfo($filename, PATHINFO_EXTENSION) !== 'sql') {
    echo json_encode(['error' => 'Invalid file type']);
    exit;
}

$filepath = '../backups/' . basename($filename);

if (file_exists($filepath)) {
    if (unlink($filepath)) {
        echo json_encode(['success' => true, 'message' => 'Backup deleted successfully']);
    } else {
        echo json_encode(['error' => 'Failed to delete backup file']);
    }
} else {
    echo json_encode(['error' => 'Backup file not found']);
}
?>