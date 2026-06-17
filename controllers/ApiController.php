<?php
/**
 * SOUK.IQ API v1 Controller
 */

class ApiController extends \Core\Controller {

    // Autocomplete Search suggestion API
    public function search() {
        $q = trim($_GET['q'] ?? '');
        if (empty($q) || strlen($q) < 2) {
            $this->json(['products' => [], 'stores' => []]);
        }

        $productModel = $this->model('Product');
        
        // Fetch products matching query
        $productModel->query("SELECT p.id, p.name_ar, p.name_en, p.price, p.thumbnail, p.slug as product_slug, s.slug as store_slug 
                              FROM products p 
                              INNER JOIN stores s ON p.store_id = s.id 
                              WHERE (p.name_ar LIKE :q OR p.name_en LIKE :q OR p.brand LIKE :q) AND p.status = 'active' AND p.deleted_at IS NULL 
                              LIMIT 6");
        $productModel->bind(':q', '%' . $q . '%');
        $products = $productModel->resultSet();

        // Fetch stores matching query
        $storeModel = $this->model('Store');
        $storeModel->query("SELECT id, name_ar, name_en, slug, logo, avg_rating 
                            FROM stores 
                            WHERE (name_ar LIKE :q OR name_en LIKE :q) AND status = 'active' AND deleted_at IS NULL 
                            LIMIT 3");
        $storeModel->bind(':q', '%' . $q . '%');
        $stores = $storeModel->resultSet();

        $this->json([
            'products' => $products,
            'stores' => $stores
        ]);
    }

    // List notifications API
    public function notifications() {
        if (!\Core\Auth::check()) {
            $this->json(['error' => 'Unauthorized'], 401);
        }

        $userId = \Core\Auth::user()->id;
        $notifModel = $this->model('Notification');
        
        $notifModel->query("SELECT * FROM notifications WHERE user_id = :uid ORDER BY created_at DESC LIMIT 10");
        $notifModel->bind(':uid', $userId);
        $notifications = $notifModel->resultSet();

        $this->json($notifications);
    }

    // Mark notifications as read API
    public function markNotificationsRead() {
        if (!\Core\Auth::check()) {
            $this->json(['error' => 'Unauthorized'], 401);
        }

        $userId = \Core\Auth::user()->id;
        $notifModel = $this->model('Notification');

        $notifModel->query("UPDATE notifications SET is_read = 1, read_at = NOW() WHERE user_id = :uid AND is_read = 0");
        $notifModel->bind(':uid', $userId);
        $notifModel->execute();

        $this->json(['success' => true]);
    }

    // Toggle Favorite API
    public function toggleFavorite() {
        if (!\Core\Auth::check()) {
            $this->json(['error' => 'Unauthorized'], 401);
        }

        $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        if ($productId <= 0) {
            $this->json(['error' => 'Invalid product ID'], 400);
        }

        $productModel = $this->model('Product');
        $status = $productModel->toggleFavorite(\Core\Auth::user()->id, $productId);

        $this->json([
            'success' => true,
            'favorited' => $status
        ]);
    }
}
