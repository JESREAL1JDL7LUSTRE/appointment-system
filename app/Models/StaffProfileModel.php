<?php

namespace App\Models;

use CodeIgniter\Model;

class StaffProfileModel extends Model
{
    protected $table            = 'staff_profiles';
    protected $primaryKey       = 'user_id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'title', 'bio', 'is_available'];
}
