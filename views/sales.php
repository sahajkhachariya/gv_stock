<?php
require_once '../config/db.php';

$db = new DB();
$conn = $db->connect();

// Step 1: Fetch sales data
$sales = [];
$result = $conn->query("SELECT s.*, p.name, p.description, p.product_code FROM sales s JOIN products p ON s.product_id = p.id ORDER BY s.created_at DESC");


if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sales[] = $row;
    }
}

// Step 2: Group sales by customer + phone + datetime (to minute)
$salesGrouped = [];

foreach ($sales as $sale) {
    $key = $sale['customer_name'] . '|' . $sale['customer_phone'] . '|' . date('Y-m-d H:i', strtotime($sale['created_at']));

    if (!isset($salesGrouped[$key])) {
        $salesGrouped[$key] = [
            'customer_name' => $sale['customer_name'],
            'customer_phone' => $sale['customer_phone'],
            'products' => [],
            'gst_types' => [],
            'total_price' => 0,
            'total_discount' => 0,
            'created_at' => $sale['created_at']
        ];
    }

    $salesGrouped[$key]['products'][] = [
    'name' => $sale['name'],
    'description' => $sale['description'],
    'quantity' => $sale['quantity'],
    'price_per_unit' => $sale['price_per_unit'],
    'gst_type' => $sale['gst_type'],
    'product_code' => $sale['product_code']
];


    $salesGrouped[$key]['gst_types'][] = $sale['gst_type'];
    $salesGrouped[$key]['total_price'] += $sale['total_price'];
    $salesGrouped[$key]['total_discount'] += isset($sale['discount_amount']) ? $sale['discount_amount'] : 0;
}
?>

<!-- Page Container -->
<div class="page-wrapper">
  <div class="sales-container">
    <!-- Heading -->
    <h3 class="sales-heading">SALES</h3>

    <!-- Table -->
    <div class="table-responsive">
      <table class="table table-bordered table-striped table-hover">
        <thead class="table-light">
          <tr>
            <th>Customer</th>
            <th>Phone</th>
            <th>Product</th>
            <th>Description</th>
            <th>Qty</th>
            <th>Price/unit</th>
            <th>GST Type</th>
             <th>Product Code</th>
            <th>Total</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($salesGrouped as $group): ?>
            <tr>
              <td><?= htmlspecialchars($group['customer_name']) ?></td>
              <td><?= htmlspecialchars($group['customer_phone']) ?></td>

              <td>
                <?php foreach ($group['products'] as $product): ?>
                  <?= htmlspecialchars($product['name']) ?><br>
                <?php endforeach; ?>
              </td>

              <td>
                <?php foreach ($group['products'] as $product): ?>
                  <?= htmlspecialchars($product['description']) ?><br>
                <?php endforeach; ?>
              </td>

              <td>
                <?php foreach ($group['products'] as $product): ?>
                  <?= $product['quantity'] ?><br>
                <?php endforeach; ?>
              </td>
              
              <td>
                <?php foreach ($group['products'] as $product): ?>
                  ₹<?= number_format($product['price_per_unit'], 2) ?><br>
                <?php endforeach; ?>
              </td>

              <td>
                <?php foreach ($group['products'] as $product): ?>
                  <?= explode('(', $product['gst_type'])[0] ?><br>
                <?php endforeach; ?>
              </td>
<td>
  <?php foreach ($group['products'] as $product): ?>
    <?= htmlspecialchars($product['product_code']) ?><br>
  <?php endforeach; ?>
</td>
              <td>
                ₹<?= number_format($group['total_price'], 2) ?>
                <?php if ($group['total_discount'] > 0): ?>
                  <div class="text-success small">Saved ₹<?= number_format($group['total_discount'], 2) ?></div>
                <?php endif; ?>
              </td>
              


              <td><?= date('d-m-Y H:i', strtotime($group['created_at'])) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Table Styling -->
<style>
body {
  background-color: #f1f6ff;
  margin: 0;
  font-family: 'Segoe UI', sans-serif;
}

.page-wrapper {
  display: flex;
  justify-content: center;
  padding: 40px 20px;
}

.sales-container {
  width: 100%;
  max-width: 1100px;
  background-color: #ffffff;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 44, 111, 0.15);
}

.sales-heading {
  text-align: center;
  font-size: 24px;
  color: #002c6f;
  font-weight: 700;
  margin-bottom: 25px;
  letter-spacing: 0.5px;
}

.table-responsive {
  width: 100%;
  overflow-x: auto;
  display: block;
}

.table {
  width: 100%;
  margin: 0 auto;
  background-color: #ffffff;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
  table-layout: auto;
}

.table th,
.table td {
  vertical-align: middle;
  text-align: center;
  font-size: 14px;
  padding: 12px 10px;
}

.table th {
  background-color: #002c6f;
  color: #ffffff;
  font-weight: 600;
  border-bottom: 2px solid #004aad;
}

.table td {
  color: #222;
  background-color: #ffffff;
}

.table thead th {
  border-bottom: 2px solid #004aad;
}

.table-bordered td,
.table-bordered th {
  border: 1px solid #cce0ff;
}

.table-striped tbody tr:nth-of-type(odd) {
  background-color: #f5f9ff;
}

.table-hover tbody tr:hover {
  background-color: #eaf2ff;
  transition: 0.2s ease-in-out;
}

.text-success.small {
  font-size: 12px;
  margin-top: 4px;
}

@media screen and (max-width: 768px) {
  .table th,
  .table td {
    font-size: 13px;
    padding: 10px 6px;
  }

  .sales-heading {
    font-size: 20px;
  }
}

@media screen and (max-width: 576px) {
  .table-responsive {
    overflow-x: auto;
  }

  .sales-container {
    padding: 20px;
  }

  .table th,
  .table td {
    white-space: nowrap;
  }
}
</style>
