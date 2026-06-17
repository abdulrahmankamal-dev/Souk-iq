<?php
/**
 * SOUK.IQ Notification Model
 */

namespace Models;

use Core\Model;

class Notification extends Model {
    protected $table = 'notifications';

    // Get unread notification count
    public function getUnreadCount($userId) {
        $this->query("SELECT COUNT(id) as total FROM notifications WHERE user_id = :uid AND is_read = 0");
        $this->bind(':uid', $userId);
        $res = $this->single();
        return $res ? (int)$res->total : 0;
    }

    // Get latest notifications
    public function getLatest($userId, $limit = 5) {
        $this->query("SELECT * FROM notifications WHERE user_id = :uid ORDER BY created_at DESC LIMIT :limit");
        $this->bind(':uid', $userId);
        $this->bind(':limit', $limit);
        return $this->resultSet();
    }

    // Mark all user notifications as read
    public function markAllAsRead($userId) {
        $this->query("UPDATE notifications SET is_read = 1, read_at = NOW() WHERE user_id = :uid AND is_read = 0");
        $this->bind(':uid', $userId);
        return $this->execute();
    }
}
