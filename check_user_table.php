<?php
require_once 'config/database.php';

try {
    $pdo = getDB();
    
    echo "USERS TABLE STRUCTURE:\n";
    echo "=====================\n";
    
    $stmt = $pdo->query("DESCRIBE users");
    $columns = $stmt->fetchAll();
    
    foreach ($columns as $column) {
        echo "- {$column['Field']} ({$column['Type']})\n";
    }
    
    echo "\nSAMPLE USER DATA:\n";
    echo "================\n";
    
    $stmt = $pdo->query("SELECT * FROM users WHERE email = 'mountain.trails@tripbazaar.com' LIMIT 1");
    $user = $stmt->fetch();
    
    if ($user) {
        foreach ($user as $key => $value) {
            if ($key !== 'password') {
                echo "$key: $value\n";
            }
        }
    } else {
        echo "User not found!\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 