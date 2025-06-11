<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

echo "Quick Commission Check\n";
echo "======================\n\n";

$db = Database::getInstance()->getConnection();

// Check current bookings
echo "Current Bookings:\n";
$stmt = $db->query("
    SELECT b.booking_id, b.total_amount, b.commission_amount, b.provider_amount, 
           p.title, u.first_name, u.last_name
    FROM bookings b 
    JOIN packages p ON b.package_id = p.id
    JOIN users u ON p.provider_id = u.id
    LIMIT 5
");

while ($row = $stmt->fetch()) {
    echo "Booking: {$row['booking_id']}\n";
    echo "Total: ₹{$row['total_amount']}\n";
    echo "Commission: ₹{$row['commission_amount']}\n";
    echo "Provider: ₹{$row['provider_amount']}\n";
    echo "Provider: {$row['first_name']} {$row['last_name']}\n";
    echo "Package: {$row['title']}\n\n";
}

// Check commission rates
echo "Provider Commission Rates:\n";
$stmt = $db->query("
    SELECT u.id, u.first_name, u.last_name, 
           COALESCE(pp.commission_rate, 10) as rate
    FROM users u 
    LEFT JOIN provider_profiles pp ON u.id = pp.user_id
    WHERE u.role = 'provider'
");

while ($row = $stmt->fetch()) {
    echo "Provider: {$row['first_name']} {$row['last_name']} - Rate: {$row['rate']}%\n";
}

// Admin commission stats
echo "\nAdmin Commission Stats:\n";
$stmt = $db->query("
    SELECT SUM(commission_amount) as total_commission,
           SUM(total_amount) as total_revenue,
           COUNT(*) as total_bookings
    FROM bookings WHERE payment_status = 'paid'
");

$stats = $stmt->fetch();
echo "Total Revenue: ₹{$stats['total_revenue']}\n";
echo "Total Commission: ₹{$stats['total_commission']}\n";
echo "Total Bookings: {$stats['total_bookings']}\n";
echo "Avg Rate: " . ($stats['total_revenue'] > 0 ? round(($stats['total_commission'] / $stats['total_revenue']) * 100, 2) : 0) . "%\n";
?> 