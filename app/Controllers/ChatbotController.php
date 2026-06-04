<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\ChatbotService;
use CodeIgniter\HTTP\ResponseInterface;

class ChatbotController extends BaseController
{
    /**
     * Handles POST /chatbot/ask — returns JSON answer from Groq.
     */
    public function ask(): ResponseInterface
    {
        $prompt = $this->request->getPost('prompt');

        if (empty($prompt)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON(['error' => 'Prompt is required']);
        }

        try {
            $service = new ChatbotService();
            $answer  = $service->ask($prompt);

            return $this->response->setJSON(['answer' => $answer]);
        } catch (\Throwable $e) {
            log_message('error', 'ChatbotController: ' . $e->getMessage());

            return $this->response
                ->setStatusCode(500)
                ->setJSON(['error' => 'Chatbot error: ' . $e->getMessage()]);
        }
    }
}
