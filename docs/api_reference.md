# API & Routing Reference

While OmniSchedule returns HTML views for its main layouts, the dynamic operations (Modals, Booking, Status Updates) are powered by asynchronous JSON API endpoints utilizing CodeIgniter controllers.

---

## 1. Client Booking API (`Home.php`)

These endpoints power the Client Portal's Smart Booking Wizard. They are secured under the `/client` route group (requires `role:Client`).

### Get Qualified Staff for a Service
`GET /client/staff-by-service/(:num)`
*   **Description**: Retrieves all active staff members who are assigned to perform the given `service_id`.
*   **Response**: Array of staff objects (id, name, title, bio).

### Calculate Available Time Slots
`POST /client/slots`
*   **Description**: Computes all open time slots for a specific staff member on a specific date, factoring in the duration of the requested service and omitting times that clash with existing appointments.
*   **Payload**:
    ```json
    {
      "staff_id": 12,
      "service_id": 3,
      "date": "2026-06-10"
    }
    ```
*   **Response**: Array of available time strings (e.g., `["09:00:00", "09:30:00", "14:00:00"]`).

### Submit Booking
`POST /client/book`
*   **Description**: Finalizes and saves the appointment to the database.
*   **Payload**:
    ```json
    {
      "staff_id": 12,
      "service_id": 3,
      "date": "2026-06-10",
      "time": "09:00",
      "notes": "Please focus on lower back pain."
    }
    ```

### Cancel Appointment
`POST /client/cancel/(:num)`
*   **Description**: Allows a client to cancel their own appointment. Changes status to `cancelled`.

---

## 2. Admin CRUD API (`Admin.php`)

These endpoints are strictly protected under `/ui/admin` requiring `role:Administrator`. They expect `multipart/form-data` or `application/x-www-form-urlencoded` from the frontend fetch calls.

### Staff Management
*   `POST /ui/admin/staff/create`: Inserts into `USERS` and `STAFF_PROFILES`. Assigns the `Staff` role.
*   `POST /ui/admin/staff/update/(:num)`: Updates staff user details and profile.
*   `POST /ui/admin/staff/delete/(:num)`: Soft-deletes or deactivates the staff member.

### Services Management
*   `POST /ui/admin/services/create`: Creates a new service.
*   `POST /ui/admin/services/update/(:num)`: Updates service details (duration, price, description).
*   `POST /ui/admin/services/delete/(:num)`: Deactivates a service.

---

## 3. Staff API (`Staff.php`)

These endpoints are protected under `/ui/staff` requiring `role:Staff` or `role:Administrator`.

### Update Appointment Status
`POST /ui/staff/appointments/update-status/(:num)`
*   **Description**: Allows a staff member to change the status of an appointment assigned to them.
*   **Payload (FormData)**: `status` (e.g., `completed`, `no_show`).
*   **Response**: Success boolean.
