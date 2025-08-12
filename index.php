<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Get site settings
$settings = getSiteSettings();
$currentTheme = $settings['current_theme'] ?? 'blue-dark';
$viewCount = updateViewCount();

// Get data for the page
$products = getProducts();
$banners = getBanners();
$videos = getVideos();
$reviews = getApprovedReviews();
$pdfs = getPDFs();
$gallery = getGalleryImages();

// Enhanced theme configurations
$themes = [
    'blue-dark' => [
        'name' => 'Professional Blue',
        'primary' => '#1e40af',
        'secondary' => '#0891b2',
        'accent' => '#f97316',
        'background' => 'linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #0891b2 100%)',
        'cardBg' => 'rgba(255, 255, 255, 0.1)',
        'text' => '#ffffff',
        'textSecondary' => '#e2e8f0'
    ],
    'gradient' => [
        'name' => 'Vibrant Gradient',
        'primary' => '#8b5cf6',
        'secondary' => '#06b6d4',
        'accent' => '#f59e0b',
        'background' => 'linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%)',
        'cardBg' => 'rgba(255, 255, 255, 0.15)',
        'text' => '#ffffff',
        'textSecondary' => '#f1f5f9'
    ],
    'teal-orange' => [
        'name' => 'Modern Teal',
        'primary' => '#0d9488',
        'secondary' => '#0891b2',
        'accent' => '#f97316',
        'background' => 'linear-gradient(135deg, #0f766e 0%, #0891b2 50%, #f59e0b 100%)',
        'cardBg' => 'rgba(255, 255, 255, 0.12)',
        'text' => '#ffffff',
        'textSecondary' => '#e2e8f0'
    ],
    'light' => [
        'name' => 'Clean Light',
        'primary' => '#2563eb',
        'secondary' => '#0891b2',
        'accent' => '#f97316',
        'background' => 'linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%)',
        'cardBg' => '#ffffff',
        'text' => '#1e293b',
        'textSecondary' => '#64748b'
    ]
];

$theme = $themes[$currentTheme];
$currentDomain = getCurrentDomain();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($settings['site_title'] ?? 'DEMO CARD'); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($settings['meta_description'] ?? 'Professional digital visiting card and business services'); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($settings['meta_keywords'] ?? 'visiting card, business card, digital card'); ?>">
    
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="<?php echo $theme['primary']; ?>">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="<?php echo htmlspecialchars($settings['site_title'] ?? 'DEMO CARD'); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
    <link rel="apple-touch-icon" href="assets/images/apple-touch-icon.png">
    <link rel="manifest" href="manifest.json">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: <?php echo $theme['primary']; ?>;
            --secondary-color: <?php echo $theme['secondary']; ?>;
            --accent-color: <?php echo $theme['accent']; ?>;
            --card-bg: <?php echo $theme['cardBg']; ?>;
            --text-color: <?php echo $theme['text']; ?>;
            --text-secondary: <?php echo $theme['textSecondary']; ?>;
        }
        
        body {
            background: <?php echo $theme['background']; ?>;
            color: <?php echo $theme['text']; ?>;
            font-family: 'Inter', sans-serif;
        }
        
        /* Sticky Banner */
        .sticky-banner {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, #ff6b6b, #feca57);
            color: white;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10001;
            overflow: hidden;
        }
        
        .scrolling-text {
            animation: scroll-left 15s linear infinite;
            white-space: nowrap;
            font-weight: 600;
            font-size: 14px;
        }
        
        @keyframes scroll-left {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        
        .close-sticky-banner {
            position: absolute;
            right: 15px;
            background: none;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            padding: 5px;
        }
        
        /* Inquiry Section */
        .inquiry-section {
            margin: 30px 0;
            text-align: center;
            padding: 30px;
            background: var(--card-bg);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .inquiry-section h3 {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }
        
        .inquiry-images {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 20px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .inquiry-image-item {
            aspect-ratio: 1;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .inquiry-image-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .inquiry-now-btn {
            background: var(--secondary-color);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin: 0 auto;
        }
        
        .inquiry-now-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }
        
        /* Fixed banner sizes for mobile */
        .banner-container {
            height: 150px;
        }
        
        @media (max-width: 768px) {
            .banner-container {
                height: 120px;
            }
            
            .inquiry-images {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 480px) {
            .banner-container {
                height: 100px;
            }
            
            .scrolling-text {
                font-size: 12px;
            }
        }
        
        /* Improved cart modal */
        .cart-modal .cart-content {
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .cart-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .cart-item:last-child {
            border-bottom: none;
        }
        
        .cart-item img {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            object-fit: cover;
        }
        
        .cart-item-info {
            flex: 1;
        }
        
        .cart-item-title {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .cart-item-price {
            color: var(--accent-color);
            font-weight: 600;
        }
        
        /* Product grid improvements */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }
        
        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }
        }
        
        @media (max-width: 480px) {
            .products-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
        
        /* Discount popup styles */
        .discount-popup {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, #ff6b6b, #feca57);
            color: white;
            padding: 12px;
            text-align: center;
            z-index: 10000;
            display: none;
            animation: slideDown 0.5s ease-out;
        }
        
        .discount-popup.show {
            display: block;
        }
        
        @keyframes slideDown {
            from { transform: translateY(-100%); }
            to { transform: translateY(0); }
        }
        
        /* PWA install prompt */
        .pwa-install-prompt {
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 15px;
            z-index: 9999;
            display: none;
            animation: slideUp 0.5s ease-out;
        }
        
        .pwa-install-prompt.show {
            display: block;
        }
        
        @keyframes slideUp {
            from { transform: translateY(100%); }
            to { transform: translateY(0); }
        }
    </style>
</head>
<body>
    <!-- Discount Popup -->
    <div id="discountPopup" class="discount-popup">
        <div class="discount-content">
            <button class="close-popup" onclick="closeDiscountPopup()" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; color: white; font-size: 18px; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
            <div class="discount-text">
                <i class="fas fa-gift"></i>
                <span><?php echo htmlspecialchars($settings['discount_text'] ?? 'DISCOUNT UPTO 50% Live Use FREE code'); ?></span>
            </div>
        </div>
    </div>

    <!-- PWA Install Prompt -->
    <div id="pwaInstallPrompt" class="pwa-install-prompt">
        <div class="pwa-content">
            <button class="close-pwa" onclick="closePWAPrompt()" style="position: absolute; right: 10px; top: 5px; background: none; border: none; color: var(--text-color); font-size: 16px; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
            <div class="pwa-text" style="display: flex; align-items: center; justify-content: center; gap: 10px; font-size: 14px;">
                <i class="fas fa-mobile-alt"></i>
                <span>Add this website to your home screen for quick access!</span>
                <button class="install-btn" onclick="installPWA()" style="background: var(--primary-color); color: white; border: none; padding: 8px 16px; border-radius: 20px; font-size: 12px; cursor: pointer; margin-left: 10px;">Install</button>
            </div>
        </div>
    </div>
    
    <!-- Sticky Top Banner with Scrolling Text -->
    <div class="sticky-banner" id="stickyBanner">
        <div class="scrolling-text">
            <span><?php echo htmlspecialchars($settings['discount_text'] ?? 'DISCOUNT UPTO 50% Live Use FREE code'); ?></span>
        </div>
        <button class="close-sticky-banner" onclick="closeStickyBanner()">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Auto-scrolling Top Banner -->
    <div class="banner-container" id="topBanner" style="margin-top: 50px;">
        <div class="banner-slider">
            <?php foreach ($banners as $banner): ?>
                <div class="banner-slide">
                    <img src="<?php echo htmlspecialchars($banner['image_url']); ?>" 
                         alt="<?php echo htmlspecialchars($banner['title'] ?? 'Banner'); ?>"
                         onclick="<?php echo $banner['link_url'] ? "window.open('" . htmlspecialchars($banner['link_url']) . "', '_blank')" : ''; ?>">
                </div>
            <?php endforeach; ?>
        </div>
        <div class="view-counter">
            <i class="fas fa-eye"></i>
            <span><?php echo number_format($viewCount); ?></span>
        </div>
    </div>

    <!-- Main Container -->
    <div class="container">
        <!-- Header Section -->
        <header class="header-section">
            <!-- Logo -->
            <div class="logo-container">
                <div class="logo">
                    <?php if (!empty($settings['director_image_url'])): ?>
                        <img src="<?php echo htmlspecialchars($settings['director_image_url']); ?>" alt="Director Photo">
                    <?php else: ?>
                        <div class="logo-placeholder">🏢</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Company Title & Director -->
            <div class="title-section">
                <h1 class="company-title"><?php echo htmlspecialchars($settings['company_name'] ?? 'DEMO CARD'); ?></h1>
                <h2 class="director-name"><?php echo htmlspecialchars($settings['director_name'] ?? 'Vishal Rathod'); ?></h2>
                <p class="director-title"><?php echo htmlspecialchars($settings['director_title'] ?? 'FOUNDER'); ?></p>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button class="action-btn call-btn" onclick="window.open('tel:+91<?php echo $settings['contact_phone1']; ?>')">
                    <i class="fas fa-phone"></i>
                    <span>Call</span>
                </button>
                <button class="action-btn whatsapp-btn" onclick="openWhatsApp('<?php echo $settings['whatsapp_number']; ?>')">
                    <i class="fab fa-whatsapp"></i>
                    <span>WhatsApp</span>
                </button>
                <button class="action-btn direction-btn" onclick="window.open('https://maps.google.com/?q=<?php echo urlencode($settings['contact_address']); ?>')">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Direction</span>
                </button>
                <button class="action-btn mail-btn" onclick="window.open('mailto:<?php echo $settings['contact_email']; ?>')">
                    <i class="fas fa-envelope"></i>
                    <span>Mail</span>
                </button>
                <button class="action-btn website-btn" onclick="window.open('<?php echo $settings['website_url']; ?>')">
                    <i class="fas fa-globe"></i>
                    <span>Website</span>
                </button>
            </div>

            <!-- Free Website Offer Button -->
            <div class="free-website-offer">
                <button class="free-website-btn" onclick="showFreeWebsiteForm()">
                    <i class="fas fa-gift"></i>
                    <span>GET YOUR BUSINESS WEBSITE FREE</span>
                </button>
            </div>
            
            <!-- Inquiry Section -->
            <div class="inquiry-section">
                <h3>Product Inquiries</h3>
                <div class="inquiry-images">
                    <?php foreach (array_slice($products, 0, 4) as $product): ?>
                        <div class="inquiry-image-item">
                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($product['title']); ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
                <button class="inquiry-now-btn" onclick="openWhatsApp('<?php echo $settings['whatsapp_number']; ?>', 'Hi! I would like to inquire about your products.')">
                    <i class="fab fa-whatsapp"></i>
                    INQUIRE NOW
                </button>
            </div>

            <!-- User Registration/Login -->
            <div class="user-auth-section">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="user-welcome">
                        <span>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span>
                        <button class="auth-btn" onclick="showUserDashboard()">
                            <i class="fas fa-user"></i>
                            My Account
                        </button>
                        <button class="auth-btn" onclick="userLogout()">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </button>
                    </div>
                <?php else: ?>
                    <div class="auth-buttons">
                        <button class="auth-btn" onclick="showLoginForm()">
                            <i class="fas fa-sign-in-alt"></i>
                            Login
                        </button>
                        <button class="auth-btn" onclick="showRegisterForm()">
                            <i class="fas fa-user-plus"></i>
                            Register
                        </button>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Contact Details -->
            <div class="contact-details">
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <span><?php echo htmlspecialchars($settings['contact_phone1']); ?></span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <span><?php echo htmlspecialchars($settings['contact_phone2']); ?></span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <span><?php echo htmlspecialchars($settings['contact_email']); ?></span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span><?php echo htmlspecialchars($settings['contact_address']); ?></span>
                </div>
            </div>

            <!-- Share Section -->
            <div class="share-section">
                <div class="whatsapp-share">
                    <input type="text" id="countryCode" placeholder="+91" value="+91">
                    <button class="whatsapp-share-btn" onclick="shareOnWhatsApp()">
                        <i class="fab fa-whatsapp"></i>
                        Share on WhatsApp
                    </button>
                </div>

                <div class="action-row">
                    <button class="action-btn-secondary" onclick="downloadVCF()">
                        <i class="fas fa-download"></i>
                        Save to Contacts
                    </button>
                    <button class="action-btn-secondary" onclick="shareCard()">
                        <i class="fas fa-share"></i>
                        Share
                    </button>
                    <button class="action-btn-secondary" onclick="savePDF()">
                        <i class="fas fa-file-pdf"></i>
                        Save PDF
                    </button>
                </div>
            </div>

            <!-- Social Media Icons -->
            <div class="social-media">
                <?php
                $socialMedia = [
                    'facebook' => ['icon' => 'fab fa-facebook-f', 'color' => '#1877f2'],
                    'youtube' => ['icon' => 'fab fa-youtube', 'color' => '#ff0000'],
                    'twitter' => ['icon' => 'fab fa-twitter', 'color' => '#1da1f2'],
                    'instagram' => ['icon' => 'fab fa-instagram', 'color' => '#e4405f'],
                    'linkedin' => ['icon' => 'fab fa-linkedin-in', 'color' => '#0077b5'],
                    'pinterest' => ['icon' => 'fab fa-pinterest', 'color' => '#bd081c'],
                    'telegram' => ['icon' => 'fab fa-telegram', 'color' => '#0088cc'],
                    'zomato' => ['icon' => 'fas fa-utensils', 'color' => '#e23744']
                ];
                
                foreach ($socialMedia as $platform => $data):
                    $url = $settings['social_' . $platform] ?? '#';
                    if ($url !== '#'):
                ?>
                    <a href="<?php echo htmlspecialchars($url); ?>" target="_blank" class="social-icon" style="background: <?php echo $data['color']; ?>">
                        <i class="<?php echo $data['icon']; ?>"></i>
                    </a>
                <?php 
                    endif;
                endforeach; 
                ?>
            </div>

            <!-- Theme Selector -->
            <div class="theme-selector">
                <h3>Change Theme</h3>
                <div class="theme-options">
                    <?php foreach ($themes as $themeId => $themeData): ?>
                        <button class="theme-btn <?php echo $currentTheme === $themeId ? 'active' : ''; ?>" 
                                onclick="changeTheme('<?php echo $themeId; ?>')"
                                style="background: <?php echo $themeData['background']; ?>; color: <?php echo $themeData['text']; ?>;">
                            <?php echo $themeData['name']; ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </header>

        <!-- PDF Downloads -->
        <section class="pdf-section">
            <h3>Download Resources</h3>
            <div class="pdf-buttons">
                <?php foreach ($pdfs as $pdf): ?>
                    <button class="pdf-btn" onclick="downloadPDF('<?php echo htmlspecialchars($pdf['file_url']); ?>')">
                        <i class="fas fa-download"></i>
                        <?php echo htmlspecialchars($pdf['title']); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Products Section -->
        <section class="products-section">
            <h3>Our Products</h3>
            <div class="products-grid" id="productsGrid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <div class="product-image-container">
                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($product['title']); ?>"
                                 class="product-image">
                        </div>
                        <div class="product-info">
                            <h4 class="product-title"><?php echo htmlspecialchars($product['title']); ?></h4>
                            <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                            <div class="product-price">
                                <?php if ($product['discount_price']): ?>
                                    <span class="original-price">₹<?php echo number_format($product['price']); ?></span>
                                    <span class="discount-price">₹<?php echo number_format($product['discount_price']); ?></span>
                                <?php else: ?>
                                    <span class="price">₹<?php echo number_format($product['price']); ?></span>
                                <?php endif; ?>
                                <button class="whatsapp-inquiry" onclick="inquireProductWhatsApp('<?php echo htmlspecialchars($product['title']); ?>')">
                                    <i class="fab fa-whatsapp"></i>
                                </button>
                            </div>
                            <div class="product-actions">
                                <?php if (!$product['inquiry_only']): ?>
                                    <button class="add-to-cart-btn" onclick="addToCart(<?php echo $product['id']; ?>)">
                                        <i class="fas fa-shopping-cart"></i>
                                        ADD TO CART
                                    </button>
                                    <button class="inquiry-btn secondary" onclick="addToInquiry(<?php echo $product['id']; ?>)">
                                        <i class="fas fa-question-circle"></i>
                                        INQUIRE
                                    </button>
                                <?php else: ?>
                                    <button class="inquiry-btn" onclick="addToInquiry(<?php echo $product['id']; ?>)">
                                        <i class="fas fa-question-circle"></i>
                                        INQUIRE NOW
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Gallery Section -->
        <section class="gallery-section">
            <h3>Photo Gallery</h3>
            <div class="gallery-grid">
                <?php foreach ($gallery as $image): ?>
                    <div class="gallery-item" onclick="openLightbox('<?php echo htmlspecialchars($image['image_url']); ?>')">
                        <img src="<?php echo htmlspecialchars($image['thumbnail_url'] ?: $image['image_url']); ?>" 
                             alt="<?php echo htmlspecialchars($image['alt_text'] ?: $image['title']); ?>">
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- YouTube Videos -->
        <section class="videos-section">
            <h3>Video Gallery</h3>
            <div class="videos-grid">
                <?php foreach ($videos as $video): ?>
                    <div class="video-container">
                        <iframe src="<?php echo htmlspecialchars($video['embed_code']); ?>" 
                                title="<?php echo htmlspecialchars($video['title']); ?>"
                                frameborder="0" 
                                allowfullscreen></iframe>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Reviews Section -->
        <section class="reviews-section">
            <h3>Customer Reviews</h3>
            <div class="reviews-grid">
                <?php foreach ($reviews as $review): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <div class="stars">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?php echo $i <= $review['rating'] ? 'active' : ''; ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <span class="reviewer-name"><?php echo htmlspecialchars($review['name']); ?></span>
                        </div>
                        <p class="review-comment"><?php echo htmlspecialchars($review['comment']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Add Review Form -->
            <div class="add-review-form">
                <h4>Add Your Review</h4>
                <form id="reviewForm" onsubmit="submitReview(event)">
                    <div class="rating-input">
                        <div class="stars-input" id="starsInput">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star" data-rating="<?php echo $i; ?>" onclick="setRating(<?php echo $i; ?>)"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <input type="hidden" id="rating" name="rating" value="0">
                    <input type="text" name="name" placeholder="Your Name" required>
                    <input type="email" name="email" placeholder="Your Email" required>
                    <input type="tel" name="phone" placeholder="Your Phone">
                    <textarea name="comment" placeholder="Your Review" rows="4" required></textarea>
                    <button type="submit">Submit Review</button>
                </form>
            </div>
        </section>
    </div>

    <!-- Auto-scrolling Bottom Banner -->
    <div class="banner-container" id="bottomBanner">
        <div class="banner-slider">
            <?php foreach ($banners as $banner): ?>
                <div class="banner-slide">
                    <img src="<?php echo htmlspecialchars($banner['image_url']); ?>" 
                         alt="<?php echo htmlspecialchars($banner['title'] ?? 'Banner'); ?>"
                         onclick="<?php echo $banner['link_url'] ? "window.open('" . htmlspecialchars($banner['link_url']) . "', '_blank')" : ''; ?>">
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <nav class="bottom-nav">
        <a href="#" class="nav-item active">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="#" class="nav-item">
            <i class="fas fa-info-circle"></i>
            <span>About</span>
        </a>
        <a href="#" class="nav-item">
            <i class="fas fa-cogs"></i>
            <span>Services</span>
        </a>
        <a href="#" class="nav-item">
            <i class="fas fa-shopping-cart"></i>
            <span>Shop</span>
        </a>
        <a href="#" class="nav-item">
            <i class="fas fa-images"></i>
            <span>Gallery</span>
        </a>
        <a href="#" class="nav-item">
            <i class="fab fa-youtube"></i>
            <span>Videos</span>
        </a>
        <a href="#" class="nav-item">
            <i class="fas fa-credit-card"></i>
            <span>Payment</span>
        </a>
        <a href="#" class="nav-item">
            <i class="fas fa-star"></i>
            <span>Reviews</span>
        </a>
        <a href="#" class="nav-item">
            <i class="fas fa-phone"></i>
            <span>Contact</span>
        </a>
    </nav>

    <!-- Floating Cart Button -->
    <div class="floating-cart" id="floatingCart" onclick="toggleCart()">
        <i class="fas fa-shopping-cart"></i>
        <span class="cart-count" id="cartCount">0</span>
    </div>

    <!-- Floating Inquiry Button -->
    <div class="floating-inquiry" id="floatingInquiry" onclick="toggleInquiry()">
        <i class="fas fa-question-circle"></i>
        <span class="inquiry-count" id="inquiryCount">0</span>
    </div>

    <!-- Cart Modal -->
    <div class="cart-modal" id="cartModal">
        <div class="cart-content">
            <div class="cart-header">
                <h3>Shopping Cart</h3>
                <button class="close-cart" onclick="toggleCart()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="cart-items" id="cartItems">
                <!-- Cart items will be populated by JavaScript -->
            </div>
            <div class="cart-footer">
                <div class="cart-total">
                    <span>Total: ₹<span id="cartTotal">0</span></span>
                </div>
                <button class="checkout-btn" onclick="checkout()">PAY NOW via UPI</button>
            </div>
        </div>
    </div>

    <!-- Inquiry Modal -->
    <div class="inquiry-modal" id="inquiryModal">
        <div class="inquiry-content">
            <div class="inquiry-header">
                <h3>Product Inquiries</h3>
                <button class="close-inquiry" onclick="toggleInquiry()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="inquiry-items" id="inquiryItems">
                <!-- Inquiry items will be populated by JavaScript -->
            </div>
            <div class="inquiry-footer">
                <button class="send-inquiry-btn" onclick="sendInquiry()">Send Inquiry via WhatsApp</button>
            </div>
        </div>
    </div>

    <!-- UPI Payment Modal -->
    <div class="upi-modal" id="upiModal">
        <div class="upi-content">
            <div class="upi-header">
                <h3>UPI Payment</h3>
                <button class="close-upi" onclick="closeUPIModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="upi-body">
                <div class="upi-qr-container">
                    <div id="upiQRCode"></div>
                </div>
                <div class="upi-details">
                    <p><strong>UPI ID:</strong> <span id="upiId"><?php echo htmlspecialchars($settings['upi_id'] ?? 'demo@upi'); ?></span></p>
                    <p><strong>Amount:</strong> ₹<span id="upiAmount">0</span></p>
                    <p><strong>Merchant:</strong> <?php echo htmlspecialchars($settings['company_name'] ?? 'DEMO CARD'); ?></p>
                </div>
                <div class="upi-actions">
                    <button class="pay-now-btn" onclick="openUPIApp()">
                        <i class="fas fa-mobile-alt"></i>
                        Pay with UPI App
                    </button>
                    <button class="payment-done-btn" onclick="confirmPayment()">
                        <i class="fas fa-check"></i>
                        I have paid - Register to confirm order
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- User Auth Modals -->
    <div class="auth-modal" id="loginModal">
        <div class="auth-content">
            <div class="auth-header">
                <h3>Login</h3>
                <button class="close-auth" onclick="closeAuthModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="loginForm" onsubmit="userLogin(event)">
                <input type="text" name="username" placeholder="Username or Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
            <p>Don't have an account? <a href="#" onclick="showRegisterForm()">Register here</a></p>
        </div>
    </div>

    <div class="auth-modal" id="registerModal">
        <div class="auth-content">
            <div class="auth-header">
                <h3>Register</h3>
                <button class="close-auth" onclick="closeAuthModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="registerForm" onsubmit="userRegister(event)">
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="tel" name="phone" placeholder="Mobile Number" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <button type="submit">Register</button>
            </form>
            <p>Already have an account? <a href="#" onclick="showLoginForm()">Login here</a></p>
        </div>
    </div>

    <!-- Free Website Form Modal -->
    <div class="free-website-modal" id="freeWebsiteModal">
        <div class="free-website-content">
            <div class="free-website-header">
                <h3>Get Your Business Website FREE</h3>
                <button class="close-free-website" onclick="closeFreeWebsiteForm()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="freeWebsiteForm" onsubmit="submitFreeWebsiteRequest(event)">
                <input type="text" name="name" placeholder="Your Name" required>
                <input type="tel" name="mobile" placeholder="Mobile Number" required>
                <input type="email" name="email" placeholder="Email Address">
                <textarea name="business_details" placeholder="Tell us about your business" rows="4"></textarea>
                <button type="submit">Submit Request</button>
            </form>
        </div>
    </div>

    <!-- User Dashboard Modal -->
    <div class="user-dashboard-modal" id="userDashboardModal">
        <div class="user-dashboard-content">
            <div class="user-dashboard-header">
                <h3>My Account</h3>
                <button class="close-user-dashboard" onclick="closeUserDashboard()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="user-dashboard-body">
                <div class="dashboard-tabs">
                    <button class="tab-btn active" onclick="showTab('profile')">Profile</button>
                    <button class="tab-btn" onclick="showTab('orders')">Orders</button>
                    <button class="tab-btn" onclick="showTab('inquiries')">Inquiries</button>
                </div>
                <div class="dashboard-content">
                    <div id="profileTab" class="tab-content active">
                        <!-- Profile content will be loaded here -->
            <form id="reviewForm" onsubmit="submitReview(event)" method="POST">
                    <div id="ordersTab" class="tab-content">
                        <!-- Orders content will be loaded here -->
                    </div>
                    <div id="inquiriesTab" class="tab-content">
                        <!-- Inquiries content will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lightbox for Gallery -->
    <div class="lightbox" id="lightbox" onclick="closeLightbox()">
        <img src="" alt="" id="lightboxImage">
        <button class="lightbox-close" onclick="closeLightbox()">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 <?php echo htmlspecialchars($settings['company_name'] ?? 'DEMO CARD'); ?>. All rights reserved.</p>
        <p class="made-by">Made with ❤️ for digital business</p>
    </footer>

    <!-- JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode/1.5.3/qrcode.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        // Initialize banner auto-scroll
        initializeBanners();
        
        // Initialize cart and inquiry from localStorage
        loadCart();
        loadInquiry();
        
        // Show discount popup after 3 seconds if enabled
        <?php if (($settings['show_discount_popup'] ?? '1') === '1'): ?>
        setTimeout(showDiscountPopup, 3000);
        <?php endif; ?>
        
        // Show PWA install prompt after 5 seconds if enabled
        <?php if (($settings['show_pwa_prompt'] ?? '1') === '1'): ?>
        setTimeout(showPWAPrompt, 5000);
        <?php endif; ?>
        
        // Close sticky banner function
        function closeStickyBanner() {
            document.getElementById('stickyBanner').style.display = 'none';
            document.getElementById('topBanner').style.marginTop = '0';
        }
    </script>
</body>
</html>