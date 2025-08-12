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

// Handle theme change
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'change_theme') {
        $theme = $_POST['theme'] ?? '';
        
        if (updateSiteSetting('current_theme', $theme)) {
            $message = 'Theme changed successfully!';
            $messageType = 'success';
        } else {
            $message = 'Error changing theme';
            $messageType = 'error';
        }
    }
}

// Get current theme
$settings = getSiteSettings();
$currentTheme = $settings['current_theme'] ?? 'blue-dark';

// Enhanced theme configurations with 20 themes
$themes = [
    'blue-dark' => [
        'name' => 'Professional Blue',
        'primary' => '#1e40af',
        'secondary' => '#0891b2',
        'accent' => '#f97316',
        'background' => 'linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #0891b2 100%)',
        'preview' => 'https://images.pexels.com/photos/3184360/pexels-photo-3184360.jpeg?auto=compress&cs=tinysrgb&w=300&h=200'
    ],
    'gradient' => [
        'name' => 'Vibrant Gradient',
        'primary' => '#8b5cf6',
        'secondary' => '#06b6d4',
        'accent' => '#f59e0b',
        'background' => 'linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%)',
        'preview' => 'https://images.pexels.com/photos/3184338/pexels-photo-3184338.jpeg?auto=compress&cs=tinysrgb&w=300&h=200'
    ],
    'teal-orange' => [
        'name' => 'Modern Teal',
        'primary' => '#0d9488',
        'secondary' => '#0891b2',
        'accent' => '#f97316',
        'background' => 'linear-gradient(135deg, #0f766e 0%, #0891b2 50%, #f59e0b 100%)',
        'preview' => 'https://images.pexels.com/photos/3184465/pexels-photo-3184465.jpeg?auto=compress&cs=tinysrgb&w=300&h=200'
    ],
    'light' => [
        'name' => 'Clean Light',
        'primary' => '#2563eb',
        'secondary' => '#0891b2',
        'accent' => '#f97316',
        'background' => 'linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%)',
        'preview' => 'https://images.pexels.com/photos/3184432/pexels-photo-3184432.jpeg?auto=compress&cs=tinysrgb&w=300&h=200'
    ],
    'sunset' => [
        'name' => 'Sunset Orange',
        'primary' => '#ea580c',
        'secondary' => '#dc2626',
        'accent' => '#fbbf24',
        'background' => 'linear-gradient(135deg, #fb923c 0%, #f97316 50%, #ea580c 100%)',
        'preview' => 'https://images.pexels.com/photos/3184339/pexels-photo-3184339.jpeg?auto=compress&cs=tinysrgb&w=300&h=200'
    ],
    'forest' => [
        'name' => 'Forest Green',
        'primary' => '#166534',
        'secondary' => '#059669',
        'accent' => '#84cc16',
        'background' => 'linear-gradient(135deg, #166534 0%, #059669 50%, #10b981 100%)',
        'preview' => 'https://images.pexels.com/photos/3184360/pexels-photo-3184360.jpeg?auto=compress&cs=tinysrgb&w=300&h=200'
    ],
    'royal-purple' => [
        'name' => 'Royal Purple',
        'primary' => '#7c3aed',
        'secondary' => '#a855f7',
        'accent' => '#f59e0b',
        'background' => 'linear-gradient(135deg, #6d28d9 0%, #7c3aed 50%, #a855f7 100%)',
        'preview' => 'https://images.pexels.com/photos/3184338/pexels-photo-3184338.jpeg?auto=compress&cs=tinysrgb&w=300&h=200'
    ],
    'ocean-blue' => [
        'name' => 'Ocean Blue',
        'primary' => '#0284c7',
        'secondary' => '#0891b2',
        'accent' => '#06b6d4',
        'background' => 'linear-gradient(135deg, #0369a1 0%, #0284c7 50%, #0891b2 100%)',
        'preview' => 'https://images.pexels.com/photos/3184465/pexels-photo-3184465.jpeg?auto=compress&cs=tinysrgb&w=300&h=200'
    ],
    'cherry-red' => [
        'name' => 'Cherry Red',
        'primary' => '#dc2626',
        'secondary' => '#ef4444',
        'accent' => '#fbbf24',
        'background' => 'linear-gradient(135deg, #b91c1c 0%, #dc2626 50%, #ef4444 100%)',
        'preview' => 'https://images.pexels.com/photos/3184432/pexels-photo-3184432.jpeg?auto=compress&cs=tinysrgb&w=300&h=200'
    ],
    'golden-yellow' => [
        'name' => 'Golden Yellow',
        'primary' => '#d97706',
        'secondary' => '#f59e0b',
        'accent' => '#fbbf24',
        'background' => 'linear-gradient(135deg, #b45309 0%, #d97706 50%, #f59e0b 100%)',
        'preview' => 'https://images.pexels.com/photos/3184339/pexels-photo-3184339.jpeg?auto=compress&cs=tinysrgb&w=300&h=200'
    ],
    'mint-green' => [
        'name' => 'Mint Green',
        'primary' => '#10b981',
        'secondary' => '#34d399',
        'accent' => '#84cc16',
        'background' => 'linear-gradient(135deg, #059669 0%, #10b981 50%, #34d399 100%)',
        'preview' => 'https://images.pexels.com/photos/3184360/pexels-photo-3184360.jpeg?auto=compress&cs=tinysrgb&w=300&h=200'
    ],
    'lavender' => [
        'name' => 'Lavender Dream',
        'primary' => '#8b5cf6',
        'secondary' => '#a78bfa',
        'accent' => '#f472b6',
        'background' => 'linear-gradient(135deg, #7c3aed 0%, #8b5cf6 50%, #a78bfa 100%)',
        'preview' => 'https://images.pexels.com/photos/3184338/pexels-photo-3184338.jpeg?auto=compress&cs=tinysrgb&w=300&h=200'
    ],
    'coral-pink' => [
        'name' => 'Coral Pink',
        'primary' => '#f43f5e',
        'secondary' => '#fb7185',
        'accent' => '#fbbf24',
        'background' => 'linear-gradient(135deg, #e11d48 0%, #f43f5e 50%, #fb7185 100%)',
        'preview' => 'https://images.pexels.com/photos/3184465/pexels-photo-3184465.jpeg?auto=compress&cs=tinysrgb&w=300&h=200'
    ],
    'midnight-blue' => [
        'name' => 'Midnight Blue',
        'primary' => '#1e3a8a',
        'secondary' => '#3730a3',
        'accent' => '#06b6d4',
        'background' => 'linear-gradient(135deg, #1e1b4b 0%, #1e3a8a 50%, #3730a3 100%)',
        'preview' => 'https://images.pexels.com/photos/3184432/pexels-photo-3184432.jpeg?auto=compress&cs=tinysrgb&w=300&h=200'
    ],
    'emerald' => [
        'name' => 'Emerald Green',
        'primary' => '#059669',
        'secondary' => '#047857',
        'accent' => '#84cc16',
        'background' => 'linear-gradient(135deg, #064e3b 0%, #059669 50%, #047857 100%)',
        'preview' => 'https://images.pexels.com/photos/3184339/pexels-photo-3184339.jpeg?auto=compress&cs=tinysrgb&w=300&h=200'
    ],
    'rose-gold' => [
        'name' => 'Rose Gold',
        'primary' => '#be185d',
        'secondary' => '#ec4899',
        'accent' => '#f59e0b',
        'background' => 'linear-gradient(135deg, #9d174d 0%, #be185d 50%, #ec4899 100%)',
        'preview' => 'https://images.pexels.com/photos/3184360/pexels-photo-3184360.jpeg?auto=compress&cs=tinysrgb&w=300&h=200'
    ],
    'steel-gray' => [
        'name' => 'Steel Gray',
        'primary' => '#475569',
        'secondary' => '#64748b',
        'accent' => '#06b6d4',
        'background' => 'linear-gradient(135deg, #334155 0%, #475569 50%, #64748b 100%)',
        'preview' => 'https://images.pexels.com/photos/3184338/pexels-photo-3184338.jpeg?auto=compress&cs=tinysrgb&w=300&h=200'
    ],
    'amber' => [
        'name' => 'Amber Glow',
        'primary' => '#f59e0b',
        'secondary' => '#fbbf24',
        'accent' => '#fb923c',
        'background' => 'linear-gradient(135deg, #d97706 0%, #f59e0b 50%, #fbbf24 100%)',
        'preview' => 'https://images.pexels.com/photos/3184465/pexels-photo-3184465.jpeg?auto=compress&cs=tinysrgb&w=300&h=200'
    ],
    'indigo' => [
        'name' => 'Indigo Night',
        'primary' => '#4f46e5',
        'secondary' => '#6366f1',
        'accent' => '#06b6d4',
        'background' => 'linear-gradient(135deg, #3730a3 0%, #4f46e5 50%, #6366f1 100%)',
        'preview' => 'https://images.pexels.com/photos/3184432/pexels-photo-3184432.jpeg?auto=compress&cs=tinysrgb&w=300&h=200'
    ],
    'lime' => [
        'name' => 'Lime Fresh',
        'primary' => '#84cc16',
        'secondary' => '#a3e635',
        'accent' => '#10b981',
        'background' => 'linear-gradient(135deg, #65a30d 0%, #84cc16 50%, #a3e635 100%)',
        'preview' => 'https://images.pexels.com/photos/3184339/pexels-photo-3184339.jpeg?auto=compress&cs=tinysrgb&w=300&h=200'
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Themes - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar.php'; ?>
    
    <main class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-palette"></i> Website Themes</h1>
            <p>Choose from 20 beautiful themes for your website</p>
        </div>
        
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Available Themes</h3>
                <p>Current theme: <strong><?php echo htmlspecialchars($themes[$currentTheme]['name']); ?></strong></p>
            </div>
            <div class="card-content">
                <div class="themes-grid">
                    <?php foreach ($themes as $themeId => $theme): ?>
                    <div class="theme-card <?php echo $currentTheme === $themeId ? 'active' : ''; ?>">
                        <div class="theme-preview" style="background: <?php echo $theme['background']; ?>">
                            <div class="preview-content">
                                <div class="preview-header" style="background: <?php echo $theme['primary']; ?>"></div>
                                <div class="preview-body">
                                    <div class="preview-card" style="background: rgba(255,255,255,0.1)"></div>
                                    <div class="preview-card" style="background: rgba(255,255,255,0.1)"></div>
                                </div>
                                <div class="preview-accent" style="background: <?php echo $theme['accent']; ?>"></div>
                            </div>
                        </div>
                        <div class="theme-info">
                            <h4><?php echo htmlspecialchars($theme['name']); ?></h4>
                            <div class="theme-colors">
                                <span class="color-dot" style="background: <?php echo $theme['primary']; ?>" title="Primary"></span>
                                <span class="color-dot" style="background: <?php echo $theme['secondary']; ?>" title="Secondary"></span>
                                <span class="color-dot" style="background: <?php echo $theme['accent']; ?>" title="Accent"></span>
                            </div>
                            <?php if ($currentTheme === $themeId): ?>
                                <button class="theme-btn active" disabled>
                                    <i class="fas fa-check"></i> Current Theme
                                </button>
                            <?php else: ?>
                                <button class="theme-btn" onclick="changeTheme('<?php echo $themeId; ?>')">
                                    <i class="fas fa-palette"></i> Apply Theme
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Theme Preview</h3>
            </div>
            <div class="card-content">
                <p>Click on any theme above to apply it to your website. The changes will be visible immediately on the main website.</p>
                <div class="preview-actions">
                    <a href="../index.php" target="_blank" class="btn btn-secondary">
                        <i class="fas fa-external-link-alt"></i> View Website
                    </a>
                </div>
            </div>
        </div>
    </main>
    
    <script>
        function changeTheme(themeId) {
            if (confirm('Are you sure you want to change the website theme?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="change_theme">
                    <input type="hidden" name="theme" value="${themeId}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
    
    <style>
        .themes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }
        
        .theme-card {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            background: white;
            transition: all 0.3s ease;
        }
        
        .theme-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        .theme-card.active {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .theme-preview {
            height: 120px;
            position: relative;
            overflow: hidden;
        }
        
        .preview-content {
            position: absolute;
            inset: 10px;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        
        .preview-header {
            height: 20px;
            border-radius: 4px;
        }
        
        .preview-body {
            flex: 1;
            display: flex;
            gap: 5px;
        }
        
        .preview-card {
            flex: 1;
            border-radius: 4px;
        }
        
        .preview-accent {
            height: 15px;
            border-radius: 4px;
        }
        
        .theme-info {
            padding: 15px;
        }
        
        .theme-info h4 {
            margin: 0 0 10px 0;
            font-size: 16px;
            font-weight: 600;
        }
        
        .theme-colors {
            display: flex;
            gap: 5px;
            margin-bottom: 15px;
        }
        
        .color-dot {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.1);
        }
        
        .theme-btn {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .theme-btn:not(.active) {
            background: #3b82f6;
            color: white;
        }
        
        .theme-btn:not(.active):hover {
            background: #2563eb;
        }
        
        .theme-btn.active {
            background: #10b981;
            color: white;
            cursor: not-allowed;
        }
        
        .preview-actions {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</body>
</html>