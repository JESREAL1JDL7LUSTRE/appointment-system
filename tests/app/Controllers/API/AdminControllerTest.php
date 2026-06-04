<?php

namespace App\Controllers\API;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class AdminControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;
    use DatabaseTestTrait;

    protected $seed = 'App\Database\Seeds\MainSeeder';
    protected $refresh = true;

    public function testGetQuickSummarySuccess()
    {
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['role'] = 'Administrator';
        $_SESSION['user_id'] = 1; 

        $result = $this->withSession()->get('api/admin/reports/summary');

        $result->assertStatus(200);
        $json = json_decode($result->getJSON(), true);
        $this->assertArrayHasKey('total_appointments', $json);
        $this->assertArrayHasKey('total_revenue', $json);
    }

    public function testGetDynamicReportSuccess()
    {
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['role'] = 'Administrator';
        $_SESSION['user_id'] = 1; 

        $result = $this->withSession()->get('api/admin/reports/dynamic?metric=revenue&group_by=service');

        $result->assertStatus(200);
        $json = json_decode($result->getJSON(), true);
        
        // Assert meta config matches request
        $this->assertEquals('revenue', $json['meta']['metric']);
        $this->assertEquals('service', $json['meta']['group_by']);
        
        $this->assertIsArray($json['data']);
    }

    public function testAdminFailsIfRoleIsStaff()
    {
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['role'] = 'Staff'; // Incorrect role
        $_SESSION['user_id'] = 2; 

        $result = $this->withSession()->get('api/admin/reports/summary');

        $result->assertStatus(403);
    }
}
