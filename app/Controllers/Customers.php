<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CustomerModel;

class Customers extends BaseController
{
    protected $customerModel;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
        helper(['form', 'custom']);
    }

    public function index()
    {
        $data = [
            'title' => 'Manage Customers',
            'customers' => $this->customerModel->where('is_active', true)->findAll(),
            'user' => [
                'name' => session()->get('name'),
                'role' => session()->get('role')
            ]
        ];

        return view('customers/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Add New Customer',
            'validation' => \Config\Services::validation(),
            'user' => [
                'name' => session()->get('name'),
                'role' => session()->get('role')
            ]
        ];

        return view('customers/create', $data);
    }

    public function store()
    {
        // Validate input
        $rules = [
            'name' => 'required|min_length[3]|max_length[200]',
            'code' => 'required|min_length[3]|max_length[50]|is_unique[customers.code]',
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
            $this->customerModel->save($data);
            return redirect()->to('/customers')->with('success', 'Customer berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan customer: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $customer = $this->customerModel->find($id);
        
        if (!$customer) {
            return redirect()->to('/customers')->with('error', 'Customer tidak ditemukan!');
        }

        $data = [
            'title' => 'Edit Customer',
            'customer' => $customer,
            'validation' => \Config\Services::validation(),
            'user' => [
                'name' => session()->get('name'),
                'role' => session()->get('role')
            ]
        ];

        return view('customers/edit', $data);
    }

    public function update($id)
    {
        $customer = $this->customerModel->find($id);
        
        if (!$customer) {
            return redirect()->to('/customers')->with('error', 'Customer tidak ditemukan!');
        }

        // Validate input
        $rules = [
            'name' => 'required|min_length[3]|max_length[200]',
            'code' => "required|min_length[3]|max_length[50]|is_unique[customers.code,id,{$id}]",
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
            $this->customerModel->save($data);
            return redirect()->to('/customers')->with('success', 'Customer berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui customer: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        $customer = $this->customerModel->find($id);
        
        if (!$customer) {
            return redirect()->to('/customers')->with('error', 'Customer tidak ditemukan!');
        }

        try {
            // Soft delete - set is_active to false
            $this->customerModel->update($id, ['is_active' => false]);
            return redirect()->to('/customers')->with('success', 'Customer berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->to('/customers')->with('error', 'Gagal menghapus customer: ' . $e->getMessage());
        }
    }

    public function view($id)
    {
        $customer = $this->customerModel->find($id);
        
        if (!$customer) {
            return redirect()->to('/customers')->with('error', 'Customer tidak ditemukan!');
        }

        $data = [
            'title' => 'View Customer',
            'customer' => $customer,
            'user' => [
                'name' => session()->get('name'),
                'role' => session()->get('role')
            ]
        ];

        return view('customers/view', $data);
    }
}