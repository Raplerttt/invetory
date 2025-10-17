<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('title') ?>View Purchase Order<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">View Purchase Order</h1>
    <div>
        <a href="<?= base_url('/purchase-orders') ?>" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Back to POs
        </a>
        <?php if ($purchaseOrder['status'] === 'pending'): ?>
            <a href="<?= base_url('/purchase-orders/edit/' . $purchaseOrder['id']) ?>" class="btn btn-warning btn-sm">
                <i class="fas fa-edit fa-sm"></i> Edit
            </a>
        <?php endif; ?>
        <a href="<?= base_url('/purchase-orders/print/' . $purchaseOrder['id']) ?>" class="btn btn-info btn-sm" target="_blank">
            <i class="fas fa-print fa-sm"></i> Print
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Purchase Order Details</h6>
                <span class="badge bg-<?= 
                    $purchaseOrder['status'] === 'pending' ? 'warning' : 
                    ($purchaseOrder['status'] === 'approved' ? 'success' : 
                    ($purchaseOrder['status'] === 'rejected' ? 'danger' : 'info')) 
                ?>">
                    <?= ucfirst($purchaseOrder['status']) ?>
                </span>
            </div>
            <div class="card-body">
                <!-- PO Header -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h4 class="text-primary"><?= $purchaseOrder['po_number'] ?></h4>
                        <p class="text-muted">Purchase Order</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <h5 class="text-success">Rp <?= number_format($purchaseOrder['total_amount'], 0, ',', '.') ?></h5>
                        <p class="text-muted">Total Amount</p>
                    </div>
                </div>

                <!-- Supplier & Dates -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Supplier:</th>
                                <td>
                                    <strong><?= $purchaseOrder['supplier_name'] ?></strong><br>
                                    <small class="text-muted"><?= $purchaseOrder['supplier_code'] ?></small>
                                </td>
                            </tr>
                            <tr>
                                <th>Order Date:</th>
                                <td><?= date('d M Y', strtotime($purchaseOrder['order_date'])) ?></td>
                            </tr>
                            <tr>
                                <th>Delivery Date:</th>
                                <td>
                                    <?= $purchaseOrder['delivery_date'] ? date('d M Y', strtotime($purchaseOrder['delivery_date'])) : '<em class="text-muted">Not set</em>' ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Created By:</th>
                                <td><?= $purchaseOrder['created_by_name'] ?? 'System' ?></td>
                            </tr>
                            <tr>
                                <th>Approved By:</th>
                                <td>
                                    <?= $purchaseOrder['approved_by_name'] ? $purchaseOrder['approved_by_name'] : '<em class="text-muted">Not approved</em>' ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Approved At:</th>
                                <td>
                                    <?= $purchaseOrder['approved_at'] ? date('d M Y H:i', strtotime($purchaseOrder['approved_at'])) : '<em class="text-muted">Not approved</em>' ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- PO Items -->
                <h6 class="text-primary mb-3">PO Items</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($poItems as $item): ?>
                            <tr>
                                <td>
                                    <strong><?= $item['item_name'] ?></strong><br>
                                    <small class="text-muted"><?= $item['item_code'] ?> - <?= $item['item_unit'] ?></small>
                                </td>
                                <td><?= number_format($item['quantity']) ?></td>
                                <td class="text-nowrap">Rp <?= number_format($item['unit_price'], 0, ',', '.') ?></td>
                                <td class="text-nowrap"><strong>Rp <?= number_format($item['total_price'], 0, ',', '.') ?></strong></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Grand Total:</strong></td>
                                <td class="text-nowrap"><strong>Rp <?= number_format($purchaseOrder['total_amount'], 0, ',', '.') ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Notes -->
                <?php if ($purchaseOrder['notes']): ?>
                <div class="mt-4">
                    <h6 class="text-primary">Notes</h6>
                    <p class="text-muted"><?= nl2br($purchaseOrder['notes']) ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Approval Actions -->
        <?php if ($purchaseOrder['status'] === 'pending'): ?>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Approval Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <?php if ($purchaseOrder['total_amount'] < 10000000 || session()->get('role') === 'admin'): ?>
                        <a href="<?= base_url('/purchase-orders/approve/' . $purchaseOrder['id']) ?>" class="btn btn-success"
                           onclick="return confirm('Approve this Purchase Order?')">
                            <i class="fas fa-check me-1"></i> Approve PO
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($purchaseOrder['total_amount'] < 10000000 || session()->get('role') === 'admin'): ?>
                        <a href="<?= base_url('/purchase-orders/reject/' . $purchaseOrder['id']) ?>" class="btn btn-danger"
                           onclick="return confirm('Reject this Purchase Order?')">
                            <i class="fas fa-times me-1"></i> Reject PO
                        </a>
                    <?php endif; ?>
                </div>
                
                <?php if ($purchaseOrder['total_amount'] >= 10000000 && session()->get('role') !== 'admin'): ?>
                    <div class="alert alert-warning mt-2 mb-0">
                        <small>
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            PO dengan nilai ≥ Rp 10.000.000 hanya bisa disetujui/ditolak oleh Admin.
                        </small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Next Steps -->
        <?php if ($purchaseOrder['status'] === 'approved'): ?>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">Next Steps</h6>
            </div>
            <div class="card-body">
                <p>Purchase Order telah disetujui. Anda dapat melanjutkan ke:</p>
                <div class="d-grid gap-2">
                    <a href="<?= base_url('/goods-receipts/create/' . $purchaseOrder['id']) ?>" class="btn btn-primary">
                        <i class="fas fa-truck-loading me-1"></i> Create Goods Receipt
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Information Card -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Information</h6>
            </div>
            <div class="card-body">
                <div class="small">
                    <p><strong>Created:</strong><br>
                    <?= date('d M Y H:i', strtotime($purchaseOrder['created_at'])) ?></p>
                    
                    <p><strong>Last Updated:</strong><br>
                    <?= date('d M Y H:i', strtotime($purchaseOrder['updated_at'])) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>