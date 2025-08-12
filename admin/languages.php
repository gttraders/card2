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

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add_translation') {
        try {
            $stmt = $pdo->prepare("INSERT INTO translations (language_code, translation_key, translation_value) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE translation_value = ?");
            $stmt->execute([
                $_POST['language_code'],
                $_POST['translation_key'],
                $_POST['translation_value'],
                $_POST['translation_value']
            ]);
            
            $message = 'Translation added successfully!';
            $messageType = 'success';
        } catch (PDOException $e) {
            $message = 'Error adding translation';
            $messageType = 'error';
        }
    }
    
    if ($action === 'delete_translation') {
        $translationId = intval($_POST['translation_id']);
        
        try {
            $stmt = $pdo->prepare("DELETE FROM translations WHERE id = ?");
            if ($stmt->execute([$translationId])) {
                $message = 'Translation deleted successfully!';
                $messageType = 'success';
            } else {
                $message = 'Error deleting translation';
                $messageType = 'error';
            }
        } catch (PDOException $e) {
            $message = 'Error deleting translation';
            $messageType = 'error';
        }
    }
}

// Get all translations
$stmt = $pdo->query("SELECT * FROM translations ORDER BY language_code, translation_key");
$translations = $stmt->fetchAll();

// Group translations by language
$translationsByLang = [];
foreach ($translations as $translation) {
    $translationsByLang[$translation['language_code']][] = $translation;
}

// Supported languages
$languages = [
    'en' => 'English',
    'hi' => 'हिन्दी (Hindi)',
    'mr' => 'मराठी (Marathi)',
    'gu' => 'ગુજરાતી (Gujarati)',
    'ta' => 'தமிழ் (Tamil)',
    'te' => 'తెలుగు (Telugu)',
    'kn' => 'ಕನ್ನಡ (Kannada)',
    'ml' => 'മലയാളം (Malayalam)',
    'bn' => 'বাংলা (Bengali)',
    'pa' => 'ਪੰਜਾਬੀ (Punjabi)',
    'or' => 'ଓଡ଼ିଆ (Odia)',
    'as' => 'অসমীয়া (Assamese)',
    'ur' => 'اردو (Urdu)',
    'sa' => 'संस्कृत (Sanskrit)',
    'ne' => 'नेपाली (Nepali)',
    'si' => 'සිංහල (Sinhala)',
    'my' => 'မြန်မာ (Myanmar)',
    'th' => 'ไทย (Thai)',
    'vi' => 'Tiếng Việt (Vietnamese)',
    'id' => 'Bahasa Indonesia',
    'ms' => 'Bahasa Melayu',
    'zh' => '中文 (Chinese)',
    'ja' => '日本語 (Japanese)',
    'ko' => '한국어 (Korean)',
    'ar' => 'العربية (Arabic)',
    'fa' => 'فارسی (Persian)',
    'tr' => 'Türkçe (Turkish)',
    'ru' => 'Русский (Russian)',
    'de' => 'Deutsch (German)',
    'fr' => 'Français (French)',
    'es' => 'Español (Spanish)',
    'pt' => 'Português (Portuguese)',
    'it' => 'Italiano (Italian)',
    'nl' => 'Nederlands (Dutch)',
    'sv' => 'Svenska (Swedish)',
    'no' => 'Norsk (Norwegian)',
    'da' => 'Dansk (Danish)',
    'fi' => 'Suomi (Finnish)',
    'pl' => 'Polski (Polish)',
    'cs' => 'Čeština (Czech)',
    'sk' => 'Slovenčina (Slovak)',
    'hu' => 'Magyar (Hungarian)',
    'ro' => 'Română (Romanian)',
    'bg' => 'Български (Bulgarian)',
    'hr' => 'Hrvatski (Croatian)',
    'sr' => 'Српски (Serbian)',
    'sl' => 'Slovenščina (Slovenian)',
    'et' => 'Eesti (Estonian)',
    'lv' => 'Latviešu (Latvian)',
    'lt' => 'Lietuvių (Lithuanian)',
    'mt' => 'Malti (Maltese)',
    'ga' => 'Gaeilge (Irish)',
    'cy' => 'Cymraeg (Welsh)',
    'eu' => 'Euskera (Basque)',
    'ca' => 'Català (Catalan)',
    'gl' => 'Galego (Galician)'
];

// Common translation keys
$commonKeys = [
    'welcome' => 'Welcome',
    'home' => 'Home',
    'about' => 'About Us',
    'products' => 'Products',
    'services' => 'Services',
    'contact' => 'Contact',
    'gallery' => 'Gallery',
    'videos' => 'Videos',
    'reviews' => 'Reviews',
    'cart' => 'Cart',
    'add_to_cart' => 'Add to Cart',
    'buy_now' => 'Buy Now',
    'inquiry' => 'Inquiry',
    'call_now' => 'Call Now',
    'whatsapp' => 'WhatsApp',
    'email' => 'Email',
    'website' => 'Website',
    'address' => 'Address',
    'phone' => 'Phone',
    'download' => 'Download',
    'share' => 'Share',
    'save_contact' => 'Save Contact',
    'get_directions' => 'Get Directions',
    'view_more' => 'View More',
    'read_more' => 'Read More',
    'submit' => 'Submit',
    'send' => 'Send',
    'cancel' => 'Cancel',
    'close' => 'Close',
    'search' => 'Search',
    'filter' => 'Filter',
    'sort' => 'Sort',
    'price' => 'Price',
    'discount' => 'Discount',
    'offer' => 'Offer',
    'sale' => 'Sale',
    'new' => 'New',
    'popular' => 'Popular',
    'featured' => 'Featured',
    'rating' => 'Rating',
    'review' => 'Review',
    'customer' => 'Customer',
    'order' => 'Order',
    'payment' => 'Payment',
    'shipping' => 'Shipping',
    'delivery' => 'Delivery',
    'return' => 'Return',
    'refund' => 'Refund',
    'support' => 'Support',
    'help' => 'Help',
    'faq' => 'FAQ',
    'terms' => 'Terms & Conditions',
    'privacy' => 'Privacy Policy',
    'copyright' => 'Copyright',
    'all_rights_reserved' => 'All Rights Reserved'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Languages - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar.php'; ?>
    
    <main class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-language"></i> Multi-Language Support</h1>
            <button class="btn btn-primary" onclick="showAddTranslationModal()">
                <i class="fas fa-plus"></i> Add Translation
            </button>
        </div>
        
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Supported Languages (<?php echo count($languages); ?> languages)</h3>
                <p>Add translations for different languages to make your website multilingual</p>
            </div>
            <div class="card-content">
                <div class="languages-tabs">
                    <?php foreach ($languages as $code => $name): ?>
                        <button class="lang-tab <?php echo $code === 'en' ? 'active' : ''; ?>" 
                                onclick="showLanguage('<?php echo $code; ?>')" 
                                data-lang="<?php echo $code; ?>">
                            <?php echo htmlspecialchars($name); ?>
                            <span class="translation-count">
                                (<?php echo count($translationsByLang[$code] ?? []); ?>)
                            </span>
                        </button>
                    <?php endforeach; ?>
                </div>
                
                <div class="translations-content">
                    <?php foreach ($languages as $code => $name): ?>
                        <div class="lang-content <?php echo $code === 'en' ? 'active' : ''; ?>" id="lang-<?php echo $code; ?>">
                            <h4><?php echo htmlspecialchars($name); ?> Translations</h4>
                            
                            <?php if (isset($translationsByLang[$code])): ?>
                                <div class="translations-table">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Key</th>
                                                <th>Translation</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($translationsByLang[$code] as $translation): ?>
                                            <tr>
                                                <td><code><?php echo htmlspecialchars($translation['translation_key']); ?></code></td>
                                                <td><?php echo htmlspecialchars($translation['translation_value']); ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-danger" onclick="deleteTranslation(<?php echo $translation['id']; ?>)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="no-translations">No translations available for this language.</p>
                            <?php endif; ?>
                            
                            <button class="btn btn-secondary btn-sm" onclick="showAddTranslationModal('<?php echo $code; ?>')">
                                <i class="fas fa-plus"></i> Add Translation for <?php echo htmlspecialchars($name); ?>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Quick Setup</h3>
            </div>
            <div class="card-content">
                <p>Use these buttons to quickly add common translations for popular languages:</p>
                <div class="quick-setup-buttons">
                    <button class="btn btn-secondary" onclick="addCommonTranslations('hi')">
                        <i class="fas fa-magic"></i> Add Hindi Translations
                    </button>
                    <button class="btn btn-secondary" onclick="addCommonTranslations('mr')">
                        <i class="fas fa-magic"></i> Add Marathi Translations
                    </button>
                    <button class="btn btn-secondary" onclick="addCommonTranslations('gu')">
                        <i class="fas fa-magic"></i> Add Gujarati Translations
                    </button>
                    <button class="btn btn-secondary" onclick="addCommonTranslations('ta')">
                        <i class="fas fa-magic"></i> Add Tamil Translations
                    </button>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Add Translation Modal -->
    <div id="addTranslationModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add Translation</h3>
                <button class="close-modal" onclick="closeModal('addTranslationModal')">&times;</button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="add_translation">
                
                <div class="form-group">
                    <label>Language</label>
                    <select name="language_code" id="languageSelect" required>
                        <?php foreach ($languages as $code => $name): ?>
                            <option value="<?php echo $code; ?>"><?php echo htmlspecialchars($name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Translation Key</label>
                    <select name="translation_key" id="keySelect" required>
                        <option value="">Select a key or type custom</option>
                        <?php foreach ($commonKeys as $key => $defaultValue): ?>
                            <option value="<?php echo $key; ?>"><?php echo $key; ?> (<?php echo $defaultValue; ?>)</option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" id="customKey" placeholder="Or enter custom key" style="margin-top: 10px; display: none;">
                </div>
                
                <div class="form-group">
                    <label>Translation Value</label>
                    <input type="text" name="translation_value" id="translationValue" required>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Add Translation</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addTranslationModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function showLanguage(langCode) {
            // Hide all language contents
            document.querySelectorAll('.lang-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Remove active class from all tabs
            document.querySelectorAll('.lang-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Show selected language content
            document.getElementById('lang-' + langCode).classList.add('active');
            
            // Add active class to selected tab
            document.querySelector('[data-lang="' + langCode + '"]').classList.add('active');
        }
        
        function showAddTranslationModal(langCode = '') {
            if (langCode) {
                document.getElementById('languageSelect').value = langCode;
            }
            document.getElementById('addTranslationModal').style.display = 'block';
        }
        
        function deleteTranslation(translationId) {
            if (confirm('Are you sure you want to delete this translation?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete_translation">
                    <input type="hidden" name="translation_id" value="${translationId}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        function addCommonTranslations(langCode) {
            // This would add common translations for the selected language
            // For now, just redirect to add translation modal
            showAddTranslationModal(langCode);
            alert('Please add translations manually for now. Auto-translation feature coming soon!');
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
        
        // Handle custom key input
        document.getElementById('keySelect').addEventListener('change', function() {
            const customKeyInput = document.getElementById('customKey');
            const translationValue = document.getElementById('translationValue');
            
            if (this.value === '') {
                customKeyInput.style.display = 'block';
                customKeyInput.required = true;
                translationValue.value = '';
            } else {
                customKeyInput.style.display = 'none';
                customKeyInput.required = false;
                
                // Set default value based on selected key
                const commonKeys = <?php echo json_encode($commonKeys); ?>;
                if (commonKeys[this.value]) {
                    translationValue.value = commonKeys[this.value];
                }
            }
        });
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        }
    </script>
    
    <style>
        .languages-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-bottom: 20px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 10px;
        }
        
        .lang-tab {
            padding: 8px 12px;
            border: 1px solid #e5e7eb;
            background: white;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s ease;
        }
        
        .lang-tab:hover {
            background: #f3f4f6;
        }
        
        .lang-tab.active {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }
        
        .translation-count {
            font-size: 10px;
            opacity: 0.7;
        }
        
        .lang-content {
            display: none;
        }
        
        .lang-content.active {
            display: block;
        }
        
        .lang-content h4 {
            margin-bottom: 15px;
            font-size: 18px;
            font-weight: 600;
        }
        
        .translations-table {
            margin-bottom: 20px;
        }
        
        .no-translations {
            text-align: center;
            color: #6b7280;
            font-style: italic;
            margin: 40px 0;
        }
        
        .quick-setup-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        code {
            background: #f3f4f6;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
        }
    </style>
</body>
</html>