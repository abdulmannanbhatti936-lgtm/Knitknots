<?php
require_once __DIR__ . '/../includes/header.php';

$requestId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($requestId <= 0) redirect('index.php?section=custom');

$success = '';

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = sanitize($_POST['status']);

    try {
        $stmt = $pdo->prepare("UPDATE custom_orders SET status = ? WHERE id = ?");
        $stmt->execute([$status, $requestId]);
        $success = 'Request status updated successfully!';
    } catch (PDOException $e) {
        // error handled silently
    }
}

// Fetch request details
try {
    $stmt = $pdo->prepare("SELECT * FROM custom_orders WHERE id = ?");
    $stmt->execute([$requestId]);
    $co = $stmt->fetch();
    
    if (!$co) redirect('index.php?section=custom');
} catch (PDOException $e) {
    redirect('index.php?section=custom');
}
?>

<div style="padding: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
        <div>
            <h1 style="font-family: var(--font-heading); font-size: 2.5rem; color: var(--brown); margin-bottom: 5px;">Custom Request #<?php echo $co['id']; ?></h1>
            <p style="color: var(--text-light);">Submitted on <?php echo date('F d, Y h:i A', strtotime($co['created_at'])); ?></p>
        </div>
        <a href="index.php?section=custom" class="btn btn-outline" style="padding: 10px 20px;">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <?php if ($success): ?>
        <div style="background: #d1e7dd; color: #0f5132; padding: 15px; border-radius: 10px; margin-bottom: 25px; max-width: 800px;">
            <i class="fas fa-check-circle"></i> <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <div style="display: flex; gap: 30px;">
        <!-- Main Content -->
        <div style="flex: 2;">
            <div style="background: white; border-radius: 20px; box-shadow: var(--shadow); overflow: hidden; margin-bottom: 30px;">
                <div style="padding: 30px; border-bottom: 1px solid #eee;">
                    <h3 style="font-size: 1.5rem; color: var(--brown); margin-bottom: 25px;">Request Details</h3>
                    
                    <div style="margin-bottom: 30px;">
                        <label style="font-size: 0.8rem; font-weight: bold; color: var(--text-light); text-transform: uppercase; margin-bottom: 10px; display: block;">Customer</label>
                        <div style="background: #fdfdfd; padding: 25px; border-radius: 15px; border: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <h4 style="font-weight: bold; color: var(--brown); margin-bottom: 5px; font-size: 1.2rem;"><?php echo $co['customer_name']; ?></h4>
                                <p style="color: var(--text-light); margin: 0;"><?php echo $co['customer_phone']; ?></p>
                            </div>
                            <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $co['customer_phone']); ?>" target="_blank" class="btn btn-success" style="background: #25D366; color: white; border: none; padding: 12px 25px; border-radius: 20px; text-decoration: none; font-weight: bold; font-size: 0.9rem;">
                                <i class="fab fa-whatsapp"></i> Chat on WhatsApp
                            </a>
                        </div>
                    </div>

                    <div style="margin-bottom: 30px;">
                        <label style="font-size: 0.8rem; font-weight: bold; color: var(--text-light); text-transform: uppercase; margin-bottom: 10px; display: block;">Description</label>
                        <div style="background: #fcfaf8; padding: 30px; border-radius: 15px; border: 1px solid #eee; font-size: 1.1rem; line-height: 1.6; font-style: italic; color: var(--text);">
                            "<?php echo nl2br($co['description']); ?>"
                        </div>
                    </div>

                    <div style="display: flex; gap: 30px; padding-top: 20px; border-top: 1px solid #eee;">
                        <div style="flex: 1;">
                            <label style="font-size: 0.8rem; font-weight: bold; color: var(--text-light); text-transform: uppercase; margin-bottom: 5px; display: block;">Proposed Budget</label>
                            <div style="font-size: 1.2rem; font-weight: bold; color: var(--rose);"><?php echo $co['budget'] ?: 'Not specified'; ?></div>
                        </div>
                        <div style="flex: 1;">
                            <label style="font-size: 0.8rem; font-weight: bold; color: var(--text-light); text-transform: uppercase; margin-bottom: 5px; display: block;">Status</label>
                            <span style="display: inline-block; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; text-transform: uppercase;
                                <?php 
                                switch($co['status']) {
                                    case 'new': echo 'background: #cce5ff; color: #004085;'; break;
                                    case 'accepted': echo 'background: #d4edda; color: #155724;'; break;
                                    case 'reviewed': echo 'background: #fff3cd; color: #856404;'; break;
                                    case 'rejected': echo 'background: #f8d7da; color: #721c24;'; break;
                                    default: echo 'background: #e2e3e5; color: #383d41;';
                                }
                                ?>">
                                <?php echo $co['status']; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div style="flex: 1;">
            <!-- Reference Image -->
            <div style="background: white; border-radius: 20px; box-shadow: var(--shadow); padding: 30px; margin-bottom: 30px;">
                <h3 style="font-size: 1.2rem; color: var(--brown); margin-bottom: 20px;">Reference Image</h3>
                <?php if ($co['reference_image']): ?>
                    <div style="border-radius: 15px; overflow: hidden; border: 1px solid #eee;">
                        <img src="<?php echo getProductImageUrl($co['reference_image']); ?>" style="width: 100%; height: auto; max-height: 400px; object-fit: contain;">
                        <div style="text-align: center; padding: 10px; background: #fdfdfd; border-top: 1px solid #eee;">
                            <a href="<?php echo getProductImageUrl($co['reference_image']); ?>" target="_blank" style="color: var(--brown); font-size: 0.9rem; font-weight: bold; text-decoration: none;">
                                <i class="fas fa-external-link-alt"></i> View Full Image
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div style="background: #fdfdfd; border-radius: 15px; display: flex; flex-direction: column; align-items: center; justify-content: center; border: 2px dashed #eee; padding: 40px 20px;">
                        <i class="fas fa-image" style="font-size: 3rem; color: #ddd; margin-bottom: 10px;"></i>
                        <span style="font-size: 0.8rem; color: #999; font-weight: bold; text-transform: uppercase;">No Image</span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Manage Status -->
            <div style="background: white; border-radius: 20px; box-shadow: var(--shadow); padding: 30px;">
                <h3 style="font-size: 1.2rem; color: var(--brown); margin-bottom: 20px;">Manage Request</h3>
                <form action="" method="POST">
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="font-size: 0.8rem; font-weight: bold; color: var(--text-light); text-transform: uppercase; margin-bottom: 10px; display: block;">Update Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="new" <?php echo $co['status'] == 'new' ? 'selected' : ''; ?>>New Request</option>
                            <option value="reviewed" <?php echo $co['status'] == 'reviewed' ? 'selected' : ''; ?>>Reviewed</option>
                            <option value="accepted" <?php echo $co['status'] == 'accepted' ? 'selected' : ''; ?>>Accepted</option>
                            <option value="rejected" <?php echo $co['status'] == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 1.1rem; border-radius: 20px;">
                        Update Request
                    </button>
                </form>
            </div>
            
            <div style="margin-top: 20px; background: #fdfaf6; border-radius: 15px; padding: 20px; display: flex; align-items: flex-start; gap: 15px; border: 1px solid #f5ebd9;">
                <i class="fas fa-magic" style="color: var(--sage); margin-top: 3px;"></i>
                <div style="font-size: 0.85rem; color: #887a6c; line-height: 1.6; font-style: italic;">
                    Tip: Reach out with a custom quote on WhatsApp before accepting the request!
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
