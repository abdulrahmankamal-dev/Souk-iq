<?php
/**
 * SOUK.IQ Front Controller Entrypoint
 */

// Register autoloading for Core and Models namespaces
spl_autoload_register(function ($class) {
    // E.g., Core\App => core/App.php, Models\User => models/User.php
    $parts = explode('\\', $class);
    if (count($parts) > 1) {
        $prefix = strtolower($parts[0]);
        $className = implode('/', array_slice($parts, 1));
        
        $file = __DIR__ . '/' . $prefix . '/' . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

// Run the application
try {
    $app = new \Core\App();
    $app->run();
} catch (\Exception $e) {
    // Global fallback error handler
    header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
    if (defined('APP_ENV') && APP_ENV === 'development') {
        echo "<h1>Application Error</h1>";
        echo "<p>" . nl2br(htmlspecialchars($e->getMessage())) . "</p>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    } else {
        echo "An unexpected error occurred. Please try again later.";
    }
    exit;
}
