<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> - Inventory System</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom Styles -->
    <style>
        :root {
            --primary: #4e73df;
            --success: #1cc88a;
            --info: #36b9cc;
            --warning: #f6c23e;
            --danger: #e74a3b;
            --secondary: #858796;
            --light: #f8f9fc;
            --dark: #5a5c69;
        }
        
        body {
            background-color: #f8f9fc;
        }
        
        .sidebar {
            background: linear-gradient(180deg, var(--primary) 0%, #224abe 100%);
            min-height: 100vh;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 1rem;
            margin: 0.2rem 0;
            border-left: 3px solid transparent;
        }
        
        .sidebar .nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
            border-left: 3px solid rgba(255, 255, 255, 0.5);
        }
        
        .sidebar .nav-link.active {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
            border-left: 3px solid #fff;
        }
        
        .sidebar .nav-link i {
            margin-right: 0.5rem;
        }
        
        .navbar {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .card {
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            margin-bottom: 1.5rem;
        }
        
        .border-left-primary { border-left: 0.25rem solid var(--primary) !important; }
        .border-left-success { border-left: 0.25rem solid var(--success) !important; }
        .border-left-info { border-left: 0.25rem solid var(--info) !important; }
        .border-left-warning { border-left: 0.25rem solid var(--warning) !important; }
        .border-left-danger { border-left: 0.25rem solid var(--danger) !important; }
        
        .bg-gradient-primary { background: linear-gradient(180deg, var(--primary) 10%, #224abe 100%); }
        
        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }
        }
    </style>
    
    <?= $this->renderSection('styles') ?>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container-fluid">
            <!-- Sidebar Toggle -->
            <button class="btn btn-link text-dark d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar">
                <i class="fas fa-bars"></i>
            </button>
            
            <!-- Brand -->
            <a class="navbar-brand fw-bold text-primary" href="<?= base_url('/dashboard') ?>">
                <i class="fas fa-warehouse me-2"></i>Inventory System
            </a>

            <!-- User Menu -->
            <div class="dropdown">
                <button class="btn btn-link text-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle me-1"></i>
                    <?= session()->get('name') ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="<?= base_url('/dashboard/profile') ?>">
                        <i class="fas fa-user me-2"></i>Profile
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="<?= base_url('/logout') ?>">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse" id="sidebar">
                <div class="position-sticky pt-3">
                    <!-- User Info -->
                    <div class="text-center text-white mb-4 mt-3">
                        <i class="fas fa-user-circle fa-3x mb-2"></i>
                        <h6 class="fw-bold"><?= session()->get('name') ?></h6>
                        <small class="text-light"><?= session()->get('role') ?></small>
                    </div>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="<?= base_url('/dashboard') ?>">
                                <i class="fas fa-tachometer-alt"></i>Dashboard
                            </a>
                        </li>
                        
                        <!-- Master Data -->
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="collapse" href="#masterData">
                                <i class="fas fa-database"></i>Master Data
                            </a>
                            <div class="collapse show" id="masterData">
                                <ul class="nav flex-column ms-3">
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?= base_url('/items') ?>">
                                            <i class="fas fa-box"></i>Items
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?= base_url('/suppliers') ?>">
                                            <i class="fas fa-truck-loading"></i>Suppliers
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?= base_url('/customers') ?>">
                                            <i class="fas fa-users"></i>Customers
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        
                        <!-- Purchase -->
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('/purchase-orders') ?>">
                                <i class="fas fa-shopping-cart"></i>Purchase Orders
                            </a>
                        </li>
                        
                        <!-- Sales -->
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('/sales-orders') ?>">
                                <i class="fas fa-truck"></i>Sales Orders
                            </a>
                        </li>
                        
                        <!-- Reports -->
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('/reports') ?>">
                                <i class="fas fa-chart-bar"></i>Reports
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>