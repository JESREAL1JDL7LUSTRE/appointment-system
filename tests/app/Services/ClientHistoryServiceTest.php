<?php

namespace App\Services;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Models\AppointmentModel;
use App\Models\ServiceModel;

class ClientHistoryServiceTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $seed = 'App\Database\Seeds\MainSeeder';
    protected $refresh = true;

    public function testGetClientHistoryReturnsArray()
    {
        // First we need to manually insert an appointment since the seeder doesn't seed appointments
        $serviceModel = new ServiceModel();
        $serviceId = $serviceModel->insert([
            'name' => 'Test Service',
            'duration_minutes' => 60,
            'price' => 100,
            'is_active' => 1
        ]);

        $apptModel = new AppointmentModel();
        $apptModel->insert([
            'client_id' => 3, // Assuming ID 3 is the seeded Client
            'staff_id' => 2,  // Assuming ID 2 is the seeded Staff
            'service_id' => $serviceId,
            'appointment_date' => '2020-01-01', // past date
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => 'completed',
            'client_notes' => 'Neck pain'
        ]);

        $service = new ClientHistoryService();
        $history = $service->getClientHistory(3);

        $this->assertIsArray($history);
        $this->assertCount(1, $history);
        $this->assertEquals('completed', $history[0]['status']);
        $this->assertEquals('Test Service', $history[0]['service_name']);
        $this->assertEquals('Neck pain', $history[0]['client_notes']);
    }
}
