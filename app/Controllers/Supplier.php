<?php

namespace App\Controllers;

use App\Models\SupplierModel;

class Suppliers extends BaseController
{
    protected $supplierModel;

    public function __construct()
    {
        $this->supplierModel = new SupplierModel();
    }

    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Master Data Supplier',
            'suppliers' => $this->supplierModel->findAll(),
            'user' => [
                'name' => session()->get('name'),
                'role' => session()->get('role')
            ]
        ];

        return view('suppliers/index', $data);
    }

    public function create()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if ($this->request->getMethod() === 'post') {
            $data = [
                'code' => $this->request->getPost('code'),
                'name' => $this->request->getPost('name'),
                'address' => $this->request->getPost('address'),
                'phone' => $this->request->getPost('phone'),
                'email' => $this->request->getPost('email'),
                'created_by' => session()->get('user_id')
            ];

            if ($this->supplierModel->save($data)) {
                return redirect()->to('/suppliers')->with('success', 'Supplier berhasil ditambahkan');
            } else {
                return redirect()->back()->with('errors', $this->supplierModel->errors());
            }
        }

        return view('suppliers/create', [
            'title' => 'Tambah Supplier',
            'user' => [
                'name' => session()->get('name'),
                'role' => session()->get('role')
            ]
        ]);
    }

    public function edit($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $supplier = $this->supplierModel->find($id);
        if (!$supplier) {
            return redirect()->to('/suppliers')->with('error', 'Supplier tidak ditemukan');
        }

        if ($this->request->getMethod() === 'post') {
            $data = [
                'name' => $this->request->getPost('name'),
                'address' => $this->request->getPost('address'),
                'phone' => $this->request->getPost('phone'),
                'email' => $this->request->getPost('email'),
            ];

            if ($this->supplierModel->update($id, $data)) {
                return redirect()->to('/suppliers')->with('success', 'Supplier berhasil diupdate');
            } else {
                return redirect()->back()->with('errors', $this->supplierModel->errors());
            }
        }

        return view('suppliers/edit', [
            'title' => 'Edit Supplier',
            'supplier' => $supplier,
            'user' => [
                'name' => session()->get('name'),
                'role' => session()->get('role')
            ]
        ]);
    }

    public function delete($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if ($this->supplierModel->delete($id)) {
            return redirect()->to('/suppliers')->with('success', 'Supplier berhasil dihapus');
        } else {
            return redirect()->to('/suppliers')->with('error', 'Gagal menghapus supplier');
        }
    }
}