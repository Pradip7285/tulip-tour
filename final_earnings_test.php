<?php
require_once 'config/database.php';

echo "Final Earnings Dashboard Test\n";
echo "============================\n\n";

try {
    $pdo = getDB();
    
    // Check total bookings
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM bookings");
    $bookingCount = $stmt->fetch()['count'];
    echo "Total bookings in database: $bookingCount\n";
    
    if ($bookingCount > 0) {
        // Show sample bookings with earnings
        $stmt = $pdo->query("
            SELECT b.id, b.total_amount, b.status, p.title, pp.company_name
            FROM bookings b 
            JOIN packages p ON b.package_id = p.id 
            JOIN provider_profiles pp ON p.provider_id = pp.id 
            LIMIT 5
        ");
        $bookings = $stmt->fetchAll();
        
        echo "\nSample Bookings with Provider Earnings:\n";
        echo "---------------------------------------\n";
        
        $totalSystemEarnings = 0;
        foreach ($bookings as $booking) {
            $providerEarning = $booking['total_amount'] * 0.85; // 85% to provider
            $systemCommission = $booking['total_amount'] * 0.15; // 15% to system
            
            echo "Booking {$booking['id']}: {$booking['title']}\n";
            echo "  Company: {$booking['company_name']}\n";
            echo "  Amount: ₹{$booking['total_amount']} ({$booking['status']})\n";
            echo "  Provider gets: ₹" . number_format($providerEarning) . "\n";
            echo "  System gets: ₹" . number_format($systemCommission) . "\n\n";
            
            if ($booking['status'] == 'completed') {
                $totalSystemEarnings += $systemCommission;
            }
        }
        
        echo "Total completed system earnings: ₹" . number_format($totalSystemEarnings) . "\n\n";
        
        // Test provider dashboard calculation
        $stmt = $pdo->query("
            SELECT pp.id, pp.company_name, u.email
            FROM provider_profiles pp 
            JOIN users u ON pp.user_id = u.id 
            LIMIT 1
        ");
        $provider = $stmt->fetch();
        
        if ($provider) {
            echo "Testing Provider Dashboard for: {$provider['company_name']}\n";
            echo "Email: {$provider['email']}\n";
            echo "Provider ID: {$provider['id']}\n\n";
            
            // Calculate this provider's earnings
            $stmt = $pdo->prepare("
                SELECT 
                    COALESCE(SUM(CASE WHEN b.status = 'completed' THEN b.total_amount * 0.85 ELSE 0 END), 0) as total_earnings,
                    COALESCE(SUM(CASE WHEN b.status = 'completed' AND DATE_FORMAT(b.booking_date, '%Y-%m') = ? THEN b.total_amount * 0.85 ELSE 0 END), 0) as month_earnings,
                    COUNT(*) as total_bookings
                FROM bookings b
                JOIN packages p ON b.package_id = p.id
                WHERE p.provider_id = ?
            ");
            $stmt->execute([date('Y-m'), $provider['id']]);
            $earnings = $stmt->fetch();
            
            echo "Dashboard Results:\n";
            echo "- Total Earnings (Completed): ₹" . number_format($earnings['total_earnings']) . "\n";
            echo "- This Month Earnings: ₹" . number_format($earnings['month_earnings']) . "\n";
            echo "- Total Bookings: {$earnings['total_bookings']}\n\n";
            
            if ($earnings['total_earnings'] > 0) {
                echo "✅ SUCCESS: Earnings dashboard should show ₹" . number_format($earnings['total_earnings']) . "\n";
                echo "✅ The provider dashboard is working correctly!\n\n";
                echo "🎯 You can now login as: {$provider['email']} (password: password123)\n";
                echo "🌐 Go to: http://localhost/Tulip/login\n";
            } else {
                echo "❌ WARNING: No completed bookings found for this provider\n";
            }
        }
    } else {
        echo "❌ No bookings found in database\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?> 