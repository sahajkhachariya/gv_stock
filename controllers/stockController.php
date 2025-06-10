<?php
require_once '../config/db.php';
require_once '../models/Product.php';

$db = new DB();
$conn = $db->connect();

$product = new Product($conn); // Use $product everywhere

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['name'], $_POST['price'], $_POST['quantity'], $_POST['cost_price'])) {
        $name = trim($_POST['name']);
        $description = trim($_POST['description'] ?? '');
        $cost_price = floatval($_POST['cost_price']);
        $price = floatval($_POST['price']);
        $quantity = intval($_POST['quantity']);

        if ($name !== '' && $quantity > 0) {
            $added = $product->addNewProduct($name, $description, $cost_price, $price, $quantity);
            if ($added) {
                header("Location: ../views/manage_stock.php?msg=Product added successfully");
                exit;
            } else {
                header("Location: ../views/manage_stock.php?error=Failed to add product");
                exit;
            }
        }
    }

    if (isset($_POST['product_id'], $_POST['quantity'])) {
        $product_id = intval($_POST['product_id']);
        $quantity = intval($_POST['quantity']);

        if ($product_id > 0 && $quantity > 0) {
            $updated = $product->addStock($product_id, $quantity);
            if ($updated) {
                header("Location: ../views/manage_stock.php?msg=Stock updated successfully");
                exit;
            } else {
                header("Location: ../views/manage_stock.php?error=Failed to update stock");
                exit;
            }
        }
    }
}
?>
