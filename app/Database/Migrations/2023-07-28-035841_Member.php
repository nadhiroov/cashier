<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Member extends Migration
{
    protected $tableName  = 'member';
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'          => 'INT',
                'constraint'    => 11,
                'unsigned'      => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'          => 'VARCHAR',
                'constraint'    => 200,
                'null'          => false,
            ],
            'address' => [
                'type'          => 'VARCHAR',
                'constraint'    => 250,
                'null'          => true,
                'default'       => null
            ],
            'phone' => [
                'type'          => 'VARCHAR',
                'constraint'    => 50,
                'null'          => true,
                'default'       => null
            ],
            'point' => [
                'type'          => 'INT',
                'constraint'    => 11,
                'null'          => true,
                'default'       => 0
            ],
            'point_history' => [
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
        $this->forge->createTable($this->tableName);
    }

    public function down()
    {
        $this->forge->dropTable($this->tableName);
    }
}
