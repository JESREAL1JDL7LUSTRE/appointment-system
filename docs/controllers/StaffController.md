# StaffController

**Namespace**: `App\Controllers\API`  
**Extends**: `CodeIgniter\RESTful\ResourceController`

The `StaffController` allows staff members to manage their own schedules, including setting recurring working hours and requesting specific dates off. It interacts with the `StaffManagementService`.

## Middleware
This controller is strictly protected by the `RoleFilter` and requires the `Staff` role.

## Endpoints

### 1. Set Working Hours
- **Route**: `POST /api/staff/working-hours`
- **Description**: Defines or updates a staff member's availability for a specific day of the week.
- **Expected Payload**:
  - `day_of_week` (int, required, 0-6 where 0=Sunday)
  - `start_time` (string, required, HH:MM:SS format)
  - `end_time` (string, required, HH:MM:SS format)
  - `is_active` (boolean/int, required, 1 or 0)
- **Responses**:
  - `200 OK`: Working hours updated successfully.

### 2. Request Time Off
- **Route**: `POST /api/staff/time-off`
- **Description**: Blocks off an entire date range for the logged-in staff member.
- **Expected Payload**:
  - `start_date` (string, required, YYYY-MM-DD)
  - `end_date` (string, required, YYYY-MM-DD)
  - `reason` (string, required)
- **Responses**:
  - `201 Created`: Time off requested successfully.
  - `400 Bad Request`: Validation failure (e.g., start_date is after end_date).
