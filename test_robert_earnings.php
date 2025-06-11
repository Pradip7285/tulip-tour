<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

echo "Testing Robert Patel's Earnings with Negotiated Rate + Payment Gateway Fees\n";
echo "=========================================================================\n\n";

try {
    $pdo = Database::getInstance()->getConnection();
    
    // Get Robert's user info
    $stmt = $pdo->query("SELECT * FROM users WHERE email = 'mountain.trails@tripbazaar.com'");
    $robert = $stmt->fetch();
    
    if (!$robert) {
        echo "❌ Robert not found\n";
        exit;
    }
    
    echo "Provider: {$robert['first_name']} {$robert['last_name']}\n";
    echo "Email: {$robert['email']}\n";
    echo "User ID: {$robert['id']}\n\n";
    
    // Check his negotiated commission rate
    $negotiatedRate = getProviderCommissionRate($robert['id']);
    echo "Negotiated Commission Rate: {$negotiatedRate}%\n\n";
    
    // Get his bookings with detailed breakdown
    echo "Detailed Booking Analysis:\n";
    echo "=========================\n";
    
    $stmt = $pdo->prepare("
        SELECT b.*, p.title
        FROM bookings b
        JOIN packages p ON b.package_id = p.id
        WHERE p.provider_id = ? AND b.payment_status = 'paid' AND b.status = 'completed'
    ");
    $stmt->execute([$robert['id']]);
    $bookings = $stmt->fetchAll();
    
    $totalBookingValue = 0;
    $totalGatewayFees = 0;
    $totalPlatformCommission = 0;
    $totalProviderEarnings = 0;
    
    foreach ($bookings as $booking) {
        $gatewayFee = $booking['total_amount'] * 0.025; // 2.5%
        $netAfterGateway = $booking['total_amount'] - $gatewayFee;
        $platformCommission = $netAfterGateway * ($negotiatedRate / 100);
        $providerAmount = $netAfterGateway - $platformCommission;
        
        echo "Booking {$booking['id']}: {$booking['title']}\n";
        echo "  Total Amount: ₹" . number_format($booking['total_amount']) . "\n";
        echo "  Gateway Fee (2.5%): ₹" . number_format($gatewayFee, 2) . "\n";
        echo "  Net After Gateway: ₹" . number_format($netAfterGateway, 2) . "\n";
        echo "  Platform Commission ({$negotiatedRate}%): ₹" . number_format($platformCommission, 2) . "\n";
        echo "  Provider Amount: ₹" . number_format($providerAmount, 2) . "\n";
        echo "  Stored Provider Amount: ₹" . number_format($booking['provider_amount'], 2) . "\n";
        echo "  ✅ " . ($abs($booking['provider_amount'] - $providerAmount) < 1 ? "Matches calculation" : "Calculation differs") . "\n\n";
        
        $totalBookingValue += $booking['total_amount'];
        $totalGatewayFees += $gatewayFee;
        $totalPlatformCommission += $platformCommission;
        $totalProviderEarnings += $booking['provider_amount'];
    }
    
    echo "Summary for Robert Patel:\n";
    echo "========================\n";
    echo "Total Booking Value: ₹" . number_format($totalBookingValue) . "\n";
    echo "Total Gateway Fees (2.5%): ₹" . number_format($totalGatewayFees, 2) . "\n";
    echo "Total Platform Commission ({$negotiatedRate}%): ₹" . number_format($totalPlatformCommission, 2) . "\n";
    echo "Total Provider Earnings: ₹" . number_format($totalProviderEarnings, 2) . "\n";
    
    $providerPercentage = ($totalProviderEarnings / $totalBookingValue) * 100;
    $systemPercentage = (($totalGatewayFees + $totalPlatformCommission) / $totalBookingValue) * 100;
    
    echo "Provider Gets: " . round($providerPercentage, 1) . "% of total booking value\n";
    echo "System Gets: " . round($systemPercentage, 1) . "% (2.5% gateway + " . round($systemPercentage - 2.5, 1) . "% platform)\n\n";
    
    // Test dashboard calculation
    echo "Dashboard Calculation Test:\n";
    echo "==========================\n";
    
    require_once 'controllers/DashboardController.php';
    $dashboard = new DashboardController();
    
    $reflection = new ReflectionClass($dashboard);
    $getProviderEarnings = $reflection->getMethod('getProviderEarnings');
    $getProviderEarnings->setAccessible(true);
    
    $dashboardEarnings = $getProviderEarnings->invoke($dashboard, $robert['id']);
    
    echo "Dashboard Total Earnings: ₹" . number_format($dashboardEarnings['total']) . "\n";
    echo "Expected Total: ₹" . number_format($totalProviderEarnings) . "\n";
    echo "✅ " . ($dashboardEarnings['total'] == $totalProviderEarnings ? "Dashboard matches calculation!" : "Dashboard calculation differs") . "\n\n";
    
    echo "🎯 LOGIN TEST:\n";
    echo "==============\n";
    echo "URL: http://localhost/Tulip/login\n";
    echo "Email: {$robert['email']}\n";
    echo "Password: password123\n";
    echo "Expected Dashboard Total: ₹" . number_format($dashboardEarnings['total']) . "\n";
    echo "Rate Structure: 2.5% gateway + {$negotiatedRate}% platform = " . round($systemPercentage, 1) . "% total system take\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?> 