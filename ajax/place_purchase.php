<?php
require_once '../config/db.php';

$db = new DB();
$conn = $db->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supplier_name = trim($_POST['supplier_name']);
    $supplier_phone = trim($_POST['supplier_phone']);
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    $cost_price = floatval($_POST['cost_price']);
    $gst_type = $_POST['gst_type'];

    // Validate input
    if ($supplier_name === '' || $quantity <= 0 || $cost_price <= 0 || $product_id <= 0 || !in_array($gst_type, ['igst', 'cgst_sgst'])) {
        header("Location: ../views/manage_stocks.php?error=Invalid input");
        exit;
    }

    // Get product code from product_id
    $product_code = '';
    $stmtCode = $conn->prepare("SELECT product_code FROM products WHERE id = ?");
    $stmtCode->bind_param("i", $product_id);
    $stmtCode->execute();
    $stmtCode->bind_result($product_code);
    $stmtCode->fetch();
    $stmtCode->close();

    // GST Calculation
    $gst_percent = 18;
    $base_price = $cost_price * $quantity;

    if ($gst_type === 'igst') {
        $igst_amount = $base_price * ($gst_percent / 100);
        $cgst_amount = 0;
        $sgst_amount = 0;
    } elseif ($gst_type === 'cgst_sgst') {
        $igst_amount = 0;
        $cgst_amount = $base_price * ($gst_percent / 2 / 100);
        $sgst_amount = $base_price * ($gst_percent / 2 / 100);
    }

    $total_price = $base_price + $igst_amount + $cgst_amount + $sgst_amount;

    // Insert into purchases
    $stmt = $conn->prepare("INSERT INTO purchases (
        supplier_name, supplier_phone, product_id, product_code, quantity, cost_price,
        igst_amount, cgst_amount, sgst_amount, total_price, gst_type, gst_percent, created_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

    $stmt->bind_param(
        "ssissddddsds",
        $supplier_name,
        $supplier_phone,
        $product_id,
        $product_code,
        $quantity,
        $cost_price,
        $igst_amount,
        $cgst_amount,
        $sgst_amount,
        $total_price,
        $gst_type,
        $gst_percent
    );

    $stmt->execute();
    $stmt->close();

    // Update stock quantity
    $stmt2 = $conn->prepare("UPDATE products SET quantity = quantity + ? WHERE id = ?");
    $stmt2->bind_param("ii", $quantity, $product_id);
    $stmt2->execute();
    $stmt2->close();

    header("Location: ../views/manage_stocks.php?msg=Purchase added successfully");
    exit;
}
?>
