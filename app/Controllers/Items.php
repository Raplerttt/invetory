<?php

namespace App\Controllers;

use App\Models\ItemModel;

class Items extends BaseController
{
    protected $itemModel;

    public function __construct()
    {
        $this->itemModel = new ItemModel();
    }

    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Master Data Item',
            'items' => $this->itemModel->findAll(),
            'user' => [
                'name' => session()->get('name'),
                'role' => session()->get('role')
            ]
        ];

        return view('items/index', $data);
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
                'description' => $this->request->getPost('description'),
                'price' => $this->request->getPost('price'),
                'stock' => $this->request->getPost('stock'),
                'created_by' => session()->get('user_id')
            ];

            if ($this->itemModel->save($data)) {
                return redirect()->to('/items')->with('success', 'Item berhasil ditambahkan');
            } else {
                return redirect()->back()->with('errors', $this->itemModel->errors());
            }
        }

        return view('items/create', [
            'title' => 'Tambah Item',
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

        $item = $this->itemModel->find($id);
        if (!$item) {
            return redirect()->to('/items')->with('error', 'Item tidak ditemukan');
        }

        if ($this->request->getMethod() === 'post') {
            $data = [
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                'price' => $this->request->getPost('price'),
                'stock' => $this->request->getPost('stock'),
            ];

            if ($this->itemModel->update($id, $data)) {
                return redirect()->to('/items')->with('success', 'Item berhasil diupdate');
            } else {
                return redirect()->back()->with('errors', $this->itemModel->errors());
            }
        }

        return view('items/edit', [
            'title' => 'Edit Item',
            'item' => $item,
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

        if ($this->itemModel->delete($id)) {
            return redirect()->to('/items')->with('success', 'Item berhasil dihapus');
        } else {
            return redirect()->to('/items')->with('error', 'Gagal menghapus item');
        }
    }
}