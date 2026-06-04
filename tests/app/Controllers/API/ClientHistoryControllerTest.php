<?php

namespace App\Controllers\API;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class ClientHistoryControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;
    use DatabaseTestTrait;

    protected $seed = 'App\Database\Seeds\MainSeeder';
    protected $refresh = true;

    public function testGetClientHistoryReturns200()
    {
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['role'] = 'Staff';
        $_SESSION['user_id'] = 2; // Staff member

        // 3 is the client from the seeder
        $result = $this->withSession()->get('api/staff/client-history/3');

        $result->assertStatus(200);
        
        $json = json_decode($result->getJSON(), true);
        $this->assertArrayHasKey('client', $json);
        $this->assertArrayHasKey('history', $json);
        $this->assertEquals(3, $json['client']['id']);
    }

    public function testGetClientHistoryReturns404ForInvalidUser()
    {
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['role'] = 'Staff';
        $_SESSION['user_id'] = 2;

        $result = $this->withSession()->get('api/staff/client-history/999');

        $result->assertStatus(404);
        $result->assertJSONExact(['error' => 'Client not found', 'status' => 404]);
    }
}
