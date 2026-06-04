<?php

namespace App\Controllers\API;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class AppointmentControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;
    use DatabaseTestTrait;

    protected $seed = 'App\Database\Seeds\MainSeeder';
    protected $refresh = true;

    public function testCreateAppointmentReturns401IfNotLoggedIn()
    {
        $result = $this->post('api/client/appointments', [
            'staff_id' => 2,
            'service_id' => 1,
            'appointment_date' => '2030-01-01',
            'start_time' => '10:00:00'
        ]);

        $result->assertStatus(401);
        $result->assertJSONExact(['error' => 'Unauthorized: Please log in first.']);
    }

    public function testCreateAppointmentSuccess()
    {
        // Mock a logged-in client session
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['role'] = 'Client';
        $_SESSION['user_id'] = 10;

        $result = $this->withSession()->post('api/client/appointments', [
            'staff_id' => 2,
            'service_id' => 1,
            'appointment_date' => '2030-01-01',
            'start_time' => '10:00:00'
        ]);

        $result->assertStatus(201); // Created
        $result->assertJSONFragment(['message' => 'Appointment booked successfully']);
    }

    public function testCreateAppointmentValidationFailure()
    {
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['role'] = 'Client';
        $_SESSION['user_id'] = 10;

        // Missing start_time
        $result = $this->withSession()->post('api/client/appointments', [
            'staff_id' => 2,
            'service_id' => 1,
            'appointment_date' => '2030-01-01',
        ]);

        $result->assertStatus(400); // Fail Validation
    }
}
