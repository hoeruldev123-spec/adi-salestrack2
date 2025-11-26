<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\UserModel;

class UserSeeder extends Seeder
{
    public function run()
    {
        $userModel = new UserModel();

        $users = [
            [
                'username' => 'admin',
                'name' => 'Administrator',
                'email' => 'admin@saltrack.com',
                'password' => 'admin123',
                'role' => 'manager',
                'is_active' => 1
            ],
            [
                'username' => 'bod01',
                'name' => 'Director Finance',
                'email' => 'bod@saltrack.com',
                'password' => 'bod123',
                'role' => 'bod',
                'is_active' => 1
            ],
            [
                'username' => 'manager01',
                'name' => 'Sales Manager',
                'email' => 'manager@saltrack.com',
                'password' => 'manager123',
                'role' => 'manager',
                'is_active' => 1
            ],
            [
                'username' => 'sales01',
                'name' => 'Ahmad Sales',
                'email' => 'ahmad@saltrack.com',
                'password' => 'sales123',
                'role' => 'sales',
                'is_active' => 1
            ],
            [
                'username' => 'sales02',
                'name' => 'Budi Salesman',
                'email' => 'budi@saltrack.com',
                'password' => 'sales123',
                'role' => 'sales',
                'is_active' => 1
            ],
            [
                'username' => 'sales03',
                'name' => 'Citra Sales Executive',
                'email' => 'citra@saltrack.com',
                'password' => 'sales123',
                'role' => 'sales',
                'is_active' => 1
            ],
            [
                'username' => 'sales04',
                'name' => 'Dian Sales Representative',
                'email' => 'dian@saltrack.com',
                'password' => 'sales123',
                'role' => 'sales',
                'is_active' => 1
            ]
        ];

        foreach ($users as $user) {
            $userModel->insert($user);
        }

        echo "Users seeded successfully!\n";
    }
}
