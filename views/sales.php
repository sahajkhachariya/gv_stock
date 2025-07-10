<?php
require_once '../config/db.php';

$db = new DB();
$conn = $db->connect();

$sales = [];
$result = $conn->query("SELECT s.*, p.name, p.description FROM sales s JOIN products p ON s.product_id = p.id ORDER BY s.created_at DESC");

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sales[] = $row;
    }
}
 
 ?>

<!-- Heading -->
<!-- Page Container -->
<div class="page-wrapper">
  <div class="sales-container">
    <div class="back-button">
  <a href="home.php">⬅</a>
</div>
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
            <th>Total</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($sales as $sale): ?>
            <tr>
              <td><?= htmlspecialchars($sale['customer_name']) ?></td>
              <td><?= htmlspecialchars($sale['customer_phone']) ?></td>
              <td><?= htmlspecialchars($sale['name']) ?></td>
              <td><?= htmlspecialchars($sale['description']) ?></td>
              <td><?= $sale['quantity'] ?></td>
              <td>₹<?= number_format($sale['price_per_unit'], 2) ?></td>
              <td><?= $sale['gst_type'] ?></td>
              <td>₹<?= number_format($sale['total_price'], 2) ?></td>
              <td><?= date('d-m-Y H:i', strtotime($sale['created_at'])) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>


<!-- Table Styling -->
<style>
 /* Table Styling - Dark Blue & White Theme */
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
  display: block; /* Ensure it stacks properly below heading */
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
