-- SOUK.IQ Seeds SQL
-- Initial Categories, Plans, Roles, Permissions, and Test Data

-- 1. Insert default permissions
INSERT INTO `permissions` (`name`, `group_name`, `description`) VALUES
('view_dashboard', 'General', 'Access user dashboard'),
('manage_profile', 'General', 'Edit own profile details'),
('write_reviews', 'General', 'Write reviews for products/stores'),
('create_store', 'Store Owner', 'Register a new store'),
('manage_store_products', 'Store Owner', 'Add, edit, delete store products'),
('manage_store_staff', 'Store Owner', 'Invite or modify store staff'),
('view_store_analytics', 'Store Owner', 'View store visitor charts'),
('manage_users', 'Admin', 'View and modify user accounts'),
('manage_stores', 'Admin', 'Approve/reject or suspend stores'),
('manage_products', 'Admin', 'Moderate products'),
('manage_categories', 'Admin', 'Add/edit system categories'),
('manage_ads', 'Admin', 'Manage store banner ads'),
('view_audit_logs', 'Super Admin', 'Inspect system audit trails'),
('manage_settings', 'Super Admin', 'Configure global platform parameters');

-- 2. Associate permissions with roles
-- SUPER ADMIN: all permissions
INSERT INTO `role_permissions` (`role`, `permission_id`)
SELECT 'super_admin', `id` FROM `permissions`;

-- ADMIN: general, store owner, and admin permissions
INSERT INTO `role_permissions` (`role`, `permission_id`)
SELECT 'admin', `id` FROM `permissions` WHERE `group_name` IN ('General', 'Store Owner', 'Admin');

-- STORE OWNER: own store permissions and customer features
INSERT INTO `role_permissions` (`role`, `permission_id`)
SELECT 'store_owner', `id` FROM `permissions` WHERE `group_name` IN ('General', 'Store Owner');

-- CUSTOMER: profile, reviews, and general dashboard access
INSERT INTO `role_permissions` (`role`, `permission_id`)
SELECT 'customer', `id` FROM `permissions` WHERE `group_name` = 'General';


-- 3. Insert default subscription plans
INSERT INTO `subscription_plans` (`name_ar`, `name_ku`, `name_en`, `slug`, `price_monthly`, `price_yearly`, `currency`, `max_products`, `max_images`, `analytics_level`, `can_feature`, `can_advertise`, `priority_index`, `badge_type`, `features`, `is_active`, `sort_order`) VALUES
('الباقة المجانية', 'پلانى خۆڕایی', 'Free Plan', 'free', 0.00, 0.00, 'IQD', 10, 3, 'basic', 0, 0, 0, 'none', '["10 Products Maximum", "3 Images per Product", "Basic Store Listing"]', 1, 1),
('الباقة الاحترافية', 'پلانى پرۆفیشناڵ', 'Pro Plan', 'pro', 25000.00, 250000.00, 'IQD', 150, 8, 'advanced', 1, 0, 1, 'verified', '["150 Products Maximum", "8 Images per Product", "Advanced Dashboard Analytics", "Verified Badge", "Featured Listings Eligibility"]', 1, 2),
('باقة الأعمال الذهبية', 'پلانى کارى زێڕین', 'Golden Enterprise Plan', 'gold-enterprise', 75000.00, 750000.00, 'IQD', 99999, 15, 'full', 1, 1, 2, 'premium', '["Unlimited Products", "15 Images per Product", "Full Advanced Analytics", "Premium Golden Badge", "Featured Store placement on homepage", "Access to banner advertising campaigns"]', 1, 3);


-- 4. Insert Categories & Subcategories
-- Categories (Electronics, Fashion, Supermarket, Vehicles, Home, Health)
INSERT INTO `categories` (`id`, `parent_id`, `name_ar`, `name_ku`, `name_en`, `slug`, `icon`, `image`, `color`, `description`, `sort_order`, `is_active`) VALUES
(1, NULL, 'الإلكترونيات', 'ئەلەکترۆنیات', 'Electronics', 'electronics', 'bi-laptop', 'electronics.jpg', '#C8922A', 'Mobile phones, laptops, and smart appliances', 1, 1),
(2, NULL, 'الأزياء والملابس', 'جلوبەرگ و مۆدە', 'Fashion & Apparel', 'fashion', 'bi-sunglasses', 'fashion.jpg', '#1A8C8C', 'Traditional Iraqi wear, modern clothes, and accessories', 2, 1),
(3, NULL, 'السوبرماركت', 'سۆپەرمارکێت', 'Supermarket', 'supermarket', 'bi-basket', 'supermarket.jpg', '#1E8A4A', 'Groceries, fresh food, and daily essentials', 3, 1),
(4, NULL, 'السيارات والمركبات', 'ئۆتۆمبێل و گواستنەوە', 'Vehicles & Automotive', 'vehicles', 'bi-car-front', 'vehicles.jpg', '#D97706', 'Cars, motorcycles, and spare parts', 4, 1),
(5, NULL, 'المنزل والمطبخ', 'ماڵ و چێشتخانە', 'Home & Kitchen', 'home-kitchen', 'bi-house', 'home.jpg', '#1A6FA8', 'Furniture, kitchenware, and decor', 5, 1);

-- Subcategories for Electronics
INSERT INTO `categories` (`parent_id`, `name_ar`, `name_ku`, `name_en`, `slug`, `icon`, `image`, `color`, `description`, `sort_order`, `is_active`) VALUES
(1, 'الهواتف الذكية', 'مۆبایلە زیرەکەکان', 'Smartphones', 'smartphones', 'bi-phone', NULL, NULL, 'Smartphones and accessories', 1, 1),
(1, 'أجهزة الكمبيوتر', 'کۆمپیوتەرەکان', 'Computers & Laptops', 'computers', 'bi-pc-display', NULL, NULL, 'Laptops, Desktops, and components', 2, 1),
(1, 'الأجهزة المنزلية الكبيرة', 'ئامێرە گەورەکانی ناوماڵ', 'Home Appliances', 'appliances', 'bi-tv', NULL, NULL, 'Refrigerators, televisions, air conditioners', 3, 1);

-- Subcategories for Fashion
INSERT INTO `categories` (`parent_id`, `name_ar`, `name_ku`, `name_en`, `slug`, `icon`, `image`, `color`, `description`, `sort_order`, `is_active`) VALUES
(2, 'ملابس رجالية', 'جلوبەرگی پیاوان', 'Mens Fashion', 'mens-fashion', 'bi-gender-male', NULL, NULL, 'Shirts, pants, suits, and shoes for men', 1, 1),
(2, 'ملابس نسائية', 'جلوبەرگی ژنان', 'Womens Fashion', 'womens-fashion', 'bi-gender-female', NULL, NULL, 'Dresses, abayas, and accessories for women', 2, 1);

-- Subcategories for Supermarket
INSERT INTO `categories` (`parent_id`, `name_ar`, `name_ku`, `name_en`, `slug`, `icon`, `image`, `color`, `description`, `sort_order`, `is_active`) VALUES
(3, 'المنتجات العراقية الوطنية', 'بەرهەمە نیشتمانییەکانی عێراق', 'Iraqi Local Products', 'iraqi-products', 'bi-award', NULL, NULL, 'Local produce and packaged products', 1, 1);


-- 5. Seed Users
-- Password is 'Password123' for all seeded users (hash is BCRYPT cost 12 of 'Password123')
-- Hash value: $2y$12$fT7z3Zt3Z9c0f99f/vX3UuZ9/m5.xN8B9L6hU.L61r2s3Y5o8B67a (let's use standard bcrypt hash for safety)
SET @pass_hash = '$2y$12$4e963R3v/jZgWz1rIeK5nO97L46wHj7Wq1vO.cKeF14P77Lle4J/O';

INSERT INTO `users` (`uuid`, `full_name`, `username`, `email`, `phone`, `phone_verified`, `email_verified`, `password_hash`, `role`, `status`, `governorate`, `city`, `birth_date`, `gender`, `lang_pref`, `theme_pref`) VALUES
('3a4b5c6d-7e8f-9a0b-1c2d-3e4f5a6b7c8d', 'علي الرافدين', 'superadmin', 'admin@souk.iq', '+9647701234567', 1, 1, @pass_hash, 'super_admin', 'active', 'Baghdad', 'Karrada', '1990-01-01', 'male', 'ar', 'dark'),
('5c6d7e8f-9a0b-1c2d-3e4f-5a6b7c8d9e0f', 'أحمد البصراوي', 'basra_store', 'basra@store.iq', '+9647801234567', 1, 1, @pass_hash, 'store_owner', 'active', 'Basra', 'Ashar', '1985-05-12', 'male', 'ar', 'light'),
('7e8f9a0b-1c2d-3e4f-5a6b-7c8d9e0f1a2b', 'سارا الكردية', 'erbil_shop', 'erbil@store.iq', '+9647501234567', 1, 1, @pass_hash, 'store_owner', 'active', 'Erbil', 'Bakhtiyari', '1993-09-24', 'female', 'ku', 'system'),
('9a0b1c2d-3e4f-5a6b-7c8d-9e0f1a2b3c4d', 'مصطفى محمد', 'mustafa_customer', 'mustafa@gmail.com', '+9647711223344', 1, 1, @pass_hash, 'customer', 'active', 'Baghdad', 'Mansour', '1998-11-30', 'male', 'ar', 'system');

-- Settings
INSERT INTO `user_settings` (`user_id`, `profile_visibility`, `show_email`, `show_phone`, `show_location`, `allow_messages`) VALUES
(1, 'private', 1, 1, 1, 1),
(2, 'public', 1, 1, 1, 1),
(3, 'public', 1, 1, 1, 1),
(4, 'public', 0, 0, 1, 1);


-- 6. Seed Stores
INSERT INTO `stores` (`uuid`, `owner_id`, `name_ar`, `name_ku`, `name_en`, `slug`, `logo`, `banner`, `description_ar`, `description_ku`, `description_en`, `category_id`, `address_line`, `governorate`, `city`, `latitude`, `longitude`, `phone`, `whatsapp`, `email`, `website`, `working_hours`, `status`, `is_verified`, `is_featured`, `trust_score`, `avg_rating`, `reviews_count`, `subscription_id`) VALUES
('e8b0c4d2-f61a-4702-8e01-9238e2d27451', 2, 'معرض البصرة للتكنولوجيا', 'پێشانگای بەسرە بۆ تەکنەلۆجیا', 'Basra Tech Center', 'basra-tech', 'logo1.jpg', 'banner1.jpg', 'أكبر معرض لبيع الهواتف وأجهزة الكمبيوتر المستوردة في محافظة البصرة.', 'گەورەترین پێشانگا بۆ فرۆشتنی مۆبایل و کۆمپیوتەری هاوردەکراو لە پارێزگای بەسرە.', 'The largest showroom for imported phones and computers in Basra governorate.', 1, 'شارع الاستقلال، قرب ساحة أم البروم', 'Basra', 'Ashar', 30.5081, 47.8223, '+9647801234567', '+9647801234567', 'sales@basratech.iq', 'https://basratech.iq', '{"monday": "09:00-21:00", "tuesday": "09:00-21:00", "wednesday": "09:00-21:00", "thursday": "09:00-21:00", "friday": "16:00-22:00", "saturday": "09:00-21:00", "sunday": "09:00-21:00"}', 'active', 1, 1, 4.8, 4.50, 2, 2),
('e9d0a1b2-c3f4-4a5b-6c7d-8e9f0a1b2c3d', 3, 'بوتيك أزياء كردستان', 'پۆشاکی کوردستان', 'Kurdistan Fashion Boutique', 'kurdistan-fashion', 'logo2.jpg', 'banner2.jpg', 'نقدم تشكيلة راقية من الملابس الكردية التقليدية والأزياء العصرية الأنيقة.', 'کۆمەڵێک جلی کوردی ڕەسەن و مۆدەی هاوچەرخ پێشکەش دەکەین.', 'We offer an elegant collection of traditional Kurdish apparel and contemporary fashion.', 2, 'شارع 100، قرب ماجدي مول', 'Erbil', 'Bakhtiyari', 36.1911, 44.0092, '+9647501234567', '+9647501234567', 'hello@kurdstore.com', 'https://kurdstore.com', '{"monday": "10:00-22:00", "tuesday": "10:00-22:00", "wednesday": "10:00-22:00", "thursday": "10:00-22:00", "friday": "14:00-23:00", "saturday": "10:00-22:00", "sunday": "10:00-22:00"}', 'active', 1, 1, 4.9, 5.00, 1, 3);


-- 7. Seed Products
INSERT INTO `products` (`uuid`, `store_id`, `category_id`, `subcategory_id`, `name_ar`, `name_ku`, `name_en`, `slug`, `sku`, `brand`, `description_ar`, `description_ku`, `description_en`, `price`, `discount_price`, `currency`, `price_negotiable`, `images`, `thumbnail`, `specifications`, `warranty_info`, `condition_type`, `stock_status`, `stock_qty`, `status`, `is_featured`, `avg_rating`, `reviews_count`, `tags`, `meta_title`, `meta_description`) VALUES
('c8f1e2d3-a4b5-6c7d-8e9f-0a1b2c3d4e5f', 1, 1, 6, 'آيفون 15 برو ماكس 256 جيجابايت', 'ئایفۆن ١٥ پرۆ ماکس ٢٥٦ گێگابایت', 'iPhone 15 Pro Max 256GB', 'iphone-15-pro-max-256gb', 'IPH15PM-256', 'Apple', 'تيتانيوم طبيعي، سعة 256 جيجابايت، وارد دبي، جديد غير مفتوح مع ضمان سنة.', 'تیتانیۆمی سروشتی، ٢٥٦ گێگابایت، نوێ و نەکراوەتەوە لەگەڵ گەرەنتی یەک ساڵ.', 'Natural Titanium, 256GB storage, Dubai import, brand new sealed with 1-year warranty.', 1650000.00, 1580000.00, 'IQD', 0, '["iphone1.jpg", "iphone2.jpg"]', 'iphone_thumb.jpg', '{"screen": "6.7 inches", "processor": "A17 Pro", "camera": "48MP Main", "battery": "4441 mAh"}', 'سنة واحدة من الوكيل', 'new', 'in_stock', 15, 'active', 1, 4.60, 2, '["iPhone", "Apple", "Smartphones", "Bghdad"]', 'آيفون 15 برو ماكس في البصرة', 'قارن أسعار آيفون 15 برو ماكس في العراق مع معرض البصرة للتكنولوجيا'),
('d9f0e1a2-b3c4-5d6e-7f8a-9b0c1d2e3f4a', 1, 1, 7, 'لابتوب لينوفو ليجن 5 برو', 'لاپتۆپی لێنۆڤۆ لیجن ٥ پرۆ', 'Lenovo Legion 5 Pro Gaming', 'lenovo-legion-5-pro', 'LEN-LEGION5P', 'Lenovo', 'لابتوب ألعاب متطور كور آي 7، كارت شاشة RTX 4060، رام 16 جيجابايت، شاشة 16 بوصة.', 'لاپتۆپێکی بەهێزی یاری، کۆر ئای ٧، کارتی شاشەی ٤٠٦٠، ڕام ١٦ گێگا.', 'High-performance gaming laptop, Core i7, RTX 4060 GPU, 16GB RAM, 16" QHD Screen.', 1450000.00, NULL, 'IQD', 1, '["legion1.jpg"]', 'legion_thumb.jpg', '{"cpu": "Intel Core i7 13700HX", "gpu": "Nvidia RTX 4060 8GB", "ram": "16GB DDR5", "storage": "1TB NVMe SSD"}', 'ضمان محلي 6 أشهر', 'new', 'limited', 3, 'active', 1, 0.00, 0, '["Gaming Laptop", "Lenovo", "RTX 4060"]', 'لينوفو ليجن 5 برو في البصرة', 'سعر لابتوب الألعاب لينوفو ليجن 5 في البصرة، قارن وتواصل مباشرة'),
('e0f1a2b3-c4d5-6e7f-8a9b-0c1d2e3f4a5b', 2, 2, 9, 'ثوب كردي رجالي تقليدي', 'کورتەک و ڕانک', 'Traditional Kurdish Men Costume', 'traditional-kurdish-suit', 'KRD-SUIT-01', 'Handmade', 'خياطة فاخرة، قماش ممتاز مستورد من كوردستان، متوفر بجميع المقاسات والألوان.', 'دوورینی نایاب، قوماشی کوالێتی بەرز هاوردەکراو، بە هەموو قەبارە و ڕەنگێک بەردەستە.', 'Premium stitching, high quality fabric imported, available in all sizes and traditional colors.', 120000.00, 110000.00, 'IQD', 1, '["kurdish1.jpg", "kurdish2.jpg"]', 'kurdish_thumb.jpg', '{"material": "Premium Wool blend", "style": "Traditional Rank o Choxa", "origin": "Erbil Handmade"}', 'بدون ضمان', 'new', 'in_stock', 50, 'active', 1, 5.00, 1, '["Kurdish Apparel", "Traditional Clothes", "Erbil Style"]', 'زي كردي رجالي فاخر في أربيل', 'اشتري كورتك ورانك كردي مميز من بوتيك أزياء كوردستان في أربيل');


-- 8. Seed Subscriptions
INSERT INTO `subscriptions` (`store_id`, `plan_id`, `status`, `starts_at`, `ends_at`, `auto_renew`, `price_paid`, `currency`, `payment_method`, `payment_ref`) VALUES
(1, 2, 'active', '2026-01-01 00:00:00', '2027-01-01 00:00:00', 1, 250000.00, 'IQD', 'Zain Cash', 'TXN-982374982'),
(2, 3, 'active', '2026-02-15 00:00:00', '2027-02-15 00:00:00', 1, 750000.00, 'IQD', 'AsiaHawala', 'TXN-102938102');


-- 9. Seed Reviews
INSERT INTO `reviews` (`user_id`, `target_type`, `target_id`, `rating`, `title`, `body`, `pros`, `cons`, `status`, `helpful_count`, `is_verified_purchase`, `store_reply`, `replied_at`) VALUES
(4, 'product', 1, 5, 'ممتاز جداً ويستحق الشراء', 'المنتج رائع وتعاملي مع المعرض كان ممتازاً، سعرهم أرخص من السوق بكثير.', 'سعر مناسب، جودة أصلية، خدمة سريعة', 'البطارية تنفد بسرعة مع الألعاب الثقيلة', 'approved', 4, 1, 'شكراً لثقتك بنا عزيزي مصطفى، نسعد دائماً بخدمتكم.', '2026-06-02 11:30:00'),
(4, 'product', 1, 4, 'جيد جداً', 'جهاز رائع وتوصيل سريع في البصرة.', 'كاميرا ممتازة وشاشة سريعة الاستجابة', 'الشاحن لا يأتي في العلبة', 'approved', 1, 0, NULL, NULL),
(4, 'product', 3, 5, 'شغل احترافي ومرتب', 'الزي الكردي فد شي راقي والخياطة ممتازة جداً والمقاسات مضبوطة.', 'خياطة دقيقة، ألوان زاهية، تعامل طيب', 'يتأخر الخياط أسبوع تقريباً في التفصيل', 'approved', 2, 1, 'دڵخۆشین بەوەی بەرهەمەکەمان بەدڵت بووە، بەخێر بێیت هەمیشە.', '2026-06-05 15:45:00');


-- 10. Seed Store Followers
INSERT INTO `store_followers` (`user_id`, `store_id`) VALUES
(4, 1),
(4, 2);


-- 11. Seed Notifications
INSERT INTO `notifications` (`user_id`, `type`, `title`, `body`, `data`, `icon`, `link`, `is_read`) VALUES
(4, 'welcome', 'مرحباً بك في سوق.IQ! 🎉', 'يسعدنا انضمامك لأكبر منصة مقارنة أسعار ودليل متاجر في العراق. ابدأ باكتشاف المنتجات الآن.', NULL, 'bi-party-fill', '/', 0),
(2, 'new_review', 'تقييم جديد لمتجرك ⭐️', 'قام العميل مصطفى محمد بإضافة تقييم 5 نجوم لمنتجك (آيفون 15 برو ماكس).', '{"review_id": 1, "product_id": 1}', 'bi-star-fill', '/dashboard/reviews', 0);


-- 12. Seed Advertisements
INSERT INTO `advertisements` (`store_id`, `type`, `title`, `image`, `link`, `target_id`, `target_type`, `position`, `starts_at`, `ends_at`, `impressions`, `clicks`, `status`) VALUES
(1, 'banner_home', 'خصومات الهواتف الذكية مع معرض البصرة', 'ads/banner_basra_tech.jpg', '/store/basra-tech', 1, 'store', 1, '2026-06-01 00:00:00', '2026-07-01 00:00:00', 10523, 432, 'active'),
(2, 'featured_store', 'اكتشف الأزياء الكردية التقليدية والحديثة', 'ads/kurd_fashion.jpg', '/store/kurdistan-fashion', 2, 'store', 2, '2026-06-01 00:00:00', '2026-07-01 00:00:00', 5321, 198, 'active');
