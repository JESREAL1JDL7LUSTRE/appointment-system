<?php

namespace App\Controllers\API;

use CodeIgniter\RESTful\ResourceController;
use App\Services\ReportingService;

class AdminController extends ResourceController
{
    /**
     * GET /api/admin/reports/dynamic
     * Accepts query params: ?metric=revenue&group_by=service&start_date=2026-01-01
     */
    public function dynamicReport()
    {
        $metric = $this->request->getGet('metric') ?? 'count';
        $groupBy = $this->request->getGet('group_by') ?? 'date';
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        $service = new ReportingService();
        
        try {
            $reportData = $service->generateReport($metric, $groupBy, $startDate, $endDate);
            return $this->respond([
                'meta' => [
                    'metric'     => $metric,
                    'group_by'   => $groupBy,
                    'start_date' => $startDate,
                    'end_date'   => $endDate
                ],
                'data' => $reportData
            ], 200);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * GET /api/admin/reports/summary
     */
    public function quickSummary()
    {
        $service = new ReportingService();
        return $this->respond($service->getQuickSummary(), 200);
    }
}
