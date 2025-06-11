<?php
// Simple command-line verification of demo data
require_once 'config/database.php';

echo "TripBazaar Demo Data Verification\n";
echo "=================================\n\n";

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    // Check provider users count
    $stmt = $conn->query("SELECT COUNT(*) as count FROM users WHERE role='provider'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Provider Users: " . $result['count'] . "\n\n";
    
    // Check if Sarah exists
    $stmt = $conn->prepare("SELECT email, first_name, last_name, phone FROM users WHERE email = ?");
    $stmt->execute(['sarah.travels@tripbazaar.com']);
    $sarah = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($sarah) {
        echo "✅ Sarah Williams Found!\n";
        echo "Email: " . $sarah['email'] . "\n";
        echo "Name: " . $sarah['first_name'] . " " . $sarah['last_name'] . "\n";
        echo "Phone: " . $sarah['phone'] . "\n\n";
        
        // Get Sarah's company profile
        $stmt = $conn->prepare("
            SELECT pp.company_name, pp.license_number, pp.city, pp.commission_rate, pp.is_verified 
            FROM provider_profiles pp 
            JOIN users u ON pp.user_id = u.id 
            WHERE u.email = ?
        ");
        $stmt->execute(['sarah.travels@tripbazaar.com']);
        $profile = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($profile) {
            echo "Company Profile:\n";
            echo "- Company: " . $profile['company_name'] . "\n";
            echo "- License: " . $profile['license_number'] . "\n";
            echo "- City: " . $profile['city'] . "\n";
            echo "- Commission Rate: " . $profile['commission_rate'] . "%\n";
            echo "- Verified: " . ($profile['is_verified'] ? 'Yes' : 'No') . "\n\n";
        }
        
        // Get Sarah's packages
        $stmt = $conn->prepare("
            SELECT title, destination, base_price, is_featured 
            FROM packages p 
            JOIN users u ON p.provider_id = u.id 
            WHERE u.email = ?
        ");
        $stmt->execute(['sarah.travels@tripbazaar.com']);
        $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "Sarah's Packages (" . count($packages) . " total):\n";
        foreach ($packages as $package) {
            echo "- " . $package['title'] . "\n";
            echo "  Destination: " . $package['destination'] . "\n";
            echo "  Price: ₹" . number_format($package['base_price']) . "\n";
            echo "  Featured: " . ($package['is_featured'] ? 'Yes' : 'No') . "\n\n";
        }
    } else {
        echo "❌ Sarah Williams Not Found!\n\n";
    }
    
    // Show all providers summary
    echo "All Providers Summary:\n";
    echo "=====================\n";
    $stmt = $conn->query("
        SELECT u.first_name, u.last_name, u.email, pp.company_name, pp.commission_rate
        FROM users u 
        LEFT JOIN provider_profiles pp ON u.id = pp.user_id 
        WHERE u.role = 'provider'
        ORDER BY u.first_name
    ");
    $providers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($providers as $provider) {
        echo $provider['first_name'] . " " . $provider['last_name'] . "\n";
        echo "  Email: " . $provider['email'] . "\n";
        echo "  Company: " . ($provider['company_name'] ?: 'No Profile') . "\n";
        echo "  Commission: " . ($provider['commission_rate'] ?: 'N/A') . "%\n\n";
    }
    
    // Total packages count
    $stmt = $conn->query("SELECT COUNT(*) as count FROM packages");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Total Packages: " . $result['count'] . "\n\n";
    
    echo "✅ Demo Data Import Verification Complete!\n";
    echo "Login credentials for all providers: password123\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?> 