-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 12, 2025 at 06:13 AM
-- Server version: 10.11.10-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u261459251_wap`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','super_admin') DEFAULT 'admin',
  `status` enum('active','inactive') DEFAULT 'active',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `profile_image_url` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `email`, `password_hash`, `role`, `status`, `last_login`, `created_at`, `updated_at`, `profile_image_url`) VALUES
(1, 'admin', 'admin@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin', 'active', '2025-08-12 05:43:29', '2025-08-12 03:43:55', '2025-08-12 05:43:29', 'https://i.ibb.co/RkTVDnMS/my-gt.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `admin_activity_log`
--

CREATE TABLE `admin_activity_log` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `action` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_activity_log`
--

INSERT INTO `admin_activity_log` (`id`, `admin_id`, `action`, `description`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 'login', 'Admin logged in successfully', '127.0.0.1', NULL, '2025-08-12 04:37:34'),
(2, 1, 'product_add', 'Added new product: Premium Business Card', '127.0.0.1', NULL, '2025-08-12 04:37:34'),
(3, 1, 'settings_update', 'Updated site settings', '127.0.0.1', NULL, '2025-08-12 04:37:34');

-- --------------------------------------------------------

--
-- Table structure for table `admin_bypass_tokens`
--

CREATE TABLE `admin_bypass_tokens` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` timestamp NOT NULL,
  `used` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_sessions`
--

CREATE TABLE `admin_sessions` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `session_token` varchar(128) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `image_url` varchar(500) NOT NULL,
  `link_url` varchar(500) DEFAULT NULL,
  `position` enum('top','bottom','both') DEFAULT 'both',
  `status` enum('active','inactive') DEFAULT 'active',
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `title`, `image_url`, `link_url`, `position`, `status`, `sort_order`, `created_at`) VALUES
(2, 'New Product Launch', 'https://images.pexels.com/photos/3184338/pexels-photo-3184338.jpeg?auto=compress&cs=tinysrgb&w=800', '#', 'both', 'active', 2, '2025-08-12 03:43:55'),
(3, 'Premium Services', 'https://images.pexels.com/photos/3184465/pexels-photo-3184465.jpeg?auto=compress&cs=tinysrgb&w=800', '#', 'both', 'active', 3, '2025-08-12 03:43:55'),
(4, 'sale is live', 'https://i.ibb.co/v6cXsgwQ/ssss.jpg', '', 'top', 'active', 1, '2025-08-12 04:56:10');

-- --------------------------------------------------------

--
-- Table structure for table `free_website_requests`
--

CREATE TABLE `free_website_requests` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `business_details` text DEFAULT NULL,
  `status` enum('pending','contacted','completed') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `free_website_requests`
--

INSERT INTO `free_website_requests` (`id`, `name`, `mobile`, `email`, `business_details`, `status`, `created_at`) VALUES
(1, 'Restaurant Owner', '9876543214', 'restaurant@example.com', 'I own a restaurant and need a website to showcase our menu and take online orders', 'pending', '2025-08-12 03:43:55'),
(2, 'Freelancer', '9876543215', 'freelancer@example.com', 'I am a freelance photographer and need a portfolio website', 'contacted', '2025-08-12 03:43:55'),
(3, 'raju', '9898989898', 'raj@yahoo.com', 'want to get it', 'pending', '2025-08-12 04:04:05'),
(4, 'rehman', '9865986598', 'rehman@yahoo.com', 'best one', 'pending', '2025-08-12 04:48:07'),
(5, 'kalpesh', '9865659865', 'kalpesh@yahoo.com', 'i need it', 'pending', '2025-08-12 04:59:04'),
(6, 'kalpesh', '9865659865', 'kalpesh@yahoo.com', 'need it', 'pending', '2025-08-12 05:08:00');

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(500) NOT NULL,
  `thumbnail_url` varchar(500) DEFAULT NULL,
  `alt_text` varchar(200) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `sort_order` int(11) DEFAULT 0,
  `upload_date` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `title`, `description`, `image_url`, `thumbnail_url`, `alt_text`, `status`, `sort_order`, `upload_date`) VALUES
(1, 'Business Card Design 1', 'Premium business card design sample', 'https://images.pexels.com/photos/6289065/pexels-photo-6289065.jpeg?auto=compress&cs=tinysrgb&w=400', NULL, 'Business Card Design', 'active', 1, '2025-08-12 03:43:55'),
(2, 'Business Card Design 2', 'Modern business card design sample', 'https://images.pexels.com/photos/6289025/pexels-photo-6289025.jpeg?auto=compress&cs=tinysrgb&w=400', NULL, 'Modern Business Card', 'active', 2, '2025-08-12 03:43:55'),
(3, 'Logo Design Sample', 'Professional logo design sample', 'https://images.pexels.com/photos/3184432/pexels-photo-3184432.jpeg?auto=compress&cs=tinysrgb&w=400', NULL, 'Logo Design', 'active', 3, '2025-08-12 03:43:55'),
(4, 'Branding Package', 'Complete branding package sample', 'https://images.pexels.com/photos/3184339/pexels-photo-3184339.jpeg?auto=compress&cs=tinysrgb&w=400', NULL, 'Branding Package', 'active', 4, '2025-08-12 03:43:55'),
(5, 'Product', 'new arrival', 'https://i.ibb.co/DHJKtM1M/gta-new-postr.jpg', '', 'launched', 'active', 0, '2025-08-12 04:51:42');

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `id` int(11) NOT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `user_phone` varchar(20) DEFAULT NULL,
  `user_email` varchar(100) DEFAULT NULL,
  `products` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`products`)),
  `message` text DEFAULT NULL,
  `status` enum('pending','contacted','completed') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inquiries`
--

INSERT INTO `inquiries` (`id`, `user_name`, `user_phone`, `user_email`, `products`, `message`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Inquiry Customer', '9876543212', 'inquiry@example.com', '[{\"id\": 4, \"title\": \"Logo Design Service\"}]', 'Interested in logo design for my startup', 'pending', '2025-08-12 03:43:55', '2025-08-12 03:43:55'),
(2, 'Business Owner', '9876543213', 'business@example.com', '[{\"id\": 5, \"title\": \"Website Development\"}]', 'Need a professional website for my business', 'contacted', '2025-08-12 03:43:55', '2025-08-12 03:43:55'),
(3, 'Guest User', '', '', '[{\"id\":4,\"title\":\"Logo Design Service\",\"price\":\"1500.00\",\"image_url\":\"https:\\/\\/images.pexels.com\\/photos\\/3184432\\/pexels-photo-3184432.jpeg?auto=compress&cs=tinysrgb&w=400\"},{\"id\":5,\"title\":\"Website Development\",\"price\":\"12000.00\",\"image_url\":\"https:\\/\\/images.pexels.com\\/photos\\/196644\\/pexels-photo-196644.jpeg?auto=compress&cs=tinysrgb&w=400\"}]', 'Product Inquiry:\nLogo Design Service\nWebsite Development\n\nPlease provide more details about these products.', 'pending', '2025-08-12 04:45:13', '2025-08-12 04:45:13'),
(4, 'Guest User', '', '', '[{\"id\":2,\"title\":\"Digital Visiting Card\",\"price\":\"299.00\",\"image_url\":\"https:\\/\\/images.pexels.com\\/photos\\/6289025\\/pexels-photo-6289025.jpeg?auto=compress&cs=tinysrgb&w=400\"},{\"id\":1,\"title\":\"Premium Business Card\",\"price\":\"399.00\",\"image_url\":\"https:\\/\\/images.pexels.com\\/photos\\/6289065\\/pexels-photo-6289065.jpeg?auto=compress&cs=tinysrgb&w=400\"},{\"id\":5,\"title\":\"Website Development\",\"price\":\"12000.00\",\"image_url\":\"https:\\/\\/images.pexels.com\\/photos\\/196644\\/pexels-photo-196644.jpeg?auto=compress&cs=tinysrgb&w=400\"}]', 'Product Inquiry:\nDigital Visiting Card\nPremium Business Card\nWebsite Development\n\nPlease provide more details about these products.', 'pending', '2025-08-12 05:05:58', '2025-08-12 05:05:58'),
(5, 'Guest User', '', '', '[{\"id\":7,\"title\":\"Sms after Calls\",\"price\":\"999.00\",\"image_url\":\"https:\\/\\/i.ibb.co\\/GQTxwNGw\\/txt.jpg\"}]', 'Product Inquiry:\n\nSms after Calls - ₹999.00\n\nPlease provide more details about these products.', 'pending', '2025-08-12 05:27:02', '2025-08-12 05:27:02');

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_subscriptions`
--

CREATE TABLE `newsletter_subscriptions` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `status` enum('active','unsubscribed') DEFAULT 'active',
  `subscribed_at` timestamp NULL DEFAULT current_timestamp(),
  `unsubscribed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_number` varchar(50) NOT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `user_phone` varchar(20) DEFAULT NULL,
  `user_email` varchar(100) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `final_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','confirmed','paid','shipped','delivered','cancelled') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT 'upi',
  `payment_status` enum('pending','paid','failed') DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `tracking_number` varchar(100) DEFAULT NULL,
  `estimated_delivery` date DEFAULT NULL,
  `actual_delivery` date DEFAULT NULL,
  `delivery_address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_number`, `user_name`, `user_phone`, `user_email`, `total_amount`, `final_amount`, `status`, `payment_method`, `payment_status`, `notes`, `created_at`, `updated_at`, `tracking_number`, `estimated_delivery`, `actual_delivery`, `delivery_address`) VALUES
(1, NULL, 'ORD20250101001', 'Test Customer', '9876543210', 'test@example.com', 399.00, 399.00, 'paid', 'upi', 'pending', NULL, '2025-08-12 03:43:55', '2025-08-12 05:04:49', NULL, NULL, NULL, NULL),
(2, NULL, 'ORD20250101002', 'Sample User', '9876543211', 'sample@example.com', 1999.00, 1999.00, 'paid', 'upi', 'paid', NULL, '2025-08-12 03:43:55', '2025-08-12 05:08:27', NULL, NULL, NULL, NULL),
(3, NULL, 'ORD202508122941', 'Guest User', '', '', 1999.00, 1999.00, 'paid', 'upi', 'pending', NULL, '2025-08-12 03:47:25', '2025-08-12 05:08:25', NULL, NULL, NULL, NULL),
(4, NULL, 'ORD202508128372', 'Guest User', '', '', 299.00, 299.00, 'paid', 'upi', 'pending', NULL, '2025-08-12 04:44:31', '2025-08-12 05:08:22', NULL, NULL, NULL, NULL),
(5, NULL, 'ORD202508122377', 'Guest User', '', '', 99.00, 99.00, 'pending', 'upi', 'pending', NULL, '2025-08-12 05:25:10', '2025-08-12 05:25:10', NULL, NULL, NULL, NULL),
(6, NULL, 'ORD202508127965', 'Guest User', '', '', 99.00, 99.00, 'pending', 'upi', 'pending', NULL, '2025-08-12 05:25:35', '2025-08-12 05:25:35', NULL, NULL, NULL, NULL),
(7, NULL, 'ORD202508127681', 'Guest User', '', '', 299.00, 299.00, 'pending', 'upi', 'pending', NULL, '2025-08-12 05:35:07', '2025-08-12 05:35:07', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_title` varchar(200) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_title`, `quantity`, `unit_price`, `total_price`, `created_at`) VALUES
(1, 1, 1, 'Premium Business Card', 1, 399.00, 399.00, '2025-08-12 03:43:55'),
(2, 2, 3, 'Corporate Branding Package', 1, 1999.00, 1999.00, '2025-08-12 03:43:55'),
(3, 3, 3, 'Corporate Branding Package', 1, 1999.00, 1999.00, '2025-08-12 03:47:25'),
(4, 4, 2, 'Digital Visiting Card', 1, 299.00, 299.00, '2025-08-12 04:44:31'),
(5, 5, 6, 'NFC Card', 1, 99.00, 99.00, '2025-08-12 05:25:10'),
(6, 6, 6, 'NFC Card', 1, 99.00, 99.00, '2025-08-12 05:25:35'),
(7, 7, 2, 'Digital Visiting Card', 1, 299.00, 299.00, '2025-08-12 05:35:07');

-- --------------------------------------------------------

--
-- Table structure for table `payment_transactions`
--

CREATE TABLE `payment_transactions` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT 'upi',
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','success','failed','cancelled') DEFAULT 'pending',
  `gateway_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gateway_response`)),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pdfs`
--

CREATE TABLE `pdfs` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `file_url` varchar(500) NOT NULL,
  `file_size` int(11) DEFAULT NULL,
  `download_count` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pdfs`
--

INSERT INTO `pdfs` (`id`, `title`, `description`, `file_url`, `file_size`, `download_count`, `status`, `sort_order`, `created_at`) VALUES
(1, 'Company Brochure', 'Download our complete company brochure', '/uploads/brochure.pdf', NULL, 0, 'active', 1, '2025-08-12 03:43:55'),
(2, 'Product Catalog', 'Complete catalog of all our products and services', '/uploads/catalog.pdf', NULL, 0, 'active', 2, '2025-08-12 03:43:55'),
(3, 'Price List', 'Current pricing for all our services', '/uploads/pricelist.pdf', NULL, 0, 'active', 3, '2025-08-12 03:43:55'),
(4, 'Company Profile', 'Detailed company profile and capabilities', '/uploads/profile.pdf', NULL, 0, 'active', 4, '2025-08-12 03:43:55'),
(5, 'Portfolio', 'Portfolio of our completed projects', '/uploads/portfolio.pdf', NULL, 0, 'active', 5, '2025-08-12 03:43:55');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_price` decimal(10,2) DEFAULT NULL,
  `qty_stock` int(11) DEFAULT 0,
  `image_url` varchar(500) DEFAULT NULL,
  `gallery_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery_images`)),
  `inquiry_only` tinyint(1) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `meta_title` varchar(200) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `tags` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `title`, `description`, `price`, `discount_price`, `qty_stock`, `image_url`, `gallery_images`, `inquiry_only`, `status`, `sort_order`, `created_at`, `updated_at`, `meta_title`, `meta_description`, `tags`) VALUES
(1, 'Premium Business Card', 'High-quality business cards with premium finish and professional design', 500.00, 399.00, 100, 'https://images.pexels.com/photos/6289065/pexels-photo-6289065.jpeg?auto=compress&cs=tinysrgb&w=400', NULL, 0, 'active', 1, '2025-08-12 03:43:55', '2025-08-12 03:43:55', NULL, NULL, NULL),
(2, 'Digital Visiting Card', 'Modern digital visiting card solution with QR code and online sharing', 299.00, NULL, 50, 'https://images.pexels.com/photos/6289025/pexels-photo-6289025.jpeg?auto=compress&cs=tinysrgb&w=400', NULL, 0, 'active', 2, '2025-08-12 03:43:55', '2025-08-12 03:43:55', NULL, NULL, NULL),
(3, 'Corporate Branding Package', 'Complete corporate branding solution including logo, letterhead, and business cards', 2999.00, 1999.00, 20, 'https://images.pexels.com/photos/3184339/pexels-photo-3184339.jpeg?auto=compress&cs=tinysrgb&w=400', NULL, 0, 'active', 3, '2025-08-12 03:43:55', '2025-08-12 03:43:55', NULL, NULL, NULL),
(4, 'Logo Design Service', 'Professional logo design service with multiple concepts and revisions', 1500.00, NULL, 0, 'https://images.pexels.com/photos/3184432/pexels-photo-3184432.jpeg?auto=compress&cs=tinysrgb&w=400', NULL, 1, 'active', 4, '2025-08-12 03:43:55', '2025-08-12 03:43:55', NULL, NULL, NULL),
(5, 'Website Development', 'Custom website development with responsive design and SEO optimization', 15000.00, 12000.00, 0, 'https://images.pexels.com/photos/196644/pexels-photo-196644.jpeg?auto=compress&cs=tinysrgb&w=400', NULL, 1, 'active', 5, '2025-08-12 03:43:55', '2025-08-12 03:43:55', NULL, NULL, NULL),
(6, 'NFC Card', 'Tap &amp; Share', 399.00, 99.00, 100, 'https://i.ibb.co/TDL9srQZ/Whats-App-Image-2025-04-13-at-11-34-06-AM.jpg', NULL, 0, 'active', 0, '2025-08-12 05:03:19', '2025-08-12 05:03:19', NULL, NULL, NULL),
(7, 'Sms after Calls', 'outgoing , incomming , missed call after Auto sms sender', 1500.00, 999.00, 100, 'https://i.ibb.co/GQTxwNGw/txt.jpg', NULL, 1, 'active', 0, '2025-08-12 05:07:26', '2025-08-12 05:07:26', NULL, NULL, NULL),
(8, 'Sms after Calls', 'outgoing , incomming , missed call after Auto sms sender', 1500.00, 999.00, 100, 'https://i.ibb.co/GQTxwNGw/txt.jpg', NULL, 1, 'active', 0, '2025-08-12 05:08:10', '2025-08-12 05:08:10', NULL, NULL, NULL),
(9, 'Sms after Calls', 'outgoing , incomming , missed call after Auto sms sender', 1500.00, 999.00, 100, 'https://i.ibb.co/GQTxwNGw/txt.jpg', NULL, 1, 'active', 0, '2025-08-12 05:08:12', '2025-08-12 05:08:12', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `rating` int(1) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `comment` text NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `ip_address` varchar(45) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `product_id` int(11) DEFAULT NULL,
  `helpful_count` int(11) DEFAULT 0,
  `verified_purchase` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `name`, `email`, `phone`, `rating`, `comment`, `status`, `ip_address`, `approved_at`, `created_at`, `product_id`, `helpful_count`, `verified_purchase`) VALUES
(1, 'Rajesh Kumar', 'rajesh@example.com', '9876543210', 5, 'Excellent service and professional quality work. Highly recommended for business cards!', 'approved', NULL, '2025-08-12 03:43:55', '2025-08-12 03:43:55', NULL, 0, 0),
(2, 'Priya Singh', 'priya@example.com', '9876543211', 4, 'Great experience with their team. Very responsive and helpful throughout the process.', 'approved', NULL, '2025-08-12 03:43:55', '2025-08-12 03:43:55', NULL, 0, 0),
(3, 'Amit Sharma', 'amit@example.com', '9876543212', 5, 'Outstanding digital visiting card solution. Modern and professional design.', 'approved', NULL, '2025-08-12 03:43:55', '2025-08-12 03:43:55', NULL, 0, 0),
(4, 'Neha Patel', 'neha@example.com', '9876543213', 4, 'Good quality products and timely delivery. Will definitely use their services again.', 'approved', NULL, '2025-08-12 03:43:55', '2025-08-12 03:43:55', NULL, 0, 0),
(5, 'sona', 'sona@gmail.com', '9865986598', 5, 'best', 'approved', '2401:4900:1c9a:d026:3024:4c0:7d17:e807', '2025-08-12 04:50:01', '2025-08-12 04:43:38', NULL, 0, 0),
(6, 'Mahesh', 'mahesh@yahoo.com', '9865326536', 5, 'Best', 'approved', '2401:4900:1c9a:d026:75fc:c3e2:8935:c958', '2025-08-12 04:49:57', '2025-08-12 04:45:46', NULL, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` enum('text','number','boolean','json','url') DEFAULT 'text',
  `description` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `description`, `updated_at`) VALUES
(1, 'site_title', 'Business Portfolio', 'text', 'Website title', '2025-08-12 04:58:03'),
(2, 'company_name', 'Gt Digital', 'text', 'Company name', '2025-08-12 04:58:03'),
(3, 'director_name', 'Vishal Rathod', 'text', 'Director/Owner name', '2025-08-12 03:43:55'),
(4, 'director_title', 'FOUNDER', 'text', 'Director title', '2025-08-12 03:43:55'),
(5, 'contact_phone1', '7767834383', 'text', 'Primary contact phone', '2025-08-12 04:58:03'),
(6, 'contact_phone2', '7767834383', 'text', 'Secondary contact phone', '2025-08-12 04:58:03'),
(7, 'contact_email', 'info@galaxytribes.in', 'text', 'Contact email', '2025-08-12 03:43:55'),
(8, 'contact_address', 'Nashik, Maharashtra, India', 'text', 'Business address', '2025-08-12 03:43:55'),
(9, 'whatsapp_number', '917767834383', 'text', 'WhatsApp number with country code', '2025-08-12 04:58:03'),
(10, 'website_url', 'https://gtai.in', 'url', 'Company website URL', '2025-08-12 04:58:03'),
(11, 'upi_id', '8329834309@ybl', 'text', 'UPI ID for payments', '2025-08-12 04:58:03'),
(12, 'meta_description', 'Professional Digital Visiting Card and Business Services - Get premium business cards, logo design, and complete branding solutions.', 'text', 'Meta description for SEO', '2025-08-12 04:37:34'),
(13, 'meta_keywords', 'visiting card, business card, digital card, logo design, branding, professional services, business solutions, corporate identity', 'text', 'Meta keywords for SEO', '2025-08-12 04:37:34'),
(14, 'current_theme', 'light', 'text', 'Current active theme', '2025-08-12 05:41:54'),
(15, 'logo_url', '', 'url', 'Company logo URL', '2025-08-12 03:43:55'),
(16, 'view_count', '51', 'number', 'Website view counter', '2025-08-12 05:57:06'),
(17, 'discount_text', 'DISCOUNT UPTO 50% ', 'text', 'Discount popup text', '2025-08-12 03:48:31'),
(18, 'show_discount_popup', '1', 'boolean', 'Show discount popup', '2025-08-12 03:43:55'),
(19, 'show_pwa_prompt', '1', 'boolean', 'Show PWA install prompt', '2025-08-12 03:43:55'),
(20, 'google_analytics', '', 'text', 'Google Analytics tracking ID', '2025-08-12 03:43:55'),
(21, 'facebook_pixel', '', 'text', 'Facebook Pixel ID', '2025-08-12 03:43:55'),
(22, 'social_facebook', 'https://facebook.com/democard', 'url', 'Facebook page URL', '2025-08-12 03:43:55'),
(23, 'social_youtube', 'https://youtube.com/democard', 'url', 'YouTube channel URL', '2025-08-12 03:43:55'),
(24, 'social_twitter', 'https://twitter.com/democard', 'url', 'Twitter profile URL', '2025-08-12 03:43:55'),
(25, 'social_instagram', 'https://instagram.com/democard', 'url', 'Instagram profile URL', '2025-08-12 03:43:55'),
(26, 'social_linkedin', 'https://linkedin.com/company/democard', 'url', 'LinkedIn page URL', '2025-08-12 03:43:55'),
(27, 'social_pinterest', 'https://pinterest.com/democard', 'url', 'Pinterest profile URL', '2025-08-12 03:43:55'),
(28, 'social_telegram', 'https://t.me/democard', 'url', 'Telegram channel URL', '2025-08-12 03:43:55'),
(29, 'social_zomato', 'https://zomato.com/democard', 'url', 'Zomato page URL', '2025-08-12 03:43:55'),
(146, 'director_image_url', '', 'url', 'Director/Owner profile image URL', '2025-08-12 04:37:33'),
(147, 'sticky_banner_enabled', '1', 'boolean', 'Enable sticky top banner', '2025-08-12 04:37:33'),
(148, 'sticky_banner_text', 'DISCOUNT UPTO 50% Live Use FREE code', 'text', 'Sticky banner text', '2025-08-12 04:37:33'),
(149, 'upi_merchant_name', 'DEMO CARD', 'text', 'UPI merchant display name', '2025-08-12 04:37:33'),
(150, 'payment_timer_minutes', '5', 'number', 'Payment confirmation timer in minutes', '2025-08-12 04:37:33'),
(151, 'inquiry_whatsapp_message', 'Hi! I would like to inquire about your products.', 'text', 'Default inquiry WhatsApp message', '2025-08-12 04:37:33'),
(152, 'seo_title_suffix', ' - DEMO CARD', 'text', 'SEO title suffix for all pages', '2025-08-12 04:37:34'),
(153, 'google_analytics_id', '', 'text', 'Google Analytics tracking ID', '2025-08-12 04:37:34'),
(154, 'facebook_pixel_id', '', 'text', 'Facebook Pixel ID', '2025-08-12 04:37:34'),
(155, 'whatsapp_chat_enabled', '1', 'boolean', 'Enable WhatsApp chat widget', '2025-08-12 04:37:34'),
(156, 'maintenance_mode', '0', 'boolean', 'Enable maintenance mode', '2025-08-12 04:37:34'),
(157, 'max_cart_items', '50', 'number', 'Maximum items allowed in cart', '2025-08-12 04:37:34'),
(158, 'min_order_amount', '100', 'number', 'Minimum order amount', '2025-08-12 04:37:34'),
(159, 'free_shipping_threshold', '500', 'number', 'Free shipping threshold amount', '2025-08-12 04:37:34'),
(160, 'currency_symbol', '₹', 'text', 'Currency symbol', '2025-08-12 04:37:34'),
(161, 'currency_code', 'INR', 'text', 'Currency code', '2025-08-12 04:37:34'),
(162, 'timezone', 'Asia/Kolkata', 'text', 'Website timezone', '2025-08-12 04:37:34'),
(163, 'date_format', 'd/m/Y', 'text', 'Date display format', '2025-08-12 04:37:34'),
(164, 'time_format', 'H:i', 'text', 'Time display format', '2025-08-12 04:37:34');

-- --------------------------------------------------------

--
-- Table structure for table `translations`
--

CREATE TABLE `translations` (
  `id` int(11) NOT NULL,
  `language_code` varchar(5) NOT NULL DEFAULT 'en',
  `translation_key` varchar(100) NOT NULL,
  `translation_value` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `translations`
--

INSERT INTO `translations` (`id`, `language_code`, `translation_key`, `translation_value`, `created_at`, `updated_at`) VALUES
(1, 'en', 'welcome', 'Welcome', '2025-08-12 03:43:55', '2025-08-12 03:43:55'),
(2, 'hi', 'welcome', 'स्वागत', '2025-08-12 03:43:55', '2025-08-12 03:43:55'),
(3, 'en', 'products', 'Products', '2025-08-12 03:43:55', '2025-08-12 03:43:55'),
(4, 'hi', 'products', 'उत्पाद', '2025-08-12 03:43:55', '2025-08-12 03:43:55'),
(5, 'en', 'services', 'Services', '2025-08-12 03:43:55', '2025-08-12 03:43:55'),
(6, 'hi', 'services', 'सेवाएं', '2025-08-12 03:43:55', '2025-08-12 03:43:55'),
(7, 'en', 'contact', 'Contact', '2025-08-12 03:43:55', '2025-08-12 03:43:55'),
(8, 'hi', 'contact', 'संपर्क', '2025-08-12 03:43:55', '2025-08-12 03:43:55'),
(9, 'en', 'about', 'About Us', '2025-08-12 03:43:55', '2025-08-12 03:43:55'),
(10, 'hi', 'about', 'हमारे बारे में', '2025-08-12 03:43:55', '2025-08-12 03:43:55');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `phone`, `password_hash`, `status`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'John Doe', 'johndoe', 'john@example.com', '9876543210', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active', NULL, '2025-08-12 03:43:55', '2025-08-12 03:43:55'),
(2, 'Jane Smith', 'janesmith', 'jane@example.com', '9876543211', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active', NULL, '2025-08-12 03:43:55', '2025-08-12 03:43:55'),
(3, 'raju', 'raj', 'raj@yahoo.com', '9850656565', '$2y$10$EsnETHacNMpix20ObjBQq.LxylKIAqWASTX/BVIL.FgMAgbXOXifC', 'active', '2025-08-12 03:44:54', '2025-08-12 03:44:48', '2025-08-12 03:44:54');

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `youtube_url` varchar(500) DEFAULT NULL,
  `embed_code` varchar(500) DEFAULT NULL,
  `thumbnail_url` varchar(500) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`id`, `title`, `description`, `youtube_url`, `embed_code`, `thumbnail_url`, `status`, `sort_order`, `created_at`) VALUES
(1, 'Company Introduction', 'Learn about our company and services', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'https://www.youtube.com/embed/dQw4w9WgXcQ', NULL, 'active', 1, '2025-08-12 03:43:55'),
(2, 'Product Showcase', 'Showcase of our premium business card designs', 'https://www.youtube.com/watch?v=jNQXAC9IVRw', 'https://www.youtube.com/embed/jNQXAC9IVRw', NULL, 'active', 2, '2025-08-12 03:43:55'),
(3, 'Customer Testimonials', 'What our customers say about our services', 'https://www.youtube.com/watch?v=9bZkp7q19f0', 'https://www.youtube.com/embed/9bZkp7q19f0', NULL, 'active', 3, '2025-08-12 03:43:55'),
(4, 'Just Launched', 'New Products', 'https://youtu.be/zcKKfwQY8Qo?si=nVtxiKub6u7VfuVU', 'https://www.youtube.com/embed/zcKKfwQY8Qo', NULL, 'active', 0, '2025-08-12 04:52:48');

-- --------------------------------------------------------

--
-- Table structure for table `visits`
--

CREATE TABLE `visits` (
  `id` int(11) NOT NULL,
  `page` varchar(100) DEFAULT 'home',
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `referer` varchar(500) DEFAULT NULL,
  `visit_time` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `visits`
--

INSERT INTO `visits` (`id`, `page`, `ip_address`, `user_agent`, `referer`, `visit_time`) VALUES
(1, 'home', '192.168.1.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', NULL, '2025-08-12 03:43:55'),
(2, 'home', '192.168.1.2', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X)', NULL, '2025-08-12 03:43:55'),
(3, 'home', '192.168.1.3', 'Mozilla/5.0 (Android 10; Mobile; rv:81.0)', NULL, '2025-08-12 03:43:55'),
(4, 'home', '2401:4900:1c9a:d026:3024:4c0:7d17:e807', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'https://wap.galaxytribes.in/', '2025-08-12 03:44:12'),
(5, 'home', '2401:4900:1c9a:d026:3024:4c0:7d17:e807', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'https://wap.galaxytribes.in/', '2025-08-12 03:44:54'),
(6, 'home', '2401:4900:1c9a:d026:3024:4c0:7d17:e807', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '', '2025-08-12 03:47:17'),
(7, 'home', '2401:4900:1c9a:d026:3024:4c0:7d17:e807', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '', '2025-08-12 03:47:33'),
(8, 'home', '2401:4900:1c9a:d026:3024:4c0:7d17:e807', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '', '2025-08-12 03:48:34'),
(9, 'home', '2401:4900:1c9a:d026:3024:4c0:7d17:e807', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'https://wap.galaxytribes.in/', '2025-08-12 04:03:43'),
(10, 'home', '2401:4900:1c9a:d026:3024:4c0:7d17:e807', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '', '2025-08-12 04:05:52'),
(11, 'home', '2401:4900:1c9a:d026:3024:4c0:7d17:e807', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'https://wap.galaxytribes.in/', '2025-08-12 04:07:23'),
(12, 'home', '2401:4900:1c9a:d026:3024:4c0:7d17:e807', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'https://wap.galaxytribes.in/', '2025-08-12 04:07:24'),
(13, 'home', '2401:4900:1c9a:d026:3024:4c0:7d17:e807', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'https://wap.galaxytribes.in/', '2025-08-12 04:10:29'),
(14, 'home', '2401:4900:1c9a:d026:75fc:c3e2:8935:c958', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', '', '2025-08-12 04:10:51'),
(15, 'home', '2401:4900:1c9a:d026:3024:4c0:7d17:e807', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'https://wap.galaxytribes.in/', '2025-08-12 04:43:15'),
(16, 'home', '2401:4900:1c9a:d026:3024:4c0:7d17:e807', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '', '2025-08-12 04:44:00'),
(17, 'home', '2401:4900:1c9a:d026:3024:4c0:7d17:e807', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'https://wap.galaxytribes.in/', '2025-08-12 04:44:15'),
(18, 'home', '2401:4900:1c9a:d026:75fc:c3e2:8935:c958', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', '', '2025-08-12 04:44:46'),
(19, 'home', '2401:4900:1c9a:d026:75fc:c3e2:8935:c958', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', '', '2025-08-12 04:44:48'),
(20, 'home', '2401:4900:1c9a:d026:3024:4c0:7d17:e807', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '', '2025-08-12 04:47:14'),
(21, 'home', '2401:4900:1c9a:d026:3024:4c0:7d17:e807', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '', '2025-08-12 04:58:29'),
(22, 'home', '2401:4900:1c9a:d026:3024:4c0:7d17:e807', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '', '2025-08-12 05:01:40'),
(23, 'home', '2401:4900:1c9a:d026:3024:4c0:7d17:e807', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '', '2025-08-12 05:03:24'),
(24, 'home', '2401:4900:1c9a:d026:75fc:c3e2:8935:c958', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', '', '2025-08-12 05:03:54'),
(25, 'home', '2401:4900:1c9a:d026:3024:4c0:7d17:e807', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '', '2025-08-12 05:07:30'),
(26, 'home', '2401:4900:1c9a:d026:3024:4c0:7d17:e807', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '', '2025-08-12 05:09:37'),
(27, 'home', '2401:4900:1c9a:d026:3024:4c0:7d17:e807', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '', '2025-08-12 05:09:53'),
(28, 'home', '2401:4900:1c9a:d026:75fc:c3e2:8935:c958', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', '', '2025-08-12 05:10:08'),
(29, 'home', '2401:4900:1c9a:d026:75fc:c3e2:8935:c958', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', '', '2025-08-12 05:12:05'),
(30, 'home', '2401:4900:1c9a:d026:75fc:c3e2:8935:c958', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', '', '2025-08-12 05:19:57'),
(31, 'home', '2401:4900:1c9a:d026:75fc:c3e2:8935:c958', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', '', '2025-08-12 05:20:00'),
(32, 'home', '2401:4900:1c9a:d026:75fc:c3e2:8935:c958', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', '', '2025-08-12 05:20:06'),
(33, 'home', '2401:4900:1c9a:d026:75fc:c3e2:8935:c958', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', '', '2025-08-12 05:20:07'),
(34, 'home', '2401:4900:1c9a:d026:75fc:c3e2:8935:c958', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', '', '2025-08-12 05:20:12'),
(35, 'home', '35.230.29.87', 'Mozilla/5.0 (Linux; Android 12; Pixel 6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Mobile Safari/537.36', '', '2025-08-12 05:20:28'),
(36, 'home', '35.230.29.87', 'Mozilla/5.0 (Linux; Android 12; Pixel 6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Mobile Safari/537.36', '', '2025-08-12 05:20:32'),
(37, 'home', '2401:4900:1c9a:d026:75fc:c3e2:8935:c958', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', '', '2025-08-12 05:21:08'),
(38, 'home', '2401:4900:1c9a:d026:3024:4c0:7d17:e807', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '', '2025-08-12 05:23:03'),
(39, 'home', '2401:4900:1c9a:d026:75fc:c3e2:8935:c958', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', '', '2025-08-12 05:25:03'),
(40, 'home', '2401:4900:1c9a:d026:75fc:c3e2:8935:c958', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', '', '2025-08-12 05:25:26'),
(41, 'home', '2401:4900:1c9a:d026:3024:4c0:7d17:e807', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'https://wap.galaxytribes.in/', '2025-08-12 05:26:47'),
(42, 'home', '2401:4900:1c9a:d026:3024:4c0:7d17:e807', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'https://wap.galaxytribes.in/', '2025-08-12 05:30:31'),
(43, 'home', '2401:4900:1c9a:d026:75fc:c3e2:8935:c958', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', '', '2025-08-12 05:34:59'),
(44, 'home', '2401:4900:1c9a:d026:75fc:c3e2:8935:c958', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', '', '2025-08-12 05:35:18'),
(45, 'home', '2401:4900:1c9a:d026:3024:4c0:7d17:e807', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'https://wap.galaxytribes.in/', '2025-08-12 05:36:41'),
(46, 'home', '34.53.69.147', 'Mozilla/5.0 (Linux; Android 12; Pixel 6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Mobile Safari/537.36', '', '2025-08-12 05:38:53'),
(47, 'home', '34.53.69.147', 'Mozilla/5.0 (Linux; Android 12; Pixel 6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Mobile Safari/537.36', '', '2025-08-12 05:38:56'),
(48, 'home', '2401:4900:1c9a:d026:3024:4c0:7d17:e807', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'https://wap.galaxytribes.in/admin/pdfs.php', '2025-08-12 05:39:09'),
(49, 'home', '2401:4900:1c9a:d026:3024:4c0:7d17:e807', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'https://wap.galaxytribes.in/index.php', '2025-08-12 05:41:54'),
(50, 'home', '2401:4900:1c9a:d026:3024:4c0:7d17:e807', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '', '2025-08-12 05:43:05'),
(51, 'home', '44.249.166.219', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36', '', '2025-08-12 05:57:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_email` (`email`);

--
-- Indexes for table `admin_activity_log`
--
ALTER TABLE `admin_activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_admin_id` (`admin_id`),
  ADD KEY `idx_action` (`action`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `admin_bypass_tokens`
--
ALTER TABLE `admin_bypass_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `idx_token` (`token`),
  ADD KEY `idx_expires` (`expires_at`);

--
-- Indexes for table `admin_sessions`
--
ALTER TABLE `admin_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `idx_session_token` (`session_token`),
  ADD KEY `idx_expires_at` (`expires_at`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_position` (`position`),
  ADD KEY `idx_sort_order` (`sort_order`);

--
-- Indexes for table `free_website_requests`
--
ALTER TABLE `free_website_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_mobile` (`mobile`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_sort_order` (`sort_order`);

--
-- Indexes for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_phone` (`user_phone`);

--
-- Indexes for table `newsletter_subscriptions`
--
ALTER TABLE `newsletter_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_order_number` (`order_number`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_order_id` (`order_id`);

--
-- Indexes for table `payment_transactions`
--
ALTER TABLE `payment_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `idx_transaction_id` (`transaction_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `pdfs`
--
ALTER TABLE `pdfs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_sort_order` (`sort_order`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_sort_order` (`sort_order`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_rating` (`rating`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`),
  ADD UNIQUE KEY `idx_setting_key` (`setting_key`);

--
-- Indexes for table `translations`
--
ALTER TABLE `translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_lang_key` (`language_code`,`translation_key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_email` (`email`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_sort_order` (`sort_order`);

--
-- Indexes for table `visits`
--
ALTER TABLE `visits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_page` (`page`),
  ADD KEY `idx_visit_time` (`visit_time`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_activity_log`
--
ALTER TABLE `admin_activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `admin_bypass_tokens`
--
ALTER TABLE `admin_bypass_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_sessions`
--
ALTER TABLE `admin_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `free_website_requests`
--
ALTER TABLE `free_website_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `newsletter_subscriptions`
--
ALTER TABLE `newsletter_subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `payment_transactions`
--
ALTER TABLE `payment_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pdfs`
--
ALTER TABLE `pdfs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=261;

--
-- AUTO_INCREMENT for table `translations`
--
ALTER TABLE `translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `visits`
--
ALTER TABLE `visits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_activity_log`
--
ALTER TABLE `admin_activity_log`
  ADD CONSTRAINT `admin_activity_log_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `admin_bypass_tokens`
--
ALTER TABLE `admin_bypass_tokens`
  ADD CONSTRAINT `admin_bypass_tokens_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `admin_sessions`
--
ALTER TABLE `admin_sessions`
  ADD CONSTRAINT `admin_sessions_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payment_transactions`
--
ALTER TABLE `payment_transactions`
  ADD CONSTRAINT `payment_transactions_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
