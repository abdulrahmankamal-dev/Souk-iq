<?php
/**
 * SOUK.IQ Store Model
 */

namespace Models;

use Core\Model;

class Store extends Model {
    protected $table = 'stores';
    protected $useSoftDeletes = true;

    // Get featured stores for homepage
    public function getFeaturedStores($limit = 8) {
        $this->query("SELECT s.*, c.name_ar as category_name_ar, c.name_ku as category_name_ku, c.name_en as category_name_en 
                      FROM stores s 
                      LEFT JOIN categories c ON s.category_id = c.id 
                      WHERE s.is_featured = 1 AND s.status = 'active' AND s.deleted_at IS NULL 
                      ORDER BY s.avg_rating DESC, s.followers_count DESC 
                      LIMIT :limit");
        $this->bind(':limit', $limit);
        return $this->resultSet();
    }

    // Find store by its unique URL slug
    public function findBySlug($slug) {
        $this->query("SELECT s.*, c.name_ar as category_name_ar, c.name_ku as category_name_ku, c.name_en as category_name_en, u.full_name as owner_name 
                      FROM stores s 
                      LEFT JOIN categories c ON s.category_id = c.id 
                      LEFT JOIN users u ON s.owner_id = u.id 
                      WHERE s.slug = :slug AND s.deleted_at IS NULL LIMIT 1");
        $this->bind(':slug', $slug);
        return $this->single();
    }

    // Increment store profile view count
    public function incrementViews($id) {
        $this->query("UPDATE stores SET views_count = views_count + 1 WHERE id = :id");
        $this->bind(':id', $id);
        return $this->execute();
    }

    // Check if user is following this store
    public function isFollowing($userId, $storeId) {
        $this->query("SELECT id FROM store_followers WHERE user_id = :uid AND store_id = :sid LIMIT 1");
        $this->bind(':uid', $userId);
        $this->bind(':sid', $storeId);
        return $this->single() ? true : false;
    }

    // Toggle follow status
    public function toggleFollow($userId, $storeId) {
        if ($this->isFollowing($userId, $storeId)) {
            // Unfollow
            $this->query("DELETE FROM store_followers WHERE user_id = :uid AND store_id = :sid");
            $this->bind(':uid', $userId);
            $this->bind(':sid', $storeId);
            $this->execute();
            
            // Decrement follower count
            $this->query("UPDATE stores SET followers_count = GREATEST(0, CAST(followers_count AS SIGNED) - 1) WHERE id = :sid");
            $this->bind(':sid', $storeId);
            $this->execute();
            return false; // Not following now
        } else {
            // Follow
            $this->query("INSERT INTO store_followers (user_id, store_id) VALUES (:uid, :sid)");
            $this->bind(':uid', $userId);
            $this->bind(':sid', $storeId);
            $this->execute();
            
            // Increment follower count
            $this->query("UPDATE stores SET followers_count = followers_count + 1 WHERE id = :sid");
            $this->bind(':sid', $storeId);
            $this->execute();
            return true; // Following now
        }
    }

    // Get followed stores for dashboard
    public function getFollowedStoresByUser($userId) {
        $this->query("SELECT s.* 
                      FROM store_followers sf 
                      INNER JOIN stores s ON sf.store_id = s.id 
                      WHERE sf.user_id = :uid AND s.deleted_at IS NULL 
                      ORDER BY sf.created_at DESC");
        $this->bind(':uid', $userId);
        return $this->resultSet();
    }
}
