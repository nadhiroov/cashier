<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterProduct extends Migration
{
    protected $tableName  = 'product';
    public function up()
    {
        $alterfields = [
            'percent' => [
                'type'          => 'INT',
                'constraint'    => 11,
                'unsigned'      => true,
                'null'          => true
            ],
            'purchase_price' => [
                'type'          => 'INT',
                'constraint'    => 11,
                'unsigned'      => true,
                'null'          => true
            ],
        ];
        $this->forge->addColumn($this->tableName, $alterfields);
    }

    public function down()
    {
        $this->forge->dropColumn($this->tableName, 'percent, purchase_price');
    }
}
