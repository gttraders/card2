<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

// Handle inquiry actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $inquiryId = $_POST['inquiry_id'] ?? '';
    
    if ($action === 'update_status') {
        $status = $_POST['status'] ?? 'pending';
        $stmt = $pdo->prepare("UPDATE inquiries SET status = ? WHERE id = ?");
        $stmt->execute([$status, $inquiryId]);
    }
}

// Get all inquiries
$inquiries = getInquiries();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inquiries - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar.php'; ?>
    
    <main class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-question-circle"></i> Product Inquiries</h1>
        </div>
        
        <div class="dashboard-card">
            <div class="card-header">
                <h3>All Inquiries</h3>
            </div>
            <div class="card-content">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Phone</th>
                                <th>Products</th>
                                <th>Message</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($inquiries as $inquiry): ?>
                            <tr>
                                <td><?php echo $inquiry['id']; ?></td>
                                <td><?php echo htmlspecialchars($inquiry['user_name']); ?></td>
                                <td><?php echo htmlspecialchars($inquiry['user_phone']); ?></td>
                                <td>
                                    <?php 
                                    $products = json_decode($inquiry['products'], true);
                                    echo count($products) . ' products';
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars(substr($inquiry['message'], 0, 50)) . '...'; ?></td>
                                <td>
                                    <span class="status status-<?php echo $inquiry['status']; ?>">
                                        <?php echo ucfirst($inquiry['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M j, Y', strtotime($inquiry['created_at'])); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="viewInquiry(<?php echo $inquiry['id']; ?>)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="action" value="update_status">
                                        <input type="hidden" name="inquiry_id" value="<?php echo $inquiry['id']; ?>">
                                        <select name="status" onchange="this.form.submit()" class="status-select">
                                            <option value="pending" <?php echo $inquiry['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="contacted" <?php echo $inquiry['status'] === 'contacted' ? 'selected' : ''; ?>>Contacted</option>
                                            <option value="completed" <?php echo $inquiry['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    
    <script>
        function viewInquiry(inquiryId) {
            // Open inquiry details in modal or new page
            window.open(`inquiry-details.php?id=${inquiryId}`, '_blank');
        }
    </script>
</body>
</html>