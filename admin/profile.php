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
    
    if ($action === 'update_profile') {
        $adminId = $_SESSION['admin_id'];
        
        try {
            $stmt = $pdo->prepare("UPDATE admins SET username = ?, email = ? WHERE id = ?");
            if ($stmt->execute([
                sanitizeInput($_POST['username']),
                sanitizeInput($_POST['email']),
                $adminId
            ])) {
                $_SESSION['admin_username'] = sanitizeInput($_POST['username']);
                $message = 'Profile updated successfully!';
                $messageType = 'success';
            } else {
                $message = 'Error updating profile';
                $messageType = 'error';
            }
        } catch (PDOException $e) {
            $message = 'Error updating profile: ' . $e->getMessage();
            $messageType = 'error';
        }
    }
    
    if ($action === 'change_password') {
        $adminId = $_SESSION['admin_id'];
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];
        
        if ($newPassword !== $confirmPassword) {
            $message = 'New passwords do not match';
            $messageType = 'error';
        } else {
            // Get current admin data
            $stmt = $pdo->prepare("SELECT password_hash FROM admins WHERE id = ?");
            $stmt->execute([$adminId]);
            $admin = $stmt->fetch();
            
            if ($admin && password_verify($currentPassword, $admin['password_hash'])) {
                $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                
                try {
                    $stmt = $pdo->prepare("UPDATE admins SET password_hash = ? WHERE id = ?");
                    if ($stmt->execute([$newPasswordHash, $adminId])) {
                        $message = 'Password changed successfully!';
                        $messageType = 'success';
                    } else {
                        $message = 'Error changing password';
                        $messageType = 'error';
                    }
                } catch (PDOException $e) {
                    $message = 'Error changing password';
                    $messageType = 'error';
                }
            } else {
                $message = 'Current password is incorrect';
                $messageType = 'error';
            }
        }
    }
    
    if ($action === 'upload_profile_image') {
        $imageUrl = sanitizeInput($_POST['profile_image_url']);
        $adminId = $_SESSION['admin_id'];
        
        try {
            // Check if profile_image_url column exists, if not add it
            $stmt = $pdo->prepare("SHOW COLUMNS FROM admins LIKE 'profile_image_url'");
            $stmt->execute();
            
            if ($stmt->rowCount() == 0) {
                // Add the column if it doesn't exist
                $pdo->exec("ALTER TABLE admins ADD COLUMN profile_image_url VARCHAR(500) DEFAULT NULL");
            }
            
            $stmt = $pdo->prepare("UPDATE admins SET profile_image_url = ? WHERE id = ?");
            if ($stmt->execute([$imageUrl, $adminId])) {
                $message = 'Profile image updated successfully!';
                $messageType = 'success';
            } else {
                $message = 'Error updating profile image';
                $messageType = 'error';
            }
        } catch (PDOException $e) {
            $message = 'Error updating profile image';
            $messageType = 'error';
        }
    }
}

// Get current admin data
$stmt = $pdo->prepare("SELECT * FROM admins WHERE id = ?");
$stmt->execute([$_SESSION['admin_id']]);
$admin = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar.php'; ?>
    
    <main class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-user"></i> Profile Settings</h1>
        </div>
        
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <div class="form-grid">
            <!-- Profile Information -->
            <div class="form-section">
                <h3><i class="fas fa-user-circle"></i> Profile Information</h3>
                
                <form method="POST">
                    <input type="hidden" name="action" value="update_profile">
                    
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" value="<?php echo htmlspecialchars($admin['username']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Role</label>
                        <input type="text" value="<?php echo htmlspecialchars(ucfirst($admin['role'])); ?>" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label>Last Login</label>
                        <input type="text" value="<?php echo $admin['last_login'] ? date('M j, Y H:i', strtotime($admin['last_login'])) : 'Never'; ?>" readonly>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Profile
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Profile Image -->
            <div class="form-section">
                <h3><i class="fas fa-image"></i> Profile Image</h3>
                
                <div class="profile-image-preview">
                    <?php if (!empty($admin['profile_image_url'])): ?>
                        <img src="<?php echo htmlspecialchars($admin['profile_image_url']); ?>" alt="Profile Image" id="profileImagePreview">
                    <?php else: ?>
                        <div class="no-image" id="profileImagePreview">
                            <i class="fas fa-user-circle"></i>
                            <p>No profile image</p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <form method="POST">
                    <input type="hidden" name="action" value="upload_profile_image">
                    
                    <div class="form-group">
                        <label>Profile Image URL</label>
                        <input type="url" name="profile_image_url" placeholder="https://example.com/image.jpg" 
                               value="<?php echo htmlspecialchars($admin['profile_image_url'] ?? ''); ?>">
                        <small>Max size: 500KB. Supported formats: JPG, PNG, GIF</small>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Update Image
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Change Password -->
            <div class="form-section">
                <h3><i class="fas fa-lock"></i> Change Password</h3>
                
                <form method="POST">
                    <input type="hidden" name="action" value="change_password">
                    
                    <div class="form-group">
                        <label>Current Password</label>
                        <input type="password" name="current_password" required>
                    </div>
                    
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="new_password" required minlength="6">
                        <small>Minimum 6 characters</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input type="password" name="confirm_password" required minlength="6">
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-key"></i> Change Password
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Account Information -->
            <div class="form-section">
                <h3><i class="fas fa-info-circle"></i> Account Information</h3>
                
                <div class="info-grid">
                    <div class="info-item">
                        <label>Account Created</label>
                        <span><?php echo date('M j, Y H:i', strtotime($admin['created_at'])); ?></span>
                    </div>
                    
                    <div class="info-item">
                        <label>Account Status</label>
                        <span class="status status-<?php echo $admin['status']; ?>">
                            <?php echo ucfirst($admin['status']); ?>
                        </span>
                    </div>
                    
                    <div class="info-item">
                        <label>Admin ID</label>
                        <span><?php echo $admin['id']; ?></span>
                    </div>
                    
                    <div class="info-item">
                        <label>Session Active</label>
                        <span class="status status-active">Active</span>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <style>
        .profile-image-preview {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .profile-image-preview img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #e5e7eb;
        }
        
        .no-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: #f3f4f6;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            border: 4px solid #e5e7eb;
        }
        
        .no-image i {
            font-size: 40px;
            color: #9ca3af;
            margin-bottom: 5px;
        }
        
        .no-image p {
            font-size: 12px;
            color: #6b7280;
            margin: 0;
        }
        
        .info-grid {
            display: grid;
            gap: 15px;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .info-item label {
            font-weight: 600;
            color: #374151;
        }
        
        .info-item span {
            color: #6b7280;
        }
        
        .status-active {
            background: #d1fae5;
            color: #059669;
        }
    </style>
</body>
</html>