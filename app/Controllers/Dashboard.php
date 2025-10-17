<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\ItemModel;
use App\Models\PurchaseOrderModel;
use App\Models\SalesOrderModel;
use App\Models\SupplierModel;
use App\Models\CustomerModel;

class Dashboard extends BaseController
{
    protected $userModel;
    protected $itemModel;
    protected $purchaseOrderModel;
    protected $salesOrderModel;
    protected $supplierModel;
    protected $customerModel;


    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->itemModel = new ItemModel();
        $this->purchaseOrderModel = new PurchaseOrderModel();
        $this->salesOrderModel = new SalesOrderModel();
        $this->supplierModel = new SupplierModel();
        $this->customerModel = new CustomerModel();
        helper(['number', 'form']);
    }

    public function index()
    {
        $data = [
            'title' => 'Dashboard',
            'user' => [
                'name' => session()->get('name'),
                'role' => session()->get('role')
            ],
            'stats' => $this->getDashboardStats(),
            'recentActivities' => $this->getRecentActivities(),
            'lowStockItems' => $this->getLowStockItems(),
            'pendingOrders' => $this->getPendingOrders(),
            'monthlySales' => $this->getMonthlySales(),
            'topProducts' => $this->getTopProducts()
        ];

        return view('dashboard/index', $data);
    }

    public function getDashboardStats()
    {
        $currentMonth = date('Y-m');
        $previousMonth = date('Y-m', strtotime('-1 month'));

        return [
            'total_items' => $this->itemModel->countAllResults(),
            'total_suppliers' => $this->supplierModel->countAllResults(),
            'total_customers' => $this->customerModel->countAllResults(),
            'total_purchase_orders' => $this->purchaseOrderModel->countAllResults(),
            'total_sales_orders' => $this->salesOrderModel->countAllResults(),
            'monthly_purchase' => $this->purchaseOrderModel->getMonthlyTotal($currentMonth),
            'monthly_sales' => $this->salesOrderModel->getMonthlyTotal($currentMonth),
            'pending_po' => 5, // dummy data
            'pending_so' => 3  // dummy data
        ];
    }

    public function getRecentActivities()
    {
        // Gabungkan aktivitas terbaru dari berbagai modul
        $activities = [];

        // Recent Purchase Orders
        $recentPO = $this->purchaseOrderModel->findAll(3);

        foreach ($recentPO as $po) {
            $activities[] = [
                'type' => 'purchase',
                'icon' => 'fas fa-shopping-cart',
                'color' => 'primary',
                'title' => 'PO Baru: ' . $po['po_number'],
                'description' => 'Supplier: ' . ($po['supplier_name'] ?? 'Unknown'),
                'amount' => $po['total_amount'],
                'time' => $po['created_at'],
                'status' => $po['status']
            ];
        }

        // Recent Sales Orders
        $recentSO = $this->salesOrderModel->findAll(3);

        foreach ($recentSO as $so) {
            $activities[] = [
                'type' => 'sales',
                'icon' => 'fas fa-truck',
                'color' => 'success',
                'title' => 'SO Baru: ' . $so['so_number'],
                'description' => 'Customer: ' . ($so['customer_name'] ?? 'Unknown'),
                'amount' => $so['total_amount'],
                'time' => $so['created_at'],
                'status' => $so['status']
            ];
        }

        // Tambahkan beberapa aktivitas dummy
        $activities[] = [
            'type' => 'stock',
            'icon' => 'fas fa-boxes',
            'color' => 'info',
            'title' => 'Stok Update: Laptop Dell',
            'description' => 'Penambahan stok 10 unit',
            'amount' => 0,
            'time' => date('Y-m-d H:i:s', strtotime('-30 minutes')),
            'status' => 'completed'
        ];

        $activities[] = [
            'type' => 'system',
            'icon' => 'fas fa-cog',
            'color' => 'warning',
            'title' => 'Backup Database',
            'description' => 'Backup otomatis sistem',
            'amount' => 0,
            'time' => date('Y-m-d H:i:s', strtotime('-2 hours')),
            'status' => 'completed'
        ];

        // Urutkan berdasarkan waktu terbaru
        usort($activities, function($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });

        return array_slice($activities, 0, 6); // Ambil 6 terbaru
    }

// Di method getLowStockItems()
public function getLowStockItems()
{
    return $this->itemModel
        ->where('stock <= min_stock')
        ->where('stock > 0')
        ->where('is_active', true)
        ->orderBy('stock', 'ASC')
        ->findAll();
}

    public function getPendingOrders()
    {
        $pendingPO = [
            [
                'id' => 1,
                'po_number' => 'PO/2024/003',
                'total_amount' => 12500000,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
            ],
            [
                'id' => 2,
                'po_number' => 'PO/2024/004',
                'total_amount' => 8500000,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 hours'))
            ]
        ];

        $pendingSO = [
            [
                'id' => 1,
                'so_number' => 'SO/2024/003',
                'total_amount' => 9800000,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours'))
            ],
            [
                'id' => 2,
                'so_number' => 'SO/2024/004',
                'total_amount' => 15600000,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 hours'))
            ]
        ];

        return [
            'purchase_orders' => $pendingPO,
            'sales_orders' => $pendingSO
        ];
    }

    public function getMonthlySales()
    {
        $currentYear = date('Y');
        $monthlyData = [];

        for ($month = 1; $month <= 12; $month++) {
            $monthlyData[] = [
                'month' => date('M', mktime(0, 0, 0, $month, 1)),
                'sales' => rand(5000000, 25000000),
                'purchases' => rand(3000000, 20000000)
            ];
        }

        return $monthlyData;
    }

    public function getTopProducts()
    {
        return [
            ['name' => 'Laptop Dell XPS 13', 'sales' => 150, 'revenue' => 2250000000],
            ['name' => 'Monitor 24 inch', 'sales' => 120, 'revenue' => 300000000],
            ['name' => 'Keyboard Mechanical', 'sales' => 95, 'revenue' => 80750000],
            ['name' => 'Mouse Wireless', 'sales' => 80, 'revenue' => 24000000],
            ['name' => 'Webcam HD', 'sales' => 65, 'revenue' => 16250000]
        ];
    }

    public function profile()
    {
        $userId = session()->get('userId');
        $user = $this->userModel->find($userId);

        $data = [
            'title' => 'Profile',
            'user' => $user
        ];

        return view('dashboard/profile', $data);
    }

    public function updateProfile()
    {
        $userId = session()->get('userId');
        $user = $this->userModel->find($userId);

        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'permit_empty|valid_email'
        ];

        // Jika username berubah
        if ($this->request->getPost('username') !== $user['username']) {
            $rules['username'] = 'required|min_length[3]|max_length[20]|is_unique[users.username]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email')
        ];

        // Update username jika berubah
        if ($this->request->getPost('username') !== $user['username']) {
            $data['username'] = $this->request->getPost('username');
        }

        // Update password jika diisi
        if ($this->request->getPost('password')) {
            $data['password'] = $this->request->getPost('password');
        }

        $this->userModel->update($userId, $data);

        // Update session
        session()->set('name', $data['name']);
        if (isset($data['username'])) {
            session()->set('username', $data['username']);
        }

        return redirect()->to('/dashboard/profile')->with('success', 'Profile berhasil diperbarui!');
    }

    // API untuk get stats real-time
    public function getDashboardStatsApi()
    {
        return $this->response->setJSON($this->getDashboardStats());
    }

    public function settings()
    {
        $data = [
            'title' => 'Settings'
        ];

        return view('dashboard/settings', $data);
    }
}