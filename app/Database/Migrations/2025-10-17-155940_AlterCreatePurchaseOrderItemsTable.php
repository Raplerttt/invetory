<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterPurchaseOrderItemsAddTimestamps extends Migration
{
    public function up()
    {
        $fields = [];
    
        // Cek kalau kolom belum ada, baru tambah tanpa default
        $columnsToAdd = ['created_at', 'updated_at'];
        foreach ($columnsToAdd as $column) {
            $columnExists = $this->db->query(
                "SELECT column_name FROM information_schema.columns WHERE table_name='purchase_order_items' AND column_name='{$column}'"
            )->getResult();
    
            if (empty($columnExists)) {
                $fields[$column] = [
                    'type' => 'TIMESTAMPTZ',
                    'null' => true,
                    // No default here!
                ];
            }
        }
    
        if (!empty($fields)) {
            $this->forge->addColumn('purchase_order_items', $fields);
        }
    
        // Set default CURRENT_TIMESTAMP via raw query (tanpa tanda kutip)
        $this->db->query('ALTER TABLE purchase_order_items ALTER COLUMN created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->db->query('ALTER TABLE purchase_order_items ALTER COLUMN updated_at SET DEFAULT CURRENT_TIMESTAMP');
    }
    

    public function down()
    {
        // Drop kolom created_at dan updated_at
        $this->forge->dropColumn('purchase_order_items', 'created_at');
        $this->forge->dropColumn('purchase_order_items', 'updated_at');
    }
}
