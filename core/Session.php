<?php
/**
 * SOUK.IQ Core Session Manager
 */

namespace Core;

class Session {
    public static function init() {
        if (session_status() === PHP_SESSION_NONE) {
            // Set secure cookie policies
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_only_cookies', 1);
            ini_set('session.cookie_samesite', 'Lax');
            
            // Adjust session lifetime
            if (defined('SESSION_LIFETIME')) {
                ini_set('session.gc_maxlifetime', SESSION_LIFETIME);
            }
            
            session_start();
        }

        // Set default language
        if (!isset($_SESSION['lang'])) {
            $_SESSION['lang'] = 'ar';
        }

        // Prevent Session Hijacking
        self::checkSessionValidity();
    }

    protected static function checkSessionValidity() {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';

        if (!isset($_SESSION['created_at'])) {
            $_SESSION['created_at'] = time();
            $_SESSION['user_ip'] = $ip;
            $_SESSION['user_agent'] = $ua;
        } else {
            // Regenerate session ID every 30 minutes to reduce hijacking risks
            if (time() - $_SESSION['created_at'] > 1800) {
                session_regenerate_id(true);
                $_SESSION['created_at'] = time();
            }

            // Check if IP or UA changed significantly (basic check)
            if ($_SESSION['user_agent'] !== $ua) {
                self::destroy();
                self::init();
            }
        }
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }

    public static function has($key) {
        return isset($_SESSION[$key]);
    }

    public static function remove($key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    // Set flash message (persists only for next request)
    public static function setFlash($key, $message) {
        $_SESSION['flash'][$key] = $message;
    }

    // Get flash message
    public static function getFlash($key) {
        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        return null;
    }

    // Clear session flash buffer
    public static function clearFlash() {
        $_SESSION['flash'] = [];
    }

    public static function destroy() {
        if (session_status() !== PHP_SESSION_NONE) {
            session_unset();
            session_destroy();
        }
    }
}
