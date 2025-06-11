<?php
require_once 'config/database.php';

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    echo "TripBazaar Demo Data Summary\n";
    echo str_repeat("=", 50) . "\n\n";
    
    // Users Summary
    echo "USERS:\n";
    echo "------\n";
    $users = $conn->query("SELECT role, COUNT(*) as count FROM users GROUP BY role")->fetchAll();
    foreach ($users as $user) {
        echo "- " . ucfirst($user['role']) . "s: " . $user['count'] . "\n";
    }
    
    echo "\nCUSTOMERS:\n";
    echo "----------\n";
    $customers = $conn->query("SELECT first_name, last_name, email FROM users WHERE role='customer' ORDER BY first_name")->fetchAll();
    foreach ($customers as $customer) {
        echo "- {$customer['first_name']} {$customer['last_name']} ({$customer['email']})\n";
    }
    
    echo "\nPROVIDERS WITH COMPANIES:\n";
    echo "-------------------------\n";
    $providers = $conn->query("
        SELECT u.first_name, u.last_name, u.email, pp.company_name, pp.city, pp.commission_rate
        FROM users u 
        LEFT JOIN provider_profiles pp ON u.id = pp.user_id 
        WHERE u.role='provider' 
        ORDER BY u.first_name
    ")->fetchAll();
    
    foreach ($providers as $provider) {
        $company = $provider['company_name'] ?: 'No Profile';
        $commission = $provider['commission_rate'] ? $provider['commission_rate'] . '%' : 'N/A';
        echo "- {$provider['first_name']} {$provider['last_name']}\n";
        echo "  Email: {$provider['email']}\n";
        echo "  Company: $company\n";
        echo "  Commission: $commission\n\n";
    }
    
    echo "TRAVEL PACKAGES:\n";
    echo "----------------\n";
    $packages = $conn->query("
        SELECT p.title, p.destination, p.base_price, p.duration_days, u.first_name, u.last_name, pp.company_name, p.is_featured
        FROM packages p
        JOIN users u ON p.provider_id = u.id
        LEFT JOIN provider_profiles pp ON u.id = pp.user_id
        ORDER BY p.title
    ")->fetchAll();
    
    foreach ($packages as $package) {
        $featured = $package['is_featured'] ? ' [FEATURED]' : '';
        $company = $package['company_name'] ?: 'No Company';
        echo "- {$package['title']}$featured\n";
        echo "  Provider: {$package['first_name']} {$package['last_name']} ($company)\n";
        echo "  Destination: {$package['destination']}\n";
        echo "  Duration: {$package['duration_days']} days\n";
        echo "  Price: ₹" . number_format($package['base_price']) . "\n\n";
    }
    
    echo "BOOKINGS:\n";
    echo "---------\n";
    $bookings = $conn->query("
        SELECT b.booking_id, b.customer_name, p.title, b.total_amount, b.status, b.travel_date
        FROM bookings b
        JOIN packages p ON b.package_id = p.id
        ORDER BY b.booking_date DESC
    ")->fetchAll();
    
    if (count($bookings) > 0) {
        foreach ($bookings as $booking) {
            echo "- {$booking['booking_id']}\n";
            echo "  Customer: {$booking['customer_name']}\n";
            echo "  Package: {$booking['title']}\n";
            echo "  Amount: ₹" . number_format($booking['total_amount']) . "\n";
            echo "  Status: " . ucfirst($booking['status']) . "\n";
            echo "  Travel Date: {$booking['travel_date']}\n\n";
        }
    } else {
        echo "No bookings found.\n\n";
    }
    
    echo "HOMEPAGE BANNERS:\n";
    echo "-----------------\n";
    $banners = $conn->query("SELECT title, subtitle, button_text FROM banners WHERE is_active=1 ORDER BY sort_order")->fetchAll();
    foreach ($banners as $banner) {
        echo "- {$banner['title']}\n";
        echo "  Subtitle: {$banner['subtitle']}\n";
        echo "  Button: {$banner['button_text']}\n\n";
    }
    
    echo str_repeat("=", 50) . "\n";
    echo "QUICK LOGIN CREDENTIALS:\n";
    echo "- Sarah Williams (Provider): sarah.travels@tripbazaar.com / password123\n";
    echo "- John Doe (Customer): john.customer@gmail.com / password123\n";
    echo "- Admin: admin@tripbazaar.com / password123\n";
    echo "\nWebsite: http://localhost/Tulip/\n";
    echo str_repeat("=", 50) . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 