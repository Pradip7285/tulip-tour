<?php
// Fix Sarah Williams password
require_once 'config/database.php';

echo "Checking and fixing Sarah Williams password...\n";

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    // Check if Sarah exists
    $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email = ?");
    $stmt->execute(['sarah.travels@tripbazaar.com']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "Found Sarah Williams in database\n";
        echo "Current password hash: " . substr($user['password'], 0, 20) . "...\n";
        
        // Create a new password hash for "password123"
        $newPassword = password_hash('password123', PASSWORD_DEFAULT);
        echo "New password hash: " . substr($newPassword, 0, 20) . "...\n";
        
        // Update the password
        $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $updateStmt->execute([$newPassword, 'sarah.travels@tripbazaar.com']);
        
        echo "✅ Password updated successfully!\n";
        
        // Verify the password works
        if (password_verify('password123', $newPassword)) {
            echo "✅ Password verification test passed!\n";
        } else {
            echo "❌ Password verification test failed!\n";
        }
        
        // Show user details
        echo "\n--- Sarah Williams Login Details ---\n";
        echo "Email: sarah.travels@tripbazaar.com\n";
        echo "Password: password123\n";
        echo "Role: provider\n";
        echo "Company: Sarah's Romantic Escapes\n";
        
    } else {
        echo "❌ Sarah Williams not found in database!\n";
        
        // Let's check all users with 'sarah' in email
        $stmt = $conn->prepare("SELECT email, first_name, last_name FROM users WHERE email LIKE '%sarah%' OR first_name LIKE '%sarah%'");
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "Users containing 'sarah':\n";
        foreach ($users as $u) {
            echo "- {$u['first_name']} {$u['last_name']} ({$u['email']})\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?> 