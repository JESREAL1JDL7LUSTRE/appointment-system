<?php

namespace App\Controllers;

use App\Models\AppointmentModel;
use App\Models\ServiceModel;

class Client extends BaseController
{
    public function dashboard()
    {
        $clientId = session()->get('client_id');
        if (!$clientId) {
            return redirect()->to('/login');
        }

        // Fetch active client details
        $activeClient = (new \App\Models\UserModel())->find($clientId);

        $appointmentModel = new AppointmentModel();
        
        // Fetch all appointments for the client
        $appointmentsRaw = $appointmentModel
            ->select('appointments.*, s.first_name as staff_first, s.last_name as staff_last, serv.name as service_name, serv.duration_minutes')
            ->join('users s', 's.id = appointments.staff_id', 'left')
            ->join('services serv', 'serv.id = appointments.service_id', 'left')
            ->where('appointments.client_id', $clientId)
            ->orderBy('appointments.appointment_date', 'DESC')
            ->orderBy('appointments.start_time', 'DESC')
            ->findAll();
            
        $clientAppointments = [];
        foreach ($appointmentsRaw as $apt) {
            $clientAppointments[] = [
                'id' => $apt['id'],
                'staff_name' => trim(($apt['staff_first'] ?? '') . ' ' . ($apt['staff_last'] ?? '')),
                'service_name' => $apt['service_name'] ?? 'Unknown',
                'appointment_date' => $apt['appointment_date'],
                'start_time' => $apt['start_time'],
                'end_time' => $apt['end_time'],
                'duration' => $apt['duration_minutes'],
                'status' => $apt['status']
            ];
        }

        // Fetch services for the booking modal
        $serviceModel = new ServiceModel();
        $services = $serviceModel->where('is_active', 1)->findAll();

        $data = [
            'clientAppointments' => $clientAppointments,
            'services' => $services,
            'staff' => [], // Dynamic via fetch
            'activeClient' => $activeClient
        ];

        return view('client/dashboard', $data);
    }

    public function appointments()
    {
        $clientId = session()->get('client_id');
        if (!$clientId) {
            return redirect()->to('/login');
        }

        $appointmentModel = new AppointmentModel();
        
        $appointmentsRaw = $appointmentModel
            ->select('appointments.*, s.first_name as staff_first, s.last_name as staff_last, serv.name as service_name, serv.duration_minutes')
            ->join('users s', 's.id = appointments.staff_id', 'left')
            ->join('services serv', 'serv.id = appointments.service_id', 'left')
            ->where('appointments.client_id', $clientId)
            ->orderBy('appointments.appointment_date', 'DESC')
            ->orderBy('appointments.start_time', 'DESC')
            ->findAll();
            
        $appointments = [];
        foreach ($appointmentsRaw as $apt) {
            $appointments[] = [
                'id' => 'APT-' . str_pad($apt['id'], 4, '0', STR_PAD_LEFT),
                'staff_name' => trim(($apt['staff_first'] ?? '') . ' ' . ($apt['staff_last'] ?? '')),
                'service_name' => $apt['service_name'] ?? 'Unknown',
                'duration_minutes' => $apt['duration_minutes'],
                'appointment_date' => $apt['appointment_date'],
                'start_time' => $apt['start_time'],
                'end_time' => $apt['end_time'],
                'status' => $apt['status'],
                'notes' => $apt['client_notes']
            ];
        }

        $data['appointments'] = $appointments;
        return view('client/appointments', $data);
    }
}
