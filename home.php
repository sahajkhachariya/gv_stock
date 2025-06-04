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
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-o+AScXWr0x78EMuTIq+o+x+dXw37LjDrHjx8A3H3w1Dbhv4Ll7y29fZrO7zU8KP8A9KkKhxyMbzY/3D2AS+fFw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  

  <style>
    body {
      margin: 0;
      padding: 0;
    }

    .bg-dark-blue {
      background-color: #002c6f;
    }

    .sidebar {
  width: 240px;
  height: 100vh;
  position: fixed;
  top: 0;
  left: -240px;
  z-index: 1050; /* higher than overlay or content */
  background-color: #002c6f;
  transition: left 0.3s ease;
  overflow-y: auto;
}

    .sidebar.active {
      left: 0;
    }

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

    .overlay.active {
      display: block;
    }

    .main-content {
      margin-left: 0;
      transition: margin-left 0.3s ease;
      padding: 1rem;
    }

    .table-row-alt {
      background-color: #f1f1f1;
    }

  .menu-btn {
  background: nonem ;
  border: none;
  padding: 8px;
  font-size: 1.6rem;
  color: #002c6f;
}

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

.menu-btn:hover {
  color: #0149a3;
}


    .nav-link {
      font-size: 1rem;
    }

    .nav-link i {
      width: 20px;
    }

    @media (min-width: 768px) {
      .main-content {
        padding: 2rem;
      }
    }
  </style>
</head>
<body>

  <!-- Sidebar Drawer -->
  <div id="sidebar" class="sidebar">
    <h5 class="mb-4 fw-bold">Stock Manager</h5>
    <ul class="nav flex-column gap-3">
      <li class="nav-item">
        <a class="nav-link text-white" href="#">
          <i class="fa-solid fa-boxes-stacked"></i> Manage stocks
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="#">
          <i class="fa-solid fa-chart-line"></i> Report
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="#">
          <i class="fa-solid fa-clock-rotate-left "></i> Sales history
        </a>
      </li>
      
    </ul>
    <li class="nav-item mt-3">
  <a class="nav-link text-white" href="logout.php">
    <i class="fa-solid fa-sign-out-alt"></i> Logout
  </a>
</li>

  </div>

  <!-- Overlay -->
  <div id="overlay" class="overlay" onclick="toggleSidebar()"></div>

  <!-- Main Content -->
  <div class="main-content">

    <!-- Top Bar -->
    <div class="row align-items-center mb-4">
      <div class="col-auto">
        <!-- Hamburger Button -->
     <!-- Hamburger Button -->
<button class="hamburger" onclick="toggleSidebar()" aria-label="Toggle Menu">
  <span></span>
  <span></span>
  <span></span>
</button>


      </div>
      <div class="col">
        <!-- Search Field -->
        <input type="text" class="form-control" placeholder="Search"/>
      </div>
      <div class="col-auto">
        <!-- Aligned Place Order Button -->
        <button class="btn btn-primary fw-bold px-4">Place Order</button>
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

  <!-- Script for Sidebar -->
  <script>
    function toggleSidebar() {
      document.getElementById('sidebar').classList.toggle('active');
      document.getElementById('overlay').classList.toggle('active');
    }
  </script>

</body>
</html>
