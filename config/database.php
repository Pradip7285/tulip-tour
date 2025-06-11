<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'tripbazaar');
define('DB_USER', 'root');
define('DB_PASS', 'Pr@dip7285'); // Database password

class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        try {
            $this->connection = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch(PDOException $e) {
            // Try to connect without database specified to create it
            try {
                $this->connection = new PDO(
                    "mysql:host=" . DB_HOST . ";charset=utf8mb4",
                    DB_USER,
                    DB_PASS,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );
                
                // Create database if it doesn't exist
                $this->connection->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
                $this->connection->exec("USE " . DB_NAME);
                
            } catch(PDOException $e2) {
                // Only show detailed errors in debug mode
                if (defined('APP_DEBUG') && APP_DEBUG) {
                    die("Database connection failed: " . $e2->getMessage() . "<br>Please check your XAMPP MySQL service and database credentials.");
                } else {
                    error_log("Database connection failed: " . $e2->getMessage());
                    die("Database connection failed. Please try again later.");
                }
            }
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
}

// Get database connection
function getDB() {
    return Database::getInstance()->getConnection();
}
?> 