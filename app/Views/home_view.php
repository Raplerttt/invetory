<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Inventory System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <?= view('templates/navbar') ?>

    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Sidebar -->
            <?= view('templates/sidebar') ?>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                </div>

                <!-- Notifikasi Stok Minimum -->
                <?php if (!empty($low_stock_items)): ?>
                <div class="alert alert-warning">
                    <h5><i class="fas fa-exclamation-triangle"></i> Peringatan Stok Minimum</h5>
                    <ul class="mb-0">
                        <?php foreach ($low_stock_items as $item): ?>
                        <li><?= $item['name'] ?> - Stok: <?= $item['current_stock'] ?> (Minimum: <?= $item['min_stock'] ?>)</li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <!-- Statistik -->
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title"><?= $pending_po ?></h4>
                                        <p class="card-text">PO Menunggu</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-shopping-cart fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title"><?= $pending_so ?></h4>
                                        <p class="card-text">SO Menunggu</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-truck fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h4>Quick Actions</h4>
                        <div class="d-grid gap-2 d-md-flex">
                            <a href="/purchase-orders/create" class="btn btn-primary me-md-2">
                                <i class="fas fa-plus"></i> Buat PO Baru
                            </a>
                            <a href="/sales-orders/create" class="btn btn-success">
                                <i class="fas fa-plus"></i> Buat SO Baru
                            </a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>