<?php
require_once __DIR__ . '/../includes/header.php';

try {
    $stmt = $pdo->query("SELECT p.*, c.name as category_name FROM products p 
                         JOIN categories c ON p.category_id = c.id 
                         ORDER BY p.created_at DESC");
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    $products = [];
}
?>

<div style="padding: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
        <div>
            <h1 style="font-family: var(--font-heading); font-size: 2.5rem; color: var(--brown); margin-bottom: 5px;">Products Management</h1>
            <p style="color: var(--text-light);">Manage your handmade crochet collection</p>
        </div>
        <a href="add.php?section=products" class="btn btn-primary" style="padding: 10px 20px;">
            <i class="fas fa-plus"></i> Add New Product
        </a>
    </div>

    <div style="background: white; border-radius: 20px; box-shadow: var(--shadow); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #fdfdfd; border-bottom: 1px solid #eee;">
                    <th style="padding: 15px 25px; font-size: 0.8rem; color: var(--text-light); text-transform: uppercase;">Product</th>
                    <th style="padding: 15px 25px; font-size: 0.8rem; color: var(--text-light); text-transform: uppercase;">Category</th>
                    <th style="padding: 15px 25px; font-size: 0.8rem; color: var(--text-light); text-transform: uppercase;">Price</th>
                    <th style="padding: 15px 25px; font-size: 0.8rem; color: var(--text-light); text-transform: uppercase; text-align: center;">Stock</th>
                    <th style="padding: 15px 25px; font-size: 0.8rem; color: var(--text-light); text-transform: uppercase; text-align: center;">Featured</th>
                    <th style="padding: 15px 25px; font-size: 0.8rem; color: var(--text-light); text-transform: uppercase;"></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $p): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 15px 25px;">
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <div style="width: 50px; height: 50px; border-radius: 10px; overflow: hidden; border: 1px solid #eee;">
                                        <img src="<?php echo getProductImageUrl($p['image']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                    <div style="font-weight: bold; color: var(--brown);"><?php echo $p['name']; ?></div>
                                </div>
                            </td>
                            <td style="padding: 15px 25px; color: var(--text-light);"><?php echo $p['category_name']; ?></td>
                            <td style="padding: 15px 25px; font-weight: bold; color: var(--rose);"><?php echo formatPrice($p['price']); ?></td>
                            <td style="padding: 15px 25px; text-align: center;">
                                <span style="padding: 5px 15px; background: #f9f9f9; border-radius: 20px; font-size: 0.9rem; color: #666; border: 1px solid #eee;">
                                    <?php echo $p['stock']; ?>
                                </span>
                            </td>
                            <td style="padding: 15px 25px; text-align: center;">
                                <?php if ($p['is_featured']): ?>
                                    <span style="display: inline-block; padding: 3px 10px; background: var(--rose); color: white; border-radius: 15px; font-size: 0.7rem; text-transform: uppercase; font-weight: bold;">Featured</span>
                                <?php else: ?>
                                    <span style="color: #ccc; font-size: 0.8rem; text-transform: uppercase; font-weight: bold;">No</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 15px 25px; text-align: right;">
                                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                                    <a href="edit.php?id=<?php echo $p['id']; ?>&section=products" class="btn btn-outline" style="padding: 5px 15px; font-size: 0.85rem; border-color: #eee; color: var(--text);">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="delete.php?id=<?php echo $p['id']; ?>&section=products" class="btn btn-outline" style="padding: 5px 15px; font-size: 0.85rem; border-color: #f8d7da; color: #dc3545; background: #fff5f5;" onclick="return confirm('Are you sure you want to delete this product?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="padding: 40px; text-align: center; color: var(--text-light); font-style: italic;">No products found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>