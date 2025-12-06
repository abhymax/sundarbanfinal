<?php
require_once 'db_connect.php';

try {
    // Add favicon_url column
    $stmt = $pdo->query("SHOW COLUMNS FROM settings LIKE 'favicon_url'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE settings ADD COLUMN favicon_url VARCHAR(255) DEFAULT NULL AFTER second_logo_url");
        echo "Added favicon_url column.<br>";
    }

    // Add call_button_number column
    $stmt = $pdo->query("SHOW COLUMNS FROM settings LIKE 'call_button_number'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE settings ADD COLUMN call_button_number VARCHAR(50) DEFAULT NULL AFTER whatsapp_number");

        // Populate with existing phone number as default
        $pdo->exec("UPDATE settings SET call_button_number = phone WHERE call_button_number IS NULL");
        echo "Added call_button_number column.<br>";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>