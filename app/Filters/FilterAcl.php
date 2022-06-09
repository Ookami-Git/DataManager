<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models;

class FilterAcl implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $roles=session()->get("roles");
        // Admin full access
        if (session()->get("username") == "admin") {return;}
        if (in_array("admin",$roles)) {return;}
        // Check ACL
        $segments=$request->uri->getSegments();
        $page=end($segments);
        $model = new Models\dbAcl();
        $acl = $model->where('page',$page)->first();
        $authorizedUsers = @json_decode($acl['users'],true)??array();
        $authorizedGroups = @json_decode($acl['groups'],true)??array();
        if (count(array_diff($roles,$authorizedGroups)) < count($roles) || in_array(session()->get("username"),$authorizedUsers)) {
            return;
        }
        return redirect()->to(base_url()."/error/401");
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}