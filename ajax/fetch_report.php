<?php
require_once '../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $from = $_POST['from_date'] . ' 00:00:00';
    $to = $_POST['to_date'] . ' 23:59:59';

    if (!$from || !$to) {
        echo json_encode(['success' => false, 'message' => 'Missing date range']);
        exit;
    }

    $db = new DB();
    $conn = $db->connect();

    // 1. Total Units Sold and Revenue
    $stmt = $conn->prepare("SELECT SUM(quantity) as total_units, SUM(total_price) as total_revenue FROM sales WHERE created_at BETWEEN ? AND ?");
    $stmt->bind_param("ss", $from, $to);
    $stmt->execute();
    $stmt->bind_result($total_units, $total_revenue);
    $stmt->fetch();
    $stmt->close();

    // 2. Total Purchase Expense
    $stmt2 = $conn->prepare("SELECT SUM(total_price) as total_purchase FROM purchases WHERE created_at BETWEEN ? AND ?");
    $stmt2->bind_param("ss", $from, $to);
    $stmt2->execute();
    $stmt2->bind_result($total_purchase);
    $stmt2->fetch();
    $stmt2->close();

    // 3. Stock Value (Qty x Cost)
    $stock_value = 0;
    $stock_query = $conn->query("SELECT quantity, cost_price FROM products");
    while ($row = $stock_query->fetch_assoc()) {
        $stock_value += $row['quantity'] * $row['cost_price'];
    }

    // 4. Sales List HTML
    $sales_html = '<div style="overflow-x:auto;"><table class="table-report">
    <thead class="table-light">
      <tr>
        <th>Customer</th>
        <th>Phone</th>
        <th>Product</th>
        <th>Product Code</th>
        <th>Qty</th>
        <th>Price/unit</th>
        <th>GST Type</th>
        <th>Total</th>
        <th>Date</th>
      </tr>
    </thead><tbody>';
// Grouped sales data by customer + created_at
$sales_stmt = $conn->prepare("SELECT s.*, p.name, p.product_code FROM sales s JOIN products p ON s.product_id = p.id WHERE s.created_at BETWEEN ? AND ? ORDER BY s.created_at DESC");
$sales_stmt->bind_param("ss", $from, $to);
$sales_stmt->execute();
$result = $sales_stmt->get_result();

$grouped_sales = [];

while ($sale = $result->fetch_assoc()) {
    $key = $sale['customer_name'] . '_' . $sale['customer_phone'] . '_' . $sale['created_at'];

    if (!isset($grouped_sales[$key])) {
        $grouped_sales[$key] = [
            'customer_name' => $sale['customer_name'],
            'customer_phone' => $sale['customer_phone'],
            'created_at' => $sale['created_at'],
            'products' => []
        ];
    }

    $grouped_sales[$key]['products'][] = [
        'name' => $sale['name'],
        'product_code' => $sale['product_code'],
        'quantity' => $sale['quantity'],
        'price_per_unit' => $sale['price_per_unit'],
        'gst_type' => $sale['gst_type'],
        'total_price' => $sale['total_price']
    ];
}

$sales_html = '<div style="overflow-x:auto;"><table class="table-report">
<thead class="table-light">
  <tr>
    <th>Customer</th>
    <th>Phone</th>
    <th>Product</th>
    <th>Product Code</th>
    <th>Qty</th>
    <th>Price/unit</th>
    <th>GST Type</th>
    <th>Total</th>
    <th>Date</th>
  </tr>
</thead><tbody>';

if (!empty($grouped_sales)) {
    foreach ($grouped_sales as $group) {
        $products = array_column($group['products'], 'name');
        $codes = array_column($group['products'], 'product_code');
        $quantities = array_column($group['products'], 'quantity');
        $prices = array_map(fn($p) => '₹' . number_format($p['price_per_unit'], 2), $group['products']);
        $gst = array_column($group['products'], 'gst_type');
        $totals = array_map(fn($p) => '₹' . number_format($p['total_price'], 2), $group['products']);

        $sales_html .= '<tr>
            <td>' . htmlspecialchars($group['customer_name']) . '</td>
            <td>' . htmlspecialchars($group['customer_phone']) . '</td>
            <td>' . implode("<br>", $products) . '</td>
            <td>' . implode("<br>", $codes) . '</td>
            <td>' . implode("<br>", $quantities) . '</td>
            <td>' . implode("<br>", $prices) . '</td>
            <td>' . implode("<br>", $gst) . '</td>
            <td>' . implode("<br>", $totals) . '</td>
            <td>' . date('d-m-Y H:i', strtotime($group['created_at'])) . '</td>
        </tr>';
    }
} else {
    $sales_html .= '<tr><td colspan="9" class="text-center">No sales in selected date range</td></tr>';
}

$sales_html .= '</tbody></table></div>';


    // Final Output
    echo json_encode([
        'success' => true,
        'units_sold' => $total_units ?? 0,
        'total_revenue' => number_format($total_revenue ?? 0, 2),
        'total_purchase' => number_format($total_purchase ?? 0, 2),
        'net_profit' => number_format(($total_revenue ?? 0) - ($total_purchase ?? 0), 2),
        'stock_value' => number_format($stock_value ?? 0, 2),
        'sales_html' => $sales_html
    ]);
}
?>
