<!-- Content akan di-render setelah header dan sidebar -->
<div class="container-fluid">
    
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-card bg-gradient-primary text-white rounded-3 p-4 shadow">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="mb-2">
                            <i class="fas fa-hand-wave me-2"></i>
                            Selamat Datang, <?= esc($userData['name']) ?>!
                        </h3>
                        <p class="mb-0 opacity-75">
                            <?= date('l, d F Y') ?> - Selamat bekerja di Inventory System
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="bg-white bg-opacity-25 rounded p-3 d-inline-block">
                            <i class="fas fa-user-shield fa-2x"></i>
                            <div class="mt-1 fw-semibold"><?= ucfirst($userData['role']) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Total Items -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                Total Items
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800"><?= number_format($total_items) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cubes fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Suppliers -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                Total Suppliers
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800"><?= number_format($total_suppliers) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-truck fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-info text-uppercase mb-1">
                                Total Customers
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800"><?= number_format($total_customers) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                                Low Stock Items
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800"><?= number_format($low_stock_count) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Purchase & Sales Overview -->
    <div class="row mb-4">
        <!-- Purchase Overview -->
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-primary text-white py-3">
                    <h6 class="m-0 fw-bold">
                        <i class="fas fa-shopping-cart me-2"></i>Purchase Overview
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="border-end">
                                <div class="text-primary fw-bold h5"><?= number_format($pending_po_count) ?></div>
                                <small class="text-muted">Pending PO</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border-end">
                                <div class="text-success fw-bold h5"><?= number_format($approved_po_count) ?></div>
                                <small class="text-muted">Approved PO</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-info fw-bold h5"><?= $this->formatCurrency($total_po_amount) ?></div>
                            <small class="text-muted">Total Value</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Overview -->
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-success text-white py-3">
                    <h6 class="m-0 fw-bold">
                        <i class="fas fa-chart-line me-2"></i>Sales Overview
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="border-end">
                                <div class="text-warning fw-bold h5"><?= number_format($pending_so_count) ?></div>
                                <small class="text-muted">Pending SO</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border-end">
                                <div class="text-success fw-bold h5"><?= number_format($approved_so_count) ?></div>
                                <small class="text-muted">Approved SO</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-info fw-bold h5"><?= $this->formatCurrency($total_so_amount) ?></div>
                            <small class="text-muted">Total Value</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Low Stock Alert -->
    <div class="row mb-4">
        <!-- Quick Actions -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($quick_actions as $action): ?>
                            <div class="col-md-6 mb-3">
                                <a href="<?= base_url($action['url']) ?>" class="card action-card text-decoration-none">
                                    <div class="card-body text-center p-3">
                                        <div class="text-<?= $action['color'] ?> mb-2">
                                            <i class="<?= $action['icon'] ?> fa-2x"></i>
                                        </div>
                                        <h6 class="card-title text-dark"><?= $action['title'] ?></h6>
                                        <p class="card-text text-muted small"><?= $action['description'] ?></p>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-warning text-dark py-3">
                    <h6 class="m-0 fw-bold">
                        <i class="fas fa-exclamation-triangle me-2"></i>Low Stock Alert
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($low_stock_items)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach (array_slice($low_stock_items, 0, 5) as $item): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <h6 class="mb-1 small"><?= esc($item['name']) ?></h6>
                                        <small class="text-muted">Stock: <?= $item['quantity'] ?> | Min: <?= $item['min_stock'] ?></small>
                                    </div>
                                    <span class="badge bg-danger rounded-pill">!</span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (count($low_stock_items) > 5): ?>
                            <div class="text-center mt-2">
                                <a href="<?= base_url('/items') ?>" class="btn btn-sm btn-outline-warning">
                                    Lihat Semua <?= count($low_stock_items) ?> Items
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-check-circle fa-2x mb-2 text-success"></i>
                            <p class="mb-0">Semua stok dalam kondisi aman</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <!-- Recent Purchases -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-info text-white py-3">
                    <h6 class="m-0 fw-bold">
                        <i class="fas fa-shopping-cart me-2"></i>Recent Purchases
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($recent_purchases)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($recent_purchases as $purchase): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <h6 class="mb-1 small"><?= esc($purchase['po_number']) ?></h6>
                                        <small class="text-muted">Supplier: <?= esc($purchase['supplier_name']) ?></small>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-success"><?= $this->formatCurrency($purchase['total_amount']) ?></div>
                                        <small class="text-muted"><?= $this->formatDate($purchase['created_at']) ?></small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-info-circle fa-2x mb-2"></i>
                            <p class="mb-0">Belum ada purchase order</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Sales -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-success text-white py-3">
                    <h6 class="m-0 fw-bold">
                        <i class="fas fa-chart-line me-2"></i>Recent Sales
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($recent_sales)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($recent_sales as $sale): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <h6 class="mb-1 small"><?= esc($sale['so_number']) ?></h6>
                                        <small class="text-muted">Customer: <?= esc($sale['customer_name']) ?></small>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-success"><?= $this->formatCurrency($sale['total_amount']) ?></div>
                                        <small class="text-muted"><?= $this->formatDate($sale['created_at']) ?></small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-info-circle fa-2x mb-2"></i>
                            <p class="mb-0">Belum ada sales order</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Custom CSS untuk Dashboard -->
<style>
.welcome-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.action-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    border: 1px solid #e3e6f0;
}

.action-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important;
}

.card {
    border: none;
    border-radius: 0.5rem;
}

.card-header {
    border-radius: 0.5rem 0.5rem 0 0 !important;
}

.border-start-primary { border-left: 4px solid #4e73df !important; }
.border-start-success { border-left: 4px solid #1cc88a !important; }
.border-start-info { border-left: 4px solid #36b9cc !important; }
.border-start-warning { border-left: 4px solid #f6c23e !important; }

.list-group-item {
    border: none;
    padding-left: 0;
    padding-right: 0;
}
</style>

<!-- JavaScript untuk Auto Refresh Stats -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto refresh stats every 60 seconds
    setInterval(function() {
        fetch('/dashboard/getDashboardStats')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update stats here if needed
                    console.log('Stats updated', data.data);
                }
            })
            .catch(error => console.error('Error updating stats:', error));
    }, 60000);
});
</script>