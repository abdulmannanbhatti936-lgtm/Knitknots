<?php
$pageTitle = 'Home';
require_once __DIR__ . '/layout/header.php';

// Fetch featured products
try {
    $stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p 
                           JOIN categories c ON p.category_id = c.id 
                           WHERE p.is_featured = 1 
                           LIMIT 6");
    $stmt->execute();
    $featuredProducts = $stmt->fetchAll();
} catch (PDOException $e) {
    $featuredProducts = [];
}
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container-custom">
        <div class="hero-content">
            <h1>Handmade with Love,<br><span>Flowers that Last Forever.</span></h1>
            <p>Discover unique crochet keychains, stunning flower pots, and cozy home decor handcrafted in Islamabad.</p>
            <div class="hero-btns">
                <a href="<?php echo SITE_URL; ?>/pages/shop.php" class="btn btn-primary">Shop Now</a>
                <a href="<?php echo SITE_URL; ?>/pages/custom-order.php" class="btn btn-outline">Custom Order</a>
            </div>
        </div>
    </div>
</section>

<!-- Features Strip -->
<div class="features-strip">
    <div class="container-custom">
        <div class="features-container">
            <div class="feature-item">
                <i class="fas fa-hand-holding-heart"></i>
                <span>100% Handmade</span>
            </div>
            <div class="feature-item">
                <i class="fas fa-truck"></i>
                <span>Pakistan Delivery</span>
            </div>
            <div class="feature-item">
                <i class="fas fa-magic"></i>
                <span>Custom Designs</span>
            </div>
            <div class="feature-item">
                <i class="fas fa-smile"></i>
                <span>Made with Love</span>
            </div>
        </div>
    </div>
</div>

<!-- Featured Products Section -->
<section>
    <div class="container-custom">
        <div class="section-title">
            <h2>Featured Collections</h2>
            <p>Our most loved handmade creations</p>
        </div>
        
        <div class="product-grid">
            <?php if (!empty($featuredProducts)): ?>
                <?php foreach ($featuredProducts as $product): ?>
                    <div class="product-card">
                        <?php if ($product['is_featured']): ?>
                            <div class="product-badge">Best Seller</div>
                        <?php endif; ?>
                        
                        <div class="product-image">
                            <img src="<?php echo getProductImageUrl($product['image']); ?>" alt="<?php echo $product['name']; ?>">
                            <div class="product-overlay">
                                <a href="<?php echo SITE_URL; ?>/pages/product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary"><i class="fas fa-eye"></i> View</a>
                            </div>
                        </div>
                        
                        <div class="product-info">
                            <div class="product-category"><?php echo $product['category_name']; ?></div>
                            <h3 class="product-name"><?php echo $product['name']; ?></h3>
                            <div class="product-price"><?php echo formatPrice($product['price']); ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align:center; padding:50px;">
                    <p>No featured products found.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div style="text-align: center; margin-top: 50px;">
            <a href="<?php echo SITE_URL; ?>/pages/shop.php" class="btn btn-primary">View All Collections</a>
        </div>
    </div>
</section>

<!-- Custom Order CTA -->
<section>
    <div class="container-custom">
        <div class="custom-cta">
            <div class="custom-cta-content">
                <h2 style="font-family: var(--font-heading); font-size: 2.5rem; color: var(--brown); margin-bottom: 20px;">Want something unique? We do custom orders!</h2>
                <p style="font-size: 1.1rem; color: var(--text-light); margin-bottom: 30px;">From personalized characters to specific floral arrangements, let's bring your imagination to life with yarn.</p>
                <div class="hero-btns" style="justify-content: flex-start;">
                    <a href="<?php echo SITE_URL; ?>/pages/custom-order.php" class="btn btn-primary">See How it Works</a>
                    <a href="https://wa.me/<?php echo WHATSAPP_NUMBER; ?>" target="_blank" class="btn btn-outline" style="border-color: #25D366; color: #25D366;">
                        <i class="fab fa-whatsapp"></i> Chat on WhatsApp
                    </a>
                </div>
            </div>
            <div class="custom-cta-image"></div>
        </div>
    </div>
</section>

<!-- No COD Notice -->
<section style="background: var(--cream); padding: 40px 0;">
    <div class="container-custom" style="text-align: center;">
        <p style="color: var(--brown); font-weight: 600;"><i class="fas fa-info-circle" style="color: var(--rose);"></i> Notice: We require advance payment for all orders. No COD available.</p>
    </div>
</section>

<?php require_once __DIR__ . '/layout/footer.php'; ?>