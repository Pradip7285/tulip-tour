<?php
require_once 'config/database.php';

echo "TripBazaar Complete Demo Data Population\n";
echo "========================================\n\n";

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    $conn->beginTransaction();
    
    $password = password_hash('password123', PASSWORD_DEFAULT);
    
    // 1. ADD CUSTOMER USERS
    echo "1. Adding customer users...\n";
    $customers = [
        ['john.customer@gmail.com', 'John', 'Doe', '+1-555-1001'],
        ['jane.smith@gmail.com', 'Jane', 'Smith', '+1-555-1002'],
        ['mike.wilson@gmail.com', 'Mike', 'Wilson', '+1-555-1003'],
        ['david.brown@gmail.com', 'David', 'Brown', '+1-555-1005'],
        ['lisa.davis@gmail.com', 'Lisa', 'Davis', '+1-555-1006'],
        ['robert.clark@gmail.com', 'Robert', 'Clark', '+1-555-1007'],
        ['maria.garcia@gmail.com', 'Maria', 'Garcia', '+1-555-1008'],
        ['chris.johnson@gmail.com', 'Chris', 'Johnson', '+1-555-1009'],
        ['anna.white@gmail.com', 'Anna', 'White', '+1-555-1010']
    ];
    
    foreach ($customers as $customer) {
        try {
            $stmt = $conn->prepare("INSERT INTO users (email, password, first_name, last_name, phone, role, status) VALUES (?, ?, ?, ?, ?, 'customer', 'active')");
            $stmt->execute([$customer[0], $password, $customer[1], $customer[2], $customer[3]]);
            echo "   Added: {$customer[1]} {$customer[2]}\n";
        } catch (Exception $e) {
            echo "   Exists: {$customer[1]} {$customer[2]}\n";
        }
    }
    
    // 2. ADD MORE PROVIDERS
    echo "\n2. Adding provider users...\n";
    $providers = [
        ['family.fun@tripbazaar.com', 'David', 'Kumar', '+1-555-2004'],
        ['beach.paradise@tripbazaar.com', 'Lisa', 'Chen', '+1-555-2005'],
        ['mountain.trails@tripbazaar.com', 'Robert', 'Patel', '+1-555-2006'],
        ['cultural.journeys@tripbazaar.com', 'Priya', 'Singh', '+1-555-2007'],
        ['wildlife.safaris@tripbazaar.com', 'James', 'Anderson', '+1-555-2008']
    ];
    
    foreach ($providers as $provider) {
        try {
            $stmt = $conn->prepare("INSERT INTO users (email, password, first_name, last_name, phone, role, status) VALUES (?, ?, ?, ?, ?, 'provider', 'active')");
            $stmt->execute([$provider[0], $password, $provider[1], $provider[2], $provider[3]]);
            echo "   Added: {$provider[1]} {$provider[2]}\n";
        } catch (Exception $e) {
            echo "   Exists: {$provider[1]} {$provider[2]}\n";
        }
    }
    
    // 3. ADD PROVIDER PROFILES
    echo "\n3. Adding provider profiles...\n";
    $profiles = [
        ['adventure.hub@tripbazaar.com', 'Adventure Hub India', 'Your premier adventure travel specialist', 'ADV-2023-001', 'Mumbai'],
        ['luxury.escapes@tripbazaar.com', 'Elite Luxury Escapes', 'Curating extraordinary luxury experiences', 'LUX-2023-002', 'Delhi'],
        ['family.fun@tripbazaar.com', 'Happy Family Travels', 'Creating magical family memories', 'FAM-2023-003', 'Bangalore'],
        ['beach.paradise@tripbazaar.com', 'Coastal Paradise Tours', 'Discover pristine coastal destinations', 'BCH-2023-004', 'Goa'],
        ['mountain.trails@tripbazaar.com', 'Himalayan Mountain Trails', 'Explore majestic mountain ranges', 'MTN-2023-005', 'Shimla'],
        ['cultural.journeys@tripbazaar.com', 'Heritage Cultural Tours', 'Immerse in rich cultural heritage', 'CUL-2023-006', 'Jaipur'],
        ['wildlife.safaris@tripbazaar.com', 'Wild India Safaris', 'Experience incredible wildlife', 'WLD-2023-007', 'Ranthambore']
    ];
    
    foreach ($profiles as $profile) {
        try {
            $stmt = $conn->prepare("
                INSERT INTO provider_profiles 
                (user_id, company_name, description, license_number, address, city, state, country, 
                 bank_name, account_number, account_holder_name, ifsc_code, commission_rate, is_verified) 
                VALUES 
                ((SELECT id FROM users WHERE email = ?), ?, ?, ?, ?, ?, 'Maharashtra', 'India', 
                 'SBI Bank', '123456789', ?, 'SBIN0001234', 8.5, TRUE)
            ");
            $stmt->execute([$profile[0], $profile[1], $profile[2], $profile[3], 'Business Address', $profile[4], $profile[1]]);
            echo "   Added: {$profile[1]}\n";
        } catch (Exception $e) {
            echo "   Exists: {$profile[1]}\n";
        }
    }
    
    // 4. ADD PACKAGES
    echo "\n4. Adding travel packages...\n";
    $packages = [
        ['adventure.hub@tripbazaar.com', 1, 'Rishikesh Adventure Sports', 'rishikesh-adventure-sports', 'Experience thrilling adventure sports in Rishikesh', 'Rishikesh', 4, 18000, 9000, 1],
        ['adventure.hub@tripbazaar.com', 1, 'Manali Trekking Expedition', 'manali-trekking-expedition', 'Discover beautiful trekking trails in Manali', 'Manali', 6, 25000, 12000, 0],
        ['luxury.escapes@tripbazaar.com', 8, 'Rajasthan Royal Experience', 'rajasthan-royal-experience', 'Live like royalty in magnificent Rajasthan palaces', 'Rajasthan', 7, 95000, 35000, 1],
        ['family.fun@tripbazaar.com', 3, 'Goa Family Beach Holiday', 'goa-family-beach-holiday', 'Perfect family vacation with beach fun and activities', 'Goa', 5, 42000, 21000, 1],
        ['beach.paradise@tripbazaar.com', 4, 'Andaman Tropical Paradise', 'andaman-tropical-paradise', 'Escape to pristine beaches of Andaman Islands', 'Andaman', 6, 55000, 28000, 1],
        ['mountain.trails@tripbazaar.com', 5, 'Himachal Hill Station Tour', 'himachal-hill-station-tour', 'Explore scenic hill stations of Himachal Pradesh', 'Himachal', 8, 38000, 19000, 1],
        ['cultural.journeys@tripbazaar.com', 6, 'Golden Triangle Cultural Tour', 'golden-triangle-cultural-tour', 'Discover Indias rich heritage in Delhi, Agra, Jaipur', 'Delhi-Agra-Jaipur', 6, 32000, 16000, 1],
        ['wildlife.safaris@tripbazaar.com', 7, 'Ranthambore Tiger Safari', 'ranthambore-tiger-safari', 'Spot Royal Bengal Tigers in their natural habitat', 'Ranthambore', 4, 28000, 14000, 1]
    ];
    
    foreach ($packages as $package) {
        try {
            $stmt = $conn->prepare("
                INSERT INTO packages 
                (provider_id, category_id, title, slug, description, short_description, destination, 
                 duration_days, max_guests, base_price, child_price, extra_room_price, inclusions, 
                 exclusions, terms_conditions, featured_image, is_featured, rating, total_reviews, total_bookings) 
                VALUES 
                ((SELECT id FROM users WHERE email = ?), ?, ?, ?, ?, ?, ?, ?, 20, ?, ?, 5000, 
                 'Accommodation, Meals, Transportation, Guide', 'Airfare, Personal expenses', 
                 'Advance booking required', '/assets/images/packages/default.jpg', ?, 4.5, 25, 15)
            ");
            $stmt->execute([
                $package[0], $package[1], $package[2], $package[3], $package[4], 
                substr($package[4], 0, 100), $package[5], $package[6], $package[7], $package[8], $package[9]
            ]);
            echo "   Added: {$package[2]}\n";
        } catch (Exception $e) {
            echo "   Exists: {$package[2]}\n";
        }
    }
    
    // 5. ADD BOOKINGS
    echo "\n5. Adding sample bookings...\n";
    $bookings = [
        ['romantic-goa-honeymoon', 'john.customer@gmail.com', 2, 0, 90000, 'completed'],
        ['rishikesh-adventure-sports', 'mike.wilson@gmail.com', 2, 0, 36000, 'completed'],
        ['goa-family-beach-holiday', 'david.brown@gmail.com', 2, 2, 84000, 'confirmed'],
        ['golden-triangle-cultural-tour', 'lisa.davis@gmail.com', 3, 1, 80000, 'pending']
    ];
    
    foreach ($bookings as $booking) {
        try {
            $bookingId = 'TB' . date('Y') . sprintf('%06d', rand(100000, 999999));
            $commission = $booking[4] * 0.08;
            $providerAmount = $booking[4] - $commission;
            
            $stmt = $conn->prepare("
                INSERT INTO bookings 
                (booking_id, package_id, customer_id, customer_name, customer_email, customer_phone,
                 adults_count, children_count, extra_rooms, base_amount, total_amount, commission_amount,
                 provider_amount, status, payment_status, payment_method, booking_date, travel_date) 
                VALUES 
                (?, (SELECT id FROM packages WHERE slug = ?), 
                 (SELECT id FROM users WHERE email = ?),
                 (SELECT CONCAT(first_name, ' ', last_name) FROM users WHERE email = ?),
                 ?, (SELECT phone FROM users WHERE email = ?),
                 ?, ?, 0, ?, ?, ?, ?, ?, 'paid', 'online', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY))
            ");
            $stmt->execute([
                $bookingId, $booking[0], $booking[1], $booking[1], $booking[1], $booking[1],
                $booking[2], $booking[3], $booking[4], $booking[4], $commission, $providerAmount, $booking[5]
            ]);
            echo "   Added: Booking $bookingId\n";
        } catch (Exception $e) {
            echo "   Error: Booking for {$booking[0]}\n";
        }
    }
    
    // 6. ADD REVIEWS
    echo "\n6. Adding customer reviews...\n";
    $reviews = [
        ['romantic-goa-honeymoon', 'john.customer@gmail.com', 5, 'Perfect Honeymoon!', 'Amazing experience with Sarahs team'],
        ['rishikesh-adventure-sports', 'mike.wilson@gmail.com', 4, 'Thrilling Adventure!', 'Great adventure package with professional guides'],
        ['goa-family-beach-holiday', 'david.brown@gmail.com', 5, 'Excellent Family Trip!', 'Kids loved the beach activities and hotel was perfect']
    ];
    
    foreach ($reviews as $review) {
        try {
            $stmt = $conn->prepare("
                INSERT INTO reviews 
                (package_id, customer_id, rating, title, review_text, is_approved) 
                VALUES 
                ((SELECT id FROM packages WHERE slug = ?), (SELECT id FROM users WHERE email = ?), ?, ?, ?, TRUE)
            ");
            $stmt->execute([$review[0], $review[1], $review[2], $review[3], $review[4]]);
            echo "   Added: {$review[3]}\n";
        } catch (Exception $e) {
            echo "   Error: Review by {$review[1]}\n";
        }
    }
    
    // 7. ADD BANNERS
    echo "\n7. Adding homepage banners...\n";
    $banners = [
        ['Discover Amazing India', 'Book your dream vacation with trusted travel providers', '/assets/images/banner1.jpg', 'Explore Packages', '/packages'],
        ['Adventure Awaits', 'Experience thrilling adventures across incredible destinations', '/assets/images/banner2.jpg', 'View Adventures', '/packages?category=adventure'],
        ['Luxury Redefined', 'Indulge in premium travel experiences', '/assets/images/banner3.jpg', 'Luxury Packages', '/packages?category=luxury']
    ];
    
    $sortOrder = 1;
    foreach ($banners as $banner) {
        try {
            $stmt = $conn->prepare("INSERT INTO banners (title, subtitle, image_url, button_text, button_link, is_active, sort_order) VALUES (?, ?, ?, ?, ?, TRUE, ?)");
            $stmt->execute([$banner[0], $banner[1], $banner[2], $banner[3], $banner[4], $sortOrder++]);
            echo "   Added: {$banner[0]}\n";
        } catch (Exception $e) {
            echo "   Exists: {$banner[0]}\n";
        }
    }
    
    $conn->commit();
    
    // SHOW SUMMARY
    echo "\n" . str_repeat("=", 50) . "\n";
    echo "COMPLETE DEMO DATA POPULATED SUCCESSFULLY!\n";
    echo str_repeat("=", 50) . "\n\n";
    
    // Get counts
    $userCount = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $providerCount = $conn->query("SELECT COUNT(*) FROM users WHERE role='provider'")->fetchColumn();
    $customerCount = $conn->query("SELECT COUNT(*) FROM users WHERE role='customer'")->fetchColumn();
    $packageCount = $conn->query("SELECT COUNT(*) FROM packages")->fetchColumn();
    $bookingCount = $conn->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
    $reviewCount = $conn->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
    $bannerCount = $conn->query("SELECT COUNT(*) FROM banners")->fetchColumn();
    
    echo "DATABASE SUMMARY:\n";
    echo "   Total Users: $userCount\n";
    echo "   Providers: $providerCount\n";
    echo "   Customers: $customerCount\n";
    echo "   Travel Packages: $packageCount\n";
    echo "   Bookings: $bookingCount\n";
    echo "   Reviews: $reviewCount\n";
    echo "   Banners: $bannerCount\n\n";
    
    echo "LOGIN CREDENTIALS (all use password123):\n";
    echo "   Sarah Williams: sarah.travels@tripbazaar.com\n";
    echo "   John Customer: john.customer@gmail.com\n";
    echo "   Admin: admin@tripbazaar.com\n\n";
    
    echo "Access your marketplace:\n";
    echo "   Homepage: http://localhost/Tulip/\n";
    echo "   Admin Login: http://localhost/Tulip/login\n\n";
    
    echo "Your TripBazaar marketplace is now ready with complete demo data!\n";
    
} catch (Exception $e) {
    $conn->rollback();
    echo "Error: " . $e->getMessage() . "\n";
    echo "Rolling back all changes...\n";
}
?> 