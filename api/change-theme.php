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

if (empty($input['theme'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Theme not specified']);
    exit;
}

$allowedThemes = ['blue-dark', 'gradient', 'teal-orange', 'light'];
$theme = $input['theme'];

if (!in_array($theme, $allowedThemes)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid theme']);
    exit;
}

// Update theme setting
if (updateSiteSetting('current_theme', $theme)) {
    echo json_encode(['success' => true, 'theme' => $theme]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to update theme']);
}
?>