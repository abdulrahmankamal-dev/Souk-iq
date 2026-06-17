<?php
/**
 * SOUK.IQ User Model
 */

namespace Models;

use Core\Model;

class User extends Model {
    protected $table = 'users';
    protected $useSoftDeletes = true;

    // Find user by email or username
    public function findByEmailOrUsername($identifier) {
        $this->query("SELECT * FROM users WHERE (email = :id OR username = :id) AND deleted_at IS NULL LIMIT 1");
        $this->bind(':id', $identifier);
        return $this->single();
    }

    // Find by Username
    public function findByUsername($username) {
        $this->query("SELECT * FROM users WHERE username = :username AND deleted_at IS NULL LIMIT 1");
        $this->bind(':username', $username);
        return $this->single();
    }

    // Find by Email
    public function findByEmail($email) {
        $this->query("SELECT * FROM users WHERE email = :email AND deleted_at IS NULL LIMIT 1");
        $this->bind(':email', $email);
        return $this->single();
    }

    // Update login statistics
    public function updateLoginStats($userId, $ipAddress) {
        // Log to login history
        $this->query("INSERT INTO login_history (user_id, ip_address, status) VALUES (:uid, :ip, 'success')");
        $this->bind(':uid', $userId);
        $this->bind(':ip', $ipAddress);
        $this->execute();

        // Update user record
        $this->query("UPDATE users SET login_count = login_count + 1, last_login_at = NOW(), last_login_ip = :ip WHERE id = :uid");
        $this->bind(':uid', $userId);
        $this->bind(':ip', $ipAddress);
        return $this->execute();
    }

    // Get user settings
    public function getSettings($userId) {
        $this->query("SELECT * FROM user_settings WHERE user_id = :uid LIMIT 1");
        $this->bind(':uid', $userId);
        $settings = $this->single();
        
        // If settings record doesn't exist, create it with defaults
        if (!$settings) {
            $this->query("INSERT INTO user_settings (user_id) VALUES (:uid)");
            $this->bind(':uid', $userId);
            $this->execute();
            
            $this->query("SELECT * FROM user_settings WHERE user_id = :uid LIMIT 1");
            $this->bind(':uid', $userId);
            $settings = $this->single();
        }
        
        return $settings;
    }

    // Update user settings
    public function updateSettings($userId, $data) {
        $fields = [];
        foreach ($data as $key => $val) {
            $fields[] = "{$key} = :{$key}";
        }
        
        $sql = "UPDATE user_settings SET " . implode(', ', $fields) . " WHERE user_id = :uid";
        $this->query($sql);
        $this->bind(':uid', $userId);
        foreach ($data as $key => $val) {
            $this->bind(':' . $key, $val);
        }
        
        return $this->execute();
    }
}
