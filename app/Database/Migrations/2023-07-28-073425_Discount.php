<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Discount extends Migration
{
    protected $tableName  = 'discount';
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'          => 'INT',
                'constraint'    => 11,
                'unsigned'      => true,
                'auto_increment' => true,
            ],
            'product_id' => [
                'type'          => 'INT',
                'unsigned'      => true,
                'constraint'    => 11,
                'null'          => true,
            ],
            'discount' => [
                'type'          => 'FLOAT',
                'null'          => false
            ],
            'date_start' => [
                'type'          => 'DATETIME',
                'null'          => false,
                'null'          => true,
            ],
            'date_end' => [
                'type'          => 'DATETIME',
                'null'          => false,
                'null'          => true,
            ],
            'created_at' => [
                'type'          => 'DATETIME',
                'null'          => true,
            ],
            'updated_at' => [
                'type'          => 'DATETIME',
                'null'          => true,
            ]
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('product_id', 'product', 'id', 'CASCADE', 'CASCADE', 'discount_product_id');
        $this->forge->createTable($this->tableName);
    }

    public function down()
    {
        $this->forge->dropTable($this->tableName);
    }
}
