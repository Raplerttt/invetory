<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Database;

class TestConnection extends Controller
{
    public function index()
    {
        $db = Database::connect();

        // Coba query untuk memastikan koneksi berhasil
        try {
            $query = $db->query("SELECT current_database(), version()");
            $result = $query->getResult();

            echo "<h3>Koneksi berhasil ✅</h3>";
            echo "<pre>";
            print_r($result);
            echo "</pre>";

            // Ambil konfigurasi dari Database config
            $config = config('Database')->default;

            echo "<h4>Konfigurasi Koneksi:</h4>";
            echo "<pre>";
            print_r([
                'hostname' => $config['hostname'],
                'database' => $config['database'],
                'username' => $config['username'],
                'DBDriver' => $config['DBDriver'],
                'port'     => $config['port'],
                'charset'  => $config['charset'],
                'collation'=> $config['DBCollat'],
            ]);
            echo "</pre>";

        } catch (\Exception $e) {
            echo "<h3 style='color:red;'>Koneksi gagal ❌</h3>";
            echo "Error: " . $e->getMessage();
        }
    }
}
