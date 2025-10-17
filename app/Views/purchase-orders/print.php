<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - <?= $purchaseOrder['po_number'] ?></title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .company-info {
            margin-bottom: 10px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .document-title {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
        }
        .po-number {
            font-size: 16px;
            color: #666;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .row {
            display: flex;
            margin-bottom: 8px;
        }
        .col-6 {
            flex: 1;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-section {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px solid #333;
        }
        .signature-section {
            margin-top: 50px;
        }
        .signature-line {
            border-top: 1px solid #333;
            width: 200px;
            margin-top: 60px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
            .page-break { page-break-before: always; }
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-approved { background-color: #d1edff; color: #0c5460; }
        .status-rejected { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <!-- Print Button (hidden when printing) -->
    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" class="btn btn-primary" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
            <i class="fas fa-print"></i> Print Document
        </button>
        <button onclick="window.close()" class="btn btn-secondary" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">
            <i class="fas fa-times"></i> Close
        </button>
    </div>

    <!-- Header -->
    <div class="header">
        <div class="company-info">
            <div class="company-name">INVENTORY MANAGEMENT SYSTEM</div>
            <div>Jl. Contoh Alamat No. 123, Jakarta</div>
            <div>Telp: (021) 123-4567 | Email: info@company.com</div>
        </div>
        <div class="document-title">PURCHASE ORDER</div>
        <div class="po-number">No: <?= $purchaseOrder['po_number'] ?></div>
    </div>

    <!-- Supplier & Dates -->
    <div class="section">
        <div class="row">
            <div class="col-6">
                <strong>Kepada:</strong><br>
                <strong><?= $purchaseOrder['supplier_name'] ?></strong><br>
                <?= $purchaseOrder['supplier_code'] ?><br>
            </div>
            <div class="col-6">
                <table style="width: 100%;">
                    <tr>
                        <td><strong>Tanggal PO:</strong></td>
                        <td><?= date('d F Y', strtotime($purchaseOrder['order_date'])) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Pengiriman:</strong></td>
                        <td>
                            <?= $purchaseOrder['delivery_date'] ? date('d F Y', strtotime($purchaseOrder['delivery_date'])) : 'Tidak ditentukan' ?>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            <span class="status-badge status-<?= $purchaseOrder['status'] ?>">
                                <?= strtoupper($purchaseOrder['status']) ?>
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- PO Items -->
    <div class="section">
        <div class="section-title">DAFTAR BARANG</div>
        <table class="table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="45%">Nama Barang</th>
                    <th width="15%">Kode</th>
                    <th width="10%">Qty</th>
                    <th width="15%">Harga Satuan</th>
                    <th width="15%">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $grandTotal = 0;
                $counter = 1;
                ?>
                <?php foreach ($poItems as $item): ?>
                <tr>
                    <td class="text-center"><?= $counter++ ?></td>
                    <td><?= $item['item_name'] ?></td>
                    <td><?= $item['item_code'] ?></td>
                    <td class="text-center"><?= number_format($item['quantity']) ?></td>
                    <td class="text-right">Rp <?= number_format($item['unit_price'], 0, ',', '.') ?></td>
                    <td class="text-right">Rp <?= number_format($item['total_price'], 0, ',', '.') ?></td>
                </tr>
                <?php 
                    $grandTotal += $item['total_price'];
                ?>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-right"><strong>GRAND TOTAL</strong></td>
                    <td class="text-right"><strong>Rp <?= number_format($grandTotal, 0, ',', '.') ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Notes -->
    <?php if ($purchaseOrder['notes']): ?>
    <div class="section">
        <div class="section-title">CATATAN</div>
        <p><?= nl2br($purchaseOrder['notes']) ?></p>
    </div>
    <?php endif; ?>

    <!-- Approval Information -->
    <?php if ($purchaseOrder['status'] === 'approved'): ?>
    <div class="section">
        <div class="section-title">PERSETUJUAN</div>
        <div class="row">
            <div class="col-6">
                <strong>Disetujui Oleh:</strong><br>
                <?= $purchaseOrder['approved_by_name'] ?><br>
                <div class="signature-line"></div>
                <div style="margin-top: 5px;">Tanggal: <?= date('d F Y', strtotime($purchaseOrder['approved_at'])) ?></div>
            </div>
            <div class="col-6">
                <strong>Dibuat Oleh:</strong><br>
                <?= $purchaseOrder['created_by_name'] ?? 'System' ?><br>
                <div class="signature-line"></div>
                <div style="margin-top: 5px;">Tanggal: <?= date('d F Y', strtotime($purchaseOrder['created_at'])) ?></div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini dicetak secara elektronik pada <?= date('d F Y H:i:s') ?></p>
        <p>Inventory Management System - <?= date('Y') ?></p>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            // Optional: auto print
            // window.print();
        };
    </script>
</body>
</html>