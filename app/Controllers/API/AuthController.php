<?php

namespace App\Controllers\API;

use CodeIgniter\RESTful\ResourceController;
use App\Services\AuthService;

class AuthController extends ResourceController
{
    public function login()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $authService = new AuthService();

        try {
            $user = $authService->attemptLogin(
                $this->request->getVar('email'), 
                $this->request->getVar('password')
            );

            if ($user) {
                return $this->respond(['message' => 'Login successful', 'user' => $user], 200);
            }

            return $this->failUnauthorized('Invalid email or password');
        } catch (\Exception $e) {
            return $this->failUnauthorized($e->getMessage());
        }
    }

    public function logout()
    {
        $authService = new AuthService();
        $authService->logout();
        return $this->respond(['message' => 'Logged out successfully'], 200);
    }
}
