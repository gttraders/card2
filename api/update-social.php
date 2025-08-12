<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

// Check if logged in
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$platform = $input['platform'] ?? '';
$url = $input['url'] ?? '';

if (empty($platform)) {
    echo json_encode(['error' => 'Platform not specified']);
    exit;
}

$allowedPlatforms = ['facebook', 'youtube', 'twitter', 'instagram', 'linkedin', 'pinterest', 'telegram', 'zomato'];

if (!in_array($platform, $allowedPlatforms)) {
    echo json_encode(['error' => 'Invalid platform']);
    exit;
}

if (updateSiteSetting('social_' . $platform, $url)) {
    echo json_encode(['success' => true, 'message' => 'Social link updated successfully']);
} else {
    echo json_encode(['error' => 'Failed to update social link']);
}
?>