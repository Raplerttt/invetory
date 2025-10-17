<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SupplierModel;

class Suppliers extends BaseController
{
    protected $supplierModel;

    public function __construct()
    {
        $this->supplierModel = new SupplierModel();
        helper(['form', 'custom']);
    }

    public function index()
    {
        $data = [
            'title' => 'Manage Suppliers',
            'suppliers' => $this->supplierModel->where('is_active', true)->findAll(),
            'user' => [
                'name' => session()->get('name'),
                'role' => session()->get('role')
            ]
        ];

        return view('suppliers/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Add New Supplier',
            'validation' => \Config\Services::validation(),
            'user' => [
                'name' => session()->get('name'),
                'role' => session()->get('role')
            ]
        ];

        return view('suppliers/create', $data);
    }

    public function store()
    {
        // Validate input
        $rules = [
            'name' => 'required|min_length[3]|max_length[200]',
            'code' => 'required|min_length[3]|max_length[50]|is_unique[suppliers.code]',
            'phone' => 'max_length[20]',
            'email' => 'permit_empty|valid_email'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        // Prepare data
        $data = [
            'name' => $this->request->getPost('name'),
            'code' => $this->request->getPost('code'),
            'address' => $this->request->getPost('address'),
            'phone' => $this->request->getPost('phone'),
            'email' => $this->request->getPost('email'),
            'created_by' => session()->get('userId'),
            'is_active' => true
        ];

        try {
            $this->supplierModel->save($data);
            return redirect()->to('/suppliers')->with('success', 'Supplier berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan supplier: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $supplier = $this->supplierModel->find($id);
        
        if (!$supplier) {
            return redirect()->to('/suppliers')->with('error', 'Supplier tidak ditemukan!');
        }

        $data = [
            'title' => 'Edit Supplier',
            'supplier' => $supplier,
            'validation' => \Config\Services::validation(),
            'user' => [
                'name' => session()->get('name'),
                'role' => session()->get('role')
            ]
        ];

        return view('suppliers/edit', $data);
    }

    public function update($id)
    {
        $supplier = $this->supplierModel->find($id);
        
        if (!$supplier) {
            return redirect()->to('/suppliers')->with('error', 'Supplier tidak ditemukan!');
        }

        // Validate input
        $rules = [
            'name' => 'required|min_length[3]|max_length[200]',
            'code' => "required|min_length[3]|max_length[50]|is_unique[suppliers.code,id,{$id}]",
            'phone' => 'max_length[20]',
            'email' => 'permit_empty|valid_email'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        // Prepare data
        $data = [
            'id' => $id,
            'name' => $this->request->getPost('name'),
            'code' => $this->request->getPost('code'),
            'address' => $this->request->getPost('address'),
            'phone' => $this->request->getPost('phone'),
            'email' => $this->request->getPost('email')
        ];

        try {
            $this->supplierModel->save($data);
            return redirect()->to('/suppliers')->with('success', 'Supplier berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui supplier: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        $supplier = $this->supplierModel->find($id);
        
        if (!$supplier) {
            return redirect()->to('/suppliers')->with('error', 'Supplier tidak ditemukan!');
        }

        try {
            // Soft delete - set is_active to false
            $this->supplierModel->update($id, ['is_active' => false]);
            return redirect()->to('/suppliers')->with('success', 'Supplier berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->to('/suppliers')->with('error', 'Gagal menghapus supplier: ' . $e->getMessage());
        }
    }

    public function view($id)
    {
        $supplier = $this->supplierModel->find($id);
        
        if (!$supplier) {
            return redirect()->to('/suppliers')->with('error', 'Supplier tidak ditemukan!');
        }

        $data = [
            'title' => 'View Supplier',
            'supplier' => $supplier,
            'user' => [
                'name' => session()->get('name'),
                'role' => session()->get('role')
            ]
        ];

        return view('suppliers/view', $data);
    }
}