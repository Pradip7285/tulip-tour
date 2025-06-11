<?php
require_once 'config/database.php';

echo "All Provider Earnings Check\n";
echo "===========================\n\n";

try {
    $pdo = getDB();
    
    // Get all providers with their earnings
    $stmt = $pdo->query("
        SELECT 
            pp.id,
            pp.company_name,
            u.email,
            COALESCE(SUM(CASE WHEN b.status = 'completed' THEN b.total_amount * 0.85 ELSE 0 END), 0) as total_earnings,
            COALESCE(SUM(CASE WHEN b.status = 'completed' AND DATE_FORMAT(b.booking_date, '%Y-%m') = CURDATE() THEN b.total_amount * 0.85 ELSE 0 END), 0) as month_earnings,
            COUNT(b.id) as total_bookings,
            COUNT(CASE WHEN b.status = 'completed' THEN 1 END) as completed_bookings
        FROM provider_profiles pp
        JOIN users u ON pp.user_id = u.id
        LEFT JOIN packages p ON p.provider_id = pp.id
        LEFT JOIN bookings b ON b.package_id = p.id
        GROUP BY pp.id, pp.company_name, u.email
        ORDER BY total_earnings DESC
    ");
    $providers = $stmt->fetchAll();
    
    echo "Provider Earnings Summary:\n";
    echo "--------------------------\n";
    
    foreach ($providers as $provider) {
        echo "📊 {$provider['company_name']}\n";
        echo "   Email: {$provider['email']}\n";
        echo "   Provider ID: {$provider['id']}\n";
        echo "   Total Bookings: {$provider['total_bookings']}\n";
        echo "   Completed Bookings: {$provider['completed_bookings']}\n";
        echo "   Total Earnings: ₹" . number_format($provider['total_earnings']) . "\n";
        echo "   This Month: ₹" . number_format($provider['month_earnings']) . "\n\n";
    }
    
    // Find the provider with highest earnings
    $topProvider = $providers[0];
    if ($topProvider['total_earnings'] > 0) {
        echo "🎯 TOP EARNING PROVIDER:\n";
        echo "========================\n";
        echo "Company: {$topProvider['company_name']}\n";
        echo "Email: {$topProvider['email']}\n";
        echo "Total Earnings: ₹" . number_format($topProvider['total_earnings']) . "\n\n";
        
        echo "✅ LOGIN TO TEST DASHBOARD:\n";
        echo "Email: {$topProvider['email']}\n";
        echo "Password: password123\n";
        echo "URL: http://localhost/Tulip/login\n\n";
        
        echo "🔥 THIS PROVIDER SHOULD SHOW EARNINGS IN DASHBOARD!\n";
    } else {
        echo "❌ No providers have any earnings yet\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?> 