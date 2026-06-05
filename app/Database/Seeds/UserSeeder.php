<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // ----------------------------------------------------------------
        // 1. Seed Roles
        // ----------------------------------------------------------------
        $roles = [
            ['name' => 'Administrator', 'description' => 'System administrator with full access'],
            ['name' => 'Staff',         'description' => 'Service provider'],
            ['name' => 'Client',        'description' => 'Customer who books appointments'],
        ];
        $this->db->table('roles')->insertBatch($roles);

        // Fetch IDs by name (PostgreSQL-safe — no insertID())
        $adminRoleId  = $this->db->table('roles')->where('name', 'Administrator')->get()->getRow()->id;
        $staffRoleId  = $this->db->table('roles')->where('name', 'Staff')->get()->getRow()->id;
        $clientRoleId = $this->db->table('roles')->where('name', 'Client')->get()->getRow()->id;

        // ----------------------------------------------------------------
        // 2. Seed Admin User
        // ----------------------------------------------------------------
        $this->db->table('users')->insert([
            'email'         => 'admin@example.com',
            'password_hash' => password_hash('password123', PASSWORD_DEFAULT),
            'first_name'    => 'System',
            'last_name'     => 'Admin',
            'phone'         => '09000000000',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);
        $adminId = $this->db->table('users')->where('email', 'admin@example.com')->get()->getRow()->id;
        $this->db->table('user_roles')->insert(['user_id' => $adminId, 'role_id' => $adminRoleId]);

        // ----------------------------------------------------------------
        // 3. Seed Staff Members (hardcoded — no Faker needed)
        // ----------------------------------------------------------------
        $staffMembers = [
            ['first_name' => 'Maria',   'last_name' => 'Santos',    'email' => 'maria.santos@staff.com',   'phone' => '09111111111'],
            ['first_name' => 'Jose',    'last_name' => 'Reyes',     'email' => 'jose.reyes@staff.com',     'phone' => '09222222222'],
            ['first_name' => 'Ana',     'last_name' => 'Cruz',      'email' => 'ana.cruz@staff.com',       'phone' => '09333333333'],
            ['first_name' => 'Miguel',  'last_name' => 'Garcia',    'email' => 'miguel.garcia@staff.com',  'phone' => '09444444444'],
            ['first_name' => 'Lucia',   'last_name' => 'Flores',    'email' => 'lucia.flores@staff.com',   'phone' => '09555555555'],
        ];

        foreach ($staffMembers as $staff) {
            $this->db->table('users')->insert([
                'email'         => $staff['email'],
                'password_hash' => password_hash('staff123', PASSWORD_DEFAULT),
                'first_name'    => $staff['first_name'],
                'last_name'     => $staff['last_name'],
                'phone'         => $staff['phone'],
                'is_active'     => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ]);
            $staffId = $this->db->table('users')->where('email', $staff['email'])->get()->getRow()->id;
            $this->db->table('user_roles')->insert(['user_id' => $staffId, 'role_id' => $staffRoleId]);
            $this->db->table('staff_profiles')->insert([
                'user_id'      => $staffId,
                'title'        => 'Service Specialist',
                'bio'          => 'Experienced professional providing quality service.',
                'is_available' => 1,
            ]);
        }

        // ----------------------------------------------------------------
        // 4. Seed Sample Clients (hardcoded — no Faker needed)
        // ----------------------------------------------------------------
        $clients = [
            ['first_name' => 'Juan',     'last_name' => 'Dela Cruz',  'email' => 'juan.delacruz@client.com',  'phone' => '09600000001'],
            ['first_name' => 'Rosa',     'last_name' => 'Mendoza',    'email' => 'rosa.mendoza@client.com',   'phone' => '09600000002'],
            ['first_name' => 'Carlos',   'last_name' => 'Aquino',     'email' => 'carlos.aquino@client.com',  'phone' => '09600000003'],
            ['first_name' => 'Elena',    'last_name' => 'Bautista',   'email' => 'elena.bautista@client.com', 'phone' => '09600000004'],
            ['first_name' => 'Pedro',    'last_name' => 'Villanueva', 'email' => 'pedro.villanueva@client.com','phone' => '09600000005'],
        ];

        foreach ($clients as $client) {
            $this->db->table('users')->insert([
                'email'         => $client['email'],
                'password_hash' => password_hash('client123', PASSWORD_DEFAULT),
                'first_name'    => $client['first_name'],
                'last_name'     => $client['last_name'],
                'phone'         => $client['phone'],
                'is_active'     => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ]);
            $clientId = $this->db->table('users')->where('email', $client['email'])->get()->getRow()->id;
            $this->db->table('user_roles')->insert(['user_id' => $clientId, 'role_id' => $clientRoleId]);
            $this->db->table('client_profiles')->insert([
                'user_id'        => $clientId,
                'internal_notes' => 'Sample client account.',
            ]);
        }
    }
}
