<?php
require_once __DIR__ . '/includes/header.php';

// Fetch stats
try {
    $totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    $pendingOrders = $pdo->query("SELECT COUNT(*) FROM orders WHERE order_status = 'new'")->fetchColumn();
    $newCustomRequests = $pdo->query("SELECT COUNT(*) FROM custom_orders WHERE status = 'new'")->fetchColumn();

    // Fetch recent 5 orders
    $recentOrders = $pdo->query("SELECT o.*, p.name as product_name FROM orders o 
                                 JOIN products p ON o.product_id = p.id 
                                 ORDER BY o.created_at DESC LIMIT 5")->fetchAll();
} catch (PDOException $e) {
    $totalProducts = 0; $totalOrders = 0; $pendingOrders = 0; $newCustomRequests = 0;
    $recentOrders = [];
}
?>

<div style="padding: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
        <div>
            <h1 style="font-family: var(--font-heading); font-size: 2.5rem; color: var(--brown); margin-bottom: 5px;">Admin Dashboard</h1>
            <p style="color: var(--text-light);">Welcome back, <span style="font-weight: bold; color: var(--brown);"><?php echo $_SESSION['admin_username']; ?></span>!</p>
        </div>
        <a href="<?php echo SITE_URL; ?>" class="btn btn-outline" target="_blank" style="border-radius: 30px; padding: 10px 20px;">
            <i class="fas fa-external-link-alt"></i> View Public Site
        </a>
    </div>

    <!-- Stats Grid -->
    <div style="display: flex; gap: 20px; margin-bottom: 40px;">
        <div style="flex: 1; background: white; padding: 25px; border-radius: 20px; box-shadow: var(--shadow); display: flex; align-items: center; gap: 20px;">
            <div style="width: 60px; height: 60px; background: rgba(74, 55, 40, 0.1); color: var(--brown); border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                <i class="fas fa-box"></i>
            </div>
            <div>
                <h3 style="font-size: 2rem; color: var(--brown); margin-bottom: 2px;"><?php echo $totalProducts; ?></h3>
                <p style="font-size: 0.8rem; font-weight: bold; color: var(--text-light); text-transform: uppercase;">Products</p>
            </div>
        </div>
        <div style="flex: 1; background: white; padding: 25px; border-radius: 20px; box-shadow: var(--shadow); display: flex; align-items: center; gap: 20px;">
            <div style="width: 60px; height: 60px; background: rgba(46, 204, 113, 0.1); color: #2ecc71; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <div>
                <h3 style="font-size: 2rem; color: var(--brown); margin-bottom: 2px;"><?php echo $totalOrders; ?></h3>
                <p style="font-size: 0.8rem; font-weight: bold; color: var(--text-light); text-transform: uppercase;">Total Orders</p>
            </div>
        </div>
        <div style="flex: 1; background: white; padding: 25px; border-radius: 20px; box-shadow: var(--shadow); display: flex; align-items: center; gap: 20px;">
            <div style="width: 60px; height: 60px; background: rgba(241, 196, 15, 0.1); color: #f1c40f; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <h3 style="font-size: 2rem; color: var(--brown); margin-bottom: 2px;"><?php echo $pendingOrders; ?></h3>
                <p style="font-size: 0.8rem; font-weight: bold; color: var(--text-light); text-transform: uppercase;">Pending</p>
            </div>
        </div>
        <div style="flex: 1; background: white; padding: 25px; border-radius: 20px; box-shadow: var(--shadow); display: flex; align-items: center; gap: 20px;">
            <div style="width: 60px; height: 60px; background: rgba(212, 163, 115, 0.1); color: var(--rose); border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                <i class="fas fa-magic"></i>
            </div>
            <div>
                <h3 style="font-size: 2rem; color: var(--brown); margin-bottom: 2px;"><?php echo $newCustomRequests; ?></h3>
                <p style="font-size: 0.8rem; font-weight: bold; color: var(--text-light); text-transform: uppercase;">Requests</p>
            </div>
        </div>
    </div>

    <!-- Recent Orders Section -->
    <div style="background: white; border-radius: 20px; box-shadow: var(--shadow); overflow: hidden;">
        <div style="padding: 25px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
            <h2 style="font-family: var(--font-heading); font-size: 1.5rem; color: var(--brown);">Recent Orders</h2>
            <a href="<?php echo SITE_URL; ?>/admin/orders/index.php?section=orders" class="btn btn-primary" style="padding: 8px 20px; font-size: 0.9rem;">View All Orders</a>
        </div>
        
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #fdfdfd; border-bottom: 1px solid #eee;">
                    <th style="padding: 15px 25px; font-size: 0.8rem; color: var(--text-light); text-transform: uppercase;">Order ID</th>
                    <th style="padding: 15px 25px; font-size: 0.8rem; color: var(--text-light); text-transform: uppercase;">Customer</th>
                    <th style="padding: 15px 25px; font-size: 0.8rem; color: var(--text-light); text-transform: uppercase;">Product</th>
                    <th style="padding: 15px 25px; font-size: 0.8rem; color: var(--text-light); text-transform: uppercase;">Date</th>
                    <th style="padding: 15px 25px; font-size: 0.8rem; color: var(--text-light); text-transform: uppercase; text-align: center;">Status</th>
                    <th style="padding: 15px 25px; font-size: 0.8rem; color: var(--text-light); text-transform: uppercase;"></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($recentOrders)): ?>
                    <?php foreach ($recentOrders as $order): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 15px 25px; font-weight: bold; color: var(--brown);">#<?php echo $order['id']; ?></td>
                            <td style="padding: 15px 25px;">
                                <div style="font-weight: bold; color: var(--brown);"><?php echo $order['customer_name']; ?></div>
                                <div style="font-size: 0.85rem; color: var(--text-light);"><?php echo $order['customer_phone']; ?></div>
                            </td>
                            <td style="padding: 15px 25px; color: var(--text);"><?php echo $order['product_name']; ?></td>
                            <td style="padding: 15px 25px; font-size: 0.9rem; color: var(--text-light);"><?php echo date('d M, Y', strtotime($order['created_at'])); ?></td>
                            <td style="padding: 15px 25px; text-align: center;">
                                <span style="display: inline-block; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; text-transform: uppercase;
                                    <?php echo $order['order_status'] === 'new' ? 'background: #fff3cd; color: #856404;' : ($order['order_status'] === 'completed' ? 'background: #d4edda; color: #155724;' : 'background: #e2e3e5; color: #383d41;'); ?>">
                                    <?php echo $order['order_status']; ?>
                                </span>
                            </td>
                            <td style="padding: 15px 25px; text-align: right;">
                                <a href="<?php echo SITE_URL; ?>/admin/orders/view.php?id=<?php echo $order['id']; ?>&section=orders" class="btn btn-outline" style="padding: 5px 15px; font-size: 0.85rem; border-color: #eee; color: var(--text);">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="padding: 40px; text-align: center; color: var(--text-light); font-style: italic;">No orders yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>