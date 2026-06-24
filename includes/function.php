<?php
function sanitize($data)
{
    return htmlspecialchars(strip_tags(trim($data)));
}

function uploadImage($file, $folder = 'products')
{
    $allowed = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($file['type'], $allowed))
        return false;
    if ($file['size'] > 5 * 1024 * 1024)
        return false;

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('img_') . '.' . $ext;
    $dest = UPLOAD_DIR . $folder . '/' . $filename;

    if (!is_dir(UPLOAD_DIR . $folder)) {
        mkdir(UPLOAD_DIR . $folder, 0755, true);
    }

    if (move_uploaded_file($file['tmp_name'], $dest)) {
        return $folder . '/' . $filename;
    }
    return false;
}

function getProductImageUrl($image)
{
    if (!$image)
        return SITE_URL . '/assets/images/placeholder.png';
    return UPLOAD_URL . $image;
}

function formatPrice($price)
{
    return 'PKR ' . number_format($price, 0);
}

function redirect($url)
{
    header('Location: ' . $url);
    exit;
}

function isAdminLoggedIn()
{
    return isset($_SESSION['admin_id']);
}

function requireAdmin()
{
    if (!isAdminLoggedIn()) {
        redirect(SITE_URL . '/admin/login.php');
    }
}

function generateTxnRefNo()
{
    return 'KK' . date('YmdHis') . rand(100, 999);
}

function whatsappOrderUrl($product, $customer = null)
{
    $msg = "Hello! I want to order:\n";
    $msg .= "*Product:* " . $product['name'] . "\n";
    $msg .= "*Price:* " . formatPrice($product['price']) . "\n";
    if ($customer) {
        $msg .= "*Name:* " . $customer['name'] . "\n";
        $msg .= "*Address:* " . $customer['address'];
    }
    return "https://wa.me/" . WHATSAPP_NUMBER . "?text=" . urlencode($msg);
}