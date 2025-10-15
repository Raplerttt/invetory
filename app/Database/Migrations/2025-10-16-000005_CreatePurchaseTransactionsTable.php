<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePurchaseTransactionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'SERIAL',  // PostgreSQL auto increment
                'unsigned' => true,
            ],
            'transaction_code' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'unique' => true,
            ],
            'supplier_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'total_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
            ],
            // ENUM tidak langsung didukung di PostgreSQL, gunakan VARCHAR + constraint
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => '10',
                'default' => 'pending',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_by' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'approved_by' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMPTZ',
                'null' => true,
                'default' => null,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMPTZ',
                'null' => true,
                'default' => null,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('supplier_id', 'suppliers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('approved_by', 'users', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('purchase_transactions');

        // Tambah constraint check untuk status ENUM-like behavior
        $this->db->query("ALTER TABLE purchase_transactions ADD CONSTRAINT status_check CHECK (status IN ('pending', 'approved', 'rejected'))");

        // Set default timestamps
        $this->db->query("ALTER TABLE purchase_transactions ALTER COLUMN created_at SET DEFAULT CURRENT_TIMESTAMP");
        $this->db->query("ALTER TABLE purchase_transactions ALTER COLUMN updated_at SET DEFAULT CURRENT_TIMESTAMP");
    }

    public function down()
    {
        $this->forge->dropTable('purchase_transactions');
    }
}
