<?php
require_once '../config/db.php';

$db = new DB();
$conn = $db->connect();

// Step 1: Fetch purchase records
$purchases = [];
$where = '';
if (!empty($_GET['from']) && !empty($_GET['to'])) {
    $from = $_GET['from'];
    $to = $_GET['to'];
    $where = "WHERE DATE(pu.created_at) BETWEEN '$from' AND '$to'";
}

$sql = "SELECT pu.*, pr.name, pr.description, pr.product_code 
        FROM purchases pu 
        JOIN products pr ON pu.product_id = pr.id 
        $where 
        ORDER BY pu.created_at DESC";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $purchases[] = $row;
    }
}
$totalPurchase = 0;
$totalQty = 0;
$totalIGST = 0;
$totalSGST = 0;
$totalCGST = 0;

foreach ($purchases as $p) {
    $totalPurchase += $p['total_price'];
    $totalQty += $p['quantity'];
    $totalIGST += $p['igst_amount'];
    $totalSGST += $p['sgst_amount'];
    $totalCGST += $p['cgst_amount'];
}
?>

<!-- Page Wrapper -->
<div class="page-wrapper">
  <div class="sales-container">
    <h3 class="sales-heading">PURCHASE HISTORY</h3>
<form method="GET" class="row g-3 mb-4">
  <div class="col-md-3">
    <label for="from" class="form-label">From Date</label>
    <input type="date" id="from" name="from" class="form-control" value="<?= isset($_GET['from']) ? $_GET['from'] : '' ?>">
  </div>
  <div class="col-md-3">
    <label for="to" class="form-label">To Date</label>
    <input type="date" id="to" name="to" class="form-control" value="<?= isset($_GET['to']) ? $_GET['to'] : '' ?>">
  </div>
  <div class="col-md-2 align-self-end">
    <button type="submit" class="btn btn-primary">Filter</button>
  </div>
</form>
<div class="row mb-4">
  <div class="col-md-3">
    <div class="card border-success">
      <div class="card-body text-center">
        <h6 class="text-muted">Total Purchase</h6>
        <h5 class="text-success">₹<?= number_format($totalPurchase, 2) ?></h5>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card border-info">
      <div class="card-body text-center">
        <h6 class="text-muted">Total Quantity</h6>
        <h5 class="text-info"><?= $totalQty ?></h5>
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="card border-warning">
      <div class="card-body text-center">
        <h6 class="text-muted">IGST</h6>
        <h5 class="text-warning">₹<?= number_format($totalIGST, 2) ?></h5>
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="card border-primary">
      <div class="card-body text-center">
        <h6 class="text-muted">SGST</h6>
        <h5 class="text-primary">₹<?= number_format($totalSGST, 2) ?></h5>
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="card border-danger">
      <div class="card-body text-center">
        <h6 class="text-muted">CGST</h6>
        <h5 class="text-danger">₹<?= number_format($totalCGST, 2) ?></h5>
      </div>
    </div>
  </div>
</div>

    <div class="table-responsive">
      <table class="table table-bordered table-striped table-hover">
        <thead class="table-light">
          <tr>
            <th>Supplier</th>
            <th>Phone</th>
            <th>Product</th>
            <th>Code</th> <!-- Added -->
            <th>Description</th>
            <th>Qty</th>
            <th>Cost/Unit</th>
            <th>GST Type</th>
            <th>IGST</th>
            <th>CGST</th>
            <th>SGST</th>
            <th>Total</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($purchases as $purchase): ?>
            <tr>
              <td><?= htmlspecialchars($purchase['supplier_name']) ?></td>
              <td><?= htmlspecialchars($purchase['supplier_phone']) ?></td>
              <td><?= htmlspecialchars($purchase['name']) ?></td>
              <td><?= htmlspecialchars($purchase['product_code']) ?></td> <!-- Added -->
              <td><?= htmlspecialchars($purchase['description']) ?></td>
              <td><?= $purchase['quantity'] ?></td>
              <td>₹<?= number_format($purchase['cost_price'], 2) ?></td>
              <td><?= strtoupper($purchase['gst_type']) ?></td>
              <td>₹<?= number_format($purchase['igst_amount'], 2) ?></td>
              <td>₹<?= number_format($purchase['cgst_amount'], 2) ?></td>
              <td>₹<?= number_format($purchase['sgst_amount'], 2) ?></td>
              <td>₹<?= number_format($purchase['total_price'], 2) ?></td>
              <td><?= date("d-m-Y H:i", strtotime($purchase['created_at'])) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Styles -->
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
  max-width: 1200px;
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
}

.table-striped tbody tr:nth-of-type(odd) {
  background-color: #f5f9ff;
}

.table-hover tbody tr:hover {
  background-color: #eaf2ff;
}

@media screen and (max-width: 768px) {
  .sales-heading {
    font-size: 20px;
  }

  .table th,
  .table td {
    font-size: 13px;
    padding: 10px 6px;
  }
}
</style>
