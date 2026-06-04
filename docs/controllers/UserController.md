# UserController

**Namespace**: `App\Controllers\API`  
**Extends**: `CodeIgniter\RESTful\ResourceController`

The `UserController` handles generic profile operations that *any* authenticated user (Admin, Staff, or Client) can perform.

## Middleware
This controller requires an active session, but does not restrict by a specific role.

## Endpoints

### 1. Upload Avatar (Profile Picture)
- **Route**: `POST /api/user/avatar`
- **Description**: Accepts an image upload, passes it to the `CloudinaryService` for cloud CDN storage (cropped to 400x400), and saves the secure URL to the `users` table.
- **Expected Payload**:
  - `profile_picture` (File, required, must be a valid image under 5MB). Sent as `multipart/form-data`.
- **Responses**:
  - `200 OK`: Upload successful. Returns the `profile_picture_url`.
  - `400 Bad Request`: Validation failure (file too large, not an image).
  - `500 Internal Server Error`: Cloudinary upload failure.
