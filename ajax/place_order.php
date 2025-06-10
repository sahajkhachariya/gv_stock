<?php
require_once '../config/db.php';

$db = new DB();
$conn = $db->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['customer_name'];
  $phone = $_POST['phone'];
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  $gst_type = $_POST['gst_type'];

  // 1. Get product price
  $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $product = $result->fetch_assoc();
  $price_per_unit = $product['price'];
  $stmt->close();

  // 2. Calculate prices
  $base_price = $price_per_unit * $quantity;
  $gst_percent = 18;
  $gst_amount = $base_price * 0.18;
  $total_price = $base_price + $gst_amount;

  // 3. Insert into sales table
  $stmt = $conn->prepare("INSERT INTO sales (customer_name, customer_phone, product_id, quantity, price_per_unit, gst_type, gst_percent, gst_amount, total_price, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
  $stmt->bind_param("ssiiddidd", $name, $phone, $product_id, $quantity, $price_per_unit, $gst_type, $gst_percent, $gst_amount, $total_price);
  $stmt->execute();
  $stmt->close();

  // 4. Update stock quantity
  $stmt = $conn->prepare("UPDATE products SET quantity = quantity - ? WHERE id = ?");
  $stmt->bind_param("ii", $quantity, $product_id);
  $stmt->execute();
  $stmt->close();

  // 5. Redirect
  header("Location: ../views/sales.php");
  exit;
}
?>
