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
<h3 class="text-center mt-4 mb-4 fw-bold">SALES</h3>

<!-- Sales Table -->
<div class="container">
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

<!-- Table Styling -->
<style>
 /* Table Styling - Dark Blue & White Theme */
.table {
  margin-top: 20px;
  background-color: #ffffff;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0, 44, 111, 0.15); /* subtle dark blue shadow */
}

.table th,
.table td {
  vertical-align: middle;
  text-align: center;
  font-size: 14px;
  padding: 12px 10px;
}

.table th {
  background-color: #002c6f; /* Dark Blue */
  color: #ffffff; /* White Text */
  font-weight: 600;
  border-bottom: 2px solid #004aad;
}

.table td {
  color: #222; /* Dark Text */
  background-color: #ffffff;
}

.table thead th {
  border-bottom: 2px solid #004aad; /* Border under header */
}

.table-bordered td,
.table-bordered th {
  border: 1px solid #cce0ff; /* Light blue border */
}

.table-striped tbody tr:nth-of-type(odd) {
  background-color: #f5f9ff; /* Very light blue for alternating rows */
}

.table-hover tbody tr:hover {
  background-color: #eaf2ff; /* Light blue on hover */
  transition: 0.2s ease-in-out;
}

/* Optional: Center the heading */
h3.text-center {
  color: #002c6f; /* Match dark blue heading */
  font-weight: bold;
  letter-spacing: 0.5px;
}

</style>
