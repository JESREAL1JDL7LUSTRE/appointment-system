<?php

namespace App\Services;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Models\ServiceModel;

class AppointmentServiceTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $refresh = true;

    public function testBookAppointmentSuccess()
    {
        // Setup initial service
        $serviceModel = new ServiceModel();
        $serviceId = $serviceModel->insert([
            'name' => 'Test Service',
            'duration_minutes' => 60,
            'price' => 100,
            'is_active' => 1
        ]);

        // Initialize service (mocking NotificationService so it doesn't actually send emails)
        $apptService = new AppointmentService();

        // Use reflection or override to replace NotificationService if we wanted true isolation,
        // but for now, testing standard path (which logs to DB but skips real email sending if properly configured in CI4 Test env).
        
        $apptId = $apptService->bookAppointment(
            clientId: 10,
            staffId: 2,
            serviceId: $serviceId,
            date: '2030-01-01',
            startTime: '10:00:00'
        );

        $this->assertIsInt($apptId);
        $this->assertTrue($apptId > 0);
    }

    public function testBookAppointmentThrowsExceptionOnConflict()
    {
        $serviceModel = new ServiceModel();
        $serviceId = $serviceModel->insert([
            'name' => 'Test Service',
            'duration_minutes' => 60,
            'price' => 100,
            'is_active' => 1
        ]);

        $apptService = new AppointmentService();

        // Book first appointment
        $apptService->bookAppointment(
            clientId: 10,
            staffId: 2,
            serviceId: $serviceId,
            date: '2030-01-01',
            startTime: '10:00:00'
        );

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('The requested time slot is no longer available.');

        // Attempting to book overlapping appointment (starts at 10:30, while 1st appt ends at 11:00)
        $apptService->bookAppointment(
            clientId: 11,
            staffId: 2,
            serviceId: $serviceId,
            date: '2030-01-01',
            startTime: '10:30:00'
        );
    }
}
