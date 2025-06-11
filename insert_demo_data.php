<?php
// Direct PHP insertion of demo provider data
require_once 'config/database.php';

echo "Inserting Demo Provider Data...\n";

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    // Start transaction
    $conn->beginTransaction();
    
    echo "Inserting provider users...\n";
    
    // Insert provider users
    $users = [
        ['sarah.travels@tripbazaar.com', 'Sarah', 'Williams', '+1-555-2001'],
        ['adventure.hub@tripbazaar.com', 'Michael', 'Rodriguez', '+1-555-2002'],
        ['luxury.escapes@tripbazaar.com', 'Emma', 'Thompson', '+1-555-2003']
    ];
    
    $userStmt = $conn->prepare("INSERT INTO users (email, password, first_name, last_name, phone, role, status) VALUES (?, ?, ?, ?, ?, 'provider', 'active')");
    $password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; // password123
    
    foreach ($users as $user) {
        $userStmt->execute([$user[0], $password, $user[1], $user[2], $user[3]]);
        echo "Inserted user: {$user[1]} {$user[2]}\n";
    }
    
    echo "Inserting provider profiles...\n";
    
    // Insert Sarah's profile
    $profileStmt = $conn->prepare("
        INSERT INTO provider_profiles 
        (user_id, company_name, description, license_number, address, city, state, country, 
         bank_name, account_number, account_holder_name, ifsc_code, commission_rate, is_verified) 
        VALUES 
        ((SELECT id FROM users WHERE email = ?), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $profileStmt->execute([
        'sarah.travels@tripbazaar.com',
        'Sarah\'s Romantic Escapes',
        'Specializing in creating magical romantic getaways for couples. With over 8 years of experience in the travel industry.',
        'RT-LIC-2019-001234',
        'Suite 205, Romance Tower, Love Lane',
        'Mumbai',
        'Maharashtra',
        'India',
        'HDFC Bank',
        '50100123456789',
        'Sarah Williams',
        'HDFC0001234',
        8.50,
        true
    ]);
    echo "Inserted profile: Sarah's Romantic Escapes\n";
    
    echo "Inserting Sarah's packages...\n";
    
    // Insert Sarah's packages
    $packageStmt = $conn->prepare("
        INSERT INTO packages 
        (provider_id, category_id, title, slug, description, short_description, destination, duration_days, max_guests, 
         base_price, child_price, extra_room_price, inclusions, exclusions, terms_conditions, featured_image, 
         is_featured, rating, total_reviews, total_bookings) 
        VALUES 
        ((SELECT id FROM users WHERE email = ?), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    // Goa Honeymoon Package
    $packageStmt->execute([
        'sarah.travels@tripbazaar.com',
        2, // Romantic category
        'Romantic Goa Honeymoon',
        'romantic-goa-honeymoon',
        'Experience the perfect honeymoon in beautiful Goa with beachside luxury, candlelight dinners, and romantic sunsets.',
        'Perfect honeymoon package in Goa with luxury beach resort, spa, and romantic experiences',
        'Goa',
        5,
        4,
        45000.00,
        15000.00,
        8000.00,
        'Luxury beach resort accommodation, Daily breakfast and dinner, Couple spa session, Private candlelight dinner on beach',
        'Airfare, Lunch, Personal expenses, Adventure activities, Tips and gratuities',
        'Booking must be made 15 days in advance. 50% advance payment required.',
        '/assets/images/packages/goa-honeymoon.jpg',
        true,
        4.8,
        156,
        89
    ]);
    echo "Inserted package: Romantic Goa Honeymoon\n";
    
    // Kashmir Package
    $packageStmt->execute([
        'sarah.travels@tripbazaar.com',
        2, // Romantic category
        'Kashmir Paradise for Couples',
        'kashmir-paradise-couples',
        'Discover the beauty of Kashmir with your loved one. Enjoy houseboat stays, Shikara rides, beautiful gardens.',
        'Romantic Kashmir getaway with houseboat stays and scenic beauty',
        'Kashmir',
        6,
        4,
        52000.00,
        18000.00,
        9000.00,
        'Deluxe houseboat accommodation, Dal Lake Shikara ride, Mughal Gardens tour, All meals',
        'Airfare, Pony rides, Shopping, Personal expenses, Tips',
        'Valid for couples only. Weather dependent activities.',
        '/assets/images/packages/kashmir-couples.jpg',
        true,
        4.9,
        203,
        134
    ]);
    echo "Inserted package: Kashmir Paradise for Couples\n";
    
    // Commit transaction
    $conn->commit();
    
    echo "\n✅ Demo data insertion completed successfully!\n";
    echo "Sarah Williams and her romantic packages have been added.\n";
    echo "Login: sarah.travels@tripbazaar.com / password123\n\n";
    
} catch (Exception $e) {
    $conn->rollback();
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?> 