<?php

namespace App\Models;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Models\UserModel;

class UserModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    // Refresh database before each test
    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;

    protected $seed = 'App\Database\Seeds\UserSeeder';

    public function testUserCreation()
    {
        $userModel = new UserModel();

        $data = [
            'email'         => 'test@example.com',
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
            'first_name'    => 'Test',
            'last_name'     => 'User',
            'phone'         => '1234567890',
            'is_active'     => 1,
        ];

        $userId = $userModel->insert($data);

        $this->assertIsNumeric($userId);
        
        $savedUser = $userModel->find($userId);
        $this->assertEquals('test@example.com', $savedUser['email']);
        $this->assertEquals('Test', $savedUser['first_name']);
    }

    public function testDuplicateEmailFails()
    {
        $userModel = new UserModel();

        // Email already seeded in UserSeeder
        $data = [
            'email'         => 'admin@example.com',
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
            'first_name'    => 'Duplicate',
            'last_name'     => 'Email',
            'is_active'     => 1,
        ];

        $this->expectException(\CodeIgniter\Database\Exceptions\DatabaseException::class);
        $userModel->insert($data);
    }
}
