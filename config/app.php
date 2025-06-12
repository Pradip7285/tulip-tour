<?php

// Define debug constant
define('APP_DEBUG', true);

// Application Configuration
class AppConfig {
    
    // Auto-detect base URL or set manually
    public static function getBaseUrl() {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $script = $_SERVER['SCRIPT_NAME'];
        $basePath = str_replace('/index.php', '', $script);
        
        return $protocol . '://' . $host . $basePath;
    }
    
    // Get base path (directory part only)
    public static function getBasePath() {
        $script = $_SERVER['SCRIPT_NAME'];
        $basePath = str_replace('/index.php', '', $script);
        return rtrim($basePath, '/');
    }
    
    // Generate URL with base path
    public static function url($path = '') {
        $basePath = self::getBasePath();
        $path = ltrim($path, '/');
        
        // For development server (php -S), we might not have a base path
        if (empty($basePath) || $basePath === '') {
            return '/' . $path;
        }
        
        return $basePath . ($path ? '/' . $path : '');
    }
    
    // Application settings
    public static function get($key, $default = null) {
        $config = [
            'app_name' => 'Tulip Tourisam',
            'app_description' => 'Your Gateway to Amazing Adventures',
            'app_version' => '1.0.0',
            'debug' => true, // Set to false in production
            'timezone' => 'UTC',
            'per_page' => 12,
            'static'=> './assets/images/',
            'upload_max_size' => 5 * 1024 * 1024, // 5MB
            'allowed_image_types' => ['jpeg', 'jpg', 'png', 'gif', 'webp'],
        ];
        
        return isset($config[$key]) ? $config[$key] : $default;
    }
    
    // Check if we're in development mode
    public static function isDebug() {
        return self::get('debug', false);
    }
    
    // Get current URL path without base path
    public static function getCurrentPath() {
        $requestUri = $_SERVER['REQUEST_URI'];
        $path = parse_url($requestUri, PHP_URL_PATH);
        $basePath = self::getBasePath();
        
        // Remove base path from current path
        if ($basePath && strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath));
        }
        
        return $path ?: '/';
    }
}

// Helper functions for easier access
function app_url($path = '') {
    // For PHP development server, use simple path
    if (isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'], 'Development Server') !== false) {
        return '/' . ltrim($path, '/');
    }
    
    return AppConfig::url($path);
}

function base_url() {
    return AppConfig::getBaseUrl();
}

function asset_url($path) {
    return app_url($path);
}

function current_path() {
    return AppConfig::getCurrentPath();
}

?> 