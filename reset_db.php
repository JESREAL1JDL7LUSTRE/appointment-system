<?php
// reset_db.php - A quick script to force-drop all tables and start completely fresh

$mysqli = new mysqli("localhost", "root", "", "appointment_db");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Disable foreign key checks to allow dropping tables in any order
$mysqli->query("SET FOREIGN_KEY_CHECKS = 0");

$result = $mysqli->query("SHOW TABLES");

while($row = $result->fetch_array()) {
    $tableName = $row[0];
    $mysqli->query("DROP TABLE `" . $tableName . "`");
    echo "Dropped table: $tableName\n";
}

// Re-enable foreign key checks
$mysqli->query("SET FOREIGN_KEY_CHECKS = 1");

echo "\nDatabase is now completely empty! You are ready to migrate.\n";
