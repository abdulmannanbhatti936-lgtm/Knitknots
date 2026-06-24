<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$pageTitle = 'Customer Reviews';
require_once __DIR__ . '/layout/header.php';
?>

<section style="background-color: var(--cream); padding-top: 50px; padding-bottom: 80px;">
    <div class="container-custom">
        <div class="section-title">
            <h2 style="font-family: var(--font-heading); font-size: 2.5rem; color: var(--brown);">What Our Clients Say</h2>
            <p style="color: var(--text-light); margin-top: 10px;">Read honest feedback from our wonderful KnitKnots family.</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px;">
            <?php 
            $reviews = [
                ['name' => 'Ayesha Khan', 'city' => 'Islamabad', 'rating' => 5, 'text' => 'Absolutely love the crochet sunflowers! They brought so much life to my living room. Fast delivery and beautiful packaging.'],
                ['name' => 'Fatima Ali', 'city' => 'Lahore', 'rating' => 5, 'text' => 'The custom amigurumi bunny I ordered for my niece was perfect. The attention to detail is incredible. Will definitely order again.'],
                ['name' => 'Hira Naveed', 'city' => 'Karachi', 'rating' => 4, 'text' => 'Ordered the tulip bouquet. Very neat work and the colors are vibrant. The only issue was a slight delay in shipping, but the product is worth it!'],
                ['name' => 'Zainab Qureshi', 'city' => 'Rawalpindi', 'rating' => 5, 'text' => 'Amazing quality keychains. I bought a set for my friends as birthday favors and everyone adored them. Highly recommended!'],
                ['name' => 'Maryam Tariq', 'city' => 'Peshawar', 'rating' => 5, 'text' => 'Such a pleasant experience. The customer service over WhatsApp was incredibly polite, and my custom colors were matched exactly as requested.'],
                ['name' => 'Sana Malik', 'city' => 'Multan', 'rating' => 5, 'text' => 'These flowers will last forever! I gifted a bouquet to my mother and she was so happy. Truly a premium feel and worth the price.']
            ];

            foreach ($reviews as $review): 
            ?>
            <div style="background: white; padding: 30px; border-radius: 20px; box-shadow: var(--shadow); transition: var(--transition);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px;">
                    <div>
                        <h3 style="color: var(--brown); font-size: 1.2rem; margin-bottom: 5px;"><?php echo $review['name']; ?></h3>
                        <span style="font-size: 0.85rem; color: var(--text-light); text-transform: uppercase;">
                            <i class="fas fa-map-marker-alt" style="color: var(--rose); margin-right: 5px;"></i><?php echo $review['city']; ?>
                        </span>
                    </div>
                    <div style="color: #FFD700; font-size: 1rem;">
                        <?php for($i=1; $i<=5; $i++): ?>
                            <i class="fas fa-star" <?php echo $i > $review['rating'] ? 'style="color: #eee;"' : ''; ?>></i>
                        <?php endfor; ?>
                    </div>
                </div>
                <p style="color: var(--text); font-size: 0.95rem; line-height: 1.6; font-style: italic;">"<?php echo $review['text']; ?>"</p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/layout/footer.php'; ?>