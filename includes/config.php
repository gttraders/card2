<?php
// Database Configuration
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'u261459251_wap');
define('DB_USER', $_ENV['DB_USER'] ?? 'u261459251_wapmini');
define('DB_PASS', $_ENV['DB_PASS'] ?? 'Vishraj@9884');
define('DB_CHARSET', 'utf8mb4');

// Site Configuration
define('SITE_URL', $_ENV['SITE_URL'] ?? 'http://localhost');
define('UPLOAD_PATH', 'uploads/');
define('MAX_FILE_SIZE', 5242880); // 5MB
define('ADMIN_SECRET', $_ENV['ADMIN_SECRET'] ?? 'your-secure-secret-key');

// UPI Configuration
define('UPI_ID', $_ENV['UPI_ID'] ?? 'demo@upi');
define('UPI_MERCHANT_NAME', $_ENV['UPI_MERCHANT_NAME'] ?? 'Demo Business');

// Security Settings
define('SESSION_TIMEOUT', 3600); // 1 hour
define('MAX_LOGIN_ATTEMPTS', 5);

// Error Reporting (set to false in production)
define('DEBUG_MODE', $_ENV['DEBUG_MODE'] ?? true);

if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Database Connection
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    if (DEBUG_MODE) {
        die("Database connection failed: " . $e->getMessage());
    } else {
        die("Database connection failed. Please try again later.");
    }
}

// Load environment variables from .env file if it exists
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        if (!array_key_exists($name, $_ENV)) {
            $_ENV[$name] = $value;
        }
    }
}
?>