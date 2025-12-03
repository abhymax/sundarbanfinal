<?php
require_once 'db_connect.php';

try {
    // Add day_number column if it doesn't exist
    $stmt = $pdo->query("SHOW COLUMNS FROM page_timeline LIKE 'day_number'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE page_timeline ADD COLUMN day_number INT DEFAULT 1 AFTER title");
        echo "Column 'day_number' added successfully.<br>";
        
        // Update existing events for 1N2D tour (Optional: logic to guess day based on your current data)
        // For now, we default everything to Day 1. You can edit this in Admin.
    } else {
        echo "Column 'day_number' already exists.<br>";
    }
    echo "Database ready for multi-day itineraries.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>