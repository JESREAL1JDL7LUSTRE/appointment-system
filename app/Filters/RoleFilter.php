<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $isAjaxOrJson = $request->hasHeader('X-Requested-With') && $request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest' || strpos($request->getHeaderLine('Accept'), 'application/json') !== false;

        if (!session()->get('isLoggedIn')) {
            if ($isAjaxOrJson) {
                return \Config\Services::response()
                    ->setStatusCode(401)
                    ->setJSON(['error' => 'Unauthorized: Please log in first.']);
            }
            return redirect()->to('/login');
        }

        // If specific roles are passed as arguments to the filter (e.g. filter => 'role:Administrator,Staff')
        if ($arguments && !empty($arguments)) {
            $userRole = session()->get('role');
            
            if (!in_array($userRole, $arguments)) {
                if ($isAjaxOrJson) {
                    return \Config\Services::response()
                        ->setStatusCode(403)
                        ->setJSON(['error' => 'Forbidden: You do not have the required role to access this resource.']);
                }
                return redirect()->back();
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
