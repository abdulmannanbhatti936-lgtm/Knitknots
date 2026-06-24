<div class="admin-sidebar">
    <div class="sidebar-header">
        Knit<span>Knots</span>
    </div>
    <ul class="sidebar-menu">
        <?php $currentSection = isset($_GET['section']) ? $_GET['section'] : ''; ?>
        
        <li class="<?php echo $currentSection == '' ? 'active' : ''; ?>">
            <a href="<?php echo SITE_URL; ?>/admin/index.php"><i class="fas fa-chart-line"></i> Dashboard</a>
        </li>
        <li class="<?php echo $currentSection == 'products' ? 'active' : ''; ?>">
            <a href="<?php echo SITE_URL; ?>/admin/products/index.php?section=products"><i class="fas fa-box"></i> Products</a>
        </li>
        <li class="<?php echo $currentSection == 'orders' ? 'active' : ''; ?>">
            <a href="<?php echo SITE_URL; ?>/admin/orders/index.php?section=orders"><i class="fas fa-shopping-bag"></i> All Orders</a>
        </li>
        <li style="margin-top: 50px;">
            <a href="<?php echo SITE_URL; ?>" target="_blank"><i class="fas fa-external-link-alt"></i> View Public Site</a>
        </li>
        <li>
            <a href="<?php echo SITE_URL; ?>/admin/logout.php" style="color: #ff6b6b;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </li>
    </ul>
</div>
