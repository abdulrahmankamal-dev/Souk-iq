<?php
/**
 * SOUK.IQ Core Cache Helper (File-Based Caching)
 */

namespace Core;

class Cache {
    protected static $dir = null;

    protected static function getDir() {
        if (self::$dir === null) {
            self::$dir = defined('CACHE_PATH') ? CACHE_PATH : dirname(__DIR__) . '/cache';
            if (!is_dir(self::$dir)) {
                mkdir(self::$dir, 0777, true);
            }
        }
        return self::$dir;
    }

    protected static function getFilename($key) {
        return self::getDir() . '/' . md5($key) . '.cache';
    }

    // Set cache value
    public static function set($key, $value, $lifetime = null) {
        if (!defined('CACHE_ENABLED') || !CACHE_ENABLED) {
            return false;
        }

        if ($lifetime === null) {
            $lifetime = CACHE_LIFETIME;
        }

        $filename = self::getFilename($key);
        $data = [
            'expires' => time() + $lifetime,
            'data' => $value
        ];

        return file_put_contents($filename, serialize($data)) !== false;
    }

    // Get cache value
    public static function get($key) {
        if (!defined('CACHE_ENABLED') || !CACHE_ENABLED) {
            return null;
        }

        $filename = self::getFilename($key);
        if (!file_exists($filename)) {
            return null;
        }

        $content = file_get_contents($filename);
        if ($content === false) {
            return null;
        }

        $data = unserialize($content);
        if (!is_array($data) || !isset($data['expires']) || !isset($data['data'])) {
            unlink($filename);
            return null;
        }

        // Check expiration
        if (time() > $data['expires']) {
            unlink($filename);
            return null;
        }

        return $data['data'];
    }

    // Delete cache key
    public static function delete($key) {
        $filename = self::getFilename($key);
        if (file_exists($filename)) {
            return unlink($filename);
        }
        return false;
    }

    // Clear all cache files
    public static function clear() {
        $dir = self::getDir();
        $files = glob($dir . '/*.cache');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        return true;
    }
}
