<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class UserSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create();

        // 1. Seed Roles
        $roles = [
            ['name' => 'Administrator', 'description' => 'System administrator with full access'],
            ['name' => 'Staff', 'description' => 'Service provider'],
            ['name' => 'Client', 'description' => 'Customer who books appointments'],
        ];
        $this->db->table('roles')->insertBatch($roles);

        // Fetch inserted role IDs (assuming sequential 1, 2, 3)
        $adminRoleId = 1;
        $staffRoleId = 2;
        $clientRoleId = 3;

        // 2. Seed Admin User
        $adminData = [
            'email'         => 'admin@example.com',
            'password_hash' => password_hash('password123', PASSWORD_DEFAULT),
            'first_name'    => 'System',
            'last_name'     => 'Admin',
            'phone'         => $faker->phoneNumber(),
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        $this->db->table('users')->insert($adminData);
        $adminId = $this->db->insertID();
        
        $this->db->table('user_roles')->insert([
            'user_id' => $adminId,
            'role_id' => $adminRoleId,
        ]);

        // 3. Seed Staff Members
        for ($i = 0; $i < 5; $i++) {
            $staffData = [
                'email'         => clone $faker->unique()->safeEmail(),
                'password_hash' => password_hash('staff123', PASSWORD_DEFAULT),
                'first_name'    => clone $faker->firstName(),
                'last_name'     => clone $faker->lastName(),
                'phone'         => clone $faker->phoneNumber(),
                'is_active'     => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ];
            $this->db->table('users')->insert($staffData);
            $staffId = $this->db->insertID();

            // Assign Role
            $this->db->table('user_roles')->insert([
                'user_id' => $staffId,
                'role_id' => $staffRoleId,
            ]);

            // Create Staff Profile
            $this->db->table('staff_profiles')->insert([
                'user_id'      => $staffId,
                'title'        => clone $faker->jobTitle(),
                'bio'          => clone $faker->paragraph(),
                'is_available' => 1,
            ]);
        }

        // 4. Seed Clients
        for ($i = 0; $i < 20; $i++) {
            $clientData = [
                'email'         => clone $faker->unique()->safeEmail(),
                'password_hash' => password_hash('client123', PASSWORD_DEFAULT),
                'first_name'    => clone $faker->firstName(),
                'last_name'     => clone $faker->lastName(),
                'phone'         => clone $faker->phoneNumber(),
                'is_active'     => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ];
            $this->db->table('users')->insert($clientData);
            $clientId = $this->db->insertID();

            // Assign Role
            $this->db->table('user_roles')->insert([
                'user_id' => $clientId,
                'role_id' => $clientRoleId,
            ]);

            // Create Client Profile
            $this->db->table('client_profiles')->insert([
                'user_id'        => $clientId,
                'internal_notes' => 'New client. ' . clone $faker->sentence(),
            ]);
        }
    }
}
