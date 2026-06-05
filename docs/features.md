# OmniSchedule Features & Workflows

This document breaks down the primary features available in OmniSchedule, separated by user roles. The system enforces strict isolation between these roles.

---

## 1. Client Portal (`/dashboard`)

The Client Portal is the primary interface for standard customers to interact with the business, book services, and manage their history.

### Smart Booking Wizard
The core feature of the client portal is a 4-step, reactive booking engine built with Alpine.js:
*   **Step 1: Service Selection** - The client chooses from a list of active wellness/therapeutic services.
*   **Step 2: Specialist Selection** - Upon selecting a service, the backend dynamically returns a list of Staff members who are qualified (via the `STAFF_SERVICES` mapping) to perform that specific service.
*   **Step 3: Schedule Selection** - The client picks a date. The system cross-references the selected Staff member's `WORKING_HOURS`, `TIME_OFFS`, and existing `APPOINTMENTS` to generate a list of exactly which time slots are genuinely open.
*   **Step 4: Confirmation & Notes** - The client reviews the booking, adds optional internal notes, and submits.

### Dashboard & History
*   **Dashboard SPA**: Built as a Single Page Application using Alpine.js and URL hash routing (`#home`, `#book`, `#bookings`, `#profile`). The page does not need to reload to switch contexts.
*   **Upcoming & Past Bookings**: Clients can view their upcoming schedules and their historical completed/cancelled appointments.

---

## 2. Staff Portal (`/ui/staff`)

The Staff Portal is designed for service providers (therapists, consultants, etc.) to manage their daily workflows.

### Appointment Management
*   **Assigned Schedule**: Staff can see all appointments specifically booked with them. They cannot see other staff members' appointments.
*   **Status Transitions**: Staff can mutate the state of an appointment directly from their dashboard. For example, marking an appointment as `Completed` or `No Show`.
*   **Client Notes View**: Staff can see the custom notes left by the client during the booking process.

### Availability Management (Roadmap/Partial)
*   **Working Hours**: Defining default weekly recurring availability (e.g., Mon-Fri 9 AM to 5 PM).
*   **Time Off Requests**: Requesting specific dates/times as unavailable due to sickness, vacation, or breaks.

---

## 3. Admin Portal (`/ui/admin`)

Administrators have global access to manage the business logic, staff members, services, and view system-wide metrics.

### Dashboard & Metrics
*   **Overview Stats**: Quick viewing of total active clients, upcoming global appointments, and revenue estimates.
*   **Global Appointments**: View, filter, and manage appointments across the entire system regardless of which staff member is assigned.

### Services CRUD Management
*   Administrators can create new services, update existing ones (name, description, duration, price), and soft-delete/deactivate them. 
*   Updates to services immediately reflect in the Client Booking Wizard.

### Staff CRUD Management
*   Administrators act as HR, capable of registering new staff accounts, assigning them specific titles/bios, and managing their system access.
*   Staff can be deactivated, instantly removing them from the available specialists pool in the Client Portal.

### Client History Tracking
*   Admins can view a detailed audit log of client interactions and appointment histories for quality control.
