<?php

namespace App\Models;

use CodeIgniter\Model;

class TimeOffModel extends Model
{
    protected $table            = 'time_offs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['staff_id', 'start_datetime', 'end_datetime', 'reason', 'status'];
}
