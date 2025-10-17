<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('title') ?>Manage Items<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Manage Items</h1>
    <a href="<?= base_url('/items/create') ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Add New Item
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
        <h6 class="m-0 font-weight-bold text-primary">Items List</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Unit</th>
                        <th>Stock</th>
                        <th>Min Stock</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td>
                            <strong><?= $item['code'] ?></strong>
                        </td>
                        <td><?= $item['name'] ?></td>
                        <td>
                            <span class="badge bg-info"><?= $item['category'] ?></span>
                        </td>
                        <td><?= $item['unit'] ?></td>
                        <td>
                            <span class="fw-bold <?= $item['stock'] <= $item['min_stock'] ? 'text-danger' : 'text-success' ?>">
                                <?= number_format($item['stock']) ?>
                            </span>
                            <?php if ($item['stock'] <= $item['min_stock']): ?>
                                <i class="fas fa-exclamation-triangle text-danger" title="Low Stock"></i>
                            <?php endif; ?>
                        </td>
                        <td><?= number_format($item['min_stock']) ?></td>
                        <td class="text-nowrap">
                            <strong>Rp <?= number_format($item['price'], 0, ',', '.') ?></strong>
                        </td>
                        <td>
                            <span class="badge bg-<?= $item['is_active'] ? 'success' : 'danger' ?>">
                                <?= $item['is_active'] ? 'Active' : 'Inactive' ?>
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="<?= base_url('/items/view/' . $item['id']) ?>" class="btn btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?= base_url('/items/edit/' . $item['id']) ?>" class="btn btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= base_url('/items/delete/' . $item['id']) ?>" class="btn btn-danger" title="Delete" 
                                   onclick="return confirm('Are you sure you want to delete this item?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
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