<?php

namespace App\Services;

use App\Models\AppointmentModel;
use App\Models\ServiceModel;
use App\Libraries\NotificationService;

class AppointmentService
{
    protected $appointmentModel;
    protected $serviceModel;
    protected $notificationService;

    public function __construct()
    {
        $this->appointmentModel = new AppointmentModel();
        $this->serviceModel = new ServiceModel();
        $this->notificationService = new NotificationService(); // Still using the previously created Library
    }

    /**
     * Books an appointment if the slot is available
     * @throws \Exception
     */
    public function bookAppointment(int $clientId, int $staffId, int $serviceId, string $date, string $startTime, ?string $clientNotes = null)
    {
        $service = $this->serviceModel->find($serviceId);
        if (!$service) {
            throw new \Exception("Invalid service selected.");
        }

        $endTime = date('H:i:s', strtotime("+{$service['duration_minutes']} minutes", strtotime($startTime)));

        if ($this->appointmentModel->hasConflict($staffId, $date, $startTime, $endTime)) {
            throw new \Exception("The requested time slot is no longer available.");
        }

        $data = [
            'client_id'        => $clientId,
            'staff_id'         => $staffId,
            'service_id'       => $serviceId,
            'appointment_date' => $date,
            'start_time'       => $startTime,
            'end_time'         => $endTime,
            'status'           => 'pending',
            'client_notes'     => $clientNotes,
        ];

        $appointmentId = $this->appointmentModel->insert($data);

        // Async dispatch or immediate dispatch via NotificationService
        $this->notificationService->dispatchEmail(
            $clientId, 
            $appointmentId, 
            'confirmation', 
            'Appointment Requested', 
            "Your appointment on $date at $startTime has been requested."
        );

        return $appointmentId;
    }
}
