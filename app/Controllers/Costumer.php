<?php

namespace App\Controllers;

use App\Models\CustomerModel;

class Customers extends BaseController
{
    protected $customerModel;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
    }

    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Master Data Customer',
            'customers' => $this->customerModel->findAll(),
            'user' => [
                'name' => session()->get('name'),
                'role' => session()->get('role')
            ]
        ];

        return view('customers/index', $data);
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

            if ($this->customerModel->save($data)) {
                return redirect()->to('/customers')->with('success', 'Customer berhasil ditambahkan');
            } else {
                return redirect()->back()->with('errors', $this->customerModel->errors());
            }
        }

        return view('customers/create', [
            'title' => 'Tambah Customer',
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

        $customer = $this->customerModel->find($id);
        if (!$customer) {
            return redirect()->to('/customers')->with('error', 'Customer tidak ditemukan');
        }

        if ($this->request->getMethod() === 'post') {
            $data = [
                'name' => $this->request->getPost('name'),
                'address' => $this->request->getPost('address'),
                'phone' => $this->request->getPost('phone'),
                'email' => $this->request->getPost('email'),
            ];

            if ($this->customerModel->update($id, $data)) {
                return redirect()->to('/customers')->with('success', 'Customer berhasil diupdate');
            } else {
                return redirect()->back()->with('errors', $this->customerModel->errors());
            }
        }

        return view('customers/edit', [
            'title' => 'Edit Customer',
            'customer' => $customer,
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

        if ($this->customerModel->delete($id)) {
            return redirect()->to('/customers')->with('success', 'Customer berhasil dihapus');
        } else {
            return redirect()->to('/customers')->with('error', 'Gagal menghapus customer');
        }
    }
}