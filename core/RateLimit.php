<?php
/**
 * SOUK.IQ Core Rate Limiter
 */

namespace Core;

class RateLimit {
    // Check if request exceeds rate limit
    public static function check($action, $maxAttempts = 5, $windowSeconds = 3600) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $identifier = md5($ip . '_' . $action);
        
        $db = new Model();
        
        // Clean up old rates
        $db->query("DELETE FROM rate_limits WHERE window_start < DATE_SUB(NOW(), INTERVAL :sec SECOND) AND blocked_until IS NULL");
        $db->bind(':sec', $windowSeconds);
        $db->execute();
        
        // Fetch current rate limit record
        $db->query("SELECT * FROM rate_limits WHERE identifier = :id AND action = :act LIMIT 1");
        $db->bind(':id', $identifier);
        $db->bind(':act', $action);
        $limit = $db->single();
        
        $now = date('Y-m-d H:i:s');
        
        if ($limit) {
            // Check if currently blocked
            if ($limit->blocked_until !== null && strtotime($limit->blocked_until) > time()) {
                return false; // Blocked
            }
            
            // Check if window expired
            if (strtotime($limit->window_start) + $windowSeconds < time()) {
                // Reset window
                $db->query("UPDATE rate_limits SET attempts = 1, window_start = NOW(), blocked_until = NULL WHERE id = :lid");
                $db->bind(':lid', $limit->id);
                $db->execute();
                return true;
            }
            
            $attempts = $limit->attempts + 1;
            $blockedUntil = null;
            
            if ($attempts >= $maxAttempts) {
                // Block for window length
                $blockedUntil = date('Y-m-d H:i:s', time() + $windowSeconds);
            }
            
            $db->query("UPDATE rate_limits SET attempts = :att, blocked_until = :blk WHERE id = :lid");
            $db->bind(':att', $attempts);
            $db->bind(':blk', $blockedUntil);
            $db->bind(':lid', $limit->id);
            $db->execute();
            
            return ($blockedUntil === null);
        } else {
            // Insert new rate limit record
            $db->query("INSERT INTO rate_limits (identifier, action, attempts, window_start) VALUES (:id, :act, 1, NOW())");
            $db->bind(':id', $identifier);
            $db->bind(':act', $action);
            $db->execute();
            return true;
        }
    }

    // Get time left before unblock
    public static function getCooldownSeconds($action) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $identifier = md5($ip . '_' . $action);
        
        $db = new Model();
        $db->query("SELECT blocked_until FROM rate_limits WHERE identifier = :id AND action = :act LIMIT 1");
        $db->bind(':id', $identifier);
        $db->bind(':act', $action);
        $limit = $db->single();
        
        if ($limit && $limit->blocked_until) {
            $diff = strtotime($limit->blocked_until) - time();
            return $diff > 0 ? $diff : 0;
        }
        return 0;
    }
}
