<?php

namespace App\Models;

use CodeIgniter\Model;

class DeliveryModel extends Model
{
    protected $table = 'deliveries';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'delivery_code', 'so_id', 'delivery_date', 'delivered_by', 
        'status', 'shipping_address', 'notes'
    ];
    
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    
    public function getDeliveriesWithDetails($id = null)
    {
        $builder = $this->db->table('deliveries d');
        $builder->select('d.*, so.so_code, c.name as customer_name, u.username as delivered_by_name');
        $builder->join('sales_orders so', 'so.id = d.so_id');
        $builder->join('customers c', 'c.id = so.customer_id');
        $builder->join('users u', 'u.id = d.delivered_by');
        
        if ($id) {
            $builder->where('d.id', $id);
            return $builder->get()->getRowArray();
        }
        
        $builder->orderBy('d.created_at', 'DESC');
        return $builder->get()->getResultArray();
    }
    
    public function getDeliveryItems($deliveryId)
    {
        $builder = $this->db->table('delivery_items di');
        $builder->select('di.*, i.name as item_name, i.code as item_code, w.name as warehouse_name');
        $builder->join('items i', 'i.id = di.item_id');
        $builder->join('warehouses w', 'w.id = di.warehouse_id');
        $builder->where('di.delivery_id', $deliveryId);
        
        return $builder->get()->getResultArray();
    }
    
    public function approveDelivery($deliveryId, $approvedBy)
    {
        $this->db->transStart();
        
        // Update delivery status
        $this->update($deliveryId, [
            'status' => 'approved'
        ]);
        
        // Get delivery items
        $deliveryItems = $this->getDeliveryItems($deliveryId);
        $stockModel = new StockModel();
        $stockCardModel = new StockCardModel();
        
        foreach ($deliveryItems as $item) {
            // Update stock (reduce)
            $stockModel->reduceStock($item['item_id'], $item['warehouse_id'], $item['quantity_delivered']);
            
            // Record stock card
            $stockCardModel->recordTransaction(
                $item['item_id'],
                $item['warehouse_id'],
                'out',
                'delivery',
                $deliveryId,
                $item['quantity_delivered'],
                "Delivery {$item['delivery_code']}"
            );
        }
        
        // Update SO status to delivered
        $delivery = $this->find($deliveryId);
        $soModel = new SalesOrderModel();
        $soModel->update($delivery['so_id'], ['status' => 'delivered']);
        
        $this->db->transComplete();
        return $this->db->transStatus();
    }
}