<?php
require_once 'db_connect.php';

try {
    // Add second_logo_url column to settings table
    $sql = "ALTER TABLE settings ADD COLUMN second_logo_url VARCHAR(255) DEFAULT NULL AFTER logo_url";
    $pdo->exec($sql);
    echo "Column 'second_logo_url' added successfully to 'settings' table.";
} catch (PDOException $e) {
    if ($e->getCode() == '42S21') { // Duplicate column error code
        echo "Column 'second_logo_url' already exists.";
    } else {
        echo "Error updating database: " . $e->getMessage();
    }
}
?>