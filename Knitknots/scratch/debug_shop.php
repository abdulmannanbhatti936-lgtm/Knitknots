<?php
require_once 'includes/db.php';
$stmt = $pdo->query("SELECT id, name, slug FROM categories");
echo "Categories:\n";
while($row = $stmt->fetch()) {
    echo "ID: " . $row['id'] . " | Name: " . $row['name'] . " | Slug: [" . $row['slug'] . "]\n";
}

$stmt = $pdo->query("SELECT id, category_id, name FROM products");
echo "\nProducts:\n";
while($row = $stmt->fetch()) {
    echo "ID: " . $row['id'] . " | CatID: " . $row['category_id'] . " | Name: " . $row['name'] . "\n";
}
