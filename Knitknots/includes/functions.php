<?php

/**
 * Sanitize user input
 */
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Upload image to specified folder
 */
function uploadImage($file, $folder = 'products/') {
    $targetDir = UPLOAD_DIR . $folder;
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    $fileName = basename($file["name"]);
    $imageFileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $newFileName = uniqid('kk_') . '.' . $imageFileType;
    $targetFile = $targetDir . $newFileName;

    // Check if image file is a actual image or fake image
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) return false;

    // Check file size (limit to 5MB)
    if ($file["size"] > 5000000) return false;

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "webp") {
        return false;
    }

    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        return $folder . $newFileName;
    } else {
        return false;
    }
}

/**
 * Get product image URL with fallback
 */
function getProductImageUrl($image) {
    if ($image && file_exists(UPLOAD_DIR . $image)) {
        return UPLOAD_URL . $image;
    }
    return SITE_URL . '/assets/img/placeholder.jpg';
}

/**
 * Format price in PKR
 */
function formatPrice($price) {
    return 'PKR ' . number_format($price, 0);
}

/**
 * Redirect to URL
 */
function redirect($url) {
    header("Location: " . $url);
    exit();
}

/**
 * Generate transaction reference number
 */
function generateTxnRefNo() {
    return 'KK-' . date('YmdHis') . '-' . rand(100, 999);
}

/**
 * Generate WhatsApp Order URL with Order details
 */
function whatsappOrderUrl($productName, $price, $orderId = '', $customerName = '') {
    $text = "Assalamu Alaikum KnitKnots Studio! 🧶\n\n";
    if ($orderId) {
        $text .= "*Order ID:* #" . $orderId . "\n";
    }
    if ($customerName) {
        $text .= "*Customer:* " . $customerName . "\n";
    }
    $text .= "*Product:* " . $productName . "\n";
    $text .= "*Price:* " . formatPrice($price) . "\n\n";
    $text .= "Please provide payment details to confirm my order. (No COD accepted)";
    
    return "https://wa.me/" . WHATSAPP_NUMBER . "?text=" . urlencode($text);
}

/**
 * Generate WhatsApp Custom Order URL
 */
function whatsappCustomUrl($details) {
    $text = "Assalamu Alaikum KnitKnots Studio! I'm interested in a custom order: \n\n";
    $text .= $details;
    return "https://wa.me/" . WHATSAPP_NUMBER . "?text=" . urlencode($text);
}
