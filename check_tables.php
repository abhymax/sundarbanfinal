<?php
require_once 'db_connect.php';

try {
    echo "<h2>Tables:</h2>";
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>" . htmlspecialchars($table) . "</li>";
    }
    echo "</ul>";

    echo "<h2>Settings (ID=1):</h2>";
    $stmt = $pdo->query("SELECT * FROM settings WHERE id = 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($settings);
    echo "</pre>";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>