<?php
// Test Sarah Williams login process
require_once 'config/database.php';
require_once 'includes/functions.php';

echo "Testing Sarah Williams Login Process...\n";

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    $email = 'sarah.travels@tripbazaar.com';
    $password = 'password123';
    
    echo "Looking up user: $email\n";
    
    // Simulate the login process
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND status = 'active'");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "✅ User found in database\n";
        echo "User ID: {$user['id']}\n";
        echo "Name: {$user['first_name']} {$user['last_name']}\n";
        echo "Role: {$user['role']}\n";
        echo "Status: {$user['status']}\n";
        
        // Test password verification
        if (password_verify($password, $user['password'])) {
            echo "✅ Password verification successful!\n";
            
            // Check if user has provider profile
            $stmt = $conn->prepare("SELECT company_name, is_verified FROM provider_profiles WHERE user_id = ?");
            $stmt->execute([$user['id']]);
            $profile = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($profile) {
                echo "✅ Provider profile found: {$profile['company_name']}\n";
                echo "Verified status: " . ($profile['is_verified'] ? 'Yes' : 'No') . "\n";
            } else {
                echo "❌ No provider profile found\n";
            }
            
            echo "\n--- LOGIN SHOULD WORK ---\n";
            echo "✅ All checks passed!\n";
            echo "✅ Sarah can now login with password123\n";
            
        } else {
            echo "❌ Password verification failed!\n";
            echo "Current hash: " . substr($user['password'], 0, 30) . "...\n";
        }
        
    } else {
        echo "❌ User not found or inactive\n";
        
        // Check if user exists but is inactive
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $inactiveUser = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($inactiveUser) {
            echo "User exists but status is: {$inactiveUser['status']}\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?> 