<?php
require_once 'config/database.php';

echo "Testing provider details query...\n";

try {
    $db = Database::getInstance()->getConnection();
    
    // Get a provider ID to test with
    $stmt = $db->prepare("SELECT id FROM users WHERE role = 'provider' LIMIT 1");
    $stmt->execute();
    $provider = $stmt->fetch();
    
    if (!$provider) {
        echo "❌ No providers found\n";
        exit;
    }
    
    $providerId = $provider['id'];
    echo "Testing with provider ID: $providerId\n\n";
    
    // Test the problematic query
    $stmt = $db->prepare("
        SELECT u.id, u.first_name, u.last_name, u.email, u.role, u.status, u.created_at, u.updated_at,
               pp.company_name, pp.commission_rate, pp.description, pp.address, pp.city, pp.state, pp.country,
               COUNT(DISTINCT p.id) as total_packages,
               COUNT(DISTINCT CASE WHEN p.is_active = 1 THEN p.id END) as active_packages,
               COUNT(DISTINCT b.id) as total_bookings,
               COALESCE(SUM(CASE WHEN b.payment_status = 'paid' THEN b.provider_amount END), 0) as total_earnings,
               COALESCE(SUM(CASE WHEN b.payment_status = 'paid' THEN b.commission_amount END), 0) as total_commission_paid,
               COALESCE(AVG(r.rating), 0) as avg_rating,
               COUNT(DISTINCT r.id) as total_reviews,
               MAX(b.created_at) as last_booking_date,
               MAX(r.created_at) as last_review_date,
               MAX(p.created_at) as last_package_created
        FROM users u
        LEFT JOIN provider_profiles pp ON u.id = pp.user_id
        LEFT JOIN packages p ON u.id = p.provider_id
        LEFT JOIN bookings b ON p.id = b.package_id
        LEFT JOIN reviews r ON p.id = r.package_id
        WHERE u.id = ? AND u.role = 'provider'
        GROUP BY u.id, u.first_name, u.last_name, u.email, u.role, u.status, u.created_at, u.updated_at,
                 pp.company_name, pp.commission_rate, pp.description, pp.address, pp.city, pp.state, pp.country
    ");
    
    $stmt->execute([$providerId]);
    $result = $stmt->fetch();
    
    if ($result) {
        echo "✅ Query executed successfully!\n";
        echo "Provider: {$result['first_name']} {$result['last_name']}\n";
        echo "Email: {$result['email']}\n";
        echo "Total Packages: {$result['total_packages']}\n";
        echo "Total Bookings: {$result['total_bookings']}\n";
        echo "Total Earnings: ₹" . number_format($result['total_earnings']) . "\n";
    } else {
        echo "❌ No results returned\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?> 