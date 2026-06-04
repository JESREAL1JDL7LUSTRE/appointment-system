<?php

namespace App\Services;

use App\Models\WorkingHourModel;
use App\Models\TimeOffModel;

class StaffManagementService
{
    protected $whModel;
    protected $timeOffModel;

    public function __construct()
    {
        $this->whModel = new WorkingHourModel();
        $this->timeOffModel = new TimeOffModel();
    }

    /**
     * Set or update working hours for a specific day of the week
     */
    public function setWorkingHours(int $staffId, int $dayOfWeek, string $startTime, string $endTime, bool $isActive = true)
    {
        if ($dayOfWeek < 0 || $dayOfWeek > 6) {
            throw new \Exception("Invalid day of week. Must be between 0 (Sunday) and 6 (Saturday).");
        }

        $existing = $this->whModel->where('staff_id', $staffId)
                                  ->where('day_of_week', $dayOfWeek)
                                  ->first();

        $data = [
            'staff_id'    => $staffId,
            'day_of_week' => $dayOfWeek,
            'start_time'  => $startTime,
            'end_time'    => $endTime,
            'is_active'   => $isActive ? 1 : 0
        ];

        if ($existing) {
            $this->whModel->update($existing['id'], $data);
            return $existing['id'];
        } else {
            return $this->whModel->insert($data);
        }
    }

    /**
     * Block off a specific date range for a staff member (Vacation/Sick leave)
     */
    public function requestTimeOff(int $staffId, string $startDate, string $endDate, string $reason)
    {
        if (strtotime($startDate) > strtotime($endDate)) {
            throw new \Exception("Start date must be before end date.");
        }

        $data = [
            'staff_id'   => $staffId,
            'start_date' => $startDate,
            'end_date'   => $endDate,
            'reason'     => $reason,
            'status'     => 'approved' // Auto-approve for now, can be changed to pending for admin approval
        ];

        return $this->timeOffModel->insert($data);
    }
}
