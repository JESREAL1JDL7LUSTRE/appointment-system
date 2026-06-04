# ScheduleController

**Namespace**: `App\Controllers\API`  
**Extends**: `CodeIgniter\RESTful\ResourceController`

The `ScheduleController` provides a public or client-facing endpoint to determine the availability of a specific staff member. It abstracts away the complex math of checking working hours and skipping booked appointments by relying on the `ScheduleService`.

## Endpoints

### 1. Get Available Slots
- **Route**: `GET /api/schedule/slots`
- **Description**: Calculates and returns an array of available 30-minute time slots for a given staff member on a given date.
- **Query Parameters**:
  - `staff_id` (int, required): The ID of the staff member.
  - `date` (string, required): The date to check in `YYYY-MM-DD` format.
- **Responses**:
  - `200 OK`: Returns a JSON array of time strings (e.g., `["09:00:00", "09:30:00"]`).
  - `400 Bad Request`: Missing `staff_id` or `date` parameters.
