<?php
/**
 * SOUK.IQ Administrator Controller
 */

class AdminController extends \Core\Controller {

    public function __construct() {
        $this->requireRole(['admin', 'super_admin']);
    }

    // Dashboard Overview
    public function index() {
        $model = $this->model('User');

        // Fetch counts
        $model->query("SELECT COUNT(id) as total FROM users");
        $usersCount = $model->single()->total ?? 0;

        $model->query("SELECT COUNT(id) as total FROM stores");
        $storesCount = $model->single()->total ?? 0;

        $model->query("SELECT COUNT(id) as total FROM products");
        $productsCount = $model->single()->total ?? 0;

        $model->query("SELECT COUNT(id) as total FROM reports WHERE status = 'pending'");
        $pendingReports = $model->single()->total ?? 0;

        // Fetch latest users
        $model->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 5");
        $latestUsers = $model->resultSet();

        // Fetch pending stores
        $model->query("SELECT s.*, u.full_name as owner_name FROM stores s INNER JOIN users u ON s.owner_id = u.id WHERE s.status = 'pending' ORDER BY s.created_at DESC");
        $pendingStores = $model->resultSet();

        $this->view('admin/dashboard', [
            'title' => 'لوحة الإدارة | ' . __('logo'),
            'usersCount' => $usersCount,
            'storesCount' => $storesCount,
            'productsCount' => $productsCount,
            'pendingReports' => $pendingReports,
            'latestUsers' => $latestUsers,
            'pendingStores' => $pendingStores
        ], 'dashboard');
    }

    // List all users
    public function users() {
        $userModel = $this->model('User');
        $userModel->query("SELECT * FROM users ORDER BY created_at DESC");
        $users = $userModel->resultSet();

        $this->view('admin/users', [
            'title' => 'إدارة المستخدمين | ' . __('logo'),
            'users' => $users
        ], 'dashboard');
    }

    // Change User Status
    public function changeUserStatus() {
        $this->validateCSRF();
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $status = $_POST['status'] ?? 'active';

        if ($id > 0 && in_array($status, ['active', 'inactive', 'suspended', 'banned'])) {
            $userModel = $this->model('User');
            $userModel->update($id, ['status' => $status]);
            \Core\Session::setFlash('success', 'تم تعديل حالة المستخدم بنجاح.');
        } else {
            \Core\Session::setFlash('error', 'بيانات غير صالحة.');
        }
        $this->redirect('admin/users');
    }

    // List stores
    public function stores() {
        $storeModel = $this->model('Store');
        $storeModel->query("SELECT s.*, u.full_name as owner_name FROM stores s INNER JOIN users u ON s.owner_id = u.id ORDER BY s.created_at DESC");
        $stores = $storeModel->resultSet();

        $this->view('admin/stores', [
            'title' => 'إدارة المتاجر | ' . __('logo'),
            'stores' => $stores
        ], 'dashboard');
    }

    // Approve store
    public function approveStore() {
        $this->validateCSRF();
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

        if ($id > 0) {
            $storeModel = $this->model('Store');
            $storeModel->update($id, ['status' => 'active', 'is_verified' => 1, 'verified_at' => date('Y-m-d H:i:s')]);
            
            // Notify store owner
            $store = $storeModel->find($id);
            $notifModel = $this->model('Notification');
            $notifModel->create([
                'user_id' => $store->owner_id,
                'type' => 'store_approved',
                'title' => 'تهانينا! تم قبول وتوثيق متجرك 🛍️',
                'body' => 'تمت الموافقة على متجرك (' . $store->name_ar . ') وهو الآن نشط للجميع.',
                'icon' => 'bi-shop',
                'link' => '/store-owner/dashboard',
                'is_read' => 0
            ]);

            \Core\Session::setFlash('success', 'تم الموافقة على المتجر وتوثيقه بنجاح.');
        }
        $this->redirect('admin/stores');
    }

    // Reject store
    public function rejectStore() {
        $this->validateCSRF();
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $reason = trim($_POST['rejection_reason'] ?? '');

        if ($id > 0) {
            $storeModel = $this->model('Store');
            $storeModel->update($id, ['status' => 'rejected', 'rejection_reason' => $reason]);
            
            // Notify owner
            $store = $storeModel->find($id);
            $notifModel = $this->model('Notification');
            $notifModel->create([
                'user_id' => $store->owner_id,
                'type' => 'store_rejected',
                'title' => 'تحديث بشأن طلب المتجر ⚠️',
                'body' => 'تم رفض طلب إنشاء متجرك. السبب: ' . $reason,
                'icon' => 'bi-exclamation-triangle-fill',
                'link' => '/store-owner/settings',
                'is_read' => 0
            ]);

            \Core\Session::setFlash('success', 'تم رفض المتجر وإعلام المالك.');
        }
        $this->redirect('admin/stores');
    }

    // Suspend store
    public function suspendStore() {
        $this->validateCSRF();
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

        if ($id > 0) {
            $storeModel = $this->model('Store');
            $store = $storeModel->find($id);
            $newStatus = ($store->status === 'suspended') ? 'active' : 'suspended';
            $storeModel->update($id, ['status' => $newStatus]);
            
            \Core\Session::setFlash('success', 'تم تغيير حالة المتجر بنجاح.');
        }
        $this->redirect('admin/stores');
    }

    // List products
    public function products() {
        $productModel = $this->model('Product');
        $productModel->query("SELECT p.*, s.name_ar as store_name FROM products p INNER JOIN stores s ON p.store_id = s.id ORDER BY p.created_at DESC");
        $products = $productModel->resultSet();

        $this->view('admin/products', [
            'title' => 'مراقبة المنتجات | ' . __('logo'),
            'products' => $products
        ], 'dashboard');
    }

    // Change product status
    public function changeProductStatus() {
        $this->validateCSRF();
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $status = $_POST['status'] ?? 'active';

        if ($id > 0 && in_array($status, ['active', 'pending', 'rejected', 'archived'])) {
            $productModel = $this->model('Product');
            $productModel->update($id, ['status' => $status]);
            \Core\Session::setFlash('success', 'تم تحديث حالة المنتج بنجاح.');
        } else {
            \Core\Session::setFlash('error', 'بيانات غير صالحة.');
        }
        $this->redirect('admin/products');
    }

    // Categories management
    public function categories() {
        $categoryModel = $this->model('Category');
        $categories = $categoryModel->findAll('sort_order ASC, name_ar ASC');

        $this->view('admin/categories', [
            'title' => 'إدارة الفئات | ' . __('logo'),
            'categories' => $categories
        ], 'dashboard');
    }

    public function createCategory() {
        $this->validateCSRF();
        $nameAr = trim($_POST['name_ar'] ?? '');
        $nameKu = trim($_POST['name_ku'] ?? '');
        $nameEn = trim($_POST['name_en'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $icon = trim($_POST['icon'] ?? '');
        $parentId = !empty($_POST['parent_id']) ? intval($_POST['parent_id']) : null;

        if (empty($nameAr) || empty($slug)) {
            \Core\Session::setFlash('error', 'الاسم بالعربية والـ Slug مطلوبان.');
            $this->redirect('admin/categories');
        }

        $categoryModel = $this->model('Category');
        $categoryModel->create([
            'parent_id' => $parentId,
            'name_ar' => $nameAr,
            'name_ku' => $nameKu,
            'name_en' => $nameEn,
            'slug' => $slug,
            'icon' => $icon,
            'is_active' => 1
        ]);

        \Core\Session::setFlash('success', 'تم إنشاء الفئة بنجاح!');
        $this->redirect('admin/categories');
    }

    public function editCategory() {
        $this->validateCSRF();
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $nameAr = trim($_POST['name_ar'] ?? '');
        $nameKu = trim($_POST['name_ku'] ?? '');
        $nameEn = trim($_POST['name_en'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $icon = trim($_POST['icon'] ?? '');

        if ($id > 0 && !empty($nameAr)) {
            $categoryModel = $this->model('Category');
            $categoryModel->update($id, [
                'name_ar' => $nameAr,
                'name_ku' => $nameKu,
                'name_en' => $nameEn,
                'slug' => $slug,
                'icon' => $icon
            ]);
            \Core\Session::setFlash('success', 'تم تعديل الفئة بنجاح.');
        } else {
            \Core\Session::setFlash('error', 'الاسم والـ ID مطلوبين.');
        }
        $this->redirect('admin/categories');
    }

    public function deleteCategory() {
        $this->validateCSRF();
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

        if ($id > 0) {
            $categoryModel = $this->model('Category');
            $categoryModel->delete($id);
            \Core\Session::setFlash('success', 'تم حذف الفئة.');
        }
        $this->redirect('admin/categories');
    }

    // Advertisements management
    public function advertisements() {
        $model = $this->model('User');
        $model->query("SELECT a.*, s.name_ar as store_name FROM advertisements a LEFT JOIN stores s ON a.store_id = s.id ORDER BY a.created_at DESC");
        $ads = $model->resultSet();

        // Fetch active stores for ad allocation dropdown
        $model->query("SELECT id, name_ar FROM stores WHERE status = 'active' ORDER BY name_ar ASC");
        $stores = $model->resultSet();

        $this->view('admin/advertisements', [
            'title' => 'إدارة الإعلانات والبنرات | ' . __('logo'),
            'ads' => $ads,
            'stores' => $stores
        ], 'dashboard');
    }

    public function createAd() {
        $this->validateCSRF();
        $storeId = !empty($_POST['store_id']) ? intval($_POST['store_id']) : null;
        $type = $_POST['type'] ?? 'banner_home';
        $title = trim($_POST['title'] ?? '');
        $link = trim($_POST['link'] ?? '');
        $position = intval($_POST['position'] ?? 1);

        if (empty($title)) {
            \Core\Session::setFlash('error', 'عنوان الإعلان مطلوب.');
            $this->redirect('admin/advertisements');
        }

        // Handle image upload
        $image = 'default_ad.jpg';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $fileName = uniqid('ad_') . '.' . $ext;
            $uploadDir = dirname(dirname(__DIR__)) . '/uploads/ads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
                $image = 'ads/' . $fileName;
            }
        }

        $model = $this->model('User');
        $model->query("INSERT INTO advertisements (store_id, type, title, image, link, position, status, starts_at, ends_at) VALUES (:sid, :type, :title, :img, :link, :pos, 'active', NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY))");
        $model->bind(':sid', $storeId);
        $model->bind(':type', $type);
        $model->bind(':title', $title);
        $model->bind(':img', $image);
        $model->bind(':link', $link);
        $model->bind(':pos', $position);
        $model->execute();

        \Core\Session::setFlash('success', 'تم إنشاء الإعلان وبدء حملته بنجاح!');
        $this->redirect('admin/advertisements');
    }

    public function changeAdStatus() {
        $this->validateCSRF();
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $status = $_POST['status'] ?? 'active';

        if ($id > 0 && in_array($status, ['pending', 'active', 'paused', 'expired'])) {
            $model = $this->model('User');
            $model->query("UPDATE advertisements SET status = :status WHERE id = :id");
            $model->bind(':id', $id);
            $model->bind(':status', $status);
            $model->execute();
            \Core\Session::setFlash('success', 'تم تعديل حالة حملة الإعلان.');
        }
        $this->redirect('admin/advertisements');
    }

    // Reports panel
    public function reports() {
        $model = $this->model('User');
        $model->query("SELECT r.*, u.full_name as reporter_name FROM reports r INNER JOIN users u ON r.reporter_id = u.id ORDER BY r.created_at DESC");
        $reports = $model->resultSet();

        $this->view('admin/reports', [
            'title' => 'الشكاوى والبلاغات | ' . __('logo'),
            'reports' => $reports
        ], 'dashboard');
    }

    public function resolveReport() {
        $this->validateCSRF();
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $note = trim($_POST['resolution_note'] ?? '');
        $status = $_POST['status'] ?? 'resolved';

        if ($id > 0 && in_array($status, ['resolved', 'dismissed'])) {
            $model = $this->model('User');
            $model->query("UPDATE reports SET status = :status, resolved_by = :by, resolution_note = :note, resolved_at = NOW() WHERE id = :id");
            $model->bind(':id', $id);
            $model->bind(':status', $status);
            $model->bind(':by', \Core\Auth::user()->id);
            $model->bind(':note', $note);
            $model->execute();

            \Core\Session::setFlash('success', 'تم تسجيل قرار معالجة البلاغ بنجاح.');
        }
        $this->redirect('admin/reports');
    }
}
