# Controllers Reference Documentation

This directory documents the Controller layer of the Appointment System. 

## Architectural Concept: API-First Service-Oriented Architecture
To ensure extreme scalability and modularity, the system uses an API-first backend design. **Controllers contain zero business logic.** Their sole responsibility is to:
1. Receive incoming HTTP Requests.
2. Validate the request payload (using CodeIgniter Validation).
3. Pass data to the `app/Services/` layer where the actual math and logic happen.
4. Return a strictly formatted RESTful JSON response.

All backend logic lives inside `app/Controllers/API/` and inherits from `CodeIgniter\RESTful\ResourceController`.

---

## API Controllers (`app/Controllers/API/`)

### 1. `AuthController`
- **Purpose**: Manages secure session-based authentication.
- **Endpoints**:
  - `POST /api/login`: Accepts `email` and `password`. Passes to `AuthService` to verify hashes and assign session variables. Returns user metadata on success.
  - `POST /api/logout`: Destroys the active session.

### 2. `ScheduleController`
- **Purpose**: Exposes dynamic availability algorithms to the public or clients.
- **Endpoints**:
  - `GET /api/schedule/slots`: Requires `staff_id` and `date`. Queries the `ScheduleService` to calculate 30-minute time blocks based on the staff's working hours, minus existing appointments.

### 3. `AppointmentController`
- **Purpose**: Handles client-side booking operations.
- **Endpoints**:
  - `POST /api/client/appointments`: Accepts booking payloads. Protected by the `RoleFilter` (Client only). Relies on `AppointmentService` to check for double-booking conflicts and automatically trigger email notifications upon success.

### 4. `UserController`
- **Purpose**: Handles generalized actions that any logged-in user can perform regardless of their role.
- **Endpoints**:
  - `POST /api/user/avatar`: Accepts a `multipart/form-data` image upload. Passes the temporary file to `CloudinaryService` which crops it to 400x400 via their CDN, and saves the secure URL to the database.

### 5. `StaffController`
- **Purpose**: Handles staff-specific management actions.
- **Endpoints**:
  - `POST /api/staff/working-hours`: Allows a staff member to set their weekly availability schedule.
  - `POST /api/staff/time-off`: Allows a staff member to request vacation or sick leave by passing a `start_date` and `end_date`.

### 6. `ClientHistoryController`
- **Purpose**: Read-only endpoints for looking up past records.
- **Endpoints**:
  - `GET /api/staff/client-history/{id}`: Accessible by Staff and Admins. Returns a comprehensive JSON array of a specific client's past appointments, linked service data, assigned staff, and private booking notes.

### 7. `AdminController`
- **Purpose**: Handles high-level administrative reporting.
- **Endpoints**:
  - `GET /api/admin/reports/summary`: Returns quick dashboard integers (Total Appointments, Total Revenue).
  - `GET /api/admin/reports/dynamic`: A highly flexible reporting engine. Accepts query parameters (`metric`, `group_by`, `start_date`, `end_date`) to dynamically generate JSON arrays for customized chart rendering (e.g., Revenue grouped by Service, Cancellations grouped by Staff).

---

## Frontend UI Controllers (`app/Controllers/`)

*(Note: These controllers exist to serve HTML templates for the frontend UI. In a purely headless API environment, these can be safely deprecated or removed.)*

### 1. `Home.php`
- **Purpose**: The main web interface for the client. Handles the rendering of the landing page, the client dashboard, and form submissions that return HTML redirects.

### 2. `UiPreview.php`
- **Purpose**: Renders the administrative and staff dashboard UI layouts.

### 3. `ChatbotController.php`
- **Purpose**: Receives POST requests to process NLP chatbot queries and returns AI responses.
