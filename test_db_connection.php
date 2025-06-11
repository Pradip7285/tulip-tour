<?php
echo "<h2>🔍 Database Connection Test</h2>";

require_once 'config/database.php';

try {
    $pdo = getDB();
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
    
    // Test basic query
    $stmt = $pdo->query("SELECT DATABASE() as current_db");
    $result = $stmt->fetch();
    echo "<p><strong>Current Database:</strong> " . $result['current_db'] . "</p>";
    
    // Check if tables exist
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<p><strong>Tables found:</strong> " . count($tables) . "</p>";
    if (!empty($tables)) {
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>$table</li>";
        }
        echo "</ul>";
        
        // Check users table
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
        $userCount = $stmt->fetch()['count'];
        echo "<p><strong>Total users:</strong> $userCount</p>";
        
        // Check providers table
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM providers");
        $providerCount = $stmt->fetch()['count'];
        echo "<p><strong>Total providers:</strong> $providerCount</p>";
        
        // Check bookings table
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM bookings");
        $bookingCount = $stmt->fetch()['count'];
        echo "<p><strong>Total bookings:</strong> $bookingCount</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database connection failed: " . $e->getMessage() . "</p>";
    echo "<p>Please make sure XAMPP MySQL service is running!</p>";
}
?> 