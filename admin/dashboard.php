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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Microsite</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar.php'; ?>
    
    <main class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
            <p>Welcome back, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</p>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
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
            
            <div class="stat-card">
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
            
            <div class="stat-card">
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
            
            <div class="stat-card">
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
        
        <!-- Charts and Recent Activity -->
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
                    <a href="orders.php" class="btn btn-sm">View All</a>
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
                    <a href="products.php?action=add" class="quick-action">
                        <i class="fas fa-plus"></i>
                        <span>Add Product</span>
                    </a>
                    <a href="orders.php" class="quick-action">
                        <i class="fas fa-shopping-cart"></i>
                        <span>View Orders</span>
                    </a>
                    <a href="reviews.php" class="quick-action">
                        <i class="fas fa-star"></i>
                        <span>Manage Reviews</span>
                    </a>
                    <a href="settings.php" class="quick-action">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                    <a href="../index.php" target="_blank" class="quick-action">
                        <i class="fas fa-external-link-alt"></i>
                        <span>View Site</span>
                    </a>
                    <a href="backup.php" class="quick-action">
                        <i class="fas fa-download"></i>
                        <span>Backup Data</span>
                    </a>
                </div>
            </div>
        </div>
    </main>
    
    <script>
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
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
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
                        }
                    }
                }
            }
        });
        
        // Auto-refresh stats every 30 seconds
        setInterval(function() {
            location.reload();
        }, 30000);
    </script>
</body>
</html>