<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'No data received']);
    exit();
}

// Logic to identify order and update status
$orderId = isset($data['order_id']) ? (int)$data['order_id'] : 0;
$responseCode = isset($data['pp_ResponseCode']) ? sanitize($data['pp_ResponseCode']) : '';

try {
    if ($orderId > 0 && $responseCode === '000') {
        
        $pdo->beginTransaction();
        
        // Update order status to 'paid' and 'processing'
        $updateStmt = $pdo->prepare("UPDATE orders SET payment_status = 'paid', order_status = 'processing' WHERE id = ?");
        $updateStmt->execute([$orderId]);
        
        // Fetch order and product details for WhatsApp redirect
        $stmt = $pdo->prepare("SELECT o.*, p.name as product_name, p.price as product_price 
                               FROM orders o 
                               LEFT JOIN products p ON o.product_id = p.id 
                               WHERE o.id = ?");
        $stmt->execute([$orderId]);
        $orderDetail = $stmt->fetch();
        
        $pdo->commit();
        
        if ($orderDetail) {
            $redirectUrl = whatsappOrderUrl($orderDetail['product_name'], $orderDetail['product_price'], $orderDetail['id'], $orderDetail['customer_name']);
            echo json_encode(['success' => true, 'redirect_url' => $redirectUrl]);
            exit();
        } else {
            echo json_encode(['success' => false, 'error' => 'Order details not found after payment update']);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid payment verification']);
        exit();
    }
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Payment verification error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Database error during verification']);
}