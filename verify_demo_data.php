<?php
// Verify Demo Data Import
require_once 'config/database.php';

echo "<h1>TripBazaar Demo Data Verification</h1>\n";

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    // Check provider users count
    $stmt = $conn->query("SELECT COUNT(*) as count FROM users WHERE role='provider'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<h2>Provider Users: " . $result['count'] . "</h2>\n";
    
    // Check if Sarah exists
    $stmt = $conn->prepare("SELECT email, first_name, last_name, phone FROM users WHERE email = ?");
    $stmt->execute(['sarah.travels@tripbazaar.com']);
    $sarah = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($sarah) {
        echo "<h2>✅ Sarah Williams Found!</h2>\n";
        echo "<p>Email: " . $sarah['email'] . "</p>\n";
        echo "<p>Name: " . $sarah['first_name'] . " " . $sarah['last_name'] . "</p>\n";
        echo "<p>Phone: " . $sarah['phone'] . "</p>\n";
        
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
            echo "<h3>Company Profile:</h3>\n";
            echo "<p>Company: " . $profile['company_name'] . "</p>\n";
            echo "<p>License: " . $profile['license_number'] . "</p>\n";
            echo "<p>City: " . $profile['city'] . "</p>\n";
            echo "<p>Commission Rate: " . $profile['commission_rate'] . "%</p>\n";
            echo "<p>Verified: " . ($profile['is_verified'] ? 'Yes' : 'No') . "</p>\n";
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
        
        echo "<h3>Sarah's Packages (" . count($packages) . " total):</h3>\n";
        foreach ($packages as $package) {
            echo "<div style='border:1px solid #ccc; padding:10px; margin:5px;'>\n";
            echo "<strong>" . $package['title'] . "</strong><br>\n";
            echo "Destination: " . $package['destination'] . "<br>\n";
            echo "Price: ₹" . number_format($package['base_price']) . "<br>\n";
            echo "Featured: " . ($package['is_featured'] ? 'Yes' : 'No') . "<br>\n";
            echo "</div>\n";
        }
    } else {
        echo "<h2>❌ Sarah Williams Not Found!</h2>\n";
    }
    
    // Show all providers summary
    echo "<h2>All Providers Summary</h2>\n";
    $stmt = $conn->query("
        SELECT u.first_name, u.last_name, u.email, pp.company_name, pp.commission_rate
        FROM users u 
        LEFT JOIN provider_profiles pp ON u.id = pp.user_id 
        WHERE u.role = 'provider'
        ORDER BY u.first_name
    ");
    $providers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse:collapse; width:100%;'>\n";
    echo "<tr><th>Name</th><th>Email</th><th>Company</th><th>Commission %</th></tr>\n";
    foreach ($providers as $provider) {
        echo "<tr>\n";
        echo "<td>" . $provider['first_name'] . " " . $provider['last_name'] . "</td>\n";
        echo "<td>" . $provider['email'] . "</td>\n";
        echo "<td>" . ($provider['company_name'] ?: 'No Profile') . "</td>\n";
        echo "<td>" . ($provider['commission_rate'] ?: 'N/A') . "%</td>\n";
        echo "</tr>\n";
    }
    echo "</table>\n";
    
    // Total packages count
    $stmt = $conn->query("SELECT COUNT(*) as count FROM packages");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<h2>Total Packages: " . $result['count'] . "</h2>\n";
    
    echo "<h2>✅ Demo Data Import Verification Complete!</h2>\n";
    echo "<p><strong>Login credentials for all providers:</strong> password123</p>\n";
    
} catch (Exception $e) {
    echo "<h2>❌ Error: " . $e->getMessage() . "</h2>\n";
}
?> 