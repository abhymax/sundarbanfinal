<?php
require_once 'db_connect.php';

try {
    // Check if column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM site_sections LIKE 'sub_heading_font_size'");
    $column = $stmt->fetch();

    if (!$column) {
        // Add column if it doesn't exist
        $sql = "ALTER TABLE site_sections ADD COLUMN sub_heading_font_size VARCHAR(50) DEFAULT 'text-xl' AFTER subtitle";
        $pdo->exec($sql);
        echo "Column 'sub_heading_font_size' added successfully.";
    } else {
        echo "Column 'sub_heading_font_size' already exists.";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>