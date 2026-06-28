<?php
// controllers/InvoiceController.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../fpdf/fpdf.php';

class InvoiceController {
    
    public function download() {
        
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $db = Database::getConnection();

        // 1. Fetch Master Order record verify ownership context layout profiles
        $stmt = $db->prepare("SELECT o.*, u.name as user_name, u.email as user_email FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = ? AND o.user_id = ?");
        $stmt->execute([$orderId, $_SESSION['user_id']]);
        $order = $stmt->fetch();

        if (!$order) {
            die("Error: Authorized Invoice records parameter target matching index not found.");
        }

        // 2. Fetch associated line rows elements purchased quantities catalog mapping values
        $itemStmt = $db->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $itemStmt->execute([$orderId]);
        $items = $itemStmt->fetchAll();

        // 3. Initiate FPDF Vector canvas compilation engine context layout rules
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetMargins(15, 15, 15);
        
        // --- INVOICE TYPOGRAPHY STYLING SCHEME HEADER ---
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->SetTextColor(37, 99, 235); // Matches the system primary Blue brand accent theme
        $pdf->Cell(100, 10, 'MVC INDIAN E-SHOP PVT LTD', 0, 0);
        
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor(31, 41, 55);
        $pdf->Cell(80, 10, 'TAX INVOICE / RECEIPT', 0, 1, 'R');
        $pdf->Ln(2);

        // Corporate Merchant metadata address specs metrics lines block rows
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(107, 114, 128);
        $pdf->Cell(100, 4, 'Corporate District Node, Tech Park Phase-2', 0, 0);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetTextColor(55, 65, 81);
        $pdf->Cell(80, 4, 'Invoice Ref: ' . $order['mock_payment_id'], 0, 1, 'R');
        
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(107, 114, 128);
        $pdf->Cell(100, 4, 'New Delhi, Delhi, 110001, India', 0, 0);
        $pdf->Cell(80, 4, 'Date: ' . date('d-M-Y', strtotime($order['created_at'])), 0, 1, 'R');
        
        $pdf->Cell(100, 4, 'GSTIN Corporate ID: 07AAAAM1234C1Z5 (Mock)', 0, 0);
        $pdf->Cell(80, 4, 'Channel: ' . strtoupper($order['payment_method']), 0, 1, 'R');
        $pdf->Ln(8);

        // Draw structural solid styling layout divider bar vector stroke line item rule
        $pdf->Line(15, 42, 195, 42);

        // --- CUSTOMER SHIPPING AND BILLING LAYOUT ADDRESS DATA BLOCKS ---
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor(31, 41, 55);
        $pdf->Cell(90, 5, 'Billed & Shipped To:', 0, 1);
        
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(55, 65, 81);
        $pdf->Cell(90, 4, 'Customer Name: ' . $order['user_name'], 0, 1);
        $pdf->Cell(90, 4, 'Email Identity: ' . $order['user_email'], 0, 1);
        
        // Multi-line wrap delivery address data metric text boundaries
        $pdf->SetFont('Arial', 'I', 9);
        $pdf->MultiCell(120, 4, 'Address: ' . $order['shipping_address'], 0, 'L');
        $pdf->Ln(8);

        // --- TABULAR TRANSACTION ITEM MATRIX SECTION ---
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetFillColor(243, 244, 246); // Light gray background matrix layout shading fills
        $pdf->SetTextColor(55, 65, 81);
        
        // Print column headers
        $pdf->Cell(80, 8, ' Product Description Item Title', 1, 0, 'L', true);
        $pdf->Cell(25, 8, 'Unit Price (INR)', 1, 0, 'C', true);
        $pdf->Cell(20, 8, 'Qty Rate', 1, 0, 'C', true);
        $pdf->Cell(25, 8, 'Taxable (INR)', 1, 0, 'C', true);
        $pdf->Cell(30, 8, 'Gross Total (INR)', 1, 1, 'C', true);

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(31, 41, 55);
        
        $totalTaxableAmount = 0;

        foreach ($items as $item) {
            $grossItemTotal = (float)$item['price'] * (int)$item['quantity'];
            
            // Calculate Indian 18% inclusive GST valuation math metrics configurations formulas
            // Taxable Base = Gross Total / (1 + GST Rate)
            $taxableBase = $grossItemTotal / 1.18;
            $totalTaxableAmount += $taxableBase;

            $pdf->Cell(80, 8, ' ' . $item['product_name'], 1, 0, 'L');
            $pdf->Cell(25, 8, number_format($item['price'] / 1.18, 2), 1, 0, 'C');
            $pdf->Cell(20, 8, $item['quantity'], 1, 0, 'C');
            $pdf->Cell(25, 8, number_format($taxableBase, 2), 1, 0, 'C');
            $pdf->Cell(30, 8, number_format($grossItemTotal, 2), 1, 1, 'C');
        }

        // --- GST TAX CALCULATION & INVOICE SUMMARY PANEL SUMMARY CLOSURES ---
        $pdf->Ln(4);
        $orderTotalAmount = (float)$order['total_amount'];
        $aggregateGSTValue = $orderTotalAmount - $totalTaxableAmount;
        
        // Indian Domestic compliance rule demands CGST (9%) + SGST (9%) structural breakdown equal ratio split
        $splitGSTComponent = $aggregateGSTValue / 2;

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(125, 6, '', 0, 0);
        $pdf->Cell(25, 6, 'Net Taxable base:', 0, 0, 'R');
        $pdf->Cell(30, 6, 'Rs. ' . number_format($totalTaxableAmount, 2), 0, 1, 'R');
        
        $pdf->Cell(125, 6, '', 0, 0);
        $pdf->Cell(25, 6, 'CGST Rate (9%):', 0, 0, 'R');
        $pdf->Cell(30, 6, 'Rs. ' . number_format($splitGSTComponent, 2), 0, 1, 'R');
        
        $pdf->Cell(125, 6, '', 0, 0);
        $pdf->Cell(25, 6, 'SGST Rate (9%):', 0, 0, 'R');
        $pdf->Cell(30, 6, 'Rs. ' . number_format($splitGSTComponent, 2), 0, 1, 'R');

        // Draw final gross grand summary total block outline box
        $pdf->Ln(2);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(125, 8, '', 0, 0);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFillColor(37, 99, 235); // Prominent brand bold highlighting banner fills
        $pdf->Cell(25, 8, ' Grand Total:', 0, 0, 'L', true);
        $pdf->Cell(30, 8, 'Rs. ' . number_format($orderTotalAmount, 2) . ' ', 0, 1, 'R', true);
        
        $pdf->Ln(15);
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->SetTextColor(156, 163, 175);
        $pdf->Cell(180, 4, 'This is a computer-generated dynamic electronic receipt document. No physical signature verification is required.', 0, 1, 'C');
        $pdf->Cell(180, 4, 'Thank you for shopping with MVC Indian E-Shop!', 0, 1, 'C');

        // 4. Force browser window context transmission binary action download attachment loop
        $pdf->Output('D', 'Invoice_' . $order['mock_payment_id'] . '.pdf');
        exit;
    }
}
