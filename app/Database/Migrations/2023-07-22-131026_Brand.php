<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Brand extends Migration
{
    protected $tableName  = 'brand';
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'          => 'INT',
                'constraint'    => 11,
                'unsigned'      => true,
                'auto_increment' => true,
            ],
            'brand' => [
                'type'          => 'VARCHAR',
                'constraint'    => 100,
            ],
            'category_id'       => [
                'type'          => 'INT',
                'constraint'    => 11,
                'unsigned'      => true,
                'null'          => true
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('category_id', 'category', 'id', 'RESTRICT', 'RESTRICT', 'brand_category_id');
        $this->forge->createTable($this->tableName);
    }

    public function down()
    {
        $this->forge->dropTable($this->tableName);
    }
}
