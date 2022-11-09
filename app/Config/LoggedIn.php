<?php 

namespace Config;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;


class LoggedIn implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        helper(['url']);
        $isLoggedIn = session()->get('isLoggedIn');
        if($isLoggedIn == TRUE)
        {
            return redirect()->to('home');
        }
    }
    
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        
    }
}

?>