<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../includes/config.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (empty($input['products']) || !is_array($input['products'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No products in inquiry']);
    exit;
}

$inquiryData = [
    'user_name' => sanitizeInput($input['user_name'] ?? 'Guest User'),
    'user_phone' => sanitizeInput($input['user_phone'] ?? ''),
    'user_email' => sanitizeInput($input['user_email'] ?? ''),
    'products' => $input['products'],
    'message' => sanitizeInput($input['message'] ?? 'Product inquiry')
];

if (createInquiry($inquiryData)) {
    echo json_encode(['success' => true, 'message' => 'Inquiry submitted successfully']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to submit inquiry']);
}
?>