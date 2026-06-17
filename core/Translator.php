<?php
/**
 * SOUK.IQ Translation Helper
 */

// Load current language dictionary
function loadLanguageDict() {
    static $dictionary = null;
    
    if ($dictionary === null) {
        $lang = $_SESSION['lang'] ?? 'ar';
        
        // Ensure language is valid
        if (!in_array($lang, ['ar', 'ku', 'en'])) {
            $lang = 'ar';
        }
        
        $langFile = dirname(__DIR__) . '/lang/' . $lang . '.php';
        if (file_exists($langFile)) {
            $dictionary = require $langFile;
        } else {
            $dictionary = [];
        }
    }
    
    return $dictionary;
}

// Global translate helper function
function __($key, $placeholders = []) {
    $dict = loadLanguageDict();
    
    $value = $dict[$key] ?? $key;
    
    if (!empty($placeholders)) {
        foreach ($placeholders as $placeholderKey => $placeholderValue) {
            $value = str_replace('{' . $placeholderKey . '}', $placeholderValue, $value);
        }
    }
    
    return $value;
}

// Get HTML attributes (lang and dir)
function getHtmlAttributes() {
    $lang = $_SESSION['lang'] ?? 'ar';
    $dir = ($lang === 'en') ? 'ltr' : 'rtl';
    return "lang=\"{$lang}\" dir=\"{$dir}\"";
}

// Localize object property based on current language
function getLocalized($obj, $property, $fallbackSuffix = 'ar') {
    if (!$obj) return '';
    $lang = $_SESSION['lang'] ?? 'ar';
    
    $propLang = $property . '_' . $lang;
    if (isset($obj->$propLang) && !empty($obj->$propLang)) {
        return $obj->$propLang;
    }
    
    // Fallback to suffix (typically 'ar')
    $propFallback = $property . '_' . $fallbackSuffix;
    if (isset($obj->$propFallback) && !empty($obj->$propFallback)) {
        return $obj->$propFallback;
    }
    
    // Check if property without suffix exists
    if (isset($obj->$property)) {
        return $obj->$property;
    }
    
    return '';
}
