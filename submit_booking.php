<?php
header('Content-Type: application/json');
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

try {
    // 1. Collect and Sanitize Inputs
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $package = trim($_POST['package'] ?? '');
    $travel_date = trim($_POST['travel_date'] ?? '');
    $adults = (int) ($_POST['adults'] ?? 0);
    $children = (int) ($_POST['children'] ?? 0);
    $message = trim($_POST['message'] ?? '');

    // 2. Validate Required Fields
    if (empty($name) || empty($phone) || empty($travel_date)) {
        throw new Exception('Name, Phone, and Travel Date are required.');
    }

    // 3. Insert into Database using Prepared Statements
    $sql = "INSERT INTO bookings (name, phone, package_name, travel_date, adults, children, message, status, created_at) 
            VALUES (:name, :phone, :package, :travel_date, :adults, :children, :message, 'pending', NOW())";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':name' => $name,
        ':phone' => $phone,
        ':package' => $package,
        ':travel_date' => $travel_date,
        ':adults' => $adults,
        ':children' => $children,
        ':message' => $message
    ]);

    // 4. Return Success Response
    echo json_encode([
        'status' => 'success',
        'message' => 'Booking received! We will contact you soon.'
    ]);

} catch (Exception $e) {
    // 5. Return Error Response
    // Log the error for debugging if needed: error_log($e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'Submission failed: ' . $e->getMessage()
    ]);
}
?>