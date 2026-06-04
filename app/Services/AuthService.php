<?php

namespace App\Services;

use App\Models\UserModel;

class AuthService
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Authenticates a user via email and password, and sets session variables.
     * @return array|bool Returns user data on success, false on failure.
     */
    public function attemptLogin(string $email, string $password)
    {
        $user = $this->userModel->where('email', $email)->first();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return false;
        }

        if (!$user['is_active']) {
            throw new \Exception("Account is deactivated.");
        }

        $roleName = $this->userModel->getUserRoleName($user['id']);

        $sessionData = [
            'user_id'    => $user['id'],
            'first_name' => $user['first_name'],
            'last_name'  => $user['last_name'],
            'email'      => $user['email'],
            'role'       => $roleName,
            'isLoggedIn' => true,
        ];
        
        session()->set($sessionData);

        return $sessionData;
    }

    public function logout()
    {
        session()->destroy();
    }
}
