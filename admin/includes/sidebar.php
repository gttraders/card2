<aside class="admin-sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <i class="fas fa-store"></i>
            <span>Microsite</span>
        </div>
    </div>
    
    <nav class="sidebar-nav">
        <ul class="nav-list">
            <li class="nav-item">
                <a href="dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="products.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'products.php' ? 'active' : ''; ?>">
                    <i class="fas fa-box"></i>
                    <span>Products</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="orders.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'orders.php' ? 'active' : ''; ?>">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Orders</span>
                    <?php
                    $pendingCount = $stats['pending_orders'] ?? 0;
                    if ($pendingCount > 0):
                    ?>
                    <span class="nav-badge"><?php echo $pendingCount; ?></span>
                    <?php endif; ?>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="reviews.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'reviews.php' ? 'active' : ''; ?>">
                    <i class="fas fa-star"></i>
                    <span>Reviews</span>
                    <?php
                    $pendingReviews = $stats['pending_reviews'] ?? 0;
                    if ($pendingReviews > 0):
                    ?>
                    <span class="nav-badge"><?php echo $pendingReviews; ?></span>
                    <?php endif; ?>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="gallery.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'gallery.php' ? 'active' : ''; ?>">
                    <i class="fas fa-images"></i>
                    <span>Gallery</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="videos.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'videos.php' ? 'active' : ''; ?>">
                    <i class="fab fa-youtube"></i>
                    <span>Videos</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="banners.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'banners.php' ? 'active' : ''; ?>">
                    <i class="fas fa-image"></i>
                    <span>Banners</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="pdfs.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'pdfs.php' ? 'active' : ''; ?>">
                    <i class="fas fa-file-pdf"></i>
                    <span>PDF Downloads</span>
                </a>
            </li>
            
            <li class="nav-divider"></li>
            
            <li class="nav-item">
                <a href="analytics.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'analytics.php' ? 'active' : ''; ?>">
                    <i class="fas fa-chart-bar"></i>
                    <span>Analytics</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="themes.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'themes.php' ? 'active' : ''; ?>">
                    <i class="fas fa-palette"></i>
                    <span>Themes</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="translations.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'translations.php' ? 'active' : ''; ?>">
                    <i class="fas fa-language"></i>
                    <span>Languages</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="settings.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'settings.php' ? 'active' : ''; ?>">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </li>
            
            <li class="nav-divider"></li>
            
            <li class="nav-item">
                <a href="backup.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'backup.php' ? 'active' : ''; ?>">
                    <i class="fas fa-download"></i>
                    <span>Backup</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="../index.php" target="_blank" class="nav-link">
                    <i class="fas fa-external-link-alt"></i>
                    <span>View Website</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="logout.php" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>
    
    <div class="sidebar-footer">
        <div class="sidebar-user">
            <i class="fas fa-user-circle"></i>
            <div class="user-info">
                <div class="user-name"><?php echo htmlspecialchars($_SESSION['admin_username']); ?></div>
                <div class="user-role"><?php echo htmlspecialchars($_SESSION['admin_role']); ?></div>
            </div>
        </div>
    </div>
</aside>