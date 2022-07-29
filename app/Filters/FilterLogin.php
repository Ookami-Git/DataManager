<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class FilterLogin implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        helper('uri');
        if (!session()->get("isSignedIn")) { 
            session()->set('previousUrl',$_SERVER['REQUEST_URI']);
            return redirect()->to(base_url()."/login");
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}