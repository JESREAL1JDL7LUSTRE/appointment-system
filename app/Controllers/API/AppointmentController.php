<?php

namespace App\Controllers\API;

use CodeIgniter\RESTful\ResourceController;
use App\Services\AppointmentService;

class AppointmentController extends ResourceController
{
    public function create()
    {
        $rules = [
            'staff_id'         => 'required|is_natural_no_zero',
            'service_id'       => 'required|is_natural_no_zero',
            'appointment_date' => 'required|valid_date[Y-m-d]',
            'start_time'       => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $clientId = session()->get('user_id'); 
        if (!$clientId) {
            return $this->failUnauthorized('You must be logged in as a client to book an appointment.');
        }

        $apptService = new AppointmentService();

        try {
            $appointmentId = $apptService->bookAppointment(
                $clientId,
                $this->request->getVar('staff_id'),
                $this->request->getVar('service_id'),
                $this->request->getVar('appointment_date'),
                $this->request->getVar('start_time'),
                $this->request->getVar('client_notes')
            );

            return $this->respondCreated(['message' => 'Appointment booked successfully', 'appointment_id' => $appointmentId]);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), 400); // 400 Bad Request (Conflict or Invalid)
        }
    }
}
