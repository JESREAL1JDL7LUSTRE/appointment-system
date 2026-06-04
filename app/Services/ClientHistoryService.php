<?php

namespace App\Services;

use App\Models\AppointmentModel;

class ClientHistoryService
{
    protected $appointmentModel;

    public function __construct()
    {
        $this->appointmentModel = new AppointmentModel();
    }

    /**
     * Retrieve the appointment history for a specific client.
     * Joins with services and staff tables to provide a comprehensive record.
     */
    public function getClientHistory(int $clientId): array
    {
        $db = \Config\Database::connect();
        
        $query = $db->table('appointments a')
                    ->select('a.id, a.appointment_date, a.start_time, a.end_time, a.status, a.client_notes, s.name as service_name, s.price, u.first_name as staff_first_name, u.last_name as staff_last_name')
                    ->join('services s', 's.id = a.service_id')
                    ->join('users u', 'u.id = a.staff_id')
                    ->where('a.client_id', $clientId)
                    ->orderBy('a.appointment_date', 'DESC')
                    ->orderBy('a.start_time', 'DESC')
                    ->get();

        return $query->getResultArray();
    }
}
