# UiPreview

**Namespace**: `App\Controllers`  
**Extends**: `BaseController`

The `UiPreview` controller exists solely to serve HTML templates for the administrative and staff dashboards.

## Operations
It intercepts routes prefixed with `/ui` and returns HTML view files (e.g., `admin/dashboard`, `staff/schedule`). It contains no backend database logic.

## Notes
Similar to the `Home` controller, if the frontend is decoupled into a separate Javascript SPA repository, this entire controller (and its associated views) can be safely removed.
