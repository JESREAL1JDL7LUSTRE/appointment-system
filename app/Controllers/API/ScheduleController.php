<?php

namespace App\Controllers\API;

use CodeIgniter\RESTful\ResourceController;
use App\Services\ScheduleService;

class ScheduleController extends ResourceController
{
    public function slots()
    {
        $staffId = $this->request->getVar('staff_id');
        $date = $this->request->getVar('date');
        
        if (!$staffId || !$date) {
            return $this->failValidationErrors('Missing staff_id or date parameters');
        }

        $scheduleService = new ScheduleService();
        $slots = $scheduleService->calculateAvailableSlots((int)$staffId, $date);

        return $this->respond(['slots' => $slots], 200);
    }
}
