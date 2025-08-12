<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (isset($_GET['token'])) {
    $token = sanitizeInput($_GET['token']);
    $admin = validateBypassToken($token);
    
    if ($admin) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['admin_role'] = $admin['role'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid or expired bypass token';
    }
} else {
    $error = 'No bypass token provided';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bypass Login - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>Bypass Login</h1>
                <p><?php echo htmlspecialchars($error); ?></p>
            </div>
            <a href="index.php" class="back-link">Back to Login</a>
        </div>
    </div>
</body>
</html>