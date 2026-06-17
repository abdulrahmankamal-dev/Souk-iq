<?php
/**
 * SOUK.IQ Core CSRF Protection
 */

namespace Core;

class CSRF {
    // Generate a secure token and store in session
    public static function generate() {
        if (!Session::has('csrf_token') || !Session::has('csrf_token_time')) {
            self::refresh();
        } else {
            // Check expiry
            if (time() - Session::get('csrf_token_time') > CSRF_EXPIRY) {
                self::refresh();
            }
        }
        return Session::get('csrf_token');
    }

    // Refresh token value
    public static function refresh() {
        $token = bin2hex(random_bytes(32));
        Session::set('csrf_token', $token);
        Session::set('csrf_token_time', time());
    }

    // Verify token submitted
    public static function verify($token) {
        if (!Session::has('csrf_token')) {
            return false;
        }
        
        $sessionToken = Session::get('csrf_token');
        $sessionTime = Session::get('csrf_token_time');
        
        // Check expiry
        if (time() - $sessionTime > CSRF_EXPIRY) {
            self::refresh();
            return false;
        }

        // Constant time comparison to prevent timing attacks
        return hash_equals($sessionToken, $token);
    }

    // Generate hidden HTML input tag
    public static function field() {
        $token = self::generate();
        return '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }
}
