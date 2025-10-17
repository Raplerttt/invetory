<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'code', 'name', 'address', 'phone', 'email', 
        'is_active', 'created_by'
    ];
    
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[200]',
        'code' => 'required|min_length[3]|max_length[50]|is_unique[customers.code]',
        'email' => 'permit_empty|valid_email'
    ];

    protected $validationMessages = [
        'code' => [
            'is_unique' => 'Kode customer sudah digunakan.'
        ]
    ];

    // Get active customers for dropdown
    public function getActiveCustomers()
    {
        return $this->where('is_active', true)
                   ->orderBy('name', 'ASC')
                   ->findAll();
    }

    public function getCustomersForDropdown()
    {
        $customers = $this->getActiveCustomers();
        $dropdown = [];
        foreach ($customers as $customer) {
            $dropdown[$customer['id']] = $customer['name'] . ' (' . $customer['code'] . ')';
        }
        return $dropdown;
    }

    // Count active customers
    public function countActiveCustomers()
    {
        return $this->where('is_active', true)->countAllResults();
    }
}