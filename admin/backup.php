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

// Handle backup actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create_backup') {
        try {
            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $filepath = '../backups/' . $filename;
            
            // Create backups directory if it doesn't exist
            if (!is_dir('../backups')) {
                mkdir('../backups', 0755, true);
            }
            
            // Get all tables
            $stmt = $pdo->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            $backup = "-- Database Backup\n";
            $backup .= "-- Generated on: " . date('Y-m-d H:i:s') . "\n\n";
            
            foreach ($tables as $table) {
                // Get table structure
                $stmt = $pdo->query("SHOW CREATE TABLE `$table`");
                $createTable = $stmt->fetch();
                $backup .= "-- Table: $table\n";
                $backup .= "DROP TABLE IF EXISTS `$table`;\n";
                $backup .= $createTable['Create Table'] . ";\n\n";
                
                // Get table data
                $stmt = $pdo->query("SELECT * FROM `$table`");
                $rows = $stmt->fetchAll();
                
                if (!empty($rows)) {
                    $backup .= "-- Data for table: $table\n";
                    foreach ($rows as $row) {
                        $values = array_map(function($value) use ($pdo) {
                            return $value === null ? 'NULL' : $pdo->quote($value);
                        }, array_values($row));
                        
                        $backup .= "INSERT INTO `$table` VALUES (" . implode(', ', $values) . ");\n";
                    }
                    $backup .= "\n";
                }
            }
            
            if (file_put_contents($filepath, $backup)) {
                $message = 'Backup created successfully! <a href="../backups/' . $filename . '" download>Download Backup</a>';
                $messageType = 'success';
            } else {
                $message = 'Error creating backup file';
                $messageType = 'error';
            }
            
        } catch (Exception $e) {
            $message = 'Error creating backup: ' . $e->getMessage();
            $messageType = 'error';
        }
    }
}

// Get existing backup files
$backupFiles = [];
if (is_dir('../backups')) {
    $files = scandir('../backups');
    foreach ($files as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
            $backupFiles[] = [
                'name' => $file,
                'size' => filesize('../backups/' . $file),
                'date' => filemtime('../backups/' . $file)
            ];
        }
    }
    // Sort by date (newest first)
    usort($backupFiles, function($a, $b) {
        return $b['date'] - $a['date'];
    });
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backup - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar.php'; ?>
    
    <main class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-shield-alt"></i> Database Backup & Restore</h1>
        </div>
        
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <div class="backup-section">
            <h3><i class="fas fa-download"></i> Create Backup</h3>
            <p style="color: var(--admin-text-light); margin-bottom: 24px;">
                Create a complete backup of your database including all tables and data.
            </p>
            
            <form method="POST">
                <input type="hidden" name="action" value="create_backup">
                <div class="backup-actions">
                    <button type="submit" class="backup-btn">
                        <i class="fas fa-download"></i>
                        Create Full Backup
                    </button>
                </div>
            </form>
        </div>
        
        <?php if (!empty($backupFiles)): ?>
        <div class="dashboard-card" style="margin-top: 32px;">
            <div class="card-header">
                <h3><i class="fas fa-history"></i> Backup History</h3>
            </div>
            <div class="card-content">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Backup File</th>
                                <th>Size</th>
                                <th>Created Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($backupFiles as $backup): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($backup['name']); ?></td>
                                <td><?php echo number_format($backup['size'] / 1024, 2); ?> KB</td>
                                <td><?php echo date('M j, Y H:i', $backup['date']); ?></td>
                                <td>
                                    <a href="../backups/<?php echo htmlspecialchars($backup['name']); ?>" 
                                       download class="btn btn-sm btn-primary">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                    <button class="btn btn-sm btn-danger" 
                                            onclick="deleteBackup('<?php echo htmlspecialchars($backup['name']); ?>')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="dashboard-card" style="margin-top: 32px;">
            <div class="card-header">
                <h3><i class="fas fa-info-circle"></i> Backup Information</h3>
            </div>
            <div class="card-content">
                <div style="display: grid; gap: 16px;">
                    <div style="padding: 16px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px;">
                        <h4 style="color: #166534; margin-bottom: 8px;">✅ What's Included in Backup:</h4>
                        <ul style="color: #166534; margin-left: 20px;">
                            <li>All database tables and structure</li>
                            <li>All products, orders, and customer data</li>
                            <li>Reviews, videos, banners, and gallery</li>
                            <li>Site settings and configurations</li>
                            <li>Admin accounts and permissions</li>
                        </ul>
                    </div>
                    
                    <div style="padding: 16px; background: #fffbeb; border: 1px solid #fed7aa; border-radius: 8px;">
                        <h4 style="color: #92400e; margin-bottom: 8px;">⚠️ Important Notes:</h4>
                        <ul style="color: #92400e; margin-left: 20px;">
                            <li>Backups are stored in the /backups directory</li>
                            <li>Regular backups are recommended (daily/weekly)</li>
                            <li>Keep backups in a secure location</li>
                            <li>Test restore process periodically</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <script>
        function deleteBackup(filename) {
            if (confirm('Are you sure you want to delete this backup file?')) {
                fetch('../api/delete-backup.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ filename: filename })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error deleting backup file');
                    }
                });
            }
        }
    </script>
</body>
</html>