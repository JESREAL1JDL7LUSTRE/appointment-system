<?php
namespace App\Services;

use LucianoTonet\GroqPHP\Groq;

/**
 * Service wrapper for Groq AI chat interactions.
 */
class ChatbotService
{
    protected Groq $groq;

    public function __construct()
    {
        $apiKey = getenv('GROQ_API_KEY') ?: env('GROQ_API_KEY');
        if (!$apiKey) {
            throw new \RuntimeException('GROQ_API_KEY not set in environment');
        }
        $this->groq = new Groq($apiKey);
    }

    /**
     * Send a user prompt to Groq and return the assistant's reply.
     */
    public function ask(string $prompt): string
    {
        $context = $this->getSystemContext();

        $systemPrompt = "You are the AI Appointment Assistant for our Client Appointment Management System.\n"
            . "You must assist users ONLY with inquiries about this appointment system, such as active services, pricing, durations, staff profiles, availability, working hours, and their own bookings/appointments.\n\n"
            . "Here is the real-time context about the system and the current user:\n"
            . "----------------------------------------\n"
            . $context
            . "----------------------------------------\n\n"
            . "CRITICAL GUIDELINES:\n"
            . "1. ONLY answer questions directly related to this appointment system (services, staff, schedules, user appointments, and system guide).\n"
            . "2. If the user asks about ANY topic outside this scope (e.g. math, writing code, general knowledge, sports, unrelated advice, definitions of words, external companies, translating or writing content that isn't about scheduling), you must politely decline to answer, explaining that you are only programmed to assist with this appointment system.\n"
            . "3. Be professional, friendly, and concise.\n"
            . "4. Keep dates and times in a clean, human-readable format.\n"
            . "5. If the user wants to book or cancel an appointment, instruct them on how to navigate the portal (e.g. \"To book a new appointment, go to the dashboard, choose your desired service and staff, pick a date and time, then click 'Book Appointment'. To cancel, view your appointments on the dashboard and click 'Cancel' next to the appointment\").\n"
            . "6. You do NOT have the ability to directly book, cancel, or modify any database record yourself. You can only view the provided context and guide the user.";

        $response = $this->groq->chat()->completions()->create([
            'model'    => 'llama-3.1-8b-instant',
            'messages' => [
                [
                    'role'    => 'system',
                    'content' => $systemPrompt,
                ],
                [
                    'role'    => 'user',
                    'content' => $prompt,
                ],
            ],
        ]);

        return $response['choices'][0]['message']['content'] ?? 'No response received.';
    }

    /**
     * Fetch real-time system context (services, staff, user session, appointments).
     */
    protected function getSystemContext(): string
    {
        $db = \Config\Database::connect();
        
        // 1. Fetch active services
        $servicesText = "No active services found.";
        try {
            $services = $db->table('services')
                ->where('is_active', 1)
                ->get()
                ->getResultArray();
            
            if (!empty($services)) {
                $servicesText = "";
                foreach ($services as $s) {
                    $servicesText .= "- ID: {$s['id']}, Name: {$s['name']}, Price: \${$s['price']}, Duration: {$s['duration_minutes']} mins. Description: {$s['description']}\n";
                }
            }
        } catch (\Throwable $e) {
            log_message('error', 'ChatbotService failed fetching services: ' . $e->getMessage());
        }

        // 2. Fetch active staff and availability
        $staffText = "No active staff members found.";
        try {
            $staff = $db->table('users')
                ->select('users.id, users.first_name, users.last_name, staff_profiles.title, staff_profiles.bio, staff_profiles.is_available')
                ->join('user_roles', 'user_roles.user_id = users.id')
                ->join('staff_profiles', 'staff_profiles.user_id = users.id')
                ->where('user_roles.role_id', 2) // Staff
                ->where('users.is_active', 1)
                ->get()
                ->getResultArray();

            if (!empty($staff)) {
                $staffText = "";
                foreach ($staff as $st) {
                    $avail = $st['is_available'] ? 'Available' : 'Unavailable/On Leave';
                    $staffText .= "- ID: {$st['id']}, Name: {$st['first_name']} {$st['last_name']}, Title: {$st['title']}, Bio: {$st['bio']}, Status: {$avail}\n";
                }
            }
        } catch (\Throwable $e) {
            log_message('error', 'ChatbotService failed fetching staff: ' . $e->getMessage());
        }

        // 3. User session details
        $session = session();
        $isLoggedIn = $session->get('isLoggedIn') ? 'Yes' : 'No';
        $userId = $session->get('user_id');
        $userName = $isLoggedIn === 'Yes' ? ($session->get('first_name') . ' ' . $session->get('last_name')) : 'Guest';
        $userRole = $isLoggedIn === 'Yes' ? $session->get('role') : 'None';

        $sessionText = "Logged In: {$isLoggedIn}\n"
            . "User Name: {$userName}\n"
            . "User Role: {$userRole}\n";

        // 4. User specific appointments
        $appointmentsText = "No appointments found for this user.";
        if ($userId) {
            try {
                $appointments = $db->table('appointments')
                    ->select('appointments.*, services.name as service_name, staff.first_name as staff_first_name, staff.last_name as staff_last_name')
                    ->join('services', 'services.id = appointments.service_id')
                    ->join('users as staff', 'staff.id = appointments.staff_id')
                    ->where('appointments.client_id', $userId)
                    ->orWhere('appointments.staff_id', $userId)
                    ->orderBy('appointments.appointment_date', 'DESC')
                    ->orderBy('appointments.start_time', 'DESC')
                    ->get()
                    ->getResultArray();

                if (!empty($appointments)) {
                    $appointmentsText = "";
                    foreach ($appointments as $apt) {
                        $appointmentsText .= "- ID: {$apt['id']}, Date: {$apt['appointment_date']}, Time: {$apt['start_time']} to {$apt['end_time']}, Service: {$apt['service_name']}, Practitioner/Staff: {$apt['staff_first_name']} {$apt['staff_last_name']}, Status: {$apt['status']}, Notes: {$apt['client_notes']}\n";
                    }
                }
            } catch (\Throwable $e) {
                log_message('error', 'ChatbotService failed fetching appointments: ' . $e->getMessage());
            }
        }

        // 5. System working hours
        $hoursText = "Monday to Friday: 9:00 AM to 5:00 PM. Weekends: Closed.\n";

        // Construct context string
        $contextText = "SYSTEM SERVICES OFFERED:\n" . $servicesText . "\n"
            . "STAFF MEMBERS:\n" . $staffText . "\n"
            . "GENERAL WORKING HOURS:\n" . $hoursText . "\n"
            . "CURRENT USER SESSION INFO:\n" . $sessionText . "\n"
            . "CURRENT USER APPOINTMENTS:\n" . $appointmentsText . "\n";

        return $contextText;
    }
}

