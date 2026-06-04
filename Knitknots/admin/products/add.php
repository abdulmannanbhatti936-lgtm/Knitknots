<?php
require_once __DIR__ . '/../includes/header.php';

$error = '';
$success = '';

// Fetch categories for the dropdown
try {
    $categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
} catch (PDOException $e) {
    $categories = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $category_id = (int)$_POST['category_id'];
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $description = sanitize($_POST['description']);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $imagePath = '';

    if (empty($name) || empty($category_id) || empty($price)) {
        $error = 'Please fill all required fields.';
    } else {
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $imagePath = uploadImage($_FILES['image'], 'products/');
        }

        try {
            $stmt = $pdo->prepare("INSERT INTO products (name, category_id, price, stock, description, image, is_featured) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $category_id, $price, $stock, $description, $imagePath, $is_featured]);
            $success = 'Product added successfully!';
            
            // Redirect after 2 seconds
            header("Refresh:2; url=index.php?section=products");
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}
?>

<div style="padding: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
        <div>
            <h1 style="font-family: var(--font-heading); font-size: 2.5rem; color: var(--brown); margin-bottom: 5px;">Add New Product</h1>
            <p style="color: var(--text-light);">Create a new entry in your collection</p>
        </div>
        <a href="index.php?section=products" class="btn btn-outline" style="padding: 10px 20px;">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div style="background: white; border-radius: 20px; box-shadow: var(--shadow); padding: 40px; max-width: 800px;">
        <?php if ($error): ?>
            <div style="background: #f8d7da; color: #842029; padding: 15px; border-radius: 10px; margin-bottom: 25px;">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div style="background: #d1e7dd; color: #0f5132; padding: 15px; border-radius: 10px; margin-bottom: 25px;">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                <div class="form-group" style="flex: 2; margin-bottom: 0;">
                    <label>Product Name *</label>
                    <input type="text" id="name" name="name" class="form-control" required placeholder="e.g. Handmade Sunflower Pot">
                </div>
                <div class="form-group" style="flex: 1; margin-bottom: 0;">
                    <label>Category *</label>
                    <select id="category_id" name="category_id" class="form-control" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div style="display: flex; gap: 20px; margin-bottom: 20px; align-items: flex-end;">
                <div class="form-group" style="flex: 1; margin-bottom: 0;">
                    <label>Price (PKR) *</label>
                    <input type="number" step="0.01" id="price" name="price" class="form-control" required placeholder="0.00">
                </div>
                <div class="form-group" style="flex: 1; margin-bottom: 0;">
                    <label>Initial Stock</label>
                    <input type="number" id="stock" name="stock" class="form-control" value="0">
                </div>
                <div class="form-group" style="flex: 1; margin-bottom: 0;">
                    <div style="display: flex; align-items: center; gap: 10px; padding: 10px 0;">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1" style="width: 20px; height: 20px; cursor: pointer;">
                        <label for="is_featured" style="cursor: pointer; margin-bottom: 0; font-weight: bold; color: var(--brown);">Featured Product</label>
                    </div>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label>Description</label>
                <textarea id="description" name="description" class="form-control" rows="5" placeholder="Tell the story of this creation..."></textarea>
            </div>

            <div class="form-group" style="margin-bottom: 30px;">
                <label>Product Image</label>
                <div style="border: 2px dashed #eee; border-radius: 15px; padding: 30px; text-align: center; background: #fdfdfd;">
                    <i class="fas fa-cloud-upload-alt" style="font-size: 2.5rem; color: #ccc; margin-bottom: 15px;"></i>
                    <input type="file" id="image" name="image" class="form-control" style="width: auto; margin: 0 auto; border: none; padding: 0; background: transparent;" accept="image/*">
                    <p style="color: var(--text-light); font-size: 0.8rem; margin-top: 10px;">Recommended: Square format (1:1), Max 5MB. WEBP/JPG/PNG supported.</p>
                </div>
            </div>

            <div style="display: flex; gap: 15px;">
                <button type="submit" class="btn btn-primary" style="padding: 12px 30px; font-size: 1.1rem;">
                    Save Product
                </button>
                <a href="index.php?section=products" class="btn btn-outline" style="padding: 12px 30px; border-color: #eee; color: var(--text);">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>