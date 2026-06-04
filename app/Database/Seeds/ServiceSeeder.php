<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create();

        // 1. Seed Services
        $services = [
            [
                'name'             => 'Initial Consultation',
                'description'      => 'A 30-minute introductory meeting to discuss needs and goals.',
                'duration_minutes' => 30,
                'price'            => 50.00,
                'is_active'        => 1,
                'created_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'name'             => 'Standard Service',
                'description'      => 'A 60-minute standard service session.',
                'duration_minutes' => 60,
                'price'            => 100.00,
                'is_active'        => 1,
                'created_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'name'             => 'Premium Package',
                'description'      => 'A comprehensive 2-hour intensive session.',
                'duration_minutes' => 120,
                'price'            => 250.00,
                'is_active'        => 1,
                'created_at'       => date('Y-m-d H:i:s'),
            ]
        ];

        $this->db->table('services')->insertBatch($services);
        $serviceIds = [1, 2, 3];

        // 2. Link Services to Staff
        // We know from UserSeeder that staff IDs will be roughly 2 through 6 (since Admin is 1)
        // For robustness, let's fetch staff IDs from the DB
        $staffMembers = $this->db->table('staff_profiles')->select('user_id')->get()->getResultArray();

        $staffServicesData = [];
        foreach ($staffMembers as $staff) {
            // Give each staff member 1 to 3 random services
            $assignedServices = (array) array_rand(array_flip($serviceIds), rand(1, 3));
            
            foreach ($assignedServices as $serviceId) {
                $staffServicesData[] = [
                    'staff_id'   => $staff['user_id'],
                    'service_id' => $serviceId,
                ];
            }
        }

        if (!empty($staffServicesData)) {
            $this->db->table('staff_services')->insertBatch($staffServicesData);
        }
    }
}
