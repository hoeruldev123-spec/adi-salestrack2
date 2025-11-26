<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SolutionArchitectSeeder extends Seeder
{
    public function run()
    {
        $solutionArchitects = [
            ['sa_name' => 'Rizki Pratama', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['sa_name' => 'Sari Indah', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['sa_name' => 'Fajar Nugroho', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['sa_name' => 'Dewi Lestari', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['sa_name' => 'Bambang Susanto', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['sa_name' => 'Maya Sari', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['sa_name' => 'Hendra Gunawan', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['sa_name' => 'Lina Marlina', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['sa_name' => 'Agus Setiawan', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['sa_name' => 'Rina Wijaya', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')]
        ];

        $this->db->table('master_solution_architects')->insertBatch($solutionArchitects);

        echo "Solution Architects seeded successfully!\n";
    }
}
