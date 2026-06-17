<?php
/**
 * SOUK.IQ Core Sanitizer Class
 */

namespace Core;

class Sanitizer {
    // Escape output for HTML context
    public static function escape($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::escape($value);
            }
            return $data;
        }
        
        if (is_string($data)) {
            return htmlspecialchars(trim($data), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        }
        
        return $data;
    }

    // Sanitize rich text inputs (e.g., descriptions allowing basic HTML)
    public static function cleanHtml($html) {
        if (empty($html)) return '';
        // Basic protection by removing script and style tags
        $clean = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
        $clean = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $clean);
        // Remove javascript href links
        $clean = preg_replace('#href="javascript:[^"]+"#i', 'href="#"', $clean);
        return trim($clean);
    }

    // Format price to Iraqi Dinars format (e.g. 1,580,000 د.ع)
    public static function formatPrice($price, $currency = 'IQD', $lang = 'ar') {
        $formatted = number_format($price, 0, '.', ',');
        
        if ($lang === 'ar') {
            return $formatted . ' د.ع';
        } elseif ($lang === 'ku') {
            return $formatted . ' د.ع';
        } else {
            return $formatted . ' IQD';
        }
    }

    // Slugify string (useful for category and product slugs, handles Arabic/Kurdish characters)
    public static function slugify($text) {
        // Replace non-alphanumeric characters or spaces with hyphens
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text); // Transliterate if possible
        $text = preg_replace('~[^-\w]+~', '', $text); // Remove unwanted characters
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text); // Avoid double hyphens
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }
}
