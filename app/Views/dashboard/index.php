<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    <div class="d-none d-sm-inline-block">
        <span class="badge bg-primary">
            <i class="fas fa-user me-1"></i>
            <?= session()->get('name') ?> (<?= session()->get('role') ?>)
        </span>
        <span class="badge bg-secondary ms-2">
            <i class="fas fa-calendar me-1"></i>
            <?= date('d F Y') ?>
        </span>
    </div>
</div>

<!-- Flash Message -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Statistics Cards -->
<div class="row">
    <!-- Total Items -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Items
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= number_format($stats['total_items']) ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-boxes fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Sales -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Penjualan Bulan Ini
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Rp <?= number_format($stats['monthly_sales'], 0, ',', '.') ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Purchase -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Pembelian Bulan Ini
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Rp <?= number_format($stats['monthly_purchase'], 0, ',', '.') ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Orders -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Order Pending
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                    <?= $stats['pending_po'] + $stats['pending_so'] ?>
                                </div>
                            </div>
                            <div class="col">
                                <div class="progress progress-sm mr-2">
                                    <div class="progress-bar bg-warning" role="progressbar" 
                                         style="width: <?= min(($stats['pending_po'] + $stats['pending_so']) * 10, 100) ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Chart -->
    <div class="col-xl-8 col-lg-7">
        <!-- Sales & Purchase Chart -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Grafik Penjualan & Pembelian Tahun <?= date('Y') ?></h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="salesPurchaseChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
<!-- Recent Activities -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Aktivitas Terbaru</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-borderless table-hover">
                <thead>
                    <tr>
                        <th>Aktivitas</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentActivities as $activity): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="<?= $activity['icon'] ?> text-<?= $activity['color'] ?>"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="fw-bold"><?= $activity['title'] ?></div>
                                    <small class="text-muted"><?= $activity['description'] ?></small>
                                </div>
                            </div>
                        </td>
                        <td class="text-nowrap">
            <strong>Rp <?= number_format($activity['amount'], 0, ',', '.') ?></strong>
</td>
                        <td>
                            <span class="badge bg-<?= getStatusBadgeColor($activity['status']) ?>">
                                <?= getStatusText($activity['status']) ?>
                            </span>
                        </td>
                        <td class="text-nowrap">
                            <small class="text-muted"><?= timeAgo($activity['time']) ?></small>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
    </div>

    <!-- Right Sidebar -->
    <div class="col-xl-4 col-lg-5">
        <!-- Low Stock Alert -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-danger">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    Stok Menipis
                </h6>
            </div>
            <div class="card-body">
                <?php if (!empty($lowStockItems)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($lowStockItems as $item): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <div class="fw-bold"><?= $item['name'] ?></div>
                                <small class="text-muted">Stok: <?= $item['current_stock'] ?></small>
                            </div>
                            <span class="badge bg-danger rounded-pill">
                                <i class="fas fa-exclamation"></i>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="mt-3 text-center">
                        <a href="<?= base_url('/items') ?>" class="btn btn-sm btn-outline-danger">
                            Kelola Stok
                        </a>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                        <p>Semua stok aman</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-warning">
                    <i class="fas fa-clock me-1"></i>
                    Order Menunggu
                </h6>
            </div>
            <div class="card-body">
                <h6 class="small font-weight-bold text-primary mb-2">Purchase Orders</h6>
                <?php foreach ($pendingOrders['purchase_orders'] as $po): ?>
                <div class="card mb-2 border-left-primary">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="fw-bold"><?= $po['po_number'] ?></div>
                                <small class="text-muted">Rp <?= number_format($po['total_amount'], 0, ',', '.') ?></small>
                            </div>
                            <a href="<?= base_url('/purchase-orders/view/' . $po['id']) ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

                <h6 class="small font-weight-bold text-success mb-2 mt-3">Sales Orders</h6>
                <?php foreach ($pendingOrders['sales_orders'] as $so): ?>
                <div class="card mb-2 border-left-success">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="fw-bold"><?= $so['so_number'] ?></div>
                                <small class="text-muted">Rp <?= number_format($so['total_amount'], 0, ',', '.') ?></small>
                            </div>
                            <a href="<?= base_url('/sales-orders/view/' . $so['id']) ?>" class="btn btn-sm btn-success">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-info">
                    <i class="fas fa-bolt me-1"></i>
                    Akses Cepat
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6">
                        <a href="<?= base_url('/purchase-orders/create') ?>" class="btn btn-outline-primary w-100 h-100 py-3">
                            <i class="fas fa-plus fa-2x mb-2"></i><br>
                            PO Baru
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?= base_url('/sales-orders/create') ?>" class="btn btn-outline-success w-100 h-100 py-3">
                            <i class="fas fa-plus fa-2x mb-2"></i><br>
                            SO Baru
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?= base_url('/items/create') ?>" class="btn btn-outline-info w-100 h-100 py-3">
                            <i class="fas fa-box fa-2x mb-2"></i><br>
                            Item Baru
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?= base_url('/reports') ?>" class="btn btn-outline-warning w-100 h-100 py-3">
                            <i class="fas fa-chart-bar fa-2x mb-2"></i><br>
                            Laporan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sales & Purchase Chart
    const ctx = document.getElementById('salesPurchaseChart').getContext('2d');
    const salesPurchaseChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode(array_column($monthlySales, 'month')) ?>,
            datasets: [
                {
                    label: 'Penjualan',
                    data: <?= json_encode(array_column($monthlySales, 'sales')) ?>,
                    borderColor: '#1cc88a',
                    backgroundColor: 'rgba(28, 200, 138, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Pembelian',
                    data: <?= json_encode(array_column($monthlySales, 'purchases')) ?>,
                    borderColor: '#36b9cc',
                    backgroundColor: 'rgba(54, 185, 204, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': Rp ' + context.raw.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    // Auto refresh stats every 30 seconds
    setInterval(function() {
        fetch('<?= base_url('/dashboard/get-stats') ?>')
            .then(response => response.json())
            .then(data => {
                // Update stats cards
                document.querySelectorAll('.card .h5')[0].textContent = data.total_items.toLocaleString('id-ID');
                document.querySelectorAll('.card .h5')[1].textContent = 'Rp ' + data.monthly_sales.toLocaleString('id-ID');
                document.querySelectorAll('.card .h5')[2].textContent = 'Rp ' + data.monthly_purchase.toLocaleString('id-ID');
                document.querySelectorAll('.card .h5')[3].textContent = (data.pending_po + data.pending_so).toString();
            })
            .catch(error => console.error('Error refreshing stats:', error));
    }, 30000);
});
</script>
<?= $this->endSection() ?>