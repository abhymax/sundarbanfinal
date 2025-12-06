<?php
session_start();
header('Content-Type: application/json');
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}

try {
    // 1. Verify Captcha
    $userCaptcha = (int) ($_POST['captcha'] ?? 0);
    $realCaptcha = (int) ($_SESSION['contact_captcha'] ?? -1);

    if ($userCaptcha !== $realCaptcha) {
        throw new Exception("Incorrect security answer. Please try again.");
    }

    // 2. Sanitize inputs
    $fname = trim($_POST['first_name'] ?? '');
    $lname = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($email) || empty($message)) {
        throw new Exception("Email and Message are required.");
    }

    // 3. Save to DB
    $stmt = $pdo->prepare("INSERT INTO contact_messages (first_name, last_name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$fname, $lname, $email, $phone, $subject, $message]);

    // Clear used captcha
    unset($_SESSION['contact_captcha']);

    echo json_encode(['status' => 'success', 'message' => 'Message sent successfully!']);

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>