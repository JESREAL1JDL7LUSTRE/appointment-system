<?php

namespace App\Services;

use App\Models\WorkingHourModel;
use App\Models\AppointmentModel;

class ScheduleService
{
    protected $whModel;
    protected $apptModel;

    public function __construct()
    {
        $this->whModel = new WorkingHourModel();
        $this->apptModel = new AppointmentModel();
    }

    /**
     * Calculates available 30-minute slots for a staff member on a specific date
     */
    public function calculateAvailableSlots(int $staffId, string $date): array
    {
        $dayOfWeek = date('w', strtotime($date));

        $workingHours = $this->whModel->where('staff_id', $staffId)
                                      ->where('day_of_week', $dayOfWeek)
                                      ->where('is_active', 1)
                                      ->first();

        if (!$workingHours) {
            return []; // No working hours
        }

        $appointments = $this->apptModel->where('staff_id', $staffId)
                                        ->where('appointment_date', $date)
                                        ->whereIn('status', ['pending', 'confirmed'])
                                        ->findAll();

        $slots = [];
        $startTime = strtotime($date . ' ' . $workingHours['start_time']);
        $endTime = strtotime($date . ' ' . $workingHours['end_time']);

        while ($startTime < $endTime) {
            $slotString = date('H:i:s', $startTime);
            $isAvailable = true;

            foreach ($appointments as $appt) {
                $apptStart = strtotime($date . ' ' . $appt['start_time']);
                $apptEnd = strtotime($date . ' ' . $appt['end_time']);
                
                if ($startTime >= $apptStart && $startTime < $apptEnd) {
                    $isAvailable = false;
                    break;
                }
            }

            if ($isAvailable) {
                $slots[] = $slotString;
            }

            $startTime = strtotime('+30 minutes', $startTime);
        }

        return $slots;
    }
}
