<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LicenseTypeSeeder extends Seeder
{
    public function run()
    {
        $licenseTypes = [
            ['license_type' => 'Enterprise License', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['license_type' => 'Standard License', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['license_type' => 'Professional License', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['license_type' => 'Basic License', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['license_type' => 'Premium License', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['license_type' => 'Trial License', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['license_type' => 'Developer License', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['license_type' => 'Academic License', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['license_type' => 'Cloud Subscription', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['license_type' => 'On-Premise License', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['license_type' => 'SaaS Subscription', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['license_type' => 'Perpetual License', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['license_type' => 'Annual Subscription', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['license_type' => 'Monthly Subscription', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')]
        ];

        $this->db->table('master_license_types')->insertBatch($licenseTypes);

        echo "License Types seeded successfully!\n";
    }
}
