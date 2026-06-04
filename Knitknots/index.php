<?php
ob_start();
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

switch ($page) {
    case 'home':
        require_once __DIR__ . '/pages/home.php';
        break;
    case 'shop':
        require_once __DIR__ . '/pages/shop.php';
        break;
    case 'product':
        require_once __DIR__ . '/pages/product.php';
        break;
    case 'custom-order':
        require_once __DIR__ . '/pages/custom-order.php';
        break;
    case 'checkout':
        require_once __DIR__ . '/pages/checkout.php';
        break;
    case 'about':
        require_once __DIR__ . '/pages/about.php';
        break;
    case 'reviews':
        require_once __DIR__ . '/pages/reviews.php';
        break;

    default:
        require_once __DIR__ . '/pages/home.php';
        break;
}