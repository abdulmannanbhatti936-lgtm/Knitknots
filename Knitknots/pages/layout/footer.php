<footer>
    <div class="container">
        <div class="footer-grid">
            <div class="footer-about">
                <div class="footer-logo">Knit<span>Knots</span></div>
                <p>Spreading joy with handmade crochet creations. Based in Islamabad, our flowers are made to last forever, and our characters are crafted with love.</p>
                <div class="social-links">
                    <a href="https://instagram.com/knitknots.studio" target="_blank"><i class="fab fa-instagram"></i></a>
                    <a href="https://wa.me/<?php echo WHATSAPP_NUMBER; ?>" target="_blank"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
            
            <div class="footer-links">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="<?php echo SITE_URL; ?>">Home</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/pages/shop.php">Shop Now</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/pages/custom-order.php">Custom Order</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/pages/about.php">Our Story</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/pages/reviews.php">Reviews</a></li>
                </ul>
            </div>
            
            <div class="footer-links">
                <h4>Support</h4>
                <ul>
                    <li><a href="#">Shipping Policy</a></li>
                    <li><a href="#">Payment Methods</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/admin/login.php">Admin Login</a></li>
                </ul>
            </div>
            
            <div class="footer-links">
                <h4>Newsletter</h4>
                <p style="margin-bottom: 15px; font-size: 0.9rem;">Join the KnitKnots family for updates on new drops!</p>
                <form action="#" style="display: flex; gap: 10px;">
                    <input type="email" placeholder="Your email" class="form-control" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2); color: white;">
                    <button class="btn btn-primary" style="padding: 10px 15px;"><i class="fas fa-paper-plane"></i></button>
                </form>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> KnitKnots Studio. Made with <i class="fas fa-heart" style="color: var(--rose);"></i> in Islamabad, Pakistan.</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>
</body>
</html>