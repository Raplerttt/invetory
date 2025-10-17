<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PurchaseOrderModel;
use App\Models\PurchaseOrderItemModel;
use App\Models\SupplierModel;
use App\Models\ItemModel;

class PurchaseOrders extends BaseController
{
    protected $purchaseOrderModel;
    protected $purchaseOrderItemModel;
    protected $supplierModel;
    protected $itemModel;

    public function __construct()
    {
        $this->purchaseOrderModel = new PurchaseOrderModel();
        $this->purchaseOrderItemModel = new PurchaseOrderItemModel();
        $this->supplierModel = new SupplierModel();
        $this->itemModel = new ItemModel();
        helper(['form', 'custom']);
    }

    public function index()
    {
        $data = [
            'title' => 'Purchase Orders',
            'purchaseOrders' => $this->purchaseOrderModel->getPOWithSupplier(),
            'user' => [
                'name' => session()->get('name'),
                'role' => session()->get('role')
            ]
        ];

        return view('purchase-orders/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Create Purchase Order',
            'suppliers' => $this->supplierModel->getActiveSuppliers(),
            'items' => $this->itemModel->getActiveItems(),
            'po_number' => $this->purchaseOrderModel->generatePONumber(),
            'validation' => \Config\Services::validation(),
            'user' => [
                'name' => session()->get('name'),
                'role' => session()->get('role')
            ]
        ];

        return view('purchase-orders/create', $data);
    }

    public function store()
    {
        // DEBUG: Tampilkan semua data POST
        log_message('debug', '=== PURCHASE ORDER STORE START ===');
        log_message('debug', 'POST Data: ' . print_r($this->request->getPost(), true));
        log_message('debug', 'Session userId: ' . session()->get('userId'));
        log_message('debug', 'Session data: ' . print_r(session()->get(), true));

        // Validate input
        $rules = [
            'po_number' => 'required|min_length[3]|max_length[100]|is_unique[purchase_orders.po_number]',
            'supplier_id' => 'required|integer',
            'order_date' => 'required|valid_date',
            'delivery_date' => 'permit_empty|valid_date',
            'items' => 'required'
        ];

        log_message('debug', 'Validation rules: ' . print_r($rules, true));

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            log_message('debug', 'Validation failed: ' . print_r($errors, true));
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        log_message('debug', 'Validation passed');

        // Start transaction
        $this->db->transStart();
        log_message('debug', 'Transaction started');

        try {
            // Create PO
            $poData = [
                'po_number' => $this->request->getPost('po_number'),
                'supplier_id' => $this->request->getPost('supplier_id'),
                'order_date' => $this->request->getPost('order_date'),
                'delivery_date' => $this->request->getPost('delivery_date'),
                'notes' => $this->request->getPost('notes'),
                'created_by' => session()->get('userId'),
                'status' => 'pending',
                'total_amount' => 0
            ];

            log_message('debug', 'PO Data to insert: ' . print_r($poData, true));

            $poId = $this->purchaseOrderModel->insert($poData);
            log_message('debug', 'PO Inserted with ID: ' . $poId);

            if (!$poId) {
                throw new \Exception('Failed to insert PO - no ID returned');
            }

            // Add PO items
            $items = $this->request->getPost('items');
            log_message('debug', 'Items data received: ' . print_r($items, true));

            $totalAmount = 0;
            $itemsProcessed = 0;

            if (is_array($items)) {
                foreach ($items as $index => $item) {
                    log_message('debug', "Processing item {$index}: " . print_r($item, true));
                    
                    // Cek apakah item valid
                    if (!empty($item['item_id']) && !empty($item['quantity']) && !empty($item['unit_price'])) {
                        $itemTotal = $item['quantity'] * $item['unit_price'];
                        
                        $itemData = [
                            'purchase_order_id' => $poId,
                            'item_id' => $item['item_id'],
                            'quantity' => $item['quantity'],
                            'unit_price' => $item['unit_price'],
                            'total_price' => $itemTotal
                        ];

                        log_message('debug', 'Item Data to insert: ' . print_r($itemData, true));

                        $itemInserted = $this->purchaseOrderItemModel->insert($itemData);
                        log_message('debug', 'Item Insert result: ' . $itemInserted);

                        if (!$itemInserted) {
                            throw new \Exception('Failed to insert PO item at index: ' . $index);
                        }

                        $totalAmount += $itemTotal;
                        $itemsProcessed++;
                    } else {
                        log_message('debug', "Item {$index} skipped - missing data");
                    }
                }
            } else {
                log_message('debug', 'Items is not an array or empty');
            }

            log_message('debug', 'Total items processed: ' . $itemsProcessed);
            log_message('debug', 'Total Amount calculated: ' . $totalAmount);

            // Update PO total amount
            log_message('debug', 'Updating PO total amount to: ' . $totalAmount);
            $updateResult = $this->purchaseOrderModel->update($poId, ['total_amount' => $totalAmount]);
            log_message('debug', 'PO Update result: ' . $updateResult);

            $this->db->transComplete();
            log_message('debug', 'Transaction completed successfully');

            log_message('debug', '=== PURCHASE ORDER STORE SUCCESS ===');
            return redirect()->to('/purchase-orders')->with('success', 'Purchase Order berhasil dibuat! ID: ' . $poId);

        } catch (\Exception $e) {
            log_message('error', 'Error in PO store: ' . $e->getMessage());
            log_message('debug', 'Error trace: ' . $e->getTraceAsString());
            $this->db->transRollback();
            log_message('debug', 'Transaction rolled back');
            
            log_message('debug', '=== PURCHASE ORDER STORE FAILED ===');
            return redirect()->back()->withInput()->with('error', 'Gagal membuat Purchase Order: ' . $e->getMessage());
        }
    }

    // TEST METHOD - Untuk debugging tanpa form
    public function testCreate()
    {
        log_message('debug', '=== TEST CREATE PO ===');
        
        // Test data sederhana
        $testData = [
            'po_number' => 'PO/TEST/' . time(),
            'supplier_id' => 1,
            'order_date' => date('Y-m-d'),
            'delivery_date' => date('Y-m-d', strtotime('+7 days')),
            'notes' => 'Test PO from debug method',
            'items' => [
                [
                    'item_id' => 1,
                    'quantity' => 5,
                    'unit_price' => 10000
                ]
            ]
        ];

        // Simulasikan POST request
        $_POST = $testData;
        
        log_message('debug', 'Test data: ' . print_r($testData, true));
        
        // Panggil store method
        return $this->store();
    }

    // SIMPLE STORE - Untuk test tanpa complex validation
    public function simpleStore()
    {
        log_message('debug', '=== SIMPLE STORE PO ===');
        
        try {
            $poData = [
                'po_number' => 'PO/SIMPLE/' . time(),
                'supplier_id' => 1,
                'order_date' => date('Y-m-d'),
                'delivery_date' => date('Y-m-d', strtotime('+7 days')),
                'notes' => 'Simple test PO',
                'created_by' => session()->get('userId'),
                'status' => 'pending',
                'total_amount' => 50000
            ];

            log_message('debug', 'Simple PO Data: ' . print_r($poData, true));
            
            $poId = $this->purchaseOrderModel->insert($poData);
            log_message('debug', 'Simple PO Inserted with ID: ' . $poId);

            if ($poId) {
                return redirect()->to('/purchase-orders')->with('success', 'Simple PO berhasil dibuat! ID: ' . $poId);
            } else {
                return redirect()->to('/purchase-orders')->with('error', 'Gagal membuat simple PO');
            }

        } catch (\Exception $e) {
            log_message('error', 'Error in simple store: ' . $e->getMessage());
            return redirect()->to('/purchase-orders')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function view($id)
    {
        log_message('debug', 'View PO ID: ' . $id);
        
        $purchaseOrder = $this->purchaseOrderModel->getPOWithSupplier($id);
        
        if (!$purchaseOrder) {
            log_message('debug', 'PO not found: ' . $id);
            return redirect()->to('/purchase-orders')->with('error', 'Purchase Order tidak ditemukan!');
        }

        $data = [
            'title' => 'View Purchase Order',
            'purchaseOrder' => $purchaseOrder,
            'poItems' => $this->purchaseOrderItemModel->getItemsByPO($id),
            'user' => [
                'name' => session()->get('name'),
                'role' => session()->get('role')
            ]
        ];

        return view('purchase-orders/view', $data);
    }

    public function edit($id)
    {
        $purchaseOrder = $this->purchaseOrderModel->find($id);
        
        if (!$purchaseOrder) {
            return redirect()->to('/purchase-orders')->with('error', 'Purchase Order tidak ditemukan!');
        }

        // Only allow editing for pending POs
        if ($purchaseOrder['status'] !== 'pending') {
            return redirect()->to('/purchase-orders')->with('error', 'Hanya PO dengan status pending yang bisa diedit!');
        }

        $data = [
            'title' => 'Edit Purchase Order',
            'purchaseOrder' => $purchaseOrder,
            'poItems' => $this->purchaseOrderItemModel->getItemsByPO($id),
            'suppliers' => $this->supplierModel->getActiveSuppliers(),
            'items' => $this->itemModel->getActiveItems(),
            'validation' => \Config\Services::validation(),
            'user' => [
                'name' => session()->get('name'),
                'role' => session()->get('role')
            ]
        ];

        return view('purchase-orders/edit', $data);
    }

    public function update($id)
    {
        $purchaseOrder = $this->purchaseOrderModel->find($id);
        
        if (!$purchaseOrder) {
            return redirect()->to('/purchase-orders')->with('error', 'Purchase Order tidak ditemukan!');
        }

        if ($purchaseOrder['status'] !== 'pending') {
            return redirect()->to('/purchase-orders')->with('error', 'Hanya PO dengan status pending yang bisa diedit!');
        }

        // Validate input
        $rules = [
            'supplier_id' => 'required|integer',
            'order_date' => 'required|valid_date',
            'delivery_date' => 'permit_empty|valid_date',
            'items' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        // Start transaction
        $this->db->transStart();

        try {
            // Update PO
            $poData = [
                'id' => $id,
                'supplier_id' => $this->request->getPost('supplier_id'),
                'order_date' => $this->request->getPost('order_date'),
                'delivery_date' => $this->request->getPost('delivery_date'),
                'notes' => $this->request->getPost('notes')
            ];

            $this->purchaseOrderModel->save($poData);

            // Delete existing items
            $this->purchaseOrderItemModel->deleteByPO($id);

            // Add new PO items
            $items = $this->request->getPost('items');
            $totalAmount = 0;

            foreach ($items as $item) {
                if (!empty($item['item_id']) && !empty($item['quantity']) && !empty($item['unit_price'])) {
                    $itemTotal = $item['quantity'] * $item['unit_price'];
                    
                    $itemData = [
                        'purchase_order_id' => $id,
                        'item_id' => $item['item_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'total_price' => $itemTotal
                    ];

                    $this->purchaseOrderItemModel->insert($itemData);
                    $totalAmount += $itemTotal;
                }
            }

            // Update PO total amount
            $this->purchaseOrderModel->update($id, ['total_amount' => $totalAmount]);

            $this->db->transComplete();

            return redirect()->to('/purchase-orders')->with('success', 'Purchase Order berhasil diperbarui!');

        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Purchase Order: ' . $e->getMessage());
        }
    }

    public function approve($id)
    {
        $purchaseOrder = $this->purchaseOrderModel->find($id);
        
        if (!$purchaseOrder) {
            return redirect()->to('/purchase-orders')->with('error', 'Purchase Order tidak ditemukan!');
        }

        $userRole = session()->get('role');
        $totalAmount = $purchaseOrder['total_amount'];

        // Hanya admin yang bisa approve PO >= 10jt
        if ($totalAmount >= 10000000 && $userRole !== 'admin') {
            return redirect()->to('/purchase-orders')->with('error', 'PO dengan nilai ≥ 10.000.000 hanya bisa disetujui oleh Admin!');
        }

        try {
            $this->purchaseOrderModel->updateStatus($id, 'approved', session()->get('userId'));
            return redirect()->to('/purchase-orders')->with('success', 'Purchase Order berhasil disetujui!');
        } catch (\Exception $e) {
            return redirect()->to('/purchase-orders')->with('error', 'Gagal menyetujui Purchase Order: ' . $e->getMessage());
        }
    }

    public function reject($id)
    {
        $purchaseOrder = $this->purchaseOrderModel->find($id);
        
        if (!$purchaseOrder) {
            return redirect()->to('/purchase-orders')->with('error', 'Purchase Order tidak ditemukan!');
        }

        $userRole = session()->get('role');
        $totalAmount = $purchaseOrder['total_amount'];

        // Hanya admin yang bisa reject PO >= 10jt
        if ($totalAmount >= 10000000 && $userRole !== 'admin') {
            return redirect()->to('/purchase-orders')->with('error', 'PO dengan nilai ≥ 10.000.000 hanya bisa ditolak oleh Admin!');
        }

        try {
            $this->purchaseOrderModel->updateStatus($id, 'rejected');
            return redirect()->to('/purchase-orders')->with('success', 'Purchase Order berhasil ditolak!');
        } catch (\Exception $e) {
            return redirect()->to('/purchase-orders')->with('error', 'Gagal menolak Purchase Order: ' . $e->getMessage());
        }
    }

    // Staff approval untuk PO < 10jt
    public function staffApprove($id)
    {
        $purchaseOrder = $this->purchaseOrderModel->find($id);
        
        if (!$purchaseOrder) {
            return redirect()->to('/purchase-orders')->with('error', 'Purchase Order tidak ditemukan!');
        }

        $userRole = session()->get('role');
        $totalAmount = $purchaseOrder['total_amount'];

        // Staff hanya bisa approve PO < 10jt
        if ($totalAmount >= 10000000) {
            return redirect()->to('/purchase-orders')->with('error', 'Staff hanya bisa menyetujui PO dengan nilai < 10.000.000!');
        }

        // Hanya staff role yang bisa menggunakan route ini
        if ($userRole !== 'staff') {
            return redirect()->to('/purchase-orders')->with('error', 'Akses ditolak!');
        }

        try {
            $this->purchaseOrderModel->updateStatus($id, 'approved', session()->get('userId'));
            return redirect()->to('/purchase-orders')->with('success', 'Purchase Order berhasil disetujui!');
        } catch (\Exception $e) {
            return redirect()->to('/purchase-orders')->with('error', 'Gagal menyetujui Purchase Order: ' . $e->getMessage());
        }
    }

    public function print($id)
    {
        $purchaseOrder = $this->purchaseOrderModel->getPOWithSupplier($id);
        
        if (!$purchaseOrder) {
            return redirect()->to('/purchase-orders')->with('error', 'Purchase Order tidak ditemukan!');
        }

        $data = [
            'title' => 'Print Purchase Order',
            'purchaseOrder' => $purchaseOrder,
            'poItems' => $this->purchaseOrderItemModel->getItemsByPO($id)
        ];

        return view('purchase-orders/print', $data);
    }

    // API untuk mendapatkan items berdasarkan supplier
    public function getSupplierItems($supplierId)
    {
        $items = $this->itemModel->getActiveItems();
        return $this->response->setJSON($items);
    }
}