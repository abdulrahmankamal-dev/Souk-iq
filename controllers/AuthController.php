<?php
/**
 * SOUK.IQ Authentication Controller
 */

class AuthController extends \Core\Controller {

    // Show login form
    public function showLogin() {
        if (\Core\Auth::check()) {
            $this->redirect('dashboard');
        }
        $this->view('auth/login', [
            'title' => __('login') . ' | ' . __('logo')
        ], 'auth');
    }

    // Process login
    public function login() {
        if (\Core\Auth::check()) {
            $this->redirect('dashboard');
        }

        $emailOrUsername = trim($_POST['email_or_username'] ?? '');
        $password = $_POST['password'] ?? '';
        $rememberMe = isset($_POST['remember_me']);

        // Basic validation
        if (empty($emailOrUsername) || empty($password)) {
            \Core\Session::setFlash('error', 'يرجى إدخال اسم المستخدم/البريد الإلكتروني وكلمة المرور.');
            $this->redirect('login');
        }

        $result = \Core\Auth::login($emailOrUsername, $password, $rememberMe);

        if ($result === 'success') {
            \Core\Session::setFlash('success', 'تم تسجيل الدخول بنجاح! أهلاً بك مجدداً.');
            
            // Redirect based on role
            $user = \Core\Auth::user();
            if ($user->role === 'store_owner') {
                $this->redirect('store-owner/dashboard');
            } elseif ($user->role === 'admin' || $user->role === 'super_admin') {
                $this->redirect('admin');
            } else {
                $this->redirect('dashboard');
            }
        } elseif ($result === 'suspended') {
            $this->redirect('login');
        } elseif ($result === '2fa_required') {
            // Placeholder for 2FA redirection if needed
            \Core\Session::setFlash('info', 'رمز التحقق الثنائي مطلوب.');
            $this->redirect('login');
        } else {
            \Core\Session::setFlash('error', 'اسم المستخدم أو كلمة المرور غير صحيحة.');
            $this->redirect('login');
        }
    }

    // Show registration form
    public function showRegister() {
        if (\Core\Auth::check()) {
            $this->redirect('dashboard');
        }
        $this->view('auth/register', [
            'title' => __('register') . ' | ' . __('logo')
        ], 'auth');
    }

    // Process registration
    public function register() {
        if (\Core\Auth::check()) {
            $this->redirect('dashboard');
        }

        $fullName = trim($_POST['full_name'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $dob = $_POST['dob'] ?? null;
        $gender = $_POST['gender'] ?? 'prefer_not';
        $governorate = $_POST['governorate'] ?? null;
        $city = $_POST['city'] ?? null;
        $role = $_POST['role'] ?? 'customer'; // Default is customer, store_owner is selectable
        $termsAgree = isset($_POST['terms_agree']);

        // Validation
        if (empty($fullName) || empty($username) || empty($email) || empty($password)) {
            \Core\Session::setFlash('error', 'يرجى ملء جميع الحقول المطلوبة.');
            $this->redirect('register');
        }

        if (!$termsAgree) {
            \Core\Session::setFlash('error', 'يجب الموافقة على شروط الخدمة وسياسة الخصوصية.');
            $this->redirect('register');
        }

        if ($password !== $confirmPassword) {
            \Core\Session::setFlash('error', 'كلمة المرور وتأكيد كلمة المرور غير متطابقين.');
            $this->redirect('register');
        }

        $userModel = $this->model('User');

        // Check if username already exists
        if ($userModel->findByUsername($username)) {
            \Core\Session::setFlash('error', 'اسم المستخدم مستعمل بالفعل. اختر اسماً آخر.');
            $this->redirect('register');
        }

        // Check if email already exists
        if ($userModel->findByEmail($email)) {
            \Core\Session::setFlash('error', 'البريد الإلكتروني مسجل مسبقاً. سجل دخولك بدلاً من ذلك.');
            $this->redirect('register');
        }

        // Generate UUID v4
        $uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );

        $passwordHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

        $userData = [
            'uuid' => $uuid,
            'full_name' => $fullName,
            'username' => $username,
            'email' => $email,
            'phone' => empty($phone) ? null : $phone,
            'password_hash' => $passwordHash,
            'role' => in_array($role, ['customer', 'store_owner']) ? $role : 'customer',
            'status' => 'active',
            'governorate' => $governorate,
            'city' => $city,
            'birth_date' => empty($dob) ? null : $dob,
            'gender' => in_array($gender, ['male', 'female', 'prefer_not']) ? $gender : 'prefer_not',
            'lang_pref' => $_SESSION['lang'] ?? 'ar',
            'theme_pref' => 'system'
        ];

        $userId = $userModel->create($userData);

        if ($userId) {
            // Create user settings
            $userModel->getSettings($userId); // calling this creates default settings

            // Auto-login the user
            $registeredUser = $userModel->find($userId);
            \Core\Auth::completeLogin($registeredUser);

            // Add Welcome Notification
            $notifModel = $this->model('Notification');
            $notifModel->create([
                'user_id' => $userId,
                'type' => 'welcome',
                'title' => 'مرحباً بك في سوق.IQ! 🎉',
                'body' => 'يسعدنا انضمامك لأكبر منصة مقارنة أسعار ودليل متاجر في العراق. ابدأ باكتشاف المنتجات الآن.',
                'icon' => 'bi-party-fill',
                'link' => '/',
                'is_read' => 0
            ]);

            \Core\Session::setFlash('success', 'تم إنشاء حسابك بنجاح! أهلاً بك في سوق.IQ.');
            
            if ($registeredUser->role === 'store_owner') {
                $this->redirect('store-owner/settings'); // Go setup store first
            } else {
                $this->redirect('dashboard');
            }
        } else {
            \Core\Session::setFlash('error', 'حدث خطأ غير متوقع أثناء إنشاء الحساب. يرجى المحاولة مرة أخرى.');
            $this->redirect('register');
        }
    }

    // Process logout
    public function logout() {
        \Core\Auth::logout();
        \Core\Session::setFlash('success', 'تم تسجيل الخروج بنجاح. نراك لاحقاً!');
        $this->redirect('login');
    }
}
