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
    
    if ($action === 'update_social_media') {
        $socialPlatforms = ['facebook', 'youtube', 'twitter', 'instagram', 'linkedin', 'pinterest', 'telegram', 'zomato'];
        
        $success = true;
        foreach ($socialPlatforms as $platform) {
            $url = sanitizeInput($_POST['social_' . $platform] ?? '');
            if (!updateSiteSetting('social_' . $platform, $url)) {
                $success = false;
                break;
            }
        }
        
        if ($success) {
            $message = 'Social media links updated successfully!';
            $messageType = 'success';
        } else {
            $message = 'Error updating social media links';
            $messageType = 'error';
        }
    }
}

// Get current settings
$settings = getSiteSettings();

$socialPlatforms = [
    'facebook' => ['name' => 'Facebook', 'icon' => 'fab fa-facebook-f', 'color' => '#1877f2'],
    'youtube' => ['name' => 'YouTube', 'icon' => 'fab fa-youtube', 'color' => '#ff0000'],
    'twitter' => ['name' => 'Twitter', 'icon' => 'fab fa-twitter', 'color' => '#1da1f2'],
    'instagram' => ['name' => 'Instagram', 'icon' => 'fab fa-instagram', 'color' => '#e4405f'],
    'linkedin' => ['name' => 'LinkedIn', 'icon' => 'fab fa-linkedin-in', 'color' => '#0077b5'],
    'pinterest' => ['name' => 'Pinterest', 'icon' => 'fab fa-pinterest', 'color' => '#bd081c'],
    'telegram' => ['name' => 'Telegram', 'icon' => 'fab fa-telegram', 'color' => '#0088cc'],
    'zomato' => ['name' => 'Zomato', 'icon' => 'fas fa-utensils', 'color' => '#e23744']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar.php'; ?>
    
    <main class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-share-alt"></i> Social Media Management</h1>
        </div>
        
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <i class="fas fa-info-circle"></i>
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Social Media Links</h3>
                <p>Manage all your social media profile links</p>
            </div>
            <div class="card-content">
                <form method="POST">
                    <input type="hidden" name="action" value="update_social_media">
                    
                    <div class="social-media-grid">
                        <?php foreach ($socialPlatforms as $platform => $data): ?>
                        <div class="social-media-item">
                            <div class="social-icon-large" style="background: <?php echo $data['color']; ?>">
                                <i class="<?php echo $data['icon']; ?>"></i>
                            </div>
                            <h4><?php echo $data['name']; ?></h4>
                            <div class="form-group">
                                <label><?php echo $data['name']; ?> URL</label>
                                <input type="url" name="social_<?php echo $platform; ?>" 
                                       value="<?php echo htmlspecialchars($settings['social_' . $platform] ?? ''); ?>"
                                       placeholder="https://<?php echo $platform; ?>.com/yourpage">
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Social Media Links
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Preview</h3>
            </div>
            <div class="card-content">
                <p>Preview how your social media icons will appear on the website:</p>
                <div style="display: flex; gap: 15px; justify-content: center; margin-top: 20px;">
                    <?php foreach ($socialPlatforms as $platform => $data): ?>
                        <?php $url = $settings['social_' . $platform] ?? ''; ?>
                        <a href="<?php echo htmlspecialchars($url ?: '#'); ?>" 
                           target="_blank" 
                           class="social-icon-large" 
                           style="background: <?php echo $data['color']; ?>; width: 50px; height: 50px; text-decoration: none; <?php echo !$url ? 'opacity: 0.3;' : ''; ?>">
                            <i class="<?php echo $data['icon']; ?>" style="color: white;"></i>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>
</body>
</html>