<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SettingPoint extends Migration
{
    protected $tableName  = 'point';
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'          => 'INT',
                'constraint'    => 11,
                'unsigned'      => true,
                'auto_increment' => true,
            ],
            'nominal' => [
                'type'          => 'INT',
                'unsigned'      => true,
                'constraint'    => 11,
                'null'          => true,
            ],
            'point' => [
                'type'          => 'INT',
                'unsigned'      => true,
                'constraint'    => 11,
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
