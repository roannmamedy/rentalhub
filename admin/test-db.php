<?php
require_once '../config/database.php';

echo "<h1>Database Connection Test</h1>";

try {
    echo "<p>✅ Database connection successful!</p>";
    
    $stmt = $pdo->query("SHOW TABLES LIKE 'tblcars'");
    if ($stmt->rowCount() > 0) {
        echo "<p>✅ tblcars table exists!</p>";
        
        echo "<h2>Table Structure:</h2>";
        $stmt = $pdo->query("DESCRIBE tblcars");
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while ($row = $stmt->fetch()) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . $row['Default'] . "</td>";
            echo "<td>" . $row['Extra'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM tblcars");
        $count = $stmt->fetch()['count'];
        echo "<p>📊 Total cars in database: " . $count . "</p>";
    } else {
        echo "<p>❌ tblcars table does not exist!</p>";
        echo "<p>Please run the SQL to create the table.</p>";
    }
} catch (PDOException $e) {
    echo "<p>❌ Database error: " . $e->getMessage() . "</p>";
}
?>
<p><a href="add-car.php">Go to Add Car Form</a></p>
