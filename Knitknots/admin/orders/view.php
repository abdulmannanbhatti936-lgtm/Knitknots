<?php
require_once __DIR__ . '/../includes/header.php';

$orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($orderId <= 0) redirect('index.php?section=orders');

$success = '';

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderStatus = sanitize($_POST['order_status']);
    $paymentStatus = sanitize($_POST['payment_status']);

    try {
        $stmt = $pdo->prepare("UPDATE orders SET order_status = ?, payment_status = ? WHERE id = ?");
        $stmt->execute([$orderStatus, $paymentStatus, $orderId]);
        $success = 'Order status updated successfully!';
    } catch (PDOException $e) {
        // error handled silently
    }
}

// Fetch order details
try {
    $stmt = $pdo->prepare("SELECT o.*, p.name as product_name, p.price as product_price FROM orders o 
                           LEFT JOIN products p ON o.product_id = p.id 
                           WHERE o.id = ?");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch();
    
    if (!$order) redirect('index.php?section=orders');
} catch (PDOException $e) {
    redirect('index.php?section=orders');
}
?>

<div style="padding: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
        <div>
            <h1 style="font-family: var(--font-heading); font-size: 2.5rem; color: var(--brown); margin-bottom: 5px;">Order #<?php echo $order['id']; ?></h1>
            <p style="color: var(--text-light);">Placed on <?php echo date('F d, Y h:i A', strtotime($order['created_at'])); ?></p>
        </div>
        <a href="index.php?section=orders" class="btn btn-outline" style="padding: 10px 20px;">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <?php if ($success): ?>
        <div style="background: #d1e7dd; color: #0f5132; padding: 15px; border-radius: 10px; margin-bottom: 25px; max-width: 800px;">
            <i class="fas fa-check-circle"></i> <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <div style="display: flex; gap: 30px;">
        <!-- Main Details -->
        <div style="flex: 2;">
            <div style="background: white; border-radius: 20px; box-shadow: var(--shadow); overflow: hidden; margin-bottom: 30px;">
                <div style="padding: 30px; border-bottom: 1px solid #eee;">
                    <h3 style="font-size: 1.5rem; color: var(--brown); margin-bottom: 20px;">Customer Information</h3>
                    
                    <div style="display: flex; gap: 30px;">
                        <div style="flex: 1;">
                            <label style="font-size: 0.8rem; font-weight: bold; color: var(--text-light); text-transform: uppercase; margin-bottom: 10px; display: block;">Shipping To</label>
                            <div style="background: #fdfdfd; padding: 25px; border-radius: 15px; border: 1px solid #eee;">
                                <h4 style="font-weight: bold; color: var(--brown); margin-bottom: 5px;"><?php echo $order['customer_name']; ?></h4>
                                <p style="color: var(--text-light); margin-bottom: 15px;"><?php echo $order['customer_phone']; ?></p>
                                <div style="font-size: 0.9rem; color: #888; font-style: italic; line-height: 1.6;">
                                    <?php echo nl2br($order['customer_address']); ?>
                                </div>
                                
                                <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $order['customer_phone']); ?>" target="_blank" class="btn btn-success" style="display: block; text-align: center; margin-top: 25px; background: #25D366; color: white; border: none; padding: 12px; border-radius: 20px; text-decoration: none; font-weight: bold;">
                                    <i class="fab fa-whatsapp"></i> Message Customer
                                </a>
                            </div>
                        </div>
                        <div style="flex: 1;">
                            <label style="font-size: 0.8rem; font-weight: bold; color: var(--text-light); text-transform: uppercase; margin-bottom: 10px; display: block;">Order Meta</label>
                            <div style="display: flex; flex-direction: column; gap: 15px;">
                                <div style="display: flex; justify-content: space-between; padding: 15px; border-bottom: 1px solid #eee; font-size: 0.9rem;">
                                    <span style="color: var(--text-light);">Payment Method</span>
                                    <span style="font-weight: bold; color: var(--brown);"><?php echo ucfirst($order['payment_method']); ?></span>
                                </div>
                                <div style="padding: 20px; background: #fff3cd; border-radius: 15px; border: 1px solid #ffeeba;">
                                    <span style="font-size: 0.8rem; font-weight: bold; color: #856404; text-transform: uppercase; display: block; margin-bottom: 5px;">Order Notes</span>
                                    <p style="font-size: 0.9rem; color: #856404; margin: 0;"><?php echo $order['notes'] ?: 'No notes from customer.'; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="padding: 30px;">
                    <h3 style="font-size: 1.5rem; color: var(--brown); margin-bottom: 20px;">Order Items</h3>
                    <div style="border-radius: 15px; border: 1px solid #eee; overflow: hidden;">
                        <table style="width: 100%; border-collapse: collapse; text-align: left;">
                            <thead style="background: #fdfdfd;">
                                <tr>
                                    <th style="padding: 15px 25px; font-size: 0.8rem; color: var(--text-light); text-transform: uppercase;">Product</th>
                                    <th style="padding: 15px 25px; font-size: 0.8rem; color: var(--text-light); text-transform: uppercase; text-align: right;">Total Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="padding: 25px;">
                                        <div style="font-weight: bold; color: var(--brown); font-size: 1.2rem;"><?php echo $order['product_name'] ?: 'N/A'; ?></div>
                                        <div style="font-size: 0.8rem; color: var(--text-light); text-transform: uppercase; margin-top: 5px;">Direct Checkout / Unit Price Included</div>
                                    </td>
                                    <td style="padding: 25px; text-align: right;">
                                        <div style="font-size: 1.5rem; font-weight: bold; color: var(--rose);"><?php echo formatPrice($order['product_price'] ?: 0); ?></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Update -->
        <div style="flex: 1;">
            <div style="background: white; border-radius: 20px; box-shadow: var(--shadow); padding: 30px; position: sticky; top: 30px; margin-bottom: 30px;">
                <h3 style="font-size: 1.2rem; color: var(--brown); margin-bottom: 25px;">Manage Status</h3>
                <form action="" method="POST">
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="font-size: 0.8rem; font-weight: bold; color: var(--text-light); text-transform: uppercase; margin-bottom: 10px; display: block;">Order Status</label>
                        <select name="order_status" id="order_status" class="form-control">
                            <option value="new" <?php echo $order['order_status'] == 'new' ? 'selected' : ''; ?>>New Order</option>
                            <option value="processing" <?php echo $order['order_status'] == 'processing' ? 'selected' : ''; ?>>Processing</option>
                            <option value="shipped" <?php echo $order['order_status'] == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                            <option value="delivered" <?php echo $order['order_status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                            <option value="cancelled" <?php echo $order['order_status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom: 30px;">
                        <label style="font-size: 0.8rem; font-weight: bold; color: var(--text-light); text-transform: uppercase; margin-bottom: 10px; display: block;">Payment Status</label>
                        <select name="payment_status" id="payment_status" class="form-control">
                            <option value="pending" <?php echo $order['payment_status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="paid" <?php echo $order['payment_status'] == 'paid' ? 'selected' : ''; ?>>Paid</option>
                            <option value="failed" <?php echo $order['payment_status'] == 'failed' ? 'selected' : ''; ?>>Failed</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 1.1rem; border-radius: 20px;">
                        Update Order
                    </button>
                </form>
            </div>
            
            <div style="background: rgba(201, 114, 122, 0.05); border-radius: 15px; padding: 20px; display: flex; align-items: flex-start; gap: 15px; border: 1px solid rgba(201, 114, 122, 0.1);">
                <i class="fas fa-info-circle" style="color: var(--rose); margin-top: 3px;"></i>
                <div style="font-size: 0.85rem; color: var(--brown); line-height: 1.6;">
                    Status updates will be visible to the admin only. It is recommended to contact the customer on WhatsApp after status changes to keep them informed.
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>