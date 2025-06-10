<?php include '../config/db.php'; ?>

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
  </div>

  <div id="salesList">
    <!-- Sales list table will be appended here -->
  </div>
</div>

<script>
document.getElementById('fetchReportBtn').addEventListener('click', function () {
  const from = document.getElementById('from_date').value;
  const to = document.getElementById('to_date').value;

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
</script>
