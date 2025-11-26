<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run()
    {
        $this->call('UserSeeder');
        $this->call('AccountSeeder');
        $this->call('PrincipalSeeder');
        $this->call('SolutionArchitectSeeder');
        $this->call('LicenseTypeSeeder');
        $this->call('OpportunitySeeder');
    }
}
