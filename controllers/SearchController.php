<?php
/**
 * SOUK.IQ Search Controller
 */

class SearchController extends \Core\Controller {

    // Advanced Search Page
    public function index() {
        $productModel = $this->model('Product');
        $categoryModel = $this->model('Category');

        // Extract filters from GET
        $q = trim($_GET['q'] ?? '');
        $categoryId = isset($_GET['category']) ? intval($_GET['category']) : null;
        $governorates = isset($_GET['gov']) ? (array)$_GET['gov'] : [];
        $condition = $_GET['condition'] ?? null;
        $stockStatus = $_GET['stock'] ?? null;
        $minPrice = isset($_GET['min_price']) && $_GET['min_price'] !== '' ? floatval($_GET['min_price']) : null;
        $maxPrice = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? floatval($_GET['max_price']) : null;
        $verifiedOnly = isset($_GET['verified']) ? intval($_GET['verified']) : null;
        
        $sort = $_GET['sort'] ?? 'relevance';
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 12;
        $offset = ($page - 1) * $limit;

        $filters = [
            'q' => $q,
            'category' => $categoryId,
            'governorates' => $governorates,
            'condition' => $condition,
            'stock_status' => $stockStatus,
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
            'verified_only' => $verifiedOnly
        ];

        // Perform Search queries
        $products = $productModel->search($filters, $sort, $limit, $offset);
        $totalProducts = $productModel->searchCount($filters);
        $totalPages = ceil($totalProducts / $limit);

        // Fetch categories for sidebar selection list
        $categories = $categoryModel->getCategoriesWithSub();

        // Selected category detail
        $selectedCategory = null;
        if ($categoryId) {
            $selectedCategory = $categoryModel->find($categoryId);
        }

        // Log search query for analytics if keyword search is run
        if (!empty($q) && $page === 1) {
            $userId = \Core\Auth::check() ? \Core\Auth::user()->id : null;
            $ip = $_SERVER['REMOTE_ADDR'] ?? '';
            $productModel->query("INSERT INTO search_logs (user_id, query, results_count, ip_address) VALUES (:uid, :q, :cnt, :ip)");
            $productModel->bind(':uid', $userId);
            $productModel->bind(':q', $q);
            $productModel->bind(':cnt', $totalProducts);
            $productModel->bind(':ip', $ip);
            $productModel->execute();
        }

        $this->view('search', [
            'title' => 'البحث والمقارنة | ' . __('logo'),
            'products' => $products,
            'totalProducts' => $totalProducts,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
            'filters' => $filters,
            'sort' => $sort
        ], 'main');
    }
}
