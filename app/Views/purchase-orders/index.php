<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('title') ?>Purchase Orders<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Purchase Orders</h1>
    <a href="<?= base_url('/purchase-orders/create') ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Create New PO
    </a>
</div>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Purchase Orders List</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead class="table-light">
                    <tr>
                        <th>PO Number</th>
                        <th>Supplier</th>
                        <th>Order Date</th>
                        <th>Delivery Date</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($purchaseOrders as $po): ?>
                    <tr>
                        <td>
                            <strong><?= $po['po_number'] ?></strong>
                        </td>
                        <td>
                            <?= $po['supplier_name'] ?><br>
                            <small class="text-muted"><?= $po['supplier_code'] ?></small>
                        </td>
                        <td><?= date('d M Y', strtotime($po['order_date'])) ?></td>
                        <td>
                            <?= $po['delivery_date'] ? date('d M Y', strtotime($po['delivery_date'])) : '<em class="text-muted">Not set</em>' ?>
                        </td>
                        <td class="text-nowrap">
                            <strong>Rp <?= number_format($po['total_amount'], 0, ',', '.') ?></strong>
                        </td>
                        <td>
                            <?php
                            $statusBadge = [
                                'pending' => 'warning',
                                'approved' => 'success',
                                'rejected' => 'danger',
                                'completed' => 'info'
                            ];
                            $statusText = [
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                                'completed' => 'Completed'
                            ];
                            ?>
                            <span class="badge bg-<?= $statusBadge[$po['status']] ?? 'secondary' ?>">
                                <?= $statusText[$po['status']] ?? $po['status'] ?>
                            </span>
                        </td>
                        <td><?= $po['created_by_name'] ?? 'System' ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="<?= base_url('/purchase-orders/view/' . $po['id']) ?>" class="btn btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if ($po['status'] === 'pending'): ?>
                                    <a href="<?= base_url('/purchase-orders/edit/' . $po['id']) ?>" class="btn btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                <?php endif; ?>
                                <a href="<?= base_url('/purchase-orders/print/' . $po['id']) ?>" class="btn btn-secondary" title="Print" target="_blank">
                                    <i class="fas fa-print"></i>
                                </a>
                            </div>

                            <!-- Approval Actions -->
                            <?php if ($po['status'] === 'pending'): ?>
                                <div class="btn-group btn-group-sm mt-1">
                                    <?php if ($po['total_amount'] < 10000000 || session()->get('role') === 'admin'): ?>
                                        <a href="<?= base_url('/purchase-orders/approve/' . $po['id']) ?>" class="btn btn-success" title="Approve"
                                           onclick="return confirm('Approve this Purchase Order?')">
                                            <i class="fas fa-check"></i>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($po['total_amount'] < 10000000 || session()->get('role') === 'admin'): ?>
                                        <a href="<?= base_url('/purchase-orders/reject/' . $po['id']) ?>" class="btn btn-danger" title="Reject"
                                           onclick="return confirm('Reject this Purchase Order?')">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide alerts
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    });
</script>
<?= $this->endSection() ?>