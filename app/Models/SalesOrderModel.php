<?php

namespace App\Models;

use CodeIgniter\Model;

class SalesOrderModel extends Model
{
    protected $table = 'sales_orders';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'so_code', 'customer_id', 'order_date', 'total_amount', 'status', 
        'created_by', 'approved_by', 'notes'
    ];
    
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    
    public function getSalesOrdersWithDetails($id = null)
    {
        $builder = $this->db->table('sales_orders so');
        $builder->select('so.*, c.name as customer_name, c.address as customer_address, 
                         u1.username as created_by_name, u2.username as approved_by_name');
        $builder->join('customers c', 'c.id = so.customer_id');
        $builder->join('users u1', 'u1.id = so.created_by');
        $builder->join('users u2', 'u2.id = so.approved_by', 'left');
        
        if ($id) {
            $builder->where('so.id', $id);
            return $builder->get()->getRowArray();
        }
        
        $builder->orderBy('so.created_at', 'DESC');
        return $builder->get()->getResultArray();
    }
    
    public function getSOItems($soId)
    {
        $builder = $this->db->table('sales_order_items soi');
        $builder->select('soi.*, i.name as item_name, i.code as item_code, w.name as warehouse_name');
        $builder->join('items i', 'i.id = soi.item_id');
        $builder->join('warehouses w', 'w.id = soi.warehouse_id');
        $builder->where('soi.so_id', $soId);
        
        return $builder->get()->getResultArray();
    }
    
    public function checkStockAvailability($soId)
    {
        $items = $this->getSOItems($soId);
        $stockModel = new StockModel();
        
        foreach ($items as $item) {
            $currentStock = $stockModel->getStock($item['item_id'], $item['warehouse_id']);
            if ($currentStock < $item['quantity']) {
                return [
                    'available' => false,
                    'item' => $item['item_name'],
                    'required' => $item['quantity'],
                    'available_stock' => $currentStock
                ];
            }
        }
        
        return ['available' => true];
    }
    
    public function approveSO($soId, $approvedBy)
    {
        $stockCheck = $this->checkStockAvailability($soId);
        
        if (!$stockCheck['available']) {
            return [
                'success' => false,
                'message' => "Stok tidak mencukupi untuk item {$stockCheck['item']}. Stok tersedia: {$stockCheck['available_stock']}, dibutuhkan: {$stockCheck['required']}"
            ];
        }
        
        $this->update($soId, [
            'status' => 'approved',
            'approved_by' => $approvedBy
        ]);
        
        return ['success' => true, 'message' => 'SO berhasil disetujui'];
    }
}