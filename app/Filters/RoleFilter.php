<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('isLoggedIn')) {
            return \Config\Services::response()
                ->setStatusCode(401)
                ->setJSON(['error' => 'Unauthorized: Please log in first.']);
        }

        // If specific roles are passed as arguments to the filter (e.g. filter => 'role:Administrator,Staff')
        if ($arguments && !empty($arguments)) {
            $userRole = session()->get('role');
            
            if (!in_array($userRole, $arguments)) {
                return \Config\Services::response()
                    ->setStatusCode(403)
                    ->setJSON(['error' => 'Forbidden: You do not have the required role to access this resource.']);
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
