<?php
require_once 'db_connect.php';

try {
    // Check if video_url exists in site_sections
    $stmt = $pdo->query("SHOW COLUMNS FROM site_sections LIKE 'video_url'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE site_sections ADD COLUMN video_url VARCHAR(255) DEFAULT '' AFTER image_url");
        echo "Success: Added 'video_url' column to site_sections.<br>";
    } else {
        echo "'video_url' column already exists.<br>";
    }
    echo "Database is ready for video banners.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>