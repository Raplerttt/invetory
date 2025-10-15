<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\Auth;
use App\Controllers\Dashboard;
use App\Controllers\Items;
use App\Controllers\Suppliers;
use App\Controllers\Customers;
use App\Controllers\PurchaseOrders;
use App\Controllers\GoodsReceipts;
use App\Controllers\SalesOrders;
use App\Controllers\Deliveries;
use App\Controllers\Reports;
use App\Controllers\TestConnection;

/**
 * @var RouteCollection $routes
 */

// Public Routes
$routes->get('/', 'Auth::login');
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::login');
$routes->get('/logout', 'Auth::logout');

// Test Routes (bisa dihapus di production)
$routes->get('test-db', 'TestConnection::index');
$routes->get('test-env', 'TestConnection::checkEnv');

// Protected Routes - dengan filter auth
$routes->group('', ['filter' => 'auth'], function($routes) {
    
    // Dashboard
    $routes->get('/dashboard', 'Dashboard::index');
    $routes->get('/dashboard/profile', 'Dashboard::profile');
    $routes->post('/dashboard/update-profile', 'Dashboard::updateProfile');
    $routes->get('/dashboard/get-stats', 'Dashboard::getDashboardStats');
    
    // Master Data - Items
    $routes->group('items', function($routes) {
        $routes->get('/', 'Items::index');
        $routes->get('create', 'Items::create');
        $routes->post('store', 'Items::store');
        $routes->get('edit/(:num)', 'Items::edit/$1');
        $routes->post('update/(:num)', 'Items::update/$1');
        $routes->get('delete/(:num)', 'Items::delete/$1');
        $routes->get('view/(:num)', 'Items::view/$1');
        $routes->get('get-stock/(:num)', 'Items::getStock/$1'); // API untuk cek stok
    });
    
    // Master Data - Suppliers
    $routes->group('suppliers', function($routes) {
        $routes->get('/', 'Suppliers::index');
        $routes->get('create', 'Suppliers::create');
        $routes->post('store', 'Suppliers::store');
        $routes->get('edit/(:num)', 'Suppliers::edit/$1');
        $routes->post('update/(:num)', 'Suppliers::update/$1');
        $routes->get('delete/(:num)', 'Suppliers::delete/$1');
        $routes->get('view/(:num)', 'Suppliers::view/$1');
    });
    
    // Master Data - Customers
    $routes->group('customers', function($routes) {
        $routes->get('/', 'Customers::index');
        $routes->get('create', 'Customers::create');
        $routes->post('store', 'Customers::store');
        $routes->get('edit/(:num)', 'Customers::edit/$1');
        $routes->post('update/(:num)', 'Customers::update/$1');
        $routes->get('delete/(:num)', 'Customers::delete/$1');
        $routes->get('view/(:num)', 'Customers::view/$1');
    });
    
    // Purchase Orders
    $routes->group('purchase-orders', function($routes) {
        $routes->get('/', 'PurchaseOrders::index');
        $routes->get('create', 'PurchaseOrders::create');
        $routes->post('store', 'PurchaseOrders::store');
        $routes->get('view/(:num)', 'PurchaseOrders::view/$1');
        $routes->get('edit/(:num)', 'PurchaseOrders::edit/$1');
        $routes->post('update/(:num)', 'PurchaseOrders::update/$1');
        $routes->get('print/(:num)', 'PurchaseOrders::print/$1');
        
        // Approval routes - dengan filter role admin
        $routes->group('', ['filter' => 'role:admin'], function($routes) {
            $routes->post('approve/(:num)', 'PurchaseOrders::approve/$1');
            $routes->post('reject/(:num)', 'PurchaseOrders::reject/$1');
        });
        
        // Staff hanya bisa approve PO < 10jt
        $routes->post('staff-approve/(:num)', 'PurchaseOrders::staffApprove/$1');
    });
    
    // Goods Receipt Notes
    $routes->group('goods-receipts', function($routes) {
        $routes->get('/', 'GoodsReceipts::index');
        $routes->get('create/(:num)', 'GoodsReceipts::create/$1'); // dari PO
        $routes->post('store', 'GoodsReceipts::store');
        $routes->get('view/(:num)', 'GoodsReceipts::view/$1');
        $routes->get('print/(:num)', 'GoodsReceipts::print/$1');
        
        // Approval routes
        $routes->group('', ['filter' => 'role:admin'], function($routes) {
            $routes->post('approve/(:num)', 'GoodsReceipts::approve/$1');
            $routes->post('reject/(:num)', 'GoodsReceipts::reject/$1');
        });
    });
    
    // Sales Orders
    $routes->group('sales-orders', function($routes) {
        $routes->get('/', 'SalesOrders::index');
        $routes->get('create', 'SalesOrders::create');
        $routes->post('store', 'SalesOrders::store');
        $routes->get('view/(:num)', 'SalesOrders::view/$1');
        $routes->get('edit/(:num)', 'SalesOrders::edit/$1');
        $routes->post('update/(:num)', 'SalesOrders::update/$1');
        $routes->get('print/(:num)', 'SalesOrders::print/$1');
        
        // Approval routes
        $routes->group('', ['filter' => 'role:admin'], function($routes) {
            $routes->post('approve/(:num)', 'SalesOrders::approve/$1');
            $routes->post('reject/(:num)', 'SalesOrders::reject/$1');
        });
    });
    
    // Deliveries
    $routes->group('deliveries', function($routes) {
        $routes->get('/', 'Deliveries::index');
        $routes->get('create/(:num)', 'Deliveries::create/$1'); // dari SO
        $routes->post('store', 'Deliveries::store');
        $routes->get('view/(:num)', 'Deliveries::view/$1');
        $routes->get('print/(:num)', 'Deliveries::print/$1');
        
        // Approval routes
        $routes->group('', ['filter' => 'role:admin'], function($routes) {
            $routes->post('approve/(:num)', 'Deliveries::approve/$1');
            $routes->post('reject/(:num)', 'Deliveries::reject/$1');
        });
    });
    
    // Reports
    $routes->group('reports', function($routes) {
        $routes->get('/', 'Reports::index');
        $routes->get('stock', 'Reports::stock');
        $routes->get('stock-card', 'Reports::stockCard');
        $routes->get('stock-movement', 'Reports::stockMovement');
        $routes->get('top-products', 'Reports::topProducts');
        $routes->get('aging-stock', 'Reports::agingStock');
        $routes->get('purchase-report', 'Reports::purchaseReport');
        $routes->get('sales-report', 'Reports::salesReport');
        
        // Export routes
        $routes->get('export-stock', 'Reports::exportStock');
        $routes->get('export-stock-card', 'Reports::exportStockCard');
        $routes->get('export-sales', 'Reports::exportSales');
    });
    
    // API Routes untuk AJAX
    $routes->group('api', function($routes) {
        $routes->get('item-stock/(:num)', 'Items::getItemStock/$1');
        $routes->get('supplier-items/(:num)', 'PurchaseOrders::getSupplierItems/$1');
        $routes->get('customer-items/(:num)', 'SalesOrders::getCustomerItems/$1');
        $routes->get('check-stock', 'Items::checkStock');
    });
    
    // Profile & Settings
    $routes->get('profile', 'Dashboard::profile');
    $routes->post('profile/update', 'Dashboard::updateProfile');
    $routes->get('settings', 'Dashboard::settings');
});

// Catch all - 404
$routes->set404Override(function() {
    return view('errors/404');
});

// Maintenance mode (optional)
// $routes->setAutoRoute(false);