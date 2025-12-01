<?php
// app/Database/Migrations/2024_01_01_000002_add_admin_to_users_role_enum.php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAdminToUsersRoleEnum extends Migration
{
    public function up()
    {
        // Method 1: Modify column (lebih aman untuk MySQL)
        $this->db->query("ALTER TABLE users MODIFY COLUMN role ENUM('sales', 'manager', 'bod', 'admin') NOT NULL");

        // Method 2: Atau gunakan forge (tapi hati-hati dengan data existing)
        // $this->forge->modifyColumn('users', [
        //     'role' => [
        //         'type' => 'ENUM',
        //         'constraint' => ['sales', 'manager', 'bod', 'admin'],
        //         'null' => false,
        //     ]
        // ]);
    }

    public function down()
    {
        // Kembalikan ke original (optional)
        $this->db->query("ALTER TABLE users MODIFY COLUMN role ENUM('sales', 'manager', 'bod') NOT NULL");
    }
}
