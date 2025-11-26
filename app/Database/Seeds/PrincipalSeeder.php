<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PrincipalSeeder extends Seeder
{
    public function run()
    {
        $principals = [
            ['principal_name' => 'Microsoft', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['principal_name' => 'Oracle', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['principal_name' => 'IBM', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['principal_name' => 'Cisco', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['principal_name' => 'VMware', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['principal_name' => 'Dell EMC', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['principal_name' => 'HP Enterprise', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['principal_name' => 'SAP', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['principal_name' => 'Salesforce', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['principal_name' => 'Adobe', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['principal_name' => 'Amazon Web Services', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['principal_name' => 'Google Cloud', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['principal_name' => 'Microsoft Azure', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['principal_name' => 'Fortinet', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['principal_name' => 'Palo Alto Networks', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['principal_name' => 'Check Point', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['principal_name' => 'Symantec', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['principal_name' => 'McAfee', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['principal_name' => 'Red Hat', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['principal_name' => 'SUSE', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')]
        ];

        // Using DB query builder instead of Model
        $this->db->table('master_principals')->insertBatch($principals);

        echo "Principals seeded successfully!\n";
    }
}
