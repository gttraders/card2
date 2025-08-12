<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if logged in
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';

try {
    switch ($action) {
        case 'create_full_backup':
            $filename = 'full_backup_' . date('Y-m-d_H-i-s') . '.sql';
            $filepath = '../backups/' . $filename;
            
            // Create backups directory if it doesn't exist
            if (!is_dir('../backups')) {
                mkdir('../backups', 0755, true);
            }
            
            // Get all tables
            $stmt = $pdo->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            $backup = "-- Full Database Backup\n";
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
            
            file_put_contents($filepath, $backup);
            
            echo json_encode([
                'success' => true,
                'message' => 'Full backup created successfully',
                'download_url' => '../backups/' . $filename
            ]);
            break;
            
        case 'create_data_backup':
            $filename = 'data_backup_' . date('Y-m-d_H-i-s') . '.sql';
            $filepath = '../backups/' . $filename;
            
            if (!is_dir('../backups')) {
                mkdir('../backups', 0755, true);
            }
            
            $backup = "-- Data Only Backup\n";
            $backup .= "-- Generated on: " . date('Y-m-d H:i:s') . "\n\n";
            
            $dataTables = ['products', 'orders', 'order_items', 'reviews', 'banners', 'videos', 'pdfs', 'gallery', 'site_settings'];
            
            foreach ($dataTables as $table) {
                $stmt = $pdo->query("SELECT * FROM `$table`");
                $rows = $stmt->fetchAll();
                
                if (!empty($rows)) {
                    $backup .= "-- Data for table: $table\n";
                    $backup .= "DELETE FROM `$table`;\n";
                    
                    foreach ($rows as $row) {
                        $values = array_map(function($value) use ($pdo) {
                            return $value === null ? 'NULL' : $pdo->quote($value);
                        }, array_values($row));
                        
                        $backup .= "INSERT INTO `$table` VALUES (" . implode(', ', $values) . ");\n";
                    }
                    $backup .= "\n";
                }
            }
            
            file_put_contents($filepath, $backup);
            
            echo json_encode([
                'success' => true,
                'message' => 'Data backup created successfully',
                'download_url' => '../backups/' . $filename
            ]);
            break;
            
        default:
            echo json_encode(['error' => 'Invalid action']);
    }
    
} catch (Exception $e) {
    echo json_encode(['error' => 'Backup failed: ' . $e->getMessage()]);
}
?>