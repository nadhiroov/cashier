<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Alteruser extends Migration
{
    protected $tableName  = 'user';
    public function up()
    {
        $fields = [
            'img' => [
                'type'      => 'VARCHAR',
                'default'   => 'profile.png',
                'null'      => true,
                'constraint'=> 100,
            ],
            'is_admin' => [
                'type'      => 'ENUM("1","0")',
                'default'   => '0',
                'null'      => true,
            ],
            'theme' => [
                'type'      => 'VARCHAR',
                'default'   => 'light',
                'null'      => true,
                'constraint' => 13,
            ],
        ];
        $this->forge->addColumn($this->tableName, $fields);
        $this->forge->renameTable('user', 'users');
    }

    public function down()
    {
        $this->forge->dropColumn($this->tableName, 'ATTENDANCE');
        $this->forge->renameTable('users', 'user');
    }
}
