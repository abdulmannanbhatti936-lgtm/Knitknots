<?php
require_once __DIR__ . '/../includes/header.php';

try {
    $stmt = $pdo->query("SELECT * FROM custom_orders ORDER BY created_at DESC");
    $customOrders = $stmt->fetchAll();
} catch (PDOException $e) {
    $customOrders = [];
}
?>

<div style="padding: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
        <div>
            <h1 style="font-family: var(--font-heading); font-size: 2.5rem; color: var(--brown); margin-bottom: 5px;">Custom Requests</h1>
            <p style="color: var(--text-light);">Manage one-of-a-kind handmade requests</p>
        </div>
    </div>

    <div style="background: white; border-radius: 20px; box-shadow: var(--shadow); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #fdfdfd; border-bottom: 1px solid #eee;">
                    <th style="padding: 15px 25px; font-size: 0.8rem; color: var(--text-light); text-transform: uppercase;">Request ID</th>
                    <th style="padding: 15px 25px; font-size: 0.8rem; color: var(--text-light); text-transform: uppercase;">Customer</th>
                    <th style="padding: 15px 25px; font-size: 0.8rem; color: var(--text-light); text-transform: uppercase;">Description Preview</th>
                    <th style="padding: 15px 25px; font-size: 0.8rem; color: var(--text-light); text-transform: uppercase;">Budget</th>
                    <th style="padding: 15px 25px; font-size: 0.8rem; color: var(--text-light); text-transform: uppercase; text-align: center;">Status</th>
                    <th style="padding: 15px 25px; font-size: 0.8rem; color: var(--text-light); text-transform: uppercase;"></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($customOrders)): ?>
                    <?php foreach ($customOrders as $co): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 15px 25px; font-weight: bold; color: var(--brown);">#<?php echo $co['id']; ?></td>
                            <td style="padding: 15px 25px;">
                                <div style="font-weight: bold; color: var(--brown);"><?php echo $co['customer_name']; ?></div>
                                <div style="font-size: 0.85rem; color: var(--text-light);"><?php echo $co['customer_phone']; ?></div>
                            </td>
                            <td style="padding: 15px 25px;">
                                <div style="font-size: 0.9rem; color: var(--text); max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo $co['description']; ?></div>
                                <div style="font-size: 0.8rem; color: var(--text-light);"><?php echo date('d M, Y', strtotime($co['created_at'])); ?></div>
                            </td>
                            <td style="padding: 15px 25px;">
                                <span style="padding: 5px 15px; background: #f9f9f9; border-radius: 20px; font-size: 0.8rem; font-weight: bold; color: #666; border: 1px solid #eee;">
                                    <?php echo $co['budget'] ?: 'N/A'; ?>
                                </span>
                            </td>
                            <td style="padding: 15px 25px; text-align: center;">
                                <span style="display: inline-block; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; text-transform: uppercase;
                                    <?php 
                                    switch($co['status']) {
                                        case 'new': echo 'background: #cce5ff; color: #004085;'; break;
                                        case 'accepted': echo 'background: #d4edda; color: #155724;'; break;
                                        case 'rejected': echo 'background: #f8d7da; color: #721c24;'; break;
                                        default: echo 'background: #e2e3e5; color: #383d41;';
                                    }
                                    ?>">
                                    <?php echo $co['status']; ?>
                                </span>
                            </td>
                            <td style="padding: 15px 25px; text-align: right;">
                                <a href="view.php?id=<?php echo $co['id']; ?>&section=custom" class="btn btn-outline" style="padding: 5px 15px; font-size: 0.85rem; border-color: #eee; color: var(--text);">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="padding: 40px; text-align: center; color: var(--text-light); font-style: italic;">No custom requests yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>