<?php
require_once '../config/db.php';

$db = new DB();
$conn = $db->connect();

$salesGrouped = [];

$result = $conn->query("SELECT s.*, p.name AS product_name, p.description, p.product_code 
                        FROM sales s 
                        JOIN products p ON s.product_id = p.id 
                        ORDER BY s.created_at DESC");

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $groupKey = $row['customer_name'] . '_' . $row['customer_phone'] . '_' . $row['created_at'];

        if (!isset($salesGrouped[$groupKey])) {
            $salesGrouped[$groupKey] = [
                'customer_name' => $row['customer_name'],
                'customer_phone' => $row['customer_phone'],
                'products' => [],
                'gst_type' => $row['gst_type'],
                'total_price' => 0,
                'created_at' => $row['created_at']
            ];
        }

        $salesGrouped[$groupKey]['products'][] = [
            'product_name' => $row['product_name'],
            'product_code' => $row['product_code'],
            'description' => $row['description'],
            'quantity' => $row['quantity'],
            'price_per_unit' => $row['price_per_unit']
        ];

        $salesGrouped[$groupKey]['total_price'] += $row['total_price'];
    }
}
?>

<!-- HTML Table -->
<div class="page-wrapper">
  <div class="sales-container">
    <div class="back-button">
      <a href="home.php">⬅</a>
    </div>

    <h3 class="sales-heading">SALES</h3>

    <div class="table-responsive">
      <table class="table table-bordered table-striped table-hover">
        <thead class="table-light">
          <tr>
            <th>Customer</th>
            <th>Phone</th>
            <th>Product</th>
            <th>Product Code</th>
            <th>Description</th>
            <th>Qty</th>
            <th>Price/unit</th>
            <th>GST Type</th>
            <th>Total</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($salesGrouped as $entry): ?>
            <tr>
              <td><?= htmlspecialchars($entry['customer_name']) ?></td>
              <td><?= htmlspecialchars($entry['customer_phone']) ?></td>
              <td>
                <?php foreach ($entry['products'] as $p): ?>
                  <?= htmlspecialchars($p['product_name']) ?><br>
                <?php endforeach; ?>
              </td>
              <td>
                <?php foreach ($entry['products'] as $p): ?>
                  <?= htmlspecialchars($p['product_code']) ?><br>
                <?php endforeach; ?>
              </td>
              <td>
                <?php foreach ($entry['products'] as $p): ?>
                  <?= htmlspecialchars($p['description']) ?><br>
                <?php endforeach; ?>
              </td>
              <td>
                <?php foreach ($entry['products'] as $p): ?>
                  <?= $p['quantity'] ?><br>
                <?php endforeach; ?>
              </td>
              <td>
                <?php foreach ($entry['products'] as $p): ?>
                  ₹<?= number_format($p['price_per_unit'], 2) ?><br>
                <?php endforeach; ?>
              </td>
              <td><?= $entry['gst_type'] ?></td>
              <td>₹<?= number_format($entry['total_price'], 2) ?></td>
              <td><?= date('d-m-Y H:i', strtotime($entry['created_at'])) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Keep your existing styles here (unchanged) -->


<!-- Table Styling -->
<style>
body {
  background-color: #f1f6ff;
  margin: 0;
  font-family: 'Segoe UI', sans-serif;
}

.back-button {
  position: absolute;
  top: 20px;
  left: 30px;
  z-index: 10;
}

.back-button a {
  background-color: #002c6f;
  color: white;
  padding: 8px 16px;
  border-radius: 6px;
  text-decoration: none;
  font-weight: 500;
  transition: background-color 0.3s ease;
}

.back-button a:hover {
  background-color: #002c6f;
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
