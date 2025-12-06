<?php
session_start();
require_once '../db_connect.php';

// Security Check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Get ID and Status
$id = $_GET['id'] ?? null;
$status = $_GET['status'] ?? null;

if ($id && $status) {
    try {
        $stmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);

        // Redirect back with success message (optional, for now just redirect)
        header('Location: dashboard.php');
        exit;
    } catch (PDOException $e) {
        die("Error updating status: " . $e->getMessage());
    }
} else {
    // Invalid request
    header('Location: dashboard.php');
    exit;
}
?>