<?php

namespace App\Models;

use CodeIgniter\Model;

class PurchaseModel extends Model
{
    protected $table            = 'purchase_transactions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['transaction_code', 'supplier_id', 'total_amount', 'status', 'notes', 'created_by', 'approved_by'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getPurchasesWithDetails()
    {
        return $this->select('purchase_transactions.*, suppliers.name as supplier_name, creator.name as created_by_name, approver.name as approved_by_name')
                    ->join('suppliers', 'suppliers.id = purchase_transactions.supplier_id')
                    ->join('users as creator', 'creator.id = purchase_transactions.created_by')
                    ->join('users as approver', 'approver.id = purchase_transactions.approved_by', 'left')
                    ->orderBy('purchase_transactions.created_at', 'DESC')
                    ->findAll();
    }
}