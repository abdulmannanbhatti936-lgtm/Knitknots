<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($productId <= 0) {
    redirect(SITE_URL . '/pages/shop.php');
}

// Fetch product details
try {
    $stmt = $pdo->prepare("SELECT p.*, c.name as category_name, c.slug as category_slug FROM products p 
                           JOIN categories c ON p.category_id = c.id 
                           WHERE p.id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch();

    if (!$product) {
        redirect(SITE_URL . '/pages/shop.php');
    }

    // Fetch related products (same category, excluding current)
    $relStmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p 
                              JOIN categories c ON p.category_id = c.id 
                              WHERE p.category_id = ? AND p.id != ? 
                              LIMIT 4");
    $relStmt->execute([$product['category_id'], $productId]);
    $relatedProducts = $relStmt->fetchAll();

} catch (PDOException $e) {
    redirect(SITE_URL . '/pages/shop.php');
}

$pageTitle = $product['name'];
require_once __DIR__ . '/layout/header.php';
?>

<section style="padding-top: 50px;">
    <div class="container-custom">
        <!-- Breadcrumb -->
        <div style="margin-bottom: 30px;">
            <a href="<?php echo SITE_URL; ?>" style="color: var(--text-light);">Home</a> / 
            <a href="<?php echo SITE_URL; ?>/pages/shop.php" style="color: var(--text-light);">Shop</a> / 
            <a href="<?php echo SITE_URL; ?>/pages/shop.php?category=<?php echo $product['category_slug']; ?>" style="color: var(--text-light);"><?php echo $product['category_name']; ?></a> / 
            <span style="color: var(--brown); font-weight: bold;"><?php echo $product['name']; ?></span>
        </div>

        <div style="display: flex; gap: 50px; align-items: flex-start; margin-bottom: 80px;">
            <!-- Image Column -->
            <div style="flex: 1;">
                <img src="<?php echo getProductImageUrl($product['image']); ?>" alt="<?php echo $product['name']; ?>" style="width: 100%; border-radius: 20px; box-shadow: var(--shadow);">
            </div>

            <!-- Content Column -->
            <div style="flex: 1;">
                <span style="color: var(--rose); font-weight: bold; text-transform: uppercase; font-size: 0.9rem; margin-bottom: 15px; display: block;"><?php echo $product['category_name']; ?></span>
                <h1 style="font-family: var(--font-heading); font-size: 3rem; color: var(--brown); margin-bottom: 20px;"><?php echo $product['name']; ?></h1>
                
                <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 30px;">
                    <div style="color: var(--rose); font-weight: bold; font-size: 2rem;"><?php echo formatPrice($product['price']); ?></div>
                    <div style="color: var(--text-light);">
                        <?php echo $product['stock'] > 0 ? $product['stock'] . ' units available' : 'Out of stock'; ?>
                    </div>
                </div>
                
                <div style="font-size: 1.1rem; color: var(--text); line-height: 1.8; margin-bottom: 40px;">
                    <p><?php echo nl2br($product['description']); ?></p>
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; flex-direction: column; gap: 15px; margin-bottom: 40px;">
                    <a href="<?php echo SITE_URL; ?>/pages/checkout.php?product_id=<?php echo $product['id']; ?>" class="btn btn-primary" style="text-align: center; font-size: 1.1rem;">
                        Proceed to Checkout
                    </a>
                    
                    <a href="https://wa.me/<?php echo WHATSAPP_NUMBER; ?>" target="_blank" class="btn btn-outline" style="text-align: center; border-color: #25D366; color: #25D366; font-size: 1.1rem;">
                        <i class="fab fa-whatsapp"></i> Chat for Queries
                    </a>
                </div>

                <!-- No COD Notice -->
                <div style="background: var(--cream); padding: 20px; border-radius: 15px; display: flex; align-items: center; gap: 15px;">
                    <i class="fas fa-info-circle" style="color: var(--rose); font-size: 1.5rem;"></i>
                    <div>
                        <strong style="color: var(--brown);">Secure Check-out</strong>
                        <p style="color: var(--text-light); margin-top: 5px; font-size: 0.9rem;">Advance payment required via JazzCash/Easypaisa. No Cash on Delivery (COD).</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Products -->
<section style="background: var(--cream); border-top: 1px solid #eee;">
    <div class="container-custom">
        <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 40px;">
            <div>
                <h2 style="font-family: var(--font-heading); font-size: 2.5rem; color: var(--brown);">Related Creations</h2>
            </div>
            <a href="<?php echo SITE_URL; ?>/pages/shop.php" style="color: var(--rose); font-weight: bold; text-decoration: underline;">View All</a>
        </div>
        
        <div class="product-grid">
            <?php foreach ($relatedProducts as $rel): ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="<?php echo getProductImageUrl($rel['image']); ?>" alt="<?php echo $rel['name']; ?>">
                        <div class="product-overlay">
                            <a href="?id=<?php echo $rel['id']; ?>" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3 class="product-name"><?php echo $rel['name']; ?></h3>
                        <div class="product-price"><?php echo formatPrice($rel['price']); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/layout/footer.php'; ?>