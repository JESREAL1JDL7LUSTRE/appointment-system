<?php

namespace App\Services;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

class AuthServiceTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $seed = 'App\Database\Seeds\MainSeeder';
    protected $refresh = true;

    public function testAttemptLoginSuccess()
    {
        $authService = new AuthService();
        
        // Using the admin generated from MainSeeder
        $result = $authService->attemptLogin('admin@example.com', 'password123');

        $this->assertIsArray($result);
        $this->assertEquals('admin@example.com', $result['email']);
        $this->assertEquals('Administrator', $result['role']);
        $this->assertTrue($result['isLoggedIn']);
        
        // Check session data was actually set
        $this->assertEquals('Administrator', session()->get('role'));
    }

    public function testAttemptLoginFailureWrongPassword()
    {
        $authService = new AuthService();
        $result = $authService->attemptLogin('admin@example.com', 'wrongpassword');

        $this->assertFalse($result);
    }
    
    public function testAttemptLoginFailureWrongEmail()
    {
        $authService = new AuthService();
        $result = $authService->attemptLogin('nonexistent@example.com', 'password123');

        $this->assertFalse($result);
    }
}
