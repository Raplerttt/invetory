<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ItemModel;

class Items extends BaseController
{
    protected $itemModel;

    public function __construct()
    {
        $this->itemModel = new ItemModel();
        helper(['form', 'custom']);
    }

    public function index()
    {
        $data = [
            'title' => 'Manage Items',
            'items' => $this->itemModel->where('is_active', true)->findAll(),
            'user' => [
                'name' => session()->get('name'),
                'role' => session()->get('role')
            ]
        ];

        return view('items/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Add New Item',
            'validation' => \Config\Services::validation(),
            'user' => [
                'name' => session()->get('name'),
                'role' => session()->get('role')
            ]
        ];

        return view('items/create', $data);
    }

    public function store()
    {
        // Validate input
        $rules = [
            'name' => 'required|min_length[3]|max_length[200]',
            'code' => 'required|min_length[3]|max_length[50]|is_unique[items.code]',
            'category' => 'required|max_length[100]',
            'unit' => 'required|max_length[20]',
            'price' => 'required|decimal',
            'cost_price' => 'decimal',
            'stock' => 'integer',
            'min_stock' => 'integer'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        // Prepare data
        $data = [
            'name' => $this->request->getPost('name'),
            'code' => $this->request->getPost('code'),
            'description' => $this->request->getPost('description'),
            'category' => $this->request->getPost('category'),
            'unit' => $this->request->getPost('unit'),
            'price' => $this->request->getPost('price'),
            'cost_price' => $this->request->getPost('cost_price') ?? 0,
            'stock' => $this->request->getPost('stock') ?? 0,
            'min_stock' => $this->request->getPost('min_stock') ?? 0,
            'max_stock' => $this->request->getPost('max_stock'),
            'created_by' => session()->get('userId'),
            'is_active' => true
        ];

        try {
            $this->itemModel->save($data);
            return redirect()->to('/items')->with('success', 'Item berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan item: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $item = $this->itemModel->find($id);
        
        if (!$item) {
            return redirect()->to('/items')->with('error', 'Item tidak ditemukan!');
        }

        $data = [
            'title' => 'Edit Item',
            'item' => $item,
            'validation' => \Config\Services::validation(),
            'user' => [
                'name' => session()->get('name'),
                'role' => session()->get('role')
            ]
        ];

        return view('items/edit', $data);
    }

    public function update($id)
    {
        $item = $this->itemModel->find($id);
        
        if (!$item) {
            return redirect()->to('/items')->with('error', 'Item tidak ditemukan!');
        }

        // Validate input
        $rules = [
            'name' => 'required|min_length[3]|max_length[200]',
            'code' => "required|min_length[3]|max_length[50]|is_unique[items.code,id,{$id}]",
            'category' => 'required|max_length[100]',
            'unit' => 'required|max_length[20]',
            'price' => 'required|decimal',
            'cost_price' => 'decimal',
            'stock' => 'integer',
            'min_stock' => 'integer'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        // Prepare data
        $data = [
            'id' => $id,
            'name' => $this->request->getPost('name'),
            'code' => $this->request->getPost('code'),
            'description' => $this->request->getPost('description'),
            'category' => $this->request->getPost('category'),
            'unit' => $this->request->getPost('unit'),
            'price' => $this->request->getPost('price'),
            'cost_price' => $this->request->getPost('cost_price') ?? 0,
            'stock' => $this->request->getPost('stock') ?? 0,
            'min_stock' => $this->request->getPost('min_stock') ?? 0,
            'max_stock' => $this->request->getPost('max_stock')
        ];

        try {
            $this->itemModel->save($data);
            return redirect()->to('/items')->with('success', 'Item berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui item: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        $item = $this->itemModel->find($id);
        
        if (!$item) {
            return redirect()->to('/items')->with('error', 'Item tidak ditemukan!');
        }

        try {
            // Soft delete - set is_active to false
            $this->itemModel->update($id, ['is_active' => false]);
            return redirect()->to('/items')->with('success', 'Item berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->to('/items')->with('error', 'Gagal menghapus item: ' . $e->getMessage());
        }
    }

    public function view($id)
    {
        $item = $this->itemModel->find($id);
        
        if (!$item) {
            return redirect()->to('/items')->with('error', 'Item tidak ditemukan!');
        }

        $data = [
            'title' => 'View Item',
            'item' => $item,
            'user' => [
                'name' => session()->get('name'),
                'role' => session()->get('role')
            ]
        ];

        return view('items/view', $data);
    }

    // API untuk cek stok
    public function getStock($id)
    {
        $item = $this->itemModel->find($id);
        if ($item) {
            return $this->response->setJSON([
                'success' => true,
                'stock' => $item['stock']
            ]);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Item tidak ditemukan'
        ]);
    }

    public function getItemStock($id)
    {
        return $this->getStock($id);
    }

    public function checkStock()
    {
        $itemId = $this->request->getGet('item_id');
        $quantity = $this->request->getGet('quantity');
        
        $item = $this->itemModel->find($itemId);
        if ($item) {
            $available = $item['stock'] >= $quantity;
            return $this->response->setJSON([
                'success' => true,
                'available' => $available,
                'current_stock' => $item['stock'],
                'required' => $quantity
            ]);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Item tidak ditemukan'
        ]);
    }
}