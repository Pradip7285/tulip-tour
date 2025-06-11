<?php
require_once 'config/database.php';

echo "<h2>📊 Earnings Dashboard Check</h2>";

try {
    $pdo = getDB();
    
    // 1. Check if we have any providers
    $stmt = $pdo->query("SELECT COUNT(*) as provider_count FROM users WHERE role = 'provider'");
    $providerCount = $stmt->fetch()['provider_count'];
    echo "<p><strong>Total Providers:</strong> $providerCount</p>";
    
    if ($providerCount == 0) {
        echo "<p style='color: red;'>❌ No providers found in database!</p>";
        exit;
    }
    
    // 2. Get a sample provider to test
    $stmt = $pdo->query("SELECT id, name, email FROM users WHERE role = 'provider' LIMIT 1");
    $provider = $stmt->fetch();
    echo "<p><strong>Testing with Provider:</strong> {$provider['name']} (ID: {$provider['id']})</p>";
    
    // 3. Check if provider has a company
    $stmt = $pdo->prepare("SELECT id, company_name FROM providers WHERE user_id = ?");
    $stmt->execute([$provider['id']]);
    $company = $stmt->fetch();
    
    if (!$company) {
        echo "<p style='color: red;'>❌ Provider has no company record!</p>";
        exit;
    }
    
    echo "<p><strong>Company:</strong> {$company['company_name']} (Provider ID: {$company['id']})</p>";
    
    // 4. Check bookings for this provider
    echo "<h3>📋 Bookings Data</h3>";
    $stmt = $pdo->prepare("
        SELECT b.*, p.title as package_title, p.price 
        FROM bookings b 
        JOIN packages p ON b.package_id = p.id 
        WHERE p.provider_id = ?
        ORDER BY b.booking_date DESC
    ");
    $stmt->execute([$company['id']]);
    $bookings = $stmt->fetchAll();
    
    if (empty($bookings)) {
        echo "<p style='color: red;'>❌ No bookings found for this provider!</p>";
    } else {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Booking ID</th><th>Package</th><th>Amount</th><th>Status</th><th>Date</th><th>Commission</th></tr>";
        
        $totalEarnings = 0;
        $currentMonthEarnings = 0;
        $currentMonth = date('Y-m');
        
        foreach ($bookings as $booking) {
            $commission = $booking['total_amount'] * 0.85; // 85% to provider
            echo "<tr>";
            echo "<td>{$booking['id']}</td>";
            echo "<td>{$booking['package_title']}</td>";
            echo "<td>₹" . number_format($booking['total_amount']) . "</td>";
            echo "<td>{$booking['status']}</td>";
            echo "<td>{$booking['booking_date']}</td>";
            echo "<td>₹" . number_format($commission) . "</td>";
            echo "</tr>";
            
            if ($booking['status'] == 'completed') {
                $totalEarnings += $commission;
                
                if (date('Y-m', strtotime($booking['booking_date'])) == $currentMonth) {
                    $currentMonthEarnings += $commission;
                }
            }
        }
        echo "</table>";
        
        echo "<p><strong>Calculated Total Earnings (Completed):</strong> ₹" . number_format($totalEarnings) . "</p>";
        echo "<p><strong>Calculated This Month Earnings:</strong> ₹" . number_format($currentMonthEarnings) . "</p>";
    }
    
    // 5. Check what the dashboard controller would calculate
    echo "<h3>🔧 Dashboard Controller Simulation</h3>";
    
    // Simulate the dashboard controller logic
    $providerId = $company['id'];
    
    // Total earnings query
    $stmt = $pdo->prepare("
        SELECT COALESCE(SUM(b.total_amount * 0.85), 0) as total_earnings
        FROM bookings b
        JOIN packages p ON b.package_id = p.id
        WHERE p.provider_id = ? AND b.status = 'completed'
    ");
    $stmt->execute([$providerId]);
    $dashboardTotal = $stmt->fetch()['total_earnings'];
    
    // This month earnings
    $stmt = $pdo->prepare("
        SELECT COALESCE(SUM(b.total_amount * 0.85), 0) as month_earnings
        FROM bookings b
        JOIN packages p ON b.package_id = p.id
        WHERE p.provider_id = ? 
        AND b.status = 'completed'
        AND DATE_FORMAT(b.booking_date, '%Y-%m') = ?
    ");
    $stmt->execute([$providerId, $currentMonth]);
    $dashboardMonth = $stmt->fetch()['month_earnings'];
    
    // Pending payouts
    $stmt = $pdo->prepare("
        SELECT COALESCE(SUM(amount), 0) as pending_payouts
        FROM payouts
        WHERE provider_id = ? AND status = 'pending'
    ");
    $stmt->execute([$providerId]);
    $pendingPayouts = $stmt->fetch()['pending_payouts'];
    
    // Completed payouts
    $stmt = $pdo->prepare("
        SELECT COALESCE(SUM(amount), 0) as completed_payouts
        FROM payouts
        WHERE provider_id = ? AND status = 'completed'
    ");
    $stmt->execute([$providerId]);
    $completedPayouts = $stmt->fetch()['completed_payouts'];
    
    echo "<div style='background: #f0f0f0; padding: 15px; margin: 10px 0;'>";
    echo "<h4>Dashboard Results:</h4>";
    echo "<p><strong>Total Earnings:</strong> ₹" . number_format($dashboardTotal) . "</p>";
    echo "<p><strong>This Month:</strong> ₹" . number_format($dashboardMonth) . "</p>";
    echo "<p><strong>Pending Payouts:</strong> ₹" . number_format($pendingPayouts) . "</p>";
    echo "<p><strong>Completed Payouts:</strong> ₹" . number_format($completedPayouts) . "</p>";
    echo "</div>";
    
    // 6. Check if payouts table exists and has data
    echo "<h3>💰 Payouts Table Check</h3>";
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as payout_count FROM payouts");
        $payoutCount = $stmt->fetch()['payout_count'];
        echo "<p><strong>Total Payouts:</strong> $payoutCount</p>";
        
        if ($payoutCount > 0) {
            $stmt = $pdo->query("SELECT * FROM payouts LIMIT 5");
            $payouts = $stmt->fetchAll();
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>ID</th><th>Provider ID</th><th>Amount</th><th>Status</th><th>Date</th></tr>";
            foreach ($payouts as $payout) {
                echo "<tr>";
                echo "<td>{$payout['id']}</td>";
                echo "<td>{$payout['provider_id']}</td>";
                echo "<td>₹" . number_format($payout['amount']) . "</td>";
                echo "<td>{$payout['status']}</td>";
                echo "<td>{$payout['created_at']}</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Payouts table error: " . $e->getMessage() . "</p>";
    }
    
    echo "<h3>✅ Summary</h3>";
    if ($dashboardTotal > 0) {
        echo "<p style='color: green;'>✅ Dashboard should show earnings data correctly!</p>";
    } else {
        echo "<p style='color: red;'>❌ Dashboard calculations are returning 0</p>";
    }

} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?> 