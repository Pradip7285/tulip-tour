<?php
echo "<h1>TripBazaar Web Test</h1>";
echo "<p>Current time: " . date('Y-m-d H:i:s') . "</p>";

// Test database connection
echo "<h2>Database Connection Test:</h2>";
try {
    require_once 'config/database.php';
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    echo "<p style='color: green;'>✅ Database connected successfully!</p>";
    
    // Test a simple query
    $stmt = $conn->query("SELECT COUNT(*) as count FROM users");
    $result = $stmt->fetch();
    echo "<p>Users in database: {$result['count']}</p>";
    
    // Test provider count
    $stmt = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'provider'");
    $result = $stmt->fetch();
    echo "<p>Providers in database: {$result['count']}</p>";
    
    // Test package count
    $stmt = $conn->query("SELECT COUNT(*) as count FROM packages");
    $result = $stmt->fetch();
    echo "<p>Packages in database: {$result['count']}</p>";
    
} catch(Exception $e) {
    echo "<p style='color: red;'>❌ Database connection failed: " . $e->getMessage() . "</p>";
}

echo "<h2>Application Links:</h2>";
echo '<ul>';
echo '<li><a href="/tulip/">Home Page</a></li>';
echo '<li><a href="/tulip/admin/dashboard">Admin Dashboard</a></li>';
echo '<li><a href="/tulip/provider/dashboard">Provider Dashboard</a></li>';
echo '<li><a href="/tulip/packages">Browse Packages</a></li>';
echo '</ul>';
?> 