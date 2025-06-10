<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new DB();
    $conn = $db->connect();

    $id = $_POST['product_id'];
    $name = $_POST['product_name']; // ✅ Fixed
    $desc = $_POST['description'];
    $cost = $_POST['cost_price'];
    $price = $_POST['price'];
    $qty = $_POST['quantity'];

    $stmt = $conn->prepare("UPDATE products SET name=?, description=?, cost_price=?, price=?, quantity=? WHERE id=?");
    $stmt->bind_param("ssddii", $name, $desc, $cost, $price, $qty, $id);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
