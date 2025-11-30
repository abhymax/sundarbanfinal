<?php
require_once 'db_connect.php';

try {
    // Check if column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM settings LIKE 'logo_url'");
    $exists = $stmt->fetch();

    if (!$exists) {
        $pdo->exec("ALTER TABLE settings ADD COLUMN logo_url VARCHAR(255) DEFAULT NULL AFTER site_name");
        echo "Column 'logo_url' added successfully.";
    } else {
        echo "Column 'logo_url' already exists.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>