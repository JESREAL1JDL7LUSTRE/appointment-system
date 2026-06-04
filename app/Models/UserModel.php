<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['email', 'password_hash', 'first_name', 'last_name', 'phone', 'is_active'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    /**
     * Retrieves the human-readable Role Name for the user.
     */
    public function getUserRoleName(int $userId): string
    {
        $db = \Config\Database::connect();
        $query = $db->query("
            SELECT r.name 
            FROM roles r
            JOIN user_roles ur ON r.id = ur.role_id
            WHERE ur.user_id = ?
            LIMIT 1
        ", [$userId]);
        
        $role = $query->getRowArray();
        return $role ? $role['name'] : 'Client';
    }
}
