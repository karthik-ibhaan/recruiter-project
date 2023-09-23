<?php 

namespace Config;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;


class InterviewConsultantFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        helper(['url']);
        $isLoggedIn = session()->get('isLoggedIn');
        $level = session()->get('level');
        if($isLoggedIn == TRUE && $level == "4")
        {
            return redirect()->to('ibhaaninterview');
        }
    }
    
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        
    }
}

?>