<?php
// Complete Demo Data Population for TripBazaar
require_once 'config/database.php';

echo "Creating Complete Demo Data for TripBazaar...\n";

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    // Start transaction
    $conn->beginTransaction();
    
    $password = password_hash('password123', PASSWORD_DEFAULT);
    
    // 1. ADD CUSTOMERS
    echo "1. Adding customer users...\n";
    $customers = [
        ['john.customer@gmail.com', 'John', 'Doe', '+1-555-1001'],
        ['jane.smith@gmail.com', 'Jane', 'Smith', '+1-555-1002'],
        ['mike.wilson@gmail.com', 'Mike', 'Wilson', '+1-555-1003'],
        ['david.brown@gmail.com', 'David', 'Brown', '+1-555-1005'],
        ['lisa.davis@gmail.com', 'Lisa', 'Davis', '+1-555-1006'],
        ['tom.miller@gmail.com', 'Tom', 'Miller', '+1-555-1007'],
        ['amy.garcia@gmail.com', 'Amy', 'Garcia', '+1-555-1008']
    ];
    
    $customerStmt = $conn->prepare("INSERT INTO users (email, password, first_name, last_name, phone, role, status) VALUES (?, ?, ?, ?, ?, 'customer', 'active')");
    foreach ($customers as $customer) {
        $customerStmt->execute([$customer[0], $password, $customer[1], $customer[2], $customer[3]]);
        echo "Added customer: {$customer[1]} {$customer[2]}\n";
    }
    
    // 2. ADD MORE PROVIDERS
    echo "\n2. Adding more provider users...\n";
    $moreProviders = [
        ['family.fun@tripbazaar.com', 'David', 'Kumar', '+1-555-2004'],
        ['beach.paradise@tripbazaar.com', 'Lisa', 'Chen', '+1-555-2005'],
        ['mountain.trails@tripbazaar.com', 'Robert', 'Patel', '+1-555-2006'],
        ['cultural.journeys@tripbazaar.com', 'Priya', 'Singh', '+1-555-2007'],
        ['wildlife.safaris@tripbazaar.com', 'James', 'Anderson', '+1-555-2008']
    ];
    
    $providerStmt = $conn->prepare("INSERT INTO users (email, password, first_name, last_name, phone, role, status) VALUES (?, ?, ?, ?, ?, 'provider', 'active')");
    foreach ($moreProviders as $provider) {
        try {
            $providerStmt->execute([$provider[0], $password, $provider[1], $provider[2], $provider[3]]);
            echo "Added provider: {$provider[1]} {$provider[2]}\n";
        } catch (Exception $e) {
            echo "Provider {$provider[1]} {$provider[2]} already exists\n";
        }
    }
    
    // 3. ADD PROVIDER PROFILES
    echo "\n3. Adding provider profiles...\n";
    $profiles = [
        ['adventure.hub@tripbazaar.com', 'Adventure Hub India', 'ADV-LIC-2018-005678', 'Gurgaon', 9.00],
        ['luxury.escapes@tripbazaar.com', 'Elite Luxury Escapes', 'LUX-LIC-2017-009876', 'Bangalore', 7.00],
        ['family.fun@tripbazaar.com', 'Happy Family Travels', 'FAM-LIC-2020-112233', 'Pune', 9.50],
        ['beach.paradise@tripbazaar.com', 'Coastal Paradise Tours', 'BCH-LIC-2019-445566', 'Kochi', 8.00],
        ['mountain.trails@tripbazaar.com', 'Himalayan Mountain Trails', 'MTN-LIC-2018-778899', 'Shimla', 8.75],
        ['cultural.journeys@tripbazaar.com', 'Heritage Cultural Tours', 'CUL-LIC-2019-334455', 'Jaipur', 9.25],
        ['wildlife.safaris@tripbazaar.com', 'Wild India Safaris', 'WLD-LIC-2020-667788', 'Nagpur', 8.25]
    ];
    
    $profileStmt = $conn->prepare("
        INSERT INTO provider_profiles 
        (user_id, company_name, description, license_number, address, city, state, country, 
         bank_name, account_number, account_holder_name, ifsc_code, commission_rate, is_verified) 
        VALUES 
        ((SELECT id FROM users WHERE email = ?), ?, 'Professional travel services provider', ?, ?, ?, 'Maharashtra', 'India', 'SBI Bank', '123456789', ?, 'SBIN0001234', ?, TRUE)
    ");
    
    foreach ($profiles as $profile) {
        try {
            $profileStmt->execute([
                $profile[0], $profile[1], $profile[2], 'Business Address, ' . $profile[3], 
                $profile[3], $profile[1], $profile[4]
            ]);
            echo "Added profile: {$profile[1]}\n";
        } catch (Exception $e) {
            echo "Profile {$profile[1]} already exists\n";
        }
    }
    
    // 4. ADD PACKAGES
    echo "\n4. Adding packages...\n";
    $packages = [
        ['adventure.hub@tripbazaar.com', 1, 'Rishikesh Adventure Sports', 'rishikesh-adventure-sports', 'Rishikesh', 4, 15000.00, 8000.00, true, 4.7],
        ['adventure.hub@tripbazaar.com', 1, 'Manali Trekking Expedition', 'manali-trekking-expedition', 'Manali', 7, 22000.00, 12000.00, false, 4.6],
        ['luxury.escapes@tripbazaar.com', 8, 'Rajasthan Royal Experience', 'rajasthan-royal-experience', 'Rajasthan', 8, 120000.00, 40000.00, true, 4.9],
        ['family.fun@tripbazaar.com', 3, 'Goa Family Beach Holiday', 'goa-family-beach-holiday', 'Goa', 5, 35000.00, 20000.00, true, 4.6],
        ['beach.paradise@tripbazaar.com', 4, 'Andaman Tropical Paradise', 'andaman-tropical-paradise', 'Andaman Islands', 6, 48000.00, 24000.00, true, 4.7],
        ['mountain.trails@tripbazaar.com', 5, 'Himachal Hill Station Tour', 'himachal-hill-station-tour', 'Himachal Pradesh', 8, 32000.00, 18000.00, true, 4.5],
        ['cultural.journeys@tripbazaar.com', 6, 'Golden Triangle Cultural Tour', 'golden-triangle-cultural-tour', 'Delhi-Agra-Jaipur', 6, 25000.00, 15000.00, true, 4.6],
        ['wildlife.safaris@tripbazaar.com', 7, 'Ranthambore Tiger Safari', 'ranthambore-tiger-safari', 'Ranthambore', 4, 22000.00, 12000.00, true, 4.8]
    ];
    
    $packageStmt = $conn->prepare("
        INSERT INTO packages 
        (provider_id, category_id, title, slug, description, short_description, destination, duration_days, max_guests, 
         base_price, child_price, extra_room_price, inclusions, exclusions, terms_conditions, featured_image, 
         is_featured, rating, total_reviews, total_bookings) 
        VALUES 
        ((SELECT id FROM users WHERE email = ?), ?, ?, ?, ?, ?, ?, ?, 20, ?, ?, 5000.00, 
         'Accommodation, Meals, Transportation, Guide', 'Airfare, Personal expenses', 
         'Advance booking recommended', '/assets/images/packages/default.jpg', ?, ?, 50, 25)
    ");
    
    foreach ($packages as $package) {
        try {
            $packageStmt->execute([
                $package[0], $package[1], $package[2], $package[3], 
                'Amazing travel experience in ' . $package[4], 
                'Great package for ' . $package[4], $package[4], $package[5], 
                $package[6], $package[7], $package[8], $package[9]
            ]);
            echo "Added package: {$package[2]}\n";
        } catch (Exception $e) {
            echo "Package {$package[2]} already exists\n";
        }
    }
    
    // 5. ADD BOOKINGS
    echo "\n5. Adding sample bookings...\n";
    $bookings = [
        ['romantic-goa-honeymoon', 'john.customer@gmail.com', 2, 0, 90000.00, 'completed', 'paid'],
        ['kashmir-paradise-couples', 'jane.smith@gmail.com', 2, 0, 104000.00, 'completed', 'paid'],
        ['rishikesh-adventure-sports', 'mike.wilson@gmail.com', 3, 1, 53000.00, 'completed', 'paid'],
        ['goa-family-beach-holiday', 'david.brown@gmail.com', 2, 2, 110000.00, 'confirmed', 'paid'],
        ['golden-triangle-cultural-tour', 'lisa.davis@gmail.com', 4, 0, 100000.00, 'pending', 'pending']
    ];
    
    $bookingStmt = $conn->prepare("
        INSERT INTO bookings 
        (booking_id, package_id, customer_id, customer_name, customer_email, customer_phone, 
         adults_count, children_count, extra_rooms, base_amount, extra_room_amount, total_amount, 
         commission_amount, provider_amount, status, payment_status, payment_method, booking_date, travel_date) 
        VALUES 
        (?, (SELECT id FROM packages WHERE slug = ?), 
         (SELECT id FROM users WHERE email = ?), 
         (SELECT CONCAT(first_name, ' ', last_name) FROM users WHERE email = ?),
         ?, (SELECT phone FROM users WHERE email = ?),
         ?, ?, 0, ?, 0, ?, ?, ?, ?, ?, 'online', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY))
    ");
    
    foreach ($bookings as $booking) {
        $bookingId = 'TB' . date('Y') . sprintf('%06d', rand(100000, 999999));
        $commissionAmount = $booking[4] * 0.10;
        $providerAmount = $booking[4] - $commissionAmount;
        
        try {
            $bookingStmt->execute([
                $bookingId, $booking[0], $booking[1], $booking[1], $booking[1], $booking[1],
                $booking[2], $booking[3], $booking[4], $booking[4], $commissionAmount, 
                $providerAmount, $booking[5], $booking[6]
            ]);
            echo "Added booking: $bookingId\n";
        } catch (Exception $e) {
            echo "Booking error (may be duplicate): " . substr($e->getMessage(), 0, 50) . "...\n";
        }
    }
    
    // 6. ADD REVIEWS
    echo "\n6. Adding reviews...\n";
    $reviews = [
        ['romantic-goa-honeymoon', 'john.customer@gmail.com', 5, 'Perfect Honeymoon!', 'Amazing experience with Sarah\'s team. Highly recommended!'],
        ['kashmir-paradise-couples', 'jane.smith@gmail.com', 5, 'Kashmir is Paradise!', 'Beautiful houseboat and excellent service throughout.'],
        ['rishikesh-adventure-sports', 'mike.wilson@gmail.com', 4, 'Thrilling Adventure!', 'Great adventure package with professional guides.']
    ];
    
    $reviewStmt = $conn->prepare("
        INSERT INTO reviews 
        (package_id, booking_id, customer_id, rating, title, review_text, is_approved) 
        VALUES 
        ((SELECT id FROM packages WHERE slug = ?),
         (SELECT b.id FROM bookings b 
          JOIN packages p ON b.package_id = p.id 
          JOIN users u ON b.customer_id = u.id 
          WHERE p.slug = ? AND u.email = ? LIMIT 1),
         (SELECT id FROM users WHERE email = ?), ?, ?, ?, TRUE)
    ");
    
    foreach ($reviews as $review) {
        try {
            $reviewStmt->execute([
                $review[0], $review[0], $review[1], $review[1], 
                $review[2], $review[3], $review[4]
            ]);
            echo "Added review: {$review[3]}\n";
        } catch (Exception $e) {
            echo "Review error: " . substr($e->getMessage(), 0, 50) . "...\n";
        }
    }
    
    // 7. ADD BANNERS
    echo "\n7. Adding homepage banners...\n";
    $banners = [
        ['Discover Amazing Destinations', 'Book your dream vacation with expert providers', '/assets/images/banners/banner1.jpg', 'Explore Packages', '/packages'],
        ['Romantic Getaways', 'Create unforgettable memories with your loved one', '/assets/images/banners/banner2.jpg', 'View Romantic Packages', '/packages?category=romantic'],
        ['Adventure Awaits', 'Experience thrilling adventures across destinations', '/assets/images/banners/banner3.jpg', 'Book Adventure', '/packages?category=adventure']
    ];
    
    $bannerStmt = $conn->prepare("INSERT INTO banners (title, subtitle, image_url, button_text, button_link, is_active, sort_order) VALUES (?, ?, ?, ?, ?, TRUE, ?)");
    
    $sortOrder = 1;
    foreach ($banners as $banner) {
        try {
            $bannerStmt->execute([$banner[0], $banner[1], $banner[2], $banner[3], $banner[4], $sortOrder++]);
            echo "Added banner: {$banner[0]}\n";
        } catch (Exception $e) {
            echo "Banner {$banner[0]} already exists\n";
        }
    }
    
    // Commit transaction
    $conn->commit();
    
    echo "\n✅ COMPLETE DEMO DATA CREATION SUCCESSFUL!\n";
    echo "Database is now fully populated with:\n";
    echo "- Multiple providers including Sarah Williams\n";
    echo "- Customer accounts\n";
    echo "- Travel packages across all categories\n";
    echo "- Sample bookings and reviews\n";
    echo "- Homepage banners\n";
    echo "\n🔑 All accounts use password: password123\n";
    
} catch (Exception $e) {
    $conn->rollback();
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
