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
if (empty($input['items']) || !is_array($input['items'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No items in order']);
    exit;
}

if (empty($input['total_amount']) || !is_numeric($input['total_amount'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid total amount']);
    exit;
}

// Prepare order data
$orderData = [
    'user_name' => sanitizeInput($input['user_name'] ?? 'Guest User'),
    'user_phone' => sanitizeInput($input['user_phone'] ?? ''),
    'user_email' => sanitizeInput($input['user_email'] ?? ''),
    'total_amount' => floatval($input['total_amount']),
    'final_amount' => floatval($input['total_amount']), // Could apply discounts here
    'items' => []
];

// Validate and prepare items
foreach ($input['items'] as $item) {
    if (empty($item['id']) || empty($item['quantity'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid item data']);
        exit;
    }
    
    // Get product details from database
    $product = getProduct($item['id']);
    if (!$product) {
        http_response_code(400);
        echo json_encode(['error' => 'Product not found: ' . $item['id']]);
        exit;
    }
    
    $unitPrice = $product['discount_price'] ?: $product['price'];
    $quantity = intval($item['quantity']);
    
    $orderData['items'][] = [
        'product_id' => $product['id'],
        'product_title' => $product['title'],
        'quantity' => $quantity,
        'unit_price' => $unitPrice,
        'total_price' => $unitPrice * $quantity
    ];
}

// Create order
$orderId = createOrder($orderData);

if ($orderId) {
    echo json_encode([
        'success' => true,
        'order_id' => $orderId,
        'message' => 'Order created successfully'
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to create order']);
}
?>