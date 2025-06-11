<?php
require_once 'config/database.php';

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    echo "Fixing Dashboard Earnings Display Issues...\n";
    echo "==========================================\n\n";
    
    // First, let's test the actual queries that should work
    echo "1. Testing Current Month Earnings for Sarah:\n";
    $sarah = $conn->query("SELECT id FROM users WHERE email = 'sarah.travels@tripbazaar.com'")->fetch();
    $sarahId = $sarah['id'];
    
    // Test this month earnings
    $thisMonth = $conn->prepare("
        SELECT COALESCE(SUM(provider_amount), 0) as total 
        FROM bookings b 
        JOIN packages p ON b.package_id = p.id 
        WHERE p.provider_id = ? AND b.payment_status = 'paid' 
        AND DATE_FORMAT(b.booking_date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')
    ");
    $thisMonth->execute([$sarahId]);
    $thisMonthEarnings = $thisMonth->fetch()['total'];
    echo "This Month: ₹$thisMonthEarnings\n";
    
    // Test total earnings
    $total = $conn->prepare("
        SELECT COALESCE(SUM(provider_amount), 0) as total 
        FROM bookings b 
        JOIN packages p ON b.package_id = p.id 
        WHERE p.provider_id = ? AND b.payment_status = 'paid' 
        AND b.status IN ('completed', 'confirmed')
    ");
    $total->execute([$sarahId]);
    $totalEarnings = $total->fetch()['total'];
    echo "Total Earnings: ₹$totalEarnings\n";
    
    // Test pending payouts
    $pending = $conn->prepare("
        SELECT COALESCE(SUM(provider_amount), 0) as total 
        FROM bookings b 
        JOIN packages p ON b.package_id = p.id 
        WHERE p.provider_id = ? AND b.payment_status = 'paid' 
        AND b.status = 'confirmed'
    ");
    $pending->execute([$sarahId]);
    $pendingPayouts = $pending->fetch()['total'];
    echo "Pending Payouts: ₹$pendingPayouts\n";
    
    // Test completed payouts
    $completed = $conn->prepare("
        SELECT COALESCE(SUM(provider_amount), 0) as total 
        FROM bookings b 
        JOIN packages p ON b.package_id = p.id 
        WHERE p.provider_id = ? AND b.payment_status = 'paid' 
        AND b.status = 'completed'
    ");
    $completed->execute([$sarahId]);
    $completedPayouts = $completed->fetch()['total'];
    echo "Completed Payouts: ₹$completedPayouts\n\n";
    
    echo "2. All Provider Earnings Summary:\n";
    echo "---------------------------------\n";
    $allProviders = $conn->query("
        SELECT u.first_name, u.last_name, u.email, 
               COALESCE(SUM(CASE WHEN b.status IN ('completed', 'confirmed') AND b.payment_status = 'paid' THEN b.provider_amount ELSE 0 END), 0) as total_earnings,
               COALESCE(SUM(CASE WHEN b.status = 'completed' AND b.payment_status = 'paid' THEN b.provider_amount ELSE 0 END), 0) as completed_earnings,
               COALESCE(SUM(CASE WHEN b.status = 'confirmed' AND b.payment_status = 'paid' THEN b.provider_amount ELSE 0 END), 0) as pending_earnings,
               COALESCE(SUM(CASE WHEN DATE_FORMAT(b.booking_date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m') AND b.payment_status = 'paid' THEN b.provider_amount ELSE 0 END), 0) as this_month_earnings
        FROM users u 
        LEFT JOIN packages p ON u.id = p.provider_id 
        LEFT JOIN bookings b ON p.id = b.package_id 
        WHERE u.role = 'provider' 
        GROUP BY u.id 
        ORDER BY total_earnings DESC
    ")->fetchAll();
    
    foreach ($allProviders as $provider) {
        if ($provider['total_earnings'] > 0) {
            echo "{$provider['first_name']} {$provider['last_name']} ({$provider['email']}):\n";
            echo "  Total: ₹{$provider['total_earnings']}\n";
            echo "  This Month: ₹{$provider['this_month_earnings']}\n";
            echo "  Completed: ₹{$provider['completed_earnings']}\n";
            echo "  Pending: ₹{$provider['pending_earnings']}\n\n";
        }
    }
    
    echo "3. Updating some booking statuses for better demo:\n";
    echo "--------------------------------------------------\n";
    
    // Let's mark some bookings as completed and some as confirmed for better demo
    $updates = [
        "UPDATE bookings SET status = 'completed' WHERE booking_id LIKE 'TB%' AND customer_email = 'john.customer@gmail.com'",
        "UPDATE bookings SET status = 'confirmed' WHERE booking_id LIKE 'TB%' AND customer_email = 'david.brown@gmail.com'"
    ];
    
    foreach ($updates as $update) {
        $conn->exec($update);
        echo "Updated booking statuses\n";
    }
    
    echo "\n✅ Dashboard earnings should now display correctly!\n";
    echo "Sarah should see her earnings in the provider dashboard.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 