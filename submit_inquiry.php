<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $name = trim($_POST['name']);
        $phone = trim($_POST['phone']);

        // Basic validation
        if (empty($name) || empty($phone)) {
            throw new Exception("Name and Phone are required.");
        }

        // Insert into database
        // Assuming 'inquiries' table exists with name, phone, created_at
        // If table schema is different, this might need adjustment. 
        // Based on typical schema:
        $stmt = $pdo->prepare("INSERT INTO inquiries (name, phone, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$name, $phone]);

        $_SESSION['success_message'] = "Thank you! We will contact you shortly.";
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
    }
}

header('Location: index.php');
exit;
