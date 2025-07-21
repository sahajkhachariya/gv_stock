<?php
require_once '../config/db.php';

$db = new DB();
$conn = $db->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['customer_name'];
    $phone = $_POST['phone'];
    $product_ids = $_POST['product_id'];
    $quantities = $_POST['quantity'];

    $is_inclusive = ($_POST['gst_type'] === 'inclusive');
    $gst_type = $is_inclusive ? 'Inclusive (18%)' : 'Exclusive (18%)';
    $gst_percent = 18;
    $gst_percent_for_db = $is_inclusive ? 0 : $gst_percent;
    $discount_percent = isset($_POST['discount_percent']) ? floatval($_POST['discount_percent']) : 0;

    $base_prices = [];
    $total_price_after_gst = 0;

    for ($i = 0; $i < count($product_ids); $i++) {
        $product_id = $product_ids[$i];
        $quantity = $quantities[$i];

        $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $stmt->close();

        $price_per_unit = $product['price'];
        $base_price = $price_per_unit * $quantity;

        if ($is_inclusive) {
            $total = $base_price;
            $gst_amount = ($base_price * $gst_percent) / (100 + $gst_percent);
        } else {
            $gst_amount = $base_price * ($gst_percent / 100);
            $total = $base_price + $gst_amount;
        }

        $base_prices[] = [
            'product_id' => $product_id,
            'quantity' => $quantity,
            'price_per_unit' => $price_per_unit,
            'base_price' => $base_price,
            'total' => $total,
            'gst_amount' => $gst_amount,
            'gst_percent' => $gst_percent_for_db
        ];

        $total_price_after_gst += $total;
    }

    $total_discount_amount = ($discount_percent > 0) ? ($total_price_after_gst * ($discount_percent / 100)) : 0;

    foreach ($base_prices as $item) {
        $proportional_discount = ($total_price_after_gst > 0)
            ? ($item['total'] / $total_price_after_gst) * $total_discount_amount
            : 0;

        $final_price = $item['total'] - $proportional_discount;

        $stmt = $conn->prepare("INSERT INTO sales (customer_name, customer_phone, product_id, quantity, price_per_unit, gst_type, gst_percent, gst_amount, total_price, discount_percent, discount_amount, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param(
            "ssiidsidddd",
            $name,
            $phone,
            $item['product_id'],
            $item['quantity'],
            $item['price_per_unit'],
            $gst_type,
            $item['gst_percent'],
            $item['gst_amount'],
            $final_price,
            $discount_percent,
            $proportional_discount
        );
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("UPDATE products SET quantity = quantity - ? WHERE id = ?");
        $stmt->bind_param("ii", $item['quantity'], $item['product_id']);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: ../views/sales.php");
    exit;
}
?>
