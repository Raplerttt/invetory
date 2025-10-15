<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Admin Users
            [
                'username' => 'admin',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'name' => 'Administrator System',
                'role' => 'admin',
                'is_active' => true,
                'created_at' => Time::now(),
                'updated_at' => Time::now()
            ],
            [
                'username' => 'superadmin',
                'password' => password_hash('super123', PASSWORD_DEFAULT),
                'name' => 'Super Admin',
                'role' => 'admin',
                'is_active' => true,
                'created_at' => Time::now(),
                'updated_at' => Time::now()
            ],
            [
                'username' => 'manager',
                'password' => password_hash('manager123', PASSWORD_DEFAULT),
                'name' => 'Manager Utama',
                'role' => 'admin',
                'is_active' => true,
                'created_at' => Time::now(),
                'updated_at' => Time::now()
            ],

            // Staff Users
            [
                'username' => 'staff1',
                'password' => password_hash('staff123', PASSWORD_DEFAULT),
                'name' => 'Staff Keuangan',
                'role' => 'staff',
                'is_active' => true,
                'created_at' => Time::now(),
                'updated_at' => Time::now()
            ],
            [
                'username' => 'staff2',
                'password' => password_hash('staff123', PASSWORD_DEFAULT),
                'name' => 'Staff Gudang',
                'role' => 'staff',
                'is_active' => true,
                'created_at' => Time::now(),
                'updated_at' => Time::now()
            ],
            [
                'username' => 'staff3',
                'password' => password_hash('staff123', PASSWORD_DEFAULT),
                'name' => 'Staff Pembelian',
                'role' => 'staff',
                'is_active' => true,
                'created_at' => Time::now(),
                'updated_at' => Time::now()
            ],
            [
                'username' => 'john_doe',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'name' => 'John Doe',
                'role' => 'staff',
                'is_active' => true,
                'created_at' => Time::now(),
                'updated_at' => Time::now()
            ],
            [
                'username' => 'jane_smith',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'name' => 'Jane Smith',
                'role' => 'staff',
                'is_active' => true,
                'created_at' => Time::now(),
                'updated_at' => Time::now()
            ]
        ];

        // Using Query Builder
        $this->db->table('users')->insertBatch($data);

        echo "Seeder UserSeeder berhasil dijalankan!\n";
        echo "Admin accounts:\n";
        echo "- admin / admin123\n";
        echo "- superadmin / super123\n";
        echo "- manager / manager123\n";
        echo "Staff accounts:\n";
        echo "- staff1 / staff123\n";
        echo "- staff2 / staff123\n";
        echo "- staff3 / staff123\n";
        echo "- john_doe / password123\n";
        echo "- jane_smith / password123\n";
    }
}