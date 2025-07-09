<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}
?>

<?php
require_once '../config/db.php';
require_once '../models/Product.php';

$db = new DB();
$conn = $db->connect();

$product = new Product($conn);
$products = $product->getAllProducts();
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
    .product-code-display {
  font-size: 12px;
  color: #6c757d;
  margin-top: 4px;
}


    .menu-btn:hover { color: #0149a3; }

    .nav-link { font-size: 1rem; }
    .nav-link i { width: 20px; }

    @media (min-width: 768px) {
      .main-content { padding: 2rem; }

      @media (max-width: 992px) {
  .main-content {
    padding: 1rem;
  }

  .table th,
  .table td {
    font-size: 14px;
    padding: 10px;
  }

  .btn {
    font-size: 14px;
    padding: 8px 14px;
  }

  .modal-dialog {
    max-width: 90%;
    margin: 1rem auto;
  }

  .form-label,
  .form-control,
  .form-select {
    font-size: 14px;
  }
}

@media (max-width: 768px) {
  .row.align-items-center {
    flex-direction: column;
    gap: 1rem;
    align-items: stretch;
  }

  .hamburger {
    margin-bottom: 10px;
  }

  .col-auto,
  .col {
    width: 100%;
  }

  .table-responsive {
    overflow-x: auto;
  }

  .modal-body .form-label {
    font-size: 13px;
  }

  .modal-body input,
  .modal-body select {
    font-size: 13px;
  }
}

@media (max-width: 576px) {
  .sidebar {
    width: 200px;
  }

  .sidebar h5 {
    font-size: 16px;
  }

  .nav-link {
    font-size: 14px;
  }

  .modal-dialog {
    max-width: 95%;
  }

  #grandTotal {
    font-size: 14px;
  }
}

    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div id="sidebar" class="sidebar">
  <h5 class="mb-4 fw-bold text-white p-3">GV NUTRITION</h5>
  <ul class="nav flex-column gap-3 px-3">
    <li class="nav-item">
      <a class="nav-link text-white" href="manage_stocks.php"><i class="fa-solid fa-boxes-stacked"></i> Manage stocks</a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-white" href="report.php"><i class="fa-solid fa-chart-line"></i> sales Report</a>
    </li>
    <li class="nav-item">
  <a class="nav-link text-white" href="purchase_report.php">
    <i class="fa-solid fa-clipboard-list"></i> Purchase Report
  </a>
</li>

    <li class="nav-item">
      <a class="nav-link text-white" href="sales.php"><i class="fa-solid fa-clock-rotate-left"></i> Sales history</a>
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
  <div class="row align-items-center mb-4">
    <div class="col-auto">
      <button class="hamburger" onclick="toggleSidebar()" aria-label="Toggle Menu">
        <span></span><span></span><span></span>
      </button>
    </div>
    <div class="col">
      <input type="text" id="productSearch" class="form-control" placeholder="Search products..." list="productList">
<datalist id="productList">
  <?php foreach ($products as $p): ?>
    <option value="<?= htmlspecialchars($p['name']) ?>"></option>
  <?php endforeach; ?>
</datalist>


    </div>
    <div class="col-auto">
      <button class="btn btn-primary fw-bold px-4" data-bs-toggle="modal" data-bs-target="#placeOrderModal">Place Order</button>
    </div>
  </div>

  <!-- Product Table -->
  <div class="table-responsive">
    <table class="table table-bordered text-center">
      <thead class="table-light fw-bold">

        <tr class="product-row">
  <th>Sr No.</th>
  <th>Product Code</th>
  <th>Name</th>
  <th>Description</th>
  <th>Cost Price (₹)</th>
  <th>Price (₹)</th>
  <th>Quantity</th>
</tr>

      </thead>
      <tbody>
        <?php if (!empty($products)): ?>
         <?php foreach ($products as $index => $product): ?>
  <tr>
    <td><?= $index + 1 ?></td>
    <td><?= htmlspecialchars($product['product_code']) ?></td>
    <td><?= htmlspecialchars($product['name']) ?></td>
    <td><?= htmlspecialchars($product['description']) ?></td>
    <td><?= number_format($product['cost_price'], 2) ?></td>
    <td><?= number_format($product['price'], 2) ?></td>
    <td><?= $product['quantity'] ?></td>
  </tr>
<?php endforeach; ?>

        <?php else: ?>
          <tr><td colspan="6">No products found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
    <!-- <button type="button" class="btn btn-sm btn-outline-primary" id="addProductRow">+ Add Product</button> -->
  </div>
</div>

<!-- Place Order Modal -->
<div class="modal fade" id="placeOrderModal" tabindex="-1" aria-labelledby="placeOrderModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="../ajax/place_order.php" class="modal-content">
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

     <div id="productGroupContainer">
  <div class="product-group row mb-2">
    <div class="col-6">
      <select name="product_id[]" class="form-select product-select" required>
        <option value="">-- Select Product --</option>
        <?php foreach ($products as $prod): ?>
          <option 
            value="<?= $prod['id'] ?>" 
            data-price="<?= $prod['price'] ?>" 
            data-code="<?= htmlspecialchars($prod['product_code']) ?>"
          >
            <?= htmlspecialchars($prod['name']) ?> - <?= htmlspecialchars($prod['description']) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <small class="text-muted product-code-display"></small>
    </div>
    <div class="col-3">
      <input type="number" name="quantity[]" class="form-control product-qty" min="1" value="1" required>
    </div>
    <div class="col-3">
      <button type="button" class="btn btn-danger remove-product">Remove</button>
    </div>
  </div>
</div>

<button type="button" class="btn btn-secondary mb-3" id="addProductBtn">+ Add Another Product</button>

        <!-- <div class="mb-3">
          <label for="quantity" class="form-label">Quantity</label>
          <input type="number" name="quantity" class="form-control" min="1" required>
        </div> -->

        <div class="mb-3">
          <label for="gst_type" class="form-label">GST Type</label>
          <select name="gst_type" class="form-select" id="overallGstType" required>

            <option value="">-- Select GST Type --</option>
            <option value="inclusive">Inclusive (18%)</option>
            <option value="exclusive">Exclusive (18%)</option>
          </select>
        </div>
  <div class="mb-3">
  <label for="discount_percent" class="form-label">Discount (%)</label>
<input type="number" name="discount_percent" id="discountInput" class="form-control" min="0" step="0.01" placeholder="e.g. 10 for 10%" />

</div>


  <!-- Updated Dynamic Total -->
  <div id="grandTotal" class="alert alert-info text-center fw-bold">Grand Total: ₹0.00</div>

  <!-- Hidden Fields -->
  <input type="hidden" name="gst_amount" id="gstAmount">
  <input type="hidden" name="total_price" id="totalPrice">
  <input type="hidden" name="discount_amount" id="discountAmount">
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
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const productSelect = document.getElementById('productSelect');
    const quantityInput = document.querySelector('input[name="quantity"]');
    const gstSelect = document.querySelector('select[name="gst_type"]');
    const grandTotal = document.getElementById('grandTotal');
    const gstAmountInput = document.getElementById('gstAmount');
    const totalPriceInput = document.getElementById('totalPrice');

    const products = <?= json_encode($products) ?>;

    function calculateTotal() {
      const selectedId = productSelect.value;
      const quantity = parseInt(quantityInput.value || 0);
      const gstType = gstSelect.value;

      const selectedProduct = products.find(p => p.id == selectedId);
      if (!selectedProduct || quantity <= 0 || !gstType) {
        grandTotal.innerText = "Grand Total: ₹0.00";
        gstAmountInput.value = "";
        totalPriceInput.value = "";
        return;
      }

      const pricePerUnit = parseFloat(selectedProduct.price);
      const basePrice = pricePerUnit * quantity;
      const gstRate = 0.18;

      let gstAmount = 0;
      let finalTotal = 0;

      if (gstType === "inclusive") {
        gstAmount = basePrice * (gstRate / (1 + gstRate));
        finalTotal = basePrice;
      } else if (gstType === "exclusive") {
        gstAmount = basePrice * gstRate;
        finalTotal = basePrice + gstAmount;
      }

      grandTotal.innerText = `Grand Total: ₹${finalTotal.toFixed(2)} (GST: ₹${gstAmount.toFixed(2)})`;
      gstAmountInput.value = gstAmount.toFixed(2);
      totalPriceInput.value = finalTotal.toFixed(2);
    }

    productSelect.addEventListener('change', calculateTotal);
    quantityInput.addEventListener('input', calculateTotal);
    gstSelect.addEventListener('change', calculateTotal);
  });
</script>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("productSearch");
    const suggestionsBox = document.getElementById("searchSuggestions");

    const products = <?= json_encode(array_column($products, 'name')) ?>;

    searchInput.addEventListener("input", function () {
      const query = this.value.toLowerCase();
      suggestionsBox.innerHTML = "";

      if (query.length === 0) return;

      const matches = products.filter(name => name.toLowerCase().includes(query)).slice(0, 5);

      if (matches.length === 0) {
        suggestionsBox.innerHTML = "<div class='list-group-item'>No match found</div>";
      } else {
        matches.forEach(name => {
          const item = document.createElement("div");
          item.className = "list-group-item list-group-item-action";
          item.textContent = name;
          item.addEventListener("click", () => {
            searchInput.value = name;
            suggestionsBox.innerHTML = "";
            filterTableByName(name); // Optional: also filter table directly
          });
          suggestionsBox.appendChild(item);
        });
      }
    });

    document.addEventListener("click", function (e) {
      if (!searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
        suggestionsBox.innerHTML = "";
      }
    });

    function filterTableByName(name) {
      const rows = document.querySelectorAll(".product-row");
      rows.forEach(row => {
        const rowName = row.children[1].textContent.toLowerCase();
        row.style.display = rowName.includes(name.toLowerCase()) ? "" : "none";
      });
    }
  });
</script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const products = <?= json_encode($products) ?>;
    const productGroupContainer = document.getElementById("productGroupContainer");
    const addItemBtn = document.getElementById("addProductBtn");
    const gstSelect = document.getElementById("overallGstType");
    const grandTotalDiv = document.getElementById("grandTotal");
    const gstAmountInput = document.getElementById("gstAmount");
    const totalPriceInput = document.getElementById("totalPrice");
    const discountInput = document.querySelector('input[name="discount_percent"]');


   addItemBtn.addEventListener("click", () => {
  const newGroup = document.createElement("div");
  newGroup.className = "product-group row mb-2";

  newGroup.innerHTML = `
    <div class="col-6">
      <select name="product_id[]" class="form-select product-select" required>
        <option value="">-- Select Product --</option>
        ${products.map(p => `
          <option value="${p.id}" data-price="${p.price}" data-code="${p.product_code}">
            ${p.name} - ${p.description}
          </option>`).join('')}
      </select>
      <small class="text-muted product-code-display"></small>
    </div>
    <div class="col-3">
      <input type="number" name="quantity[]" class="form-control product-qty" min="1" value="1" required>
    </div>
    <div class="col-3">
      <button type="button" class="btn btn-danger remove-product">Remove</button>
    </div>
  `;
  productGroupContainer.appendChild(newGroup);
  calculateTotal();
});


    productGroupContainer.addEventListener("click", function (e) {
      if (e.target.classList.contains("remove-product")) {
        e.target.closest(".product-group").remove();
        calculateTotal();
      }
    });
productGroupContainer.addEventListener("change", function (e) {
  if (e.target.classList.contains("product-select")) {
    const selectedOption = e.target.selectedOptions[0];
    const code = selectedOption.dataset.code || '';
    const codeDisplay = e.target.closest('.col-6').querySelector('.product-code-display');
    codeDisplay.textContent = code ? `Code: ${code}` : '';
  }
});

    productGroupContainer.addEventListener("input", calculateTotal);
    productGroupContainer.addEventListener("change", calculateTotal);
    gstSelect.addEventListener("change", calculateTotal);
    discountInput?.addEventListener("input", calculateTotal);


   function calculateTotal() {
  const gstRate = 0.18;
  const gstType = gstSelect.value;
  const discountPercent = parseFloat(discountInput?.value || 0);

  let subtotal = 0;

  productGroupContainer.querySelectorAll(".product-group").forEach(group => {
    const select = group.querySelector(".product-select");
    const qtyInput = group.querySelector(".product-qty");
    const quantity = parseInt(qtyInput.value || 0);
    const product = products.find(p => p.id == select.value);

    if (product && quantity > 0) {
      subtotal += parseFloat(product.price) * quantity;
    }
  });

  let gstAmount = 0;
  let totalBeforeDiscount = 0;

  if (gstType === "inclusive") {
    gstAmount = subtotal * (gstRate / (1 + gstRate));
    totalBeforeDiscount = subtotal;
  } else if (gstType === "exclusive") {
    gstAmount = subtotal * gstRate;
    totalBeforeDiscount = subtotal + gstAmount;
  }

  let discountAmount = 0;
  if (!isNaN(discountPercent) && discountPercent > 0) {
    discountAmount = (totalBeforeDiscount * discountPercent) / 100;
  }

  let grandTotal = totalBeforeDiscount - discountAmount;

  // Prevent negative total
  grandTotal = Math.max(grandTotal, 0);

  grandTotalDiv.innerText = `Grand Total: ₹${grandTotal.toFixed(2)} (GST: ₹${gstAmount.toFixed(2)}, Discount: ₹${discountAmount.toFixed(2)})`;
  gstAmountInput.value = gstAmount.toFixed(2);
  totalPriceInput.value = grandTotal.toFixed(2);
  document.getElementById('discountAmount').value = discountAmount.toFixed(2);
}

    // Trigger initial calculation on page load
    calculateTotal();
  });
</script>


</body>
</html>
