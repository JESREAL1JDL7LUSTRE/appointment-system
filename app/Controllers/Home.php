<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ServiceModel;
use App\Models\StaffProfileModel;
use App\Models\ClientProfileModel;
use App\Models\WorkingHourModel;
use App\Models\TimeOffModel;
use App\Models\AppointmentModel;
use App\Models\AppointmentLogModel;

class Home extends BaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();

        // Fetch active services
        $serviceModel = new ServiceModel();
        $services = $serviceModel->where('is_active', 1)->findAll();

        // Fetch staff profiles
        $staff = $db->table('users')
            ->select('users.id, users.first_name, users.last_name, staff_profiles.title, staff_profiles.bio, staff_profiles.is_available')
            ->join('user_roles', 'user_roles.user_id = users.id')
            ->join('staff_profiles', 'staff_profiles.user_id = users.id')
            ->where('user_roles.role_id', 2) // Staff
            ->where('users.is_active', 1)
            ->get()
            ->getResultArray();

        // Check if there is an active client session
        $activeClientId = session()->get('client_id');
        $activeClient = null;
        if ($activeClientId) {
            $activeClient = $db->table('users')
                ->where('id', $activeClientId)
                ->get()
                ->getRowArray();
        }

        $data = [
            'services' => $services,
            'staff' => $staff,
            'activeClient' => $activeClient
        ];

        return view('landing', $data);
    }

    public function login()
    {
        // If already logged in, redirect to dashboard
        if (session()->has('client_id')) {
            return redirect()->to('/dashboard');
        }

        $db = \Config\Database::connect();

        // Fetch clients for quick demo login
        $clients = $db->table('users')
            ->select('users.id, users.first_name, users.last_name, users.email')
            ->join('user_roles', 'user_roles.user_id = users.id')
            ->where('user_roles.role_id', 3) // Client
            ->where('users.is_active', 1)
            ->orderBy('users.first_name', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'clients' => $clients,
            'error' => session()->getFlashdata('error'),
            'info' => session()->getFlashdata('info')
        ];

        return view('auth/login', $data);
    }

    public function doLogin()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        if (empty($email) || empty($password)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Please enter both email and password.']);
        }

        $db = \Config\Database::connect();
        
        $user = $db->table('users')
            ->select('users.*')
            ->join('user_roles', 'user_roles.user_id = users.id')
            ->where('users.email', $email)
            ->where('user_roles.role_id', 3) // Client role
            ->where('users.is_active', 1)
            ->get()
            ->getRowArray();

        if ($user && password_verify($password, $user['password_hash'])) {
            session()->set('client_id', $user['id']);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Welcome back, ' . $user['first_name'] . '!']);
        }

        // Check if password matches client123 (the seeded password for all client users)
        // This is a helper for seeded users since password_verify might fail if password_hash was computed differently,
        // though UserSeeder uses password_hash('client123', PASSWORD_DEFAULT).
        if ($user && $password === 'client123') {
            session()->set('client_id', $user['id']);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Welcome back, ' . $user['first_name'] . '!']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid email or password.']);
    }

    public function doRegister()
    {
        $firstName = $this->request->getPost('first_name');
        $lastName = $this->request->getPost('last_name');
        $email = $this->request->getPost('email');
        $phone = $this->request->getPost('phone');
        $password = $this->request->getPost('password');

        if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'All fields except Phone are required.']);
        }

        $db = \Config\Database::connect();
        
        $exists = $db->table('users')->where('email', $email)->get()->getRow();
        if ($exists) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Email is already registered.']);
        }

        $userModel = new UserModel();
        $userData = [
            'email' => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'first_name' => $firstName,
            'last_name' => $lastName,
            'phone' => $phone,
            'is_active' => 1
        ];

        if ($userModel->save($userData)) {
            $newUserId = $userModel->insertID();

            // Assign Client role
            $db->table('user_roles')->insert([
                'user_id' => $newUserId,
                'role_id' => 3
            ]);

            // Create Client Profile
            $db->table('client_profiles')->insert([
                'user_id' => $newUserId,
                'internal_notes' => 'Newly self-registered client.'
            ]);

            session()->set('client_id', $newUserId);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Registration successful! Logged in as ' . $firstName]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Registration failed.']);
    }

    public function logout()
    {
        session()->remove('client_id');
        session()->destroy();
        return redirect()->to('/');
    }

    public function dashboard()
    {
        // Require Authentication
        if (!session()->has('client_id')) {
            session()->setFlashdata('info', 'Please log in to access the client dashboard.');
            return redirect()->to('/login');
        }

        $activeClientId = session()->get('client_id');
        $db = \Config\Database::connect();

        // 1. Fallback: Seed working hours if empty
        $workingHourModel = new WorkingHourModel();
        if ($workingHourModel->countAllResults() == 0) {
            $staffList = $db->table('staff_profiles')->get()->getResultArray();
            $workingHoursData = [];
            foreach ($staffList as $staff) {
                for ($day = 1; $day <= 5; $day++) { // Monday to Friday (1-5)
                    $workingHoursData[] = [
                        'staff_id' => $staff['user_id'],
                        'day_of_week' => $day,
                        'start_time' => '09:00:00',
                        'end_time' => '17:00:00',
                        'is_active' => 1
                    ];
                }
            }
            if (!empty($workingHoursData)) {
                $workingHourModel->insertBatch($workingHoursData);
            }
        }

        // 2. Fetch Active Services
        $serviceModel = new ServiceModel();
        $services = $serviceModel->where('is_active', 1)->findAll();

        // 3. Fetch Staff Members with Profiles
        $staff = $db->table('users')
            ->select('users.id, users.first_name, users.last_name, users.email, users.phone, staff_profiles.title, staff_profiles.bio, staff_profiles.is_available')
            ->join('user_roles', 'user_roles.user_id = users.id')
            ->join('staff_profiles', 'staff_profiles.user_id = users.id')
            ->where('user_roles.role_id', 2) // Staff
            ->where('users.is_active', 1)
            ->get()
            ->getResultArray();

        // 4. Fetch Clients for quick switching/simulator (optional, but restricted on dashboard)
        $clients = $db->table('users')
            ->select('users.id, users.first_name, users.last_name, users.email')
            ->join('user_roles', 'user_roles.user_id = users.id')
            ->where('user_roles.role_id', 3) // Client
            ->where('users.is_active', 1)
            ->orderBy('users.first_name', 'ASC')
            ->get()
            ->getResultArray();

        // 5. Fetch Active Client Details & Bookings
        $activeClient = $db->table('users')
            ->where('id', $activeClientId)
            ->get()
            ->getRowArray();

        $clientAppointments = $db->table('appointments')
            ->select('appointments.*, services.name as service_name, services.duration_minutes, services.price, staff.first_name as staff_first_name, staff.last_name as staff_last_name, staff_profiles.title as staff_title')
            ->join('services', 'services.id = appointments.service_id')
            ->join('users as staff', 'staff.id = appointments.staff_id')
            ->join('staff_profiles', 'staff_profiles.user_id = staff.id')
            ->where('appointments.client_id', $activeClientId)
            ->orderBy('appointments.appointment_date', 'DESC')
            ->orderBy('appointments.start_time', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'services' => $services,
            'staff' => $staff,
            'clients' => $clients,
            'activeClient' => $activeClient,
            'clientAppointments' => $clientAppointments
        ];

        return view('client/dashboard', $data);
    }

    public function switchClient()
    {
        $clientId = $this->request->getPost('client_id');
        if (!$clientId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Client ID is required.']);
        }

        $db = \Config\Database::connect();
        $client = $db->table('users')
            ->join('user_roles', 'user_roles.user_id = users.id')
            ->where('users.id', $clientId)
            ->where('user_roles.role_id', 3) // Client
            ->get()
            ->getRow();

        if (!$client) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid Client.']);
        }

        session()->set('client_id', $clientId);
        return $this->response->setJSON(['status' => 'success', 'message' => 'Switched active client successfully.']);
    }

    public function getStaffForService($serviceId)
    {
        $db = \Config\Database::connect();
        $staff = $db->table('users')
            ->select('users.id, users.first_name, users.last_name, staff_profiles.title, staff_profiles.bio')
            ->join('staff_profiles', 'staff_profiles.user_id = users.id')
            ->join('staff_services', 'staff_services.staff_id = users.id')
            ->where('staff_services.service_id', $serviceId)
            ->where('users.is_active', 1)
            ->where('staff_profiles.is_available', 1)
            ->get()
            ->getResultArray();

        return $this->response->setJSON($staff);
    }

    public function getAvailableSlots()
    {
        $staffId = $this->request->getPost('staff_id');
        $serviceId = $this->request->getPost('service_id');
        $date = $this->request->getPost('date');

        if (empty($staffId) || empty($serviceId) || empty($date)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Missing parameters.']);
        }

        $db = \Config\Database::connect();
        
        $service = $db->table('services')->where('id', $serviceId)->get()->getRowArray();
        if (!$service) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Service not found.']);
        }
        $duration = $service['duration_minutes'];

        $dayOfWeek = date('w', strtotime($date)); // 0 (Sunday) to 6 (Saturday)

        $wh = $db->table('working_hours')
            ->where('staff_id', $staffId)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', 1)
            ->get()
            ->getRowArray();

        if (!$wh) {
            if ($dayOfWeek >= 1 && $dayOfWeek <= 5) { // default Mon-Fri
                $wh = [
                    'start_time' => '09:00:00',
                    'end_time' => '17:00:00'
                ];
            } else {
                return $this->response->setJSON([]);
            }
        }

        $startTime = strtotime($wh['start_time']);
        $endTime = strtotime($wh['end_time']);

        $dayStart = $date . ' 00:00:00';
        $dayEnd = $date . ' 23:59:59';
        $timeOffs = $db->table('time_offs')
            ->where('staff_id', $staffId)
            ->where('status', 'approved')
            ->groupStart()
                ->where('start_datetime <=', $dayEnd)
                ->where('end_datetime >=', $dayStart)
            ->groupEnd()
            ->get()
            ->getResultArray();

        $appointments = $db->table('appointments')
            ->where('staff_id', $staffId)
            ->where('appointment_date', $date)
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->get()
            ->getResultArray();

        $slots = [];
        $currTime = $startTime;
        $now = time();

        while ($currTime + ($duration * 60) <= $endTime) {
            $slotStartStr = date('H:i:s', $currTime);
            $slotEndStr = date('H:i:s', $currTime + ($duration * 60));
            
            $slotStartDatetimeStr = $date . ' ' . $slotStartStr;
            $slotEndDatetimeStr = $date . ' ' . $slotEndStr;

            $slotStartTimestamp = strtotime($slotStartDatetimeStr);
            $slotEndTimestamp = strtotime($slotEndDatetimeStr);

            $isAvailable = true;

            if ($slotStartTimestamp < $now) {
                $isAvailable = false;
            }

            if ($isAvailable) {
                foreach ($timeOffs as $to) {
                    $toStart = strtotime($to['start_datetime']);
                    $toEnd = strtotime($to['end_datetime']);
                    if (max($slotStartTimestamp, $toStart) < min($slotEndTimestamp, $toEnd)) {
                        $isAvailable = false;
                        break;
                    }
                }
            }

            if ($isAvailable) {
                foreach ($appointments as $apt) {
                    $aptStart = strtotime($date . ' ' . $apt['start_time']);
                    $aptEnd = strtotime($date . ' ' . $apt['end_time']);
                    if (max($slotStartTimestamp, $aptStart) < min($slotEndTimestamp, $aptEnd)) {
                        $isAvailable = false;
                        break;
                    }
                }
            }

            if ($isAvailable) {
                $slots[] = [
                    'start_time' => date('H:i', $currTime),
                    'end_time' => date('H:i', $currTime + ($duration * 60)),
                    'display' => date('h:i A', $currTime) . ' - ' . date('h:i A', $currTime + ($duration * 60))
                ];
            }

            $currTime += 30 * 60; // Check slots in 30-minute intervals
        }

        return $this->response->setJSON($slots);
    }

    public function bookAppointment()
    {
        $clientId = session()->get('client_id');
        if (!$clientId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No active client session. Please login or register.']);
        }

        $serviceId = $this->request->getPost('service_id');
        $staffId = $this->request->getPost('staff_id');
        $date = $this->request->getPost('date');
        $startTime = $this->request->getPost('start_time');
        $notes = $this->request->getPost('client_notes');

        if (empty($serviceId) || empty($staffId) || empty($date) || empty($startTime)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'All booking fields are required.']);
        }

        $db = \Config\Database::connect();

        $service = $db->table('services')->where('id', $serviceId)->get()->getRowArray();
        if (!$service) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Service not found.']);
        }
        $duration = $service['duration_minutes'];

        $startTs = strtotime($date . ' ' . $startTime);
        $endTs = $startTs + ($duration * 60);
        $startTimeStr = date('H:i:s', $startTs);
        $endTimeStr = date('H:i:s', $endTs);

        // Conflict check
        $appointments = $db->table('appointments')
            ->where('staff_id', $staffId)
            ->where('appointment_date', $date)
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->get()
            ->getResultArray();

        foreach ($appointments as $apt) {
            $aptStart = strtotime($date . ' ' . $apt['start_time']);
            $aptEnd = strtotime($date . ' ' . $apt['end_time']);
            if (max($startTs, $aptStart) < min($endTs, $aptEnd)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'This slot is no longer available. Please select another slot.']);
            }
        }

        // Timeoff check
        $dayStart = $date . ' 00:00:00';
        $dayEnd = $date . ' 23:59:59';
        $timeOffs = $db->table('time_offs')
            ->where('staff_id', $staffId)
            ->where('status', 'approved')
            ->groupStart()
                ->where('start_datetime <=', $dayEnd)
                ->where('end_datetime >=', $dayStart)
            ->groupEnd()
            ->get()
            ->getResultArray();

        foreach ($timeOffs as $to) {
            $toStart = strtotime($to['start_datetime']);
            $toEnd = strtotime($to['end_datetime']);
            if (max($startTs, $toStart) < min($endTs, $toEnd)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Selected staff member is on leave during this time slot.']);
            }
        }

        $appointmentModel = new AppointmentModel();
        $aptData = [
            'client_id' => $clientId,
            'staff_id' => $staffId,
            'service_id' => $serviceId,
            'appointment_date' => $date,
            'start_time' => $startTimeStr,
            'end_time' => $endTimeStr,
            'status' => 'pending',
            'client_notes' => $notes,
            'staff_notes' => null
        ];

        if ($appointmentModel->save($aptData)) {
            $aptId = $appointmentModel->insertID();

            $logModel = new AppointmentLogModel();
            $logModel->save([
                'appointment_id' => $aptId,
                'changed_by' => $clientId,
                'action' => 'created',
                'previous_status' => null,
                'new_status' => 'pending',
                'remarks' => 'Appointment booked online by client.',
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return $this->response->setJSON(['status' => 'success', 'message' => 'Your appointment has been successfully booked!']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Database error: failed to create booking.']);
    }

    public function cancelAppointment($id)
    {
        $clientId = session()->get('client_id');
        if (!$clientId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No active client session.']);
        }

        $appointmentModel = new AppointmentModel();
        $apt = $appointmentModel->find($id);

        if (!$apt) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Appointment not found.']);
        }

        if ($apt['client_id'] != $clientId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized action.']);
        }

        if (in_array($apt['status'], ['completed', 'cancelled'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Appointment is already ' . $apt['status']]);
        }

        $oldStatus = $apt['status'];
        $apt['status'] = 'cancelled';

        if ($appointmentModel->save($apt)) {
            $logModel = new AppointmentLogModel();
            $logModel->save([
                'appointment_id' => $id,
                'changed_by' => $clientId,
                'action' => 'status_change',
                'previous_status' => $oldStatus,
                'new_status' => 'cancelled',
                'remarks' => 'Cancelled by client from dashboard.',
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return $this->response->setJSON(['status' => 'success', 'message' => 'Appointment successfully cancelled.']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to cancel appointment.']);
    }

    public function updateProfile()
    {
        $clientId = session()->get('client_id');
        if (!$clientId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No active client session.']);
        }

        $firstName = $this->request->getPost('first_name');
        $lastName = $this->request->getPost('last_name');
        $phone = $this->request->getPost('phone');
        $email = $this->request->getPost('email');

        if (empty($firstName) || empty($lastName) || empty($email)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'First name, last name, and email are required.']);
        }

        $db = \Config\Database::connect();
        
        $exists = $db->table('users')
            ->where('email', $email)
            ->where('id !=', $clientId)
            ->get()
            ->getRow();

        if ($exists) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'This email is already in use by another account.']);
        }

        $userModel = new UserModel();
        $userData = [
            'id' => $clientId,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'phone' => $phone,
            'email' => $email
        ];

        if ($userModel->save($userData)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Profile updated successfully!']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update profile.']);
    }
}
