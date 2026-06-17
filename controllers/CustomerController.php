<?php
/**
 * SOUK.IQ Customer Dashboard Controller
 */

class CustomerController extends \Core\Controller {

    public function __construct() {
        // Require standard customer logged in role
        $this->requireRole(['customer', 'store_owner', 'admin', 'super_admin']);
    }

    // Customer Home Overview
    public function index() {
        $user = \Core\Auth::user();
        
        $productModel = $this->model('Product');
        $storeModel = $this->model('Store');
        $reviewModel = $this->model('Review');

        // Fetch counts
        $favoritesCount = count($productModel->getFavoritesByUser($user->id));
        $followedCount = count($storeModel->getFollowedStoresByUser($user->id));
        $reviewsCount = count($reviewModel->getReviewsByUser($user->id));

        // Calculate profile completion percentage
        $fields = ['full_name', 'email', 'phone', 'governorate', 'city', 'birth_date', 'gender', 'avatar'];
        $filled = 0;
        foreach ($fields as $f) {
            if (!empty($user->$f)) $filled++;
        }
        $profilePercent = round(($filled / count($fields)) * 100);

        // Fetch latest reviews
        $latestReviews = $reviewModel->getReviewsByUser($user->id);

        $this->view('customer/dashboard', [
            'title' => __('dashboard') . ' | ' . __('logo'),
            'user' => $user,
            'favoritesCount' => $favoritesCount,
            'followedCount' => $followedCount,
            'reviewsCount' => $reviewsCount,
            'profilePercent' => $profilePercent,
            'latestReviews' => array_slice($latestReviews, 0, 3)
        ], 'dashboard');
    }

    // Favorites page
    public function favorites() {
        $user = \Core\Auth::user();
        $productModel = $this->model('Product');
        $favorites = $productModel->getFavoritesByUser($user->id);

        $this->view('customer/favorites', [
            'title' => __('my_favorites') . ' | ' . __('logo'),
            'favorites' => $favorites
        ], 'dashboard');
    }

    // Toggle Favorite Action (AJAX or normal POST)
    public function toggleFavorite() {
        $this->validateCSRF();
        $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        
        if ($productId > 0) {
            $productModel = $this->model('Product');
            $status = $productModel->toggleFavorite(\Core\Auth::user()->id, $productId);
            
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                $this->json(['success' => true, 'favorited' => $status]);
            } else {
                \Core\Session::setFlash('success', $status ? 'تمت الإضافة إلى المفضلة' : 'تمت الإزالة من المفضلة');
                header("Location: " . ($_SERVER['HTTP_REFERER'] ?? SITE_URL));
                exit;
            }
        }
        $this->json(['success' => false, 'error' => 'Invalid ID'], 400);
    }

    // Reviews management page
    public function reviews() {
        $user = \Core\Auth::user();
        $reviewModel = $this->model('Review');
        $reviews = $reviewModel->getReviewsByUser($user->id);

        $this->view('customer/reviews', [
            'title' => __('my_reviews') . ' | ' . __('logo'),
            'reviews' => $reviews
        ], 'dashboard');
    }

    // Delete review
    public function deleteReview() {
        $this->validateCSRF();
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $userId = \Core\Auth::user()->id;

        $reviewModel = $this->model('Review');
        $review = $reviewModel->find($id);

        if ($review && $review->user_id == $userId) {
            $reviewModel->delete($id);
            \Core\Session::setFlash('success', 'تم حذف التقييم بنجاح.');
        } else {
            \Core\Session::setFlash('error', 'التقييم غير موجود.');
        }

        $this->redirect('dashboard/reviews');
    }

    // Notifications page
    public function notifications() {
        $user = \Core\Auth::user();
        $notifModel = $this->model('Notification');
        
        $notifModel->query("SELECT * FROM notifications WHERE user_id = :uid ORDER BY created_at DESC");
        $notifModel->bind(':uid', $user->id);
        $notifications = $notifModel->resultSet();

        // Mark all as read
        $notifModel->query("UPDATE notifications SET is_read = 1, read_at = NOW() WHERE user_id = :uid AND is_read = 0");
        $notifModel->bind(':uid', $user->id);
        $notifModel->execute();

        $this->view('customer/notifications', [
            'title' => __('notifications') . ' | ' . __('logo'),
            'notifications' => $notifications
        ], 'dashboard');
    }

    /* -----------------------------------------------------
       SETTINGS ACTION HANDLERS
       ----------------------------------------------------- */

    public function settingsProfile() {
        $this->view('customer/settings-profile', [
            'title' => __('settings_profile') . ' | ' . __('logo'),
            'user' => \Core\Auth::user()
        ], 'dashboard');
    }

    public function updateProfile() {
        $this->validateCSRF();
        $user = \Core\Auth::user();

        $fullName = trim($_POST['full_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $gov = $_POST['governorate'] ?? '';
        $city = $_POST['city'] ?? '';
        $dob = $_POST['dob'] ?? null;
        $gender = $_POST['gender'] ?? 'prefer_not';

        if (empty($fullName)) {
            \Core\Session::setFlash('error', 'الاسم الكامل مطلوب.');
            $this->redirect('dashboard/settings/profile');
        }

        $updateData = [
            'full_name' => $fullName,
            'phone' => empty($phone) ? null : $phone,
            'governorate' => $gov,
            'city' => $city,
            'birth_date' => empty($dob) ? null : $dob,
            'gender' => $gender
        ];

        // Avatar Upload handling
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $fileName = uniqid('avatar_') . '.' . $ext;
            $uploadDir = dirname(dirname(__DIR__)) . '/uploads/avatars/';
            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadDir . $fileName)) {
                $updateData['avatar'] = $fileName;
            }
        }

        $userModel = $this->model('User');
        $userModel->update($user->id, $updateData);

        \Core\Session::setFlash('success', 'تم تحديث بيانات ملفك الشخصي بنجاح!');
        $this->redirect('dashboard/settings/profile');
    }

    public function settingsSecurity() {
        $this->view('customer/settings-security', [
            'title' => __('settings_security') . ' | ' . __('logo')
        ], 'dashboard');
    }

    public function updateSecurity() {
        $this->validateCSRF();
        $user = \Core\Auth::user();

        $currentPass = $_POST['current_password'] ?? '';
        $newPass = $_POST['new_password'] ?? '';
        $confirmPass = $_POST['confirm_password'] ?? '';

        if (empty($currentPass) || empty($newPass)) {
            \Core\Session::setFlash('error', 'يرجى إدخال جميع كلمات المرور المطلوبة.');
            $this->redirect('dashboard/settings/security');
        }

        // Verify password
        $userModel = $this->model('User');
        $dbUser = $userModel->find($user->id);

        if (!password_verify($currentPass, $dbUser->password_hash)) {
            \Core\Session::setFlash('error', 'كلمة المرور الحالية غير صحيحة.');
            $this->redirect('dashboard/settings/security');
        }

        if ($newPass !== $confirmPass) {
            \Core\Session::setFlash('error', 'تأكيد كلمة المرور الجديدة غير متطابق.');
            $this->redirect('dashboard/settings/security');
        }

        $newHash = password_hash($newPass, PASSWORD_BCRYPT, ['cost' => 12]);
        $userModel->update($user->id, ['password_hash' => $newHash]);

        \Core\Session::setFlash('success', 'تم تحديث كلمة المرور الخاصة بك بنجاح!');
        $this->redirect('dashboard/settings/security');
    }

    public function settingsPrivacy() {
        $userModel = $this->model('User');
        $settings = $userModel->getSettings(\Core\Auth::user()->id);

        $this->view('customer/settings-privacy', [
            'title' => __('settings_privacy') . ' | ' . __('logo'),
            'settings' => $settings
        ], 'dashboard');
    }

    public function updatePrivacy() {
        $this->validateCSRF();
        $user = \Core\Auth::user();

        $visibility = $_POST['profile_visibility'] ?? 'public';
        $email = isset($_POST['show_email']) ? 1 : 0;
        $phone = isset($_POST['show_phone']) ? 1 : 0;
        $location = isset($_POST['show_location']) ? 1 : 0;
        $messages = isset($_POST['allow_messages']) ? 1 : 0;

        $userModel = $this->model('User');
        $userModel->updateSettings($user->id, [
            'profile_visibility' => $visibility,
            'show_email' => $email,
            'show_phone' => $phone,
            'show_location' => $location,
            'allow_messages' => $messages
        ]);

        \Core\Session::setFlash('success', 'تم تحديث إعدادات الخصوصية بنجاح.');
        $this->redirect('dashboard/settings/privacy');
    }

    public function settingsNotifications() {
        $userModel = $this->model('User');
        $settings = $userModel->getSettings(\Core\Auth::user()->id);

        $this->view('customer/settings-notifications', [
            'title' => __('settings_notifications') . ' | ' . __('logo'),
            'settings' => $settings
        ], 'dashboard');
    }

    public function updateNotifications() {
        $this->validateCSRF();
        $user = \Core\Auth::user();

        $userModel = $this->model('User');
        $userModel->updateSettings($user->id, [
            'notify_email_reviews' => isset($_POST['notify_email_reviews']) ? 1 : 0,
            'notify_email_follows' => isset($_POST['notify_email_follows']) ? 1 : 0,
            'notify_email_marketing' => isset($_POST['notify_email_marketing']) ? 1 : 0,
            'notify_push_reviews' => isset($_POST['notify_push_reviews']) ? 1 : 0,
            'notify_push_follows' => isset($_POST['notify_push_follows']) ? 1 : 0,
            'notify_push_pricedrops' => isset($_POST['notify_push_pricedrops']) ? 1 : 0,
            'notify_push_newproducts' => isset($_POST['notify_push_newproducts']) ? 1 : 0
        ]);

        \Core\Session::setFlash('success', 'تم تحديث تنبيهات البريد والدفع بنجاح.');
        $this->redirect('dashboard/settings/notifications');
    }

    public function settingsAppearance() {
        $this->view('customer/settings-appearance', [
            'title' => __('settings_appearance') . ' | ' . __('logo'),
            'user' => \Core\Auth::user()
        ], 'dashboard');
    }

    public function updateAppearance() {
        $this->validateCSRF();
        $user = \Core\Auth::user();

        $theme = $_POST['theme_pref'] ?? 'system';
        $lang = $_POST['lang_pref'] ?? 'ar';

        $userModel = $this->model('User');
        $userModel->update($user->id, [
            'theme_pref' => $theme,
            'lang_pref' => $lang
        ]);

        $_SESSION['lang'] = $lang;

        \Core\Session::setFlash('success', 'تم حفظ المظهر وتفضيلات اللغة بنجاح.');
        $this->redirect('dashboard/settings/appearance');
    }
}
