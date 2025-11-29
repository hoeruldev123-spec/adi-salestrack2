<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSalesPerformanceTable extends Migration
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
            'total_deals' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'comment' => 'Total opportunities handled'
            ],
            'won_deals' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'comment' => 'Successfully closed deals'
            ],
            'lost_deals' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'comment' => 'Unsuccessful deals'
            ],
            'win_rate' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'default' => 0.00,
                'comment' => 'Win rate percentage'
            ],
            'total_revenue' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0.00,
                'comment' => 'Total revenue in IDR'
            ],
            'avg_deal_size' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0.00,
                'comment' => 'Average deal value'
            ],
            'performance_rank' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'Rank among sales team'
            ],
            'quota_achievement' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'default' => 0.00,
                'comment' => 'Percentage of quota achieved'
            ],
            'last_updated' => [
                'type' => 'DATETIME',
                'null' => true
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
        $this->forge->addForeignKey('sales_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('sales_performance');
    }

    public function down()
    {
        $this->forge->dropTable('sales_performance');
    }
}
