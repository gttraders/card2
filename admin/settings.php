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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_settings') {
        $settings = [
            'site_title' => $_POST['site_title'] ?? '',
            'company_name' => $_POST['company_name'] ?? '',
            'director_name' => $_POST['director_name'] ?? '',
            'director_title' => $_POST['director_title'] ?? '',
            'contact_phone1' => $_POST['contact_phone1'] ?? '',
            'contact_phone2' => $_POST['contact_phone2'] ?? '',
            'contact_email' => $_POST['contact_email'] ?? '',
            'contact_address' => $_POST['contact_address'] ?? '',
            'whatsapp_number' => $_POST['whatsapp_number'] ?? '',
            'website_url' => $_POST['website_url'] ?? '',
            'upi_id' => $_POST['upi_id'] ?? '',
            'meta_description' => $_POST['meta_description'] ?? '',
            'meta_keywords' => $_POST['meta_keywords'] ?? '',
            'discount_text' => $_POST['discount_text'] ?? '',
            'show_discount_popup' => isset($_POST['show_discount_popup']) ? '1' : '0',
            'show_pwa_prompt' => isset($_POST['show_pwa_prompt']) ? '1' : '0',
            'current_theme' => $_POST['current_theme'] ?? 'blue-dark'
        ];
        
        $success = true;
        foreach ($settings as $key => $value) {
            if (!updateSiteSetting($key, $value)) {
                $success = false;
                break;
            }
        }
        
        if ($success) {
            $message = 'Settings updated successfully!';
            $messageType = 'success';
        } else {
            $message = 'Error updating settings';
            $messageType = 'error';
        }
    }
}

// Get current settings
$settings = getSiteSettings();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Settings - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar.php'; ?>
    
    <main class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-cog"></i> Site Settings</h1>
        </div>
        
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" class="form-grid">
            <input type="hidden" name="action" value="update_settings">
            
            <!-- Basic Information -->
            <div class="form-section">
                <h3><i class="fas fa-info-circle"></i> Basic Information</h3>
                
                <div class="form-group">
                    <label>Site Title</label>
                    <input type="text" name="site_title" value="<?php echo htmlspecialchars($settings['site_title'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Company Name</label>
                    <input type="text" name="company_name" value="<?php echo htmlspecialchars($settings['company_name'] ?? ''); ?>" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Director Name</label>
                        <input type="text" name="director_name" value="<?php echo htmlspecialchars($settings['director_name'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Director Title</label>
                        <input type="text" name="director_title" value="<?php echo htmlspecialchars($settings['director_title'] ?? ''); ?>">
                    </div>
                </div>
            </div>
            
            <!-- Contact Information -->
            <div class="form-section">
                <h3><i class="fas fa-phone"></i> Contact Information</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Phone 1</label>
                        <input type="tel" name="contact_phone1" value="<?php echo htmlspecialchars($settings['contact_phone1'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Phone 2</label>
                        <input type="tel" name="contact_phone2" value="<?php echo htmlspecialchars($settings['contact_phone2'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="contact_email" value="<?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label>Address</label>
                    <textarea name="contact_address" rows="3"><?php echo htmlspecialchars($settings['contact_address'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>WhatsApp Number (with country code)</label>
                        <input type="tel" name="whatsapp_number" value="<?php echo htmlspecialchars($settings['whatsapp_number'] ?? ''); ?>" placeholder="919876543210">
                    </div>
                    
                    <div class="form-group">
                        <label>Website URL</label>
                        <input type="url" name="website_url" value="<?php echo htmlspecialchars($settings['website_url'] ?? ''); ?>">
                    </div>
                </div>
            </div>
            
            <!-- Payment Settings -->
            <div class="form-section">
                <h3><i class="fas fa-credit-card"></i> Payment Settings</h3>
                
                <div class="form-group">
                    <label>UPI ID</label>
                    <input type="text" name="upi_id" value="<?php echo htmlspecialchars($settings['upi_id'] ?? ''); ?>" placeholder="yourname@upi">
                    <small>This UPI ID will be used for payment QR code generation</small>
                </div>
            </div>
            
            <!-- SEO Settings -->
            <div class="form-section">
                <h3><i class="fas fa-search"></i> SEO Settings</h3>
                
                <div class="form-group">
                    <label>Meta Description</label>
                    <textarea name="meta_description" rows="3"><?php echo htmlspecialchars($settings['meta_description'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Meta Keywords</label>
                    <input type="text" name="meta_keywords" value="<?php echo htmlspecialchars($settings['meta_keywords'] ?? ''); ?>">
                </div>
            </div>
            
            <!-- Display Settings -->
            <div class="form-section">
                <h3><i class="fas fa-palette"></i> Display Settings</h3>
                
                <div class="form-group">
                    <label>Current Theme</label>
                    <select name="current_theme">
                        <option value="blue-dark" <?php echo ($settings['current_theme'] ?? '') === 'blue-dark' ? 'selected' : ''; ?>>Professional Blue</option>
                        <option value="gradient" <?php echo ($settings['current_theme'] ?? '') === 'gradient' ? 'selected' : ''; ?>>Vibrant Gradient</option>
                        <option value="teal-orange" <?php echo ($settings['current_theme'] ?? '') === 'teal-orange' ? 'selected' : ''; ?>>Modern Teal</option>
                        <option value="light" <?php echo ($settings['current_theme'] ?? '') === 'light' ? 'selected' : ''; ?>>Clean Light</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Discount Popup Text</label>
                    <input type="text" name="discount_text" value="<?php echo htmlspecialchars($settings['discount_text'] ?? 'DISCOUNT UPTO 50% Live Use FREE code'); ?>">
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="show_discount_popup" <?php echo ($settings['show_discount_popup'] ?? '1') === '1' ? 'checked' : ''; ?>>
                        Show Discount Popup
                    </label>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="show_pwa_prompt" <?php echo ($settings['show_pwa_prompt'] ?? '1') === '1' ? 'checked' : ''; ?>>
                        Show PWA Install Prompt
                    </label>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Save Settings
                </button>
            </div>
        </form>
    </main>
</body>
</html>