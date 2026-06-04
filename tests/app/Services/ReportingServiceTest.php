<?php

namespace App\Services;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Models\AppointmentModel;
use App\Models\ServiceModel;

class ReportingServiceTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $seed = 'App\Database\Seeds\MainSeeder';
    protected $refresh = true;

    public function setUp(): void
    {
        parent::setUp();
        
        $serviceModel = new ServiceModel();
        $this->serviceId1 = $serviceModel->insert(['name' => 'Service A', 'duration_minutes' => 60, 'price' => 100, 'is_active' => 1]);
        $this->serviceId2 = $serviceModel->insert(['name' => 'Service B', 'duration_minutes' => 60, 'price' => 50, 'is_active' => 1]);

        $apptModel = new AppointmentModel();
        // Completed appt for Service A
        $apptModel->insert(['client_id' => 3, 'staff_id' => 2, 'service_id' => $this->serviceId1, 'appointment_date' => '2023-01-01', 'start_time' => '10:00:00', 'end_time' => '11:00:00', 'status' => 'completed']);
        // Cancelled appt for Service B
        $apptModel->insert(['client_id' => 3, 'staff_id' => 2, 'service_id' => $this->serviceId2, 'appointment_date' => '2023-01-02', 'start_time' => '10:00:00', 'end_time' => '11:00:00', 'status' => 'cancelled']);
    }

    public function testGenerateReportCountByDate()
    {
        $service = new ReportingService();
        $report = $service->generateReport('count', 'date');
        
        // 2 total appointments, each on a different date
        $this->assertCount(2, $report);
        $this->assertEquals(1, $report[0]['value']);
    }

    public function testGenerateReportRevenueByService()
    {
        $service = new ReportingService();
        $report = $service->generateReport('revenue', 'service');
        
        // Only 1 completed appointment, so revenue is 100 for Service A
        $this->assertCount(1, $report); // cancelled appt isn't counted in revenue
        $this->assertEquals('Service A', $report[0]['label']);
        $this->assertEquals(100, $report[0]['value']);
    }

    public function testGetQuickSummary()
    {
        $service = new ReportingService();
        $summary = $service->getQuickSummary();

        $this->assertEquals(2, $summary['total_appointments']);
        $this->assertEquals(100, $summary['total_revenue']);
    }
}
