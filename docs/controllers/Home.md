# Home

**Namespace**: `App\Controllers`  
**Extends**: `BaseController`

The `Home` controller acts as the primary web entry point for the frontend UI. 

## Operations
While the backend has shifted heavily to an API-first approach, this controller still handles the standard HTML rendering for:
- The public landing page.
- Client login and registration views.
- The standard web-based client dashboard.

## Notes
If the application is fully transitioned to a Headless API (e.g., consumed strictly by a React or Vue SPA), this controller can be deprecated, as its functionality is entirely replaced by the `App\Controllers\API\` endpoints.
