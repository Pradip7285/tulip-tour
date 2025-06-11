<?php
session_start();
require_once 'config/database.php';
require_once 'controllers/DashboardController.php';
require_once 'includes/auth.php';

echo "Dashboard Session Debug\n";
echo "======================\n\n";

// Check current session
echo "SESSION DATA:\n";
if (isset($_SESSION['user'])) {
    echo "User ID: " . $_SESSION['user']['id'] . "\n";
    echo "Email: " . $_SESSION['user']['email'] . "\n";
    echo "Role: " . $_SESSION['user']['role'] . "\n";
    echo "Full Name: " . $_SESSION['user']['first_name'] . " " . $_SESSION['user']['last_name'] . "\n";
} else {
    echo "No user session found\n";
}

echo "\nTEST getCurrentUser() function:\n";
try {
    $currentUser = getCurrentUser();
    if ($currentUser) {
        echo "getCurrentUser() works:\n";
        echo "- ID: " . $currentUser['id'] . "\n";
        echo "- Email: " . $currentUser['email'] . "\n";
        echo "- Role: " . $currentUser['role'] . "\n";
    } else {
        echo "getCurrentUser() returned null\n";
    }
} catch (Exception $e) {
    echo "getCurrentUser() error: " . $e->getMessage() . "\n";
}

// Manually simulate login for Robert Patel
echo "\n=== SIMULATING LOGIN FOR ROBERT PATEL ===\n";

try {
    $pdo = getDB();
    
    // Get Robert's user data
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute(['mountain.trails@tripbazaar.com']);
    $user = $stmt->fetch();
    
    if ($user) {
        // Simulate login session
        $_SESSION['user'] = $user;
        
        echo "Simulated login successful\n";
        echo "Session user ID: " . $_SESSION['user']['id'] . "\n";
        
        // Test dashboard controller with simulated session
        $dashboard = new DashboardController();
        
        // Use reflection to test getProviderStats
        $reflection = new ReflectionClass($dashboard);
        $getProviderStats = $reflection->getMethod('getProviderStats');
        $getProviderStats->setAccessible(true);
        $stats = $getProviderStats->invoke($dashboard, $user['id']);
        
        echo "\nDASHBOARD STATS WITH SIMULATED SESSION:\n";
        echo "--------------------------------------\n";
        echo "Total Packages: " . $stats['total_packages'] . "\n";
        echo "Total Bookings: " . $stats['total_bookings'] . "\n";
        echo "Total Earnings: ₹" . number_format($stats['total_earnings']) . "\n";
        
        // Test getProviderEarnings
        $getProviderEarnings = $reflection->getMethod('getProviderEarnings');
        $getProviderEarnings->setAccessible(true);
        $earnings = $getProviderEarnings->invoke($dashboard, $user['id']);
        
        echo "\nEARNINGS DATA:\n";
        echo "Total: ₹" . number_format($earnings['total']) . "\n";
        echo "This Month: ₹" . number_format($earnings['this_month']) . "\n";
        
        if ($stats['total_earnings'] > 0) {
            echo "\n✅ Dashboard should show ₹" . number_format($stats['total_earnings']) . "\n";
            echo "If still showing 0, the issue is in the view or routing\n";
        } else {
            echo "\n❌ Still returning 0 - controller issue\n";
        }
        
    } else {
        echo "Robert Patel user not found!\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 