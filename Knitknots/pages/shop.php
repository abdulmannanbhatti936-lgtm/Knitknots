<?php
$pageTitle = 'Shop';
require_once __DIR__ . '/layout/header.php';

// Get current category and search term
$catFilter = isset($_GET['category']) ? $_GET['category'] : 'all';
$searchTerm = isset($_GET['search']) ? sanitize($_GET['search']) : '';

// Fetch all categories
try {
    $catStmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
    $categories = $catStmt->fetchAll();
} catch (PDOException $e) {
    $categories = [];
}

// Build query for products
try {
    $sql = "SELECT p.*, c.name as category_name FROM products p 
            JOIN categories c ON p.category_id = c.id WHERE 1=1";
    $params = [];

    if ($catFilter !== 'all') {
        $sql .= " AND c.slug = ?";
        $params[] = $catFilter;
    }

    if (!empty($searchTerm)) {
        $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
        $params[] = "%$searchTerm%";
        $params[] = "%$searchTerm%";
    }

    $sql .= " ORDER BY p.created_at DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    $products = [];
}
?>

<section style="background-color: var(--white); border-bottom: 1px solid #eee;">
    <div class="container-custom" style="text-align: center;">
        <h1 style="font-size: 3rem; margin-bottom: 20px;">Our Studio</h1>
        <p style="color: var(--text-light); max-width: 600px; margin: 0 auto 40px; font-size: 1.1rem;">Browse our collection of handmade crochet treasures. Each piece is unique and made with attention to detail.</p>
        
        <!-- Search Bar -->
        <div style="max-width: 500px; margin: 0 auto 40px;">
            <form action="" method="GET" style="position: relative;">
                <input type="text" name="search" placeholder="Search for products..." 
                    class="form-control" style="border-radius: 30px; padding: 15px 20px;"
                    value="<?php echo $searchTerm; ?>">
                <button type="submit" class="btn btn-primary" style="position: absolute; right: 5px; top: 5px; bottom: 5px; border-radius: 50%; width: 42px; padding: 0;">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <!-- Category Filters -->
        <div class="filters">
            <a href="?category=all" 
               class="filter-btn <?php echo $catFilter === 'all' ? 'active' : '' ?>">
               All Collections
            </a>
            <?php foreach ($categories as $cat): ?>
                <a href="?category=<?php echo $cat['slug']; ?>" 
                   class="filter-btn <?php echo $catFilter === $cat['slug'] ? 'active' : '' ?>">
                   <?php echo $cat['name']; ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section>
    <div class="container-custom">
        <?php if (!empty($products)): ?>
            <div class="product-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <?php if ($product['is_featured']): ?>
                            <div class="product-badge">Featured</div>
                        <?php endif; ?>
                        
                        <div class="product-image">
                            <img src="<?php echo getProductImageUrl($product['image']); ?>" alt="<?php echo $product['name']; ?>">
                            <div class="product-overlay">
                                <a href="<?php echo SITE_URL; ?>/pages/product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary"><i class="fas fa-eye"></i> View</a>
                            </div>
                        </div>
                        
                        <div class="product-info">
                            <div class="product-category"><?php echo $product['category_name']; ?></div>
                            <h3 class="product-name">
                                <a href="<?php echo SITE_URL; ?>/pages/product.php?id=<?php echo $product['id']; ?>"><?php echo $product['name']; ?></a>
                            </h3>
                            <div class="product-price"><?php echo formatPrice($product['price']); ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 50px;">
                <h2 style="margin-bottom: 15px;">No treasures found</h2>
                <p style="color: var(--text-light); margin-bottom: 25px;">Try adjusting your search or filters to find what you're looking for.</p>
                <a href="?category=all" class="btn btn-primary">View All Collections</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/layout/footer.php'; ?>