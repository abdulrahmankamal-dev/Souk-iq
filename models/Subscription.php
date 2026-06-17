<?php
/**
 * SOUK.IQ Subscription Model
 */

namespace Models;

use Core\Model;

class Subscription extends Model {
    protected $table = 'subscriptions';

    // Get active subscription for a store
    public function getActiveSubscription($storeId) {
        $this->query("SELECT s.*, p.name_ar as plan_name_ar, p.name_ku as plan_name_ku, p.name_en as plan_name_en, 
                             p.max_products, p.max_images, p.analytics_level, p.can_feature, p.can_advertise, p.badge_type
                      FROM subscriptions s 
                      INNER JOIN subscription_plans p ON s.plan_id = p.id 
                      WHERE s.store_id = :sid AND s.status = 'active' AND s.starts_at <= NOW() AND (s.ends_at IS NULL OR s.ends_at > NOW()) 
                      LIMIT 1");
        $this->bind(':sid', $storeId);
        return $this->single();
    }

    // Get all subscription plans
    public function getPlans() {
        $this->query("SELECT * FROM subscription_plans WHERE is_active = 1 ORDER BY sort_order ASC");
        return $this->resultSet();
    }
}
