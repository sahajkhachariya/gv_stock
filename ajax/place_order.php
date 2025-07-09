<?php
require_once '../config/db.php';

$db = new DB();
$conn = $db->connect();

// Optional: Normalize old GST types
$conn->query("UPDATE sales SET gst_type = 'Inclusive (18%)' WHERE gst_type = 'inclusive'");
$conn->query("UPDATE sales SET gst_type = 'Exclusive (18%)' WHERE gst_type = 'exclusive'");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['customer_name'];
    $phone = $_POST['phone'];
    $product_ids = $_POST['product_id'];     // array
    $quantities = $_POST['quantity'];        // array

    $gst_type = ($_POST['gst_type'] === 'inclusive') ? 'Inclusive (18%)' : 'Exclusive (18%)';
    $gst_percent = 18;
    $discount_percent = isset($_POST['discount_percent']) ? floatval($_POST['discount_percent']) : 0;

    $base_prices = [];
    $total_price_after_gst = 0;

    // Step 1: Calculate base prices and total price after GST
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

        if ($gst_type === 'Inclusive (18%)') {
            $total = $base_price;
        } else {
            $total = $base_price + ($base_price * $gst_percent / 100);
        }

        $base_prices[] = [
            'product_id' => $product_id,
            'quantity' => $quantity,
            'price_per_unit' => $price_per_unit,
            'base_price' => $base_price,
            'total' => $total
        ];

        $total_price_after_gst += $total;
    }

    // Step 2: Calculate total discount amount
    $total_discount_amount = ($discount_percent > 0) ? ($total_price_after_gst * ($discount_percent / 100)) : 0;

    // Step 3: Insert each product as a separate sale entry with proportional discount
    foreach ($base_prices as $item) {
        $proportional_discount = ($total_price_after_gst > 0)
            ? ($item['total'] / $total_price_after_gst) * $total_discount_amount
            : 0;

        $final_price = $item['total'] - $proportional_discount;

        // GST amount calculation
        if ($gst_type === 'Inclusive (18%)') {
            $gst_amount = $item['base_price'] * ($gst_percent / (100 + $gst_percent));
        } else {
            $gst_amount = $item['base_price'] * ($gst_percent / 100);
        }

        // Insert into sales table
        $stmt = $conn->prepare("INSERT INTO sales (customer_name, customer_phone, product_id, quantity, price_per_unit, gst_type, gst_percent, gst_amount, total_price, discount_percent, discount_amount, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param(
            "ssiiddidddd",
            $name,
            $phone,
            $item['product_id'],
            $item['quantity'],
            $item['price_per_unit'],
            $gst_type,
            $gst_percent,
            $gst_amount,
            $final_price,
            $discount_percent,
            $proportional_discount
        );
        $stmt->execute();
        $stmt->close();

        // Update product stock
        $stmt = $conn->prepare("UPDATE products SET quantity = quantity - ? WHERE id = ?");
        $stmt->bind_param("ii", $item['quantity'], $item['product_id']);
        $stmt->execute();
        $stmt->close();
    }

    // Redirect to sales page
    header("Location: ../views/sales.php");
    exit;
}
?>
