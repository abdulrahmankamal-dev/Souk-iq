<?php
/**
 * SOUK.IQ Configuration File
 */

// Error reporting toggled by environment
define('APP_ENV', 'development'); // 'development' or 'production'

if (APP_ENV === 'development') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

// Database Credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'souk_iq');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHAR', 'utf8mb4');

// App Settings
define('SITE_NAME', 'سوق.IQ — دليلك للتسوق والمقارنة في العراق');
define('SITE_URL', 'http://localhost/souk-iq'); // Adjust base URL as needed
define('DEFAULT_LANG', 'ar');

// Security & Sessions
define('SESSION_LIFETIME', 2592000); // 30 days in seconds
define('JWT_SECRET', 'd3c2a1e9_souk_iq_secure_jwt_token_key_for_iraq_platform');
define('CSRF_EXPIRY', 7200); // 2 hours

// SMTP Settings (for Mailer helper)
define('SMTP_HOST', 'smtp.mailtrap.io');
define('SMTP_PORT', 2525);
define('SMTP_USER', '');
define('SMTP_PASS', '');
define('SMTP_FROM_EMAIL', 'noreply@souk.iq');
define('SMTP_FROM_NAME', 'Souk.IQ Platform');

// Cache Configuration
define('CACHE_ENABLED', true);
define('CACHE_PATH', dirname(__DIR__) . '/cache');
define('CACHE_LIFETIME', 3600); // 1 hour
