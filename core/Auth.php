<?php
/**
 * SOUK.IQ Core Auth Class
 */

namespace Core;

class Auth {
    protected static $cachedUser = null;

    // Check if user is logged in
    public static function check() {
        return Session::has('user_id');
    }

    // Get current logged-in user object
    public static function user() {
        if (!self::check()) {
            return null;
        }

        if (self::$cachedUser === null) {
            $userId = Session::get('user_id');
            // Instantiate User model directly
            require_once dirname(__DIR__) . '/models/User.php';
            $userModel = new \Models\User();
            self::$cachedUser = $userModel->find($userId);
        }

        return self::$cachedUser;
    }

    // Get user role
    public static function getRole() {
        $user = self::user();
        return $user ? $user->role : 'visitor';
    }

    // Perform Login
    public static function login($emailOrUsername, $password, $rememberMe = false) {
        require_once dirname(__DIR__) . '/models/User.php';
        $userModel = new \Models\User();
        
        $user = $userModel->findByEmailOrUsername($emailOrUsername);
        
        if ($user) {
            // Check status
            if ($user->status === 'suspended' || $user->status === 'banned') {
                Session::setFlash('error', 'الحساب موقوف. يرجى التواصل مع الدعم الفني.');
                return 'suspended';
            }

            // Verify Password
            if (password_verify($password, $user->password_hash)) {
                // Check 2FA
                if ($user->two_fa_enabled) {
                    Session::set('temp_2fa_user_id', $user->id);
                    return '2fa_required';
                }

                // Log user in
                self::completeLogin($user, $rememberMe);
                return 'success';
            }
        }
        
        return 'failed';
    }

    public static function completeLogin($user, $rememberMe = false) {
        Session::set('user_id', $user->id);
        Session::set('user_role', $user->role);
        Session::set('user_name', $user->full_name);
        if (!empty($user->lang_pref)) {
            Session::set('lang', $user->lang_pref);
        }
        
        // Update user stats
        require_once dirname(__DIR__) . '/models/User.php';
        $userModel = new \Models\User();
        $userModel->updateLoginStats($user->id, $_SERVER['REMOTE_ADDR'] ?? '');

        // Handle remember me (extends session lifetime)
        if ($rememberMe) {
            $cookieLifetime = time() + (86400 * 30); // 30 days
            setcookie(session_name(), session_id(), $cookieLifetime, '/', '', false, true);
        }
    }

    // Perform Logout
    public static function logout() {
        self::$cachedUser = null;
        Session::destroy();
        
        // Remove session cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
    }

    // JWT Token Generation (for API Authentication)
    public static function generateJWT($userId) {
        $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
        $payload = json_encode([
            'sub' => $userId,
            'iat' => time(),
            'exp' => time() + (3600 * 24 * 7) // 1 week
        ]);
        
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, JWT_SECRET, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        
        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    // JWT Validation Helper
    public static function validateJWT($token) {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false;
        }
        
        list($header, $payload, $signature) = $parts;
        
        $expectedSignature = hash_hmac('sha256', $header . "." . $payload, JWT_SECRET, true);
        $expectedBase64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($expectedSignature));
        
        if (!hash_equals($expectedBase64UrlSignature, $signature)) {
            return false;
        }
        
        $payloadData = json_decode(base64_decode($payload));
        if ($payloadData->exp < time()) {
            return false; // Expired
        }
        
        return $payloadData->sub; // Return User ID
    }
}
