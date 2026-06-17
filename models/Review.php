<?php
/**
 * SOUK.IQ Review Model
 */

namespace Models;

use Core\Model;

class Review extends Model {
    protected $table = 'reviews';

    // Get approved reviews for product or store target
    public function getReviews($targetType, $targetId, $limit = 10, $offset = 0) {
        $this->query("SELECT r.*, u.full_name as user_name, u.avatar as user_avatar 
                      FROM reviews r 
                      INNER JOIN users u ON r.user_id = u.id 
                      WHERE r.target_type = :type AND r.target_id = :tid AND r.status = 'approved' 
                      ORDER BY r.created_at DESC 
                      LIMIT :limit OFFSET :offset");
        $this->bind(':type', $targetType);
        $this->bind(':tid', $targetId);
        $this->bind(':limit', $limit);
        $this->bind(':offset', $offset);
        return $this->resultSet();
    }

    // Get average rating and stars percentage breakdown
    public function getRatingSummary($targetType, $targetId) {
        $this->query("SELECT rating, COUNT(id) as count 
                      FROM reviews 
                      WHERE target_type = :type AND target_id = :tid AND status = 'approved' 
                      GROUP BY rating");
        $this->bind(':type', $targetType);
        $this->bind(':tid', $targetId);
        $rows = $this->resultSet();

        $summary = [
            'average' => 0.0,
            'total' => 0,
            'stars' => [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0],
            'percentages' => [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0]
        ];

        $sum = 0;
        foreach ($rows as $row) {
            $summary['stars'][(int)$row->rating] = (int)$row->count;
            $summary['total'] += (int)$row->count;
            $sum += ((int)$row->rating * (int)$row->count);
        }

        if ($summary['total'] > 0) {
            $summary['average'] = round($sum / $summary['total'], 2);
            foreach ($summary['stars'] as $star => $count) {
                $summary['percentages'][$star] = round(($count / $summary['total']) * 100);
            }
        }

        return $summary;
    }

    // Get reviews written by a customer
    public function getReviewsByUser($userId) {
        $this->query("SELECT r.*, 
                             p.name_ar as product_name_ar, p.name_ku as product_name_ku, p.name_en as product_name_en, p.slug as product_slug,
                             s.name_ar as store_name_ar, s.name_ku as store_name_ku, s.name_en as store_name_en, s.slug as store_slug
                      FROM reviews r 
                      LEFT JOIN products p ON r.target_type = 'product' AND r.target_id = p.id 
                      LEFT JOIN stores s ON (r.target_type = 'store' AND r.target_id = s.id) OR (r.target_type = 'product' AND p.store_id = s.id)
                      WHERE r.user_id = :uid 
                      ORDER BY r.created_at DESC");
        $this->bind(':uid', $userId);
        return $this->resultSet();
    }
}
