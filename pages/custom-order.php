<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $phone = sanitize($_POST['phone']);
    $description = sanitize($_POST['description']);
    $budget = sanitize($_POST['budget']);
    $imagePath = '';

    if (empty($name) || empty($phone) || empty($description)) {
        $error = 'Please fill all required fields.';
    } else {
        // Handle Image Upload
        if (isset($_FILES['reference_image']) && $_FILES['reference_image']['error'] === 0) {
            $imagePath = uploadImage($_FILES['reference_image'], 'custom/');
        }

        try {
            $stmt = $pdo->prepare("INSERT INTO custom_orders (customer_name, customer_phone, description, reference_image, budget) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $phone, $description, $imagePath, $budget]);
            $success = true;
        } catch (PDOException $e) {
            $error = 'Something went wrong. Please try again.';
        }
    }
}

$pageTitle = 'Custom Order';
require_once __DIR__ . '/layout/header.php';
?>

<section style="background-color: var(--white); border-bottom: 1px solid #eee;">
    <div class="container-custom" style="text-align: center;">
        <h1 style="font-size: 3rem; margin-bottom: 20px;">Bring Your Ideas to Life</h1>
        <p style="color: var(--text-light); max-width: 600px; margin: 0 auto; font-size: 1.1rem;">Can't find exactly what you're looking for? Whether it's a specific keychain character or a personalized flower arrangement, we'd love to make it for you.</p>
    </div>
</section>

<!-- How It Works -->
<section style="background-color: var(--cream);">
    <div class="container-custom">
        <div class="section-title">
            <h2>How It Works</h2>
        </div>
        
        <div style="display: flex; gap: 30px; text-align: center;">
            <div style="flex: 1; background: white; padding: 40px; border-radius: 20px; box-shadow: var(--shadow);">
                <div style="width: 60px; height: 60px; background: var(--rose); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 1.5rem; font-weight: bold;">1</div>
                <h3 style="color: var(--brown); margin-bottom: 15px;">Submit Details</h3>
                <p style="color: var(--text-light);">Fill out the form below with your requirements and reference images.</p>
            </div>
            <div style="flex: 1; background: white; padding: 40px; border-radius: 20px; box-shadow: var(--shadow);">
                <div style="width: 60px; height: 60px; background: var(--rose); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 1.5rem; font-weight: bold;">2</div>
                <h3 style="color: var(--brown); margin-bottom: 15px;">Review & Quote</h3>
                <p style="color: var(--text-light);">We'll review your request and message you on WhatsApp with a price quote.</p>
            </div>
            <div style="flex: 1; background: white; padding: 40px; border-radius: 20px; box-shadow: var(--shadow);">
                <div style="width: 60px; height: 60px; background: var(--rose); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 1.5rem; font-weight: bold;">3</div>
                <h3 style="color: var(--brown); margin-bottom: 15px;">Pay & Receive</h3>
                <p style="color: var(--text-light);">Once you pay the advance, we'll start crafting and deliver to your doorstep.</p>
            </div>
        </div>
    </div>
</section>

<!-- Custom Order Form -->
<section style="background-color: var(--white); padding: 80px 0;">
    <div class="container-custom">
        <div style="max-width: 800px; margin: 0 auto;">
            <div style="background: var(--cream); border-radius: 30px; padding: 60px; box-shadow: var(--shadow);">
                <?php if ($success): ?>
                    <div style="text-align: center; padding: 40px 0;">
                        <i class="fas fa-check-circle" style="color: var(--sage); font-size: 4rem; margin-bottom: 20px;"></i>
                        <h2 style="color: var(--brown); margin-bottom: 15px;">Request Submitted!</h2>
                        <p style="color: var(--text-light); margin-bottom: 30px;">Thank you, <?php echo $name; ?>. We have received your custom order request and will contact you on WhatsApp soon.</p>
                        <a href="<?php echo SITE_URL; ?>" class="btn btn-primary">Back to Home</a>
                    </div>
                <?php else: ?>
                    <div style="text-align: center; margin-bottom: 40px;">
                        <h2 style="font-family: var(--font-heading); font-size: 2.5rem; color: var(--brown);">Custom Order Form</h2>
                        <p style="color: var(--text-light);">Let's create something special together</p>
                    </div>
                    
                    <?php if ($error): ?>
                        <div style="background: #f8d7da; color: #842029; padding: 15px; border-radius: 10px; margin-bottom: 25px;">
                            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST" enctype="multipart/form-data">
                        <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                            <div class="form-group" style="flex: 1; margin-bottom: 0;">
                                <label>Your Name *</label>
                                <input type="text" id="name" name="name" class="form-control" required placeholder="e.g. Fatima Ali">
                            </div>
                            <div class="form-group" style="flex: 1; margin-bottom: 0;">
                                <label>WhatsApp Number *</label>
                                <input type="tel" id="phone" name="phone" class="form-control" required placeholder="e.g. 03XXXXXXXXX">
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 20px;">
                            <label>What would you like us to make? *</label>
                            <textarea id="description" name="description" class="form-control" rows="4" required placeholder="Be as detailed as possible (colors, size, characters etc.)"></textarea>
                        </div>

                        <div style="display: flex; gap: 20px; margin-bottom: 30px;">
                            <div class="form-group" style="flex: 1; margin-bottom: 0;">
                                <label>Approximate Budget (PKR)</label>
                                <input type="text" id="budget" name="budget" class="form-control" placeholder="e.g. 1000-1500">
                            </div>
                            <div class="form-group" style="flex: 1; margin-bottom: 0;">
                                <label>Reference Image (Optional)</label>
                                <input type="file" id="reference_image" name="reference_image" class="form-control" accept="image/*">
                            </div>
                        </div>

                        <div style="background: rgba(201, 114, 122, 0.05); border-radius: 15px; padding: 20px; display: flex; align-items: flex-start; gap: 15px; margin-bottom: 40px;">
                            <i class="fas fa-info-circle" style="color: var(--rose); margin-top: 5px;"></i>
                            <p style="color: var(--text-light); font-size: 0.9rem; margin: 0;">
                                Note: Custom orders may take 7-14 days depending on complexity. Advance payment is required to start the crafting process.
                            </p>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 1.1rem; padding: 15px;">
                            Submit Custom Request
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/layout/footer.php'; ?>