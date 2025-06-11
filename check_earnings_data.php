<?php
require_once 'config/database.php';

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    echo "Earnings Dashboard Diagnostic\n";
    echo "============================\n\n";
    
    // Check all bookings
    echo "1. ALL BOOKINGS:\n";
    echo "----------------\n";
    $bookings = $conn->query("
        SELECT b.*, p.title as package_title, u.first_name, u.last_name, u.email as provider_email
        FROM bookings b
        JOIN packages p ON b.package_id = p.id
        JOIN users u ON p.provider_id = u.id
        ORDER BY b.booking_date DESC
    ")->fetchAll();
    
    foreach ($bookings as $booking) {
        echo "Booking ID: {$booking['booking_id']}\n";
        echo "Provider: {$booking['first_name']} {$booking['last_name']} ({$booking['provider_email']})\n";
        echo "Package: {$booking['package_title']}\n";
        echo "Total Amount: ₹{$booking['total_amount']}\n";
        echo "Provider Amount: ₹{$booking['provider_amount']}\n";
        echo "Commission: ₹{$booking['commission_amount']}\n";
        echo "Status: {$booking['status']}\n";
        echo "Payment Status: {$booking['payment_status']}\n";
        echo "Booking Date: {$booking['booking_date']}\n";
        echo "Travel Date: {$booking['travel_date']}\n\n";
    }
    
    // Check Sarah's earnings specifically
    echo "2. SARAH'S BOOKINGS & EARNINGS:\n";
    echo "--------------------------------\n";
    $sarah = $conn->query("SELECT id FROM users WHERE email = 'sarah.travels@tripbazaar.com'")->fetch();
    if ($sarah) {
        $sarahId = $sarah['id'];
        echo "Sarah's User ID: $sarahId\n\n";
        
        $sarahBookings = $conn->query("
            SELECT b.*, p.title
            FROM bookings b
            JOIN packages p ON b.package_id = p.id
            WHERE p.provider_id = $sarahId
        ")->fetchAll();
        
        if (count($sarahBookings) > 0) {
            $totalEarnings = 0;
            foreach ($sarahBookings as $booking) {
                echo "- {$booking['title']}: ₹{$booking['provider_amount']} ({$booking['status']})\n";
                if ($booking['status'] == 'completed' || $booking['status'] == 'confirmed') {
                    $totalEarnings += $booking['provider_amount'];
                }
            }
            echo "Total Earnings: ₹$totalEarnings\n\n";
        } else {
            echo "No bookings found for Sarah\n\n";
        }
    }
    
    // Check current month calculation
    echo "3. CURRENT MONTH CHECK:\n";
    echo "-----------------------\n";
    $currentMonth = date('Y-m');
    echo "Current month: $currentMonth\n";
    
    $monthlyBookings = $conn->query("
        SELECT COUNT(*) as count, SUM(provider_amount) as total
        FROM bookings b
        JOIN packages p ON b.package_id = p.id
        WHERE DATE_FORMAT(b.booking_date, '%Y-%m') = '$currentMonth'
        AND b.status IN ('completed', 'confirmed')
    ")->fetch();
    
    echo "Bookings this month: {$monthlyBookings['count']}\n";
    echo "Total earnings this month: ₹" . ($monthlyBookings['total'] ?: 0) . "\n\n";
    
    // Check date formats
    echo "4. DATE ANALYSIS:\n";
    echo "-----------------\n";
    $dates = $conn->query("
        SELECT booking_date, travel_date, 
               DATE_FORMAT(booking_date, '%Y-%m') as booking_month,
               DATE_FORMAT(travel_date, '%Y-%m') as travel_month
        FROM bookings 
        LIMIT 5
    ")->fetchAll();
    
    foreach ($dates as $date) {
        echo "Booking: {$date['booking_date']} (Month: {$date['booking_month']})\n";
        echo "Travel: {$date['travel_date']} (Month: {$date['travel_month']})\n\n";
    }
    
    echo "5. PROVIDER EARNINGS SUMMARY:\n";
    echo "-----------------------------\n";
    $providerEarnings = $conn->query("
        SELECT u.first_name, u.last_name, u.email,
               COUNT(b.id) as total_bookings,
               SUM(b.provider_amount) as total_earnings,
               SUM(CASE WHEN b.status = 'completed' THEN b.provider_amount ELSE 0 END) as completed_earnings
        FROM users u
        LEFT JOIN packages p ON u.id = p.provider_id
        LEFT JOIN bookings b ON p.id = b.package_id
        WHERE u.role = 'provider'
        GROUP BY u.id, u.first_name, u.last_name, u.email
        HAVING total_bookings > 0
        ORDER BY total_earnings DESC
    ")->fetchAll();
    
    foreach ($providerEarnings as $provider) {
        echo "{$provider['first_name']} {$provider['last_name']} ({$provider['email']}):\n";
        echo "  Bookings: {$provider['total_bookings']}\n";
        echo "  Total Earnings: ₹{$provider['total_earnings']}\n";
        echo "  Completed Earnings: ₹{$provider['completed_earnings']}\n\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 