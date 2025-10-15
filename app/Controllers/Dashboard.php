<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\PurchaseOrderModel;
use App\Models\SalesOrderModel;
use App\Models\SupplierModel;
use App\Models\CustomerModel;

class Dashboard extends BaseController
{
    protected $itemModel;
    protected $purchaseOrderModel;
    protected $salesOrderModel;
    protected $supplierModel;
    protected $customerModel;

    public function __construct()
    {
        $this->itemModel = new ItemModel();
        $this->purchaseOrderModel = new PurchaseOrderModel();
        $this->salesOrderModel = new SalesOrderModel();
        $this->supplierModel = new SupplierModel();
        $this->customerModel = new CustomerModel();
    }

    public function index()
    {
        // Check authentication
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        // Set page title
        $this->setPageTitle('Dashboard');

        // Add breadcrumbs
        $this->addBreadcrumb('Dashboard', '/dashboard');

        // Get dashboard data
        $data = $this->getDashboardData();

        // Log activity
        $this->logActivity('dashboard_view', 'User accessed dashboard');

        // Render view
        return $this->render('dashboard/index', $data);
    }

    public function profile()
    {
        // Check authentication
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $this->setPageTitle('Profile');
        $this->addBreadcrumb('Dashboard', '/dashboard');
        $this->addBreadcrumb('Profile');

        return $this->render('dashboard/profile');
    }

    public function updateProfile()
    {
        // Check authentication
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'name' => 'required|min_length[3]|max_length[100]',
                'email' => 'required|valid_email'
            ];

            if ($this->validateForm($rules)) {
                // Update profile logic here
                $this->session->setFlashdata('success', 'Profile updated successfully');
                $this->logActivity('profile_update', 'User updated profile information');
                return redirect()->to('/dashboard/profile');
            } else {
                return redirect()->back()->withInput()->with('errors', $this->getValidationErrors());
            }
        }

        return redirect()->back();
    }

    public function getDashboardStats()
    {
        // Check authentication
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        if ($this->request->isAJAX()) {
            $stats = $this->getDashboardData();
            return $this->successResponse($stats, 'Dashboard stats retrieved successfully');
        }

        return $this->errorResponse('Invalid request');
    }

    private function getDashboardData()
    {
        $data = [];

        try {
            // Basic statistics
            $data['total_items'] = $this->itemModel->countAll();
            $data['total_suppliers'] = $this->supplierModel->countAll();
            $data['total_customers'] = $this->customerModel->countAll();

            // Purchase statistics
            $data['pending_po_count'] = $this->purchaseOrderModel->where('status', 'pending')->countAllResults();
            $data['approved_po_count'] = $this->purchaseOrderModel->where('status', 'approved')->countAllResults();
            $data['total_po_amount'] = $this->purchaseOrderModel->selectSum('total_amount')->where('status', 'approved')->get()->getRow()->total_amount ?? 0;

            // Sales statistics
            $data['pending_so_count'] = $this->salesOrderModel->where('status', 'pending')->countAllResults();
            $data['approved_so_count'] = $this->salesOrderModel->where('status', 'approved')->countAllResults();
            $data['total_so_amount'] = $this->salesOrderModel->selectSum('total_amount')->where('status', 'approved')->get()->getRow()->total_amount ?? 0;

            // Low stock items
            $data['low_stock_items'] = $this->itemModel->getLowStockItems();
            $data['low_stock_count'] = count($data['low_stock_items']);

            // Recent activities
            $data['recent_purchases'] = $this->purchaseOrderModel->getRecentPurchases(5);
            $data['recent_sales'] = $this->salesOrderModel->getRecentSales(5);

            // Monthly chart data (dummy data for now)
            $data['monthly_sales'] = $this->getMonthlySalesData();
            $data['monthly_purchases'] = $this->getMonthlyPurchasesData();

            // Quick actions based on role
            $data['quick_actions'] = $this->getQuickActions();

            // Pending approvals for admin
            if ($this->hasRole('admin')) {
                $data['pending_approvals'] = $this->purchaseOrderModel->where('status', 'pending')->countAllResults();
            }

        } catch (\Exception $e) {
            log_message('error', 'Error getting dashboard data: ' . $e->getMessage());
            // Set default values if there's an error
            $data = array_merge($data, [
                'total_items' => 0,
                'total_suppliers' => 0,
                'total_customers' => 0,
                'pending_po_count' => 0,
                'approved_po_count' => 0,
                'total_po_amount' => 0,
                'pending_so_count' => 0,
                'approved_so_count' => 0,
                'total_so_amount' => 0,
                'low_stock_items' => [],
                'low_stock_count' => 0,
                'recent_purchases' => [],
                'recent_sales' => [],
                'monthly_sales' => [],
                'monthly_purchases' => []
            ]);
        }

        return $data;
    }

    private function getMonthlySalesData()
    {
        // This is dummy data - in real application, you would query the database
        $currentYear = date('Y');
        $months = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $months[] = [
                'month' => date('M', mktime(0, 0, 0, $i, 1)),
                'sales' => rand(1000000, 5000000)
            ];
        }
        
        return $months;
    }

    private function getMonthlyPurchasesData()
    {
        // This is dummy data - in real application, you would query the database
        $currentYear = date('Y');
        $months = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $months[] = [
                'month' => date('M', mktime(0, 0, 0, $i, 1)),
                'purchases' => rand(800000, 4000000)
            ];
        }
        
        return $months;
    }

    private function getQuickActions()
    {
        $actions = [
            [
                'title' => 'Buat Purchase Order',
                'description' => 'Buat PO baru ke supplier',
                'icon' => 'fas fa-shopping-cart',
                'url' => '/purchase-orders/create',
                'color' => 'primary',
                'permission' => 'transaction_create'
            ],
            [
                'title' => 'Buat Sales Order',
                'description' => 'Buat SO baru untuk customer',
                'icon' => 'fas fa-file-invoice-dollar',
                'url' => '/sales-orders/create',
                'color' => 'success',
                'permission' => 'transaction_create'
            ],
            [
                'title' => 'Tambah Item Baru',
                'description' => 'Tambah produk/item baru ke inventory',
                'icon' => 'fas fa-cube',
                'url' => '/items/create',
                'color' => 'info',
                'permission' => 'master_create'
            ],
            [
                'title' => 'Lihat Laporan Stok',
                'description' => 'Lihat laporan stok terkini',
                'icon' => 'fas fa-chart-bar',
                'url' => '/reports/stock',
                'color' => 'warning',
                'permission' => 'reporting'
            ]
        ];

        // Filter actions based on user permissions
        return array_filter($actions, function($action) {
            return $this->hasPermission($action['permission']);
        });
    }
}