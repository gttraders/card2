<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$stats = getDashboardStats();
$recentOrders = getOrders(10);
$revenueChart = getRevenueChart(7);
$products = getProducts('all');
$inquiryProducts = getProducts('all'); // For inquiry tab
$freeWebsiteRequests = getFreeWebsiteRequests();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Microsite</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar.php'; ?>
    
    <main class="main-content">
        <div class="page-header">
            <div>
                <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
                <p>Welcome back, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</p>
            </div>
            
            <!-- Language Selector -->
            <div class="language-selector">
                <div class="language-dropdown" onclick="toggleLanguageDropdown()">
                    <i class="fas fa-globe"></i>
                    <span id="currentLanguage">English</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="language-options" id="languageOptions">
                    <div class="language-option" onclick="changeLanguage('en')">🇺🇸 English</div>
                    <div class="language-option" onclick="changeLanguage('hi')">🇮🇳 हिन्दी</div>
                    <div class="language-option" onclick="changeLanguage('mr')">🇮🇳 मराठी</div>
                    <div class="language-option" onclick="changeLanguage('gu')">🇮🇳 ગુજરાતી</div>
                    <div class="language-option" onclick="changeLanguage('ta')">🇮🇳 தமிழ்</div>
                </div>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card glow-on-hover">
                <div class="stat-icon">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $stats['today_orders'] ?? 0; ?></h3>
                    <p>Today's Orders</p>
                    <span class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> +12%
                    </span>
                </div>
            </div>
            
            <div class="stat-card glow-on-hover">
                <div class="stat-icon">
                    <i class="fas fa-rupee-sign"></i>
                </div>
                <div class="stat-content">
                    <h3>₹<?php echo number_format($stats['today_revenue'] ?? 0); ?></h3>
                    <p>Today's Revenue</p>
                    <span class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> +8%
                    </span>
                </div>
            </div>
            
            <div class="stat-card glow-on-hover">
                <div class="stat-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $stats['month_orders'] ?? 0; ?></h3>
                    <p>This Month</p>
                    <span class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> +15%
                    </span>
                </div>
            </div>
            
            <div class="stat-card glow-on-hover">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $stats['pending_orders'] ?? 0; ?></h3>
                    <p>Pending Orders</p>
                    <span class="stat-change negative">
                        <i class="fas fa-arrow-down"></i> -3%
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Main Dashboard Tabs -->
        <div class="tab-container">
            <div class="tab-header">
                <button class="tab-btn active" onclick="showTab('overview')">
                    <i class="fas fa-chart-line"></i> Overview
                </button>
                <button class="tab-btn" onclick="showTab('products')">
                    <i class="fas fa-box"></i> Products
                </button>
                <button class="tab-btn" onclick="showTab('inquiry-products')">
                    <i class="fas fa-question-circle"></i> Inquiry Products
                </button>
                <button class="tab-btn" onclick="showTab('orders')">
                    <i class="fas fa-shopping-cart"></i> Orders
                </button>
                <button class="tab-btn" onclick="showTab('free-website')">
                    <i class="fas fa-gift"></i> Free Website Requests
                </button>
                <button class="tab-btn" onclick="showTab('social-media')">
                    <i class="fas fa-share-alt"></i> Social Media
                </button>
                <button class="tab-btn" onclick="showTab('backup')">
                    <i class="fas fa-download"></i> Backup
                </button>
            </div>
            
            <!-- Overview Tab -->
            <div class="tab-content active" id="overviewTab">
                <div class="dashboard-grid">
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3><i class="fas fa-chart-line"></i> Revenue Chart (Last 7 Days)</h3>
                        </div>
                        <div class="card-content">
                            <canvas id="revenueChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                    
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3><i class="fas fa-list"></i> Recent Orders</h3>
                            <a href="orders.php" class="btn btn-sm btn-primary">View All</a>
                        </div>
                        <div class="card-content">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Customer</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recentOrders as $order): ?>
                                        <tr>
                                            <td>#<?php echo htmlspecialchars($order['order_number']); ?></td>
                                            <td><?php echo htmlspecialchars($order['user_name'] ?: 'Guest'); ?></td>
                                            <td>₹<?php echo number_format($order['final_amount']); ?></td>
                                            <td>
                                                <span class="status status-<?php echo $order['status']; ?>">
                                                    <?php echo ucfirst($order['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
                    </div>
                    <div class="card-content">
                        <div class="quick-actions">
                            <a href="products.php?action=add" class="quick-action glow-on-hover">
                                <i class="fas fa-plus"></i>
                                <span>Add Product</span>
                            </a>
                            <a href="orders.php" class="quick-action glow-on-hover">
                                <i class="fas fa-shopping-cart"></i>
                                <span>View Orders</span>
                            </a>
                            <a href="reviews.php" class="quick-action glow-on-hover">
                                <i class="fas fa-star"></i>
                                <span>Manage Reviews</span>
                            </a>
                            <a href="settings.php" class="quick-action glow-on-hover">
                                <i class="fas fa-cog"></i>
                                <span>Settings</span>
                            </a>
                            <a href="../index.php" target="_blank" class="quick-action glow-on-hover">
                                <i class="fas fa-external-link-alt"></i>
                                <span>View Site</span>
                            </a>
                            <a href="#" onclick="showTab('backup')" class="quick-action glow-on-hover">
                                <i class="fas fa-download"></i>
                                <span>Backup Data</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Products Tab -->
            <div class="tab-content" id="productsTab">
                <div class="card-header">
                    <h3><i class="fas fa-box"></i> All Products</h3>
                    <button class="btn btn-primary" onclick="showAddProductModal()">
                        <i class="fas fa-plus"></i> Add Product
                    </button>
                </div>
                <div class="products-grid">
                    <?php foreach ($products as $product): ?>
                    <div class="product-card-admin">
                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                             alt="<?php echo htmlspecialchars($product['title']); ?>"
                             class="product-image-admin">
                        <div class="product-info-admin">
                            <h4 class="product-title-admin"><?php echo htmlspecialchars($product['title']); ?></h4>
                            <div class="product-price-admin">
                                <?php if ($product['discount_price']): ?>
                                    <span style="text-decoration: line-through; font-size: 14px; opacity: 0.6;">₹<?php echo number_format($product['price']); ?></span>
                                    ₹<?php echo number_format($product['discount_price']); ?>
                                <?php else: ?>
                                    ₹<?php echo number_format($product['price']); ?>
                                <?php endif; ?>
                            </div>
                            <div class="product-actions-admin">
                                <button class="btn btn-sm btn-primary" onclick="editProduct(<?php echo $product['id']; ?>)">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deleteProduct(<?php echo $product['id']; ?>)">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Inquiry Products Tab -->
            <div class="tab-content" id="inquiry-productsTab">
                <div class="card-header">
                    <h3><i class="fas fa-question-circle"></i> Inquiry Only Products</h3>
                    <button class="btn btn-primary" onclick="showAddInquiryProductModal()">
                        <i class="fas fa-plus"></i> Add Inquiry Product
                    </button>
                </div>
                <div class="products-grid">
                    <?php foreach ($inquiryProducts as $product): ?>
                        <?php if ($product['inquiry_only']): ?>
                        <div class="product-card-admin">
                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($product['title']); ?>"
                                 class="product-image-admin">
                            <div class="product-info-admin">
                                <h4 class="product-title-admin"><?php echo htmlspecialchars($product['title']); ?></h4>
                                <div class="product-price-admin">₹<?php echo number_format($product['price']); ?></div>
                                <span class="status status-pending" style="margin-bottom: 12px; display: inline-block;">Inquiry Only</span>
                                <div class="product-actions-admin">
                                    <button class="btn btn-sm btn-primary" onclick="editProduct(<?php echo $product['id']; ?>)">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteProduct(<?php echo $product['id']; ?>)">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Orders Tab -->
            <div class="tab-content" id="ordersTab">
                <div class="card-header">
                    <h3><i class="fas fa-shopping-cart"></i> Orders Management</h3>
                    <div class="export-buttons">
                        <button class="export-btn" onclick="exportOrders('csv')">
                            <i class="fas fa-file-csv"></i> Export CSV
                        </button>
                        <button class="export-btn" onclick="exportOrders('txt')">
                            <i class="fas fa-file-alt"></i> Export TXT
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Phone</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentOrders as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['order_number']); ?></td>
                                <td><?php echo htmlspecialchars($order['user_name'] ?: 'Guest'); ?></td>
                                <td><?php echo htmlspecialchars($order['user_phone']); ?></td>
                                <td>₹<?php echo number_format($order['final_amount']); ?></td>
                                <td>
                                    <span class="status status-<?php echo $order['status']; ?>">
                                        <?php echo ucfirst($order['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status status-<?php echo $order['payment_status'] ?? 'pending'; ?>">
                                        <?php echo ucfirst($order['payment_status'] ?? 'pending'); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M j, Y H:i', strtotime($order['created_at'])); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="viewOrder(<?php echo $order['id']; ?>)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Free Website Requests Tab -->
            <div class="tab-content" id="free-websiteTab">
                <div class="card-header">
                    <h3><i class="fas fa-gift"></i> Free Website Requests</h3>
                    <span class="status status-pending">Total: <?php echo count($freeWebsiteRequests); ?> requests</span>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Business Details</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($freeWebsiteRequests as $request): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($request['name']); ?></td>
                                <td>
                                    <a href="tel:<?php echo htmlspecialchars($request['mobile']); ?>" class="btn btn-sm btn-success">
                                        <i class="fas fa-phone"></i> <?php echo htmlspecialchars($request['mobile']); ?>
                                    </a>
                                </td>
                                <td>
                                    <?php if ($request['email']): ?>
                                        <a href="mailto:<?php echo htmlspecialchars($request['email']); ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-envelope"></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">Not provided</span>
                                    <?php endif; ?>
                                </td>
                                <td style="max-width: 200px; word-wrap: break-word;">
                                    <?php echo htmlspecialchars(substr($request['business_details'] ?: 'No details', 0, 100)); ?>
                                </td>
                                <td>
                                    <span class="status status-<?php echo $request['status']; ?>">
                                        <?php echo ucfirst($request['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M j, Y H:i', strtotime($request['created_at'])); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-success" onclick="contactLead('<?php echo htmlspecialchars($request['mobile']); ?>', '<?php echo htmlspecialchars($request['name']); ?>')">
                                        <i class="fab fa-whatsapp"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Social Media Tab -->
            <div class="tab-content" id="social-mediaTab">
                <div class="card-header">
                    <h3><i class="fas fa-share-alt"></i> Social Media Management</h3>
                    <button class="btn btn-primary" onclick="showSocialMediaModal()">
                        <i class="fas fa-plus"></i> Update Social Links
                    </button>
                </div>
                <div class="social-media-grid">
                    <?php
                    $socialPlatforms = [
                        'facebook' => ['name' => 'Facebook', 'icon' => 'fab fa-facebook-f', 'color' => '#1877f2'],
                        'youtube' => ['name' => 'YouTube', 'icon' => 'fab fa-youtube', 'color' => '#ff0000'],
                        'twitter' => ['name' => 'Twitter', 'icon' => 'fab fa-twitter', 'color' => '#1da1f2'],
                        'instagram' => ['name' => 'Instagram', 'icon' => 'fab fa-instagram', 'color' => '#e4405f'],
                        'linkedin' => ['name' => 'LinkedIn', 'icon' => 'fab fa-linkedin-in', 'color' => '#0077b5'],
                        'pinterest' => ['name' => 'Pinterest', 'icon' => 'fab fa-pinterest', 'color' => '#bd081c'],
                        'telegram' => ['name' => 'Telegram', 'icon' => 'fab fa-telegram', 'color' => '#0088cc'],
                        'zomato' => ['name' => 'Zomato', 'icon' => 'fas fa-utensils', 'color' => '#e23744']
                    ];
                    
                    foreach ($socialPlatforms as $platform => $data):
                        $url = $settings['social_' . $platform] ?? '';
                    ?>
                    <div class="social-media-item">
                        <div class="social-icon-large" style="background: <?php echo $data['color']; ?>">
                            <i class="<?php echo $data['icon']; ?>"></i>
                        </div>
                        <h4><?php echo $data['name']; ?></h4>
                        <p style="font-size: 12px; color: var(--admin-text-light); margin-bottom: 12px;">
                            <?php echo $url ? 'Connected' : 'Not connected'; ?>
                        </p>
                        <button class="btn btn-sm btn-primary" onclick="editSocialLink('<?php echo $platform; ?>', '<?php echo htmlspecialchars($url); ?>')">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Backup Tab -->
            <div class="tab-content" id="backupTab">
                <div class="backup-section">
                    <h3><i class="fas fa-shield-alt"></i> Database Backup & Restore</h3>
                    <p style="color: var(--admin-text-light); margin-bottom: 24px;">
                        Create backups of your database and restore from previous backups.
                    </p>
                    
                    <div class="backup-actions">
                        <button class="backup-btn" onclick="createBackup()">
                            <i class="fas fa-download"></i>
                            Create Full Backup
                        </button>
                        <button class="backup-btn" onclick="createDataBackup()">
                            <i class="fas fa-database"></i>
                            Backup Data Only
                        </button>
                        <button class="backup-btn" onclick="showRestoreModal()">
                            <i class="fas fa-upload"></i>
                            Restore Backup
                        </button>
                        <button class="backup-btn" onclick="downloadSampleData()">
                            <i class="fas fa-file-export"></i>
                            Download Sample Data
                        </button>
                    </div>
                    
                    <div id="backupStatus" style="margin-top: 20px;"></div>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Add Product Modal -->
    <div id="addProductModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Product</h3>
                <button class="close-modal" onclick="closeModal('addProductModal')">&times;</button>
            </div>
            <form method="POST" action="products.php">
                <div style="padding: 28px;">
                    <input type="hidden" name="action" value="add_product">
                    
                    <div class="form-group">
                        <label>Product Title</label>
                        <input type="text" name="title" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Price (₹)</label>
                            <input type="number" name="price" step="0.01" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Discount Price (₹)</label>
                            <input type="number" name="discount_price" step="0.01">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Stock Quantity</label>
                            <input type="number" name="qty_stock" value="0">
                        </div>
                        
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Image URL</label>
                        <input type="url" name="image_url" required>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="inquiry_only" style="margin-right: 8px;">
                            Inquiry Only (No Add to Cart)
                        </label>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Add Product</button>
                        <button type="button" class="btn btn-secondary" onclick="closeModal('addProductModal')">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Social Media Modal -->
    <div id="socialMediaModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Update Social Media Links</h3>
                <button class="close-modal" onclick="closeModal('socialMediaModal')">&times;</button>
            </div>
            <form method="POST" action="settings.php">
                <div style="padding: 28px;">
                    <input type="hidden" name="action" value="update_social_media">
                    
                    <?php foreach ($socialPlatforms as $platform => $data): ?>
                    <div class="form-group">
                        <label>
                            <i class="<?php echo $data['icon']; ?>" style="color: <?php echo $data['color']; ?>; margin-right: 8px;"></i>
                            <?php echo $data['name']; ?> URL
                        </label>
                        <input type="url" name="social_<?php echo $platform; ?>" 
                               value="<?php echo htmlspecialchars($settings['social_' . $platform] ?? ''); ?>"
                               placeholder="https://<?php echo $platform; ?>.com/yourpage">
                    </div>
                    <?php endforeach; ?>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Update Social Links</button>
                        <button type="button" class="btn btn-secondary" onclick="closeModal('socialMediaModal')">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Tab functionality
        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Remove active class from all tab buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById(tabName + 'Tab').classList.add('active');
            event.target.classList.add('active');
        }
        
        // Language functionality
        function toggleLanguageDropdown() {
            const dropdown = document.getElementById('languageOptions');
            dropdown.classList.toggle('show');
        }
        
        function changeLanguage(langCode) {
            const languages = {
                'en': 'English',
                'hi': 'हिन्दी',
                'mr': 'मराठी',
                'gu': 'ગુજરાતી',
                'ta': 'தமிழ்'
            };
            
            document.getElementById('currentLanguage').textContent = languages[langCode];
            document.getElementById('languageOptions').classList.remove('show');
            
            // Save language preference
            localStorage.setItem('admin_language', langCode);
            
            // Here you would typically reload content in the selected language
            showMessage('Language changed to ' + languages[langCode], 'success');
        }
        
        // Export functionality
        function exportOrders(format) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '../api/export-orders.php';
            form.innerHTML = `<input type="hidden" name="format" value="${format}">`;
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
            
            showMessage(`Orders exported as ${format.toUpperCase()}`, 'success');
        }
        
        // Product management
        function showAddProductModal() {
            document.getElementById('addProductModal').style.display = 'block';
        }
        
        function showAddInquiryProductModal() {
            document.getElementById('addProductModal').style.display = 'block';
            // Pre-check the inquiry only checkbox
            document.querySelector('input[name="inquiry_only"]').checked = true;
        }
        
        function editProduct(productId) {
            window.location.href = `products.php?action=edit&id=${productId}`;
        }
        
        function deleteProduct(productId) {
            if (confirm('Are you sure you want to delete this product?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'products.php';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete_product">
                    <input type="hidden" name="product_id" value="${productId}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        // Social media management
        function showSocialMediaModal() {
            document.getElementById('socialMediaModal').style.display = 'block';
        }
        
        function editSocialLink(platform, currentUrl) {
            const newUrl = prompt(`Enter ${platform} URL:`, currentUrl);
            if (newUrl !== null) {
                // Update via AJAX
                fetch('../api/update-social.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ platform: platform, url: newUrl })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showMessage('Social link updated successfully', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showMessage('Error updating social link', 'error');
                    }
                });
            }
        }
        
        // Backup functionality
        function createBackup() {
            showMessage('Creating backup...', 'info');
            
            fetch('../api/backup.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'create_full_backup' })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage('Backup created successfully', 'success');
                    // Download the backup file
                    window.open(data.download_url, '_blank');
                } else {
                    showMessage('Error creating backup', 'error');
                }
            })
            .catch(error => {
                showMessage('Error creating backup', 'error');
            });
        }
        
        function createDataBackup() {
            showMessage('Creating data backup...', 'info');
            
            fetch('../api/backup.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'create_data_backup' })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage('Data backup created successfully', 'success');
                    window.open(data.download_url, '_blank');
                } else {
                    showMessage('Error creating data backup', 'error');
                }
            });
        }
        
        function contactLead(mobile, name) {
            const message = `Hi ${name}! Thank you for your interest in our free website service. I'd like to discuss your requirements. When would be a good time to talk?`;
            const whatsappUrl = `https://wa.me/${mobile.replace(/[^0-9]/g, '')}?text=${encodeURIComponent(message)}`;
            window.open(whatsappUrl, '_blank');
        }
        
        function viewOrder(orderId) {
            window.open(`orders.php?view=${orderId}`, '_blank');
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
        
        function showMessage(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            alertDiv.innerHTML = `<i class="fas fa-info-circle"></i> ${message}`;
            
            const mainContent = document.querySelector('.main-content');
            mainContent.insertBefore(alertDiv, mainContent.firstChild);
            
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }
        
        // Revenue Chart
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueData = <?php echo json_encode($revenueChart); ?>;
        
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: revenueData.map(item => {
                    const date = new Date(item.date);
                    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                }),
                datasets: [{
                    label: 'Revenue (₹)',
                    data: revenueData.map(item => item.revenue),
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#2563eb',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₹' + value.toLocaleString();
                            }
                        },
                        grid: {
                            color: '#e5e7eb'
                        }
                    },
                    x: {
                        grid: {
                            color: '#e5e7eb'
                        }
                    }
                }
            }
        });
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.language-selector')) {
                document.getElementById('languageOptions').classList.remove('show');
            }
            
            if (!e.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        });
        
        // Load saved language
        const savedLanguage = localStorage.getItem('admin_language');
        if (savedLanguage) {
            changeLanguage(savedLanguage);
        }
    </script>
</body>
</html>