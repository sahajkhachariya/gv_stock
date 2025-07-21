<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../vendor/autoload.php';

use setasign\Fpdi\Fpdi;

$pdf = new Fpdi();
$pdf->AddPage();
$templatePath = __DIR__ . '/../templates/GST_BILL.pdf';
$pageCount = $pdf->setSourceFile($templatePath);
$templateId = $pdf->importPage(1);
$pdf->useTemplate($templateId);
$pdf->SetFont('Arial', '', 10);

if (!isset($_GET['group'])) die('Invalid request');

$groupKey = $_GET['group'];
$db = new DB();
$conn = $db->connect();

$parts = explode('_', $groupKey);
if (count($parts) < 3) die('Invalid group key');
$customer_name = $conn->real_escape_string($parts[0]);
$customer_phone = $conn->real_escape_string($parts[1]);
$created_at = $conn->real_escape_string($parts[2]);

$sql = "SELECT s.*, p.name AS product_name, p.description, p.product_code 
        FROM sales s 
        JOIN products p ON s.product_id = p.id 
        WHERE s.customer_name='$customer_name' 
          AND s.customer_phone='$customer_phone' 
          AND s.created_at='$created_at'";
$result = $conn->query($sql);
if (!$result || $result->num_rows == 0) die('No data found');

$row = $result->fetch_assoc();
$gst_type = $row['gst_type'];
$is_inclusive = (strpos($gst_type, 'Inclusive') !== false);
$total_price = 0;

$pdf->SetXY(173, 24);
$pdf->Write(5, 'INV - ' . rand(1000, 9999));
$pdf->SetXY(173, 29.60);
$pdf->Write(5, date('d-m-Y', strtotime($created_at)));
$pdf->SetXY(173, 35.60);
$pdf->Write(5, date('d-m-Y', strtotime($created_at . ' +7 days')));
$pdf->SetXY(34, 54.5);
$pdf->Write(5, $customer_name);
$pdf->SetXY(34, 66.75);
$pdf->Write(5, 'GSTN: NA');
$pdf->SetXY(34, 72.75);
$pdf->MultiCell(80, 5, 'Billing Address: Not Available');
$pdf->SetXY(173, 54.75);
$pdf->MultiCell(60, 5, 'Same as billing');
$pdf->SetXY(173, 66.75);
$pdf->Write(5, $customer_phone);

// Fill product table
$startY = 99;
$result->data_seek(0);
$i = 0;
while ($row = $result->fetch_assoc()) {
    $y = $startY + ($i * 10);
    $product = $row['product_name'] . ' - ' . $row['description'];
    $hsn = $row['product_code'];
    $qty = $row['quantity'];
    $rate = $row['price_per_unit'];
    $disc = $row['discount_percent'] ?? 0;

    $line_subtotal = $rate * $qty;
    $discount_amount = ($line_subtotal * $disc) / 100;
    $after_discount = $line_subtotal - $discount_amount;

    if ($is_inclusive) {
        $line_total = $after_discount;
        $tax_display = '';
    } else {
        $tax = 18;
        $tax_amt = $after_discount * $tax / 100;
        $line_total = $after_discount + $tax_amt;
        $tax_display = "{$tax}%";
    }

    $pdf->SetXY(5, $y);
    $pdf->Cell(10, 10, $i + 1, 0, 0);
    $pdf->SetXY(17, $y);
    $pdf->Cell(55, 10, $product, 0, 0);
    $pdf->SetXY(83, $y);
    $pdf->Cell(20, 10, $hsn, 0, 0);
    $pdf->SetXY(105, $y);
    $pdf->Cell(15, 10, $qty, 0, 0);
    $pdf->SetXY(113.5, $y);
    $pdf->Cell(20, 10, number_format($rate, 2), 0, 0);
    $pdf->SetXY(140, $y);
    $pdf->Cell(20, 10, number_format($disc, 2), 0, 0);
    $pdf->SetXY(165, $y);
    $pdf->Cell(15, 10, $tax_display, 0, 0);
    $pdf->SetXY(185, $y);
    $pdf->Cell(30, 10, number_format($line_total, 2), 0, 0);

    $total_price += $line_total;
    $i++;
}

// Totals
if ($is_inclusive) {
    $sub_total = $total_price;
    $cgst = 0;
    $sgst = 0;
} else {
    $sub_total = $total_price / 1.18;
    $cgst = $sub_total * 0.09;
    $sgst = $sub_total * 0.09;
}

$pdf->SetXY(180, 198);
$pdf->Cell(40, 10, 'Rs.' . number_format($sub_total, 2), 0, 0);

if (!$is_inclusive) {
    $pdf->SetXY(148, 215.5);
    $pdf->Cell(40, 10, '(9%): Rs.' . number_format($cgst, 2), 0, 0);
    $pdf->SetXY(148, 221);
    $pdf->Cell(40, 10, '(9%): Rs.' . number_format($sgst, 2), 0, 0);
}

// Discount from last row
$discount_percent = $row['discount_percent'] ?? 0;
$discount_amount = $row['discount_amount'] ?? 0;
$grand_total = $total_price - $discount_amount;

$pdf->SetXY(150, 244);
$pdf->Cell(40, 10, 'Rs.' . number_format($grand_total, 2), 0, 0);

$pdf->Output('I', 'Invoice.pdf');