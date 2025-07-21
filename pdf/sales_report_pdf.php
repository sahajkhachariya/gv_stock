<?php
require('../fpdf/fpdf.php');
require('../config/db.php');

// Date inputs
$from = $_GET['from_date'] ?? $_POST['from_date'] ?? '';
$to = $_GET['to_date'] ?? $_POST['to_date'] ?? '';
if (!$from || !$to) die('Invalid date range.');

$sql = "SELECT 
            SUM(units_sold) as units_sold,
            SUM(total_purchase) as total_purchase,
            SUM(total_revenue) as total_revenue,
            SUM(net_profit) as net_profit
        FROM sales WHERE sale_date BETWEEN ? AND ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $from, $to);
$stmt->execute();
$summary = $stmt->get_result()->fetch_assoc();

$stock_sql = "SELECT SUM(stock * purchase_price) as stock_value FROM products";
$stock_result = $conn->query($stock_sql);
$stock_row = $stock_result->fetch_assoc();
$stock_value = $stock_row['stock_value'] ?? 0;

$list_sql = "SELECT * FROM sales WHERE sale_date BETWEEN ? AND ? ORDER BY sale_date DESC";
$list_stmt = $conn->prepare($list_sql);
$list_stmt->bind_param('ss', $from, $to);
$list_stmt->execute();
$sales_list = $list_stmt->get_result();

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, '📊 Sales Report', 0, 1, 'C');
        $this->Ln(2);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

// Summary
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, "📄 Report Summary ($from to $to)", 0, 1);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(60, 8, 'Total Units Sold:', 0, 0);       $pdf->Cell(60, 8, $summary['units_sold'] ?? 0, 0, 1);
$pdf->Cell(60, 8, 'Total Purchase:', 0, 0);         $pdf->Cell(60, 8, '₹' . number_format($summary['total_purchase'], 2), 0, 1);
$pdf->Cell(60, 8, 'Total Revenue:', 0, 0);          $pdf->Cell(60, 8, '₹' . number_format($summary['total_revenue'], 2), 0, 1);
$pdf->Cell(60, 8, 'Net Profit:', 0, 0);             $pdf->Cell(60, 8, '₹' . number_format($summary['net_profit'], 2), 0, 1);
$pdf->Cell(60, 8, 'Current In-house Stock:', 0, 0); $pdf->Cell(60, 8, '₹' . number_format($stock_value, 2), 0, 1);
$pdf->Ln(10);

// Table headers
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(220, 220, 220);
$pdf->Cell(40, 10, 'Date', 1, 0, 'C', true); // Increased width from 30 to 40
$pdf->Cell(50, 10, 'Product', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'Qty', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Purchase', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Revenue', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Profit', 1, 1, 'C', true);

// Table body
$pdf->SetFont('Arial', '', 9);
while ($row = $sales_list->fetch_assoc()) {
    $pdf->Cell(40, 8, date('d-m-Y', strtotime($row['sale_date'])), 1); // Increased width from 30 to 40
    $pdf->Cell(50, 8, $row['product_name'], 1);
    $pdf->Cell(20, 8, $row['units_sold'], 1, 0, 'C');
    $pdf->Cell(30, 8, '₹' . number_format($row['total_purchase'], 2), 1, 0, 'R');
    $pdf->Cell(30, 8, '₹' . number_format($row['total_revenue'], 2), 1, 0, 'R');
    $pdf->Cell(30, 8, '₹' . number_format($row['net_profit'], 2), 1, 1, 'R');
}

$pdf->Output('D', 'sales_report.pdf');
exit;
