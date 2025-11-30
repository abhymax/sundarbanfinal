<?php
require_once 'db_connect.php';

try {
    // Create home_about table
    $sql = "CREATE TABLE IF NOT EXISTS home_about (
        id INT AUTO_INCREMENT PRIMARY KEY,
        tagline VARCHAR(255) DEFAULT 'Who We Are',
        title VARCHAR(255) DEFAULT 'Sundarban Boat Safari',
        description TEXT,
        image_url VARCHAR(255),
        feature_1_icon VARCHAR(50) DEFAULT 'directions_boat',
        feature_1_text VARCHAR(255) DEFAULT 'Premium Private Boats',
        feature_2_icon VARCHAR(50) DEFAULT 'restaurant',
        feature_2_text VARCHAR(255) DEFAULT 'Farm-to-Table Local Cuisine',
        feature_3_icon VARCHAR(50) DEFAULT 'verified_user',
        feature_3_text VARCHAR(255) DEFAULT 'Government Authorized Guides',
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";

    $pdo->exec($sql);
    echo "Table 'home_about' created successfully.<br>";

    // Check if data exists
    $stmt = $pdo->query("SELECT COUNT(*) FROM home_about");
    if ($stmt->fetchColumn() == 0) {
        // Insert default data
        $sql = "INSERT INTO home_about (tagline, title, description, image_url, feature_1_icon, feature_1_text, feature_2_icon, feature_2_text, feature_3_icon, feature_3_text) VALUES (
            'Who We Are',
            'Sundarban Boat Safari',
            'We are natives of the delta. Our mission is to show you the raw, untamed beauty of the Sundarbans while ensuring safety, comfort, and minimal ecological footprint.',
            'https://images.unsplash.com/photo-1624360065050-32a679d67b0c?q=80&w=1000&auto=format&fit=crop',
            'directions_boat',
            'Premium Private Boats',
            'restaurant',
            'Farm-to-Table Local Cuisine',
            'verified_user',
            'Government Authorized Guides'
        )";
        $pdo->exec($sql);
        echo "Default data inserted.<br>";
    } else {
        echo "Data already exists.<br>";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>