<?php
require_once 'config/database.php';

echo "=== DATABASE TABLES CHECK ===\n";

try {
    $pdo = getDB();
    
    // Show all tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Tables in database: " . count($tables) . "\n";
    if (!empty($tables)) {
        foreach ($tables as $table) {
            echo "- $table\n";
        }
        
        // Check structure of main tables
        if (in_array('users', $tables)) {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
            $userCount = $stmt->fetch()['count'];
            echo "\nUsers table: $userCount records\n";
            
            $stmt = $pdo->query("SELECT role, COUNT(*) as count FROM users GROUP BY role");
            $roles = $stmt->fetchAll();
            foreach ($roles as $role) {
                echo "  - {$role['role']}: {$role['count']}\n";
            }
        }
    } else {
        echo "No tables found!\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 