<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Transaction extends Migration
{
    protected $tableName  = 'transaction';
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'          => 'INT',
                'constraint'    => 11,
                'unsigned'      => true,
                'auto_increment' => true,
            ],
            'nota_number' => [
                'type'          => 'VARCHAR',
                'constraint'    => 100,
                'null'          => false,
            ],
            'member' => [
                'type'          => 'INT',
                'unsigned'      => true,
                'constraint'    => 11,
                'null'          => true,
            ],
            'grand_total' => [
                'type'          => 'INT',
                'unsigned'      => true,
                'constraint'    => 11,
                'null'          => true,
            ],
            'user_id' => [
                'type'          => 'INT',
                'unsigned'      => true,
                'constraint'    => 11,
                'null'          => true,
            ],
            'total_pay' => [
                'type'          => 'INT',
                'unsigned'      => true,
                'constraint'    => 11,
                'null'          => true,
                'default'       => null
            ],
            'point_pay' => [
                'type'          => 'INT',
                'unsigned'      => true,
                'constraint'    => 11,
                'null'          => true,
                'default'       => null
            ],
            'point_earned' => [
                'type'          => 'INT',
                'unsigned'      => true,
                'constraint'    => 11,
                'null'          => true,
                'default'       => null
            ],
            'items' => [
                'type'          => 'json',
                'null'          => true
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
        $this->forge->createTable($this->tableName);
    }

    public function down()
    {
        $this->forge->dropTable($this->tableName);
    }
}
