<?php

// Authentication functions
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

function hasRole($role) {
    $user = getCurrentUser();
    return $user && $user['role'] === $role;
}

function requireLogin() {
    if (!isLoggedIn()) {
        redirect('/login');
    }
}

function requireRole($role) {
    requireLogin();
    if (!hasRole($role)) {
        http_response_code(403);
        die('Access denied');
    }
}

// Validation functions
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePhone($phone) {
    return preg_match('/^[\+]?[0-9\-\(\)\s]+$/', $phone);
}

function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Formatting functions
function formatPrice($price) {
    return '₹' . number_format($price, 2);
}

function formatDate($date) {
    return date('M j, Y', strtotime($date));
}

function formatDateTime($datetime) {
    return date('M j, Y g:i A', strtotime($datetime));
}

function createSlug($string) {
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
}

// Generate unique booking ID
function generateBookingId() {
    return 'TB' . strtoupper(uniqid());
}

// Calculate commission
function calculateCommission($amount, $rate = null) {
    if ($rate === null) {
        $db = getDB();
        $stmt = $db->prepare("SELECT setting_value FROM site_settings WHERE setting_key = 'default_commission'");
        $stmt->execute();
        $result = $stmt->fetch();
        $rate = $result ? $result['setting_value'] : 10;
    }
    return ($amount * $rate) / 100;
}

// Calculate commission based on provider's negotiated rate
function calculateProviderCommission($packageId, $totalAmount) {
    $db = getDB();
    
    // Get payment gateway fee
    $stmt = $db->prepare("SELECT setting_value FROM site_settings WHERE setting_key = 'payment_gateway_fee'");
    $stmt->execute();
    $gatewayFeeResult = $stmt->fetch();
    $gatewayFeeRate = $gatewayFeeResult ? floatval($gatewayFeeResult['setting_value']) : 2.5;
    
    // Calculate payment gateway fee
    $gatewayFeeAmount = ($totalAmount * $gatewayFeeRate) / 100;
    $netAmountAfterGateway = $totalAmount - $gatewayFeeAmount;
    
    // Get provider's commission rate from package and provider profile
    $stmt = $db->prepare("
        SELECT COALESCE(pp.commission_rate, ss.setting_value, 10) as commission_rate
        FROM packages p
        LEFT JOIN provider_profiles pp ON p.provider_id = pp.user_id
        LEFT JOIN site_settings ss ON ss.setting_key = 'default_commission'
        WHERE p.id = ?
    ");
    $stmt->execute([$packageId]);
    $result = $stmt->fetch();
    
    $commissionRate = $result ? $result['commission_rate'] : 10;
    
    // Calculate platform commission on net amount (after gateway fee)
    $platformCommissionAmount = ($netAmountAfterGateway * $commissionRate) / 100;
    $providerAmount = $netAmountAfterGateway - $platformCommissionAmount;
    
    // Total commission includes both gateway fee and platform commission
    $totalCommissionAmount = $gatewayFeeAmount + $platformCommissionAmount;
    
    return [
        'commission_rate' => $commissionRate,
        'gateway_fee_rate' => $gatewayFeeRate,
        'gateway_fee_amount' => $gatewayFeeAmount,
        'platform_commission_amount' => $platformCommissionAmount,
        'total_commission_amount' => $totalCommissionAmount,
        'provider_amount' => $providerAmount,
        'total_amount' => $totalAmount,
        'net_amount_after_gateway' => $netAmountAfterGateway
    ];
}

// Get provider's commission rate
function getProviderCommissionRate($providerId) {
    $db = getDB();
    
    $stmt = $db->prepare("
        SELECT COALESCE(pp.commission_rate, ss.setting_value, 10) as commission_rate
        FROM users u
        LEFT JOIN provider_profiles pp ON u.id = pp.user_id
        LEFT JOIN site_settings ss ON ss.setting_key = 'default_commission'
        WHERE u.id = ? AND u.role = 'provider'
    ");
    $stmt->execute([$providerId]);
    $result = $stmt->fetch();
    
    return $result ? $result['commission_rate'] : 10;
}

// Calculate tiered pricing based on group size
function calculateTieredPrice($package, $adults, $children = 0) {
    $result = [
        'total_amount' => 0,
        'adult_amount' => 0,
        'child_amount' => 0,
        'pricing_breakdown' => [],
        'applied_tier' => null,
        'rate_per_adult' => 0
    ];
    
    // Children under the specified age are free
    $freeAgeLimit = $package['child_free_age'] ?: 7;
    $payingChildren = 0; // Children under free age are free
    
    // Determine which tier applies based on total paying adults
    $tiers = [
        1 => ['max' => $package['price_tier_1_max'], 'rate' => $package['price_tier_1_rate']],
        2 => ['max' => $package['price_tier_2_max'], 'rate' => $package['price_tier_2_rate']],
        3 => ['max' => $package['price_tier_3_max'], 'rate' => $package['price_tier_3_rate']],
        4 => ['max' => $package['price_tier_4_max'], 'rate' => $package['price_tier_4_rate']]
    ];
    
    // Find the appropriate tier
    $selectedTier = null;
    foreach ($tiers as $tierNum => $tier) {
        if ($tier['rate'] > 0 && $adults <= $tier['max']) {
            $selectedTier = $tierNum;
            $result['applied_tier'] = $tierNum;
            $result['rate_per_adult'] = $tier['rate'];
            break;
        }
    }
    
    // If no tier found, use the highest available tier
    if (!$selectedTier) {
        for ($i = 4; $i >= 1; $i--) {
            if ($tiers[$i]['rate'] > 0) {
                $selectedTier = $i;
                $result['applied_tier'] = $i;
                $result['rate_per_adult'] = $tiers[$i]['rate'];
                break;
            }
        }
    }
    
    // Calculate pricing
    if ($selectedTier) {
        $result['adult_amount'] = $adults * $result['rate_per_adult'];
        $result['child_amount'] = $payingChildren * $package['child_price']; // Usually 0 since children are free
        $result['total_amount'] = $result['adult_amount'] + $result['child_amount'];
        
        // Create breakdown
        $result['pricing_breakdown'] = [
            'adults' => $adults,
            'children_free' => $children,
            'children_paid' => $payingChildren,
            'tier_applied' => $selectedTier,
            'rate_per_adult' => $result['rate_per_adult'],
            'adult_total' => $result['adult_amount'],
            'child_total' => $result['child_amount']
        ];
    }
    
    return $result;
}

// Get pricing tiers for display
function getPricingTiers($package) {
    $tiers = [];
    
    $tierData = [
        1 => ['max' => $package['price_tier_1_max'], 'rate' => $package['price_tier_1_rate']],
        2 => ['max' => $package['price_tier_2_max'], 'rate' => $package['price_tier_2_rate']],
        3 => ['max' => $package['price_tier_3_max'], 'rate' => $package['price_tier_3_rate']],
        4 => ['max' => $package['price_tier_4_max'], 'rate' => $package['price_tier_4_rate']]
    ];
    
    $previousMax = 0;
    foreach ($tierData as $tierNum => $tier) {
        if ($tier['rate'] > 0) {
            $tiers[] = [
                'tier' => $tierNum,
                'min_guests' => $previousMax + 1,
                'max_guests' => $tier['max'],
                'rate_per_adult' => $tier['rate'],
                'description' => ($previousMax + 1) . '-' . $tier['max'] . ' adults'
            ];
            $previousMax = $tier['max'];
        }
    }
    
    return $tiers;
}

// File upload function
function uploadFile($file, $destination = 'uploads/') {
    if (!isset($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file['type'], $allowedTypes)) {
        return false;
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $extension;
    $filepath = $destination . $filename;
    
    if (!is_dir($destination)) {
        mkdir($destination, 0755, true);
    }
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return $filepath;
    }
    
    return false;
}

// Send email function (placeholder - integrate with actual email service)
function sendEmail($to, $subject, $message, $headers = '') {
    // For development, we'll just log emails
    error_log("Email to: $to, Subject: $subject, Message: $message");
    return true; // Return mail($to, $subject, $message, $headers) for production
}

// Get site setting
function getSetting($key, $default = '') {
    $db = getDB();
    $stmt = $db->prepare("SELECT setting_value FROM site_settings WHERE setting_key = ?");
    $stmt->execute([$key]);
    $result = $stmt->fetch();
    return $result ? $result['setting_value'] : $default;
}

// Flash message functions
function setFlashMessage($message, $type = 'info') {
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = $type;
}

function displayFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        $type = $_SESSION['flash_type'] ?? 'info';
        
        $alertClass = [
            'success' => 'bg-green-100 border-green-400 text-green-700',
            'error' => 'bg-red-100 border-red-400 text-red-700',
            'warning' => 'bg-yellow-100 border-yellow-400 text-yellow-700',
            'info' => 'bg-blue-100 border-blue-400 text-blue-700'
        ];
        
        $iconClass = [
            'success' => 'fas fa-check-circle',
            'error' => 'fas fa-exclamation-circle',
            'warning' => 'fas fa-exclamation-triangle',
            'info' => 'fas fa-info-circle'
        ];
        
        $class = $alertClass[$type] ?? $alertClass['info'];
        $icon = $iconClass[$type] ?? $iconClass['info'];
        
        echo "<div class='border-l-4 p-4 mb-4 {$class}' role='alert'>";
        echo "<div class='flex'>";
        echo "<div class='flex-shrink-0'>";
        echo "<i class='{$icon}'></i>";
        echo "</div>";
        echo "<div class='ml-3'>";
        echo "<div class='text-sm'>{$message}</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        
        unset($_SESSION['flash_message'], $_SESSION['flash_type']);
    }
}

// CSRF token functions
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';
}

function validateCSRF($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function validateCSRFToken($token) {
    return validateCSRF($token);
}

// Pagination helper
function paginate($page, $totalItems, $itemsPerPage = 12) {
    $totalPages = ceil($totalItems / $itemsPerPage);
    $currentPage = max(1, min($page, $totalPages));
    $offset = ($currentPage - 1) * $itemsPerPage;
    
    return [
        'current_page' => $currentPage,
        'total_pages' => $totalPages,
        'total_items' => $totalItems,
        'items_per_page' => $itemsPerPage,
        'offset' => $offset,
        'has_prev' => $currentPage > 1,
        'has_next' => $currentPage < $totalPages,
        'prev_page' => $currentPage - 1,
        'next_page' => $currentPage + 1
    ];
}

// Redirect function
function redirect($url) {
    // Clean any output buffer to prevent headers already sent errors
    if (ob_get_level()) {
        ob_end_clean();
    }
    
    // If URL starts with http or https, use as-is (external URL)
    if (strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0) {
        header("Location: $url");
    } else {
        // For relative URLs, use app_url to generate proper URL
        header("Location: " . app_url($url));
    }
    exit;
}

// Flash message functions
function setFlash($type, $message) {
    $_SESSION['flash'][$type] = $message;
}

function getFlash($type) {
    if (isset($_SESSION['flash'][$type])) {
        $message = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]);
        return $message;
    }
    return null;
}

// Alias for setFlash to match usage in controllers
function set_flash_message($type, $message) {
    setFlash($type, $message);
}

// Password hashing and verification
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Generate sample data for development
function generateSamplePackages() {
    $db = getDB();
    
    // Check if we already have packages
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM packages");
    $stmt->execute();
    $result = $stmt->fetch();
    
    if ($result['count'] > 0) {
        return; // Already have data
    }
    
    // Create a sample provider
    $stmt = $db->prepare("INSERT INTO users (email, password, first_name, last_name, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute(['provider@example.com', hashPassword('password'), 'Travel', 'Company', 'provider']);
    $providerId = $db->lastInsertId();
    
    // Sample packages data
    $packages = [
        [
            'title' => 'Bali Paradise Adventure',
            'destination' => 'Bali, Indonesia',
            'duration_days' => 7,
            'base_price' => 899.00,
            'child_price' => 499.00,
            'extra_room_price' => 150.00,
            'max_guests_per_room' => 4,
            'short_description' => 'Discover the magic of Bali with temples, beaches, and cultural experiences.',
            'description' => 'Immerse yourself in the enchanting beauty of Bali with our comprehensive 7-day adventure package. Experience the perfect blend of cultural heritage, natural wonders, and tropical relaxation. Visit ancient temples, pristine beaches, lush rice terraces, and vibrant local markets while staying in comfortable accommodations.',
            'inclusions' => "• Round-trip airport transfers
• 6 nights accommodation in 4-star hotel
• Daily breakfast at hotel
• All entrance fees to temples and attractions
• Professional English-speaking guide
• Private air-conditioned vehicle
• Traditional Balinese cultural show
• Sunset dinner cruise
• Complimentary WiFi throughout the trip
• Travel insurance coverage",
            'exclusions' => "• International airfare to/from Bali
• Lunch and dinner (except sunset cruise)
• Personal expenses and shopping
• Alcoholic beverages
• Spa treatments and massages
• Tips for guides and drivers
• Visa fees (if applicable)
• Travel insurance upgrade
• Additional activities not mentioned",
            'category_id' => 1
        ],
        [
            'title' => 'Swiss Alps Romantic Getaway',
            'destination' => 'Interlaken, Switzerland',
            'duration_days' => 5,
            'base_price' => 1299.00,
            'child_price' => 799.00,
            'extra_room_price' => 200.00,
            'max_guests_per_room' => 2,
            'short_description' => 'Perfect honeymoon package with scenic Alpine views and luxury accommodations.',
            'description' => 'Escape to the breathtaking Swiss Alps for an unforgettable romantic getaway. This intimate 5-day package combines luxury accommodations with stunning mountain vistas, charming Alpine villages, and exclusive couple experiences. Perfect for honeymooners or couples celebrating special occasions.',
            'inclusions' => "• Private airport transfers in luxury vehicle
• 4 nights in premium mountain-view suite
• Daily gourmet breakfast
• Welcome champagne and chocolates
• Jungfraujoch \"Top of Europe\" excursion
• Scenic train rides with reserved seating
• Private couple's spa session
• Romantic candlelit dinner for two
• Swiss Travel Pass for local transportation
• Professional photography session
• 24/7 concierge service",
            'exclusions' => "• International flights to/from Switzerland
• Lunch and dinner (except romantic dinner)
• Additional spa treatments
• Personal shopping and souvenirs
• Alcoholic beverages (except welcome champagne)
• Travel and medical insurance
• Optional helicopter tours
• Tips and gratuities
• Ski equipment rental
• Additional excursions not mentioned",
            'category_id' => 2
        ],
        [
            'title' => 'Thailand Family Fun',
            'destination' => 'Phuket, Thailand',
            'duration_days' => 6,
            'base_price' => 749.00,
            'child_price' => 399.00,
            'extra_room_price' => 120.00,
            'max_guests_per_room' => 4,
            'short_description' => 'Family-friendly adventure with beaches, cultural sites, and kid-friendly activities.',
            'description' => 'Create lasting family memories with our specially designed Thailand family package. This 6-day adventure offers the perfect mix of relaxation, culture, and excitement suitable for all ages. From pristine beaches to exciting water activities and cultural experiences, every family member will have something to enjoy.',
            'inclusions' => "• Airport transfers for the whole family
• 5 nights family accommodation (connecting rooms available)
• Daily breakfast buffet
• Island hopping tour with lunch
• Elephant sanctuary visit
• Water sports equipment rental
• Kids' club activities and supervision
• Cultural show with traditional dinner
• Swimming pool and beach access
• Family-friendly guided tours
• Emergency medical assistance
• Complimentary baby cot (if needed)",
            'exclusions' => "• International airfare for all family members
• Lunch and dinner (except tour days)
• Babysitting services beyond kids' club hours
• Personal expenses and shopping
• Additional water sports and activities
• Travel insurance for family
• Laundry services
• Room service charges
• Optional tours and excursions
• Tips for guides and hotel staff
• Medical expenses and medications",
            'category_id' => 3
        ]
    ];
    
    foreach ($packages as $package) {
        $slug = createSlug($package['title']);
        $stmt = $db->prepare("
            INSERT INTO packages (provider_id, category_id, title, slug, description, short_description, destination, duration_days, base_price, child_price, extra_room_price, max_guests_per_room, inclusions, exclusions, is_featured) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)
        ");
        $stmt->execute([
            $providerId,
            $package['category_id'],
            $package['title'],
            $slug,
            $package['description'],
            $package['short_description'],
            $package['destination'],
            $package['duration_days'],
            $package['base_price'],
            $package['child_price'],
            $package['extra_room_price'],
            $package['max_guests_per_room'],
            $package['inclusions'],
            $package['exclusions']
        ]);
    }
}

// Calculate provider earnings with payment gateway fees deducted
function calculateProviderEarnings($totalAmount, $commissionRate = null) {
    // Get payment gateway fee rate
    $db = getDB();
    $stmt = $db->prepare("SELECT setting_value FROM site_settings WHERE setting_key = 'payment_gateway_fee'");
    $stmt->execute();
    $gatewayFeeResult = $stmt->fetch();
    $gatewayFeeRate = $gatewayFeeResult ? floatval($gatewayFeeResult['setting_value']) : 2.5;
    
    // Get commission rate if not provided
    if ($commissionRate === null) {
        $stmt = $db->prepare("SELECT setting_value FROM site_settings WHERE setting_key = 'default_commission'");
        $stmt->execute();
        $result = $stmt->fetch();
        $commissionRate = $result ? floatval($result['setting_value']) : 10;
    }
    
    // Calculate payment gateway fee
    $gatewayFeeAmount = ($totalAmount * $gatewayFeeRate) / 100;
    $netAmountAfterGateway = $totalAmount - $gatewayFeeAmount;
    
    // Calculate platform commission on net amount
    $platformCommissionAmount = ($netAmountAfterGateway * $commissionRate) / 100;
    $providerAmount = $netAmountAfterGateway - $platformCommissionAmount;
    
    return $providerAmount;
}

?> 