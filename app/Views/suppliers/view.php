<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('title') ?>View Supplier<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">View Supplier</h1>
    <div>
        <a href="<?= base_url('/suppliers') ?>" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Back to Suppliers
        </a>
        <a href="<?= base_url('/suppliers/edit/' . $supplier['id']) ?>" class="btn btn-warning btn-sm">
            <i class="fas fa-edit fa-sm"></i> Edit
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Supplier Details</h6>
                <span class="badge bg-<?= $supplier['is_active'] ? 'success' : 'danger' ?>">
                    <?= $supplier['is_active'] ? 'Active' : 'Inactive' ?>
                </span>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-8">
                        <h4 class="text-primary"><?= $supplier['name'] ?></h4>
                        <p class="text-muted">Code: <?= $supplier['code'] ?></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th>Phone:</th>
                                <td>
                                    <?php if ($supplier['phone']): ?>
                                        <a href="tel:<?= $supplier['phone'] ?>" class="text-decoration-none">
                                            <?= $supplier['phone'] ?>
                                        </a>
                                    <?php else: ?>
                                        <em class="text-muted">Not set</em>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>
                                    <?php if ($supplier['email']): ?>
                                        <a href="mailto:<?= $supplier['email'] ?>" class="text-decoration-none">
                                            <?= $supplier['email'] ?>
                                        </a>
                                    <?php else: ?>
                                        <em class="text-muted">Not set</em>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <?php if ($supplier['address']): ?>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-primary">Address</h6>
                        <p class="text-muted"><?= nl2br($supplier['address']) ?></p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Quick Actions Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= base_url('/suppliers/edit/' . $supplier['id']) ?>" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i> Edit Supplier
                    </a>
                    <a href="<?= base_url('/purchase-orders/create') ?>?supplier_id=<?= $supplier['id'] ?>" class="btn btn-success">
                        <i class="fas fa-shopping-cart me-1"></i> Create Purchase Order
                    </a>
                </div>
            </div>
        </div>

        <!-- Information Card -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Information</h6>
            </div>
            <div class="card-body">
                <div class="small">
                    <p><strong>Created:</strong><br>
                    <?= date('d M Y H:i', strtotime($supplier['created_at'])) ?></p>
                    
                    <p><strong>Last Updated:</strong><br>
                    <?= date('d M Y H:i', strtotime($supplier['updated_at'])) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>