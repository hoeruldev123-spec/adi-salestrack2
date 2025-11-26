<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMasterSolutionArchitectsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'sa_name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('master_solution_architects');
    }

    public function down()
    {
        $this->forge->dropTable('master_solution_architects');
    }
}
