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
    
    if ($action === 'add_video') {
        $youtubeUrl = sanitizeInput($_POST['youtube_url']);
        
        // Extract video ID from YouTube URL
        $videoId = '';
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $youtubeUrl, $matches)) {
            $videoId = $matches[1];
        }
        
        $embedCode = $videoId ? "https://www.youtube.com/embed/{$videoId}" : '';
        
        $videoData = [
            'title' => sanitizeInput($_POST['title']),
            'description' => sanitizeInput($_POST['description']),
            'youtube_url' => $youtubeUrl,
            'embed_code' => $embedCode,
            'status' => $_POST['status'] ?? 'active'
        ];
        
        if (addVideo($videoData)) {
            $message = 'Video added successfully!';
            $messageType = 'success';
        } else {
            $message = 'Error adding video';
            $messageType = 'error';
        }
    }
    
    if ($action === 'update_video') {
        $videoId = intval($_POST['video_id']);
        $youtubeUrl = sanitizeInput($_POST['youtube_url']);
        
        // Extract video ID from YouTube URL
        $ytVideoId = '';
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $youtubeUrl, $matches)) {
            $ytVideoId = $matches[1];
        }
        
        $embedCode = $ytVideoId ? "https://www.youtube.com/embed/{$ytVideoId}" : '';
        
        try {
            $stmt = $pdo->prepare("UPDATE videos SET title = ?, description = ?, youtube_url = ?, embed_code = ?, status = ? WHERE id = ?");
            if ($stmt->execute([
                sanitizeInput($_POST['title']),
                sanitizeInput($_POST['description']),
                $youtubeUrl,
                $embedCode,
                $_POST['status'],
                $videoId
            ])) {
                $message = 'Video updated successfully!';
                $messageType = 'success';
            } else {
                $message = 'Error updating video';
                $messageType = 'error';
            }
        } catch (PDOException $e) {
            $message = 'Error updating video';
            $messageType = 'error';
        }
    }
    
    if ($action === 'delete_video') {
        $videoId = intval($_POST['video_id']);
        
        try {
            $stmt = $pdo->prepare("DELETE FROM videos WHERE id = ?");
            if ($stmt->execute([$videoId])) {
                $message = 'Video deleted successfully!';
                $messageType = 'success';
            } else {
                $message = 'Error deleting video';
                $messageType = 'error';
            }
        } catch (PDOException $e) {
            $message = 'Error deleting video';
            $messageType = 'error';
        }
    }
}

// Get all videos
$videos = getVideos('all');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Videos - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar.php'; ?>
    
    <main class="main-content">
        <div class="page-header">
            <h1><i class="fab fa-youtube"></i> Videos Management</h1>
            <button class="btn btn-primary" onclick="showAddVideoModal()">
                <i class="fas fa-plus"></i> Add Video
            </button>
        </div>
        
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <div class="dashboard-card">
            <div class="card-header">
                <h3>YouTube Videos</h3>
            </div>
            <div class="card-content">
                <div class="videos-grid">
                    <?php foreach ($videos as $video): ?>
                    <div class="video-item">
                        <div class="video-preview">
                            <?php if ($video['embed_code']): ?>
                                <iframe src="<?php echo htmlspecialchars($video['embed_code']); ?>" 
                                        frameborder="0" allowfullscreen></iframe>
                            <?php else: ?>
                                <div class="no-preview">No Preview Available</div>
                            <?php endif; ?>
                        </div>
                        <div class="video-info">
                            <h4><?php echo htmlspecialchars($video['title']); ?></h4>
                            <p><?php echo htmlspecialchars(substr($video['description'] ?: '', 0, 100)); ?></p>
                            <div class="video-meta">
                                <span class="status status-<?php echo $video['status']; ?>">
                                    <?php echo ucfirst($video['status']); ?>
                                </span>
                                <div class="video-actions">
                                    <button class="btn btn-sm btn-primary" onclick="editVideo(<?php echo $video['id']; ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteVideo(<?php echo $video['id']; ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Add Video Modal -->
    <div id="addVideoModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Video</h3>
                <button class="close-modal" onclick="closeModal('addVideoModal')">&times;</button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="add_video">
                
                <div class="form-group">
                    <label>Video Title</label>
                    <input type="text" name="title" required>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label>YouTube URL</label>
                    <input type="url" name="youtube_url" required placeholder="https://www.youtube.com/watch?v=...">
                    <small>Enter the full YouTube video URL</small>
                </div>
                
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Add Video</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addVideoModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Edit Video Modal -->
    <div id="editVideoModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Video</h3>
                <button class="close-modal" onclick="closeModal('editVideoModal')">&times;</button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="update_video">
                <input type="hidden" name="video_id" id="editVideoId">
                
                <div class="form-group">
                    <label>Video Title</label>
                    <input type="text" name="title" id="editTitle" required>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" id="editDescription" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label>YouTube URL</label>
                    <input type="url" name="youtube_url" id="editYoutubeUrl" required>
                </div>
                
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="editStatus">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Update Video</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editVideoModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function showAddVideoModal() {
            document.getElementById('addVideoModal').style.display = 'block';
        }
        
        function editVideo(videoId) {
            // Find video data from the page
            const videos = <?php echo json_encode($videos); ?>;
            const video = videos.find(v => v.id == videoId);
            
            if (video) {
                document.getElementById('editVideoId').value = video.id;
                document.getElementById('editTitle').value = video.title;
                document.getElementById('editDescription').value = video.description || '';
                document.getElementById('editYoutubeUrl').value = video.youtube_url;
                document.getElementById('editStatus').value = video.status;
                
                document.getElementById('editVideoModal').style.display = 'block';
            }
        }
        
        function deleteVideo(videoId) {
            if (confirm('Are you sure you want to delete this video?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete_video">
                    <input type="hidden" name="video_id" value="${videoId}">
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
        .videos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }
        
        .video-item {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            background: white;
        }
        
        .video-preview {
            height: 200px;
            position: relative;
        }
        
        .video-preview iframe {
            width: 100%;
            height: 100%;
        }
        
        .no-preview {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f3f4f6;
            color: #6b7280;
        }
        
        .video-info {
            padding: 15px;
        }
        
        .video-info h4 {
            margin: 0 0 10px 0;
            font-size: 16px;
            font-weight: 600;
        }
        
        .video-info p {
            margin: 0 0 15px 0;
            font-size: 14px;
            color: #6b7280;
            line-height: 1.4;
        }
        
        .video-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .video-actions {
            display: flex;
            gap: 5px;
        }
    </style>
</body>
</html>