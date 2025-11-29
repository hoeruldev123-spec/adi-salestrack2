<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSalesProfilesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'sales_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false
            ],
            'sales_name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false
            ],
            'sales_email' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true
            ],
            'sales_phone' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true
            ],
            'join_date' => [
                'type' => 'DATE',
                'null' => true
            ],
            'location' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true
            ],
            'specialization' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
                'comment' => 'e.g., Enterprise Sales, SMB, Government'
            ],
            'profile_picture' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('sales_id');
        $this->forge->addUniqueKey('sales_email');
        $this->forge->addForeignKey('sales_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('sales_profiles');
    }

    public function down()
    {
        $this->forge->dropTable('sales_profiles');
    }
}
