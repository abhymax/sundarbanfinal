<?php
require_once 'db_connect.php';

try {
    // Add whatsapp_label column
    $pdo->exec("ALTER TABLE settings ADD COLUMN whatsapp_label VARCHAR(255) DEFAULT 'Chat with us'");
    echo "Added whatsapp_label column.<br>";
} catch (PDOException $e) {
    echo "whatsapp_label column might already exist or error: " . $e->getMessage() . "<br>";
}

try {
    // Add call_label column
    $pdo->exec("ALTER TABLE settings ADD COLUMN call_label VARCHAR(255) DEFAULT 'Call Now'");
    echo "Added call_label column.<br>";
} catch (PDOException $e) {
    echo "call_label column might already exist or error: " . $e->getMessage() . "<br>";
}

echo "Database update complete.";
?>