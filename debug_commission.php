<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

echo "Commission System Diagnostic\n";
echo "============================\n\n";

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    // 1. Check current bookings and their commission data
    echo "1. CURRENT BOOKINGS COMMISSION DATA:\n";
    echo "------------------------------------\n";
    
    $stmt = $conn->prepare("
        SELECT b.id, b.booking_id, b.total_amount, b.commission_amount, b.provider_amount,
               p.title as package_title, u.first_name, u.last_name, u.email,
               pp.commission_rate,
               CASE 
                   WHEN b.total_amount > 0 THEN (b.commission_amount / b.total_amount) * 100 
                   ELSE 0 
               END as actual_commission_rate
        FROM bookings b
        JOIN packages pkg ON b.package_id = pkg.id
        JOIN users u ON pkg.provider_id = u.id
        LEFT JOIN provider_profiles pp ON u.id = pp.user_id
        LEFT JOIN packages p ON b.package_id = p.id
        ORDER BY b.id DESC
        LIMIT 10
    ");
    $stmt->execute();
    $bookings = $stmt->fetchAll();
    
    foreach ($bookings as $booking) {
        echo "Booking #{$booking['booking_id']}:\n";
        echo "  Provider: {$booking['first_name']} {$booking['last_name']} ({$booking['email']})\n";
        echo "  Package: {$booking['package_title']}\n";
        echo "  Total Amount: ₹" . number_format($booking['total_amount'], 2) . "\n";
        echo "  Commission Amount: ₹" . number_format($booking['commission_amount'], 2) . "\n";
        echo "  Provider Amount: ₹" . number_format($booking['provider_amount'], 2) . "\n";
        echo "  Provider's Set Rate: " . ($booking['commission_rate'] ?? 'Not Set') . "%\n";
        echo "  Actual Rate Applied: " . number_format($booking['actual_commission_rate'], 2) . "%\n";
        echo "  Math Check: ₹" . number_format($booking['commission_amount'] + $booking['provider_amount'], 2) . " = ₹" . number_format($booking['total_amount'], 2) . "\n\n";
    }
    
    // 2. Check provider commission rates
    echo "2. PROVIDER COMMISSION RATES:\n";
    echo "------------------------------\n";
    
    $stmt = $conn->prepare("
        SELECT u.id, u.first_name, u.last_name, u.email,
               pp.commission_rate as profile_rate,
               ss.setting_value as default_rate
        FROM users u
        LEFT JOIN provider_profiles pp ON u.id = pp.user_id
        LEFT JOIN site_settings ss ON ss.setting_key = 'default_commission'
        WHERE u.role = 'provider'
    ");
    $stmt->execute();
    $providers = $stmt->fetchAll();
    
    foreach ($providers as $provider) {
        $effectiveRate = getProviderCommissionRate($provider['id']);
        echo "Provider: {$provider['first_name']} {$provider['last_name']} ({$provider['email']})\n";
        echo "  Profile Rate: " . ($provider['profile_rate'] ?? 'NULL') . "%\n";
        echo "  Default Rate: " . ($provider['default_rate'] ?? 'NULL') . "%\n";
        echo "  Effective Rate: {$effectiveRate}%\n\n";
    }
    
    // 3. Test commission calculation function
    echo "3. TESTING COMMISSION CALCULATION:\n";
    echo "----------------------------------\n";
    
    $stmt = $conn->prepare("SELECT id FROM packages LIMIT 1");
    $stmt->execute();
    $testPackage = $stmt->fetch();
    
    if ($testPackage) {
        $testAmount = 10000;
        $commissionData = calculateProviderCommission($testPackage['id'], $testAmount);
        
        echo "Test Package ID: {$testPackage['id']}\n";
        echo "Test Amount: ₹" . number_format($testAmount, 2) . "\n";
        echo "Calculated Commission Rate: {$commissionData['commission_rate']}%\n";
        echo "Calculated Commission Amount: ₹" . number_format($commissionData['commission_amount'], 2) . "\n";
        echo "Calculated Provider Amount: ₹" . number_format($commissionData['provider_amount'], 2) . "\n";
        echo "Total Check: ₹" . number_format($commissionData['commission_amount'] + $commissionData['provider_amount'], 2) . "\n\n";
    }
    
    // 4. Check admin dashboard commission stats
    echo "4. ADMIN DASHBOARD COMMISSION STATS:\n";
    echo "------------------------------------\n";
    
    // Simulate the admin dashboard commission calculation
    $stmt = $conn->prepare("
        SELECT COALESCE(SUM(commission_amount), 0) as total_commission,
               COALESCE(SUM(provider_amount), 0) as total_provider_amount,
               COALESCE(SUM(total_amount), 0) as total_revenue,
               COUNT(*) as total_bookings
        FROM bookings 
        WHERE payment_status = 'paid'
    ");
    $stmt->execute();
    $adminStats = $stmt->fetch();
    
    echo "Total Revenue: ₹" . number_format($adminStats['total_revenue'], 2) . "\n";
    echo "Total Commission: ₹" . number_format($adminStats['total_commission'], 2) . "\n";
    echo "Total Provider Amount: ₹" . number_format($adminStats['total_provider_amount'], 2) . "\n";
    echo "Total Bookings: {$adminStats['total_bookings']}\n";
    echo "Average Commission Rate: " . ($adminStats['total_revenue'] > 0 ? number_format(($adminStats['total_commission'] / $adminStats['total_revenue']) * 100, 2) : 0) . "%\n\n";
    
    // 5. Check for inconsistencies
    echo "5. INCONSISTENCY CHECK:\n";
    echo "-----------------------\n";
    
    $stmt = $conn->prepare("
        SELECT b.id, b.booking_id, b.total_amount, b.commission_amount, b.provider_amount
        FROM bookings b
        WHERE ABS(b.total_amount - (b.commission_amount + b.provider_amount)) > 0.01
    ");
    $stmt->execute();
    $inconsistencies = $stmt->fetchAll();
    
    if (empty($inconsistencies)) {
        echo "✅ No mathematical inconsistencies found in bookings\n";
    } else {
        echo "❌ Found " . count($inconsistencies) . " bookings with mathematical inconsistencies:\n";
        foreach ($inconsistencies as $booking) {
            echo "  Booking #{$booking['booking_id']}: Total ₹{$booking['total_amount']} ≠ Commission ₹{$booking['commission_amount']} + Provider ₹{$booking['provider_amount']}\n";
        }
    }
    
    // 6. Check zero commission amounts
    echo "\n6. ZERO COMMISSION CHECK:\n";
    echo "-------------------------\n";
    
    $stmt = $conn->prepare("
        SELECT COUNT(*) as zero_commission_count
        FROM bookings 
        WHERE commission_amount = 0 AND total_amount > 0
    ");
    $stmt->execute();
    $zeroCount = $stmt->fetch()['zero_commission_count'];
    
    echo "Bookings with zero commission: {$zeroCount}\n";
    
    if ($zeroCount > 0) {
        echo "❌ Found bookings with zero commission amounts - this needs fixing!\n";
    } else {
        echo "✅ All bookings have commission amounts calculated\n";
    }
    
    // 7. Test the route exists
    echo "\n7. ROUTE CHECK:\n";
    echo "---------------\n";
    
    $routeExists = false;
    $indexContent = file_get_contents('index.php');
    if (strpos($indexContent, '/admin/providers/update-commission') !== false) {
        echo "✅ Commission update route exists in index.php\n";
        $routeExists = true;
    } else {
        echo "❌ Commission update route missing from index.php\n";
    }
    
    // 8. Summary and recommendations
    echo "\n8. SUMMARY & RECOMMENDATIONS:\n";
    echo "------------------------------\n";
    
    if ($zeroCount > 0) {
        echo "❌ CRITICAL: Fix zero commission amounts in existing bookings\n";
    }
    
    if (!empty($inconsistencies)) {
        echo "❌ CRITICAL: Fix mathematical inconsistencies in booking amounts\n";
    }
    
    if (!$routeExists) {
        echo "❌ CRITICAL: Add commission update route to index.php\n";
    }
    
    echo "\nNext steps to fix commission tracking:\n";
    echo "1. Update existing bookings with correct commission calculations\n";
    echo "2. Ensure all providers have commission rates set\n";
    echo "3. Test commission rate updates from admin panel\n";
    echo "4. Verify new bookings use correct commission calculation\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?> 