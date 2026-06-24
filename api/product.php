<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';

$action = sanitize($_GET['action'] ?? 'all');

switch ($action) {
    case 'all':
        $cat = sanitize($_GET['cat'] ?? '');
        $search = sanitize($_GET['q'] ?? '');
        $sql = "SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE 1=1";
        $params = [];
        if ($cat) {
            $sql .= " AND c.slug = ?";
            $params[] = $cat;
        }
        if ($search) {
            $sql .= " AND p.name LIKE ?";
            $params[] = "%$search%";
        }
        $sql .= " ORDER BY p.is_featured DESC, p.created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $products = $stmt->fetchAll();
        foreach ($products as &$p) {
            $p['image_url'] = getProductImageUrl($p['image']);
            $p['price_formatted'] = formatPrice($p['price']);
        }
        echo json_encode(['success' => true, 'data' => $products]);
        break;

    case 'single':
        $id = (int) ($_GET['id'] ?? 0);
        $stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch();
        if ($product) {
            $product['image_url'] = getProductImageUrl($product['image']);
            $product['price_formatted'] = formatPrice($product['price']);
            echo json_encode(['success' => true, 'data' => $product]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Product not found']);
        }
        break;

    case 'featured':
        $stmt = $pdo->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.is_featured = 1 LIMIT 6");
        $products = $stmt->fetchAll();
        foreach ($products as &$p) {
            $p['image_url'] = getProductImageUrl($p['image']);
        }
        echo json_encode(['success' => true, 'data' => $products]);
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
}