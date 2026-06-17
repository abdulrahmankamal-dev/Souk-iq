<?php
/**
 * SOUK.IQ Home Controller
 */

class HomeController extends \Core\Controller {

    // Render Home Page
    public function index() {
        $categoryModel = $this->model('Category');
        $storeModel = $this->model('Store');
        $productModel = $this->model('Product');

        // Fetch home data
        $categories = $categoryModel->getCategoriesWithSub();
        $featuredStores = $storeModel->getFeaturedStores(8);
        $featuredProducts = $productModel->getFeaturedProducts(8);
        $recentlyAdded = $productModel->getRecentlyAdded(8);

        // Fetch home advertisements
        $adModel = $this->model('Product'); // Fallback model lookup
        $adModel->query("SELECT * FROM advertisements WHERE status = 'active' AND type = 'banner_home' AND (starts_at IS NULL OR starts_at <= NOW()) AND (ends_at IS NULL OR ends_at >= NOW()) ORDER BY position ASC");
        $bannerAds = $adModel->resultSet();

        $this->view('home', [
            'title' => __('logo') . ' | ' . __('hero_title'),
            'categories' => $categories,
            'featuredStores' => $featuredStores,
            'featuredProducts' => $featuredProducts,
            'recentlyAdded' => $recentlyAdded,
            'bannerAds' => $bannerAds
        ], 'main');
    }

    // Change Language Preference
    public function changeLanguage() {
        $lang = $_GET['lang'] ?? $_POST['lang'] ?? 'ar';
        if (in_array($lang, ['ar', 'ku', 'en'])) {
            $_SESSION['lang'] = $lang;

            // If user logged in, persist to database
            if (\Core\Auth::check()) {
                $user = \Core\Auth::user();
                $userModel = $this->model('User');
                $userModel->update($user->id, ['lang_pref' => $lang]);
                // Refresh auth session
                $_SESSION['user_session'] = serialize($userModel->find($user->id));
            }
        }

        // Redirect back
        $referer = $_SERVER['HTTP_REFERER'] ?? SITE_URL;
        header("Location: " . $referer);
        exit;
    }

    // Show DB Installer view
    public function showInstall() {
        $this->view('install', [
            'title' => 'مستودع قواعد البيانات | SOUK.IQ Installer'
        ], 'main');
    }

    // Run database installation
    public function install() {
        // Read configuration
        try {
            $dbHost = DB_HOST;
            $dbName = DB_NAME;
            $dbUser = DB_USER;
            $dbPass = DB_PASS;

            // Connect using raw PDO connection to allow database recreation
            $dsn = "mysql:host={$dbHost};charset=utf8mb4";
            $pdo = new PDO($dsn, $dbUser, $dbPass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);

            // Create database if not exists
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
            $pdo->exec("USE `{$dbName}`;");

            // Execute schema
            $schemaFile = dirname(__DIR__) . '/database/schema.sql';
            if (!file_exists($schemaFile)) {
                throw new Exception("Schema file database/schema.sql does not exist.");
            }
            $schemaSql = file_get_contents($schemaFile);
            $pdo->exec($schemaSql);

            // Execute seeds
            $seedsFile = dirname(__DIR__) . '/database/seeds.sql';
            if (!file_exists($seedsFile)) {
                throw new Exception("Seeds file database/seeds.sql does not exist.");
            }
            $seedsSql = file_get_contents($seedsFile);
            $pdo->exec($seedsSql);

            \Core\Session::setFlash('success', 'تم تهيئة وتثبيت قاعدة البيانات وتهيئة الحسابات الافتراضية بنجاح!');
            $this->redirect('');
        } catch (\Exception $e) {
            \Core\Session::setFlash('error', 'فشل في تثبيت قاعدة البيانات: ' . $e->getMessage());
            $this->redirect('install');
        }
    }
}
