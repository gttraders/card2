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
    
    if ($action === 'add_banner') {
        $bannerData = [
            'title' => sanitizeInput($_POST['title']),
            'image_url' => sanitizeInput($_POST['image_url']),
            'link_url' => sanitizeInput($_POST['link_url']),
            'position' => $_POST['position'] ?? 'both',
            'status' => $_POST['status'] ?? 'active',
            'sort_order' => intval($_POST['sort_order'])
        ];
        
        if (addBanner($bannerData)) {
            $message = 'Banner added successfully!';
            $messageType = 'success';
        } else {
            $message = 'Error adding banner';
            $messageType = 'error';
        }
    }
    
    if ($action === 'update_banner') {
        $bannerId = intval($_POST['banner_id']);
        $bannerData = [
            'title' => sanitizeInput($_POST['title']),
            'image_url' => sanitizeInput($_POST['image_url']),
            'link_url' => sanitizeInput($_POST['link_url']),
            'position' => $_POST['position'] ?? 'both',
            'status' => $_POST['status'] ?? 'active',
            'sort_order' => intval($_POST['sort_order'])
        ];
        
        if (updateBanner($bannerId, $bannerData)) {
            $message = 'Banner updated successfully!';
            $messageType = 'success';
        } else {
            $message = 'Error updating banner';
            $messageType = 'error';
        }
    }
    
    if ($action === 'delete_banner') {
        $bannerId = intval($_POST['banner_id']);
        
        try {
            $stmt = $pdo->prepare("DELETE FROM banners WHERE id = ?");
            if ($stmt->execute([$bannerId])) {
                $message = 'Banner deleted successfully!';
                $messageType = 'success';
            } else {
                $message = 'Error deleting banner';
                $messageType = 'error';
            }
        } catch (PDOException $e) {
            $message = 'Error deleting banner';
            $messageType = 'error';
        }
    }
}

// Get all banners
$banners = getBanners();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banners - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar.php'; ?>
    
    <main class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-image"></i> Banners Management</h1>
            <button class="btn btn-primary" onclick="showAddBannerModal()">
                <i class="fas fa-plus"></i> Add Banner
            </button>
        </div>
        
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Auto-scrolling Banners</h3>
                <p>Banners will auto-scroll every 2 seconds on the website</p>
            </div>
            <div class="card-content">
                <div class="banners-grid">
                    <?php foreach ($banners as $banner): ?>
                    <div class="banner-item">
                        <div class="banner-preview">
                            <img src="<?php echo htmlspecialchars($banner['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($banner['title']); ?>">
                            <div class="banner-overlay">
                                <button class="btn btn-sm btn-primary" onclick="editBanner(<?php echo $banner['id']; ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deleteBanner(<?php echo $banner['id']; ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="banner-info">
                            <h4><?php echo htmlspecialchars($banner['title'] ?: 'Untitled'); ?></h4>
                            <div class="banner-meta">
                                <span class="position-badge position-<?php echo $banner['position']; ?>">
                                    <?php echo ucfirst($banner['position']); ?>
                                </span>
                                <span class="status status-<?php echo $banner['status']; ?>">
                                    <?php echo ucfirst($banner['status']); ?>
                                </span>
                                <span class="sort-order">Order: <?php echo $banner['sort_order']; ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Add Banner Modal -->
    <div id="addBannerModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Banner</h3>
                <button class="close-modal" onclick="closeModal('addBannerModal')">&times;</button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="add_banner">
                
                <div class="form-group">
                    <label>Banner Title</label>
                    <input type="text" name="title" required>
                </div>
                
                <div class="form-group">
                    <label>Image URL</label>
                    <input type="url" name="image_url" required>
                    <small>Recommended size: 800x150px for best results</small>
                </div>
                
                <div class="form-group">
                    <label>Link URL (optional)</label>
                    <input type="url" name="link_url" placeholder="https://example.com">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Position</label>
                        <select name="position">
                            <option value="both">Both (Top & Bottom)</option>
                            <option value="top">Top Only</option>
                            <option value="bottom">Bottom Only</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Sort Order</label>
                        <input type="number" name="sort_order" value="0" min="0">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Add Banner</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addBannerModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Edit Banner Modal -->
    <div id="editBannerModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Banner</h3>
                <button class="close-modal" onclick="closeModal('editBannerModal')">&times;</button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="update_banner">
                <input type="hidden" name="banner_id" id="editBannerId">
                
                <div class="form-group">
                    <label>Banner Title</label>
                    <input type="text" name="title" id="editTitle" required>
                </div>
                
                <div class="form-group">
                    <label>Image URL</label>
                    <input type="url" name="image_url" id="editImageUrl" required>
                </div>
                
                <div class="form-group">
                    <label>Link URL (optional)</label>
                    <input type="url" name="link_url" id="editLinkUrl">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Position</label>
                        <select name="position" id="editPosition">
                            <option value="both">Both (Top & Bottom)</option>
                            <option value="top">Top Only</option>
                            <option value="bottom">Bottom Only</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Sort Order</label>
                        <input type="number" name="sort_order" id="editSortOrder" min="0">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="editStatus">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Update Banner</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editBannerModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function showAddBannerModal() {
            document.getElementById('addBannerModal').style.display = 'block';
        }
        
        function editBanner(bannerId) {
            const banners = <?php echo json_encode($banners); ?>;
            const banner = banners.find(b => b.id == bannerId);
            
            if (banner) {
                document.getElementById('editBannerId').value = banner.id;
                document.getElementById('editTitle').value = banner.title || '';
                document.getElementById('editImageUrl').value = banner.image_url;
                document.getElementById('editLinkUrl').value = banner.link_url || '';
                document.getElementById('editPosition').value = banner.position;
                document.getElementById('editSortOrder').value = banner.sort_order;
                document.getElementById('editStatus').value = banner.status;
                
                document.getElementById('editBannerModal').style.display = 'block';
            }
        }
        
        function deleteBanner(bannerId) {
            if (confirm('Are you sure you want to delete this banner?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete_banner">
                    <input type="hidden" name="banner_id" value="${bannerId}">
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
        .banners-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .banner-item {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            background: white;
        }
        
        .banner-preview {
            position: relative;
            height: 150px;
            overflow: hidden;
        }
        
        .banner-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .banner-overlay {
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
        
        .banner-item:hover .banner-overlay {
            opacity: 1;
        }
        
        .banner-info {
            padding: 15px;
        }
        
        .banner-info h4 {
            margin: 0 0 10px 0;
            font-size: 16px;
            font-weight: 600;
        }
        
        .banner-meta {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }
        
        .position-badge {
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .position-both {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .position-top {
            background: #dcfce7;
            color: #166534;
        }
        
        .position-bottom {
            background: #fef3c7;
            color: #d97706;
        }
        
        .sort-order {
            font-size: 12px;
            color: #6b7280;
        }
    </style>
</body>
</html>