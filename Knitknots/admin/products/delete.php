<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/config.php';

requireAdmin();

$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($productId > 0) {
    try {
        // Fetch image path before deleting
        $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
        $stmt->execute([$productId]);
        $product = $stmt->fetch();

        /* 
        if ($product && $product['image']) {
            if (file_exists(UPLOAD_DIR . $product['image'])) {
                unlink(UPLOAD_DIR . $product['image']);
            }
        }
        */

        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$productId]);
    } catch (PDOException $e) {
        // error handled silently
    }
}

header("Location: " . SITE_URL . "/admin/products/index.php?section=products");
exit();