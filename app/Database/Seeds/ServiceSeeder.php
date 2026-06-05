<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run()
    {
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
            ],
            [
                'name'             => 'Quick Follow-Up',
                'description'      => 'A brief follow-up appointment to review progress and address questions.',
                'duration_minutes' => 15,
                'price'            => 25.00,
                'is_active'        => 1,
                'created_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'name'             => 'Extended Consultation',
                'description'      => 'An in-depth consultation for complex requirements and planning.',
                'duration_minutes' => 90,
                'price'            => 150.00,
                'is_active'        => 1,
                'created_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'name'             => 'Group Session',
                'description'      => 'A collaborative session designed for multiple participants.',
                'duration_minutes' => 90,
                'price'            => 180.00,
                'is_active'        => 1,
                'created_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'name'             => 'Workshop',
                'description'      => 'A structured workshop focused on training and skill development.',
                'duration_minutes' => 180,
                'price'            => 350.00,
                'is_active'        => 1,
                'created_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'name'             => 'Annual Review',
                'description'      => 'A comprehensive review session covering progress and future plans.',
                'duration_minutes' => 120,
                'price'            => 220.00,
                'is_active'        => 1,
                'created_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'name'             => 'Emergency Appointment',
                'description'      => 'Priority scheduling for urgent matters requiring immediate attention.',
                'duration_minutes' => 45,
                'price'            => 175.00,
                'is_active'        => 1,
                'created_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'name'             => 'Virtual Consultation',
                'description'      => 'An online appointment conducted through video conferencing.',
                'duration_minutes' => 60,
                'price'            => 85.00,
                'is_active'        => 1,
                'created_at'       => date('Y-m-d H:i:s'),
            ],
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
