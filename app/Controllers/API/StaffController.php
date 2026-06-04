<?php

namespace App\Controllers\API;

use CodeIgniter\RESTful\ResourceController;
use App\Services\StaffManagementService;

class StaffController extends ResourceController
{
    public function setWorkingHours()
    {
        $rules = [
            'day_of_week' => 'required|numeric|less_than[7]',
            'start_time'  => 'required',
            'end_time'    => 'required',
            'is_active'   => 'required|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $staffId = session()->get('user_id');
        $service = new StaffManagementService();

        try {
            $service->setWorkingHours(
                $staffId,
                (int)$this->request->getVar('day_of_week'),
                $this->request->getVar('start_time'),
                $this->request->getVar('end_time'),
                (bool)$this->request->getVar('is_active')
            );

            return $this->respond(['message' => 'Working hours updated successfully'], 200);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), 400);
        }
    }

    public function requestTimeOff()
    {
        $rules = [
            'start_date' => 'required|valid_date[Y-m-d]',
            'end_date'   => 'required|valid_date[Y-m-d]',
            'reason'     => 'required|max_length[255]'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $staffId = session()->get('user_id');
        $service = new StaffManagementService();

        try {
            $service->requestTimeOff(
                $staffId,
                $this->request->getVar('start_date'),
                $this->request->getVar('end_date'),
                $this->request->getVar('reason')
            );

            return $this->respondCreated(['message' => 'Time off requested successfully']);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), 400);
        }
    }
}
