<?php

namespace App\Models;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Models\AppointmentModel;

class AppointmentModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    
    // Seed all data so we have staff, clients, and services available
    protected $seed = 'App\Database\Seeds\MainSeeder';
    protected $namespace = 'App';

    public function testAppointmentCreation()
    {
        $appointmentModel = new AppointmentModel();
        
        $db = db_connect();

        // Grab a random client, staff, and service from seeded data
        $client = $db->table('client_profiles')->select('user_id')->limit(1)->get()->getRowArray();
        $staff = $db->table('staff_profiles')->select('user_id')->limit(1)->get()->getRowArray();
        $service = $db->table('services')->select('id')->limit(1)->get()->getRowArray();
        
        $this->assertNotNull($client, "No client profiles found.");
        $this->assertNotNull($staff, "No staff profiles found.");
        $this->assertNotNull($service, "No services found.");

        $data = [
            'client_id'        => $client['user_id'],
            'staff_id'         => $staff['user_id'],
            'service_id'       => $service['id'],
            'appointment_date' => date('Y-m-d', strtotime('+1 week')),
            'start_time'       => '09:00:00',
            'end_time'         => '10:00:00',
            'status'           => 'pending',
            'client_notes'     => 'Looking forward to it.',
        ];

        $appointmentId = $appointmentModel->insert($data);
        $this->assertIsNumeric($appointmentId);

        $savedAppt = $appointmentModel->find($appointmentId);
        $this->assertEquals('pending', $savedAppt['status']);
        $this->assertEquals($data['appointment_date'], $savedAppt['appointment_date']);
        $this->assertEquals('Looking forward to it.', $savedAppt['client_notes']);
    }
}
