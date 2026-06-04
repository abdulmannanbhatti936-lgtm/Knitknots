<?php
require_once __DIR__ . '/../includes/header.php';

$typeFilter = isset($_GET['type']) ? sanitize($_GET['type']) : 'all';

try {
    $unionQuery = "
    SELECT 
      o.id,
      o.customer_name,
      o.customer_phone,
      o.order_status AS status,
      o.payment_status,
      o.payment_method,
      o.created_at,
      p.name AS product_name,
      p.price AS amount,
      'normal' AS order_type,
      NULL AS budget,
      NULL AS description
    FROM orders o
    LEFT JOIN products p ON o.product_id = p.id
    
    UNION ALL
    
    SELECT 
      co.id,
      co.customer_name,
      co.customer_phone,
      co.status,
      NULL AS payment_status,
      'whatsapp' AS payment_method,
      co.created_at,
      NULL AS product_name,
      NULL AS amount,
      'custom' AS order_type,
      co.budget,
      co.description
    FROM custom_orders co
    ";

    $sql = "SELECT * FROM (" . $unionQuery . ") combined_orders";
    
    if ($typeFilter === 'normal') {
        $sql .= " WHERE order_type = 'normal'";
    } elseif ($typeFilter === 'custom') {
        $sql .= " WHERE order_type = 'custom'";
    }

    $sql .= " ORDER BY created_at DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $orders = $stmt->fetchAll();
} catch (PDOException $e) {
    $orders = [];
    error_log("Orders Admin Query Error: " . $e->getMessage());
}
?>

<div style="padding: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
        <div>
            <h1 style="font-family: var(--font-heading); font-size: 2.5rem; color: var(--brown); margin-bottom: 5px;">Orders Management</h1>
            <p style="color: var(--text-light);">Track and manage all customer & custom orders</p>
        </div>
        
        <div style="display: flex; gap: 10px;">
            <a href="?type=all&section=orders" style="padding: 8px 15px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; text-decoration: none; border: 1px solid #eee; <?php echo $typeFilter === 'all' ? 'background: var(--brown); color: white; border-color: var(--brown);' : 'background: white; color: var(--text-light);' ?>">All</a>
            <a href="?type=normal&section=orders" style="padding: 8px 15px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; text-decoration: none; border: 1px solid #eee; <?php echo $typeFilter === 'normal' ? 'background: var(--brown); color: white; border-color: var(--brown);' : 'background: white; color: var(--text-light);' ?>">Normal Orders</a>
            <a href="?type=custom&section=orders" style="padding: 8px 15px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; text-decoration: none; border: 1px solid #eee; <?php echo $typeFilter === 'custom' ? 'background: var(--brown); color: white; border-color: var(--brown);' : 'background: white; color: var(--text-light);' ?>">Custom Orders</a>
        </div>
    </div>

    <div style="background: white; border-radius: 20px; box-shadow: var(--shadow); overflow: hidden;">
        <table class="admin-table" style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #fdfdfd; border-bottom: 1px solid #eee;">
                    <th style="padding: 15px 25px; font-size: 0.8rem; color: var(--text-light); text-transform: uppercase;">#</th>
                    <th style="padding: 15px 25px; font-size: 0.8rem; color: var(--text-light); text-transform: uppercase;">Customer</th>
                    <th style="padding: 15px 25px; font-size: 0.8rem; color: var(--text-light); text-transform: uppercase;">Phone</th>
                    <th style="padding: 15px 25px; font-size: 0.8rem; color: var(--text-light); text-transform: uppercase;">Type</th>
                    <th style="padding: 15px 25px; font-size: 0.8rem; color: var(--text-light); text-transform: uppercase;">Product/Description</th>
                    <th style="padding: 15px 25px; font-size: 0.8rem; color: var(--text-light); text-transform: uppercase;">Amount</th>
                    <th style="padding: 15px 25px; font-size: 0.8rem; color: var(--text-light); text-transform: uppercase;">Payment</th>
                    <th style="padding: 15px 25px; font-size: 0.8rem; color: var(--text-light); text-transform: uppercase; text-align: center;">Status</th>
                    <th style="padding: 15px 25px; font-size: 0.8rem; color: var(--text-light); text-transform: uppercase;">Date</th>
                    <th style="padding: 15px 25px; font-size: 0.8rem; color: var(--text-light); text-transform: uppercase;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orders)): ?>
                    <?php foreach ($orders as $o): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 15px 25px; font-weight: bold; color: var(--brown);">#<?php echo $o['id']; ?></td>
                            <td style="padding: 15px 25px;">
                                <div style="font-weight: bold; color: var(--brown);"><?php echo htmlspecialchars($o['customer_name']); ?></div>
                            </td>
                            <td style="padding: 15px 25px;">
                                <div style="font-size: 0.85rem; color: var(--text-light);"><?php echo htmlspecialchars($o['customer_phone']); ?></div>
                            </td>
                            <td style="padding: 15px 25px;">
                                <?php if ($o['order_type'] === 'normal'): ?>
                                    <span style="display: inline-block; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; background: #d4edda; color: #155724;">Normal</span>
                                <?php else: ?>
                                    <span style="display: inline-block; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; background: #EDE7F6; color: #4527A0;">Custom</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 15px 25px;">
                                <?php if ($o['order_type'] === 'normal'): ?>
                                    <div style="font-size: 0.9rem; color: var(--text);"><?php echo htmlspecialchars($o['product_name'] ?: 'N/A'); ?></div>
                                <?php else: ?>
                                    <div style="font-size: 0.9rem; color: var(--text); max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?php echo htmlspecialchars($o['description']); ?>">
                                        <?php echo htmlspecialchars($o['description']); ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 15px 25px;">
                                <div style="font-size: 0.9rem; font-weight: bold; color: var(--rose);">
                                    <?php 
                                    if ($o['order_type'] === 'normal') {
                                        echo formatPrice($o['amount']);
                                    } else {
                                        echo $o['budget'] ? formatPrice($o['budget']) : 'TBD';
                                    }
                                    ?>
                                </div>
                            </td>
                            <td style="padding: 15px 25px;">
                                <?php if ($o['order_type'] === 'normal' && $o['payment_status']): ?>
                                    <span style="padding: 3px 10px; border-radius: 15px; font-size: 0.7rem; font-weight: bold; text-transform: uppercase; <?php echo $o['payment_status'] === 'paid' ? 'background: #d4edda; color: #155724;' : 'background: #f8d7da; color: #721c24;'; ?>">
                                        <?php echo htmlspecialchars($o['payment_status']); ?>
                                    </span>
                                <?php else: ?>
                                    <span style="padding: 3px 10px; border-radius: 15px; font-size: 0.7rem; font-weight: bold; text-transform: uppercase; background: #e2e3e5; color: #383d41;">
                                        WhatsApp
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 15px 25px; text-align: center;">
                                <span style="display: inline-block; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; text-transform: uppercase;
                                    <?php 
                                    switch($o['status']) {
                                        case 'new': 
                                        case 'pending': echo 'background: #cce5ff; color: #004085;'; break;
                                        case 'processing': echo 'background: #fff3cd; color: #856404;'; break;
                                        case 'shipped': echo 'background: #e2d9f3; color: #381c82;'; break;
                                        case 'delivered': 
                                        case 'completed': echo 'background: #d4edda; color: #155724;'; break;
                                        case 'cancelled': 
                                        case 'rejected': echo 'background: #f8d7da; color: #721c24;'; break;
                                        default: echo 'background: #e2e3e5; color: #383d41;';
                                    }
                                    ?>">
                                    <?php echo htmlspecialchars($o['status']); ?>
                                </span>
                            </td>
                            <td style="padding: 15px 25px;">
                                <div style="font-size: 0.8rem; color: var(--text-light);"><?php echo date('d M, Y', strtotime($o['created_at'])); ?></div>
                            </td>
                            <td style="padding: 15px 25px; text-align: right;">
                                <?php if ($o['order_type'] === 'normal'): ?>
                                    <a href="<?php echo SITE_URL; ?>/admin/orders/view.php?id=<?php echo $o['id']; ?>&section=orders" class="btn btn-outline" style="padding: 5px 15px; font-size: 0.85rem; border-color: #eee; color: var(--text);">View</a>
                                <?php else: ?>
                                    <a href="<?php echo SITE_URL; ?>/admin/custom-orders/view.php?id=<?php echo $o['id']; ?>&section=orders" class="btn btn-outline" style="padding: 5px 15px; font-size: 0.85rem; border-color: #eee; color: var(--text);">View</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" style="padding: 40px; text-align: center; color: var(--text-light); font-style: italic;">No orders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>