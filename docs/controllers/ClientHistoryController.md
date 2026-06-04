# ClientHistoryController

**Namespace**: `App\Controllers\API`  
**Extends**: `CodeIgniter\RESTful\ResourceController`

The `ClientHistoryController` exposes a read-only endpoint that allows the business side (Staff and Admins) to pull a comprehensive record of a specific client's past appointments.

## Middleware
This controller can be accessed by both `Staff` and `Administrator` roles.

## Endpoints

### 1. Show Client History
- **Route**: `GET /api/staff/client-history/{clientId}` or `GET /api/admin/client-history/{clientId}`
- **Description**: Validates that the client exists, and then queries the `ClientHistoryService` to return all historical appointment data (including service details, staff details, and notes).
- **Parameters**:
  - `{clientId}` (int, passed in the URL route)
- **Responses**:
  - `200 OK`: Returns a JSON object containing `client` metadata and an array of `history` records.
  - `404 Not Found`: Client ID does not exist in the database.
