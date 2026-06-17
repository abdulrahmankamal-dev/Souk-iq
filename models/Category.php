<?php
/**
 * SOUK.IQ Category Model
 */

namespace Models;

use Core\Model;

class Category extends Model {
    protected $table = 'categories';

    // Get categories with their children subcategories
    public function getCategoriesWithSub() {
        // Fetch all categories
        $this->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY sort_order ASC, name_ar ASC");
        $all = $this->resultSet();

        $parents = [];
        $subs = [];

        foreach ($all as $cat) {
            if ($cat->parent_id === null) {
                $cat->subcategories = [];
                $parents[$cat->id] = $cat;
            } else {
                $subs[] = $cat;
            }
        }

        foreach ($subs as $sub) {
            if (isset($parents[$sub->parent_id])) {
                $parents[$sub->parent_id]->subcategories[] = $sub;
            }
        }

        return array_values($parents);
    }

    // Get top 10 categories for the navigation
    public function getTopCategories($limit = 10) {
        $this->query("SELECT c.*, COUNT(p.id) as product_count 
                      FROM categories c 
                      LEFT JOIN products p ON c.id = p.category_id AND p.status = 'active'
                      WHERE c.parent_id IS NULL AND c.is_active = 1 
                      GROUP BY c.id 
                      ORDER BY c.sort_order ASC, product_count DESC 
                      LIMIT :limit");
        $this->bind(':limit', $limit);
        return $this->resultSet();
    }

    // Find category by slug
    public function findBySlug($slug) {
        $this->query("SELECT * FROM categories WHERE slug = :slug AND is_active = 1 LIMIT 1");
        $this->bind(':slug', $slug);
        return $this->single();
    }
}
