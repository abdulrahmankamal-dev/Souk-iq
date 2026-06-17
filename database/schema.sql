-- SOUK.IQ Iraqi Product Discovery & Price Comparison Platform
-- Database Schema SQL (MySQL 8.0+ UTF8MB4 InnoDB)

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `rate_limits`;
DROP TABLE IF EXISTS `role_permissions`;
DROP TABLE IF EXISTS `permissions`;
DROP TABLE IF EXISTS `search_logs`;
DROP TABLE IF EXISTS `audit_logs`;
DROP TABLE IF EXISTS `reports`;
DROP TABLE IF EXISTS `advertisements`;
DROP TABLE IF EXISTS `subscription_plans`;
DROP TABLE IF EXISTS `subscriptions`;
DROP TABLE IF EXISTS `notifications`;
DROP TABLE IF EXISTS `store_followers`;
DROP TABLE IF EXISTS `favorites`;
DROP TABLE IF EXISTS `reviews`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `store_staff`;
DROP TABLE IF EXISTS `stores`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `login_history`;
DROP TABLE IF EXISTS `user_settings`;
DROP TABLE IF EXISTS `users`;
SET FOREIGN_KEY_CHECKS = 1;

-- -----------------------------------------------------
-- TABLE: users
-- -----------------------------------------------------
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `uuid` CHAR(36) NOT NULL UNIQUE,
  `full_name` VARCHAR(120) NOT NULL,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(180) NOT NULL UNIQUE,
  `phone` VARCHAR(20) DEFAULT NULL,
  `phone_verified` TINYINT(1) DEFAULT 0,
  `email_verified` TINYINT(1) DEFAULT 0,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` ENUM('visitor', 'customer', 'store_owner', 'admin', 'super_admin') NOT NULL DEFAULT 'customer',
  `status` ENUM('active', 'inactive', 'suspended', 'banned') NOT NULL DEFAULT 'active',
  `avatar` VARCHAR(255) DEFAULT NULL,
  `cover_photo` VARCHAR(255) DEFAULT NULL,
  `bio` TEXT DEFAULT NULL,
  `country` VARCHAR(60) DEFAULT 'Iraq',
  `governorate` VARCHAR(60) DEFAULT NULL,
  `city` VARCHAR(60) DEFAULT NULL,
  `birth_date` DATE DEFAULT NULL,
  `gender` ENUM('male', 'female', 'prefer_not') DEFAULT 'prefer_not',
  `lang_pref` ENUM('ar', 'ku', 'en') DEFAULT 'ar',
  `theme_pref` ENUM('light', 'dark', 'system') DEFAULT 'system',
  `email_token` VARCHAR(64) DEFAULT NULL,
  `phone_otp` VARCHAR(6) DEFAULT NULL,
  `otp_expires_at` DATETIME DEFAULT NULL,
  `reset_token` VARCHAR(64) DEFAULT NULL,
  `reset_expires` DATETIME DEFAULT NULL,
  `two_fa_secret` VARCHAR(32) DEFAULT NULL,
  `two_fa_enabled` TINYINT(1) DEFAULT 0,
  `last_login_at` DATETIME DEFAULT NULL,
  `last_login_ip` VARCHAR(45) DEFAULT NULL,
  `login_count` INT UNSIGNED DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  INDEX `idx_users_email` (`email`),
  INDEX `idx_users_username` (`username`),
  INDEX `idx_users_role` (`role`),
  INDEX `idx_users_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- TABLE: user_settings
-- -----------------------------------------------------
CREATE TABLE `user_settings` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL UNIQUE,
  `profile_visibility` ENUM('public', 'private') DEFAULT 'public',
  `show_email` TINYINT(1) DEFAULT 0,
  `show_phone` TINYINT(1) DEFAULT 0,
  `show_location` TINYINT(1) DEFAULT 1,
  `allow_messages` TINYINT(1) DEFAULT 1,
  `notify_email_reviews` TINYINT(1) DEFAULT 1,
  `notify_email_follows` TINYINT(1) DEFAULT 1,
  `notify_email_marketing` TINYINT(1) DEFAULT 0,
  `notify_push_reviews` TINYINT(1) DEFAULT 1,
  `notify_push_follows` TINYINT(1) DEFAULT 1,
  `notify_push_pricedrops` TINYINT(1) DEFAULT 1,
  `notify_push_newproducts` TINYINT(1) DEFAULT 1,
  CONSTRAINT `fk_settings_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- TABLE: login_history
-- -----------------------------------------------------
CREATE TABLE `login_history` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` VARCHAR(500) DEFAULT NULL,
  `device_type` ENUM('desktop', 'mobile', 'tablet', 'unknown') DEFAULT 'unknown',
  `location` VARCHAR(100) DEFAULT NULL,
  `status` ENUM('success', 'failed', '2fa_required') DEFAULT 'success',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_login_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- TABLE: categories
-- -----------------------------------------------------
CREATE TABLE `categories` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `parent_id` INT UNSIGNED DEFAULT NULL,
  `name_ar` VARCHAR(120) NOT NULL,
  `name_ku` VARCHAR(120) NOT NULL,
  `name_en` VARCHAR(120) NOT NULL,
  `slug` VARCHAR(140) NOT NULL UNIQUE,
  `icon` VARCHAR(100) DEFAULT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `color` VARCHAR(7) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `sort_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_categories_parent` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- TABLE: stores
-- -----------------------------------------------------
CREATE TABLE `stores` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `uuid` CHAR(36) NOT NULL UNIQUE,
  `owner_id` BIGINT UNSIGNED NOT NULL,
  `name_ar` VARCHAR(180) NOT NULL,
  `name_ku` VARCHAR(180) DEFAULT NULL,
  `name_en` VARCHAR(180) DEFAULT NULL,
  `slug` VARCHAR(200) NOT NULL UNIQUE,
  `logo` VARCHAR(255) DEFAULT NULL,
  `banner` VARCHAR(255) DEFAULT NULL,
  `description_ar` TEXT DEFAULT NULL,
  `description_ku` TEXT DEFAULT NULL,
  `description_en` TEXT DEFAULT NULL,
  `category_id` INT UNSIGNED DEFAULT NULL,
  `address_line` VARCHAR(300) DEFAULT NULL,
  `governorate` VARCHAR(60) DEFAULT NULL,
  `city` VARCHAR(60) DEFAULT NULL,
  `latitude` DECIMAL(10,7) DEFAULT NULL,
  `longitude` DECIMAL(10,7) DEFAULT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `whatsapp` VARCHAR(20) DEFAULT NULL,
  `email` VARCHAR(180) DEFAULT NULL,
  `website` VARCHAR(255) DEFAULT NULL,
  `facebook` VARCHAR(255) DEFAULT NULL,
  `instagram` VARCHAR(255) DEFAULT NULL,
  `tiktok` VARCHAR(255) DEFAULT NULL,
  `working_hours` JSON DEFAULT NULL,
  `status` ENUM('pending', 'active', 'suspended', 'rejected', 'closed') NOT NULL DEFAULT 'pending',
  `is_verified` TINYINT(1) DEFAULT 0,
  `is_featured` TINYINT(1) DEFAULT 0,
  `trust_score` DECIMAL(3,1) DEFAULT 5.0,
  `views_count` BIGINT UNSIGNED DEFAULT 0,
  `followers_count` INT UNSIGNED DEFAULT 0,
  `products_count` INT UNSIGNED DEFAULT 0,
  `avg_rating` DECIMAL(3,2) DEFAULT 0.00,
  `reviews_count` INT UNSIGNED DEFAULT 0,
  `subscription_id` BIGINT UNSIGNED DEFAULT NULL,
  `rejection_reason` TEXT DEFAULT NULL,
  `verified_at` DATETIME DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  INDEX `idx_stores_slug` (`slug`),
  INDEX `idx_stores_status` (`status`),
  INDEX `idx_stores_governorate` (`governorate`),
  CONSTRAINT `fk_stores_owner` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_stores_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- TABLE: store_staff
-- -----------------------------------------------------
CREATE TABLE `store_staff` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `store_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `role` ENUM('manager', 'editor', 'viewer') NOT NULL DEFAULT 'viewer',
  `permissions` JSON DEFAULT NULL,
  `invited_by` BIGINT UNSIGNED DEFAULT NULL,
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `joined_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `uk_store_staff` (`store_id`, `user_id`),
  CONSTRAINT `fk_staff_store` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_staff_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- TABLE: products
-- -----------------------------------------------------
CREATE TABLE `products` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `uuid` CHAR(36) NOT NULL UNIQUE,
  `store_id` BIGINT UNSIGNED NOT NULL,
  `category_id` INT UNSIGNED DEFAULT NULL,
  `subcategory_id` INT UNSIGNED DEFAULT NULL,
  `name_ar` VARCHAR(255) NOT NULL,
  `name_ku` VARCHAR(255) DEFAULT NULL,
  `name_en` VARCHAR(255) DEFAULT NULL,
  `slug` VARCHAR(300) NOT NULL,
  `sku` VARCHAR(100) DEFAULT NULL,
  `brand` VARCHAR(100) DEFAULT NULL,
  `description_ar` LONGTEXT DEFAULT NULL,
  `description_ku` LONGTEXT DEFAULT NULL,
  `description_en` LONGTEXT DEFAULT NULL,
  `price` DECIMAL(12,2) NOT NULL,
  `discount_price` DECIMAL(12,2) DEFAULT NULL,
  `currency` VARCHAR(3) DEFAULT 'IQD',
  `price_negotiable` TINYINT(1) DEFAULT 0,
  `images` JSON DEFAULT NULL,
  `thumbnail` VARCHAR(255) DEFAULT NULL,
  `specifications` JSON DEFAULT NULL,
  `warranty_info` VARCHAR(255) DEFAULT NULL,
  `condition_type` ENUM('new', 'used', 'refurbished') NOT NULL DEFAULT 'new',
  `stock_status` ENUM('in_stock', 'out_of_stock', 'limited') NOT NULL DEFAULT 'in_stock',
  `stock_qty` INT DEFAULT NULL,
  `status` ENUM('draft', 'pending', 'active', 'rejected', 'archived') NOT NULL DEFAULT 'pending',
  `is_featured` TINYINT(1) DEFAULT 0,
  `views_count` BIGINT UNSIGNED DEFAULT 0,
  `favorites_count` INT UNSIGNED DEFAULT 0,
  `avg_rating` DECIMAL(3,2) DEFAULT 0.00,
  `reviews_count` INT UNSIGNED DEFAULT 0,
  `tags` JSON DEFAULT NULL,
  `meta_title` VARCHAR(255) DEFAULT NULL,
  `meta_description` VARCHAR(500) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  INDEX `idx_products_store` (`store_id`),
  INDEX `idx_products_category` (`category_id`),
  INDEX `idx_products_slug` (`slug`),
  INDEX `idx_products_status` (`status`),
  INDEX `idx_products_price` (`price`),
  FULLTEXT INDEX `ft_products` (`name_ar`, `name_ku`, `name_en`, `description_ar`),
  CONSTRAINT `fk_products_store` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_products_subcategory` FOREIGN KEY (`subcategory_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- TABLE: reviews
-- -----------------------------------------------------
CREATE TABLE `reviews` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `target_type` ENUM('product', 'store') NOT NULL,
  `target_id` BIGINT UNSIGNED NOT NULL,
  `rating` TINYINT UNSIGNED NOT NULL CHECK (`rating` BETWEEN 1 AND 5),
  `title` VARCHAR(200) DEFAULT NULL,
  `body` TEXT DEFAULT NULL,
  `pros` TEXT DEFAULT NULL,
  `cons` TEXT DEFAULT NULL,
  `images` JSON DEFAULT NULL,
  `status` ENUM('pending', 'approved', 'rejected', 'hidden') NOT NULL DEFAULT 'pending',
  `helpful_count` INT UNSIGNED DEFAULT 0,
  `is_verified_purchase` TINYINT(1) DEFAULT 0,
  `store_reply` TEXT DEFAULT NULL,
  `replied_at` DATETIME DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- TABLE: favorites
-- -----------------------------------------------------
CREATE TABLE `favorites` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `type` ENUM('product', 'store') NOT NULL,
  `target_id` BIGINT UNSIGNED NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `uk_favorites_user_target` (`user_id`, `type`, `target_id`),
  CONSTRAINT `fk_favorites_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- TABLE: store_followers
-- -----------------------------------------------------
CREATE TABLE `store_followers` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `store_id` BIGINT UNSIGNED NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `uk_store_followers` (`user_id`, `store_id`),
  CONSTRAINT `fk_followers_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_followers_store` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- TABLE: notifications
-- -----------------------------------------------------
CREATE TABLE `notifications` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `type` VARCHAR(60) NOT NULL,
  `title` VARCHAR(255) DEFAULT NULL,
  `body` TEXT DEFAULT NULL,
  `data` JSON DEFAULT NULL,
  `icon` VARCHAR(100) DEFAULT NULL,
  `link` VARCHAR(500) DEFAULT NULL,
  `is_read` TINYINT(1) DEFAULT 0,
  `read_at` DATETIME DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_notifications_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- TABLE: subscription_plans
-- -----------------------------------------------------
CREATE TABLE `subscription_plans` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name_ar` VARCHAR(80) NOT NULL,
  `name_ku` VARCHAR(80) NOT NULL,
  `name_en` VARCHAR(80) NOT NULL,
  `slug` VARCHAR(80) NOT NULL UNIQUE,
  `price_monthly` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `price_yearly` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `currency` VARCHAR(3) DEFAULT 'IQD',
  `max_products` INT DEFAULT 10,
  `max_images` INT DEFAULT 5,
  `analytics_level` ENUM('basic', 'advanced', 'full') DEFAULT 'basic',
  `can_feature` TINYINT(1) DEFAULT 0,
  `can_advertise` TINYINT(1) DEFAULT 0,
  `priority_index` INT DEFAULT 0,
  `badge_type` VARCHAR(40) DEFAULT NULL,
  `features` JSON DEFAULT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `sort_order` INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- TABLE: subscriptions
-- -----------------------------------------------------
CREATE TABLE `subscriptions` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `store_id` BIGINT UNSIGNED NOT NULL,
  `plan_id` INT UNSIGNED NOT NULL,
  `status` ENUM('trial', 'active', 'expired', 'cancelled', 'past_due') NOT NULL DEFAULT 'trial',
  `starts_at` DATETIME NOT NULL,
  `ends_at` DATETIME DEFAULT NULL,
  `auto_renew` TINYINT(1) DEFAULT 1,
  `price_paid` DECIMAL(10,2) DEFAULT NULL,
  `currency` VARCHAR(3) DEFAULT 'IQD',
  `payment_method` VARCHAR(60) DEFAULT NULL,
  `payment_ref` VARCHAR(200) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_subscriptions_store` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_subscriptions_plan` FOREIGN KEY (`plan_id`) REFERENCES `subscription_plans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- TABLE: advertisements
-- -----------------------------------------------------
CREATE TABLE `advertisements` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `store_id` BIGINT UNSIGNED DEFAULT NULL,
  `type` ENUM('banner_home', 'banner_search', 'featured_store', 'featured_product', 'sidebar') NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `link` VARCHAR(500) DEFAULT NULL,
  `target_id` BIGINT UNSIGNED DEFAULT NULL,
  `target_type` ENUM('store', 'product', 'url') DEFAULT 'url',
  `position` INT DEFAULT 0,
  `starts_at` DATETIME DEFAULT NULL,
  `ends_at` DATETIME DEFAULT NULL,
  `impressions` BIGINT UNSIGNED DEFAULT 0,
  `clicks` BIGINT UNSIGNED DEFAULT 0,
  `status` ENUM('pending', 'active', 'paused', 'expired') NOT NULL DEFAULT 'pending',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_ads_store` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- TABLE: reports
-- -----------------------------------------------------
CREATE TABLE `reports` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `reporter_id` BIGINT UNSIGNED NOT NULL,
  `target_type` ENUM('product', 'store', 'review', 'user') NOT NULL,
  `target_id` BIGINT UNSIGNED NOT NULL,
  `reason` ENUM('spam', 'fake', 'inappropriate', 'wrong_info', 'scam', 'other') NOT NULL DEFAULT 'other',
  `details` TEXT DEFAULT NULL,
  `status` ENUM('pending', 'investigating', 'resolved', 'dismissed') NOT NULL DEFAULT 'pending',
  `resolved_by` BIGINT UNSIGNED DEFAULT NULL,
  `resolution_note` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `resolved_at` DATETIME DEFAULT NULL,
  CONSTRAINT `fk_reports_reporter` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_reports_resolver` FOREIGN KEY (`resolved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- TABLE: audit_logs
-- -----------------------------------------------------
CREATE TABLE `audit_logs` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED DEFAULT NULL,
  `action` VARCHAR(100) NOT NULL,
  `target_type` VARCHAR(60) DEFAULT NULL,
  `target_id` BIGINT UNSIGNED DEFAULT NULL,
  `old_data` JSON DEFAULT NULL,
  `new_data` JSON DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` VARCHAR(500) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_audit_user` (`user_id`),
  INDEX `idx_audit_action` (`action`),
  INDEX `idx_audit_created` (`created_at`),
  CONSTRAINT `fk_audit_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- TABLE: search_logs
-- -----------------------------------------------------
CREATE TABLE `search_logs` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED DEFAULT NULL,
  `query` VARCHAR(500) NOT NULL,
  `results_count` INT DEFAULT 0,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_search_log_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- TABLE: permissions
-- -----------------------------------------------------
CREATE TABLE `permissions` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL UNIQUE,
  `group_name` VARCHAR(60) DEFAULT NULL,
  `description` VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- TABLE: role_permissions
-- -----------------------------------------------------
CREATE TABLE `role_permissions` (
  `role` ENUM('customer', 'store_owner', 'admin', 'super_admin') NOT NULL,
  `permission_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`role`, `permission_id`),
  CONSTRAINT `fk_role_perm_id` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- TABLE: rate_limits
-- -----------------------------------------------------
CREATE TABLE `rate_limits` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `identifier` VARCHAR(100) NOT NULL,
  `action` VARCHAR(60) NOT NULL,
  `attempts` INT UNSIGNED DEFAULT 1,
  `window_start` DATETIME NOT NULL,
  `blocked_until` DATETIME DEFAULT NULL,
  UNIQUE KEY `uk_rate_limits` (`identifier`, `action`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
