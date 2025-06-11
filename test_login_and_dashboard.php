<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/DashboardController.php';

echo "Login and Dashboard Test\n";
echo "=======================\n\n";

try {
    // Clear any existing session
    session_destroy();
    session_start();
    
    echo "1. Simulating Robert's login...\n";
    
    $pdo = getDB();
    
    // Get Robert's data
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND status = 'active'");
    $stmt->execute(['mountain.trails@tripbazaar.com']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        echo "❌ User not found or not active\n";
        exit;
    }
    
    echo "✅ Found user: {$user['first_name']} {$user['last_name']}\n";
    
    // Simulate the exact login process from AuthController
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
    $_SESSION['user_email'] = $user['email'];
    
    echo "✅ Session set exactly like AuthController\n\n";
    
    echo "2. Testing authentication functions...\n";
    
    // Test isLoggedIn
    if (isLoggedIn()) {
        echo "✅ isLoggedIn() returns true\n";
    } else {
        echo "❌ isLoggedIn() returns false\n";
    }
    
    // Test getCurrentUser
    $currentUser = getCurrentUser();
    if ($currentUser) {
        echo "✅ getCurrentUser() works: {$currentUser['email']}\n";
    } else {
        echo "❌ getCurrentUser() failed\n";
    }
    
    echo "\n3. Testing dashboard controller...\n";
    
    $dashboard = new DashboardController();
    
    // Test the providerDashboard method using reflection
    $reflection = new ReflectionClass($dashboard);
    
    // Test getProviderStats
    $getProviderStats = $reflection->getMethod('getProviderStats');
    $getProviderStats->setAccessible(true);
    $stats = $getProviderStats->invoke($dashboard, $user['id']);
    
    echo "Dashboard Stats:\n";
    echo "- Total Packages: {$stats['total_packages']}\n";
    echo "- Total Bookings: {$stats['total_bookings']}\n";
    echo "- Total Earnings: ₹" . number_format($stats['total_earnings']) . "\n";
    
    // Test getProviderEarnings
    $getProviderEarnings = $reflection->getMethod('getProviderEarnings');
    $getProviderEarnings->setAccessible(true);
    $earnings = $getProviderEarnings->invoke($dashboard, $user['id']);
    
    echo "\nEarnings Details:\n";
    echo "- Total: ₹" . number_format($earnings['total']) . "\n";
    echo "- This Month: ₹" . number_format($earnings['this_month']) . "\n";
    echo "- Pending: ₹" . number_format($earnings['pending_payouts']) . "\n";
    echo "- Completed: ₹" . number_format($earnings['completed_payouts']) . "\n";
    
    if ($stats['total_earnings'] > 0) {
        echo "\n🎉 SUCCESS! The dashboard should show ₹" . number_format($stats['total_earnings']) . "\n";
        echo "\n4. Next steps:\n";
        echo "- Login at: http://localhost/Tulip/login\n";
        echo "- Email: mountain.trails@tripbazaar.com\n";
        echo "- Password: password123\n";
        echo "- Expected earnings: ₹" . number_format($stats['total_earnings']) . "\n";
        
        echo "\nIf still showing ₹0, the issue is likely:\n";
        echo "- Browser cache\n";
        echo "- Session cookies not working\n";
        echo "- URL routing issues\n";
        echo "- View template caching\n";
    } else {
        echo "\n❌ Still showing 0 earnings\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?> 