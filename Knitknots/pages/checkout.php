<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$productId = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
if ($productId <= 0) redirect(SITE_URL . '/pages/shop.php');

// Fetch product details
try {
    $stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch();
    if (!$product) redirect(SITE_URL . '/pages/shop.php');
} catch (PDOException $e) {
    redirect(SITE_URL . '/pages/shop.php');
}

$success = false;
$orderId = 0;
$error = '';
$waUrl = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $phone = sanitize($_POST['phone']);
    $address = sanitize($_POST['address']);
    $notes = sanitize($_POST['notes']);
    $payment_method = sanitize($_POST['payment_method']);

    if (empty($name) || empty($phone) || empty($address)) {
        $error = 'Please fill all required delivery fields.';
    } else {
        try {
            $pdo->beginTransaction();
            
            $stmt = $pdo->prepare("INSERT INTO orders (product_id, customer_name, customer_phone, customer_address, payment_method, notes) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$productId, $name, $phone, $address, $payment_method, $notes]);
            $orderId = $pdo->lastInsertId();
            
            $pdo->commit();
            $success = true;
            
            // Generate WhatsApp URL for manual click
            $waUrl = whatsappOrderUrl($product['name'], $product['price'], $orderId, $name);
            
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = 'Something went wrong while processing your order: ' . $e->getMessage();
        }
    }
}

$pageTitle = 'Checkout';
require_once __DIR__ . '/layout/header.php';
?>

<section style="background-color: var(--cream); padding: 60px 0;">
    <div class="container-custom">
        <div style="text-align: center; margin-bottom: 40px;">
            <h1 style="font-family: var(--font-heading); font-size: 2.5rem; color: var(--brown); margin-bottom: 10px;">Complete Your Order</h1>
            <p style="color: var(--text-light);">Please provide your delivery details below.</p>
        </div>

        <div style="display: flex; gap: 40px; align-items: flex-start;">
            
            <!-- Checkout Form / Success Section -->
            <div style="flex: 2; background: white; padding: 40px; border-radius: 20px; box-shadow: var(--shadow);">
                <?php if ($success): ?>
                    <div style="text-align: center; padding: 40px 0;">
                        <i class="fas fa-check-circle" style="color: var(--sage); font-size: 4rem; margin-bottom: 20px;"></i>
                        <h2 style="color: var(--brown); margin-bottom: 15px;">Order Placed Successfully! 🎉</h2>
                        
                        <div style="background: rgba(122, 158, 126, 0.1); border-radius: 15px; padding: 25px; margin-bottom: 30px; text-align: left; display: inline-block; width: 100%; max-width: 400px;">
                            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid rgba(0,0,0,0.05); padding-bottom: 15px; margin-bottom: 15px;">
                                <span style="color: var(--text-light); font-weight: bold;">Order ID:</span>
                                <span style="color: var(--brown); font-weight: bold;">#<?php echo $orderId; ?></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid rgba(0,0,0,0.05); padding-bottom: 15px; margin-bottom: 15px;">
                                <span style="color: var(--text-light); font-weight: bold;">Product:</span>
                                <span style="color: var(--brown); font-weight: bold; text-align: right;"><?php echo htmlspecialchars($product['name']); ?></span>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: var(--text-light); font-weight: bold;">Price:</span>
                                <span style="color: var(--rose); font-weight: bold; font-size: 1.1rem;"><?php echo formatPrice($product['price']); ?></span>
                            </div>
                        </div>

                        <p style="color: var(--text-light); margin-bottom: 25px;">Please click the button below to confirm your order and get payment details on WhatsApp.</p>
                        
                        <a href="<?php echo htmlspecialchars($waUrl); ?>" class="btn btn-whatsapp" target="_blank" style="font-size: 1.2rem; padding: 15px 30px; width: 100%; max-width: 400px; display: inline-block;">
                            <i class="fab fa-whatsapp"></i> Send Order on WhatsApp
                        </a>
                    </div>
                <?php else: ?>
                    <?php if ($error): ?>
                        <div style="background: #f8d7da; color: #842029; padding: 15px; border-radius: 10px; margin-bottom: 25px;">
                            <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <h3 style="color: var(--brown); margin-bottom: 20px;">Delivery Information</h3>
                        
                        <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                            <div class="form-group" style="flex: 1; margin-bottom: 0;">
                                <label>Full Name *</label>
                                <input type="text" id="name" name="name" class="form-control" required placeholder="e.g. Ayesha Khan">
                            </div>
                            <div class="form-group" style="flex: 1; margin-bottom: 0;">
                                <label>WhatsApp Number *</label>
                                <input type="tel" id="phone" name="phone" class="form-control" required placeholder="e.g. 03XXXXXXXXX">
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 20px;">
                            <label>Full Delivery Address *</label>
                            <textarea id="address" name="address" class="form-control" rows="3" required placeholder="House #, Street, Area, City"></textarea>
                        </div>

                        <div class="form-group" style="margin-bottom: 30px;">
                            <label>Special Notes (Optional)</label>
                            <textarea id="notes" name="notes" class="form-control" rows="2" placeholder="Any specific requirements..."></textarea>
                        </div>

                        <h3 style="color: var(--brown); margin-bottom: 20px;">Payment Method</h3>
                        <div style="margin-bottom: 40px;">
                            <div style="padding: 15px; border: 2px solid var(--rose); border-radius: 10px; background: rgba(201, 114, 122, 0.05); margin-bottom: 15px;">
                                <label style="display: flex; align-items: center; gap: 15px; cursor: pointer; margin: 0;">
                                    <input type="radio" name="payment_method" value="whatsapp" checked>
                                    <div>
                                        <div style="font-weight: bold; color: var(--brown);">Order via WhatsApp</div>
                                        <div style="font-size: 0.85rem; color: var(--text-light);">Place order first, then get payment details on WhatsApp.</div>
                                    </div>
                                </label>
                            </div>

                            <div style="padding: 15px; border: 2px solid #eee; border-radius: 10px; opacity: 0.6;">
                                <label style="display: flex; align-items: center; gap: 15px;">
                                    <input type="radio" name="payment_method" value="jazzcash" disabled>
                                    <div>
                                        <div style="font-weight: bold; color: var(--text-light);">JazzCash / Easypaisa (Direct)</div>
                                        <div style="font-size: 0.85rem; color: var(--text-light);">Updating shortly... Please use WhatsApp for now.</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 1.1rem; padding: 15px;">
                            Place Order & Proceed
                        </button>
                    </form>
                <?php endif; ?>
            </div>

            <!-- Order Summary -->
            <div style="flex: 1; background: white; padding: 30px; border-radius: 20px; box-shadow: var(--shadow);">
                <h3 style="color: var(--brown); margin-bottom: 25px;">Order Summary</h3>
                
                <div style="display: flex; gap: 20px; border-bottom: 1px solid #eee; padding-bottom: 20px; margin-bottom: 20px;">
                    <img src="<?php echo getProductImageUrl($product['image']); ?>" style="width: 80px; height: 80px; object-fit: cover; border-radius: 10px;">
                    <div>
                        <div style="font-size: 0.8rem; color: var(--rose); font-weight: bold; text-transform: uppercase;"><?php echo htmlspecialchars($product['category_name']); ?></div>
                        <div style="font-weight: bold; color: var(--brown); margin-bottom: 5px;"><?php echo htmlspecialchars($product['name']); ?></div>
                        <div style="color: var(--rose); font-weight: bold;"><?php echo formatPrice($product['price']); ?></div>
                    </div>
                </div>
                
                <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 1.2rem; color: var(--brown);">
                    <span>Total amount:</span>
                    <span style="color: var(--rose);"><?php echo formatPrice($product['price']); ?></span>
                </div>
                
                <div style="margin-top: 30px; background: rgba(201, 114, 122, 0.05); border: 1px solid rgba(201, 114, 122, 0.1); padding: 15px; border-radius: 10px;">
                    <p style="font-size: 0.85rem; color: var(--text); margin: 0;">
                        <i class="fas fa-info-circle" style="color: var(--rose); margin-right: 5px;"></i>
                        Advance payment is required. We will provide JazzCash/Easypaisa details after order placement.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
