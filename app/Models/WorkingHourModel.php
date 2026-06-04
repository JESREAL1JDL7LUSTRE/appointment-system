<?php

namespace App\Models;

use CodeIgniter\Model;

class WorkingHourModel extends Model
{
    protected $table            = 'working_hours';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['staff_id', 'day_of_week', 'start_time', 'end_time', 'is_active'];
}
