<?php

namespace App\Services;

class ReportingService
{
    /**
     * Generates a highly dynamic report based on custom parameters.
     * 
     * @param string $metric "count" (appointments), "revenue" (sum of service prices), "cancellations"
     * @param string $groupBy "date", "service", "staff"
     * @param string|null $startDate "Y-m-d"
     * @param string|null $endDate "Y-m-d"
     */
    public function generateReport(string $metric = 'count', string $groupBy = 'date', ?string $startDate = null, ?string $endDate = null): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table('appointments a');
        $builder->join('services s', 's.id = a.service_id');
        $builder->join('users u', 'u.id = a.staff_id');

        // 1. Date Filtering
        if ($startDate) {
            $builder->where('a.appointment_date >=', $startDate);
        }
        if ($endDate) {
            $builder->where('a.appointment_date <=', $endDate);
        }

        // 2. Select Aggregate Metric
        if ($metric === 'revenue') {
            $builder->selectSum('s.price', 'value');
            $builder->where('a.status', 'completed'); // Only count completed for revenue
        } elseif ($metric === 'cancellations') {
            $builder->selectCount('a.id', 'value');
            $builder->where('a.status', 'cancelled');
        } else {
            // default to total count
            $builder->selectCount('a.id', 'value');
        }

        // 3. Group By Logic
        if ($groupBy === 'service') {
            $builder->select('s.name as label');
            $builder->groupBy('s.id');
        } elseif ($groupBy === 'staff') {
            $builder->select('CONCAT(u.first_name, " ", u.last_name) as label');
            $builder->groupBy('a.staff_id');
        } else {
            // default to grouping by date
            $builder->select('a.appointment_date as label');
            $builder->groupBy('a.appointment_date');
        }

        $builder->orderBy('value', 'DESC');

        return $builder->get()->getResultArray();
    }
    
    /**
     * Returns a fast high-level dashboard summary
     */
    public function getQuickSummary(): array
    {
        $db = \Config\Database::connect();
        
        $totalAppts = $db->table('appointments')->countAllResults();
        
        $revenueRow = $db->table('appointments a')
                         ->selectSum('s.price')
                         ->join('services s', 's.id = a.service_id')
                         ->where('a.status', 'completed')
                         ->get()->getRowArray();
        
        $totalRevenue = $revenueRow['price'] ?? 0;

        return [
            'total_appointments' => $totalAppts,
            'total_revenue'      => $totalRevenue
        ];
    }
}
