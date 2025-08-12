<?php
// Admin Bypass Link Generator
// This file should be kept secure and not linked anywhere publicly

session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Security check - only allow from localhost or specific IPs
$allowedIPs = ['127.0.0.1', '::1', 'localhost'];
$clientIP = $_SERVER['REMOTE_ADDR'] ?? '';

// For development, you can comment out this check
// if (!in_array($clientIP, $allowedIPs)) {
//     die('Access denied');
// }

$bypassLink = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adminId = $_POST['admin_id'] ?? 1; // Default to admin ID 1
    
    // Verify admin exists
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE id = ? AND status = 'active'");
    $stmt->execute([$adminId]);
    $admin = $stmt->fetch();
    
    if ($admin) {
        $token = generateBypassToken($adminId);
        if ($token) {
            $bypassLink = getCurrentDomain() . '/admin/bypass-login.php?token=' . $token;
        } else {
            $error = 'Failed to generate bypass token';
        }
    } else {
        $error = 'Admin not found or inactive';
    }
}

// Get all admins for selection
$stmt = $pdo->query("SELECT id, username, email FROM admins WHERE status = 'active'");
$admins = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Bypass Link Generator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        select, input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background: #007cba;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        button:hover {
            background: #005a87;
        }
        .result {
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
        }
        .success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        .link-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 10px;
            border-radius: 5px;
            word-break: break-all;
            margin: 10px 0;
            font-family: monospace;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .copy-btn {
            background: #28a745;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
            width: auto;
        }
        .copy-btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Admin Bypass Link Generator</h1>
        
        <div class="warning">
            <strong>⚠️ Security Warning:</strong> This tool generates temporary bypass links for admin access. 
            Links expire in 1 hour and can only be used once. Keep this page secure and don't share bypass links.
        </div>
        
        <form method="POST">
            <div class="form-group">
                <label for="admin_id">Select Admin:</label>
                <select name="admin_id" id="admin_id" required>
                    <?php foreach ($admins as $admin): ?>
                        <option value="<?php echo $admin['id']; ?>">
                            <?php echo htmlspecialchars($admin['username'] . ' (' . $admin['email'] . ')'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit">Generate Bypass Link</button>
        </form>
        
        <?php if ($error): ?>
            <div class="result error">
                <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($bypassLink): ?>
            <div class="result success">
                <strong>✅ Bypass Link Generated Successfully!</strong>
                
                <div class="link-box">
                    <?php echo htmlspecialchars($bypassLink); ?>
                </div>
                
                <button class="copy-btn" onclick="copyToClipboard('<?php echo htmlspecialchars($bypassLink); ?>')">
                    📋 Copy Link
                </button>
                
                <div style="margin-top: 15px; font-size: 14px;">
                    <strong>Important:</strong>
                    <ul style="margin: 5px 0; padding-left: 20px;">
                        <li>This link expires in <strong>1 hour</strong></li>
                        <li>Can only be used <strong>once</strong></li>
                        <li>Automatically logs you into the admin panel</li>
                        <li>Don't share this link with unauthorized users</li>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
        
        <div style="margin-top: 30px; text-align: center; color: #666; font-size: 14px;">
            <p>🔒 This page is for authorized administrators only</p>
            <p>Access URL: <code><?php echo getCurrentDomain(); ?>/admin/bypass.php</code></p>
        </div>
    </div>
    
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('✅ Bypass link copied to clipboard!');
            }).catch(() => {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                alert('✅ Bypass link copied to clipboard!');
            });
        }
    </script>
</body>
</html>