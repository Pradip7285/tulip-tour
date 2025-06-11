<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== EARNINGS DASHBOARD CHECK ===\n";

require_once 'config/database.php';

try {
    $pdo = getDB();
    echo "✅ Database connected successfully\n";
    
    // Check users
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users WHERE role = 'provider'");
    $providerCount = $stmt->fetch()['count'];
    echo "Provider users: $providerCount\n";
    
    // Check provider companies
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM providers");
    $companyCount = $stmt->fetch()['count'];
    echo "Provider companies: $companyCount\n";
    
    // Check packages
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM packages");
    $packageCount = $stmt->fetch()['count'];
    echo "Total packages: $packageCount\n";
    
    // Check bookings
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM bookings");
    $bookingCount = $stmt->fetch()['count'];
    echo "Total bookings: $bookingCount\n";
    
    if ($bookingCount > 0) {
        // Get sample booking data
        $stmt = $pdo->query("
            SELECT b.id, b.total_amount, b.status, b.booking_date, p.title 
            FROM bookings b 
            JOIN packages p ON b.package_id = p.id 
            LIMIT 3
        ");
        $bookings = $stmt->fetchAll();
        
        echo "\n--- Sample Bookings ---\n";
        foreach ($bookings as $booking) {
            echo "Booking {$booking['id']}: {$booking['title']} - ₹{$booking['total_amount']} ({$booking['status']})\n";
        }
        
        // Calculate total earnings
        $stmt = $pdo->query("
            SELECT SUM(b.total_amount * 0.85) as total_earnings
            FROM bookings b
            WHERE b.status = 'completed'
        ");
        $totalEarnings = $stmt->fetch()['total_earnings'] ?? 0;
        echo "\nTotal Earnings (All Providers): ₹" . number_format($totalEarnings) . "\n";
        
        // Check earnings for first provider
        $stmt = $pdo->query("
            SELECT p.id, p.company_name 
            FROM providers p 
            LIMIT 1
        ");
        $provider = $stmt->fetch();
        
        if ($provider) {
            $stmt = $pdo->prepare("
                SELECT SUM(b.total_amount * 0.85) as provider_earnings
                FROM bookings b
                JOIN packages pk ON b.package_id = pk.id
                WHERE pk.provider_id = ? AND b.status = 'completed'
            ");
            $stmt->execute([$provider['id']]);
            $providerEarnings = $stmt->fetch()['provider_earnings'] ?? 0;
            
            echo "Earnings for {$provider['company_name']}: ₹" . number_format($providerEarnings) . "\n";
        }
    }
    
    echo "\n✅ Check completed successfully\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?> 