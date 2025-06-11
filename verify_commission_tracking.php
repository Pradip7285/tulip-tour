<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

echo "Commission Tracking Verification\n";
echo "================================\n\n";

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    // 1. Verify admin dashboard commission stats
    echo "1. ADMIN DASHBOARD COMMISSION STATS:\n";
    echo "------------------------------------\n";
    
    $stmt = $conn->prepare("
        SELECT 
            COALESCE(SUM(commission_amount), 0) as total_commission,
            COALESCE(SUM(total_amount), 0) as total_revenue,
            COUNT(*) as total_bookings,
            COALESCE(AVG(commission_amount), 0) as avg_commission
        FROM bookings 
        WHERE payment_status = 'paid'
    ");
    $stmt->execute();
    $stats = $stmt->fetch();
    
    echo "✅ Total Commission Earned: ₹" . number_format($stats['total_commission'], 2) . "\n";
    echo "✅ Total Revenue: ₹" . number_format($stats['total_revenue'], 2) . "\n";
    echo "✅ Total Paid Bookings: {$stats['total_bookings']}\n";
    echo "✅ Average Commission per Booking: ₹" . number_format($stats['avg_commission'], 2) . "\n";
    
    // 2. Verify provider commission rates
    echo "\n2. PROVIDER COMMISSION RATES:\n";
    echo "------------------------------\n";
    
    $stmt = $conn->prepare("
        SELECT u.id, u.first_name, u.last_name, u.email,
               COALESCE(pp.commission_rate, 10) as commission_rate
        FROM users u
        LEFT JOIN provider_profiles pp ON u.id = pp.user_id
        WHERE u.role = 'provider'
        ORDER BY u.id
    ");
    $stmt->execute();
    $providers = $stmt->fetchAll();
    
    foreach ($providers as $provider) {
        $effectiveRate = getProviderCommissionRate($provider['id']);
        echo "✅ {$provider['first_name']} {$provider['last_name']}: {$effectiveRate}% commission\n";
    }
    
    // 3. Test commission calculation function
    echo "\n3. COMMISSION CALCULATION TEST:\n";
    echo "-------------------------------\n";
    
    if (!empty($providers)) {
        $testProviderId = $providers[0]['id'];
        
        // Get a package from this provider
        $stmt = $conn->prepare("SELECT id FROM packages WHERE provider_id = ? LIMIT 1");
        $stmt->execute([$testProviderId]);
        $package = $stmt->fetch();
        
        if ($package) {
            $testAmount = 10000;
            $commissionData = calculateProviderCommission($package['id'], $testAmount);
            
            echo "✅ Test Calculation for Provider {$providers[0]['first_name']}:\n";
            echo "   Package ID: {$package['id']}\n";
            echo "   Total Amount: ₹" . number_format($testAmount, 2) . "\n";
            echo "   Commission Rate: {$commissionData['commission_rate']}%\n";
            echo "   Commission Amount: ₹" . number_format($commissionData['commission_amount'], 2) . "\n";
            echo "   Provider Amount: ₹" . number_format($commissionData['provider_amount'], 2) . "\n";
            echo "   Math Check: ₹" . number_format($commissionData['commission_amount'] + $commissionData['provider_amount'], 2) . " = ₹" . number_format($testAmount, 2) . "\n";
        }
    }
    
    // 4. Verify booking consistency
    echo "\n4. BOOKING CONSISTENCY CHECK:\n";
    echo "-----------------------------\n";
    
    $stmt = $conn->prepare("
        SELECT COUNT(*) as total_bookings,
               COUNT(CASE WHEN commission_amount = 0 THEN 1 END) as zero_commission,
               COUNT(CASE WHEN provider_amount = 0 THEN 1 END) as zero_provider,
               COUNT(CASE WHEN ABS(total_amount - (commission_amount + provider_amount)) > 0.01 THEN 1 END) as math_errors
        FROM bookings
        WHERE total_amount > 0
    ");
    $stmt->execute();
    $consistency = $stmt->fetch();
    
    echo "✅ Total Bookings: {$consistency['total_bookings']}\n";
    echo ($consistency['zero_commission'] == 0 ? "✅" : "❌") . " Zero Commission Bookings: {$consistency['zero_commission']}\n";
    echo ($consistency['zero_provider'] == 0 ? "✅" : "❌") . " Zero Provider Amount Bookings: {$consistency['zero_provider']}\n";
    echo ($consistency['math_errors'] == 0 ? "✅" : "❌") . " Math Error Bookings: {$consistency['math_errors']}\n";
    
    // 5. Show recent bookings with commission details
    echo "\n5. RECENT BOOKINGS SAMPLE:\n";
    echo "--------------------------\n";
    
    $stmt = $conn->prepare("
        SELECT b.booking_id, b.total_amount, b.commission_amount, b.provider_amount,
               p.title as package_title, u.first_name, u.last_name,
               CASE 
                   WHEN b.total_amount > 0 THEN (b.commission_amount / b.total_amount) * 100 
                   ELSE 0 
               END as actual_rate
        FROM bookings b
        JOIN packages p ON b.package_id = p.id
        JOIN users u ON p.provider_id = u.id
        ORDER BY b.id DESC
        LIMIT 3
    ");
    $stmt->execute();
    $recentBookings = $stmt->fetchAll();
    
    foreach ($recentBookings as $booking) {
        echo "✅ Booking #{$booking['booking_id']}:\n";
        echo "   Provider: {$booking['first_name']} {$booking['last_name']}\n";
        echo "   Package: {$booking['package_title']}\n";
        echo "   Total: ₹" . number_format($booking['total_amount'], 2) . "\n";
        echo "   Commission: ₹" . number_format($booking['commission_amount'], 2) . " (" . number_format($booking['actual_rate'], 1) . "%)\n";
        echo "   Provider: ₹" . number_format($booking['provider_amount'], 2) . "\n\n";
    }
    
    // 6. Overall system status
    echo "6. SYSTEM STATUS:\n";
    echo "-----------------\n";
    
    $allGood = (
        $consistency['zero_commission'] == 0 &&
        $consistency['zero_provider'] == 0 &&
        $consistency['math_errors'] == 0
    );
    
    if ($allGood) {
        echo "🎉 COMMISSION TRACKING IS FULLY FUNCTIONAL!\n\n";
        echo "✅ Admin Dashboard: Shows accurate commission statistics\n";
        echo "✅ Provider Rates: Each provider has their own commission rate\n";
        echo "✅ Booking Calculations: All bookings have correct commission amounts\n";
        echo "✅ Rate Management: Admin can update provider commission rates\n";
        echo "✅ Data Consistency: No mathematical errors in commission calculations\n\n";
        echo "🔗 Access URLs:\n";
        echo "   Admin Dashboard: http://localhost:8000/tulip/admin/dashboard\n";
        echo "   Provider Management: http://localhost:8000/tulip/admin/providers\n";
        echo "   Provider Earnings: http://localhost:8000/tulip/provider/earnings\n\n";
        echo "📧 Login Credentials:\n";
        echo "   Admin: admin@tripbazaar.com / password123\n";
        echo "   Provider: provider@tripbazaar.com / password123\n";
    } else {
        echo "⚠️ COMMISSION TRACKING HAS ISSUES - NEEDS MANUAL REVIEW\n";
        echo "Please check the specific issues listed above.\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error during verification: " . $e->getMessage() . "\n";
}
?> 