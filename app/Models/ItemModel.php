<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemModel extends Model
{
    protected $table = 'items';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'code', 'name', 'description', 'category', 'unit', 
        'price', 'cost_price', 'stock', 'min_stock', 'max_stock', 
        'is_active', 'created_by'
    ];
    
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[200]',
        'code' => 'required|min_length[3]|max_length[50]|is_unique[items.code]',
        'category' => 'required|max_length[100]',
        'unit' => 'required|max_length[20]',
        'price' => 'required|decimal',
        'cost_price' => 'decimal',
        'stock' => 'integer',
        'min_stock' => 'integer'
    ];

    protected $validationMessages = [
        'code' => [
            'is_unique' => 'Kode item sudah digunakan.'
        ]
    ];

    // Get items with low stock
    public function getLowStockItems()
    {
        return $this->where('stock <= min_stock')
                    ->where('stock > 0')
                    ->where('is_active', true)
                    ->findAll();
    }

    // Get active items for dropdown
    public function getActiveItems()
    {
        return $this->where('is_active', true)
                   ->orderBy('name', 'ASC')
                   ->findAll();
    }

    public function getItemsForDropdown()
    {
        $items = $this->getActiveItems();
        $dropdown = [];
        foreach ($items as $item) {
            $dropdown[$item['id']] = $item['name'] . ' (' . $item['code'] . ')';
        }
        
        return $dropdown;
    }

    // Update stock
    public function updateStock($itemId, $quantity)
    {
        $item = $this->find($itemId);
        if ($item) {
            $newStock = $item['stock'] + $quantity;
            return $this->update($itemId, ['stock' => $newStock]);
        }
        return false;
    }

    // Check stock availability
    public function checkStock($itemId, $requiredQuantity)
    {
        $item = $this->find($itemId);
        if ($item && $item['stock'] >= $requiredQuantity) {
            return true;
        }
        return false;
    }

    // Get item stock for API
    public function getItemStock($itemId)
    {
        $item = $this->find($itemId);
        return $item ? $item['stock'] : null;
    }
}