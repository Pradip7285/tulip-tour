<?php
echo "<h2>🔍 Database Connection Debug Test</h2>";

define('APP_DEBUG', true); // Enable debug mode

echo "<p>Testing database connection...</p>";
echo "<p><strong>DB_HOST:</strong> localhost</p>";
echo "<p><strong>DB_NAME:</strong> tripbazaar</p>";
echo "<p><strong>DB_USER:</strong> root</p>";
echo "<p><strong>DB_PASS:</strong> Pr@dip7285</p>";

try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=tripbazaar;charset=utf8mb4",
        'root',
        'Pr@dip7285',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
    
    echo "<p style='color: green;'>✅ Direct PDO connection successful!</p>";
    
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
        
        // Check specific table data
        if (in_array('users', $tables)) {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
            $userCount = $stmt->fetch()['count'];
            echo "<p><strong>Total users:</strong> $userCount</p>";
        }
        
        if (in_array('providers', $tables)) {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM providers");
            $providerCount = $stmt->fetch()['count'];
            echo "<p><strong>Total providers:</strong> $providerCount</p>";
        }
        
        if (in_array('bookings', $tables)) {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM bookings");
            $bookingCount = $stmt->fetch()['count'];
            echo "<p><strong>Total bookings:</strong> $bookingCount</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ No tables found! Database is empty.</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Database connection failed: " . $e->getMessage() . "</p>";
    
    // Try without database name
    try {
        echo "<p>Trying connection without database name...</p>";
        $pdo2 = new PDO(
            "mysql:host=localhost;charset=utf8mb4",
            'root',
            'Pr@dip7285',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        echo "<p style='color: green;'>✅ Connection to MySQL server successful!</p>";
        
        // Show available databases
        $stmt = $pdo2->query("SHOW DATABASES");
        $databases = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "<p><strong>Available databases:</strong></p>";
        echo "<ul>";
        foreach ($databases as $db) {
            echo "<li>$db</li>";
        }
        echo "</ul>";
        
    } catch (PDOException $e2) {
        echo "<p style='color: red;'>❌ MySQL server connection also failed: " . $e2->getMessage() . "</p>";
    }
}
?> 