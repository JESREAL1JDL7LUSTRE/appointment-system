# Models Reference Documentation

This directory contains the documentation for all CodeIgniter 4 Models used in the Appointment System. 

## Architectural Concept: Smart Models (Repository Pattern)
In this Service-Oriented Architecture, Models are not just dumb Table Data Gateways. They act as **Repositories**. Controllers never write raw SQL queries. Instead, if a complex query is needed (e.g., checking for time conflicts, or resolving a user's role via JOINs), a custom method is written inside the Model itself to encapsulate that database logic.

---

## Core Entities

### 1. `UserModel.php`
- **Table**: `users`
- **Purpose**: The central authentication and identity table for *all* users, regardless of their role.
- **Allowed Fields**: `email`, `password_hash`, `first_name`, `last_name`, `phone`, `profile_picture`, `is_active`
- **Custom Methods**: 
  - `getUserRoleName(int $userId): string` - Executes a JOIN query across `user_roles` and `roles` to return the human-readable string of the user's role (e.g., "Administrator").

### 2. `AppointmentModel.php`
- **Table**: `appointments`
- **Purpose**: Stores the core booking transactions between a Client and a Staff member.
- **Allowed Fields**: `client_id`, `staff_id`, `service_id`, `appointment_date`, `start_time`, `end_time`, `status`, `client_notes`
- **Custom Methods**:
  - `hasConflict(int $staffId, string $date, string $startTime, string $endTime): bool` - Critical business logic that queries the database to see if any `pending` or `confirmed` appointment already overlaps with the requested time window.

### 3. `ServiceModel.php`
- **Table**: `services`
- **Purpose**: Defines the services offered by the business (e.g., "Dental Cleaning", "Haircut").
- **Allowed Fields**: `name`, `description`, `duration_minutes`, `price`, `is_active`

---

## Role-Based Access Control (RBAC)

### 4. `RoleModel.php`
- **Table**: `roles`
- **Purpose**: Stores the distinct roles in the system (`Administrator`, `Staff`, `Client`).

### 5. `UserRoleModel.php`
- **Table**: `user_roles`
- **Purpose**: Pivot table that maps a `user_id` to a `role_id`.

### 6. `PermissionModel.php` & `RolePermissionModel.php`
- **Tables**: `permissions`, `role_permissions`
- **Purpose**: For highly granular authorization, mapping specific feature permissions to specific roles.

---

## Staff & Availability Management

### 7. `WorkingHourModel.php`
- **Table**: `working_hours`
- **Purpose**: Stores the recurring weekly availability for staff members.
- **Allowed Fields**: `staff_id`, `day_of_week` (0-6), `start_time`, `end_time`, `is_active`

### 8. `TimeOffModel.php`
- **Table**: `time_offs`
- **Purpose**: Stores specific date ranges where a staff member is unavailable (vacation, sick leave).
- **Allowed Fields**: `staff_id`, `start_date`, `end_date`, `reason`, `status`

### 9. `StaffServiceModel.php`
- **Table**: `staff_services`
- **Purpose**: Pivot table mapping which staff members are capable of performing which specific services.

---

## Extended Profiles

### 10. `StaffProfileModel.php`
- **Table**: `staff_profiles`
- **Purpose**: Stores extended metadata specifically for staff (e.g., `bio`, `specialties`).

### 11. `ClientProfileModel.php`
- **Table**: `client_profiles`
- **Purpose**: Stores extended metadata specifically for clients (e.g., `date_of_birth`, `address`, `emergency_contact`).

---

## Auditing and Logs

### 12. `NotificationModel.php`
- **Table**: `notifications`
- **Purpose**: An audit log of all emails/system alerts dispatched to users, ensuring tracking of communication.
- **Allowed Fields**: `user_id`, `appointment_id`, `type`, `subject`, `message`, `is_read`, `sent_at`

### 13. `AppointmentLogModel.php`
- **Table**: `appointment_logs`
- **Purpose**: Tracks every state change of an appointment (e.g., transition from `pending` to `confirmed` to `completed`), tracking *who* made the change.
