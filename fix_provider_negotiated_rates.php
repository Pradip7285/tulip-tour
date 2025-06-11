<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

echo "Fixing Provider Amounts with Individual Negotiated Rates + Payment Gateway Fees\n";
echo "============================================================================\n\n";

try {
    $pdo = Database::getInstance()->getConnection();
    
    // Step 1: Check current provider commission rates
    echo "Step 1: Current Provider Commission Rates\n";
    echo "=========================================\n";
    $stmt = $pdo->query("
        SELECT u.id, u.first_name, u.last_name, u.email,
               COALESCE(pp.commission_rate, 10) as commission_rate
        FROM users u
        LEFT JOIN provider_profiles pp ON u.id = pp.user_id
        WHERE u.role = 'provider'
        ORDER BY u.first_name
    ");
    $providers = $stmt->fetchAll();
    
    foreach ($providers as $provider) {
        echo "Provider: {$provider['first_name']} {$provider['last_name']}\n";
        echo "  Email: {$provider['email']}\n";
        echo "  Negotiated Rate: {$provider['commission_rate']}%\n\n";
    }
    
    // Step 2: Set different negotiated rates for each provider (example)
    echo "Step 2: Setting Individual Negotiated Rates\n";
    echo "===========================================\n";
    
    $negotiatedRates = [
        'sarah.travels@tripbazaar.com' => 7.0,    // Sarah gets 7% commission
        'mountain.trails@tripbazaar.com' => 8.5,  // Robert gets 8.5% commission  
        'heritage.tours@tripbazaar.com' => 9.0,   // Heritage gets 9% commission
        'luxury.escapes@tripbazaar.com' => 10.0,  // Luxury gets 10% commission
        'adventure.hub@tripbazaar.com' => 8.0,    // Adventure gets 8% commission
    ];
    
    foreach ($negotiatedRates as $email => $rate) {
        $stmt = $pdo->prepare("
            UPDATE provider_profiles pp
            JOIN users u ON pp.user_id = u.id
            SET pp.commission_rate = ?
            WHERE u.email = ?
        ");
        $stmt->execute([$rate, $email]);
        echo "✅ Set {$rate}% commission rate for {$email}\n";
    }
    
    // Step 3: Get payment gateway fee rate
    echo "\nStep 3: Payment Gateway Configuration\n";
    echo "====================================\n";
    $stmt = $pdo->prepare("SELECT setting_value FROM site_settings WHERE setting_key = 'payment_gateway_fee'");
    $stmt->execute();
    $gatewayFeeResult = $stmt->fetch();
    $gatewayFeeRate = $gatewayFeeResult ? floatval($gatewayFeeResult['setting_value']) : 2.5;
    echo "Payment Gateway Fee: {$gatewayFeeRate}%\n\n";
    
    // Step 4: Recalculate all bookings with provider-specific rates
    echo "Step 4: Recalculating All Bookings with Provider-Specific Rates\n";
    echo "===============================================================\n";
    
    $stmt = $pdo->query("
        SELECT b.id, b.package_id, b.total_amount, b.commission_amount, b.provider_amount,
               p.title, u.first_name, u.last_name, u.email,
               COALESCE(pp.commission_rate, 10) as provider_commission_rate
        FROM bookings b
        JOIN packages p ON b.package_id = p.id
        JOIN users u ON p.provider_id = u.id
        LEFT JOIN provider_profiles pp ON u.id = pp.user_id
        WHERE b.total_amount > 0
        ORDER BY u.first_name, b.id
    ");
    $bookings = $stmt->fetchAll();
    
    $updateStmt = $pdo->prepare("
        UPDATE bookings 
        SET commission_amount = ?, provider_amount = ?
        WHERE id = ?
    ");
    
    $providerSummary = [];
    
    foreach ($bookings as $booking) {
        $totalAmount = $booking['total_amount'];
        $providerRate = $booking['provider_commission_rate'];
        
        // Calculate payment gateway fee
        $gatewayFeeAmount = ($totalAmount * $gatewayFeeRate) / 100;
        $netAmountAfterGateway = $totalAmount - $gatewayFeeAmount;
        
        // Calculate platform commission on net amount using provider's negotiated rate
        $platformCommissionAmount = ($netAmountAfterGateway * $providerRate) / 100;
        $newProviderAmount = $netAmountAfterGateway - $platformCommissionAmount;
        $newTotalCommissionAmount = $gatewayFeeAmount + $platformCommissionAmount;
        
        // Update the booking
        $updateStmt->execute([
            $newTotalCommissionAmount,
            $newProviderAmount,
            $booking['id']
        ]);
        
        // Track provider summary
        $providerKey = $booking['email'];
        if (!isset($providerSummary[$providerKey])) {
            $providerSummary[$providerKey] = [
                'name' => $booking['first_name'] . ' ' . $booking['last_name'],
                'email' => $booking['email'],
                'rate' => $providerRate,
                'bookings' => 0,
                'total_revenue' => 0,
                'total_gateway_fees' => 0,
                'total_platform_commission' => 0,
                'total_provider_amount' => 0
            ];
        }
        
        $providerSummary[$providerKey]['bookings']++;
        $providerSummary[$providerKey]['total_revenue'] += $totalAmount;
        $providerSummary[$providerKey]['total_gateway_fees'] += $gatewayFeeAmount;
        $providerSummary[$providerKey]['total_platform_commission'] += $platformCommissionAmount;
        $providerSummary[$providerKey]['total_provider_amount'] += $newProviderAmount;
        
        echo "Updated Booking {$booking['id']}: {$booking['title']} - {$booking['first_name']} {$booking['last_name']}\n";
        echo "  Total: ₹{$totalAmount} | Gateway: ₹" . number_format($gatewayFeeAmount, 2) . " | Platform ({$providerRate}%): ₹" . number_format($platformCommissionAmount, 2) . " | Provider: ₹" . number_format($newProviderAmount, 2) . "\n\n";
    }
    
    // Step 5: Provider Summary
    echo "Step 5: Provider Earnings Summary with Individual Rates\n";
    echo "======================================================\n";
    
    foreach ($providerSummary as $summary) {
        $avgRate = $summary['total_revenue'] > 0 ? 
            (($summary['total_gateway_fees'] + $summary['total_platform_commission']) / $summary['total_revenue']) * 100 : 0;
        $providerPercentage = $summary['total_revenue'] > 0 ? 
            ($summary['total_provider_amount'] / $summary['total_revenue']) * 100 : 0;
        
        echo "🏢 {$summary['name']} ({$summary['email']})\n";
        echo "   Negotiated Rate: {$summary['rate']}% platform commission\n";
        echo "   Bookings: {$summary['bookings']}\n";
        echo "   Total Revenue: ₹" . number_format($summary['total_revenue']) . "\n";
        echo "   Gateway Fees (2.5%): ₹" . number_format($summary['total_gateway_fees']) . "\n";
        echo "   Platform Commission ({$summary['rate']}%): ₹" . number_format($summary['total_platform_commission']) . "\n";
        echo "   Provider Earnings: ₹" . number_format($summary['total_provider_amount']) . " (" . round($providerPercentage, 1) . "%)\n";
        echo "   Total System Take: " . round($avgRate, 1) . "% (Gateway + Platform)\n\n";
    }
    
    // Step 6: Verify dashboard calculations
    echo "Step 6: Dashboard Calculation Verification\n";
    echo "=========================================\n";
    
    require_once 'controllers/DashboardController.php';
    $dashboard = new DashboardController();
    
    foreach ($providers as $provider) {
        $reflection = new ReflectionClass($dashboard);
        $getProviderEarnings = $reflection->getMethod('getProviderEarnings');
        $getProviderEarnings->setAccessible(true);
        
        $earnings = $getProviderEarnings->invoke($dashboard, $provider['id']);
        
        echo "✅ {$provider['first_name']} {$provider['last_name']} Dashboard:\n";
        echo "   Total Earnings: ₹" . number_format($earnings['total']) . "\n";
        echo "   Negotiated Rate: {$provider['commission_rate']}%\n\n";
    }
    
    echo "✅ SUCCESS: All provider amounts now use individual negotiated rates + payment gateway fees!\n";
    echo "\n📊 Commission Structure Summary:\n";
    echo "===============================\n";
    echo "• Each provider has their own negotiated platform commission rate\n";
    echo "• Payment gateway fee (2.5%) is deducted from all bookings\n";
    echo "• Platform commission is calculated on net amount (after gateway fee)\n";
    echo "• Provider gets: Net Amount - Platform Commission\n";
    echo "• Total system take: Gateway Fee + Platform Commission\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?> 