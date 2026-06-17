<?php
/**
 * SOUK.IQ Store Controller
 */

class StoreController extends \Core\Controller {

    // Render public store profile
    public function profile($slug) {
        $storeModel = $this->model('Store');
        $productModel = $this->model('Product');
        $reviewModel = $this->model('Review');

        $store = $storeModel->findBySlug($slug);
        if (!$store) {
            $view = new \Core\View();
            $view->render('errors/404', ['title' => 'المحل غير موجود | 404'], 'main');
            exit;
        }

        // Increment Views
        $storeModel->incrementViews($store->id);

        // Fetch products in this store
        $productModel->query("SELECT * FROM products WHERE store_id = :sid AND status = 'active' AND deleted_at IS NULL ORDER BY created_at DESC");
        $productModel->bind(':sid', $store->id);
        $products = $productModel->resultSet();

        // Fetch reviews
        $reviews = $reviewModel->getReviews('store', $store->id, 20);
        $ratingSummary = $reviewModel->getRatingSummary('store', $store->id);

        $isFollowing = false;
        if (\Core\Auth::check()) {
            $isFollowing = $storeModel->isFollowing(\Core\Auth::user()->id, $store->id);
        }

        // Parse working hours
        $workingHours = [];
        if (!empty($store->working_hours)) {
            $workingHours = json_decode($store->working_hours, true);
        }

        $this->view('store/profile', [
            'title' => $store->name_ar . ' | ' . __('logo'),
            'store' => $store,
            'products' => $products,
            'reviews' => $reviews,
            'ratingSummary' => $ratingSummary,
            'isFollowing' => $isFollowing,
            'workingHours' => $workingHours
        ], 'main');
    }

    // Toggle follow status
    public function toggleFollow() {
        $this->requireRole(['customer', 'store_owner', 'admin', 'super_admin']);
        $storeId = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        
        if ($storeId > 0) {
            $storeModel = $this->model('Store');
            $status = $storeModel->toggleFollow(\Core\Auth::user()->id, $storeId);
            $this->json(['success' => true, 'following' => $status]);
        }
        $this->json(['success' => false, 'error' => 'Invalid store ID'], 400);
    }

    // Add store review
    public function addReview() {
        $this->requireRole(['customer', 'store_owner', 'admin', 'super_admin']);
        $this->validateCSRF();

        $storeId = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
        $body = trim($_POST['body'] ?? '');
        $userId = \Core\Auth::user()->id;

        if ($storeId <= 0 || $rating < 1 || $rating > 5 || empty($body)) {
            \Core\Session::setFlash('error', 'يرجى كتابة نص التقييم وتحديد النجوم.');
            $this->redirect('search');
        }

        $reviewModel = $this->model('Review');
        $reviewId = $reviewModel->create([
            'user_id' => $userId,
            'target_type' => 'store',
            'target_id' => $storeId,
            'rating' => $rating,
            'body' => $body,
            'status' => 'approved' // Auto approved
        ]);

        if ($reviewId) {
            // Recalculate average rating & reviews count
            $reviewModel->query("SELECT AVG(rating) as avg_r, COUNT(id) as cnt FROM reviews WHERE target_type = 'store' AND target_id = :sid AND status = 'approved'");
            $reviewModel->bind(':sid', $storeId);
            $stats = $reviewModel->single();

            $storeModel = $this->model('Store');
            $storeModel->update($storeId, [
                'avg_rating' => round($stats->avg_r ?? 0.0, 2),
                'reviews_count' => intval($stats->cnt ?? 0)
            ]);

            \Core\Session::setFlash('success', 'تم تقييم المتجر بنجاح!');
        } else {
            \Core\Session::setFlash('error', 'فشل في حفظ التقييم.');
        }

        $referer = $_SERVER['HTTP_REFERER'] ?? SITE_URL;
        header("Location: " . $referer);
        exit;
    }

    /* -----------------------------------------------------
       STORE OWNER DASHBOARD PORTAL ACTIONS
       ----------------------------------------------------- */

    // Helper to get active owner store
    private function getOwnerStore() {
        $userId = \Core\Auth::user()->id;
        $storeModel = $this->model('Store');
        $storeModel->query("SELECT * FROM stores WHERE owner_id = :uid AND deleted_at IS NULL LIMIT 1");
        $storeModel->bind(':uid', $userId);
        return $storeModel->single();
    }

    public function dashboard() {
        $this->requireRole('store_owner');
        $store = $this->getOwnerStore();
        
        if (!$store) {
            \Core\Session::setFlash('info', 'يرجى إنشاء وإعداد متجرك أولاً لتفعيل لوحة التحكم.');
            $this->redirect('store-owner/settings');
        }

        $productModel = $this->model('Product');
        $productModel->query("SELECT COUNT(id) as total FROM products WHERE store_id = :sid AND deleted_at IS NULL");
        $productModel->bind(':sid', $store->id);
        $prodStats = $productModel->single();

        $this->view('store-owner/dashboard', [
            'title' => 'لوحة متجرك | ' . __('logo'),
            'store' => $store,
            'totalProducts' => $prodStats->total ?? 0
        ], 'dashboard');
    }

    public function products() {
        $this->requireRole('store_owner');
        $store = $this->getOwnerStore();
        if (!$store) $this->redirect('store-owner/settings');

        $productModel = $this->model('Product');
        $productModel->query("SELECT p.*, c.name_ar as cat_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.store_id = :sid AND p.deleted_at IS NULL ORDER BY p.created_at DESC");
        $productModel->bind(':sid', $store->id);
        $products = $productModel->resultSet();

        $this->view('store-owner/products', [
            'title' => 'إدارة المنتجات | ' . __('logo'),
            'store' => $store,
            'products' => $products
        ], 'dashboard');
    }

    public function createProduct() {
        $this->requireRole('store_owner');
        $store = $this->getOwnerStore();
        if (!$store) $this->redirect('store-owner/settings');

        $categoryModel = $this->model('Category');
        $categories = $categoryModel->getCategoriesWithSub();

        $this->view('store-owner/products-create', [
            'title' => 'إضافة منتج | ' . __('logo'),
            'store' => $store,
            'categories' => $categories
        ], 'dashboard');
    }

    public function storeProduct() {
        $this->requireRole('store_owner');
        $store = $this->getOwnerStore();
        if (!$store) $this->redirect('store-owner/settings');

        $this->validateCSRF();

        $nameAr = trim($_POST['name_ar'] ?? '');
        $nameEn = trim($_POST['name_en'] ?? '');
        $brand = trim($_POST['brand'] ?? '');
        $price = floatval($_POST['price'] ?? 0);
        $discountPrice = !empty($_POST['discount_price']) ? floatval($_POST['discount_price']) : null;
        $categoryId = intval($_POST['category_id'] ?? 0);
        $condition = $_POST['condition'] ?? 'new';
        $stock = $_POST['stock'] ?? 'in_stock';
        $desc = trim($_POST['description_ar'] ?? '');

        if (empty($nameAr) || $price <= 0 || $categoryId <= 0) {
            \Core\Session::setFlash('error', 'يرجى تعبئة الحقول الأساسية وسعر المنتج بشكل صحيح.');
            $this->redirect('store-owner/products/create');
        }

        // Generate UUID & slug
        $uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $nameEn)));
        if (empty($slug)) {
            $slug = 'product-' . mt_rand(1000, 9999);
        }

        // Handle Image upload (Mock or basic upload)
        $thumbnail = 'default_product.jpg';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            // Standard upload mockup
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $fileName = uniqid('prod_') . '.' . $ext;
            $uploadDir = dirname(dirname(__DIR__)) . '/uploads/products/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
                $thumbnail = $fileName;
            }
        }

        $productModel = $this->model('Product');
        $productModel->create([
            'uuid' => $uuid,
            'store_id' => $store->id,
            'category_id' => $categoryId,
            'name_ar' => $nameAr,
            'name_en' => $nameEn,
            'slug' => $slug,
            'brand' => $brand,
            'description_ar' => $desc,
            'price' => $price,
            'discount_price' => $discountPrice,
            'condition_type' => $condition,
            'stock_status' => $stock,
            'thumbnail' => $thumbnail,
            'status' => 'active',
            'images' => json_encode([$thumbnail])
        ]);

        // Increment products_count in store
        $storeModel = $this->model('Store');
        $storeModel->query("UPDATE stores SET products_count = products_count + 1 WHERE id = :sid");
        $storeModel->bind(':sid', $store->id);
        $storeModel->execute();

        \Core\Session::setFlash('success', 'تم إضافة المنتج بنجاح ونشره على المنصة!');
        $this->redirect('store-owner/products');
    }

    public function editProduct($id) {
        $this->requireRole('store_owner');
        $store = $this->getOwnerStore();
        if (!$store) $this->redirect('store-owner/settings');

        $productModel = $this->model('Product');
        $product = $productModel->find($id);

        if (!$product || $product->store_id != $store->id) {
            \Core\Session::setFlash('error', 'المنتج غير موجود أو لا تملك صلاحية تعديله.');
            $this->redirect('store-owner/products');
        }

        $categoryModel = $this->model('Category');
        $categories = $categoryModel->getCategoriesWithSub();

        $this->view('store-owner/products-edit', [
            'title' => 'تعديل منتج | ' . __('logo'),
            'store' => $store,
            'product' => $product,
            'categories' => $categories
        ], 'dashboard');
    }

    public function updateProduct($id) {
        $this->requireRole('store_owner');
        $store = $this->getOwnerStore();
        if (!$store) $this->redirect('store-owner/settings');

        $this->validateCSRF();

        $productModel = $this->model('Product');
        $product = $productModel->find($id);

        if (!$product || $product->store_id != $store->id) {
            \Core\Session::setFlash('error', 'المنتج غير موجود.');
            $this->redirect('store-owner/products');
        }

        $nameAr = trim($_POST['name_ar'] ?? '');
        $nameEn = trim($_POST['name_en'] ?? '');
        $brand = trim($_POST['brand'] ?? '');
        $price = floatval($_POST['price'] ?? 0);
        $discountPrice = !empty($_POST['discount_price']) ? floatval($_POST['discount_price']) : null;
        $categoryId = intval($_POST['category_id'] ?? 0);
        $condition = $_POST['condition'] ?? 'new';
        $stock = $_POST['stock'] ?? 'in_stock';
        $desc = trim($_POST['description_ar'] ?? '');

        if (empty($nameAr) || $price <= 0 || $categoryId <= 0) {
            \Core\Session::setFlash('error', 'يرجى ملء الحقول الأساسية بشكل صحيح.');
            $this->redirect('store-owner/products/edit/' . $id);
        }

        $updateData = [
            'category_id' => $categoryId,
            'name_ar' => $nameAr,
            'name_en' => $nameEn,
            'brand' => $brand,
            'description_ar' => $desc,
            'price' => $price,
            'discount_price' => $discountPrice,
            'condition_type' => $condition,
            'stock_status' => $stock
        ];

        // Image upload handling
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $fileName = uniqid('prod_') . '.' . $ext;
            $uploadDir = dirname(dirname(__DIR__)) . '/uploads/products/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
                $updateData['thumbnail'] = $fileName;
                $updateData['images'] = json_encode([$fileName]);
            }
        }

        $productModel->update($id, $updateData);

        \Core\Session::setFlash('success', 'تم تحديث بيانات المنتج بنجاح!');
        $this->redirect('store-owner/products');
    }

    public function deleteProduct() {
        $this->requireRole('store_owner');
        $store = $this->getOwnerStore();
        if (!$store) $this->redirect('store-owner/settings');

        $this->validateCSRF();
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

        $productModel = $this->model('Product');
        $product = $productModel->find($id);

        if ($product && $product->store_id == $store->id) {
            $productModel->delete($id);

            // Decrement products_count
            $storeModel = $this->model('Store');
            $storeModel->query("UPDATE stores SET products_count = GREATEST(0, CAST(products_count AS SIGNED) - 1) WHERE id = :sid");
            $storeModel->bind(':sid', $store->id);
            $storeModel->execute();

            \Core\Session::setFlash('success', 'تم حذف المنتج بنجاح.');
        } else {
            \Core\Session::setFlash('error', 'المنتج غير موجود.');
        }

        $this->redirect('store-owner/products');
    }

    public function settings() {
        $this->requireRole('store_owner');
        $store = $this->getOwnerStore();

        $categoryModel = $this->model('Category');
        $categories = $categoryModel->getCategoriesWithSub();

        $this->view('store-owner/settings', [
            'title' => 'إعدادات المتجر | ' . __('logo'),
            'store' => $store,
            'categories' => $categories
        ], 'dashboard');
    }

    public function updateStoreSettings() {
        $this->requireRole('store_owner');
        $this->validateCSRF();

        $nameAr = trim($_POST['name_ar'] ?? '');
        $nameEn = trim($_POST['name_en'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $categoryId = intval($_POST['category_id'] ?? 0);
        $gov = $_POST['governorate'] ?? '';
        $city = $_POST['city'] ?? '';
        $address = trim($_POST['address_line'] ?? '');
        $lat = !empty($_POST['latitude']) ? floatval($_POST['latitude']) : 33.3152;
        $lng = !empty($_POST['longitude']) ? floatval($_POST['longitude']) : 44.3661;
        $phone = trim($_POST['phone'] ?? '');
        $whatsapp = trim($_POST['whatsapp'] ?? '');
        $website = trim($_POST['website'] ?? '');
        $descAr = trim($_POST['description_ar'] ?? '');

        if (empty($nameAr) || empty($slug) || $categoryId <= 0 || empty($gov)) {
            \Core\Session::setFlash('error', 'يرجى ملء جميع الحقول المطلوبة بنجمة.');
            $this->redirect('store-owner/settings');
        }

        $storeModel = $this->model('Store');
        $store = $this->getOwnerStore();

        $storeData = [
            'name_ar' => $nameAr,
            'name_en' => $nameEn,
            'slug' => $slug,
            'category_id' => $categoryId,
            'governorate' => $gov,
            'city' => $city,
            'address_line' => $address,
            'latitude' => $lat,
            'longitude' => $lng,
            'phone' => $phone,
            'whatsapp' => $whatsapp,
            'website' => $website,
            'description_ar' => $descAr,
            'status' => $store ? $store->status : 'pending' // new store status is pending
        ];

        // Handle logo upload
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
            $fileName = uniqid('logo_') . '.' . $ext;
            $uploadDir = dirname(dirname(__DIR__)) . '/uploads/logos/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploadDir . $fileName)) {
                $storeData['logo'] = $fileName;
            }
        }

        if ($store) {
            // Update
            $storeModel->update($store->id, $storeData);
            \Core\Session::setFlash('success', 'تم تحديث إعدادات متجرك بنجاح!');
        } else {
            // Insert
            $storeData['uuid'] = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
            $storeData['owner_id'] = \Core\Auth::user()->id;
            $storeModel->create($storeData);
            \Core\Session::setFlash('success', 'تم إنشاء ملف المتجر بنجاح! متجرك الآن قيد الانتظار لمراجعة الإدارة.');
        }

        $this->redirect('store-owner/settings');
    }

    public function reviews() {
        $this->requireRole('store_owner');
        $store = $this->getOwnerStore();
        if (!$store) $this->redirect('store-owner/settings');

        $reviewModel = $this->model('Review');
        // Get reviews for products of this store
        $reviewModel->query("SELECT r.*, u.full_name as user_name, p.name_ar as prod_name 
                             FROM reviews r 
                             INNER JOIN users u ON r.user_id = u.id 
                             INNER JOIN products p ON r.target_id = p.id AND r.target_type = 'product'
                             WHERE p.store_id = :sid 
                             ORDER BY r.created_at DESC");
        $reviewModel->bind(':sid', $store->id);
        $productReviews = $reviewModel->resultSet();

        // Get reviews directly for the store
        $reviewModel->query("SELECT r.*, u.full_name as user_name 
                             FROM reviews r 
                             INNER JOIN users u ON r.user_id = u.id
                             WHERE r.target_type = 'store' AND r.target_id = :sid 
                             ORDER BY r.created_at DESC");
        $reviewModel->bind(':sid', $store->id);
        $storeReviews = $reviewModel->resultSet();

        $this->view('store-owner/reviews', [
            'title' => 'تقييمات المحل | ' . __('logo'),
            'store' => $store,
            'productReviews' => $productReviews,
            'storeReviews' => $storeReviews
        ], 'dashboard');
    }

    public function replyReview() {
        $this->requireRole('store_owner');
        $this->validateCSRF();
        $reviewId = isset($_POST['review_id']) ? intval($_POST['review_id']) : 0;
        $reply = trim($_POST['reply'] ?? '');

        if ($reviewId <= 0 || empty($reply)) {
            \Core\Session::setFlash('error', 'لا يمكن إرسال رد فارغ.');
            $this->redirect('store-owner/reviews');
        }

        $reviewModel = $this->model('Review');
        $reviewModel->query("UPDATE reviews SET store_reply = :reply, replied_at = NOW() WHERE id = :id");
        $reviewModel->bind(':id', $reviewId);
        $reviewModel->bind(':reply', $reply);
        $reviewModel->execute();

        \Core\Session::setFlash('success', 'تم إرسال ردك على التقييم بنجاح!');
        $this->redirect('store-owner/reviews');
    }

    public function staff() {
        $this->requireRole('store_owner');
        $store = $this->getOwnerStore();
        if (!$store) $this->redirect('store-owner/settings');

        // Fetch staff members
        $storeModel = $this->model('Store');
        $storeModel->query("SELECT ss.*, u.full_name, u.email, u.avatar 
                             FROM store_staff ss 
                             INNER JOIN users u ON ss.user_id = u.id 
                             WHERE ss.store_id = :sid");
        $storeModel->bind(':sid', $store->id);
        $staff = $storeModel->resultSet();

        $this->view('store-owner/staff', [
            'title' => 'إدارة طاقم المتجر | ' . __('logo'),
            'store' => $store,
            'staff' => $staff
        ], 'dashboard');
    }

    public function inviteStaff() {
        $this->requireRole('store_owner');
        $store = $this->getOwnerStore();
        if (!$store) $this->redirect('store-owner/settings');

        $this->validateCSRF();
        $email = trim($_POST['email'] ?? '');
        $role = $_POST['role'] ?? 'viewer';

        if (empty($email)) {
            \Core\Session::setFlash('error', 'يرجى إدخال البريد الإلكتروني للموظف.');
            $this->redirect('store-owner/staff');
        }

        $userModel = $this->model('User');
        $invitee = $userModel->findByEmail($email);

        if (!$invitee) {
            \Core\Session::setFlash('error', 'المستخدم غير مسجل بالمنصة. يجب عليه التسجيل أولاً.');
            $this->redirect('store-owner/staff');
        }

        // Insert staff member
        try {
            $userModel->query("INSERT INTO store_staff (store_id, user_id, role, invited_by) VALUES (:sid, :uid, :role, :ib)");
            $userModel->bind(':sid', $store->id);
            $userModel->bind(':uid', $invitee->id);
            $userModel->bind(':role', $role);
            $userModel->bind(':ib', \Core\Auth::user()->id);
            $userModel->execute();

            // Notify user
            $notifModel = $this->model('Notification');
            $notifModel->create([
                'user_id' => $invitee->id,
                'type' => 'staff_invite',
                'title' => 'دعوة للانضمام إلى متجر 💼',
                'body' => 'تمت دعوتك للعمل كـ (' . $role . ') في متجر: ' . $store->name_ar,
                'icon' => 'bi-briefcase-fill',
                'link' => '/dashboard',
                'is_read' => 0
            ]);

            \Core\Session::setFlash('success', 'تمت إضافة الموظف لطاقم العمل بنجاح!');
        } catch (\PDOException $e) {
            \Core\Session::setFlash('error', 'الموظف مضاف بالفعل أو حدث خطأ أثناء الإضافة.');
        }

        $this->redirect('store-owner/staff');
    }

    public function deleteStaff() {
        $this->requireRole('store_owner');
        $store = $this->getOwnerStore();
        if (!$store) $this->redirect('store-owner/settings');

        $this->validateCSRF();
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

        $storeModel = $this->model('Store');
        $storeModel->query("DELETE FROM store_staff WHERE id = :id AND store_id = :sid");
        $storeModel->bind(':id', $id);
        $storeModel->bind(':sid', $store->id);
        $storeModel->execute();

        \Core\Session::setFlash('success', 'تم إزالة الموظف بنجاح.');
        $this->redirect('store-owner/staff');
    }
}
