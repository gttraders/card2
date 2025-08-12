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

// Validate input
$required_fields = ['name', 'email', 'rating', 'comment'];
foreach ($required_fields as $field) {
    if (empty($input[$field])) {
        http_response_code(400);
        echo json_encode(['error' => "Field '$field' is required"]);
        exit;
    }
}

// Validate rating
if (!is_numeric($input['rating']) || $input['rating'] < 1 || $input['rating'] > 5) {
    http_response_code(400);
    echo json_encode(['error' => 'Rating must be between 1 and 5']);
    exit;
}

// Validate email
if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid email address']);
    exit;
}

// Rate limiting - check if user has submitted a review recently
$ip = $_SERVER['REMOTE_ADDR'];
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM reviews WHERE ip_address = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)");
    $stmt->execute([$ip]);
    $recentReviews = $stmt->fetchColumn();
    
    if ($recentReviews >= 3) {
        http_response_code(429);
        echo json_encode(['error' => 'Too many reviews submitted. Please try again later.']);
        exit;
    }
} catch (PDOException $e) {
    // Continue if rate limiting check fails
}

// Sanitize input
$reviewData = [
    'name' => sanitizeInput($input['name']),
    'email' => sanitizeInput($input['email']),
    'phone' => sanitizeInput($input['phone'] ?? ''),
    'rating' => intval($input['rating']),
    'comment' => sanitizeInput($input['comment'])
];

// Add review
if (addReview($reviewData)) {
    echo json_encode([
        'success' => true,
        'message' => 'Review submitted successfully! It will be visible after approval.'
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to submit review']);
}
?>