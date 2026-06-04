<?php

namespace App\Controllers\API;

use CodeIgniter\RESTful\ResourceController;
use App\Services\ClientHistoryService;
use App\Models\UserModel;

class ClientHistoryController extends ResourceController
{
    public function show($clientId = null)
    {
        if (!$clientId || !is_numeric($clientId)) {
            return $this->failValidationErrors('Valid Client ID is required');
        }

        // Verify the client actually exists and is a client
        $userModel = new UserModel();
        $user = $userModel->find($clientId);
        
        if (!$user) {
            return $this->failNotFound('Client not found');
        }

        $service = new ClientHistoryService();
        $history = $service->getClientHistory((int)$clientId);

        return $this->respond([
            'client'  => [
                'id' => $user['id'],
                'name' => $user['first_name'] . ' ' . $user['last_name'],
                'email' => $user['email']
            ],
            'history' => $history
        ], 200);
    }
}
