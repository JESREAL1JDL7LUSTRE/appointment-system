<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientProfileModel extends Model
{
    protected $table            = 'client_profiles';
    protected $primaryKey       = 'user_id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'internal_notes'];
}
