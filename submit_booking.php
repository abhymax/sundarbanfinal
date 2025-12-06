<?php
session_start();
header('Content-Type: application/json');
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

try {
    // 1. Verify Captcha
    $user_captcha = (int)($_POST['captcha'] ?? 0);
    $server_captcha = (int)($_SESSION['captcha_result'] ?? -1);

    if ($user_captcha !== $server_captcha) {
        throw new Exception('Incorrect security answer. Please calculate again.');
    }

    // 2. Collect Inputs
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $package = trim($_POST['package'] ?? 'Custom Inquiry');
    $travel_date = trim($_POST['date'] ?? date('Y-m-d')); // Note: form field is 'date'
    $travelers_raw = $_POST['travelers'] ?? '0';

    // 3. Map Data for Database
    // Parse "2 Adults" -> 2
    $adults = (int) filter_var($travelers_raw, FILTER_SANITIZE_NUMBER_INT);
    if ($adults == 0) $adults = 2; // Default fallback

    // Append Email to Message (since DB lacks email column)
    $message = "Travelers: " . $travelers_raw;
    if (!empty($email)) {
        $message .= "\nEmail: " . $email;
    }

    if (empty($name) || empty($phone)) {
        throw new Exception('Name and Phone are required.');
    }

    // 4. Insert
    $sql = "INSERT INTO bookings (name, phone, package_name, travel_date, adults, children, message, status, created_at) 
            VALUES (:name, :phone, :package, :travel_date, :adults, 0, :message, 'pending', NOW())";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':name' => $name,
        ':phone' => $phone,
        ':package' => $package,
        ':travel_date' => $travel_date,
        ':adults' => $adults,
        ':message' => $message
    ]);

    // 5. Success
    unset($_SESSION['captcha_result']);
    echo json_encode([
        'status' => 'success',
        'message' => 'Inquiry Sent! We will contact you soon.'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>