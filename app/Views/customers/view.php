<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('title') ?>View Customer<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">View Customer</h1>
    <div>
        <a href="<?= base_url('/customers') ?>" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Back to Customers
        </a>
        <a href="<?= base_url('/customers/edit/' . $customer['id']) ?>" class="btn btn-warning btn-sm">
            <i class="fas fa-edit fa-sm"></i> Edit
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Customer Details</h6>
                <span class="badge bg-<?= $customer['is_active'] ? 'success' : 'danger' ?>">
                    <?= $customer['is_active'] ? 'Active' : 'Inactive' ?>
                </span>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-8">
                        <h4 class="text-primary"><?= $customer['name'] ?></h4>
                        <p class="text-muted">Code: <?= $customer['code'] ?></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Phone:</th>
                                <td>
                                    <?php if ($customer['phone']): ?>
                                        <a href="tel:<?= $customer['phone'] ?>" class="text-decoration-none">
                                            <?= $customer['phone'] ?>
                                        </a>
                                    <?php else: ?>
                                        <em class="text-muted">Not set</em>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>
                                    <?php if ($customer['email']): ?>
                                        <a href="mailto:<?= $customer['email'] ?>" class="text-decoration-none">
                                            <?= $customer['email'] ?>
                                        </a>
                                    <?php else: ?>
                                        <em class="text-muted">Not set</em>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <?php if ($customer['address']): ?>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-primary">Address</h6>
                        <p class="text-muted"><?= nl2br($customer['address']) ?></p>
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
                    <a href="<?= base_url('/customers/edit/' . $customer['id']) ?>" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i> Edit Customer
                    </a>
                    <a href="<?= base_url('/sales-orders/create') ?>?customer_id=<?= $customer['id'] ?>" class="btn btn-success">
                        <i class="fas fa-shopping-cart me-1"></i> Create Sales Order
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
                    <?= date('d M Y H:i', strtotime($customer['created_at'])) ?></p>
                    
                    <p><strong>Last Updated:</strong><br>
                    <?= date('d M Y H:i', strtotime($customer['updated_at'])) ?></p>
                    
                    <?php if ($customer['created_by']): ?>
                    <p><strong>Created By:</strong><br>
                    User ID: <?= $customer['created_by'] ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>