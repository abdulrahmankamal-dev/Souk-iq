<?php
/**
 * SOUK.IQ Core Base Controller
 */

namespace Core;

class Controller {
    
    // Load database model
    protected function model($modelName) {
        $modelFile = dirname(__DIR__) . '/models/' . $modelName . '.php';
        if (file_exists($modelFile)) {
            require_once $modelFile;
            $class = "\\Models\\" . $modelName;
            return new $class();
        }
        throw new \Exception("Model {$modelName} not found.");
    }

    // Render HTML view within layout
    protected function view($viewPath, $data = [], $layout = 'main') {
        $view = new View();
        $view->render($viewPath, $data, $layout);
    }

    // JSON response helper for API endpoints
    protected function json($data, $statusCode = 200) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($statusCode);
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    // Secure redirection helper
    protected function redirect($url) {
        $fullUrl = SITE_URL . '/' . ltrim($url, '/');
        header("Location: {$fullUrl}");
        exit;
    }

    // Helper to validate CSRF token in POST requests
    protected function validateCSRF() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
            if (!CSRF::verify($token)) {
                $this->json(['error' => 'رمز التحقق من الطلب (CSRF) غير صالح. يرجى المحاولة مجدداً.'], 403);
            }
        }
    }

    // Verify user role or redirect to login
    protected function requireRole($allowedRoles = []) {
        if (!is_array($allowedRoles)) {
            $allowedRoles = [$allowedRoles];
        }

        if (!Auth::check()) {
            Session::setFlash('error', 'يجب تسجيل الدخول أولاً للوصول إلى هذه الصفحة.');
            $this->redirect('login');
        }

        $userRole = Auth::user()->role;
        if (!in_array($userRole, $allowedRoles)) {
            Session::setFlash('error', 'ليس لديك الصلاحية الكافية للوصول لهذه الصفحة.');
            $this->redirect('');
        }
    }
}
