<header class="admin-header">
    <div class="header-left">
        <button class="sidebar-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="header-title">Microsite Admin</h1>
    </div>
    
    <div class="header-right">
        <div class="header-actions">
            <a href="../index.php" target="_blank" class="header-btn" title="View Website">
                <i class="fas fa-external-link-alt"></i>
            </a>
            
            <div class="dropdown">
                <button class="header-btn dropdown-toggle" onclick="toggleDropdown('notifications')">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </button>
                <div class="dropdown-menu" id="notifications">
                    <div class="dropdown-header">Notifications</div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-shopping-cart"></i>
                        New order received
                        <small>2 minutes ago</small>
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-star"></i>
                        New review pending
                        <small>1 hour ago</small>
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-user"></i>
                        New user registered
                        <small>3 hours ago</small>
                    </a>
                </div>
            </div>
            
            <div class="dropdown">
                <button class="header-btn dropdown-toggle" onclick="toggleDropdown('profile')">
                    <i class="fas fa-user-circle"></i>
                    <span><?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                </button>
                <div class="dropdown-menu" id="profile">
                    <div class="dropdown-header">
                        <?php echo htmlspecialchars($_SESSION['admin_username']); ?>
                        <small><?php echo htmlspecialchars($_SESSION['admin_role']); ?></small>
                    </div>
                    <a href="profile.php" class="dropdown-item">
                        <i class="fas fa-user"></i>
                        Profile Settings
                    </a>
                    <a href="settings.php" class="dropdown-item">
                        <i class="fas fa-cog"></i>
                        Site Settings
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="logout.php" class="dropdown-item">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
function toggleSidebar() {
    document.body.classList.toggle('sidebar-collapsed');
}

function toggleDropdown(id) {
    const dropdown = document.getElementById(id);
    const allDropdowns = document.querySelectorAll('.dropdown-menu');
    
    // Close all other dropdowns
    allDropdowns.forEach(menu => {
        if (menu.id !== id) {
            menu.classList.remove('show');
        }
    });
    
    // Toggle current dropdown
    dropdown.classList.toggle('show');
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.dropdown')) {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.remove('show');
        });
    }
});
</script>