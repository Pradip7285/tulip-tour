<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

echo "Session Debug for Dashboard Issue\n";
echo "================================\n\n";

// Check what's in session
echo "CURRENT SESSION:\n";
if (isset($_SESSION['user_id'])) {
    echo "user_id: " . $_SESSION['user_id'] . "\n";
} else {
    echo "No user_id in session\n";
}

if (isset($_SESSION['user'])) {
    echo "user array exists\n";
} else {
    echo "No user array in session\n";
}

// Test getCurrentUser function
echo "\nTesting getCurrentUser():\n";
$currentUser = getCurrentUser();
if ($currentUser) {
    echo "SUCCESS - getCurrentUser() works\n";
    echo "ID: " . $currentUser['id'] . "\n";
    echo "Email: " . $currentUser['email'] . "\n";
    echo "Role: " . $currentUser['role'] . "\n";
} else {
    echo "FAILED - getCurrentUser() returned null\n";
}

// Manually set session to test Robert
echo "\n=== TESTING WITH ROBERT'S SESSION ===\n";

try {
    $pdo = getDB();
    
    // Get Robert's data
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute(['mountain.trails@tripbazaar.com']);
    $robert = $stmt->fetch();
    
    if ($robert) {
        echo "Found Robert Patel (ID: {$robert['id']})\n";
        
        // Set both session formats to be safe
        $_SESSION['user_id'] = $robert['id'];
        $_SESSION['user'] = $robert;
        
        echo "Session set for Robert\n";
        
        // Test getCurrentUser again
        $testUser = getCurrentUser();
        if ($testUser) {
            echo "getCurrentUser() now works: " . $testUser['email'] . "\n";
            
            // Now test dashboard directly
            require_once 'controllers/DashboardController.php';
            $dashboard = new DashboardController();
            
            // Manually call providerDashboard method parts
            $reflection = new ReflectionClass($dashboard);
            
            $getProviderStats = $reflection->getMethod('getProviderStats');
            $getProviderStats->setAccessible(true);
            $stats = $getProviderStats->invoke($dashboard, $robert['id']);
            
            echo "\nDASHBOARD RESULTS:\n";
            echo "Total Packages: " . $stats['total_packages'] . "\n";
            echo "Total Bookings: " . $stats['total_bookings'] . "\n";
            echo "Total Earnings: ₹" . number_format($stats['total_earnings']) . "\n";
            
            if ($stats['total_earnings'] > 0) {
                echo "\n✅ Controller returns correct earnings!\n";
                echo "The issue must be in the web interface/routing\n";
            }
            
        } else {
            echo "getCurrentUser() still failed\n";
        }
        
    } else {
        echo "Robert not found in database\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 