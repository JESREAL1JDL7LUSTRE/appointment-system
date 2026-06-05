# Database Architecture & Schema

This document outlines the Enhanced Entity-Relationship Diagram (EERD) and specific logic principles governing the OmniSchedule database.

## Scalability and Design Principles

1.  **Role-Based Access Control (RBAC)**: Users, roles, and permissions are normalized. A user can easily be assigned multiple roles, or new system roles (like "Receptionist") can be added without altering the schema.
2.  **Polymorphic User Subtyping**: The `USERS` table holds universal traits (auth, contact details). However, Staff members have bios and titles, while Clients have internal notes. Rather than cluttering `USERS` with nullable columns, we use `STAFF_PROFILES` and `CLIENT_PROFILES` extending the `user_id`.
3.  **Availability Overlaps**: Preventing double-booking is handled by comparing `WORKING_HOURS` (recurring weekly schedules) against `TIME_OFFS` (vacations/breaks) and existing `APPOINTMENTS`.
4.  **Audit Trails**: Every status change to an appointment is tracked in `APPOINTMENT_LOGS`.

---

## Enhanced Entity-Relationship Diagram (EERD)

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
