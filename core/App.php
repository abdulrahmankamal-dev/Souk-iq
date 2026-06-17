<?php
/**
 * SOUK.IQ Core App Class (Router / Front Controller)
 */

namespace Core;

class App {
    protected $routes = [];

    public function __construct() {
        // Load configurations
        require_once dirname(__DIR__) . '/config/config.php';
        require_once dirname(__DIR__) . '/config/constants.php';
        require_once dirname(__DIR__) . '/core/Translator.php';
        
        // Initialize sessions securely
        Session::init();

        // Load route configurations
        $this->routes = require_once dirname(__DIR__) . '/config/routes.php';
    }

    public function run() {
        $uri = $this->getURI();
        $method = $_SERVER['REQUEST_METHOD'];

        // Standardize request method (GET, POST)
        if (!isset($this->routes[$method])) {
            $this->send405();
            return;
        }

        // Match routes
        foreach ($this->routes[$method] as $routePattern => $controllerAction) {
            // Anchor pattern
            $pattern = '#^' . $routePattern . '$#i';

            if (preg_match($pattern, $uri, $matches)) {
                // Remove the first full match
                array_shift($matches);

                // Split controller and action (e.g., HomeController@index)
                list($controllerClass, $action) = explode('@', $controllerAction);
                
                // Load controller
                $controllerFile = dirname(__DIR__) . '/controllers/' . $controllerClass . '.php';
                if (file_exists($controllerFile)) {
                    require_once $controllerFile;
                    
                    // Instantiate controller
                    $controllerInstance = new $controllerClass();
                    
                    // Call action with matches as arguments
                    call_user_func_array([$controllerInstance, $action], $matches);
                    return;
                }
            }
        }

        // If no match is found, render 404
        $this->send404();
    }

    protected function getURI() {
        // Get the URI path relative to index.php
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Extract project subfolder if running in subdirectory (e.g. /souk-iq)
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        $scriptName = str_replace('\\', '/', $scriptName); // Windows path normalization
        
        if ($scriptName !== '/') {
            $uri = substr($uri, strlen($scriptName));
        }

        // Standardize slashes
        $uri = '/' . trim($uri, '/');
        
        return $uri;
    }

    protected function send404() {
        header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
        // Load 404 View
        $view = new View();
        $view->render('errors/404', ['title' => 'الصفحة غير موجودة | 404'], 'main');
        exit;
    }

    protected function send405() {
        header($_SERVER["SERVER_PROTOCOL"] . " 405 Method Not Allowed");
        echo "405 Method Not Allowed";
        exit;
    }
}
