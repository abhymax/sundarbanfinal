<?php
require_once 'db_connect.php';

try {
    $stmt = $pdo->query("DESCRIBE settings");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($columns);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>