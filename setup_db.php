<?php
require_once 'db_connect.php';

$sqlFile = 's4dg23e2bt.sql';

if (!file_exists($sqlFile)) {
    die("Error: SQL file '$sqlFile' not found.");
}

$sql = file_get_contents($sqlFile);

try {
    $pdo->exec($sql);
    echo "<div style='font-family: sans-serif; padding: 20px; background: #e6fffa; color: #047857; border: 1px solid #047857; border-radius: 8px;'>";
    echo "<strong>Success!</strong> Database updated successfully.<br>";
    echo "The <code>site_sections</code> table has been created and seeded.<br>";
    echo "<a href='index.php' style='display: inline-block; margin-top: 10px; text-decoration: none; background: #047857; color: white; padding: 8px 16px; border-radius: 4px;'>Go to Home Page</a>";
    echo "</div>";
} catch (PDOException $e) {
    echo "<div style='font-family: sans-serif; padding: 20px; background: #fff5f5; color: #c53030; border: 1px solid #c53030; border-radius: 8px;'>";
    echo "<strong>Error updating database:</strong><br>" . htmlspecialchars($e->getMessage());
    echo "</div>";
}
?>