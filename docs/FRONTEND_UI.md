# Frontend UI Integration Documentation

This document provides a guide for backend developers on how to integrate real data into the frontend UI views for both the Admin and Staff portals. 

## Overview
The UI uses Tailwind CSS for styling and CodeIgniter 4 view layouts. The views have been refactored to use dynamic PHP variables and loops, removing the need to edit hardcoded HTML blocks.

### Directory Structure
- `app/Views/layouts/`
  - `admin.php`: The main layout wrapper for the admin portal.
  - `staff.php`: The main layout wrapper for the staff portal.
- `app/Views/components/`
  - `admin_header.php`: The top navigation header for the admin layout.
  - `admin_sidebar.php`: The side navigation menu for the admin layout.
  - `staff_header.php`: The top navigation header for the staff layout.
  - `staff_sidebar.php`: The side navigation menu for the staff layout.
- `app/Views/admin/`
  - `dashboard.php`: Admin overview and statistics.
  - `appointments.php`: Admin appointment management.
  - `staff.php`: Admin staff directory.
  - `services.php`: Admin services catalog.
- `app/Views/staff/`
  - `dashboard.php`: Staff member's personal overview.
  - `appointments.php`: Staff member's appointment list.
  - `schedule.php`: Staff member's working hours and time off.

---

## Admin Views Data Structures

### 1. Admin Dashboard (`app/Views/admin/dashboard.php`)
Requires two main variables: `$stats` and `$recent_appointments`.

```php
$data = [
    'stats' => [
        'total_appointments'    => 156,
        'active_staff'          => 12,
        'monthly_revenue'       => 12500,
        'upcoming_appointments' => 24,
    ],
    'recent_appointments' => [
        [
            'id'               => 'APT-1042',
            'client_name'      => 'John Doe',
            'service_name'     => 'General Consultation',
            'staff_name'       => 'Dr. Sarah Smith',
            'appointment_date' => '2024-10-24', // Y-m-d
            'start_time'       => '10:00:00', // H:i:s
            'end_time'         => '10:45:00', // H:i:s
            'status'           => 'confirmed' // pending, confirmed, completed, cancelled
        ]
    ]
];
```

### 2. Admin Appointments (`app/Views/admin/appointments.php`)
Requires an `$appointments` array.

```php
$data = [
    'appointments' => [
        [
            'id'               => 'APT-1042',
            'client_name'      => 'John Doe',
            'client_phone'     => '+1 555-0198', // Optional
            'service_name'     => 'General Consultation',
            'staff_name'       => 'Dr. Sarah Smith',
            'appointment_date' => '2024-10-24',
            'start_time'       => '10:00:00',
            'duration_minutes' => 45,
            'status'           => 'confirmed'
        ]
    ]
];
```

### 3. Admin Staff (`app/Views/admin/staff.php`)
Requires a `$staff_members` array.

```php
$data = [
    'staff_members' => [
        [
            'first_name'   => 'Sarah',
            'last_name'    => 'Smith',
            'email'        => 'sarah@example.com',
            'phone'        => '+1 234 567 890',
            'title'        => 'Senior Consultant',
            'is_active'    => true,  // Determines overall active status
            'is_available' => true,  // Determines Available vs On Leave badge
            'created_at'   => '2023-01-12' // Used for Joined date
        ]
    ]
];
```

### 4. Admin Services (`app/Views/admin/services.php`)
Requires a `$services` array.

```php
$data = [
    'services' => [
        [
            'name'             => 'General Consultation',
            'description'      => 'A comprehensive initial assessment...',
            'duration_minutes' => 45,
            'price'            => 150.00,
            'is_active'        => true // If false, card will be grayed out with "Inactive" badge
        ]
    ]
];
```

---

## Staff Views Data Structures

**Important for all Staff Views**: Ensure `session('user_first_name')` is set for the welcome messages.

### 1. Staff Dashboard (`app/Views/staff/dashboard.php`)
Requires `$stats` and `$recent_appointments`.

```php
$data = [
    'stats' => [
        'today_appointments'      => 5,
        'completed_this_week'     => 12,
        'upcoming_this_week'      => 18,
        'cancellations_this_week' => 1,
    ],
    'recent_appointments' => [
        [
            'client_name'  => 'John Doe',
            'service_name' => 'General Consultation',
            'start_time'   => '10:00:00',
            'client_notes' => 'First time patient, reporting mild headaches.' // Optional
        ]
    ]
];
```

### 2. Staff Appointments (`app/Views/staff/appointments.php`)
Requires an `$appointments` array. The UI will automatically label dates as "Today", "Tomorrow", or format it as "Oct 24" based on the `appointment_date` compared to the current date.

```php
$data = [
    'appointments' => [
        [
            'client_name'      => 'John Doe',
            'service_name'     => 'General Consultation',
            'appointment_date' => '2024-10-24', 
            'start_time'       => '10:00:00',
            'duration_minutes' => 45,
            'status'           => 'confirmed',
            'client_notes'     => 'Experiencing mild headaches and fatigue.'
        ]
    ]
];
```

### 3. Staff Schedule (`app/Views/staff/schedule.php`)
Requires `$working_hours` and `$time_off_requests`. The working hours form will submit a POST request to `ui/staff/schedule/update` (change this route in the form action to your actual backend route).

```php
$data = [
    'working_hours' => [
        // day_of_week is 1 (Monday) to 7 (Sunday)
        ['day_of_week' => 1, 'is_working' => true,  'start_time' => '09:00', 'end_time' => '17:00'],
        ['day_of_week' => 6, 'is_working' => false, 'start_time' => '09:00', 'end_time' => '17:00'],
    ],
    'time_off_requests' => [
        [
            'type'       => 'vacation', // Replaces underscores with spaces automatically
            'start_date' => '2024-10-28',
            'end_date'   => '2024-11-02',
            'status'     => 'pending' // approved, pending, rejected
        ]
    ]
];
```
