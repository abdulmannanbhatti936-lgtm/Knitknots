<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' | ' . SITE_NAME : SITE_NAME; ?></title>
    
    <!-- Meta Tags -->
    <meta name="description" content="KnitKnots Studio — Handmade crochet flowers, keychains & home decor. Islamabad, Pakistan. Delivery all over Pakistan.">
    
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Style -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
</head>
<body>

<header>
    <div class="nav-container container-custom">
        <a href="<?php echo SITE_URL; ?>" class="logo">
            Knit<span>Knots</span>
        </a>
        
        <button class="mobile-menu-btn">
            <i class="fas fa-bars"></i>
        </button>
        
        <ul class="nav-links">
            <li><a href="<?php echo SITE_URL; ?>">Home</a></li>
            <li><a href="<?php echo SITE_URL; ?>/pages/shop.php">Shop</a></li>
            <li><a href="<?php echo SITE_URL; ?>/pages/custom-order.php">Custom Order</a></li>
            <li><a href="<?php echo SITE_URL; ?>/pages/about.php">About</a></li>
            <li><a href="<?php echo SITE_URL; ?>/pages/reviews.php">Reviews</a></li>
        </ul>
    </div>
</header>