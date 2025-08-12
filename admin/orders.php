<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$message = '';
$messageType = '';

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_status') {
        $orderId = intval($_POST['order_id']);
        $status = $_POST['status'];
        
        if (updateOrderStatus($orderId, $status)) {
            $message = 'Order status updated successfully!';
            $messageType = 'success';
        } else {
            $message = 'Error updating order status';
            $messageType = 'error';
        }
    }
}

// Get all orders
$orders = getOrders();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar.php'; ?>
    
    <main class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-shopping-cart"></i> Orders Management</h1>
            <div class="page-actions">
                <button class="btn btn-secondary" onclick="exportOrders('csv')">
                    <i class="fas fa-download"></i> Export CSV
                </button>
                <button class="btn btn-secondary" onclick="exportOrders('txt')">
                    <i class="fas fa-file-text"></i> Export TXT
                </button>
            </div>
        </div>
        
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <div class="dashboard-card">
            <div class="card-header">
                <h3>All Orders</h3>
                <div class="card-filters">
                    <select id="statusFilter" onchange="filterOrders()">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="paid">Paid</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
            <div class="card-content">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Phone</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="ordersTableBody">
                            <?php foreach ($orders as $order): ?>
                            <tr data-status="<?php echo $order['status']; ?>">
                                <td><?php echo htmlspecialchars($order['order_number']); ?></td>
                                <td><?php echo htmlspecialchars($order['user_name'] ?: 'Guest'); ?></td>
                                <td><?php echo htmlspecialchars($order['user_phone']); ?></td>
                                <td>₹<?php echo number_format($order['final_amount']); ?></td>
                                <td>
                                    <span class="status status-<?php echo $order['status']; ?>">
                                        <?php echo ucfirst($order['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status status-<?php echo $order['payment_status']; ?>">
                                        <?php echo ucfirst($order['payment_status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M j, Y H:i', strtotime($order['created_at'])); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="viewOrder(<?php echo $order['id']; ?>)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <select onchange="updateOrderStatus(<?php echo $order['id']; ?>, this.value)" class="status-select">
                                        <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="confirmed" <?php echo $order['status'] === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                        <option value="paid" <?php echo $order['status'] === 'paid' ? 'selected' : ''; ?>>Paid</option>
                                        <option value="shipped" <?php echo $order['status'] === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                        <option value="delivered" <?php echo $order['status'] === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                        <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Order Details Modal -->
    <div id="orderDetailsModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Order Details</h3>
                <button class="close-modal" onclick="closeModal('orderDetailsModal')">&times;</button>
            </div>
            <div id="orderDetailsContent">
                <!-- Order details will be loaded here -->
            </div>
        </div>
    </div>
    
    <script>
        function updateOrderStatus(orderId, status) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="action" value="update_status">
                <input type="hidden" name="order_id" value="${orderId}">
                <input type="hidden" name="status" value="${status}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
        
        function viewOrder(orderId) {
            fetch(`../api/get-order.php?id=${orderId}`)
                .then(response => response.json())
                .then(order => {
                    let itemsHtml = '';
                    if (order.items) {
                        itemsHtml = order.items.map(item => `
                            <tr>
                                <td>${item.product_title}</td>
                                <td>${item.quantity}</td>
                                <td>₹${parseFloat(item.unit_price).toFixed(2)}</td>
                                <td>₹${parseFloat(item.total_price).toFixed(2)}</td>
                            </tr>
                        `).join('');
                    }
                    
                    document.getElementById('orderDetailsContent').innerHTML = `
                        <div class="order-info">
                            <div class="info-row">
                                <strong>Order Number:</strong> ${order.order_number}
                            </div>
                            <div class="info-row">
                                <strong>Customer:</strong> ${order.user_name || 'Guest'}
                            </div>
                            <div class="info-row">
                                <strong>Phone:</strong> ${order.user_phone || 'N/A'}
                            </div>
                            <div class="info-row">
                                <strong>Email:</strong> ${order.user_email || 'N/A'}
                            </div>
                            <div class="info-row">
                                <strong>Status:</strong> <span class="status status-${order.status}">${order.status.charAt(0).toUpperCase() + order.status.slice(1)}</span>
                            </div>
                            <div class="info-row">
                                <strong>Total Amount:</strong> ₹${parseFloat(order.final_amount).toFixed(2)}
                            </div>
                            <div class="info-row">
                                <strong>Order Date:</strong> ${new Date(order.created_at).toLocaleString()}
                            </div>
                        </div>
                        
                        <h4>Order Items:</h4>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${itemsHtml}
                            </tbody>
                        </table>
                    `;
                    
                    document.getElementById('orderDetailsModal').style.display = 'block';
                })
                .catch(error => {
                    console.error('Error fetching order:', error);
                    alert('Error loading order details');
                });
        }
        
        function filterOrders() {
            const filter = document.getElementById('statusFilter').value;
            const rows = document.querySelectorAll('#ordersTableBody tr');
            
            rows.forEach(row => {
                if (filter === '' || row.dataset.status === filter) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
        
        function exportOrders(format) {
            window.open(`../api/export-orders.php?format=${format}`, '_blank');
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>