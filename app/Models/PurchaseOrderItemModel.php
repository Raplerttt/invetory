<?php

namespace App\Models;

use CodeIgniter\Model;

class PurchaseOrderItemModel extends Model
{
    protected $table = 'purchase_order_items';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'purchase_order_id', 'item_id', 'quantity', 'unit_price', 'total_price'
    ];
    
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Get items for a PO
    public function getItemsByPO($poId)
    {
        $builder = $this->db->table('purchase_order_items poi');
        $builder->select('poi.*, i.name as item_name, i.code as item_code, i.unit as item_unit, i.stock as current_stock');
        $builder->join('items i', 'i.id = poi.item_id', 'left');
        $builder->where('poi.purchase_order_id', $poId);
        
        return $builder->get()->getResultArray();
    }

    // Calculate total amount for PO
    public function calculateTotalAmount($poId)
    {
        $result = $this->selectSum('total_price')
                      ->where('purchase_order_id', $poId)
                      ->first();
        
        return $result ? (float)$result['total_price'] : 0;
    }

    // Delete all items for a PO
    public function deleteByPO($poId)
    {
        return $this->where('purchase_order_id', $poId)->delete();
    }
}