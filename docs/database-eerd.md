# Client Appointment Management System - EERD

This document outlines the Enhanced Entity-Relationship Diagram (EERD) for the backend database of the Client Appointment Management System. The schema is designed with scalability, auditability, and flexibility in mind.

## Mermaid EERD

```mermaid
erDiagram
    USERS {
        bigint id PK
        string email UK
        string password_hash
        string first_name
        string last_name
        string phone
        boolean is_active
        timestamp created_at
        timestamp updated_at
    }

    ROLES {
        int id PK
        string name UK
        string description
    }

    USER_ROLES {
        bigint user_id PK, FK
        int role_id PK, FK
    }

    PERMISSIONS {
        int id PK
        string name UK
        string description
    }

    ROLE_PERMISSIONS {
        int role_id PK, FK
        int permission_id PK, FK
    }

    STAFF_PROFILES {
        bigint user_id PK, FK
        string title
        text bio
        boolean is_available
    }

    CLIENT_PROFILES {
        bigint user_id PK, FK
        text internal_notes
    }

    SERVICES {
        int id PK
        string name
        text description
        int duration_minutes
        decimal price
        boolean is_active
        timestamp created_at
    }

    STAFF_SERVICES {
        bigint staff_id PK, FK
        int service_id PK, FK
    }

    WORKING_HOURS {
        bigint id PK
        bigint staff_id FK
        int day_of_week "0-6 (Sun-Sat)"
        time start_time
        time end_time
        boolean is_active
    }

    TIME_OFFS {
        bigint id PK
        bigint staff_id FK
        datetime start_datetime
        datetime end_datetime
        string reason
        string status "pending, approved, rejected"
    }

    APPOINTMENTS {
        bigint id PK
        bigint client_id FK
        bigint staff_id FK
        int service_id FK
        date appointment_date
        time start_time
        time end_time
        string status "pending, confirmed, completed, cancelled, no_show"
        text client_notes
        text staff_notes
        timestamp created_at
        timestamp updated_at
    }

    APPOINTMENT_LOGS {
        bigint id PK
        bigint appointment_id FK
        bigint changed_by FK
        string action "created, status_change, rescheduled"
        string previous_status
        string new_status
        text remarks
        timestamp created_at
    }

    NOTIFICATIONS {
        bigint id PK
        bigint user_id FK
        bigint appointment_id FK
        string type "email, sms"
        string category "reminder, confirmation, cancellation"
        string status "pending, sent, failed"
        datetime scheduled_at
        datetime sent_at
    }

    %% Relationships
    USERS ||--o{ USER_ROLES : has
    ROLES ||--o{ USER_ROLES : assigned_to
    ROLES ||--o{ ROLE_PERMISSIONS : has
    PERMISSIONS ||--o{ ROLE_PERMISSIONS : assigned_to
    
    USERS ||--o| STAFF_PROFILES : "is a (subtype)"
    USERS ||--o| CLIENT_PROFILES : "is a (subtype)"
    
    STAFF_PROFILES ||--o{ STAFF_SERVICES : offers
    SERVICES ||--o{ STAFF_SERVICES : provided_by
    
    STAFF_PROFILES ||--o{ WORKING_HOURS : defines
    STAFF_PROFILES ||--o{ TIME_OFFS : requests
    
    CLIENT_PROFILES ||--o{ APPOINTMENTS : books
    STAFF_PROFILES ||--o{ APPOINTMENTS : conducts
    SERVICES ||--o{ APPOINTMENTS : includes
    
    APPOINTMENTS ||--o{ APPOINTMENT_LOGS : tracks
    USERS ||--o{ APPOINTMENT_LOGS : performs
    
    USERS ||--o{ NOTIFICATIONS : receives
    APPOINTMENTS ||--o{ NOTIFICATIONS : triggers
```

## Scalability and Design Notes

1. **Role-Based Access Control (RBAC):** By normalizing users, roles, and permissions into separate tables, the system can seamlessly introduce new roles (e.g., SuperAdmin, Receptionist) without altering the user table schema.
2. **User Subtyping (EERD):** The `USERS` table holds universal traits (auth, contact), while `STAFF_PROFILES` and `CLIENT_PROFILES` extend the user model. This polymorphic-like structure prevents "sparse tables" populated by mostly NULL columns depending on the role.
3. **Availability & Conflict Prevention:** By separating `WORKING_HOURS` (recurring weekly schedule) from `TIME_OFFS` (exceptions, breaks, leaves), the system efficiently queries to detect overlaps and prevent double bookings.
4. **Audit Trails & Client History:** The `APPOINTMENT_LOGS` table tracks every state change of an appointment. This directly satisfies the requirement for a "Client History Module" allowing admins/staff to see full timelines.
5. **Asynchronous Notifications:** A dedicated `NOTIFICATIONS` table is crucial for scalability. Rather than sending emails inline during the HTTP request and potentially causing timeouts, an external queue or cron job can process unsent reminders using this table.
