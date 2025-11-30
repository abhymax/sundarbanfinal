<?php
require_once 'db_connect.php';

$sqlFile = 'update_schema.sql';

if (!file_exists($sqlFile)) {
    die("Error: SQL file '$sqlFile' not found.");
}

$sql = file_get_contents($sqlFile);

try {
    $pdo->exec($sql);
    echo "Database schema updated successfully.";
} catch (PDOException $e) {
    echo "Error updating database: " . $e->getMessage();
}
?>