<?php

namespace App\Services;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Models\WorkingHourModel;
use App\Models\AppointmentModel;

class ScheduleServiceTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $refresh = true; // Use empty DB for exact control over math logic

    public function testCalculateAvailableSlotsWithNoAppointments()
    {
        $staffId = 1;
        $date = '2030-01-01'; // Tuesday
        
        // Insert Working Hours manually for strict control
        $whModel = new WorkingHourModel();
        $whModel->insert([
            'staff_id' => $staffId,
            'day_of_week' => 2, // Tuesday
            'start_time' => '09:00:00',
            'end_time' => '11:00:00',
            'is_active' => 1
        ]);

        $scheduleService = new ScheduleService();
        $slots = $scheduleService->calculateAvailableSlots($staffId, $date);

        // From 09:00 to 11:00 is exactly 4 30-minute slots
        $this->assertCount(4, $slots);
        $this->assertEquals('09:00:00', $slots[0]);
        $this->assertEquals('09:30:00', $slots[1]);
        $this->assertEquals('10:00:00', $slots[2]);
        $this->assertEquals('10:30:00', $slots[3]);
    }

    public function testCalculateAvailableSlotsWithExistingAppointments()
    {
        $staffId = 1;
        $date = '2030-01-01'; // Tuesday
        
        $whModel = new WorkingHourModel();
        $whModel->insert([
            'staff_id' => $staffId,
            'day_of_week' => 2,
            'start_time' => '09:00:00',
            'end_time' => '11:00:00',
            'is_active' => 1
        ]);

        // Insert a 1-hour appointment from 09:30 to 10:30
        $apptModel = new AppointmentModel();
        $apptModel->insert([
            'client_id' => 2,
            'staff_id' => $staffId,
            'service_id' => 1,
            'appointment_date' => $date,
            'start_time' => '09:30:00',
            'end_time' => '10:30:00',
            'status' => 'confirmed'
        ]);

        $scheduleService = new ScheduleService();
        $slots = $scheduleService->calculateAvailableSlots($staffId, $date);

        // 09:30 and 10:00 slots should be missing (blocked by appointment)
        $this->assertCount(2, $slots);
        $this->assertEquals('09:00:00', $slots[0]);
        $this->assertEquals('10:30:00', $slots[1]);
    }
}
