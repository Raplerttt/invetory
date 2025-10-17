<?php

namespace App\Models;

use CodeIgniter\Model;

class GoodsReceiptModel extends Model
{
    protected $table = 'goods_receipt';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'grn_code', 'po_id', 'receipt_date', 'received_by', 'status', 'notes'
    ];
    
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    
    public function getGRNWithDetails($id = null)
    {
        $builder = $this->db->table('goods_receipt gr');
        $builder->select('gr.*, po.po_code, u.username as received_by_name, s.name as supplier_name');
        $builder->join('purchase_orders po', 'po.id = gr.po_id');
        $builder->join('suppliers s', 's.id = po.supplier_id');
        $builder->join('users u', 'u.id = gr.received_by');
        
        if ($id) {
            $builder->where('gr.id', $id);
            return $builder->get()->getRowArray();
        }
        
        $builder->orderBy('gr.created_at', 'DESC');
        return $builder->get()->getResultArray();
    }
    
    public function getGRNItems($grnId)
    {
        $builder = $this->db->table('goods_receipt_items gri');
        $builder->select('gri.*, i.name as item_name, i.code as item_code, w.name as warehouse_name');
        $builder->join('items i', 'i.id = gri.item_id');
        $builder->join('warehouses w', 'w.id = gri.warehouse_id');
        $builder->where('gri.grn_id', $grnId);
        
        return $builder->get()->getResultArray();
    }
    
    public function approveGRN($grnId, $approvedBy)
    {
        $this->db->transStart();
        
        // Update GRN status
        $this->update($grnId, [
            'status' => 'approved'
        ]);
        
        // Get GRN items
        $grnItems = $this->getGRNItems($grnId);
        $stockModel = new StockModel();
        $stockCardModel = new StockCardModel();
        
        foreach ($grnItems as $item) {
            // Update stock
            $stockModel->addStock($item['item_id'], $item['warehouse_id'], $item['quantity_received']);
            
            // Record stock card
            $stockCardModel->recordTransaction(
                $item['item_id'],
                $item['warehouse_id'],
                'in',
                'grn',
                $grnId,
                $item['quantity_received'],
                "GRN {$item['grn_code']}"
            );
        }
        
        $this->db->transComplete();
        return $this->db->transStatus();
    }
}