<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include DB and Product model
require_once(dirname(__DIR__) . '/config/db.php');
require_once(dirname(__DIR__) . '/models/Product.php');

// Establish DB connection
$db = new DB();
$conn = $db->connect();
$product = new Product($conn);

// Route logic by action
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_GET['action'] ?? '';

    // Add New Product
    if ($action === 'add') {
        $name        = $_POST['product_name'] ?? '';
        $description = $_POST['description'] ?? '';
        $cost_price  = $_POST['cost_price'] ?? 0;
        $price       = $_POST['price'] ?? 0;
        $quantity    = $_POST['quantity'] ?? 0;

        if (!empty($name) && is_numeric($cost_price) && is_numeric($price) && is_numeric($quantity)) {
            $success = $product->addNewProduct($name, $description, $cost_price, $price, $quantity);

            if ($success) {
                header("Location: ../views/manage_stocks.php");
                exit();
            } else {
                echo "❌ Failed to add product. Please check DB connection or query.";
            }
        } else {
            echo "❗ Invalid input. Please fill all fields correctly.";
        }

    // Update Quantity of Existing Product  
    } elseif ($action === 'update_quantity') {
        $product_id   = $_POST['product_id'] ?? null;
        $add_quantity = $_POST['quantity'] ?? null;

        if ($product_id && is_numeric($add_quantity)) {
            $success = $product->addStockToProduct($product_id, $add_quantity);

            if ($success) {
                header("Location: ../views/manage_stocks.php");
                exit();
            } else {
                echo "❌ Failed to update stock.";
            }
        } else {
            echo "❗ Invalid input for stock update.";
        }

    } else {
        echo "❗ Unknown action.";
    }

} else {
    echo "❗ Invalid request method.";
}
