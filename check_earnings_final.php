<?php
require_once 'config/database.php';

echo "Final Earnings Dashboard Check\n";
echo "==============================\n\n";

try {
    $pdo = getDB();
    
    // Get all providers with their earnings
    $stmt = $pdo->query("
        SELECT u.id as user_id, CONCAT(u.first_name, ' ', u.last_name) as name, pp.id as provider_id, pp.company_name
        FROM users u
        JOIN provider_profiles pp ON u.id = pp.user_id
        WHERE u.role = 'provider'
    ");
    $providers = $stmt->fetchAll();
    
    foreach ($providers as $provider) {
        echo "Provider: {$provider['name']} ({$provider['company_name']})\n";
        echo "User ID: {$provider['user_id']}, Provider ID: {$provider['provider_id']}\n";
        
        // Get total earnings from bookings using the actual user_id
        $stmt2 = $pdo->prepare("
            SELECT COALESCE(SUM(b.provider_amount), 0) as total_earnings
            FROM bookings b
            JOIN packages p ON b.package_id = p.id
            WHERE p.provider_id = ? AND b.payment_status = 'paid'
        ");
        $stmt2->execute([$provider['user_id']]);  // Use user_id not provider_id
        $earnings = $stmt2->fetch();
        
        echo "Total Earnings: ₹" . number_format($earnings['total_earnings'], 2) . "\n";
        
        // Get booking count
        $stmt3 = $pdo->prepare("
            SELECT COUNT(*) as booking_count
            FROM bookings b
            JOIN packages p ON b.package_id = p.id
            WHERE p.provider_id = ?
        ");
        $stmt3->execute([$provider['user_id']]);
        $bookingCount = $stmt3->fetch();
        
        echo "Total Bookings: {$bookingCount['booking_count']}\n\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 