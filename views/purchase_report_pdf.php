<?php
require_once '../config/db.php';

// Include FPDF library
require('../fpdf/fpdf.php'); // Make sure FPDF is in the correct path

$db = new DB();
$conn = $db->connect();

// Get filter parameters
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

// Start PDF
$pdf = new FPDF('L', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Purchase Report', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);

$pdf->SetFont('Arial', '', 12);
if (!empty($_GET['from']) && !empty($_GET['to'])) {
    $pdf->Cell(0, 10, 'From: ' . $_GET['from'] . ' To: ' . $_GET['to'], 0, 1, 'C');
}

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(0, 0, 0); // Black background
$pdf->SetTextColor(255, 255, 255); // White text

$pdf->Cell(25, 8, 'Supplier', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Phone', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Product', 1, 0, 'C', true);
$pdf->Cell(20, 8, 'Code', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Description', 1, 0, 'C', true);
$pdf->Cell(10, 8, 'Qty', 1, 0, 'C', true);
$pdf->Cell(20, 8, 'Cost/Unit', 1, 0, 'C', true);
$pdf->Cell(18, 8, 'GST Type', 1, 0, 'C', true);
$pdf->Cell(15, 8, 'IGST', 1, 0, 'C', true);
$pdf->Cell(15, 8, 'CGST', 1, 0, 'C', true);
$pdf->Cell(15, 8, 'SGST', 1, 0, 'C', true);
$pdf->Cell(20, 8, 'Total', 1, 0, 'C', true);
$pdf->Cell(34, 8, 'Date', 1, 1, 'C', true);

$pdf->SetTextColor(0, 0, 0); // <- Add this here
$pdf->SetFont('Arial', '', 8);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(25, 8, $row['supplier_name'], 1);
        $pdf->Cell(25, 8, $row['supplier_phone'], 1);
        $pdf->Cell(30, 8, $row['name'], 1);
        $pdf->Cell(20, 8, $row['product_code'], 1);
        $pdf->Cell(30, 8, $row['description'], 1);
        $pdf->Cell(10, 8, $row['quantity'], 1, 0, 'C');
        $pdf->Cell(20, 8, number_format($row['cost_price'], 2), 1, 0, 'R');
        $pdf->Cell(18, 8, strtoupper($row['gst_type']), 1, 0, 'C');
        $pdf->Cell(15, 8, number_format($row['igst_amount'], 2), 1, 0, 'R');
        $pdf->Cell(15, 8, number_format($row['cgst_amount'], 2), 1, 0, 'R');
        $pdf->Cell(15, 8, number_format($row['sgst_amount'], 2), 1, 0, 'R');
        $pdf->Cell(20, 8, number_format($row['total_price'], 2), 1, 0, 'R');
        $pdf->Cell(34, 8, date("d-m-Y H:i", strtotime($row['created_at'])), 1, 1);
    }
} else {
    $pdf->Cell(299, 10, 'No records found.', 1, 1, 'C');
}

$pdf->Output('D', 'purchase_report.pdf');
exit;
