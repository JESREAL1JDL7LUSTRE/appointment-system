<?php

namespace App\Models;

use CodeIgniter\Model;

class AppointmentLogModel extends Model
{
    protected $table            = 'appointment_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['appointment_id', 'changed_by', 'action', 'previous_status', 'new_status', 'remarks'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    protected $deletedField  = '';
}
