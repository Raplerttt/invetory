<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('title') ?>Add New Item<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Add New Item</h1>
    <a href="<?= base_url('/items') ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Items
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Item Information</h6>
            </div>
            <div class="card-body">
                <?php if (isset($validation)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $validation->listErrors() ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('/items/store') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Item Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('name')) ? 'is-invalid' : '' ?>" 
                                   name="name" value="<?= old('name') ?>" required>
                            <?php if (isset($validation) && $validation->hasError('name')): ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('name') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Item Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('code')) ? 'is-invalid' : '' ?>" 
                                   name="code" value="<?= old('code') ?>" required>
                            <?php if (isset($validation) && $validation->hasError('code')): ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('code') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-control <?= (isset($validation) && $validation->hasError('category')) ? 'is-invalid' : '' ?>" 
                                    name="category" required>
                                <option value="">Select Category</option>
                                <option value="Electronics" <?= old('category') == 'Electronics' ? 'selected' : '' ?>>Electronics</option>
                                <option value="Furniture" <?= old('category') == 'Furniture' ? 'selected' : '' ?>>Furniture</option>
                                <option value="Office Supplies" <?= old('category') == 'Office Supplies' ? 'selected' : '' ?>>Office Supplies</option>
                                <option value="Tools" <?= old('category') == 'Tools' ? 'selected' : '' ?>>Tools</option>
                                <option value="Other" <?= old('category') == 'Other' ? 'selected' : '' ?>>Other</option>
                            </select>
                            <?php if (isset($validation) && $validation->hasError('category')): ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('category') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Unit <span class="text-danger">*</span></label>
                            <select class="form-control <?= (isset($validation) && $validation->hasError('unit')) ? 'is-invalid' : '' ?>" 
                                    name="unit" required>
                                <option value="">Select Unit</option>
                                <option value="pcs" <?= old('unit') == 'pcs' ? 'selected' : '' ?>>Pieces</option>
                                <option value="unit" <?= old('unit') == 'unit' ? 'selected' : '' ?>>Unit</option>
                                <option value="box" <?= old('unit') == 'box' ? 'selected' : '' ?>>Box</option>
                                <option value="pack" <?= old('unit') == 'pack' ? 'selected' : '' ?>>Pack</option>
                                <option value="set" <?= old('unit') == 'set' ? 'selected' : '' ?>>Set</option>
                                <option value="kg" <?= old('unit') == 'kg' ? 'selected' : '' ?>>Kilogram</option>
                                <option value="meter" <?= old('unit') == 'meter' ? 'selected' : '' ?>>Meter</option>
                            </select>
                            <?php if (isset($validation) && $validation->hasError('unit')): ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('unit') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Item description..."><?= old('description') ?></textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Selling Price <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" step="0.01" class="form-control <?= (isset($validation) && $validation->hasError('price')) ? 'is-invalid' : '' ?>" 
                                       name="price" value="<?= old('price') ?>" required>
                            </div>
                            <?php if (isset($validation) && $validation->hasError('price')): ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('price') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Cost Price</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" step="0.01" class="form-control" name="cost_price" value="<?= old('cost_price') ?>" placeholder="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Initial Stock</label>
                            <input type="number" class="form-control" name="stock" value="<?= old('stock', 0) ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Minimum Stock</label>
                            <input type="number" class="form-control" name="min_stock" value="<?= old('min_stock', 0) ?>">
                            <small class="form-text text-muted">Alert when stock below this level</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Maximum Stock</label>
                            <input type="number" class="form-control" name="max_stock" value="<?= old('max_stock') ?>">
                            <small class="form-text text-muted">Optional - for reference only</small>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="reset" class="btn btn-secondary">Reset</button>
                        <button type="submit" class="btn btn-primary">Save Item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quick Info</h6>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <i class="fas fa-box fa-3x text-primary mb-3"></i>
                    <h5>Add New Item</h5>
                    <p class="text-muted">Fill in the item details carefully. Item code must be unique.</p>
                </div>
                <hr>
                <div class="small">
                    <p><strong>Tips:</strong></p>
                    <ul>
                        <li>Use descriptive item names</li>
                        <li>Set minimum stock for alerts</li>
                        <li>Choose appropriate category</li>
                        <li>Ensure unique item code</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>