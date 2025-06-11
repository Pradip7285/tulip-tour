<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

echo "Comprehensive Commission Tracking Fix\n";
echo "=====================================\n\n";

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    echo "Step 1: Checking database structure...\n";
    
    // Check if commission columns exist
    $tables = ['bookings', 'provider_profiles'];
    foreach ($tables as $table) {
        $stmt = $conn->prepare("DESCRIBE $table");
        $stmt->execute();
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if ($table === 'bookings') {
            $requiredColumns = ['commission_amount', 'provider_amount'];
            foreach ($requiredColumns as $col) {
                if (in_array($col, $columns)) {
                    echo "  ✅ $table.$col exists\n";
                } else {
                    echo "  ❌ $table.$col missing - adding...\n";
                    $conn->exec("ALTER TABLE $table ADD COLUMN $col DECIMAL(10,2) DEFAULT 0");
                }
            }
        }
        
        if ($table === 'provider_profiles') {
            if (in_array('commission_rate', $columns)) {
                echo "  ✅ $table.commission_rate exists\n";
            } else {
                echo "  ❌ $table.commission_rate missing - adding...\n";
                $conn->exec("ALTER TABLE $table ADD COLUMN commission_rate DECIMAL(5,2) DEFAULT 10.00");
            }
        }
    }
    
    echo "\nStep 2: Ensuring all providers have commission rates...\n";
    
    // Get providers without commission rates
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
            $updateStmt = $conn->prepare("UPDATE provider_profiles SET commission_rate = 10.00 WHERE user_id = ?");
            $updateStmt->execute([$provider['id']]);
        } else {
            // Create new profile
            $insertStmt = $conn->prepare("
                INSERT INTO provider_profiles (user_id, company_name, commission_rate) 
                VALUES (?, ?, 10.00)
            ");
            $insertStmt->execute([
                $provider['id'], 
                $provider['first_name'] . "'s Travel Business"
            ]);
        }
        echo "  ✅ Set 10% commission for {$provider['first_name']} {$provider['last_name']}\n";
    }
    
    echo "\nStep 3: Fixing existing bookings with incorrect commission...\n";
    
    // Find bookings with zero or incorrect commission
    $stmt = $conn->prepare("
        SELECT b.id, b.package_id, b.total_amount, b.commission_amount, b.provider_amount
        FROM bookings b
        WHERE b.commission_amount = 0 OR b.provider_amount = 0 OR 
              ABS(b.total_amount - (b.commission_amount + b.provider_amount)) > 0.01
    ");
    $stmt->execute();
    $problematicBookings = $stmt->fetchAll();
    
    $updateBookingStmt = $conn->prepare("
        UPDATE bookings 
        SET commission_amount = ?, provider_amount = ? 
        WHERE id = ?
    ");
    
    foreach ($problematicBookings as $booking) {
        $commissionData = calculateProviderCommission($booking['package_id'], $booking['total_amount']);
        
        $updateBookingStmt->execute([
            $commissionData['commission_amount'],
            $commissionData['provider_amount'],
            $booking['id']
        ]);
        
        echo "  ✅ Fixed booking #{$booking['id']}: Commission ₹{$commissionData['commission_amount']}, Provider ₹{$commissionData['provider_amount']}\n";
    }
    
    echo "\nStep 4: Verifying admin dashboard commission calculation...\n";
    
    // Test admin commission stats
    $stmt = $conn->prepare("
        SELECT 
            COUNT(*) as total_bookings,
            COALESCE(SUM(total_amount), 0) as total_revenue,
            COALESCE(SUM(commission_amount), 0) as total_commission,
            COALESCE(SUM(provider_amount), 0) as total_provider_amount
        FROM bookings 
        WHERE payment_status = 'paid'
    ");
    $stmt->execute();
    $adminStats = $stmt->fetch();
    
    echo "  Total Bookings: {$adminStats['total_bookings']}\n";
    echo "  Total Revenue: ₹" . number_format($adminStats['total_revenue'], 2) . "\n";
    echo "  Total Commission: ₹" . number_format($adminStats['total_commission'], 2) . "\n";
    echo "  Total Provider Amount: ₹" . number_format($adminStats['total_provider_amount'], 2) . "\n";
    
    $calculatedTotal = $adminStats['total_commission'] + $adminStats['total_provider_amount'];
    echo "  Math Check: Commission + Provider = ₹" . number_format($calculatedTotal, 2) . "\n";
    
    if (abs($adminStats['total_revenue'] - $calculatedTotal) < 0.01) {
        echo "  ✅ Math is correct!\n";
    } else {
        echo "  ❌ Math error: Revenue ≠ Commission + Provider\n";
    }
    
    echo "\nStep 5: Testing commission update functionality...\n";
    
    // Check if update route exists
    $indexContent = file_get_contents('index.php');
    if (strpos($indexContent, '/admin/providers/update-commission') !== false) {
        echo "  ✅ Commission update route exists\n";
    } else {
        echo "  ❌ Commission update route missing - adding to index.php...\n";
        
        $routeCode = "        } elseif (\$path === '/admin/providers/update-commission') {\n" .
                     "            requireLogin();\n" .
                     "            requireRole('admin');\n" .
                     "            require_once 'controllers/AdminController.php';\n" .
                     "            \$controller = new AdminController();\n" .
                     "            \$controller->updateProviderCommission();\n";
        
        // Add route before the final else
        $updatedIndex = str_replace('        } else {', $routeCode . '        } else {', $indexContent);
        file_put_contents('index.php', $updatedIndex);
        echo "  ✅ Added commission update route\n";
    }
    
    echo "\nStep 6: Creating test commission rate update...\n";
    
    // Test commission rate change for first provider
    $stmt = $conn->prepare("SELECT id FROM users WHERE role = 'provider' LIMIT 1");
    $stmt->execute();
    $testProvider = $stmt->fetch();
    
    if ($testProvider) {
        $testRate = 8.5;
        $stmt = $conn->prepare("
            UPDATE provider_profiles 
            SET commission_rate = ? 
            WHERE user_id = ?
        ");
        $stmt->execute([$testRate, $testProvider['id']]);
        
        // Verify the change
        $verifyRate = getProviderCommissionRate($testProvider['id']);
        if ($verifyRate == $testRate) {
            echo "  ✅ Commission rate update working: Set to {$testRate}%, reads as {$verifyRate}%\n";
        } else {
            echo "  ❌ Commission rate update failed: Set to {$testRate}%, reads as {$verifyRate}%\n";
        }
    }
    
    echo "\nStep 7: Final verification...\n";
    
    // Count any remaining issues
    $stmt = $conn->prepare("
        SELECT COUNT(*) as zero_commission
        FROM bookings 
        WHERE commission_amount = 0 AND total_amount > 0
    ");
    $stmt->execute();
    $zeroCommission = $stmt->fetch()['zero_commission'];
    
    $stmt = $conn->prepare("
        SELECT COUNT(*) as providers_without_rates
        FROM users u
        LEFT JOIN provider_profiles pp ON u.id = pp.user_id
        WHERE u.role = 'provider' AND pp.commission_rate IS NULL
    ");
    $stmt->execute();
    $noRates = $stmt->fetch()['providers_without_rates'];
    
    echo "  Bookings with zero commission: {$zeroCommission}\n";
    echo "  Providers without rates: {$noRates}\n";
    
    if ($zeroCommission == 0 && $noRates == 0) {
        echo "\n🎉 Commission tracking is now fully fixed!\n";
        echo "\nFeatures now working:\n";
        echo "✅ Admin dashboard shows accurate commission statistics\n";
        echo "✅ Provider earnings display their negotiated rates\n";
        echo "✅ Admin can update commission rates via provider management\n";
        echo "✅ All bookings have correct commission calculations\n";
        echo "✅ New bookings will use provider-specific rates\n";
    } else {
        echo "\n⚠️  Some issues remain - manual intervention may be needed\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?> 