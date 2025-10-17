<?php

namespace App\Models;

use CodeIgniter\Model;

class PurchaseOrderModel extends Model
{
    protected $table = 'purchase_orders';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'po_number', 'supplier_id', 'order_date', 'delivery_date',
        'total_amount', 'status', 'notes', 'created_by', 'approved_by'
    ];
    
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'po_number' => 'required|min_length[3]|max_length[100]|is_unique[purchase_orders.po_number]',
        'supplier_id' => 'required|integer',
        'order_date' => 'required|valid_date',
        'total_amount' => 'decimal'
    ];

    protected $validationMessages = [
        'po_number' => [
            'is_unique' => 'Nomor PO sudah digunakan.'
        ]
    ];

    // Generate PO number
    public function generatePONumber()
    {
        $prefix = 'PO';
        $year = date('Y');
        $month = date('m');
        
        $lastPO = $this->like('po_number', $prefix . '/' . $year . '/' . $month)
                      ->orderBy('id', 'DESC')
                      ->first();
        
        if ($lastPO) {
            $lastNumber = (int) substr($lastPO['po_number'], -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return $prefix . '/' . $year . '/' . $month . '/' . $newNumber;
    }

    // Get PO with supplier details
    public function getPOWithSupplier($id = null)
    {
        $builder = $this->db->table('purchase_orders po');
        $builder->select('po.*, s.name as supplier_name, s.code as supplier_code, s.address as supplier_address, uc.name as created_by_name, ua.name as approved_by_name');
        $builder->join('suppliers s', 's.id = po.supplier_id', 'left');
        $builder->join('users uc', 'uc.id = po.created_by', 'left');
        $builder->join('users ua', 'ua.id = po.approved_by', 'left');
        
        if ($id) {
            $builder->where('po.id', $id);
            return $builder->get()->getRowArray();
        }
        
        $builder->orderBy('po.created_at', 'DESC');
        return $builder->get()->getResultArray();
    }

    // Get monthly total for dashboard
    public function getMonthlyTotal($month)
    {
        $result = $this->selectSum('total_amount')
                      ->where("TO_CHAR(created_at, 'YYYY-MM') = ", $month)
                      ->where('status', 'approved')
                      ->first();
        
        return $result ? (float)$result['total_amount'] : 0;
    }

    // Count by status
    public function countByStatus($status)
    {
        return $this->where('status', $status)->countAllResults();
    }

    // Update PO status
    public function updateStatus($id, $status, $approvedBy = null)
    {
        $data = ['status' => $status];
        
        if ($approvedBy && $status === 'approved') {
            $data['approved_by'] = $approvedBy;
        }
        
        return $this->update($id, $data);
    }

    // Get POs by status
    public function getByStatus($status)
    {
        return $this->where('status', $status)->findAll();
    }
}