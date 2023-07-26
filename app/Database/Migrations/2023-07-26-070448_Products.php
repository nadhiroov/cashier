<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Products extends Migration
{
    protected $tableName  = 'product';
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'          => 'INT',
                'constraint'    => 11,
                'unsigned'      => true,
                'auto_increment'=> true,
            ],
            'brand_id' => [
                'type'          => 'INT',
                'constraint'    => 11,
                'null'          => false
            ],
            'barcode' => [
                'type'          => 'VARCHAR',
                'constraint'    => 255,
                'null'          => true,
            ],
            'name' => [
                'type'          => 'VARCHAR',
                'constraint'    => 150,
            ],
            'stock' => [
                'type'          => 'INT',
                'constraint'    => 11,
                'null'          => true,
                'default'       => null
            ],
            'price' => [
                'type'          => 'int',
                'constraint'    => 11,
                'unsigned'      => true,
                'null'          => false
            ],
            'price_history' => [
                'type'          => 'json',
                'default'       => null
            ],
            'sold_history' => [
                'type'          => 'json',
                'default'       => null
            ],
            'incoming_history' => [
                'type'          => 'json',
                'default'       => null
            ],
            'created_at' => [
                'type'          => 'DATETIME',
                'null'          => true,
            ],
            'updated_at' => [
                'type'          => 'DATETIME',
                'null'          => true,
            ],
            'deleted_at' => [
                'type'          => 'DATETIME',
                'null'          => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('brand_id', 'brand', 'id', 'RESTRICT', 'RESTRICT', 'product_brand_id');
        $this->forge->createTable($this->tableName);
    }

    public function down()
    {
        $this->forge->dropTable($this->tableName);
    }
}
