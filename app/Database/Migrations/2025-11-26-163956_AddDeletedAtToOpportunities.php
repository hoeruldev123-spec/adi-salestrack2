<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDeletedAtToOpportunities extends Migration
{
    public function up()
    {
        $this->forge->addColumn('opportunities', [
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('opportunities', 'deleted_at');
    }
}
