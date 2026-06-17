<?php
/**
 * SOUK.IQ Favorite Model
 */

namespace Models;

use Core\Model;

class Favorite extends Model {
    protected $table = 'favorites';
    
    // Custom mapping of favorites logic
    public function getFavorites($userId, $type) {
        $this->query("SELECT * FROM favorites WHERE user_id = :uid AND type = :type ORDER BY created_at DESC");
        $this->bind(':uid', $userId);
        $this->bind(':type', $type);
        return $this->resultSet();
    }
}
