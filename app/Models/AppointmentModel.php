<?php

namespace App\Models;

use CodeIgniter\Model;

class AppointmentModel extends Model
{
    protected $table            = 'appointments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['client_id', 'staff_id', 'service_id', 'appointment_date', 'start_time', 'end_time', 'status', 'client_notes', 'staff_notes'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = '';

    /**
     * Checks if a staff member already has a pending/confirmed appointment 
     * that overlaps with the specified time slot.
     */
    public function hasConflict($staffId, $date, $startTime, $endTime): bool
    {
        $conflict = $this->where('staff_id', $staffId)
                         ->where('appointment_date', $date)
                         ->where('start_time <', $endTime)
                         ->where('end_time >', $startTime)
                         ->whereIn('status', ['pending', 'confirmed'])
                         ->first();

        return $conflict !== null;
    }
}
