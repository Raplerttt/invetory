<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSuppliersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'SERIAL', // ganti jadi SERIAL untuk auto increment PG
                'unsigned'       => true,
                //'auto_increment' => true, // sudah otomatis di SERIAL, bisa dihapus
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
            'address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
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
                'type'    => 'TIMESTAMPTZ',
                'null'    => true,
                'default' => null,
            ],
            'updated_at' => [
                'type'    => 'TIMESTAMPTZ',
                'null'    => true,
                'default' => null,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('suppliers');

        // Set default timestamp
        $this->db->query('ALTER TABLE suppliers ALTER COLUMN created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->db->query('ALTER TABLE suppliers ALTER COLUMN updated_at SET DEFAULT CURRENT_TIMESTAMP');
    }

    public function down()
    {
        $this->forge->dropTable('suppliers');
    }
}
