<?php
// Admin Bypass Link Generator
// This file should be kept secure and not linked anywhere publicly

session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
        }
        .form-group {
            margin-bottom: 25px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
            font-size: 14px;
        }
        select, input {
            width: 100%;
            padding: 15px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        select:focus, input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        .result {
            margin-top: 25px;
            padding: 20px;
            border-radius: 10px;
        }
        .success {
            background: #d1fae5;
            border: 2px solid #10b981;
            color: #065f46;
        }
        .error {
            background: #fee2e2;
            border: 2px solid #ef4444;
            color: #991b1b;
        }
        .link-box {
            background: #f8f9fa;
            border: 2px solid #dee2e6;
            padding: 15px;
            border-radius: 10px;
            word-break: break-all;
            margin: 15px 0;
            font-family: 'Courier New', monospace;
            font-size: 14px;
        }
        .warning {
            background: #fef3c7;
            border: 2px solid #f59e0b;
            color: #92400e;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
        }
        .copy-btn {
            background: #10b981;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            margin-top: 15px;
            width: auto;
            transition: all 0.3s ease;
        }
        .copy-btn:hover {
            background: #059669;
            transform: translateY(-1px);
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            color: #666;
            font-size: 14px;
        }
        .footer code {
            background: #f3f4f6;
            padding: 4px 8px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
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
                <label for="admin_id">Select Admin Account:</label>
                <select name="admin_id" id="admin_id" required>
                    <?php foreach ($admins as $admin): ?>
                        <option value="<?php echo $admin['id']; ?>">
                            <?php echo htmlspecialchars($admin['username'] . ' (' . $admin['email'] . ')'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit">🚀 Generate Bypass Link</button>
        </form>
        
        <?php if ($error): ?>
            <div class="result error">
                <strong>❌ Error:</strong> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($bypassLink): ?>
            <div class="result success">
                <strong>✅ Bypass Link Generated Successfully!</strong>
                
                <div class="link-box">
                    <?php echo htmlspecialchars($bypassLink); ?>
                </div>
                
                <button class="copy-btn" onclick="copyToClipboard('<?php echo htmlspecialchars($bypassLink); ?>')">
                    📋 Copy Link to Clipboard
                </button>
                
                <div style="margin-top: 20px; font-size: 14px; line-height: 1.6;">
                    <strong>📋 Important Notes:</strong>
                    <ul style="margin: 10px 0; padding-left: 25px;">
                        <li>This link expires in <strong>1 hour</strong></li>
                        <li>Can only be used <strong>once</strong></li>
                        <li>Automatically logs you into the admin panel</li>
                        <li>Don't share this link with unauthorized users</li>
                        <li>Delete this link after use for security</li>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="footer">
            <p>🔒 This page is for authorized administrators only</p>
            <p>Access URL: <code><?php echo getCurrentDomain(); ?>/bypass-admin.php</code></p>
            <p style="margin-top: 15px; font-size: 12px; opacity: 0.7;">
                Keep this URL secure and don't share it publicly
            </p>
        </div>
    </div>
    
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('✅ Bypass link copied to clipboard!\n\nYou can now paste it in a new tab to access the admin panel.');
            }).catch(() => {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = text;
                textArea.style.position = 'fixed';
                textArea.style.opacity = '0';
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                alert('✅ Bypass link copied to clipboard!');
            });
        }
        
        // Auto-focus on select
        document.getElementById('admin_id').focus();
    </script>
</body>
</html>