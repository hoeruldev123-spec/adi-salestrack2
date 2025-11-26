<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AccountSeeder extends Seeder
{
    public function run()
    {
        $accounts = [
            ['account_name' => 'Bank Central Asia', 'is_active' => 1],
            ['account_name' => 'Bank Mandiri', 'is_active' => 1],
            ['account_name' => 'Bank Rakyat Indonesia', 'is_active' => 1],
            ['account_name' => 'Bank Negara Indonesia', 'is_active' => 1],
            ['account_name' => 'Telkom Indonesia', 'is_active' => 1],
            ['account_name' => 'Indosat Ooredoo', 'is_active' => 1],
            ['account_name' => 'XL Axiata', 'is_active' => 1],
            ['account_name' => 'PT. Astra International', 'is_active' => 1],
            ['account_name' => 'PT. Unilever Indonesia', 'is_active' => 1],
            ['account_name' => 'PT. Kalbe Farma', 'is_active' => 1],
            ['account_name' => 'PT. Mayora Indah', 'is_active' => 1],
            ['account_name' => 'PT. Gudang Garam', 'is_active' => 1],
            ['account_name' => 'PT. Semen Indonesia', 'is_active' => 1],
            ['account_name' => 'PT. Pertamina', 'is_active' => 1],
            ['account_name' => 'PT. PLN', 'is_active' => 1],
            ['account_name' => 'PT. Garuda Indonesia', 'is_active' => 1],
            ['account_name' => 'PT. Lion Air', 'is_active' => 1],
            ['account_name' => 'PT. Sinar Mas', 'is_active' => 1],
            ['account_name' => 'PT. Djarum', 'is_active' => 1],
            ['account_name' => 'PT. Salim Group', 'is_active' => 1]
        ];

        // Using DB query builder instead of Model
        $this->db->table('master_accounts')->insertBatch($accounts);

        echo "Accounts seeded successfully!\n";
    }
}
