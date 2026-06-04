<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method Not Allowed']);
    exit();
}

$name = sanitize($_POST['name'] ?? '');
$phone = sanitize($_POST['phone'] ?? '');
$description = sanitize($_POST['description'] ?? '');
$budget = sanitize($_POST['budget'] ?? '');
$imagePath = '';

if (empty($name) || empty($phone) || empty($description)) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit();
}

// Handle Image Upload
if (isset($_FILES['reference_image']) && $_FILES['reference_image']['error'] === 0) {
    $imagePath = uploadImage($_FILES['reference_image'], 'custom/');
}

try {
    $stmt = $pdo->prepare("INSERT INTO custom_orders (customer_name, customer_phone, description, reference_image, budget) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $phone, $description, $imagePath, $budget]);
    echo json_encode(['success' => true, 'message' => 'Custom order request received']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}