# AuthController

**Namespace**: `App\Controllers\API`  
**Extends**: `CodeIgniter\RESTful\ResourceController`

The `AuthController` handles the secure, session-based authentication for all users in the system (Admins, Staff, and Clients). It validates incoming credentials and relies on the `AuthService` to verify the hashes and initialize the secure HTTP session.

## Endpoints

### 1. Login
- **Route**: `POST /api/login`
- **Description**: Authenticates a user and starts a session.
- **Expected Payload** (JSON or Form-Data):
  - `email` (string, required, valid email)
  - `password` (string, required)
- **Responses**:
  - `200 OK`: Login successful, returns user metadata (`user_id`, `first_name`, `last_name`, `email`, `role`).
  - `401 Unauthorized`: Invalid email or password, or account deactivated.
  - `400 Bad Request`: Validation errors on the payload.

### 2. Logout
- **Route**: `POST /api/logout`
- **Description**: Destroys the active session, logging the user out of the system.
- **Responses**:
  - `200 OK`: Session destroyed successfully.
