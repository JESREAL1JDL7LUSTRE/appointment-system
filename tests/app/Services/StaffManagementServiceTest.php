<?php

namespace App\Services;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Models\WorkingHourModel;
use App\Models\TimeOffModel;

class StaffManagementServiceTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $refresh = true;

    public function testSetWorkingHoursSuccess()
    {
        $service = new StaffManagementService();
        $whModel = new WorkingHourModel();

        // Set working hours for Monday (1)
        $id = $service->setWorkingHours(2, 1, '09:00:00', '17:00:00');

        $this->assertIsInt($id);
        $record = $whModel->find($id);
        $this->assertEquals(2, $record['staff_id']);
        $this->assertEquals(1, $record['day_of_week']);
        $this->assertEquals('09:00:00', $record['start_time']);
        $this->assertEquals('17:00:00', $record['end_time']);
    }

    public function testSetWorkingHoursUpdatesExistingRecord()
    {
        $service = new StaffManagementService();
        
        // Insert first
        $service->setWorkingHours(2, 1, '09:00:00', '17:00:00');
        // Update
        $id = $service->setWorkingHours(2, 1, '10:00:00', '16:00:00');

        $whModel = new WorkingHourModel();
        $record = $whModel->find($id);
        $this->assertEquals('10:00:00', $record['start_time']);
        $this->assertEquals('16:00:00', $record['end_time']);
    }

    public function testRequestTimeOffSuccess()
    {
        $service = new StaffManagementService();
        $timeOffModel = new TimeOffModel();

        $id = $service->requestTimeOff(2, '2030-01-01', '2030-01-05', 'Vacation');
        
        $this->assertIsInt($id);
        $record = $timeOffModel->find($id);
        $this->assertEquals(2, $record['staff_id']);
        $this->assertEquals('2030-01-01', $record['start_date']);
        $this->assertEquals('Vacation', $record['reason']);
    }

    public function testRequestTimeOffFailsIfStartDateAfterEndDate()
    {
        $service = new StaffManagementService();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Start date must be before end date.');

        $service->requestTimeOff(2, '2030-01-05', '2030-01-01', 'Time travel');
    }
}
