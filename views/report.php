<style>
  body {
    background-color: #f5f9ff;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #333;
    padding: 60px 20px 20px;
    margin: 0;
    display: flex;
    justify-content: center;
    position: relative;
    min-height: 100vh;
    box-sizing: border-box;
  }

  .container {
    background-color: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 123, 255, 0.2);
    max-width: 800px;
    width: 100%;
    text-align: center;
    box-sizing: border-box;
    max-height: calc(100vh - 120px);
    overflow-y: auto;
    display: flex;
    flex-direction: column;
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
    background-color: #001a4d;
  }

  h3 {
    color: #004aad;
    font-weight: bold;
    margin-bottom: 20px;
    flex-shrink: 0;
  }

  .controls-section {
    flex-shrink: 0;
    margin-bottom: 20px;
  }

  .row {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 20px;
  }

  .row .col-auto {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  label {
    font-weight: 500;
    color: #004aad;
    white-space: nowrap;
  }

  input[type="date"] {
    padding: 8px 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
    min-width: 150px;
  }

  button.btn-primary,
  button.btn-secondary {
    background-color: #004aad;
    border-color: #004aad;
    color: #fff;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    border: none;
    white-space: nowrap;
  }

  button.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
  }

  button.btn-primary:hover {
    background-color: #003a8c;
  }

  button.btn-secondary:hover {
    background-color: #5a6268;
  }

  button:disabled {
    background-color: #ccc;
    cursor: not-allowed;
  }

  #reportSummary {
    text-align: left;
    margin-bottom: 20px;
    background-color: #f1f6ff;
    border: 1px solid #d0e4ff;
    padding: 20px;
    border-radius: 8px;
    flex-shrink: 0;
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

  .sales-list-container {
    flex: 1;
    overflow: hidden;
    display: flex;
    flex-direction: column;
  }

  #salesList {
    flex: 1;
    overflow: auto;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #fff;
  }

  #salesList table {
    width: 100%;
    border-collapse: collapse;
    min-width: 600px;
  }

  #salesList th,
  #salesList td {
    border: 1px solid #cce0ff;
    padding: 10px 8px;
    text-align: center;
    font-size: 14px;
    white-space: nowrap;
  }

  #salesList th {
    background-color: #004aad;
    color: white;
    position: sticky;
    top: 0;
    z-index: 1;
  }

  #salesList tr:nth-child(even) {
    background-color: #f9f9f9;
  }

  #salesList tr:hover {
    background-color: #e6f3ff;
  }

  /* Mobile Responsive */
  @media screen and (max-width: 768px) {
    body {
      padding: 20px 10px;
    }

    .container {
      padding: 20px;
      max-height: calc(100vh - 60px);
    }

    .back-button {
      left: 15px;
      top: 15px;
    }

    .row {
      flex-direction: column;
      align-items: stretch;
    }

    .row .col-auto {
      width: 100%;
      justify-content: space-between;
      margin-bottom: 10px;
    }

    input[type="date"] {
      min-width: auto;
      flex: 1;
    }

    button.btn-primary,
    button.btn-secondary {
      width: 100%;
      margin-top: 10px;
    }

    #reportSummary p {
      font-size: 14px;
    }

    #salesList th,
    #salesList td {
      padding: 8px 4px;
      font-size: 12px;
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

    #salesList th,
    #salesList td {
      padding: 6px 4px;
      font-size: 11px;
    }
  }

  /* Scrollbar styling */
  #salesList::-webkit-scrollbar {
    width: 8px;
    height: 8px;
  }

  #salesList::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
  }

  #salesList::-webkit-scrollbar-thumb {
    background: #004aad;
    border-radius: 4px;
  }

  #salesList::-webkit-scrollbar-thumb:hover {
    background: #003a8c;
  }

  .container::-webkit-scrollbar {
    width: 8px;
  }

  .container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
  }

  .container::-webkit-scrollbar-thumb {
    background: #004aad;
    border-radius: 4px;
  }

  .container::-webkit-scrollbar-thumb:hover {
    background: #003a8c;
  }
</style>

<?php include '../config/db.php'; ?>

<div class="back-button">
  <a href="home.php">⬅</a>
</div>

<div class="container mt-5">
  <h3 class="mb-4">📊 Sales Report</h3>

  <div class="controls-section">
    <div class="row g-3 align-items-center">
      <div class="col-auto">
        <label for="from_date" class="col-form-label">From:</label>
        <input type="date" id="from_date" class="form-control">
      </div>
      <div class="col-auto">
        <label for="to_date" class="col-form-label">To:</label>
        <input type="date" id="to_date" class="form-control">
      </div>
      <div class="col-auto">
        <button id="fetchReportBtn" class="btn btn-primary">Generate Report</button>
      </div>
      <div class="col-auto">
        <button id="generatePdfBtn" class="btn btn-secondary" disabled>Generate PDF</button>
      </div>
    </div>
  </div>

  <div id="reportSummary" class="p-4 mb-4 rounded shadow-sm" style="display: none;">
    <h5 class="mb-3">📦 Report Summary</h5>
    <p><strong>Total Units Sold:</strong> <span id="units_sold"></span></p>
    <p><strong>Total Purchase:</strong> ₹<span id="total_purchase"></span></p>
    <p><strong>Total Revenue:</strong> ₹<span id="total_revenue"></span></p>
    <p><strong>Net Profit:</strong> ₹<span id="net_profit"></span></p>
    <p><strong>Current In-house Stock:</strong> ₹<span id="stock_value"></span></p>
  </div>

  <div class="sales-list-container">
    <div id="salesList">
      <!-- Sales table will be populated here -->
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
  document.getElementById('fetchReportBtn').addEventListener('click', function() {
    const from = document.getElementById('from_date').value;
    const to = document.getElementById('to_date').value;

    if (!from || !to) {
      alert("Please select both dates.");
      return;
    }

    // Add loading state
    const button = this;
    const originalText = button.textContent;
    button.textContent = 'Loading...';
    button.disabled = true;

    fetch('../ajax/fetch_report.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `from_date=${from}&to_date=${to}`
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          document.getElementById('reportSummary').style.display = 'block';
          document.getElementById('units_sold').innerText = data.units_sold || '0';
          document.getElementById('total_revenue').innerText = data.total_revenue || '0';
          document.getElementById('total_purchase').innerText = data.total_purchase || '0';
          document.getElementById('net_profit').innerText = data.net_profit || '0';
          document.getElementById('stock_value').innerText = data.stock_value || '0';
          document.getElementById('salesList').innerHTML = data.sales_html || '<p style="text-align: center; padding: 20px; color: #666;">No sales data available</p>';
          document.getElementById('generatePdfBtn').disabled = false;
        } else {
          alert("No data found for the selected range.");
          document.getElementById('generatePdfBtn').disabled = true;
          document.getElementById('reportSummary').style.display = 'none';
          document.getElementById('salesList').innerHTML = '<p style="text-align: center; padding: 20px; color: #666;">No data available</p>';
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while fetching the report.');
      })
      .finally(() => {
        button.textContent = originalText;
        button.disabled = false;
      });
  });

  document.getElementById('generatePdfBtn').addEventListener('click', function() {
    const button = this;
    const originalText = button.textContent;
    button.textContent = 'Generating...';
    button.disabled = true;

    // Hide the buttons before generating PDF
    const fetchBtn = document.getElementById('fetchReportBtn');
    const generateBtn = document.getElementById('generatePdfBtn');
    
    fetchBtn.style.display = 'none';
    generateBtn.style.display = 'none';

    // Create a temporary container for PDF generation
    const tempContainer = document.querySelector('.container').cloneNode(true);
    tempContainer.style.maxHeight = 'none';
    tempContainer.style.overflow = 'visible';
    
    // Find the sales list in the temp container and make it not scrollable
    const tempSalesList = tempContainer.querySelector('#salesList');
    if (tempSalesList) {
      tempSalesList.style.overflow = 'visible';
      tempSalesList.style.maxHeight = 'none';
    }

    // Temporarily add the container to the body (hidden)
    tempContainer.style.position = 'absolute';
    tempContainer.style.left = '-9999px';
    document.body.appendChild(tempContainer);

    html2canvas(tempContainer, {
      scale: 2,
      useCORS: true,
      allowTaint: true,
      scrollX: 0,
      scrollY: 0
    }).then(canvas => {
      const imgData = canvas.toDataURL('image/png');
      const pdf = new window.jspdf.jsPDF({
        orientation: 'portrait',
        unit: 'pt',
        format: 'a4'
      });
      
      const pageWidth = pdf.internal.pageSize.getWidth();
      const pageHeight = pdf.internal.pageSize.getHeight();
      const imgWidth = pageWidth - 40;
      const imgHeight = canvas.height * imgWidth / canvas.width;
      
      // If content is too tall, split into multiple pages
      if (imgHeight > pageHeight - 40) {
        let remainingHeight = imgHeight;
        let position = 0;
        
        while (remainingHeight > 0) {
          const pageCanvas = document.createElement('canvas');
          const pageCtx = pageCanvas.getContext('2d');
          const pageContentHeight = Math.min(remainingHeight, pageHeight - 40);
          
          pageCanvas.width = canvas.width;
          pageCanvas.height = (pageContentHeight * canvas.width) / imgWidth;
          
          pageCtx.drawImage(canvas, 0, position * canvas.height / imgHeight, canvas.width, pageCanvas.height, 0, 0, canvas.width, pageCanvas.height);
          
          if (position > 0) {
            pdf.addPage();
          }
          
          pdf.addImage(pageCanvas.toDataURL('image/png'), 'PNG', 20, 20, imgWidth, pageContentHeight);
          
          remainingHeight -= pageContentHeight;
          position += pageContentHeight;
        }
      } else {
        pdf.addImage(imgData, 'PNG', 20, 20, imgWidth, imgHeight);
      }
      
      const currentDate = new Date().toISOString().split('T')[0];
      pdf.save(`sales_report_${currentDate}.pdf`);
      
      // Clean up
      document.body.removeChild(tempContainer);
      
      // Show the buttons again
      fetchBtn.style.display = '';
      generateBtn.style.display = '';
      
      button.textContent = originalText;
      button.disabled = false;
    }).catch(error => {
      console.error('Error generating PDF:', error);
      alert('An error occurred while generating the PDF.');
      
      // Clean up and restore buttons
      document.body.removeChild(tempContainer);
      fetchBtn.style.display = '';
      generateBtn.style.display = '';
      
      button.textContent = originalText;
      button.disabled = false;
    });
  });

  // Set default dates (last 30 days)
  const today = new Date();
  const thirtyDaysAgo = new Date(today.getTime() - (30 * 24 * 60 * 60 * 1000));
  
  document.getElementById('to_date').value = today.toISOString().split('T')[0];
  document.getElementById('from_date').value = thirtyDaysAgo.toISOString().split('T')[0];
</script>