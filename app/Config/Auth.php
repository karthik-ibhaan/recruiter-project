<?php 

namespace Config;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;


class Auth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        helper(['url']);
        $isLoggedIn = session()->get('isLoggedIn');
        $level = session()->get('level');
        if ($isLoggedIn == FALSE || $isLoggedIn == "")
        {
            return redirect()
                ->to('/signin');
        }
        else 
        if($isLoggedIn == TRUE && (url_is('(?i)registration') || url_is('(?i)signin')))
        {
            return redirect()->to('home');
        }
    }
    
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        
    }
}

?>