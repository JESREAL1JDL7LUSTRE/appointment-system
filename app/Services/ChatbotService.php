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
        $response = $this->groq->chat()->completions()->create([
            'model'    => 'llama-3.1-8b-instant',
            'messages' => [
                [
                    'role'    => 'system',
                    'content' => 'You are a helpful assistant for an appointment scheduling system. Answer questions about appointments, scheduling, availability, and related topics concisely and helpfully.',
                ],
                [
                    'role'    => 'user',
                    'content' => $prompt,
                ],
            ],
        ]);

        return $response['choices'][0]['message']['content'] ?? 'No response received.';
    }
}
