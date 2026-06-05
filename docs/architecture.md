# System Architecture

OmniSchedule follows a standard Model-View-Controller (MVC) architectural pattern powered by CodeIgniter 4, combined with a utility-first and reactive frontend.

---

## High-Level Request Flow

1.  **Incoming Request**: An HTTP request reaches the server (e.g., `GET /ui/admin/services`).
2.  **Routing & Middleware**: `app/Config/Routes.php` catches the route. The route is protected by a Filter (Middleware), such as `role:Administrator`. If the user lacks the role, they are redirected to `/login` or given a 403 error.
3.  **Controller Processing**: The `Admin::services()` controller method is invoked. It queries the `ServiceModel` for data.
4.  **View Rendering**: The controller passes the data to the CodeIgniter View (`app/Views/admin/services.php`).
5.  **Frontend Interactivity**: The browser renders the HTML. Alpine.js initializes components (like Modals or Tabs), and Tailwind CSS styles the page.
6.  **AJAX Mutations**: When a user creates a new service, Alpine.js intercepts the form submission and fires an asynchronous `fetch()` POST request to `/ui/admin/services/create`. The controller processes it, updates the DB, and returns a JSON response. The frontend updates dynamically without a full page reload.

---

## Backend Organization (CodeIgniter 4)

### Controllers (`app/Controllers`)
Controllers are highly segregated based on role and domain:
*   `Admin.php`: Handles all `/ui/admin/*` logic (Staff CRUD, Services CRUD).
*   `Staff.php`: Handles all `/ui/staff/*` logic (Viewing schedules, updating statuses).
*   `Client.php`: Handles authenticated client views (`/dashboard`).
*   `Home.php`: Handles public routes, Authentication (Login/Register), and the core AJAX Booking API endpoints.

### Models (`app/Models`)
Models interact with the database tables. They utilize CI4's powerful Query Builder and specify allowed fields, return types, and timestamps.
*   `UserModel`
*   `ServiceModel`
*   `AppointmentModel`

### Routing & Security (`app/Config/Routes.php`)
Security is applied at the routing layer using Route Groups and Filters:
```php
$routes->group('ui', function ($routes) {
    // Only Administrators can access these routes
    $routes->group('admin', ['filter' => 'role:Administrator'], function ($routes) { ... });
    
    // Staff and Administrators can access these
    $routes->group('staff', ['filter' => 'role:Staff,Administrator'], function ($routes) { ... });
});

// Only Clients can access the dashboard
$routes->group('client', ['filter' => 'role:Client'], function ($routes) { ... });
```

---

## Frontend Organization

### Views (`app/Views`)
Views are organized by role:
*   `app/Views/admin/`
*   `app/Views/staff/`
*   `app/Views/client/`
*   `app/Views/auth/`
*   `app/Views/components/` (Reusable partials like headers, sidebars, and the Chatbot widget)
*   `app/Views/layouts/` (Base HTML skeletons)

### Alpine.js (Reactivity)
We rely on Alpine.js (`x-data`, `x-show`, `x-on:click`) to handle UI state without writing massive external JavaScript files.
*   **Modals**: Controlled via simple boolean states (e.g., `showModal: false`).
*   **AJAX Forms**: Forms are submitted using `async/await fetch()` inside Alpine component methods to provide seamless UX and trigger Toast notifications.
*   **SPA Navigation**: The Client Dashboard uses `window.location.hash` bound to an Alpine `activeTab` variable to swap out main content views instantly while respecting browser history.

### Tailwind CSS (Styling)
*   All styling is utility-based directly in the HTML class attributes.
*   A custom `tailwind.config.js` defines our specific brand colors (e.g., `primary`, `secondary`, `stone`) and typography (Inter/Serif fonts).
*   A base `app.css` is compiled containing Tailwind directives.
