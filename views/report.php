<style>
<<<<<<< HEAD
  @media screen and (max-width: 768px) {
    .container {
      padding: 20px;
    }

    .row {
      flex-direction: column;
      align-items: flex-start;
    }

    .row .col-auto {
      width: 100%;
      margin-bottom: 10px;
    }

    .row .col-auto label,
    .row .col-auto input,
    .row .col-auto button {
      width: 100%;
    }

    #reportSummary p {
      font-size: 14px;
    }

    #salesList table {
      font-size: 13px;
    }

    #salesList th, #salesList td {
      padding: 6px;
    }

    .back-button {
      left: 15px;
      top: 15px;
    }
  }

  @media screen and (max-width: 480px) {
    h3 {
      font-size: 18px;
    }

    #reportSummary p {
      font-size: 13px;
    }

    .btn {
      font-size: 13px;
      padding: 6px 12px;
    }
  }

  body {
    background-color: #f5f9ff;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #333;
    padding: 60px 20px 20px;
    margin: 0;
    display: flex;
    justify-content: center;
    position: relative;
  }

  .container {
    background-color: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 123, 255, 0.2);
    max-width: 800px;
    width: 100%;
    text-align: center;
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

  h3 {
    color: #004aad;
    font-weight: bold;
  }

  .row {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    margin-bottom: 20px;
  }

  .row .col-auto {
    margin: 5px 10px;
  }

  label {
    font-weight: 500;
    color: #004aad;
  }

  input[type="date"] {
    padding: 8px 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
  }

  button.btn-primary {
    background-color: #004aad;
    border-color: #004aad;
    color: #fff;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  button.btn-primary:hover {
    background-color: #003a8c;
  }

  #reportSummary {
    text-align: left;
    margin: 20px auto;
    background-color: #f1f6ff;
    border: 1px solid #d0e4ff;
    padding: 20px;
    border-radius: 8px;
  }

  #reportSummary h5 {
    color: #004aad;
    font-weight: bold;
    margin-bottom: 15px;
  }

  #reportSummary p {
    font-size: 15px;
    margin: 8px 0;
  }

  #salesList {
    margin-top: 20px;
    text-align: left;
  }

  #salesList table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
  }

  #salesList th, #salesList td {
    border: 1px solid #cce0ff;
    padding: 8px;
    text-align: center;
    font-size: 14px;
  }

  #salesList th {
    background-color: #004aad;
    color: white;
  }

  #salesList tr:nth-child(even) {
    background-color: #f9f9f9;
  }
=======
body {
  background-color: #f5f9ff;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  color: #333;
  padding: 20px;
  margin: 0;
  display: flex;
  justify-content: center;
}

.container {
  background-color: #fff;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0, 123, 255, 0.2);
  max-width: 1000px;
  width: 100%;
  text-align: center;
}

h3 {
  color: #004aad;
  font-weight: bold;
  margin-bottom: 20px;
}

.row {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 15px;
}

.col-auto {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}

label {
  font-weight: 500;
  color: #004aad;
  margin-bottom: 5px;
}

input[type="date"], button.btn-primary {
  padding: 8px 12px;
  font-size: 14px;
  border-radius: 6px;
  border: 1px solid #ccc;
}

button.btn-primary {
  background-color: #004aad;
  color: #fff;
  border: none;
  transition: background-color 0.3s ease;
}

button.btn-primary:hover {
  background-color: #003a8c;
}

#reportSummary {
  text-align: left;
  background-color: #f1f6ff;
  border: 1px solid #d0e4ff;
  padding: 20px;
  border-radius: 8px;
  margin-top: 30px;
}

#reportSummary h5 {
  color: #004aad;
  font-weight: bold;
  margin-bottom: 15px;
}

#reportSummary p {
  font-size: 15px;
  margin: 6px 0;
}

#salesList {
  margin-top: 30px;
  text-align: left;
  overflow-x: auto;
}

.table-report {
  width: 100%;
  border-collapse: collapse;
  font-size: 15px;
  min-width: 800px; /* Ensures horizontal scroll if too tight */
}

.table-report thead {
  background-color: #004aad;
  color: white;
}

.table-report th,
.table-report td {
  padding: 10px 14px;
  text-align: center;
  border: 1px solid #cce0ff;
  white-space: nowrap;
}

.table-report tbody tr:nth-child(even) {
  background-color: #f9f9f9;
}

.table-report tbody tr:hover {
  background-color: #eaf3ff;
}

/* Responsive Tweaks */
@media screen and (max-width: 768px) {
  .table-report {
    font-size: 13px;
  }

  .row {
    flex-direction: column;
    align-items: stretch;
  }

  .col-auto {
    width: 100%;
  }

  input[type="date"], button.btn-primary {
    width: 100%;
  }

  #reportSummary p {
    font-size: 13px;
  }
}

>>>>>>> 42b56596e61c3b58a1e10d6629d3959119018a24
</style>

<?php include '../config/db.php'; ?>

<!-- Back Button -->
<div class="back-button">
  <a href="home.php">⬅</a>
</div>

<div class="container mt-5">
  <h3 class="mb-4">📊 Sales Report</h3>

  <div class="row g-3 align-items-center mb-4">
    <div class="col-auto">
      <label for="from_date" class="col-form-label">From:</label>
    </div>
    <div class="col-auto">
      <input type="date" id="from_date" class="form-control">
    </div>
    <div class="col-auto">
      <label for="to_date" class="col-form-label">To:</label>
    </div>
    <div class="col-auto">
      <input type="date" id="to_date" class="form-control">
    </div>
    <div class="col-auto">
      <button id="fetchReportBtn" class="btn btn-primary">Generate Report</button>
    </div>
  </div>

  <div id="reportSummary" class="p-4 mb-4 rounded shadow-sm" style="background: #f8f9fa; display: none;">
    <h5 class="mb-3">📦 Report Summary</h5>
    <p><strong>Total Units Sold:</strong> <span id="units_sold"></span></p>
    <p><strong>Total Revenue:</strong> ₹<span id="total_revenue"></span></p>
    <p><strong>Net Profit:</strong> ₹<span id="net_profit"></span></p>
    <p><strong>Current In-house Stock:</strong> ₹<span id="stock_value"></span></p>
    <p><strong>Total Purchase:</strong> ₹<span id="total_purchase"></span></p>
  </div>
 


  <div id="salesList">
    <table class="table-report">
  <thead>
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
  </thead>
  <tbody>
    <!-- rows -->
  </tbody>
</table>
  </div>
</div>


<script>
  document.getElementById('fetchReportBtn').addEventListener('click', function () {
    const from = document.getElementById('from_date').value;
    const to = document.getElementById('to_date').value;

<<<<<<< HEAD
    if (!from || !to) {
      alert("Please select both dates.");
      return;
    }

    fetch('../ajax/fetch_report.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `from_date=${from}&to_date=${to}`
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        document.getElementById('reportSummary').style.display = 'block';
        document.getElementById('units_sold').innerText = data.units_sold;
        document.getElementById('total_revenue').innerText = data.total_revenue;
        document.getElementById('net_profit').innerText = data.net_profit;
        document.getElementById('stock_value').innerText = data.stock_value;
        document.getElementById('salesList').innerHTML = data.sales_html;
      } else {
        alert("No data found for the selected range.");
      }
    });
  });
=======
  if (!from || !to) {
    alert("Please select both dates.");
    return;
  }

  fetch('../ajax/fetch_report.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `from_date=${from}&to_date=${to}`
  })
  .then(res => res.json())
 .then(data => {
  if (data.success) {
    document.getElementById('reportSummary').style.display = 'block';
    document.getElementById('units_sold').innerText = data.units_sold;
    document.getElementById('total_revenue').innerText = data.total_revenue;
    document.getElementById('net_profit').innerText = data.net_profit;
    document.getElementById('stock_value').innerText = data.stock_value;
    document.getElementById('total_purchase').innerText = data.total_purchase;
    document.getElementById('salesList').innerHTML = data.sales_html;

    // Smooth scroll
    document.getElementById('reportSummary').scrollIntoView({ behavior: 'smooth' });
  } else {
    alert("No data found for the selected range.");
  }
});

});
>>>>>>> 42b56596e61c3b58a1e10d6629d3959119018a24
</script>
