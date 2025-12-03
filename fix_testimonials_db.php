<?php
require_once 'db_connect.php';

try {
    // Check if 'location' column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM testimonials LIKE 'location'");
    if ($stmt->rowCount() == 0) {
        // Add location column
        $pdo->exec("ALTER TABLE testimonials ADD COLUMN location VARCHAR(100) DEFAULT '' AFTER name");
        echo "Success: Added 'location' column.<br>";
    } else {
        echo "Column 'location' already exists.<br>";
    }
    
    echo "Database structure is now correct.";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>