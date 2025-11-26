<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOpportunitiesTable extends Migration
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
            'sales_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'account_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'principal_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'solution_architect_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'pic' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'project_name' => [
                'type' => 'VARCHAR',
                'constraint' => '200',
            ],
            'license_type_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'services_license' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'closing_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'progress' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'opportunity_status' => [
                'type' => 'ENUM',
                'constraint' => ['introduction', 'qualification', 'proposal', 'negotiation', 'closed_won', 'closed_lost'],
                'default' => 'introduction',
            ],
            'progress_percentage' => [
                'type' => 'INT',
                'constraint' => 3,
                'default' => 0,
            ],
            'deal_reg' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'estimated_value' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0,
            ],
            'remarks' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('sales_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('account_id', 'master_accounts', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('principal_id', 'master_principals', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('solution_architect_id', 'master_solution_architects', 'id', 'SET NULL', 'SET NULL');
        $this->forge->addForeignKey('license_type_id', 'master_license_types', 'id', 'SET NULL', 'SET NULL');
        $this->forge->createTable('opportunities');
    }

    public function down()
    {
        $this->forge->dropTable('opportunities');
    }
}
