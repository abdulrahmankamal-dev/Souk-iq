<?php
/**
 * SOUK.IQ Super Administrator Controller
 */

class SuperAdminController extends \Core\Controller {

    public function __construct() {
        $this->requireRole('super_admin');
    }

    // Main Super Admin dashboard
    public function index() {
        $model = $this->model('User');
        
        $model->query("SELECT COUNT(id) as total FROM users WHERE role = 'admin'");
        $adminsCount = $model->single()->total ?? 0;

        $model->query("SELECT COUNT(id) as total FROM subscription_plans");
        $plansCount = $model->single()->total ?? 0;

        $model->query("SELECT COUNT(id) as total FROM audit_logs");
        $auditLogsCount = $model->single()->total ?? 0;

        $this->view('super-admin/dashboard', [
            'title' => 'لوحة المدير العام | ' . __('logo'),
            'adminsCount' => $adminsCount,
            'plansCount' => $plansCount,
            'auditLogsCount' => $auditLogsCount
        ], 'dashboard');
    }

    // List admins
    public function admins() {
        $model = $this->model('User');
        $model->query("SELECT * FROM users WHERE role IN ('admin', 'super_admin') ORDER BY created_at DESC");
        $admins = $model->resultSet();

        $this->view('super-admin/admins', [
            'title' => 'إدارة المشرفين | ' . __('logo'),
            'admins' => $admins
        ], 'dashboard');
    }

    // Create system admin
    public function createAdmin() {
        $this->validateCSRF();
        $fullName = trim($_POST['full_name'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($fullName) || empty($username) || empty($email) || empty($password)) {
            \Core\Session::setFlash('error', 'يرجى ملء جميع البيانات.');
            $this->redirect('super-admin/admins');
        }

        $userModel = $this->model('User');
        if ($userModel->findByEmail($email) || $userModel->findByUsername($username)) {
            \Core\Session::setFlash('error', 'البريد الإلكتروني أو اسم المستخدم مسجل مسبقاً.');
            $this->redirect('super-admin/admins');
        }

        $uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
        $passwordHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

        $userModel->create([
            'uuid' => $uuid,
            'full_name' => $fullName,
            'username' => $username,
            'email' => $email,
            'password_hash' => $passwordHash,
            'role' => 'admin',
            'status' => 'active'
        ]);

        \Core\Session::setFlash('success', 'تم إنشاء حساب المشرف بنجاح!');
        $this->redirect('super-admin/admins');
    }

    // Manage plans
    public function plans() {
        $model = $this->model('Subscription');
        $model->query("SELECT * FROM subscription_plans ORDER BY sort_order ASC");
        $plans = $model->resultSet();

        $this->view('super-admin/plans', [
            'title' => 'باقات العضوية والاشتراكات | ' . __('logo'),
            'plans' => $plans
        ], 'dashboard');
    }

    public function savePlan() {
        $this->validateCSRF();
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $nameAr = trim($_POST['name_ar'] ?? '');
        $nameEn = trim($_POST['name_en'] ?? '');
        $price = floatval($_POST['price_monthly'] ?? 0);
        $maxProducts = intval($_POST['max_products'] ?? 10);

        if (empty($nameAr)) {
            \Core\Session::setFlash('error', 'الاسم بالعربية مطلوب.');
            $this->redirect('super-admin/plans');
        }

        $model = $this->model('Subscription');
        if ($id > 0) {
            $model->query("UPDATE subscription_plans SET name_ar = :name_ar, name_en = :name_en, price_monthly = :price, max_products = :max_p WHERE id = :id");
            $model->bind(':id', $id);
            $model->bind(':name_ar', $nameAr);
            $model->bind(':name_en', $nameEn);
            $model->bind(':price', $price);
            $model->bind(':max_p', $maxProducts);
            $model->execute();
            \Core\Session::setFlash('success', 'تم تحديث بيانات باقة الاشتراك.');
        } else {
            // Create plan
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $nameEn)));
            $model->query("INSERT INTO subscription_plans (name_ar, name_ku, name_en, slug, price_monthly, max_products, features) VALUES (:name_ar, '', :name_en, :slug, :price, :max_p, '[]')");
            $model->bind(':name_ar', $nameAr);
            $model->bind(':name_en', $nameEn);
            $model->bind(':slug', $slug);
            $model->bind(':price', $price);
            $model->bind(':max_p', $maxProducts);
            $model->execute();
            \Core\Session::setFlash('success', 'تم إضافة الباقة الجديدة بنجاح.');
        }

        $this->redirect('super-admin/plans');
    }

    // System audit logs
    public function auditLogs() {
        $model = $this->model('User');
        $model->query("SELECT al.*, u.username FROM audit_logs al LEFT JOIN users u ON al.user_id = u.id ORDER BY al.created_at DESC LIMIT 50");
        $logs = $model->resultSet();

        $this->view('super-admin/audit-logs', [
            'title' => 'سجلات النظام والعمليات | ' . __('logo'),
            'logs' => $logs
        ], 'dashboard');
    }

    // Global settings view & save
    public function settings() {
        $this->view('super-admin/settings', [
            'title' => 'إعدادات النظام العامة | ' . __('logo')
        ], 'dashboard');
    }

    public function saveSettings() {
        $this->validateCSRF();
        // Demonstration save
        \Core\Session::setFlash('success', 'تم حفظ إعدادات النظام العامة بنجاح!');
        $this->redirect('super-admin/settings');
    }
}
