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

// Handle review actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_status') {
        $reviewId = intval($_POST['review_id']);
        $status = $_POST['status'];
        
        if (updateReviewStatus($reviewId, $status)) {
            $message = 'Review status updated successfully!';
            $messageType = 'success';
        } else {
            $message = 'Error updating review status';
            $messageType = 'error';
        }
    }
    
    if ($action === 'delete_review') {
        $reviewId = intval($_POST['review_id']);
        
        try {
            $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ?");
            if ($stmt->execute([$reviewId])) {
                $message = 'Review deleted successfully!';
                $messageType = 'success';
            } else {
                $message = 'Error deleting review';
                $messageType = 'error';
            }
        } catch (PDOException $e) {
            $message = 'Error deleting review';
            $messageType = 'error';
        }
    }
}

// Get all reviews
$reviews = getAllReviews();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar.php'; ?>
    
    <main class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-star"></i> Reviews Management</h1>
        </div>
        
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <div class="dashboard-card">
            <div class="card-header">
                <h3>All Reviews</h3>
                <div class="card-filters">
                    <select id="statusFilter" onchange="filterReviews()">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
            </div>
            <div class="card-content">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Email</th>
                                <th>Rating</th>
                                <th>Comment</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="reviewsTableBody">
                            <?php foreach ($reviews as $review): ?>
                            <tr data-status="<?php echo $review['status']; ?>">
                                <td><?php echo htmlspecialchars($review['name']); ?></td>
                                <td><?php echo htmlspecialchars($review['email']); ?></td>
                                <td>
                                    <div class="stars">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star <?php echo $i <= $review['rating'] ? 'active' : ''; ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="comment-preview">
                                        <?php echo htmlspecialchars(substr($review['comment'], 0, 100)); ?>
                                        <?php if (strlen($review['comment']) > 100): ?>...<?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="status status-<?php echo $review['status']; ?>">
                                        <?php echo ucfirst($review['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M j, Y H:i', strtotime($review['created_at'])); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="viewReview(<?php echo $review['id']; ?>)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <select onchange="updateReviewStatus(<?php echo $review['id']; ?>, this.value)" class="status-select">
                                        <option value="pending" <?php echo $review['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="approved" <?php echo $review['status'] === 'approved' ? 'selected' : ''; ?>>Approved</option>
                                        <option value="rejected" <?php echo $review['status'] === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                    </select>
                                    <button class="btn btn-sm btn-danger" onclick="deleteReview(<?php echo $review['id']; ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Review Details Modal -->
    <div id="reviewDetailsModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Review Details</h3>
                <button class="close-modal" onclick="closeModal('reviewDetailsModal')">&times;</button>
            </div>
            <div id="reviewDetailsContent">
                <!-- Review details will be loaded here -->
            </div>
        </div>
    </div>
    
    <script>
        function updateReviewStatus(reviewId, status) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="action" value="update_status">
                <input type="hidden" name="review_id" value="${reviewId}">
                <input type="hidden" name="status" value="${status}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
        
        function deleteReview(reviewId) {
            if (confirm('Are you sure you want to delete this review?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete_review">
                    <input type="hidden" name="review_id" value="${reviewId}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        function viewReview(reviewId) {
            // Find the review data from the table
            const row = document.querySelector(`tr[data-review-id="${reviewId}"]`);
            // For now, we'll show a simple alert. In a real implementation, you'd fetch the full review data
            alert('Review details would be shown here');
        }
        
        function filterReviews() {
            const filter = document.getElementById('statusFilter').value;
            const rows = document.querySelectorAll('#reviewsTableBody tr');
            
            rows.forEach(row => {
                if (filter === '' || row.dataset.status === filter) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
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
    
    <style>
        .stars {
            display: flex;
            gap: 2px;
        }
        
        .stars i {
            color: #ddd;
            font-size: 14px;
        }
        
        .stars i.active {
            color: #fbbf24;
        }
        
        .comment-preview {
            max-width: 200px;
            word-wrap: break-word;
        }
        
        .status-select {
            padding: 4px 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 12px;
        }
    </style>
</body>
</html>