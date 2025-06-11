<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

echo "Commission System Fix Script\n";
echo "============================\n\n";

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    // 1. Update existing bookings to use correct commission calculation
    echo "1. Updating existing bookings with correct commission rates...\n";
    
    $stmt = $conn->prepare("
        SELECT b.id, b.package_id, b.total_amount, p.provider_id
        FROM bookings b
        JOIN packages p ON b.package_id = p.id
        WHERE b.commission_amount = 0 OR b.provider_amount = 0
    ");
    $stmt->execute();
    $bookings = $stmt->fetchAll();
    
    $updateStmt = $conn->prepare("
        UPDATE bookings 
        SET commission_amount = ?, provider_amount = ? 
        WHERE id = ?
    ");
    
    foreach ($bookings as $booking) {
        $commissionData = calculateProviderCommission($booking['package_id'], $booking['total_amount']);
        
        $updateStmt->execute([
            $commissionData['commission_amount'],
            $commissionData['provider_amount'],
            $booking['id']
        ]);
        
        echo "   Updated booking #{$booking['id']}: Commission {$commissionData['commission_rate']}% = ₹{$commissionData['commission_amount']}\n";
    }
    
    // 2. Ensure all providers have commission rates set
    echo "\n2. Setting default commission rates for providers without rates...\n";
    
    $stmt = $conn->prepare("
        SELECT u.id, u.first_name, u.last_name, u.email
        FROM users u
        LEFT JOIN provider_profiles pp ON u.id = pp.user_id
        WHERE u.role = 'provider' AND (pp.commission_rate IS NULL OR pp.user_id IS NULL)
    ");
    $stmt->execute();
    $providersWithoutRates = $stmt->fetchAll();
    
    foreach ($providersWithoutRates as $provider) {
        // Check if profile exists
        $checkStmt = $conn->prepare("SELECT id FROM provider_profiles WHERE user_id = ?");
        $checkStmt->execute([$provider['id']]);
        $profileExists = $checkStmt->fetch();
        
        if ($profileExists) {
            // Update existing profile
            $updateProfileStmt = $conn->prepare("
                UPDATE provider_profiles 
                SET commission_rate = 10.00 
                WHERE user_id = ?
            ");
            $updateProfileStmt->execute([$provider['id']]);
        } else {
            // Create new profile
            $createProfileStmt = $conn->prepare("
                INSERT INTO provider_profiles (user_id, company_name, commission_rate) 
                VALUES (?, ?, 10.00)
            ");
            $createProfileStmt->execute([
                $provider['id'], 
                $provider['first_name'] . "'s Travel Business"
            ]);
        }
        
        echo "   Set 10% commission rate for {$provider['first_name']} {$provider['last_name']} ({$provider['email']})\n";
    }
    
    // 3. Show commission summary
    echo "\n3. Commission System Summary:\n";
    echo "-----------------------------\n";
    
    $stmt = $conn->prepare("
        SELECT 
            u.first_name, u.last_name, u.email,
            COALESCE(pp.commission_rate, 10) as commission_rate,
            COUNT(b.id) as total_bookings,
            COALESCE(SUM(b.commission_amount), 0) as total_commission,
            COALESCE(SUM(b.provider_amount), 0) as total_provider_amount
        FROM users u
        LEFT JOIN provider_profiles pp ON u.id = pp.user_id
        LEFT JOIN packages p ON u.id = p.provider_id
        LEFT JOIN bookings b ON p.id = b.package_id AND b.payment_status = 'paid'
        WHERE u.role = 'provider'
        GROUP BY u.id, u.first_name, u.last_name, u.email, pp.commission_rate
        ORDER BY total_commission DESC
    ");
    $stmt->execute();
    $providerSummary = $stmt->fetchAll();
    
    foreach ($providerSummary as $provider) {
        echo "Provider: {$provider['first_name']} {$provider['last_name']}\n";
        echo "  Email: {$provider['email']}\n";
        echo "  Commission Rate: {$provider['commission_rate']}%\n";
        echo "  Total Bookings: {$provider['total_bookings']}\n";
        echo "  Total Commission Earned: ₹" . number_format($provider['total_commission'], 2) . "\n";
        echo "  Total Provider Earnings: ₹" . number_format($provider['total_provider_amount'], 2) . "\n\n";
    }
    
    // 4. Show overall commission statistics
    echo "4. Overall Commission Statistics:\n";
    echo "---------------------------------\n";
    
    $stmt = $conn->prepare("
        SELECT 
            COUNT(*) as total_bookings,
            SUM(total_amount) as total_revenue,
            SUM(commission_amount) as total_commission,
            SUM(provider_amount) as total_provider_amount,
            AVG(commission_amount / total_amount * 100) as avg_commission_rate
        FROM bookings 
        WHERE payment_status = 'paid' AND total_amount > 0
    ");
    $stmt->execute();
    $stats = $stmt->fetch();
    
    echo "Total Paid Bookings: {$stats['total_bookings']}\n";
    echo "Total Revenue: ₹" . number_format($stats['total_revenue'], 2) . "\n";
    echo "Total Commission: ₹" . number_format($stats['total_commission'], 2) . "\n";
    echo "Total Provider Earnings: ₹" . number_format($stats['total_provider_amount'], 2) . "\n";
    echo "Average Commission Rate: " . number_format($stats['avg_commission_rate'], 2) . "%\n";
    
    echo "\n✅ Commission system has been fixed successfully!\n";
    echo "\nKey Features:\n";
    echo "- Each provider now has their own negotiated commission rate\n";
    echo "- Admin can update commission rates via /admin/providers\n";
    echo "- All calculations use actual stored commission amounts\n";
    echo "- Provider earnings page shows their specific rate\n";
    echo "- New bookings will automatically use provider-specific rates\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?> 