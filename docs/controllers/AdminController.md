# AdminController

**Namespace**: `App\Controllers\API`  
**Extends**: `CodeIgniter\RESTful\ResourceController`

The `AdminController` provides the data necessary to power an administrative dashboard. It interacts with the `ReportingService` to generate both static summaries and highly dynamic statistical reports.

## Middleware
This controller is strictly protected by the `RoleFilter` and requires the `Administrator` role.

## Endpoints

### 1. Quick Summary
- **Route**: `GET /api/admin/reports/summary`
- **Description**: Returns fast, high-level metrics (e.g., total appointments ever booked, total revenue collected).
- **Responses**:
  - `200 OK`: JSON object with `total_appointments` and `total_revenue`.

### 2. Dynamic Report
- **Route**: `GET /api/admin/reports/dynamic`
- **Description**: A highly flexible reporting engine. Accepts parameters to customize the SQL query dynamically.
- **Query Parameters (all optional)**:
  - `metric` (string): Defaults to `count`. Can be `count` (appointments), `revenue`, or `cancellations`.
  - `group_by` (string): Defaults to `date`. Can be `date`, `service`, or `staff`.
  - `start_date` (string, YYYY-MM-DD): Filters results from this date forward.
  - `end_date` (string, YYYY-MM-DD): Filters results up to this date.
- **Responses**:
  - `200 OK`: Returns a JSON object containing the `meta` config used, and a `data` array containing `label` and `value` pairs suitable for rendering charts.
