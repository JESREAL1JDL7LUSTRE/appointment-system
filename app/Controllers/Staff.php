<?php

namespace App\Controllers;

use App\Models\AppointmentModel;
use App\Models\ServiceModel;

class Staff extends BaseController
{
    public function dashboard()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $appointmentModel = new AppointmentModel();
        
        $today = date('Y-m-d');
        
        $todaysAppointments = $appointmentModel
            ->select('appointments.*, c.first_name as client_first, c.last_name as client_last, serv.name as service_name')
            ->join('users c', 'c.id = appointments.client_id', 'left')
            ->join('services serv', 'serv.id = appointments.service_id', 'left')
            ->where('appointments.staff_id', $userId)
            ->where('appointments.appointment_date', $today)
            ->whereIn('appointments.status', ['pending', 'confirmed'])
            ->orderBy('appointments.start_time', 'ASC')
            ->findAll();

        $stats = [
            'today_appointments' => count($todaysAppointments),
            'upcoming_week' => $appointmentModel
                ->where('staff_id', $userId)
                ->where('appointment_date >', $today)
                ->where('appointment_date <=', date('Y-m-d', strtotime('+7 days')))
                ->countAllResults(),
            'completed_month' => $appointmentModel
                ->where('staff_id', $userId)
                ->where('status', 'completed')
                ->where('appointment_date >=', date('Y-m-01'))
                ->countAllResults()
        ];
        
        $formattedAppointments = [];
        foreach ($todaysAppointments as $apt) {
            $formattedAppointments[] = [
                'id' => $apt['id'],
                'client_name' => trim(($apt['client_first'] ?? '') . ' ' . ($apt['client_last'] ?? '')),
                'service_name' => $apt['service_name'] ?? 'Unknown',
                'start_time' => $apt['start_time'],
                'end_time' => $apt['end_time'],
                'status' => $apt['status'],
                'client_notes' => $apt['client_notes']
            ];
        }

        $data = [
            'stats' => $stats,
            'today_appointments' => $formattedAppointments
        ];

        return view('staff/dashboard', $data);
    }

    public function appointments()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $appointmentModel = new AppointmentModel();
        
        $appointmentsRaw = $appointmentModel
            ->select('appointments.*, c.first_name as client_first, c.last_name as client_last, c.phone as client_phone, serv.name as service_name, serv.duration_minutes')
            ->join('users c', 'c.id = appointments.client_id', 'left')
            ->join('services serv', 'serv.id = appointments.service_id', 'left')
            ->where('appointments.staff_id', $userId)
            ->orderBy('appointments.appointment_date', 'DESC')
            ->orderBy('appointments.start_time', 'DESC')
            ->findAll();
            
        $appointments = [];
        foreach ($appointmentsRaw as $apt) {
            $appointments[] = [
                'id' => 'APT-' . str_pad($apt['id'], 4, '0', STR_PAD_LEFT),
                'raw_id' => $apt['id'],
                'client_name' => trim(($apt['client_first'] ?? '') . ' ' . ($apt['client_last'] ?? '')),
                'client_phone' => $apt['client_phone'],
                'service_name' => $apt['service_name'] ?? 'Unknown',
                'duration_minutes' => $apt['duration_minutes'],
                'appointment_date' => $apt['appointment_date'],
                'start_time' => $apt['start_time'],
                'end_time' => $apt['end_time'],
                'status' => $apt['status'],
                'client_notes' => $apt['client_notes']
            ];
        }

        $data['appointments'] = $appointments;
        return view('staff/appointments', $data);
    }

    public function schedule()
    {
        // For Phase 2, this serves the static view. 
        // Real implementation of schedule availability requires a robust TimeSlot engine.
        return view('staff/schedule');
    }
    
    public function updateAppointmentStatus($id)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }
        
        $status = $this->request->getPost('status');
        $validStatuses = ['pending', 'confirmed', 'completed', 'cancelled', 'no_show'];
        
        if (!in_array($status, $validStatuses)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid status']);
        }

        $appointmentModel = new AppointmentModel();
        $appointment = $appointmentModel->find($id);
        
        if (!$appointment) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Appointment not found']);
        }
        
        // Ensure staff owns this appointment (or user is admin)
        if ($appointment['staff_id'] != $userId) {
             return $this->response->setJSON(['status' => 'error', 'message' => 'Forbidden']);
        }
        
        if ($appointmentModel->update($id, ['status' => $status])) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Status updated successfully']);
        }
        
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update status']);
    }
}
