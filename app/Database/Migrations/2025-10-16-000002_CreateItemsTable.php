<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateItemsTable extends Migration
{
    public function up()
    {
        // Add missing columns to items table
        $this->forge->addColumn('items', [
            'category' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'after' => 'description'
            ],
            'unit' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'default' => 'pcs',
                'after' => 'category'
            ],
            'cost_price' => [
                'type' => 'DECIMAL',
                'constraint' => ['15', '2'],
                'default' => 0,
                'after' => 'price'
            ],
            'min_stock' => [
                'type' => 'INT',
                'default' => 0,
                'after' => 'stock'
            ],
            'max_stock' => [
                'type' => 'INT',
                'null' => true,
                'after' => 'min_stock'
            ]
        ]);
    }

    public function down()
    {
        // Remove added columns
        $this->forge->dropColumn('items', [
            'category', 'unit', 'cost_price', 'min_stock', 'max_stock'
        ]);
    }
}