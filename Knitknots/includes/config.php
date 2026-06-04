<?php
define('SITE_NAME', 'KnitKnots Studio');
define('SITE_URL', 'http://localhost/Knitknots');
define('WHATSAPP_NUMBER', '923199712130');
define('UPLOAD_DIR', dirname(__DIR__) . '/assets/uploads/');
define('UPLOAD_URL', SITE_URL . '/assets/uploads/');

// JazzCash Credentials (User will fill these later)
define('JAZZCASH_MERCHANT_ID', 'YOUR_MERCHANT_ID');
define('JAZZCASH_PASSWORD', 'YOUR_PASSWORD');
define('JAZZCASH_INTEGRITY_SALT', 'YOUR_SALT');
define('JAZZCASH_SANDBOX_URL', 'https://sandbox.jazzcash.com.pk/CustomerPortal/transactionmanagement/merchantform/');
define('JAZZCASH_LIVE_URL', 'https://jazzcash.com.pk/CustomerPortal/transactionmanagement/merchantform/');
define('JAZZCASH_MODE', 'sandbox');

// Easypaisa Credentials (User will fill these later)
define('EASYPAISA_STORE_ID', 'YOUR_STORE_ID');
define('EASYPAISA_HASH_KEY', 'YOUR_HASH_KEY');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}