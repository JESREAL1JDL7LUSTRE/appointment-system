<?php

namespace App\Controllers\API;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class UserControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;
    use DatabaseTestTrait;

    protected $seed = 'App\Database\Seeds\MainSeeder';
    protected $refresh = true;

    public function testUploadAvatarFailsWithoutFile()
    {
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['role'] = 'Client';
        $_SESSION['user_id'] = 3; 

        // Not sending multipart file
        $result = $this->withSession()->post('api/user/avatar');

        $result->assertStatus(400); // Validation failure
    }
}
