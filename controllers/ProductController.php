<?php
/**
 * SOUK.IQ Product Controller
 */

class ProductController extends \Core\Controller {

    // Render Product Detail page
    public function detail($storeSlug, $productSlug) {
        $productModel = $this->model('Product');
        $reviewModel = $this->model('Review');

        $product = $productModel->findBySlug($storeSlug, $productSlug);

        if (!$product) {
            // Render 404 page if product doesn't exist
            $view = new \Core\View();
            $view->render('errors/404', ['title' => 'المنتج غير موجود | 404'], 'main');
            exit;
        }

        // Increment Views
        $productModel->incrementViews($product->id);

        // Fetch product reviews
        $reviews = $reviewModel->getReviews('product', $product->id, 20);
        $ratingSummary = $reviewModel->getRatingSummary('product', $product->id);

        // Fetch related products
        $relatedProducts = $productModel->getRelatedProducts($product->category_id, $product->id, 4);

        // Fetch other products from same store
        $otherStoreProducts = $productModel->getOtherStoreProducts($product->store_id, $product->id, 4);

        // Check if current user favorited this
        $isFavorite = false;
        if (\Core\Auth::check()) {
            $isFavorite = $productModel->isFavorite(\Core\Auth::user()->id, $product->id);
        }

        // Parse images JSON
        $imagesList = [];
        if (!empty($product->images)) {
            $imagesList = json_decode($product->images, true);
        }
        if (empty($imagesList) && !empty($product->thumbnail)) {
            $imagesList[] = $product->thumbnail;
        }

        // Parse specifications JSON
        $specificationsList = [];
        if (!empty($product->specifications)) {
            $specificationsList = json_decode($product->specifications, true);
        }

        // Parse tags JSON
        $tagsList = [];
        if (!empty($product->tags)) {
            $tagsList = json_decode($product->tags, true);
        }

        $this->view('product/detail', [
            'title' => $product->name_ar . ' | ' . $product->store_name_ar . ' | ' . __('logo'),
            'product' => $product,
            'reviews' => $reviews,
            'ratingSummary' => $ratingSummary,
            'relatedProducts' => $relatedProducts,
            'otherStoreProducts' => $otherStoreProducts,
            'isFavorite' => $isFavorite,
            'imagesList' => $imagesList,
            'specificationsList' => $specificationsList,
            'tagsList' => $tagsList
        ], 'main');
    }

    // Submit Product Review
    public function addReview() {
        $this->requireRole(['customer', 'store_owner', 'admin', 'super_admin']);
        $this->validateCSRF();

        $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
        $title = trim($_POST['title'] ?? '');
        $body = trim($_POST['body'] ?? '');
        $pros = trim($_POST['pros'] ?? '');
        $cons = trim($_POST['cons'] ?? '');
        $userId = \Core\Auth::user()->id;

        if ($productId <= 0 || $rating < 1 || $rating > 5 || empty($body)) {
            \Core\Session::setFlash('error', 'يرجى كتابة نص المراجعة واختيار التقييم بالنجوم بشكل صحيح.');
            $this->redirect('search');
        }

        $reviewModel = $this->model('Review');
        
        // Save review
        $reviewId = $reviewModel->create([
            'user_id' => $userId,
            'target_type' => 'product',
            'target_id' => $productId,
            'rating' => $rating,
            'title' => empty($title) ? null : $title,
            'body' => $body,
            'pros' => empty($pros) ? null : $pros,
            'cons' => empty($cons) ? null : $cons,
            'status' => 'approved', // Auto approved for demo
            'helpful_count' => 0,
            'is_verified_purchase' => 1
        ]);

        if ($reviewId) {
            // Recalculate average rating & reviews count for product
            $reviewModel->query("SELECT AVG(rating) as avg_r, COUNT(id) as cnt FROM reviews WHERE target_type = 'product' AND target_id = :pid AND status = 'approved'");
            $reviewModel->bind(':pid', $productId);
            $stats = $reviewModel->single();

            $productModel = $this->model('Product');
            $productModel->update($productId, [
                'avg_rating' => round($stats->avg_r ?? 0.0, 2),
                'reviews_count' => intval($stats->cnt ?? 0)
            ]);

            // Notify store owner
            $product = $productModel->find($productId);
            if ($product) {
                $storeModel = $this->model('Store');
                $store = $storeModel->find($product->store_id);
                if ($store) {
                    $notifModel = $this->model('Notification');
                    $notifModel->create([
                        'user_id' => $store->owner_id,
                        'type' => 'new_review',
                        'title' => 'تقييم جديد لمنتجك ⭐️',
                        'body' => 'قام العميل ' . \Core\Auth::user()->full_name . ' بإضافة تقييم لمشروعك: ' . $product->name_ar,
                        'icon' => 'bi-star-fill',
                        'link' => '/store-owner/reviews',
                        'is_read' => 0
                    ]);
                }
            }

            \Core\Session::setFlash('success', 'تم إضافة تقييمك بنجاح! شكرًا لك.');
        } else {
            \Core\Session::setFlash('error', 'حدث خطأ أثناء حفظ التقييم. حاول مجدداً.');
        }

        // Redirect back
        $referer = $_SERVER['HTTP_REFERER'] ?? SITE_URL;
        header("Location: " . $referer);
        exit;
    }
}
