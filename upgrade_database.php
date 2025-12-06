<?php
require_once 'db_connect.php';

try {
    // Add video_url column
    try {
        $pdo->exec("ALTER TABLE site_sections ADD COLUMN video_url VARCHAR(255) DEFAULT NULL");
        echo "Added video_url column.<br>";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column') !== false) {
            echo "Column video_url already exists.<br>";
        } else {
            throw $e;
        }
    }

    // Add overlay_opacity column
    try {
        $pdo->exec("ALTER TABLE site_sections ADD COLUMN overlay_opacity DECIMAL(2,1) DEFAULT 0.6");
        echo "Added overlay_opacity column.<br>";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column') !== false) {
            echo "Column overlay_opacity already exists.<br>";
        } else {
            throw $e;
        }
    }

    echo 'Database Upgraded Successfully!';

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>