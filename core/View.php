<?php
/**
 * SOUK.IQ Core View Class
 */

namespace Core;

class View {
    protected $data = [];

    // Main render method
    public function render($viewPath, $data = [], $layout = 'main') {
        $lang = $_SESSION['lang'] ?? 'ar';
        $dir = ($lang === 'en') ? 'ltr' : 'rtl';
        
        $this->data = array_merge([
            'lang' => $lang,
            'dir' => $dir
        ], $data);
        
        // Extract variables to make them available in view files
        extract($this->data);

        // Make translator global inside views
        if (!function_exists('__')) {
            require_once dirname(__DIR__) . '/core/Translator.php';
        }

        // Determine view file path
        $viewFile = dirname(__DIR__) . '/views/' . $viewPath . '.php';
        if (!file_exists($viewFile)) {
            throw new \Exception("View template {$viewPath} not found.");
        }

        // Buffer view template
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // If no layout is specified, output raw content
        if ($layout === false || empty($layout)) {
            echo $content;
            return;
        }

        // Load layout wrapper
        $layoutFile = dirname(__DIR__) . '/views/layouts/' . $layout . '.php';
        if (!file_exists($layoutFile)) {
            throw new \Exception("Layout {$layout} not found.");
        }

        require $layoutFile;
    }

    // Dynamic partial loader
    public function partial($partialPath, $data = []) {
        $lang = $_SESSION['lang'] ?? 'ar';
        $dir = ($lang === 'en') ? 'ltr' : 'rtl';
        
        // Merge parent data and child data
        $mergedData = array_merge([
            'lang' => $lang,
            'dir' => $dir
        ], $this->data, $data);
        extract($mergedData);

        // Make translator global inside partials
        if (!function_exists('__')) {
            require_once dirname(__DIR__) . '/core/Translator.php';
        }

        $partialFile = dirname(__DIR__) . '/views/partials/' . $partialPath . '.php';
        if (file_exists($partialFile)) {
            require $partialFile;
        } else {
            echo "<!-- Partial {$partialPath} not found -->";
        }
    }
}
