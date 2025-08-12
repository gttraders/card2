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

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add_image') {
        $imageData = [
            'title' => sanitizeInput($_POST['title']),
            'description' => sanitizeInput($_POST['description']),
            'image_url' => sanitizeInput($_POST['image_url']),
            'thumbnail_url' => sanitizeInput($_POST['thumbnail_url']),
            'alt_text' => sanitizeInput($_POST['alt_text']),
            'status' => $_POST['status'] ?? 'active'
        ];
        
        if (addGalleryImage($imageData)) {
            $message = 'Image added successfully!';
            $messageType = 'success';
        } else {
            $message = 'Error adding image';
            $messageType = 'error';
        }
    }
    
    if ($action === 'delete_image') {
        $imageId = intval($_POST['image_id']);
        
        try {
            $stmt = $pdo->prepare("DELETE FROM gallery WHERE id = ?");
            if ($stmt->execute([$imageId])) {
                $message = 'Image deleted successfully!';
                $messageType = 'success';
            } else {
                $message = 'Error deleting image';
                $messageType = 'error';
            }
        } catch (PDOException $e) {
            $message = 'Error deleting image';
            $messageType = 'error';
        }
    }
    
    if ($action === 'update_status') {
        $imageId = intval($_POST['image_id']);
        $status = $_POST['status'];
        
        try {
            $stmt = $pdo->prepare("UPDATE gallery SET status = ? WHERE id = ?");
            if ($stmt->execute([$status, $imageId])) {
                $message = 'Image status updated successfully!';
                $messageType = 'success';
            } else {
                $message = 'Error updating image status';
                $messageType = 'error';
            }
        } catch (PDOException $e) {
            $message = 'Error updating image status';
            $messageType = 'error';
        }
    }
}

// Get all gallery images
$stmt = $pdo->query("SELECT * FROM gallery ORDER BY sort_order ASC, upload_date DESC");
$galleryImages = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar.php'; ?>
    
    <main class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-images"></i> Gallery Management</h1>
            <button class="btn btn-primary" onclick="showAddImageModal()">
                <i class="fas fa-plus"></i> Add Image
            </button>
        </div>
        
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Gallery Images (Max 20 images)</h3>
                <p>Current images: <?php echo count($galleryImages); ?>/20</p>
            </div>
            <div class="card-content">
                <div class="gallery-grid">
                    <?php foreach ($galleryImages as $image): ?>
                    <div class="gallery-item">
                        <div class="image-container">
                            <img src="<?php echo htmlspecialchars($image['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($image['alt_text']); ?>">
                            <div class="image-overlay">
                                <button class="btn btn-sm btn-primary" onclick="editImage(<?php echo $image['id']; ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deleteImage(<?php echo $image['id']; ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="image-info">
                            <h4><?php echo htmlspecialchars($image['title'] ?: 'Untitled'); ?></h4>
                            <p><?php echo htmlspecialchars(substr($image['description'] ?: '', 0, 50)); ?></p>
                            <span class="status status-<?php echo $image['status']; ?>">
                                <?php echo ucfirst($image['status']); ?>
                            </span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Add Image Modal -->
    <div id="addImageModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Image</h3>
                <button class="close-modal" onclick="closeModal('addImageModal')">&times;</button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="add_image">
                
                <div class="form-group">
                    <label>Image Title</label>
                    <input type="text" name="title" required>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Image URL</label>
                    <input type="url" name="image_url" required>
                </div>
                
                <div class="form-group">
                    <label>Thumbnail URL (optional)</label>
                    <input type="url" name="thumbnail_url">
                </div>
                
                <div class="form-group">
                    <label>Alt Text</label>
                    <input type="text" name="alt_text" required>
                </div>
                
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Add Image</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addImageModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function showAddImageModal() {
            const currentCount = <?php echo count($galleryImages); ?>;
            if (currentCount >= 20) {
                alert('Maximum 20 images allowed in gallery. Please delete some images first.');
                return;
            }
            document.getElementById('addImageModal').style.display = 'block';
        }
        
        function editImage(imageId) {
            // For now, just show status update options
            const newStatus = prompt('Enter new status (active/inactive):');
            if (newStatus && (newStatus === 'active' || newStatus === 'inactive')) {
                updateImageStatus(imageId, newStatus);
            }
        }
        
        function updateImageStatus(imageId, status) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="action" value="update_status">
                <input type="hidden" name="image_id" value="${imageId}">
                <input type="hidden" name="status" value="${status}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
        
        function deleteImage(imageId) {
            if (confirm('Are you sure you want to delete this image?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete_image">
                    <input type="hidden" name="image_id" value="${imageId}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
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
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .gallery-item {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            background: white;
        }
        
        .image-container {
            position: relative;
            height: 200px;
            overflow: hidden;
        }
        
        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .gallery-item:hover .image-overlay {
            opacity: 1;
        }
        
        .image-info {
            padding: 15px;
        }
        
        .image-info h4 {
            margin: 0 0 5px 0;
            font-size: 14px;
            font-weight: 600;
        }
        
        .image-info p {
            margin: 0 0 10px 0;
            font-size: 12px;
            color: #6b7280;
        }
    </style>
</body>
</html>