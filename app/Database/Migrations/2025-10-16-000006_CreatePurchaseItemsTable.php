<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePurchaseItemsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'SERIAL', // PostgreSQL auto increment
                'unsigned' => true,
            ],
            'purchase_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'item_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'quantity' => [
                'type' => 'INT',
            ],
            'unit_price' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'total_price' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('purchase_id', 'purchase_transactions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('item_id', 'items', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('purchase_items');
    }

    public function down()
    {
        $this->forge->dropTable('purchase_items');
    }
}
