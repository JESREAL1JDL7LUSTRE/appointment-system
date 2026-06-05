# OmniSchedule (Client Appointment Management System)

Welcome to the documentation for **OmniSchedule**, a comprehensive, scalable, and beautifully designed client appointment management system.

## Project Overview

OmniSchedule is a web-based application designed to streamline the scheduling and management of appointments between clients and service providers. It is highly adaptable for various industries such as medical clinics, spas, consultation businesses, and more. 

The primary goal of the application is to provide an efficient and user-friendly platform for booking appointments while reducing scheduling conflicts and administrative workload. 

## Key Technology Stack

The project uses a modern monolithic stack divided between a robust PHP backend and a reactive, utility-driven frontend:

*   **Backend Framework**: [CodeIgniter 4](https://codeigniter.com/) (CI4) - Follows the MVC architecture.
*   **Frontend Logic**: [Alpine.js](https://alpinejs.dev/) - Provides lightweight reactivity and SPA-like interactions without the overhead of Vue/React.
*   **Styling**: [Tailwind CSS](https://tailwindcss.com/) - Utility-first CSS framework used for rapid, beautiful, and consistent UI design.
*   **Database**: MySQL / MariaDB - Relational data mapping.
*   **Icons**: [Phosphor Icons](https://phosphoricons.com/)

## Documentation Structure

This `docs` directory contains detailed information for developers aiming to maintain or extend the system:

1.  **[Features & Workflows](features.md)**: A detailed breakdown of the capabilities available to Clients, Staff, and Administrators.
2.  **[System Architecture](architecture.md)**: How the frontend and backend interact, security models, and code organization.
3.  **[Database Schema](database.md)**: The Enhanced Entity-Relationship Diagram (EERD) and logic for preventing scheduling overlaps.
4.  **[API Reference](api_reference.md)**: Details of the internal AJAX endpoints powering the application interfaces.

## Getting Started

### Prerequisites
*   PHP 8.1+
*   Composer
*   MySQL / MariaDB
*   Node.js and npm (for Tailwind CSS compilation)

### Installation
1.  **Clone the Repository**: Clone the project to your local server (e.g., XAMPP, Laragon, or standalone PHP server).
2.  **Install Dependencies**: 
    ```bash
    composer install
    npm install
    ```
3.  **Database Setup**: 
    *   Create a local database (e.g., `appointment_system`).
    *   Copy `env` to `.env` and configure your database credentials.
    *   Run migrations and seeders:
        ```bash
        php spark migrate
        php spark db:seed MainSeeder
        ```
4.  **Tailwind Compilation**:
    If you make changes to the `.php` views and need to compile CSS:
    ```bash
    npm run build
    # or for watch mode
    npm run dev
    ```
5.  **Run Application**:
    ```bash
    php spark serve
    ```
    Access the application at `http://localhost:8080`.

## Design Philosophy

*   **Secure by Default**: All sensitive routes are protected by CodeIgniter Route Filters enforcing Role-Based Access Control (RBAC).
*   **Reactive UI, Monolithic Core**: We use Alpine.js inside traditional CI4 views. This allows us to have dynamic modals, tab-routing, and smart form wizards without needing a fully separate Next.js/React frontend.
*   **Data Integrity**: Time-overlaps are prevented at the database query level by strictly checking existing appointments and staff working hours/time-offs.
