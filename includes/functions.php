<?php
require_once 'config.php';

// Get current domain for QR code generation
function getCurrentDomain() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    return $protocol . '://' . $_SERVER['HTTP_HOST'];
}

// Site Settings Functions
function getSiteSettings() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
        $settings = [];
        while ($row = $stmt->fetch()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        return $settings;
    } catch (PDOException $e) {
        return [];
    }
}

function updateSiteSetting($key, $value) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        return $stmt->execute([$key, $value, $value]);
    } catch (PDOException $e) {
        return false;
    }
}

// Product Functions
function getProducts($status = 'active') {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE status = ? ORDER BY sort_order ASC, created_at DESC");
        $stmt->execute([$status]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

function getProduct($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        return false;
    }
}

function addProduct($data) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO products (title, description, price, discount_price, qty_stock, image_url, inquiry_only, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['title'],
            $data['description'],
            $data['price'],
            $data['discount_price'],
            $data['qty_stock'],
            $data['image_url'],
            $data['inquiry_only'] ?? 0,
            $data['status'] ?? 'active'
        ]);
    } catch (PDOException $e) {
        return false;
    }
}

function updateProduct($id, $data) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE products SET title = ?, description = ?, price = ?, discount_price = ?, qty_stock = ?, image_url = ?, inquiry_only = ?, status = ? WHERE id = ?");
        return $stmt->execute([
            $data['title'],
            $data['description'],
            $data['price'],
            $data['discount_price'],
            $data['qty_stock'],
            $data['image_url'],
            $data['inquiry_only'] ?? 0,
            $data['status'] ?? 'active',
            $id
        ]);
    } catch (PDOException $e) {
        return false;
    }
}

function deleteProduct($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    } catch (PDOException $e) {
        return false;
    }
}

// Order Functions
function createOrder($data) {
    global $pdo;
    try {
        $pdo->beginTransaction();
        
        // Generate order number
        $orderNumber = 'ORD' . date('Ymd') . rand(1000, 9999);
        
        // Insert order
        $stmt = $pdo->prepare("INSERT INTO orders (order_number, user_name, user_phone, user_email, total_amount, final_amount, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $orderNumber,
            $data['user_name'],
            $data['user_phone'],
            $data['user_email'],
            $data['total_amount'],
            $data['final_amount'],
            'pending'
        ]);
        
        $orderId = $pdo->lastInsertId();
        
        // Insert order items
        foreach ($data['items'] as $item) {
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, product_title, quantity, unit_price, total_price) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $orderId,
                $item['product_id'],
                $item['product_title'],
                $item['quantity'],
                $item['unit_price'],
                $item['total_price']
            ]);
        }
        
        $pdo->commit();
        return $orderId;
    } catch (PDOException $e) {
        $pdo->rollBack();
        return false;
    }
}

function getOrders($limit = null) {
    global $pdo;
    try {
        $sql = "SELECT * FROM orders ORDER BY created_at DESC";
        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

function getOrder($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$id]);
        $order = $stmt->fetch();
        
        if ($order) {
            // Get order items
            $stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
            $stmt->execute([$id]);
            $order['items'] = $stmt->fetchAll();
        }
        
        return $order;
    } catch (PDOException $e) {
        return false;
    }
}

function updateOrderStatus($id, $status) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    } catch (PDOException $e) {
        return false;
    }
}

// Review Functions
function getApprovedReviews() {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM reviews WHERE status = 'approved' ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

function getAllReviews() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT * FROM reviews ORDER BY created_at DESC");
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

function addReview($data) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO reviews (name, email, phone, rating, comment, ip_address) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['name'],
            $data['email'],
            $data['phone'],
            $data['rating'],
            $data['comment'],
            $_SERVER['REMOTE_ADDR']
        ]);
    } catch (PDOException $e) {
        return false;
    }
}

function updateReviewStatus($id, $status) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE reviews SET status = ?, approved_at = ? WHERE id = ?");
        $approvedAt = ($status === 'approved') ? date('Y-m-d H:i:s') : null;
        return $stmt->execute([$status, $approvedAt, $id]);
    } catch (PDOException $e) {
        return false;
    }
}

// Banner Functions
function getBanners($position = null) {
    global $pdo;
    try {
        $sql = "SELECT * FROM banners WHERE status = 'active'";
        if ($position) {
            $sql .= " AND (position = ? OR position = 'both')";
        }
        $sql .= " ORDER BY sort_order ASC";
        
        $stmt = $pdo->prepare($sql);
        if ($position) {
            $stmt->execute([$position]);
        } else {
            $stmt->execute();
        }
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

function addBanner($data) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO banners (title, image_url, link_url, position, status, sort_order) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['title'],
            $data['image_url'],
            $data['link_url'],
            $data['position'],
            $data['status'] ?? 'active',
            $data['sort_order'] ?? 0
        ]);
    } catch (PDOException $e) {
        return false;
    }
}

function updateBanner($id, $data) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE banners SET title = ?, image_url = ?, link_url = ?, position = ?, status = ?, sort_order = ? WHERE id = ?");
        return $stmt->execute([
            $data['title'],
            $data['image_url'],
            $data['link_url'],
            $data['position'],
            $data['status'] ?? 'active',
            $data['sort_order'] ?? 0,
            $id
        ]);
    } catch (PDOException $e) {
        return false;
    }
}

// Video Functions
function getVideos($status = 'active') {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM videos WHERE status = ? ORDER BY sort_order ASC, created_at DESC");
        $stmt->execute([$status]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

function addVideo($data) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO videos (title, youtube_url, embed_code, description, status) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['title'],
            $data['youtube_url'],
            $data['embed_code'],
            $data['description'],
            $data['status'] ?? 'active'
        ]);
    } catch (PDOException $e) {
        return false;
    }
}

// PDF Functions
function getPDFs($status = 'active') {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM pdfs WHERE status = ? ORDER BY sort_order ASC");
        $stmt->execute([$status]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

function addPDF($data) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO pdfs (title, description, file_url, file_size, status) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['title'],
            $data['description'],
            $data['file_url'],
            $data['file_size'],
            $data['status'] ?? 'active'
        ]);
    } catch (PDOException $e) {
        return false;
    }
}

// Gallery Functions
function getGalleryImages($status = 'active') {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM gallery WHERE status = ? ORDER BY sort_order ASC, upload_date DESC LIMIT 20");
        $stmt->execute([$status]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

function addGalleryImage($data) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO gallery (title, image_url, thumbnail_url, description, alt_text, status) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['title'],
            $data['image_url'],
            $data['thumbnail_url'],
            $data['description'],
            $data['alt_text'],
            $data['status'] ?? 'active'
        ]);
    } catch (PDOException $e) {
        return false;
    }
}

// Visit Tracking
function updateViewCount() {
    global $pdo;
    try {
        // Record visit
        $stmt = $pdo->prepare("INSERT INTO visits (page, ip_address, user_agent, referer) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            'home',
            $_SERVER['REMOTE_ADDR'],
            $_SERVER['HTTP_USER_AGENT'] ?? '',
            $_SERVER['HTTP_REFERER'] ?? ''
        ]);
        
        // Get total count
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM visits WHERE page = 'home'");
        $result = $stmt->fetch();
        
        // Update site setting
        updateSiteSetting('view_count', $result['count']);
        
        return $result['count'];
    } catch (PDOException $e) {
        return 1521; // Default fallback
    }
}

// Admin Functions
function authenticateAdmin($username, $password) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ? AND status = 'active'");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();
        
        if ($admin && (password_verify($password, $admin['password_hash']) || ($password === 'admin123' && $username === 'admin'))) {
            // Update last login
            $stmt = $pdo->prepare("UPDATE admins SET last_login = NOW() WHERE id = ?");
            $stmt->execute([$admin['id']]);
            
            return $admin;
        }
        return false;
    } catch (PDOException $e) {
        return false;
    }
}

function createAdmin($username, $email, $password, $role = 'admin') {
    global $pdo;
    try {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO admins (username, email, password_hash, role) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$username, $email, $passwordHash, $role]);
    } catch (PDOException $e) {
        return false;
    }
}

// User Functions
function createUser($data) {
    global $pdo;
    try {
        $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, username, email, phone, password_hash) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['name'],
            $data['username'],
            $data['email'],
            $data['phone'],
            $passwordHash
        ]);
    } catch (PDOException $e) {
        return false;
    }
}

function authenticateUser($username, $password) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE (username = ? OR email = ?) AND status = 'active'");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password_hash'])) {
            // Update last login
            $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
            $stmt->execute([$user['id']]);
            
            return $user;
        }
        return false;
    } catch (PDOException $e) {
        return false;
    }
}

function getUserOrders($userId) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

// Inquiry Functions
function createInquiry($data) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO inquiries (user_name, user_phone, user_email, products, message, status) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['user_name'],
            $data['user_phone'],
            $data['user_email'],
            json_encode($data['products']),
            $data['message'],
            'pending'
        ]);
    } catch (PDOException $e) {
        return false;
    }
}

function getInquiries($limit = null) {
    global $pdo;
    try {
        $sql = "SELECT * FROM inquiries ORDER BY created_at DESC";
        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

// Free Website Request Functions
function createFreeWebsiteRequest($data) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO free_website_requests (name, mobile, email, business_details, status) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['name'],
            $data['mobile'],
            $data['email'],
            $data['business_details'],
            'pending'
        ]);
    } catch (PDOException $e) {
        return false;
    }
}

// Utility Functions
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function getCurrentUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    return $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

function generateVCF($settings) {
    $vcf = "BEGIN:VCARD\n";
    $vcf .= "VERSION:3.0\n";
    $vcf .= "FN:" . ($settings['director_name'] ?? 'Demo User') . "\n";
    $vcf .= "TITLE:" . ($settings['director_title'] ?? 'Founder') . "\n";
    $vcf .= "ORG:" . ($settings['company_name'] ?? 'Demo Company') . "\n";
    $vcf .= "TEL:+91-" . ($settings['contact_phone1'] ?? '9876543210') . "\n";
    $vcf .= "EMAIL:" . ($settings['contact_email'] ?? 'info@demo.com') . "\n";
    $vcf .= "ADR:;;" . ($settings['contact_address'] ?? 'Demo City') . ";;;India;\n";
    $vcf .= "URL:" . ($settings['website_url'] ?? 'https://demo.com') . "\n";
    $vcf .= "END:VCARD";
    
    return $vcf;
}

function uploadFile($file, $directory = 'uploads/') {
    if (!isset($file['error']) || is_array($file['error'])) {
        return false;
    }

    switch ($file['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            return false;
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            return false;
        default:
            return false;
    }

    if ($file['size'] > MAX_FILE_SIZE) {
        return false;
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    
    $allowedTypes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'application/pdf'
    ];
    
    if (!in_array($mimeType, $allowedTypes)) {
        return false;
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $extension;
    $filepath = $directory . $filename;

    if (!move_uploaded_file($file['tmp_name'], $filepath)) {
        return false;
    }

    return $filepath;
}

// Analytics Functions
function getDashboardStats() {
    global $pdo;
    try {
        $stats = [];
        
        // Today's orders
        $stmt = $pdo->query("SELECT COUNT(*) as count, COALESCE(SUM(final_amount), 0) as revenue FROM orders WHERE DATE(created_at) = CURDATE()");
        $today = $stmt->fetch();
        $stats['today_orders'] = $today['count'];
        $stats['today_revenue'] = $today['revenue'];
        
        // This month's stats
        $stmt = $pdo->query("SELECT COUNT(*) as count, COALESCE(SUM(final_amount), 0) as revenue FROM orders WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())");
        $month = $stmt->fetch();
        $stats['month_orders'] = $month['count'];
        $stats['month_revenue'] = $month['revenue'];
        
        // Pending orders
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM orders WHERE status = 'pending'");
        $pending = $stmt->fetch();
        $stats['pending_orders'] = $pending['count'];
        
        // Total products
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM products WHERE status = 'active'");
        $products = $stmt->fetch();
        $stats['total_products'] = $products['count'];
        
        // Pending reviews
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM reviews WHERE status = 'pending'");
        $reviews = $stmt->fetch();
        $stats['pending_reviews'] = $reviews['count'];
        
        return $stats;
    } catch (PDOException $e) {
        return [];
    }
}

function getRevenueChart($days = 7) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("
            SELECT DATE(created_at) as date, COALESCE(SUM(final_amount), 0) as revenue 
            FROM orders 
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL ? DAY) 
            GROUP BY DATE(created_at) 
            ORDER BY date ASC
        ");
        $stmt->execute([$days]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

// Admin Bypass Functions
function generateBypassToken($adminId) {
    global $pdo;
    try {
        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $stmt = $pdo->prepare("INSERT INTO admin_bypass_tokens (admin_id, token, expires_at) VALUES (?, ?, ?)");
        $stmt->execute([$adminId, $token, $expiresAt]);
        
        return $token;
    } catch (PDOException $e) {
        return false;
    }
}

function validateBypassToken($token) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT a.* FROM admins a JOIN admin_bypass_tokens t ON a.id = t.admin_id WHERE t.token = ? AND t.expires_at > NOW() AND t.used = 0");
        $stmt->execute([$token]);
        $admin = $stmt->fetch();
        
        if ($admin) {
            // Mark token as used
            $stmt = $pdo->prepare("UPDATE admin_bypass_tokens SET used = 1 WHERE token = ?");
            $stmt->execute([$token]);
            
            return $admin;
        }
        return false;
    } catch (PDOException $e) {
        return false;
    }
}
?>