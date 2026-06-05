<?php

namespace App\Controllers;

use App\Models\AppointmentModel;
use App\Models\ServiceModel;
use App\Models\UserModel;
use App\Models\UserRoleModel;
use App\Models\StaffProfileModel;

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
    
    public function staff()
    {
        $userModel = new UserModel();
        // Join with user_roles to get only role_id = 2, and join staff_profiles
        $staffRaw = $userModel
            ->select('users.id, users.first_name, users.last_name, users.email, users.phone, users.is_active, users.created_at, sp.title, sp.is_available')
            ->join('user_roles ur', 'ur.user_id = users.id')
            ->join('staff_profiles sp', 'sp.user_id = users.id', 'left')
            ->where('ur.role_id', 2)
            ->findAll();
            
        $data['staff_members'] = $staffRaw;
        return view('admin/staff', $data);
    }
    
    public function createStaff()
    {
        $userModel = new UserModel();
        $userRoleModel = new UserRoleModel();
        $staffProfileModel = new StaffProfileModel();
        
        $db = \Config\Database::connect();
        $db->transStart();
        
        $userData = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];
        
        if (!$userModel->insert($userData)) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to create user account.', 'errors' => $userModel->errors()]);
        }
        
        $userId = $userModel->getInsertID();
        
        $userRoleModel->insert([
            'user_id' => $userId,
            'role_id' => 2 // Staff role
        ]);
        
        $staffProfileModel->insert([
            'user_id' => $userId,
            'title' => $this->request->getPost('title') ?: 'Staff',
            'is_available' => $this->request->getPost('is_available') ? 1 : 0,
            'bio' => ''
        ]);
        
        $db->transComplete();
        
        if ($db->transStatus() === false) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Database transaction failed.']);
        }
        
        return $this->response->setJSON(['status' => 'success', 'message' => 'Staff member created successfully.']);
    }
    
    public function updateStaff($id)
    {
        $userModel = new UserModel();
        $staffProfileModel = new StaffProfileModel();
        
        $db = \Config\Database::connect();
        $db->transStart();
        
        $userData = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];
        
        if ($this->request->getPost('password')) {
            $userData['password_hash'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }
        
        $userModel->update($id, $userData);
        
        $profileData = [
            'title' => $this->request->getPost('title'),
            'is_available' => $this->request->getPost('is_available') ? 1 : 0
        ];
        
        // Check if profile exists, if not create it
        if ($staffProfileModel->find($id)) {
            $staffProfileModel->update($id, $profileData);
        } else {
            $profileData['user_id'] = $id;
            $profileData['bio'] = '';
            $staffProfileModel->insert($profileData);
        }
        
        $db->transComplete();
        
        if ($db->transStatus() === false) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Database transaction failed.']);
        }
        
        return $this->response->setJSON(['status' => 'success', 'message' => 'Staff member updated successfully.']);
    }
    
    public function deleteStaff($id)
    {
        $userModel = new UserModel();
        // Instead of hard delete, deactivate them for data integrity
        if ($userModel->update($id, ['is_active' => 0])) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Staff member deactivated successfully.']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to deactivate staff member.']);
    }
}
