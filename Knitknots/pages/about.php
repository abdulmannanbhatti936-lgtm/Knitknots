<?php
$pageTitle = 'Our Story';
require_once __DIR__ . '/layout/header.php';
?>

<!-- Brand Story -->
<section style="background-color: var(--white); padding: 80px 0;">
    <div class="container-custom">
        <div style="display: flex; gap: 60px; align-items: center;">
            <div style="flex: 1;">
                <img src="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&q=80&w=800" alt="About KnitKnots" style="width: 100%; border-radius: 20px; box-shadow: var(--shadow);">
                <div style="background-color: var(--sage); color: white; padding: 20px; border-radius: 15px; position: relative; margin-top: -50px; left: 20px; max-width: 200px; box-shadow: var(--shadow);">
                    <div style="font-size: 2.5rem; font-weight: bold; margin-bottom: 5px;">100%</div>
                    <div style="font-size: 0.8rem; font-weight: bold; text-transform: uppercase;">Handmade with Love in Islamabad</div>
                </div>
            </div>
            <div style="flex: 1;">
                <span style="color: var(--rose); font-weight: bold; text-transform: uppercase; font-size: 0.9rem; margin-bottom: 15px; display: block;">The Story Behind KnitKnots</span>
                <h1 style="font-family: var(--font-heading); font-size: 3rem; color: var(--brown); margin-bottom: 30px;">Flowers that Bloom Forever.</h1>
                <div style="font-size: 1.1rem; color: var(--text); line-height: 1.8; margin-bottom: 40px;">
                    <p style="margin-bottom: 20px;">KnitKnots Studio was born out of a passion for crafting something meaningful. Located in the heart of Islamabad, we believe that flowers don't have to wither, and characters from our favorite stories can be brought to life with a single hook and some yarn.</p>
                    <p>Everything we sell is handcrafted by local artisans. Each stitch is a labor of love, designed to bring a little warmth and "coziness" into your everyday life.</p>
                </div>
                <div style="display: flex; gap: 20px;">
                    <a href="<?php echo SITE_URL; ?>/pages/shop.php" class="btn btn-primary" style="padding: 15px 30px;">Shop Our Collection</a>
                    <a href="https://instagram.com/knitknots.studio" target="_blank" class="btn btn-outline" style="padding: 15px 30px;">
                        <i class="fab fa-instagram"></i> Follow @knitknots
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Grid -->
<section style="background-color: var(--cream); padding: 80px 0;">
    <div class="container-custom">
        <div class="section-title">
            <h2>Our Core Values</h2>
        </div>
        
        <div style="display: flex; gap: 30px;">
            <div style="flex: 1; background: white; padding: 40px; border-radius: 20px; box-shadow: var(--shadow); text-align: center;">
                <div style="width: 70px; height: 70px; background: rgba(201, 114, 122, 0.1); color: var(--rose); border-radius: 15px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 2rem;">
                    <i class="fas fa-hand-sparkles"></i>
                </div>
                <h3 style="color: var(--brown); margin-bottom: 15px;">Purity in Craft</h3>
                <p style="color: var(--text-light); font-size: 0.95rem;">No machines, just hands. Every item is unique, carrying the subtle signature of the artisan who made it.</p>
            </div>
            
            <div style="flex: 1; background: white; padding: 40px; border-radius: 20px; box-shadow: var(--shadow); text-align: center;">
                <div style="width: 70px; height: 70px; background: rgba(201, 114, 122, 0.1); color: var(--rose); border-radius: 15px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 2rem;">
                    <i class="fas fa-leaf"></i>
                </div>
                <h3 style="color: var(--brown); margin-bottom: 15px;">Eternal Beauty</h3>
                <p style="color: var(--text-light); font-size: 0.95rem;">Our crochet flowers are designed to last a lifetime. A sustainable way to gift and decorate.</p>
            </div>

            <div style="flex: 1; background: white; padding: 40px; border-radius: 20px; box-shadow: var(--shadow); text-align: center;">
                <div style="width: 70px; height: 70px; background: rgba(201, 114, 122, 0.1); color: var(--rose); border-radius: 15px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 2rem;">
                    <i class="fas fa-user-edit"></i>
                </div>
                <h3 style="color: var(--brown); margin-bottom: 15px;">Customization</h3>
                <p style="color: var(--text-light); font-size: 0.95rem;">We empower you to be a part of the design process. Your imagination, our execution.</p>
            </div>

            <div style="flex: 1; background: white; padding: 40px; border-radius: 20px; box-shadow: var(--shadow); text-align: center;">
                <div style="width: 70px; height: 70px; background: rgba(201, 114, 122, 0.1); color: var(--rose); border-radius: 15px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 2rem;">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h3 style="color: var(--brown); margin-bottom: 15px;">Locally Sourced</h3>
                <p style="color: var(--text-light); font-size: 0.95rem;">Proudly Pakistani. We source our yarn and materials from local markets in Islamabad and Lahore.</p>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/layout/footer.php'; ?>