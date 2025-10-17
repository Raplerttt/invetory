<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInventoryManagementTables extends Migration
{
    public function up()
    {
        // goods_receipt
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'grn_code' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => false],
            'po_id' => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'receipt_date' => ['type' => 'DATE', 'null' => false],
            'received_by' => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'pending', 'null' => false],
            'notes' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'TIMESTAMP', 'null' => false],
            'updated_at' => ['type' => 'TIMESTAMP', 'null' => false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('grn_code');
        $this->forge->addForeignKey('po_id', 'purchase_orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('received_by', 'users', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('goods_receipt');

        // Set default CURRENT_TIMESTAMP on timestamp columns
        $this->db->query("ALTER TABLE goods_receipt ALTER COLUMN created_at SET DEFAULT CURRENT_TIMESTAMP");
        $this->db->query("ALTER TABLE goods_receipt ALTER COLUMN updated_at SET DEFAULT CURRENT_TIMESTAMP");

        // Add check constraint for status on goods_receipt
        $this->db->query("ALTER TABLE goods_receipt ADD CONSTRAINT chk_goods_receipt_status CHECK (status IN ('pending', 'approved', 'rejected'))");

        // goods_receipt_items
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'grn_id' => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'item_id' => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'quantity_received' => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'warehouse_id' => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'batch_number' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'expiry_date' => ['type' => 'DATE', 'null' => true],
            'created_at' => ['type' => 'TIMESTAMP', 'null' => false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('grn_id', 'goods_receipt', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('item_id', 'items', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('warehouse_id', 'warehouses', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('goods_receipt_items');

        $this->db->query("ALTER TABLE goods_receipt_items ALTER COLUMN created_at SET DEFAULT CURRENT_TIMESTAMP");

        // sales_orders
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'so_code' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => false],
            'customer_id' => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'order_date' => ['type' => 'DATE', 'null' => false],
            'total_amount' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'null' => false],
            'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'pending', 'null' => false],
            'created_by' => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'approved_by' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'notes' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'TIMESTAMP', 'null' => false],
            'updated_at' => ['type' => 'TIMESTAMP', 'null' => false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('so_code');
        $this->forge->addForeignKey('customer_id', 'customers', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('approved_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('sales_orders');

        $this->db->query("ALTER TABLE sales_orders ALTER COLUMN created_at SET DEFAULT CURRENT_TIMESTAMP");
        $this->db->query("ALTER TABLE sales_orders ALTER COLUMN updated_at SET DEFAULT CURRENT_TIMESTAMP");

        $this->db->query("ALTER TABLE sales_orders ADD CONSTRAINT chk_sales_orders_status CHECK (status IN ('pending', 'approved', 'rejected', 'delivered'))");

        // sales_order_items
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'so_id' => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'item_id' => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'quantity' => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'unit_price' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'null' => false],
            'total_price' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'null' => false],
            'warehouse_id' => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'created_at' => ['type' => 'TIMESTAMP', 'null' => false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('so_id', 'sales_orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('item_id', 'items', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('warehouse_id', 'warehouses', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('sales_order_items');

        $this->db->query("ALTER TABLE sales_order_items ALTER COLUMN created_at SET DEFAULT CURRENT_TIMESTAMP");

        // deliveries
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'delivery_code' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => false],
            'so_id' => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'delivery_date' => ['type' => 'DATE', 'null' => false],
            'delivered_by' => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'pending', 'null' => false],
            'shipping_address' => ['type' => 'TEXT', 'null' => true],
            'notes' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'TIMESTAMP', 'null' => false],
            'updated_at' => ['type' => 'TIMESTAMP', 'null' => false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('delivery_code');
        $this->forge->addForeignKey('so_id', 'sales_orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('delivered_by', 'users', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('deliveries');

        $this->db->query("ALTER TABLE deliveries ALTER COLUMN created_at SET DEFAULT CURRENT_TIMESTAMP");
        $this->db->query("ALTER TABLE deliveries ALTER COLUMN updated_at SET DEFAULT CURRENT_TIMESTAMP");

        $this->db->query("ALTER TABLE deliveries ADD CONSTRAINT chk_deliveries_status CHECK (status IN ('pending', 'approved', 'rejected'))");

        // delivery_items
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'delivery_id' => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'item_id' => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'quantity_delivered' => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'warehouse_id' => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'created_at' => ['type' => 'TIMESTAMP', 'null' => false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('delivery_id', 'deliveries', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('item_id', 'items', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('warehouse_id', 'warehouses', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('delivery_items');

        $this->db->query("ALTER TABLE delivery_items ALTER COLUMN created_at SET DEFAULT CURRENT_TIMESTAMP");

        // stocks
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'item_id' => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'warehouse_id' => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'quantity' => ['type' => 'INT', 'default' => 0, 'null' => false],
            'min_stock' => ['type' => 'INT', 'default' => 0, 'null' => true],
            'max_stock' => ['type' => 'INT', 'default' => 0, 'null' => true],
            'created_at' => ['type' => 'TIMESTAMP', 'null' => false],
            'updated_at' => ['type' => 'TIMESTAMP', 'null' => false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('item_id', 'items', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('warehouse_id', 'warehouses', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addUniqueKey(['item_id', 'warehouse_id']);
        $this->forge->createTable('stocks');

        $this->db->query("ALTER TABLE stocks ALTER COLUMN created_at SET DEFAULT CURRENT_TIMESTAMP");
        $this->db->query("ALTER TABLE stocks ALTER COLUMN updated_at SET DEFAULT CURRENT_TIMESTAMP");

        // stock_cards
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'item_id' => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'warehouse_id' => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'transaction_type' => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => false],
            'reference_type' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => false],
            'reference_id' => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'quantity' => ['type' => 'INT', 'null' => false],
            'stock_before' => ['type' => 'INT', 'null' => false],
            'stock_after' => ['type' => 'INT', 'null' => false],
            'transaction_date' => ['type' => 'TIMESTAMP', 'null' => false],
            'notes' => ['type' => 'TEXT', 'null' => true],
            'created_by' => ['type' => 'INT', 'unsigned' => true, 'null' => false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('item_id', 'items', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('warehouse_id', 'warehouses', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('stock_cards');

        $this->db->query("ALTER TABLE stock_cards ADD CONSTRAINT chk_stock_cards_transaction_type CHECK (transaction_type IN ('in', 'out'))");
        $this->db->query("ALTER TABLE stock_cards ADD CONSTRAINT chk_stock_cards_reference_type CHECK (reference_type IN ('grn', 'delivery', 'adjustment'))");

        $this->db->query("ALTER TABLE stock_cards ALTER COLUMN transaction_date SET DEFAULT CURRENT_TIMESTAMP");
    }

    public function down()
    {
        $this->forge->dropTable('stock_cards');
        $this->forge->dropTable('stocks');
        $this->forge->dropTable('delivery_items');
        $this->forge->dropTable('deliveries');
        $this->forge->dropTable('sales_order_items');
        $this->forge->dropTable('sales_orders');
        $this->forge->dropTable('goods_receipt_items');
        $this->forge->dropTable('goods_receipt');
    }
}
