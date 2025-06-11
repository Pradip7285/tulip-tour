<?php
require_once 'config/database.php';
require_once 'controllers/DashboardController.php';

echo "Testing Dashboard Controller Fix (Corrected)\n";
echo "============================================\n\n";

try {
    $pdo = getDB();
    
    // Find the user ID for mountain.trails@tripbazaar.com
    $stmt = $pdo->prepare("SELECT id, first_name, last_name, email FROM users WHERE email = ?");
    $stmt->execute(['mountain.trails@tripbazaar.com']);
    $user = $stmt->fetch();
    
    if (!$user) {
        echo "❌ User not found!\n";
        exit;
    }
    
    $fullName = $user['first_name'] . ' ' . $user['last_name'];
    echo "Testing for user: $fullName ({$user['email']})\n";
    echo "User ID: {$user['id']}\n\n";
    
    // Get provider profile ID
    $stmt = $pdo->prepare("SELECT id, company_name FROM provider_profiles WHERE user_id = ?");
    $stmt->execute([$user['id']]);
    $provider = $stmt->fetch();
    
    if (!$provider) {
        echo "❌ Provider profile not found!\n";
        exit;
    }
    
    echo "Provider Profile ID: {$provider['id']}\n";
    echo "Company: {$provider['company_name']}\n\n";
    
    // Test the dashboard controller directly
    $dashboard = new DashboardController();
    
    // Test the updated methods using reflection
    $reflection = new ReflectionClass($dashboard);
    $getProviderStats = $reflection->getMethod('getProviderStats');
    $getProviderStats->setAccessible(true);
    $stats = $getProviderStats->invoke($dashboard, $user['id']);
    
    echo "PROVIDER STATS (from dashboard controller):\n";
    echo "-------------------------------------------\n";
    echo "Total Packages: {$stats['total_packages']}\n";
    echo "Total Bookings: {$stats['total_bookings']}\n";
    echo "Active Packages: {$stats['active_packages']}\n";
    echo "Total Earnings: ₹" . number_format($stats['total_earnings']) . "\n\n";
    
    // Get provider earnings
    $getProviderEarnings = $reflection->getMethod('getProviderEarnings');
    $getProviderEarnings->setAccessible(true);
    $earnings = $getProviderEarnings->invoke($dashboard, $user['id']);
    
    echo "PROVIDER EARNINGS (from dashboard controller):\n";
    echo "----------------------------------------------\n";
    echo "Total Earnings: ₹" . number_format($earnings['total']) . "\n";
    echo "This Month: ₹" . number_format($earnings['this_month']) . "\n";
    echo "Pending Payouts: ₹" . number_format($earnings['pending_payouts']) . "\n";
    echo "Completed Payouts: ₹" . number_format($earnings['completed_payouts']) . "\n\n";
    
    // Get bookings
    $getProviderBookings = $reflection->getMethod('getProviderBookings');
    $getProviderBookings->setAccessible(true);
    $bookings = $getProviderBookings->invoke($dashboard, $user['id'], 10);
    
    echo "PROVIDER BOOKINGS (from dashboard controller):\n";
    echo "----------------------------------------------\n";
    echo "Total bookings found: " . count($bookings) . "\n";
    
    foreach ($bookings as $booking) {
        echo "- Booking {$booking['id']}: {$booking['package_title']} - ₹{$booking['total_amount']} ({$booking['status']})\n";
    }
    
    if ($stats['total_earnings'] > 0) {
        echo "\n✅ SUCCESS: Dashboard should now show ₹" . number_format($stats['total_earnings']) . " in earnings!\n";
        echo "🎯 Login as: {$user['email']} (password: password123)\n";
        echo "🌐 URL: http://localhost/Tulip/login\n";
        echo "\n🔥 THE EARNINGS DASHBOARD FIX IS WORKING!\n";
    } else {
        echo "\n❌ Still showing 0 earnings - need to investigate further\n";
        
        // Debug: Check raw data
        echo "\nDEBUG - Raw booking data for this provider:\n";
        $stmt = $pdo->prepare("
            SELECT b.id, b.total_amount, b.status, p.title, p.provider_id
            FROM bookings b 
            JOIN packages p ON b.package_id = p.id 
            WHERE p.provider_id = ?
        ");
        $stmt->execute([$provider['id']]);
        $rawBookings = $stmt->fetchAll();
        
        foreach ($rawBookings as $booking) {
            echo "- Booking {$booking['id']}: ₹{$booking['total_amount']} ({$booking['status']}) - Provider ID: {$booking['provider_id']}\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?> 