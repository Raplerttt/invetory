<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateItemsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'SERIAL',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'code' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'price' => [
                'type'       => 'DECIMAL',
                'constraint' => ['15', '2'],
            ],
            'stock' => [
                'type'    => 'INT',
                'default' => 0,
            ],
            'is_active' => [
                'type'    => 'BOOLEAN',
                'default' => true,
            ],
            'created_by' => [
                'type'    => 'INT',
                'unsigned'=> true,
                'null'    => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMPTZ',
                'null' => true,
                'default' => null, // jangan set default di sini
            ],
            'updated_at' => [
                'type' => 'TIMESTAMPTZ',
                'null' => true,
                'default' => null, // jangan set default di sini
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('items');

        // Set default CURRENT_TIMESTAMP untuk created_at dan updated_at
        $this->db->query('ALTER TABLE items ALTER COLUMN created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->db->query('ALTER TABLE items ALTER COLUMN updated_at SET DEFAULT CURRENT_TIMESTAMP');
    }

    public function down()
    {
        $this->forge->dropTable('items');
    }
}
