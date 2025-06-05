<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Inventory Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

  <style>
    body { margin: 0; padding: 0; }
    .bg-dark-blue { background-color: #002c6f; }

    .sidebar {
      width: 240px;
      height: 100vh;
      position: fixed;
      top: 0;
      left: -240px;
      z-index: 1050;
      background-color: #002c6f;
      transition: left 0.3s ease;
      overflow-y: auto;
    }

    .sidebar.active { left: 0; }

    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background-color: rgba(0, 0, 0, 0.4);
      display: none;
      z-index:1040;
    }

    .overlay.active { display: block; }

    .main-content {
      margin-left: 0;
      transition: margin-left 0.3s ease;
      padding: 1rem;
    }

    .table-row-alt { background-color: #f1f1f1; }

    .hamburger {
      width: 30px;
      height: 22px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      background: none;
      border: none;
      padding: 0;
      cursor: pointer;
    }

    .hamburger span {
      display: block;
      height: 4px;
      background-color: #002c6f;
      border-radius: 2px;
    }

    .menu-btn:hover { color: #0149a3; }

    .nav-link { font-size: 1rem; }
    .nav-link i { width: 20px; }

    @media (min-width: 768px) {
      .main-content { padding: 2rem; }
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div id="sidebar" class="sidebar">
    <h5 class="mb-4 fw-bold text-white p-3">Stock Manager</h5>
    <ul class="nav flex-column gap-3 px-3">
      <li class="nav-item">
        <a class="nav-link text-white" href="#"><i class="fa-solid fa-boxes-stacked"></i> Manage stocks</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="#"><i class="fa-solid fa-chart-line"></i> Report</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="#"><i class="fa-solid fa-clock-rotate-left"></i> Sales history</a>
      </li>
      <li class="nav-item mt-3">
        <a class="nav-link text-white" href="logout.php"><i class="fa-solid fa-sign-out-alt"></i> Logout</a>
      </li>
    </ul>
  </div>

  <!-- Overlay -->
  <div id="overlay" class="overlay" onclick="toggleSidebar()"></div>

  <!-- Main Content -->
  <div class="main-content">

    <!-- Top Bar -->
    <div class="row align-items-center mb-4">
      <div class="col-auto">
        <button class="hamburger" onclick="toggleSidebar()" aria-label="Toggle Menu">
          <span></span><span></span><span></span>
        </button>
      </div>
      <div class="col">
        <input type="text" class="form-control" placeholder="Search"/>
      </div>
      <div class="col-auto">
        <!-- Place Order Button -->
        <button class="btn btn-primary fw-bold px-4" data-bs-toggle="modal" data-bs-target="#placeOrderModal">Place Order</button>
      </div>
    </div>

    <!-- Product Table -->
    <div class="table-responsive">
      <table class="table table-bordered text-center">
        <thead class="table-light fw-bold">
          <tr>
            <th>Sr No.</th>
            <th>Name</th>
            <th>Quantity</th>
          </tr>
        </thead>
        <tbody>
          <tr class="table-row-alt"><td>1</td><td>Apple</td><td>50</td></tr>
          <tr><td>2</td><td>Banana</td><td>100</td></tr>
          <tr class="table-row-alt"><td>3</td><td>Orange</td><td>75</td></tr>
          <tr><td>4</td><td>Milk</td><td>20</td></tr>
          <tr class="table-row-alt"><td>5</td><td>Bread</td><td>40</td></tr>
          <tr><td>6</td><td>Eggs</td><td>60</td></tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Place Order Modal -->
  <div class="modal fade" id="placeOrderModal" tabindex="-1" aria-labelledby="placeOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form method="POST" action="place_order.php" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="placeOrderModalLabel">Place Order</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <div class="mb-3">
            <label for="customer_name" class="form-label">Customer Name</label>
            <input type="text" name="customer_name" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="text" name="phone" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="product_id" class="form-label">Select Product</label>
            <select name="product_id" class="form-select" required>
              <option value="1">Apple</option>
              <option value="2">Banana</option>
              <!-- TODO: Load dynamically from DB -->
            </select>
          </div>

          <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" name="quantity" class="form-control" min="1" required>
          </div>

          <div class="mb-3">
            <label for="gst_type" class="form-label">GST Type</label>
            <select name="gst_type" class="form-select" required>
              <option value="IGST">IGST</option>
              <option value="SGST/CGST">SGST/CGST</option>
            </select>
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Place Order</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Scripts -->
  <script>
    function toggleSidebar() {
      document.getElementById('sidebar').classList.toggle('active');
      document.getElementById('overlay').classList.toggle('active');
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
