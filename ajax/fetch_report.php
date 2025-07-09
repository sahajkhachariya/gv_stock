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
        <th>Qty</th>
        <th>Price/unit</th>
        <th>GST Type</th>
        <th>Total</th>
        <th>Date</th>
      </tr>
    </thead><tbody>';

    $sales_stmt = $conn->prepare("SELECT s.*, p.name FROM sales s JOIN products p ON s.product_id = p.id WHERE s.created_at BETWEEN ? AND ? ORDER BY s.created_at DESC");
    $sales_stmt->bind_param("ss", $from, $to);
    $sales_stmt->execute();
    $result = $sales_stmt->get_result();

    if ($result->num_rows > 0) {
        while ($sale = $result->fetch_assoc()) {
            $sales_html .= '<tr>
                <td>' . htmlspecialchars($sale['customer_name']) . '</td>
                <td>' . htmlspecialchars($sale['customer_phone']) . '</td>
                <td>' . htmlspecialchars($sale['name']) . '</td>
                <td>' . $sale['quantity'] . '</td>
                <td>₹' . number_format($sale['price_per_unit'], 2) . '</td>
                <td>' . strtoupper($sale['gst_type']) . '</td>
                <td>₹' . number_format($sale['total_price'], 2) . '</td>
                <td>' . date('d-m-Y H:i', strtotime($sale['created_at'])) . '</td>
              </tr>';
        }
    } else {
        $sales_html .= '<tr><td colspan="8" class="text-center">No sales in selected date range</td></tr>';
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
