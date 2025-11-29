<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SalesProfilesSeeder extends Seeder
{
    public function run()
    {
        $salesProfiles = [
            [
                'sales_id' => 4, // sales01
                'sales_name' => 'Ahmad Wijaya',
                'sales_email' => 'ahmad.wijaya@company.com',
                'sales_phone' => '+62 812-3456-7890',
                'join_date' => '2023-01-15',
                'location' => 'Jakarta, Indonesia',
                'specialization' => 'Enterprise Banking',
                'profile_picture' => null,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'sales_id' => 5, // sales02
                'sales_name' => 'Sari Dewi',
                'sales_email' => 'sari.dewi@company.com',
                'sales_phone' => '+62 813-4567-8901',
                'join_date' => '2023-03-20',
                'location' => 'Bandung, Indonesia',
                'specialization' => 'SMB Sales',
                'profile_picture' => null,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'sales_id' => 6, // sales03
                'sales_name' => 'Budi Santoso',
                'sales_email' => 'budi.santoso@company.com',
                'sales_phone' => '+62 814-5678-9012',
                'join_date' => '2023-02-10',
                'location' => 'Surabaya, Indonesia',
                'specialization' => 'Government Sector',
                'profile_picture' => null,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'sales_id' => 7, // sales04
                'sales_name' => 'Citra Maharani',
                'sales_email' => 'citra.maharani@company.com',
                'sales_phone' => '+62 815-6789-0123',
                'join_date' => '2023-04-05',
                'location' => 'Bali, Indonesia',
                'specialization' => 'Enterprise Technology',
                'profile_picture' => null,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('sales_profiles')->insertBatch($salesProfiles);

        // Sales Performance Data
        $salesPerformance = [
            [
                'sales_id' => 4,
                'total_deals' => 24,
                'won_deals' => 16,
                'lost_deals' => 8,
                'win_rate' => 66.67,
                'total_revenue' => 1850000000,
                'avg_deal_size' => 115625000,
                'performance_rank' => 1,
                'quota_achievement' => 125.50,
                'last_updated' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'sales_id' => 5,
                'total_deals' => 18,
                'won_deals' => 10,
                'lost_deals' => 8,
                'win_rate' => 55.56,
                'total_revenue' => 1200000000,
                'avg_deal_size' => 120000000,
                'performance_rank' => 3,
                'quota_achievement' => 98.75,
                'last_updated' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'sales_id' => 6,
                'total_deals' => 20,
                'won_deals' => 12,
                'lost_deals' => 8,
                'win_rate' => 60.00,
                'total_revenue' => 1450000000,
                'avg_deal_size' => 120833333,
                'performance_rank' => 2,
                'quota_achievement' => 112.30,
                'last_updated' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'sales_id' => 7,
                'total_deals' => 15,
                'won_deals' => 8,
                'lost_deals' => 7,
                'win_rate' => 53.33,
                'total_revenue' => 950000000,
                'avg_deal_size' => 118750000,
                'performance_rank' => 4,
                'quota_achievement' => 85.60,
                'last_updated' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('sales_performance')->insertBatch($salesPerformance);

        echo "Sales profiles and performance data seeded successfully!\n";
    }
}
