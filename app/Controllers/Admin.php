<?php

namespace App\Controllers;

use App\Models\AppointmentModel;
use App\Models\ServiceModel;
use App\Models\UserModel;

class Admin extends BaseController
{
    public function dashboard()
    {
        $appointmentModel = new AppointmentModel();
        $userModel = new UserModel();
        
        $totalAppointments = $appointmentModel->countAllResults();
        
        $totalServices = (new ServiceModel())->countAllResults();
        
        $activeStaff = $userModel->join('user_roles', 'user_roles.user_id = users.id')
            ->where('user_roles.role_id', 2)
            ->where('users.is_active', 1)
            ->countAllResults();
            
        $upcomingAppointments = $appointmentModel->whereIn('status', ['pending', 'confirmed'])
            ->countAllResults();
            
        // Get recent appointments
        $recentAppointmentsRaw = $appointmentModel
            ->select('appointments.*, c.first_name as client_first, c.last_name as client_last, s.first_name as staff_first, s.last_name as staff_last, serv.name as service_name')
            ->join('users c', 'c.id = appointments.client_id', 'left')
            ->join('users s', 's.id = appointments.staff_id', 'left')
            ->join('services serv', 'serv.id = appointments.service_id', 'left')
            ->orderBy('appointments.created_at', 'DESC')
            ->limit(5)
            ->find();
            
        $recentAppointments = [];
        foreach ($recentAppointmentsRaw as $apt) {
            $recentAppointments[] = [
                'id' => 'APT-' . str_pad($apt['id'], 4, '0', STR_PAD_LEFT),
                'client_name' => trim(($apt['client_first'] ?? '') . ' ' . ($apt['client_last'] ?? '')),
                'staff_name' => trim(($apt['staff_first'] ?? '') . ' ' . ($apt['staff_last'] ?? '')),
                'service_name' => $apt['service_name'] ?? 'Unknown',
                'appointment_date' => $apt['appointment_date'],
                'start_time' => $apt['start_time'],
                'end_time' => $apt['end_time'],
                'status' => $apt['status']
            ];
        }

        $data = [
            'stats' => [
                'total_appointments' => $totalAppointments,
                'active_staff_count' => $activeStaff,
                'total_services' => $totalServices,
                'todays_revenue' => 0, // Placeholder
                'upcoming_appointments' => $upcomingAppointments,
            ],
            'recent_appointments' => $recentAppointments
        ];

        return view('admin/dashboard', $data);
    }

    public function services()
    {
        $serviceModel = new ServiceModel();
        $data['services'] = $serviceModel->orderBy('name', 'ASC')->findAll();
        return view('admin/services', $data);
    }
    
    public function createService()
    {
        $serviceModel = new ServiceModel();
        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'duration_minutes' => $this->request->getPost('duration_minutes'),
            'price' => $this->request->getPost('price'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];
        
        if ($serviceModel->insert($data)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Service created successfully.']);
        }
        
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to create service.', 'errors' => $serviceModel->errors()]);
    }
    
    public function updateService($id)
    {
        $serviceModel = new ServiceModel();
        
        // Find existing
        if (!$serviceModel->find($id)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Service not found.']);
        }
        
        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'duration_minutes' => $this->request->getPost('duration_minutes'),
            'price' => $this->request->getPost('price'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];
        
        if ($serviceModel->update($id, $data)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Service updated successfully.']);
        }
        
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update service.', 'errors' => $serviceModel->errors()]);
    }
    
    public function deleteService($id)
    {
        $serviceModel = new ServiceModel();
        if ($serviceModel->delete($id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Service deleted successfully.']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to delete service.']);
    }
    
    public function appointments()
    {
        $appointmentModel = new AppointmentModel();
        $appointmentsRaw = $appointmentModel
            ->select('appointments.*, c.first_name as client_first, c.last_name as client_last, s.first_name as staff_first, s.last_name as staff_last, serv.name as service_name')
            ->join('users c', 'c.id = appointments.client_id', 'left')
            ->join('users s', 's.id = appointments.staff_id', 'left')
            ->join('services serv', 'serv.id = appointments.service_id', 'left')
            ->orderBy('appointments.appointment_date', 'DESC')
            ->orderBy('appointments.start_time', 'DESC')
            ->findAll();
            
        $appointments = [];
        foreach ($appointmentsRaw as $apt) {
            $appointments[] = [
                'id' => 'APT-' . str_pad($apt['id'], 4, '0', STR_PAD_LEFT),
                'client_name' => trim(($apt['client_first'] ?? '') . ' ' . ($apt['client_last'] ?? '')),
                'staff_name' => trim(($apt['staff_first'] ?? '') . ' ' . ($apt['staff_last'] ?? '')),
                'service_name' => $apt['service_name'] ?? 'Unknown',
                'appointment_date' => $apt['appointment_date'],
                'start_time' => $apt['start_time'],
                'end_time' => $apt['end_time'],
                'status' => $apt['status']
            ];
        }

        $data['appointments'] = $appointments;
        return view('admin/appointments', $data);
    }
}
