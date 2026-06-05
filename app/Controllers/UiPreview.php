<?php

namespace App\Controllers;

class UiPreview extends BaseController
{
    // Admin Views
    public function adminDashboard()
    {
        $data = [
            'stats' => [
                'total_appointments' => 156,
                'active_staff' => 12,
                'monthly_revenue' => 12500,
                'upcoming_appointments' => 24,
            ],
            'recent_appointments' => [
                [
                    'id' => 'APT-1042',
                    'client_name' => 'John Doe',
                    'service_name' => 'General Consultation',
                    'staff_name' => 'Dr. Sarah Smith',
                    'appointment_date' => date('Y-m-d'),
                    'start_time' => '10:00:00',
                    'end_time' => '10:45:00',
                    'status' => 'confirmed'
                ],
                [
                    'id' => 'APT-1043',
                    'client_name' => 'Jane Smith',
                    'service_name' => 'Therapy Session',
                    'staff_name' => 'Dr. Michael Brown',
                    'appointment_date' => date('Y-m-d'),
                    'start_time' => '11:30:00',
                    'end_time' => '12:30:00',
                    'status' => 'pending'
                ]
            ]
        ];
        return view('admin/dashboard', $data);
    }

    public function adminStaff()
    {
        $data = [
            'staff_members' => [
                [
                    'first_name' => 'Sarah',
                    'last_name' => 'Smith',
                    'email' => 'sarah@example.com',
                    'phone' => '+1 234 567 890',
                    'title' => 'Senior Consultant',
                    'is_active' => true,
                    'is_available' => true,
                    'created_at' => '2023-01-12'
                ],
                [
                    'first_name' => 'Michael',
                    'last_name' => 'Brown',
                    'email' => 'michael@example.com',
                    'phone' => '+1 987 654 321',
                    'title' => 'Therapist',
                    'is_active' => true,
                    'is_available' => false,
                    'created_at' => '2023-03-05'
                ]
            ]
        ];
        return view('admin/staff', $data);
    }

    public function adminServices()
    {
        $data = [
            'services' => [
                [
                    'name' => 'General Consultation',
                    'description' => 'A comprehensive initial assessment with our specialists to discuss your concerns and develop a basic plan.',
                    'duration_minutes' => 45,
                    'price' => 150.00,
                    'is_active' => true
                ],
                [
                    'name' => 'Therapy Session',
                    'description' => 'One-on-one focused session with a certified therapist for ongoing mental health support and treatment.',
                    'duration_minutes' => 60,
                    'price' => 200.00,
                    'is_active' => true
                ]
            ]
        ];
        return view('admin/services', $data);
    }

    public function adminAppointments()
    {
        $data = [
            'appointments' => [
                [
                    'id' => 'APT-1042',
                    'client_name' => 'John Doe',
                    'client_phone' => '+1 555-0198',
                    'service_name' => 'General Consultation',
                    'staff_name' => 'Dr. Sarah Smith',
                    'appointment_date' => date('Y-m-d'),
                    'start_time' => '10:00:00',
                    'duration_minutes' => 45,
                    'status' => 'confirmed'
                ],
                [
                    'id' => 'APT-1043',
                    'client_name' => 'Jane Smith',
                    'client_phone' => '+1 555-0231',
                    'service_name' => 'Therapy Session',
                    'staff_name' => 'Dr. Michael Brown',
                    'appointment_date' => date('Y-m-d'),
                    'start_time' => '11:30:00',
                    'duration_minutes' => 60,
                    'status' => 'pending'
                ]
            ]
        ];
        return view('admin/appointments', $data);
    }

    // Staff Views
    public function staffDashboard()
    {
        $data = [
            'stats' => [
                'today_appointments' => 5,
                'completed_this_week' => 12,
                'upcoming_this_week' => 18,
                'cancellations_this_week' => 1,
            ],
            'recent_appointments' => [
                [
                    'client_name' => 'John Doe',
                    'service_name' => 'General Consultation',
                    'start_time' => '10:00:00',
                    'client_notes' => 'First time patient, reporting mild headaches.'
                ],
                [
                    'client_name' => 'Alice Johnson',
                    'service_name' => 'Follow-up Check',
                    'start_time' => '11:30:00',
                    'client_notes' => ''
                ]
            ]
        ];
        return view('staff/dashboard', $data);
    }

    public function staffSchedule()
    {
        $data = [
            'working_hours' => [
                ['day_of_week' => 1, 'is_working' => true, 'start_time' => '09:00', 'end_time' => '17:00'],
                ['day_of_week' => 2, 'is_working' => true, 'start_time' => '09:00', 'end_time' => '17:00'],
                ['day_of_week' => 3, 'is_working' => true, 'start_time' => '09:00', 'end_time' => '13:00'],
                ['day_of_week' => 4, 'is_working' => true, 'start_time' => '09:00', 'end_time' => '17:00'],
                ['day_of_week' => 5, 'is_working' => true, 'start_time' => '09:00', 'end_time' => '17:00'],
                ['day_of_week' => 6, 'is_working' => false, 'start_time' => '09:00', 'end_time' => '17:00'],
                ['day_of_week' => 7, 'is_working' => false, 'start_time' => '09:00', 'end_time' => '17:00'],
            ],
            'time_off_requests' => [
                ['type' => 'vacation', 'start_date' => date('Y-m-d', strtotime('+4 days')), 'end_date' => date('Y-m-d', strtotime('+9 days')), 'status' => 'pending'],
                ['type' => 'medical_leave', 'start_date' => date('Y-m-d', strtotime('-1 month')), 'end_date' => date('Y-m-d', strtotime('-1 month')), 'status' => 'approved'],
            ]
        ];
        return view('staff/schedule', $data);
    }

    public function staffAppointments()
    {
        $data = [
            'appointments' => [
                [
                    'client_name' => 'John Doe',
                    'service_name' => 'General Consultation',
                    'appointment_date' => date('Y-m-d'),
                    'start_time' => '10:00:00',
                    'duration_minutes' => 45,
                    'status' => 'confirmed',
                    'client_notes' => 'Experiencing mild headaches and fatigue for the past week. First-time patient.'
                ],
                [
                    'client_name' => 'Alice Johnson',
                    'service_name' => 'Follow-up Check',
                    'appointment_date' => date('Y-m-d', strtotime('+1 day')),
                    'start_time' => '14:30:00',
                    'duration_minutes' => 30,
                    'status' => 'pending',
                    'client_notes' => ''
                ]
            ]
        ];
        return view('staff/appointments', $data);
    }
}
