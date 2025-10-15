<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            // SERIAL untuk PostgreSQL auto increment
            'id' => [
                'type'           => 'SERIAL',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            // ENUM tidak tersedia langsung, jadi gunakan VARCHAR + constraint nanti
            'role' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'default'    => 'staff',
            ],
            'is_active' => [
                'type'    => 'BOOLEAN',
                'default' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMPTZ',
                'null' => true,
                'default' => null,  // jangan set default disini
            ],
            'updated_at' => [
                'type' => 'TIMESTAMPTZ',
                'null' => true,
                'default' => null,  // jangan set default disini
            ]
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('users');

        // Tambahkan constraint ENUM untuk kolom role
        $this->db->query("ALTER TABLE users ADD CONSTRAINT role_check CHECK (role IN ('admin', 'staff'))");

        // Set default CURRENT_TIMESTAMP tanpa tanda kutip untuk created_at dan updated_at
        $this->db->query('ALTER TABLE users ALTER COLUMN created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->db->query('ALTER TABLE users ALTER COLUMN updated_at SET DEFAULT CURRENT_TIMESTAMP');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
