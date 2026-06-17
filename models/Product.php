<?php
/**
 * SOUK.IQ Product Model
 */

namespace Models;

use Core\Model;

class Product extends Model {
    protected $table = 'products';
    protected $useSoftDeletes = true;

    // Get featured products for homepage
    public function getFeaturedProducts($limit = 12) {
        $this->query("SELECT p.*, s.name_ar as store_name_ar, s.name_ku as store_name_ku, s.name_en as store_name_en, s.slug as store_slug, s.is_verified as store_verified 
                      FROM products p 
                      INNER JOIN stores s ON p.store_id = s.id 
                      WHERE p.is_featured = 1 AND p.status = 'active' AND p.deleted_at IS NULL 
                      ORDER BY p.views_count DESC, p.created_at DESC 
                      LIMIT :limit");
        $this->bind(':limit', $limit);
        return $this->resultSet();
    }

    // Get recently added products for homepage
    public function getRecentlyAdded($limit = 8, $offset = 0) {
        $this->query("SELECT p.*, s.name_ar as store_name_ar, s.name_ku as store_name_ku, s.name_en as store_name_en, s.slug as store_slug 
                      FROM products p 
                      INNER JOIN stores s ON p.store_id = s.id 
                      WHERE p.status = 'active' AND p.deleted_at IS NULL 
                      ORDER BY p.created_at DESC 
                      LIMIT :limit OFFSET :offset");
        $this->bind(':limit', $limit);
        $this->bind(':offset', $offset);
        return $this->resultSet();
    }

    // Find product details by store and product slug
    public function findBySlug($storeSlug, $productSlug) {
        $this->query("SELECT p.*, 
                             s.name_ar as store_name_ar, s.name_ku as store_name_ku, s.name_en as store_name_en, 
                             s.slug as store_slug, s.logo as store_logo, s.is_verified as store_verified, 
                             s.avg_rating as store_rating, s.phone as store_phone, s.whatsapp as store_whatsapp, s.website as store_website,
                             s.governorate as store_governorate, s.address_line as store_address_line,
                             c.name_ar as category_name_ar, c.name_ku as category_name_ku, c.name_en as category_name_en,
                             sc.name_ar as subcategory_name_ar, sc.name_ku as subcategory_name_ku, sc.name_en as subcategory_name_en
                      FROM products p 
                      INNER JOIN stores s ON p.store_id = s.id 
                      LEFT JOIN categories c ON p.category_id = c.id 
                      LEFT JOIN categories sc ON p.subcategory_id = sc.id 
                      WHERE s.slug = :store_slug AND p.slug = :product_slug AND p.deleted_at IS NULL LIMIT 1");
        $this->bind(':store_slug', $storeSlug);
        $this->bind(':product_slug', $productSlug);
        return $this->single();
    }

    // Increment view counter
    public function incrementViews($id) {
        $this->query("UPDATE products SET views_count = views_count + 1 WHERE id = :id");
        $this->bind(':id', $id);
        return $this->execute();
    }

    // Advanced search engine with full filters
    public function search($filters = [], $orderBy = 'relevance', $limit = 12, $offset = 0) {
        $sql = "SELECT p.*, s.name_ar as store_name_ar, s.name_ku as store_name_ku, s.name_en as store_name_en, s.slug as store_slug, s.is_verified as store_verified, s.governorate as store_governorate
                FROM products p 
                INNER JOIN stores s ON p.store_id = s.id 
                WHERE p.status = 'active' AND p.deleted_at IS NULL";
        
        $params = [];

        // Fulltext keyword search
        if (!empty($filters['q'])) {
            // Check if there is full-text index support, fallback to LIKE queries
            $sql .= " AND (p.name_ar LIKE :q OR p.name_ku LIKE :q OR p.name_en LIKE :q OR p.brand LIKE :q OR p.description_ar LIKE :q)";
            $params[':q'] = '%' . $filters['q'] . '%';
        }

        // Category filter
        if (!empty($filters['category'])) {
            $sql .= " AND (p.category_id = :cat OR p.subcategory_id = :cat)";
            $params[':cat'] = $filters['category'];
        }

        // Governorates filter (array or single)
        if (!empty($filters['governorates'])) {
            $govList = (array)$filters['governorates'];
            $govPlaceholders = [];
            foreach ($govList as $idx => $gov) {
                $placeholder = ":gov_" . $idx;
                $govPlaceholders[] = $placeholder;
                $params[$placeholder] = $gov;
            }
            $sql .= " AND s.governorate IN (" . implode(', ', $govPlaceholders) . ")";
        }

        // Price range
        if (isset($filters['min_price']) && is_numeric($filters['min_price'])) {
            $sql .= " AND p.price >= :min_p";
            $params[':min_p'] = $filters['min_price'];
        }
        if (isset($filters['max_price']) && is_numeric($filters['max_price'])) {
            $sql .= " AND p.price <= :max_p";
            $params[':max_p'] = $filters['max_price'];
        }

        // Store rating
        if (!empty($filters['rating']) && is_numeric($filters['rating'])) {
            $sql .= " AND s.avg_rating >= :rating";
            $params[':rating'] = $filters['rating'];
        }

        // Condition (new, used, refurbished)
        if (!empty($filters['condition'])) {
            $sql .= " AND p.condition_type = :cond";
            $params[':cond'] = $filters['condition'];
        }

        // Availability (in_stock, out_of_stock, limited)
        if (!empty($filters['stock_status'])) {
            $sql .= " AND p.stock_status = :stock";
            $params[':stock'] = $filters['stock_status'];
        }

        // Verified Stores only
        if (!empty($filters['verified_only'])) {
            $sql .= " AND s.is_verified = 1";
        }

        // Ordering
        switch ($orderBy) {
            case 'newest':
                $sql .= " ORDER BY p.created_at DESC";
                break;
            case 'price_low':
                $sql .= " ORDER BY p.price ASC";
                break;
            case 'price_high':
                $sql .= " ORDER BY p.price DESC";
                break;
            case 'views':
                $sql .= " ORDER BY p.views_count DESC";
                break;
            case 'rating':
                $sql .= " ORDER BY p.avg_rating DESC";
                break;
            case 'relevance':
            default:
                $sql .= " ORDER BY p.is_featured DESC, p.created_at DESC";
                break;
        }

        $sql .= " LIMIT :limit OFFSET :offset";

        $this->query($sql);
        foreach ($params as $key => $val) {
            $this->bind($key, $val);
        }
        
        $this->bind(':limit', $limit);
        $this->bind(':offset', $offset);
        
        return $this->resultSet();
    }

    // Get search results count
    public function searchCount($filters = []) {
        $sql = "SELECT COUNT(p.id) as total 
                FROM products p 
                INNER JOIN stores s ON p.store_id = s.id 
                WHERE p.status = 'active' AND p.deleted_at IS NULL";
        
        $params = [];

        if (!empty($filters['q'])) {
            $sql .= " AND (p.name_ar LIKE :q OR p.name_ku LIKE :q OR p.name_en LIKE :q OR p.brand LIKE :q OR p.description_ar LIKE :q)";
            $params[':q'] = '%' . $filters['q'] . '%';
        }

        if (!empty($filters['category'])) {
            $sql .= " AND (p.category_id = :cat OR p.subcategory_id = :cat)";
            $params[':cat'] = $filters['category'];
        }

        if (!empty($filters['governorates'])) {
            $govList = (array)$filters['governorates'];
            $govPlaceholders = [];
            foreach ($govList as $idx => $gov) {
                $placeholder = ":gov_" . $idx;
                $govPlaceholders[] = $placeholder;
                $params[$placeholder] = $gov;
            }
            $sql .= " AND s.governorate IN (" . implode(', ', $govPlaceholders) . ")";
        }

        if (isset($filters['min_price']) && is_numeric($filters['min_price'])) {
            $sql .= " AND p.price >= :min_p";
            $params[':min_p'] = $filters['min_price'];
        }
        if (isset($filters['max_price']) && is_numeric($filters['max_price'])) {
            $sql .= " AND p.price <= :max_p";
            $params[':max_p'] = $filters['max_price'];
        }

        if (!empty($filters['rating']) && is_numeric($filters['rating'])) {
            $sql .= " AND s.avg_rating >= :rating";
            $params[':rating'] = $filters['rating'];
        }

        if (!empty($filters['condition'])) {
            $sql .= " AND p.condition_type = :cond";
            $params[':cond'] = $filters['condition'];
        }

        if (!empty($filters['stock_status'])) {
            $sql .= " AND p.stock_status = :stock";
            $params[':stock'] = $filters['stock_status'];
        }

        if (!empty($filters['verified_only'])) {
            $sql .= " AND s.is_verified = 1";
        }

        $this->query($sql);
        foreach ($params as $key => $val) {
            $this->bind($key, $val);
        }

        $res = $this->single();
        return $res ? (int)$res->total : 0;
    }

    // Get related products (same category)
    public function getRelatedProducts($categoryId, $excludeId, $limit = 6) {
        $this->query("SELECT p.*, s.name_ar as store_name_ar, s.name_ku as store_name_ku, s.name_en as store_name_en, s.slug as store_slug 
                      FROM products p 
                      INNER JOIN stores s ON p.store_id = s.id 
                      WHERE p.category_id = :cat AND p.id != :eid AND p.status = 'active' AND p.deleted_at IS NULL 
                      ORDER BY p.views_count DESC 
                      LIMIT :limit");
        $this->bind(':cat', $categoryId);
        $this->bind(':eid', $excludeId);
        $this->bind(':limit', $limit);
        return $this->resultSet();
    }

    // Get other products from the same store
    public function getOtherStoreProducts($storeId, $excludeId, $limit = 4) {
        $this->query("SELECT p.*, s.name_ar as store_name_ar, s.name_ku as store_name_ku, s.name_en as store_name_en, s.slug as store_slug 
                      FROM products p 
                      INNER JOIN stores s ON p.store_id = s.id 
                      WHERE p.store_id = :sid AND p.id != :eid AND p.status = 'active' AND p.deleted_at IS NULL 
                      ORDER BY p.created_at DESC 
                      LIMIT :limit");
        $this->bind(':sid', $storeId);
        $this->bind(':eid', $excludeId);
        $this->bind(':limit', $limit);
        return $this->resultSet();
    }

    // Check if user favorited a product
    public function isFavorite($userId, $productId) {
        $this->query("SELECT id FROM favorites WHERE user_id = :uid AND type = 'product' AND target_id = :pid LIMIT 1");
        $this->bind(':uid', $userId);
        $this->bind(':pid', $productId);
        return $this->single() ? true : false;
    }

    // Toggle favorite status
    public function toggleFavorite($userId, $productId) {
        if ($this->isFavorite($userId, $productId)) {
            $this->query("DELETE FROM favorites WHERE user_id = :uid AND type = 'product' AND target_id = :pid");
            $this->bind(':uid', $userId);
            $this->bind(':pid', $productId);
            $this->execute();

            // Decrement product favorites_count
            $this->query("UPDATE products SET favorites_count = GREATEST(0, CAST(favorites_count AS SIGNED) - 1) WHERE id = :pid");
            $this->bind(':pid', $productId);
            $this->execute();
            return false; // Removed
        } else {
            $this->query("INSERT INTO favorites (user_id, type, target_id) VALUES (:uid, 'product', :pid)");
            $this->bind(':uid', $userId);
            $this->bind(':pid', $productId);
            $this->execute();

            // Increment product favorites_count
            $this->query("UPDATE products SET favorites_count = favorites_count + 1 WHERE id = :pid");
            $this->bind(':pid', $productId);
            $this->execute();
            return true; // Added
        }
    }

    // Get favorite products for user
    public function getFavoritesByUser($userId) {
        $this->query("SELECT p.*, s.name_ar as store_name_ar, s.name_ku as store_name_ku, s.name_en as store_name_en, s.slug as store_slug 
                      FROM favorites f 
                      INNER JOIN products p ON f.target_id = p.id 
                      INNER JOIN stores s ON p.store_id = s.id 
                      WHERE f.user_id = :uid AND f.type = 'product' AND p.deleted_at IS NULL 
                      ORDER BY f.created_at DESC");
        $this->bind(':uid', $userId);
        return $this->resultSet();
    }
}
