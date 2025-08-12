<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if logged in
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$format = $_POST['format'] ?? $_GET['format'] ?? 'csv';

// Get all orders with items
try {
    $stmt = $pdo->query("
        SELECT o.*, 
               GROUP_CONCAT(CONCAT(oi.product_title, ' (', oi.quantity, 'x₹', oi.unit_price, ')') SEPARATOR ', ') as items
        FROM orders o 
        LEFT JOIN order_items oi ON o.id = oi.order_id 
        GROUP BY o.id 
        ORDER BY o.created_at DESC
    ");
    $orders = $stmt->fetchAll();
    
    if ($format === 'csv') {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="orders_' . date('Y-m-d_H-i-s') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // CSV Headers
        fputcsv($output, [
            'Order Number',
            'Customer Name',
            'Phone',
            'Email',
            'Items',
            'Total Amount',
            'Final Amount',
            'Status',
            'Payment Status',
            'Order Date'
        ]);
        
        // CSV Data
        foreach ($orders as $order) {
            fputcsv($output, [
                $order['order_number'],
                $order['user_name'] ?: 'Guest',
                $order['user_phone'],
                $order['user_email'],
                $order['items'],
                '₹' . number_format($order['total_amount']),
                '₹' . number_format($order['final_amount']),
                ucfirst($order['status']),
                ucfirst($order['payment_status'] ?? 'pending'),
                date('Y-m-d H:i:s', strtotime($order['created_at']))
            ]);
        }
        
        fclose($output);
        
    } elseif ($format === 'txt') {
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="orders_' . date('Y-m-d_H-i-s') . '.txt"');
        
        echo "ORDERS EXPORT REPORT\n";
        echo "Generated on: " . date('Y-m-d H:i:s') . "\n";
        echo "Total Orders: " . count($orders) . "\n";
        echo str_repeat("=", 80) . "\n\n";
        
        foreach ($orders as $order) {
            echo "Order #: " . $order['order_number'] . "\n";
            echo "Customer: " . ($order['user_name'] ?: 'Guest') . "\n";
            echo "Phone: " . $order['user_phone'] . "\n";
            echo "Email: " . $order['user_email'] . "\n";
            echo "Items: " . $order['items'] . "\n";
            echo "Total: ₹" . number_format($order['final_amount']) . "\n";
            echo "Status: " . ucfirst($order['status']) . "\n";
            echo "Date: " . date('Y-m-d H:i:s', strtotime($order['created_at'])) . "\n";
            echo str_repeat("-", 50) . "\n\n";
        }
        
        echo "\nEnd of Report\n";
    }
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
?>