<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMasterLicenseTypesTable extends Migration
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
            'license_type' => [
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
        $this->forge->createTable('master_license_types');
    }

    public function down()
    {
        $this->forge->dropTable('master_license_types');
    }
}
