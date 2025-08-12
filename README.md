# Complete PHP Commercial Mini-Store / Visiting Card Microsite

A complete professional visiting card and e-commerce microsite solution built with HTML5, PHP, CSS, and MySQL. Features a comprehensive admin panel, multi-theme support, and all business functionality ready for deployment to Hostinger or any PHP hosting provider.

## 🌟 Features

### 🎨 Frontend Features
- **Professional Design**: 4 stunning theme variations with smooth animations
- **Digital Visiting Card**: Complete business card with contact details and QR code
- **E-commerce Shop**: Full product catalog with shopping cart and UPI payment integration
- **Auto-scrolling Banners**: Top and bottom promotional banners (auto-scroll every 2 seconds)
- **YouTube Gallery**: Responsive embedded video content
- **Rating System**: Customer reviews with admin approval workflow
- **Photo Gallery**: Up to 20 images with lightbox view
- **Mobile-First Design**: Fully responsive with bottom navigation
- **Social Integration**: WhatsApp sharing, social media links
- **Contact Features**: VCF download, PDF resources, share functionality
- **Multi-language Support**: Admin-managed translations
- **SEO Optimized**: Meta tags, structured data, clean URLs

### 🛠️ Admin Panel Features
- **Comprehensive Dashboard**: Revenue tracking, order analytics, real-time stats
- **Product Management**: Full CRUD operations with image uploads
- **Order Management**: View, update status, export orders (CSV/TXT)
- **Content Management**: Banners, videos, PDFs, gallery images
- **Theme Management**: Switch between 4 professional themes instantly
- **Review Management**: Approve/reject customer feedback
- **Gallery Management**: Upload and manage up to 20 photos
- **Settings Management**: Site configuration, contact details, SEO settings
- **Analytics**: Revenue charts, visitor tracking, performance metrics
- **Backup System**: Database backup and restore functionality
- **Mobile Admin**: Responsive admin panel for mobile management

## 🚀 Quick Start

### Requirements
- PHP 7.4+ (Hostinger compatible)
- MySQL 5.7+
- Web server (Apache/Nginx)
- Modern web browser for admin panel

### Installation Steps

#### Step 1: Upload Files
   ```bash
   # Upload all files to your hosting directory (public_html/)
   # Ensure PHP 7.4+ and MySQL are available
   ```

#### Step 2: Database Setup
   - Create a MySQL database
   - Import the SQL file:
   ```sql
   -- Create database (replace with your database name)
   CREATE DATABASE microsite_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   
   -- Import the SQL file
   mysql -u username -p microsite_db < supabase/migrations/20250811064438_small_fountain.sql
   ```

#### Step 3: Configuration
   - Copy `.env.example` to `.env`
   - Update database credentials:
   ```env
   DB_HOST=localhost
   DB_NAME=microsite_db
   DB_USER=your_username
   DB_PASS=your_password
   UPI_ID=your-upi-id@bank
   ADMIN_SECRET=your-secure-secret
   ```

#### Step 4: File Permissions
   ```bash
   chmod 755 admin/
   chmod 777 uploads/
   chmod 777 assets/
   ```

#### Step 5: Access Your Site
   - **Main Website**: `https://yourdomain.com/`
   - **Admin Panel**: `https://yourdomain.com/admin/`
   - **Default Login**: `admin` / `admin123`

## 📁 Project Structure

```
php-microsite/
├── index.php                     # Main website
├── assets/
│   ├── css/
│   │   ├── style.css            # Main styles
│   │   ├── responsive.css       # Mobile responsive
│   │   └── admin.css            # Admin panel styles
│   └── js/
│       └── main.js              # Frontend JavaScript
├── includes/
│   ├── config.php               # Database configuration
│   └── functions.php            # Core functions
├── admin/
│   ├── index.php                # Admin login
│   ├── dashboard.php            # Admin dashboard
│   ├── products.php             # Product management
│   ├── orders.php               # Order management
│   ├── reviews.php              # Review management
│   ├── gallery.php              # Gallery management
│   ├── settings.php             # Site settings
│   └── includes/
│       ├── header.php           # Admin header
│       └── sidebar.php          # Admin sidebar
├── api/
│   ├── get-product.php          # Product API
│   ├── submit-review.php        # Review submission
│   ├── create-order.php         # Order creation
│   ├── generate-vcf.php         # VCF contact file
│   └── change-theme.php         # Theme switching
├── supabase/migrations/
│   └── 20250811064438_small_fountain.sql  # Complete database
├── uploads/                     # File uploads directory
├── .env.example                 # Environment configuration
└── README.md
```

## 🎨 Professional Theme System

The microsite includes 4 carefully designed professional themes:

1. **Professional Blue**: Corporate blue with gradient background - perfect for business
2. **Vibrant Gradient**: Colorful gradient theme - eye-catching and modern
3. **Modern Teal**: Teal and orange combination - fresh and professional
4. **Clean Light**: Light theme with subtle gradients - minimal and elegant

**Features**:
- Instant theme switching from admin panel
- Consistent design across all themes
- Mobile-optimized color schemes
- Automatic contrast adjustment
- Theme-specific animations and effects

## 💳 Payment Integration

### UPI Payment Setup
1. Configure your UPI ID in admin settings
2. QR codes are auto-generated for payments
3. Manual payment confirmation system
4. Order tracking with payment status

### Payment Flow
1. Customer adds products to cart
2. Clicks "PAY NOW via UPI"
3. Opens UPI apps or shows QR code
4. Admin confirms payment manually
5. Order status updated automatically

## 🛠️ Admin Panel Guide

### Login Access
- **Standard Login**: `/admin/` with username/password
- **Default Credentials**: `admin` / `admin123` (change immediately)

### Key Admin Functions

#### Dashboard
- Daily/monthly revenue charts
- Order statistics
- Pending payment notifications
- Quick action buttons
- Interactive revenue charts
#### Product Management
- Add/edit products with images
- Set pricing and discounts
- Manage stock levels
- Inquiry-only products

#### Order Management
- View all orders with filters
- Update payment status
- Export orders to CSV/TXT
- Customer communication

#### Content Management
- Upload banners (auto-rotating)
- Manage YouTube videos
- Handle PDF downloads (5 slots)
- Photo gallery (20 images max)

#### Settings
- Complete site configuration
- UPI payment details
- SEO meta tags
- Theme selection
- Multi-language content

## 🗄️ Database Schema

The database includes 12 comprehensive tables:

### 🔧 Core Tables
- **`admins`** - Admin user accounts with role management
- **`products`** - Complete product catalog with pricing
- **`orders`** - Customer orders with status tracking
- **`order_items`** - Detailed order line items
- **`reviews`** - Customer reviews with approval system
- **`videos`** - YouTube video management
- **`banners`** - Auto-scrolling promotional banners
- **`pdfs`** - 5 downloadable PDF resources
- **`site_settings`** - Complete site configuration
- **`translations`** - Multi-language support
- **`gallery`** - Photo gallery (up to 20 images)
- **`visits`** - Visitor tracking and analytics
- **`transactions`** - UPI payment records

### 📊 Sample Data Included
- 5 sample products with images
- 4 customer reviews
- 3 YouTube videos
- 3 promotional banners
- 5 PDF download buttons
- 4 gallery images
- Complete site settings
- Multi-language translations (English/Hindi)
- Admin user account

## 📱 Mobile Features

### Responsive Design
- Mobile-first approach
- Touch-friendly interface
- Optimized images
- Fast loading

### Mobile Admin Panel
- Fully responsive admin interface
- Touch-optimized controls
- Responsive tables
- Mobile file uploads

## 🔒 Security Features

### Data Protection
- Secure password hashing (PHP password_hash)
- SQL injection prevention
- File upload validation
- XSS prevention
- Input sanitization
- Rate limiting for reviews

### Admin Security
- Session management
- Secure authentication
- Role-based access
- Login attempt monitoring

## 🌐 SEO & Performance

### SEO Features
- Complete meta tags management
- Open Graph tags
- Clean URLs
- Mobile optimization
- Fast loading times
- Sitemap ready

### Performance
- Optimized images
- Efficient CSS/JS
- Database indexing
- Lazy loading support
- Service worker ready

## 🚀 Deployment to Hostinger

### Step-by-Step Deployment

#### 1. **Prepare Hostinger Account**
   - Sign up at [Hostinger](https://hostinger.com)
   - Choose hosting plan with PHP 7.4+ and MySQL
   - Access your hosting control panel

#### 2. **Upload Files**
   ```bash
   # Using Hostinger File Manager or FTP
   # Upload all project files to public_html/
   # Maintain the directory structure
   ```

#### 3. **Database Setup**
   - Go to Hostinger Control Panel → Databases
   - Create new MySQL database
   - Create database user with full privileges
   - Note down: database name, username, password, host

#### 4. **Import Database**
   - Access phpMyAdmin from Hostinger panel
   - Select your database
   - Import `supabase/migrations/20250811064438_small_fountain.sql`
   - Verify all tables are created with sample data

#### 5. **Configure Environment**
   - Rename `.env.example` to `.env`
   - Update database credentials:
   ```env
   DB_HOST=localhost
   DB_NAME=your_database_name
   DB_USER=your_database_user
   DB_PASS=your_database_password
   UPI_ID=your-upi-id@bank
   SITE_URL=https://yourdomain.com
   ```

#### 6. **Set File Permissions**
   ```bash
   # Using Hostinger File Manager
   uploads/ → 755 or 777
   assets/ → 755
   admin/ → 755
   api/ → 755
   ```

#### 7. **Test Everything**
   - Visit `https://yourdomain.com/` (main site)
   - Visit `https://yourdomain.com/admin/` (admin panel)
   - Login with: `admin` / `admin123`
   - Test all features: products, cart, reviews, themes
   - Change admin password immediately

### Domain Setup
```
https://yourdomain.com/              # Main website
https://yourdomain.com/admin/        # Admin panel
https://yourdomain.com/api/          # API endpoints
```

## 🛠️ Customization Guide

### 🎨 Adding New Themes
1. Edit `index.php` themes array
2. Add CSS variables in `assets/css/style.css`
3. Update theme selector in admin panel
4. Test across all devices

### 🔧 Adding New Features
1. **Frontend**: Modify `index.php` and CSS files
2. **Backend**: Add new admin pages in `/admin/`
3. **Database**: Update schema and functions
4. **API**: Add new endpoints in `/api/`

### 🌍 Language Support
1. Add translations in admin panel
2. Update `translations` table in database
3. Modify frontend to use translation keys
4. Test with different languages

### 📱 Mobile Customization
1. Edit `assets/css/responsive.css`
2. Test on various screen sizes
3. Optimize touch interactions
4. Ensure admin panel mobile compatibility

## 📞 Support & Troubleshooting

### Common Issues

#### 🔌 Database Connection Issues
```php
// Error: "Database connection failed"
// Solution:
1. Verify database credentials in .env
2. Check if database exists
3. Ensure MySQL service is running
4. Test connection with phpMyAdmin
```

#### 📁 File Upload Issues
```bash
# Error: "Failed to upload file"
# Solution:
1. Set correct permissions: chmod 755 uploads/
2. Check PHP settings:
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300
```

#### 💳 Payment Issues
```bash
# Error: UPI payment not working
# Solution:
1. Verify UPI ID format (user@bank)
2. Test UPI links manually
3. Check WhatsApp integration
4. Ensure QR code generation works
```

#### 🎨 Theme Issues
```bash
# Error: Theme not changing
# Solution:
1. Clear browser cache
2. Check database site_settings table
3. Verify CSS file permissions
4. Test in incognito mode
```

### Getting Help
1. **Check Error Logs**: Hostinger Control Panel → Error Logs
2. **PHP Version**: Ensure PHP 7.4+ is active
3. **Database Issues**: Use phpMyAdmin to verify data
4. **File Permissions**: Use File Manager to check permissions
5. **Contact Support**: Include error messages and steps to reproduce

## 📋 Testing Checklist

### Before Going Live
- [ ] **Themes**: Test all 4 themes on mobile and desktop
- [ ] **Products**: Add/edit/delete products successfully
- [ ] **Shopping Cart**: Add to cart, update quantities, checkout
- [ ] **UPI Payment**: Test UPI links and QR code generation
- [ ] **Admin Panel**: All admin functions working
- [ ] **Reviews**: Submit and approve review workflow
- [ ] **Gallery**: Upload and display images correctly
- [ ] **Videos**: YouTube videos embedding properly
- [ ] **Banners**: Auto-scrolling banners working
- [ ] **Contact**: VCF download, WhatsApp sharing
- [ ] **SEO**: Meta tags, Open Graph tags
- [ ] **Mobile**: Responsive design on all devices
- [ ] **Performance**: Fast loading times
- [ ] **Security**: Change default admin password

### 🧪 Sample Test Data Included
- **Admin Login**: `admin` / `admin123`
- **Products**: 5 sample products with images
- **Reviews**: 4 approved customer reviews
- **Videos**: 3 YouTube video embeds
- **Banners**: 3 promotional banners
- **Gallery**: 4 sample gallery images
- **PDFs**: 5 downloadable PDF buttons
- **UPI ID**: `demo@upi` (change to your actual UPI ID)

## 🎯 Production Optimization

### Performance Tips
1. **Enable GZIP**: Add to .htaccess for compression
2. **Optimize Images**: Compress images before upload
3. **Database**: Regular cleanup of old data
4. **Caching**: Enable browser caching
5. **CDN**: Use CDN for static assets if needed

### Security Hardening
1. **SSL Certificate**: Install SSL (free with Hostinger)
2. **Strong Passwords**: Change default admin password
3. **Regular Backups**: Use admin backup feature
4. **File Permissions**: Set correct permissions
5. **Updates**: Keep PHP and MySQL updated
6. **Monitor**: Check access logs regularly

## 📈 Analytics & Tracking

### Built-in Analytics
- **Visit Counter**: Real-time visitor tracking
- **Revenue Charts**: Daily/monthly revenue graphs
- **Order Analytics**: Order status and trends
- **Customer Reviews**: Rating analytics
- **Product Performance**: Best-selling products

### External Integration
- **Google Analytics**: Add tracking ID in settings
- **Facebook Pixel**: Add pixel ID for ads
- **WhatsApp Business**: Integrate business API
- **Email Marketing**: Connect with email services

---

## 🏆 What You Get

### ✅ Complete Business Solution
- **Professional Website**: 4 stunning themes, mobile-optimized
- **E-commerce Platform**: Full shopping cart with UPI payments
- **Admin Panel**: Complete content management system
- **SEO Optimized**: Ready for Google search ranking
- **Mobile Ready**: Perfect on all devices
- **Social Integration**: WhatsApp, social media sharing
- **Analytics Dashboard**: Track visitors, orders, revenue
- **Multi-language**: Support for global audience
- **Gallery System**: Showcase your work (20 images)
- **Review System**: Build customer trust
- **PDF Downloads**: Share brochures, catalogs
- **Auto Banners**: Promotional slideshow
- **YouTube Integration**: Video marketing

### 🚀 Ready for Hostinger
- **One-Click Deploy**: Upload and run
- **Database Included**: Complete with sample data
- **Documentation**: Step-by-step setup guide
- **Support Ready**: Troubleshooting guide included
- **Production Ready**: Secure, optimized, tested

### 💰 Business Benefits
- **Increase Sales**: Professional online presence
- **Save Time**: Admin panel manages everything
- **Mobile Customers**: Reach mobile users effectively
- **SEO Traffic**: Get found on Google
- **Customer Trust**: Reviews and professional design
- **Easy Updates**: Change content without coding
- **Cost Effective**: No monthly fees, own your site

---

## 🎉 Get Started Today!

1. **Download** all the files
2. **Upload** to your Hostinger account
3. **Import** the database
4. **Configure** your settings
5. **Launch** your professional website!

**Your complete digital business solution is ready in minutes!**

---

**Made with ❤️ for Digital Business Success**

*Transform your business with this professional, feature-complete microsite solution!*