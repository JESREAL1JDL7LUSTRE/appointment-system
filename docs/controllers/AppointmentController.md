# AppointmentController

**Namespace**: `App\Controllers\API`  
**Extends**: `CodeIgniter\RESTful\ResourceController`

The `AppointmentController` allows authenticated Clients to book appointments. It strictly enforces the anti-double-booking policy by communicating with the `AppointmentService`.

## Middleware
This controller is protected by the `RoleFilter` and requires the `Client` role.

## Endpoints

### 1. Create Appointment
- **Route**: `POST /api/client/appointments`
- **Description**: Books a new appointment for the logged-in client. Automatically triggers an email notification upon successful booking.
- **Expected Payload** (JSON or Form-Data):
  - `staff_id` (int, required)
  - `service_id` (int, required)
  - `appointment_date` (string, required, valid date YYYY-MM-DD)
  - `start_time` (string, required)
  - `client_notes` (string, optional)
- **Responses**:
  - `201 Created`: Appointment booked successfully. Returns the `appointment_id`.
  - `400 Bad Request`: Validation failure, or the requested time slot is no longer available (Conflict).
  - `401/403`: Unauthorized access.
