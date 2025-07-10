<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Product.php';

$db = new DB();
$conn = $db->connect();

$product = new Product($conn);
$products = $product->getAllProducts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Stock Records</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #ffffff;
      color: #333;
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
    .text-navy { color: #002c6f; }
    .custom-primary {
      background-color: #002c6f !important;
      color: white !important;
      border: none;
    }
    .custom-primary:hover { background-color: #001e4f !important; }
    .table thead th {
      background-color: #002c6f !important;
      color: white;
      vertical-align: middle;
    }
    .table td { vertical-align: middle; }
    .table .btn i { margin-right: 4px; }
    h3 { font-weight: 600; }

    @media screen and (max-width: 992px) {
  .table thead {
    font-size: 14px;
  }

  .table td, .table th {
    padding: 10px;
    font-size: 13px;
    white-space: nowrap;
  }

  .btn {
    font-size: 13px;
    padding: 6px 10px;
  }

  .modal-dialog {
    max-width: 90%;
    margin: 1rem auto;
  }

  .modal-body .form-label {
    font-size: 14px;
  }

  .modal-body input,
  .modal-body textarea,
  .modal-body select {
    font-size: 14px;
  }

  h3 {
    font-size: 20px;
  }

  .dropdown-menu {
    font-size: 14px;
  }
}

@media screen and (max-width: 576px) {
  .d-flex.justify-content-between {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
  }

  .dropdown {
    width: 100%;
  }

  .dropdown-toggle {
    width: 100%;
    text-align: left;
  }

  .table-responsive {
    overflow-x: auto;
  }

  .modal-dialog {
    max-width: 95%;
  }
}

  </style>
</head>
<body class="bg-white">

<!-- Back Button -->
<div class="back-button">
  <a href="home.php">⬅</a>
</div>

<div class="container mt-5">
  
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-navy">📦 Stock Records</h3>
    <div class="dropdown">
      <button class="btn custom-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
        <i class="fa-solid fa-plus"></i> Add Product
      </button>
      <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addNewProductModal">Add New Product</a></li>
        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addToExistingProductModal">Add to Existing Product</a></li>
      </ul>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered table-hover text-center align-middle">
      <thead>
        <tr>
          <th>Sr No</th>
          <th>Product Name</th>
          <th>Description</th>
          <th>Cost Price (₹)</th>
          <th>Price (₹)</th>
          <th>Quantity</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php $i = 1; foreach ($products as $product): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><?= htmlspecialchars($product['name']) ?></td>
          <td><?= htmlspecialchars($product['description']) ?></td>
          <td><?= number_format($product['cost_price'], 2) ?></td>
          <td><?= number_format($product['price'], 2) ?></td>
          <td><?= $product['quantity'] ?></td>
          <td>
            <button 
              class="btn btn-sm btn-outline-primary me-1 edit-btn"
              data-id="<?= $product['id'] ?>"
              data-name="<?= htmlspecialchars($product['name']) ?>"
              data-description="<?= htmlspecialchars($product['description']) ?>"
              data-cost="<?= $product['cost_price'] ?>"
              data-price="<?= $product['price'] ?>"
              data-quantity="<?= $product['quantity'] ?>"
              data-bs-toggle="modal"
              data-bs-target="#editProductModal"
            >
              <i class="fa-solid fa-pen"></i> Edit
            </button>
            <button class="btn btn-sm btn-outline-danger delete-btn" data-id="<?= $product['id'] ?>">
  <i class="fa-solid fa-trash"></i> Delete
</button>

          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal: Add New Product -->
<div class="modal fade" id="addNewProductModal" tabindex="-1" aria-labelledby="addNewProductModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="POST" action="../controllers/productController.php?action=add">
      <div class="modal-header">
        <h5 class="modal-title">Add New Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Fields -->
        <div class="mb-3">
          <label class="form-label">Product Name</label>
          <input type="text" name="product_name" class="form-control" required />
        </div>
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="3" required></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Cost Price (₹)</label>
          <input type="number" name="cost_price" class="form-control" required min="0" step="0.01" />
        </div>
        <div class="mb-3">
          <label class="form-label">Price (₹)</label>
          <input type="number" name="price" class="form-control" required min="0" step="0.01" />
        </div>
        <div class="mb-3">
          <label class="form-label">Quantity</label>
          <input type="number" name="quantity" class="form-control" required min="1" />
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn custom-primary">Add Product</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal: Edit Product -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" id="editProductForm">
      <div class="modal-header">
        <h5 class="modal-title">Edit Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="product_id" id="edit_product_id" />
        <div class="mb-3">
          <label class="form-label">Product Name</label>
          <input type="text" name="product_name" id="edit_product_name" class="form-control" required />
        </div>
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea name="description" id="edit_description" class="form-control" rows="3" required></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Cost Price (₹)</label>
          <input type="number" name="cost_price" id="edit_cost_price" class="form-control" required step="0.01" />
        </div>
        <div class="mb-3">
          <label class="form-label">Price (₹)</label>
          <input type="number" name="price" id="edit_price" class="form-control" required step="0.01" />
        </div>
        <div class="mb-3">
          <label class="form-label">Quantity</label>
          <input type="number" name="quantity" id="edit_quantity" class="form-control" required />
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn custom-primary">Save Changes</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal: Add to Existing Product -->
<div class="modal fade" id="addToExistingProductModal" tabindex="-1" aria-labelledby="addToExistingProductModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="POST" action="../controllers/productController.php?action=update_quantity">
      <div class="modal-header">
        <h5 class="modal-title">Add to Existing Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Select Product</label>
          <select name="product_id" class="form-select" required>
            <option disabled selected>-- Select Product --</option>
            <?php foreach ($products as $product): ?>
              <option value="<?= $product['id'] ?>"><?= htmlspecialchars($product['name']) ?> — <?= htmlspecialchars($product['description']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Quantity to Add</label>
          <input type="number" name="quantity" class="form-control" required min="1" />
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn custom-primary">Update Stock</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Populate edit modal
  document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', () => {
      document.getElementById('edit_product_id').value = button.dataset.id;
      document.getElementById('edit_product_name').value = button.dataset.name;
      document.getElementById('edit_description').value = button.dataset.description;
      document.getElementById('edit_cost_price').value = button.dataset.cost;
      document.getElementById('edit_price').value = button.dataset.price;
      document.getElementById('edit_quantity').value = button.dataset.quantity;
    });
  });

  // AJAX form submit for editing product
  document.getElementById('editProductForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('../ajax/update_product.php', {
      method: 'POST',
      body: formData
    })
    .then(resp => resp.text())
    .then(response => {
      if (response.trim() === 'success') {
        alert("Product updated successfully!");
        location.reload();
      } else {
        alert("Failed to update product.");
      }
    });
  });
</script>
<script>
document.querySelectorAll('.delete-btn').forEach(button => {
  button.addEventListener('click', function () {
    const id = this.getAttribute('data-id');

    if (confirm('Are you sure you want to delete this product?')) {
      fetch('../ajax/delete_product.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + id
      })
      .then(resp => resp.text())
      .then(response => {
        if (response.trim() === 'success') {
          alert("Product deleted successfully!");
          location.reload();
        } else {
          alert("Failed to delete product.");
        }
      });
    }
  });
});
</script>


</body>
</html>
