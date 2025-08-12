<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if logged in as admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$bypassLink = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = generateBypassToken($_SESSION['admin_id']);
    if ($token) {
        $bypassLink = getCurrentDomain() . '/admin/bypass-login.php?token=' . $token;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Bypass Link - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar.php'; ?>
    
    <main class="main-content">
        <div class="page-header">
            <h1>Generate Bypass Login Link</h1>
        </div>
        
        <div class="form-section">
            <form method="POST">
                <button type="submit" class="btn btn-primary">Generate Bypass Link</button>
            </form>
            
            <?php if ($bypassLink): ?>
                <div class="alert alert-success" style="margin-top: 20px;">
                    <h3>Bypass Link Generated:</h3>
                    <input type="text" value="<?php echo htmlspecialchars($bypassLink); ?>" readonly style="width: 100%; padding: 10px; margin: 10px 0;">
                    <p><strong>Note:</strong> This link expires in 1 hour and can only be used once.</p>
                    <button onclick="copyToClipboard('<?php echo htmlspecialchars($bypassLink); ?>')" class="btn btn-secondary">Copy Link</button>
                </div>
            <?php endif; ?>
        </div>
    </main>
    
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Link copied to clipboard!');
            });
        }
    </script>
</body>
</html>