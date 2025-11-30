<?php
require_once 'db_connect.php';

try {
    // 1. Add created_at to testimonials if missing
    $columns = $pdo->query("SHOW COLUMNS FROM testimonials")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('created_at', $columns)) {
        $pdo->exec("ALTER TABLE testimonials ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
        echo "Added created_at column to testimonials.<br>";
    }

    // 2. Populate settings with placeholder data if empty
    $stmt = $pdo->prepare("UPDATE settings SET 
        facebook_url = COALESCE(NULLIF(facebook_url, ''), '#'),
        instagram_url = COALESCE(NULLIF(instagram_url, ''), '#'),
        youtube_url = COALESCE(NULLIF(youtube_url, ''), '#')
        WHERE id = 1");
    $stmt->execute();
    echo "Updated settings with placeholder social links.<br>";

    echo "Database fixes completed successfully.";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>