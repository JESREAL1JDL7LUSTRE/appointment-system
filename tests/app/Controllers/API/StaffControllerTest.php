<?php

namespace App\Controllers\API;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class StaffControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;
    use DatabaseTestTrait;

    protected $seed = 'App\Database\Seeds\MainSeeder';
    protected $refresh = true;

    public function testSetWorkingHoursSuccess()
    {
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['role'] = 'Staff';
        $_SESSION['user_id'] = 2; // seed staff user

        $result = $this->withSession()->post('api/staff/working-hours', [
            'day_of_week' => 1,
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
            'is_active' => 1
        ]);

        $result->assertStatus(200);
        $result->assertJSONFragment(['message' => 'Working hours updated successfully']);
    }

    public function testRequestTimeOffSuccess()
    {
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['role'] = 'Staff';
        $_SESSION['user_id'] = 2;

        $result = $this->withSession()->post('api/staff/time-off', [
            'start_date' => '2030-01-01',
            'end_date' => '2030-01-05',
            'reason' => 'Vacation'
        ]);

        $result->assertStatus(201);
        $result->assertJSONFragment(['message' => 'Time off requested successfully']);
    }

    public function testFailsIfUnauthorized()
    {
        $result = $this->post('api/staff/working-hours', [
            'day_of_week' => 1,
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
            'is_active' => 1
        ]);

        $result->assertStatus(401);
    }
}
