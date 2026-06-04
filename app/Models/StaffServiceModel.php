<?php

namespace App\Models;

use CodeIgniter\Model;

class StaffServiceModel extends Model
{
    protected $table            = 'staff_services';
    protected $primaryKey       = 'staff_id'; // Composite
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['staff_id', 'service_id'];
}
