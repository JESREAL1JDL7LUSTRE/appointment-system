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

        // Fetch inserted role IDs from DB (PostgreSQL-safe — no insertID())
        $adminRoleId = $this->db->table('roles')->where('name', 'Administrator')->get()->getRow()->id;
        $staffRoleId = $this->db->table('roles')->where('name', 'Staff')->get()->getRow()->id;
        $clientRoleId = $this->db->table('roles')->where('name', 'Client')->get()->getRow()->id;

        // 2. Seed Admin User
        $adminData = [
            'email'         => 'admin@example.com',
            'password_hash' => password_hash('password123', PASSWORD_DEFAULT),
            'first_name'    => 'System',
            'last_name'     => 'Admin',
            'phone'         => '09000000000',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        $this->db->table('users')->insert($adminData);

        // PostgreSQL-safe: fetch ID by unique email
        $adminId = $this->db->table('users')->where('email', 'admin@example.com')->get()->getRow()->id;

        $this->db->table('user_roles')->insert([
            'user_id' => $adminId,
            'role_id' => $adminRoleId,
        ]);

        // 3. Seed Staff Members
        for ($i = 0; $i < 5; $i++) {
            $staffEmail = $faker->unique()->safeEmail();
            $staffData  = [
                'email'         => $staffEmail,
                'password_hash' => password_hash('staff123', PASSWORD_DEFAULT),
                'first_name'    => $faker->firstName(),
                'last_name'     => $faker->lastName(),
                'phone'         => $faker->phoneNumber(),
                'is_active'     => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ];
            $this->db->table('users')->insert($staffData);

            // PostgreSQL-safe: fetch ID by unique email
            $staffId = $this->db->table('users')->where('email', $staffEmail)->get()->getRow()->id;

            $this->db->table('user_roles')->insert([
                'user_id' => $staffId,
                'role_id' => $staffRoleId,
            ]);

            $this->db->table('staff_profiles')->insert([
                'user_id'      => $staffId,
                'title'        => $faker->jobTitle(),
                'bio'          => $faker->paragraph(),
                'is_available' => 1,
            ]);
        }

        // 4. Seed Clients
        for ($i = 0; $i < 20; $i++) {
            $clientEmail = $faker->unique()->safeEmail();
            $clientData  = [
                'email'         => $clientEmail,
                'password_hash' => password_hash('client123', PASSWORD_DEFAULT),
                'first_name'    => $faker->firstName(),
                'last_name'     => $faker->lastName(),
                'phone'         => $faker->phoneNumber(),
                'is_active'     => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ];
            $this->db->table('users')->insert($clientData);

            // PostgreSQL-safe: fetch ID by unique email
            $clientId = $this->db->table('users')->where('email', $clientEmail)->get()->getRow()->id;

            $this->db->table('user_roles')->insert([
                'user_id' => $clientId,
                'role_id' => $clientRoleId,
            ]);

            $this->db->table('client_profiles')->insert([
                'user_id'        => $clientId,
                'internal_notes' => 'New client. ' . $faker->sentence(),
            ]);
        }
    }
}
