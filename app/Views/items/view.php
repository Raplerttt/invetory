<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('title') ?>View Item<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">View Item</h1>
    <div>
        <a href="<?= base_url('/items') ?>" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Back to Items
        </a>
        <a href="<?= base_url('/items/edit/' . $item['id']) ?>" class="btn btn-warning btn-sm">
            <i class="fas fa-edit fa-sm"></i> Edit
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Item Details</h6>
                <span class="badge bg-<?= $item['is_active'] ? 'success' : 'danger' ?>">
                    <?= $item['is_active'] ? 'Active' : 'Inactive' ?>
                </span>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="text-primary"><?= $item['name'] ?></h5>
                        <p class="text-muted"><?= $item['code'] ?></p>
                    </div>
                    <div class="col-md-6 text-end">
                        <h4 class="text-success">Rp <?= number_format($item['price'], 0, ',', '.') ?></h4>
                        <p class="text-muted">Selling Price</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Category:</th>
                                <td><span class="badge bg-info"><?= $item['category'] ?></span></td>
                            </tr>
                            <tr>
                                <th>Unit:</th>
                                <td><?= $item['unit'] ?></td>
                            </tr>
                            <tr>
                                <th>Cost Price:</th>
                                <td>Rp <?= number_format($item['cost_price'], 0, ',', '.') ?></td>
                            </tr>
                            <tr>
                                <th>Description:</th>
                                <td><?= $item['description'] ?: '<em class="text-muted">No description</em>' ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Current Stock:</th>
                                <td>
                                    <span class="fw-bold fs-5 <?= $item['stock'] <= $item['min_stock'] ? 'text-danger' : 'text-success' ?>">
                                        <?= number_format($item['stock']) ?> <?= $item['unit'] ?>
                                    </span>
                                    <?php if ($item['stock'] <= $item['min_stock']): ?>
                                        <i class="fas fa-exclamation-triangle text-danger" title="Low Stock"></i>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Minimum Stock:</th>
                                <td><?= number_format($item['min_stock']) ?> <?= $item['unit'] ?></td>
                            </tr>
                            <tr>
                                <th>Maximum Stock:</th>
                                <td><?= $item['max_stock'] ? number_format($item['max_stock']) . ' ' . $item['unit'] : '<em class="text-muted">Not set</em>' ?></td>
                            </tr>
                            <tr>
                                <th>Stock Status:</th>
                                <td>
                                    <?php if ($item['stock'] == 0): ?>
                                        <span class="badge bg-danger">Out of Stock</span>
                                    <?php elseif ($item['stock'] <= $item['min_stock']): ?>
                                        <span class="badge bg-warning">Low Stock</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">In Stock</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Stock Alert Card -->
        <?php if ($item['stock'] <= $item['min_stock']): ?>
        <div class="card border-left-danger shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-danger">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    Stock Alert
                </h6>
            </div>
            <div class="card-body">
                <p>Current stock is below minimum level!</p>
                <div class="progress mb-2">
                    <div class="progress-bar bg-danger" role="progressbar" 
                         style="width: <?= min(($item['stock'] / max($item['min_stock'], 1)) * 100, 100) ?>%">
                    </div>
                </div>
                <small class="text-muted">
                    Stock: <?= $item['stock'] ?> / Min: <?= $item['min_stock'] ?>
                </small>
            </div>
        </div>
        <?php endif; ?>

        <!-- Quick Actions Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= base_url('/items/edit/' . $item['id']) ?>" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i> Edit Item
                    </a>
                    <a href="<?= base_url('/purchase-orders/create') ?>?item_id=<?= $item['id'] ?>" class="btn btn-success">
                        <i class="fas fa-shopping-cart me-1"></i> Purchase Order
                    </a>
                    <a href="<?= base_url('/sales-orders/create') ?>?item_id=<?= $item['id'] ?>" class="btn btn-info">
                        <i class="fas fa-truck me-1"></i> Sales Order
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
                    <?= date('d M Y H:i', strtotime($item['created_at'])) ?></p>
                    
                    <p><strong>Last Updated:</strong><br>
                    <?= date('d M Y H:i', strtotime($item['updated_at'])) ?></p>
                    
                    <?php if ($item['created_by']): ?>
                    <p><strong>Created By:</strong><br>
                    User ID: <?= $item['created_by'] ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>