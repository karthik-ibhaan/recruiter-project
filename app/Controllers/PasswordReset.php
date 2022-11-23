<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\UserModel;
use CodeIgniter\Files\File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PasswordReset extends Controller
{
    public function index()
    {
        helper(['form']);
        echo view('password_reset');
    }

    public function PasswordCheck()
    {
        $userID = $this->request->getVar('user_id');
        $password = $this->request->getVar('password');
        $userModel = new UserModel();
        $data = $userModel->where('user_id', $userID)->first();

        if($data)
        {
            $pass = $data['password'];
            $authenticatePassword = password_verify($password, $pass);
            if($authenticatePassword) {
                echo json_encode("true");
            }

            else {
                echo json_encode("false");
            }
        }
    }

    public function Resetter()
    {
        $pass = $this->request->getVar("password3");
        $pass2 = $this->request->getVar("password2");
        $userModel = new UserModel();
        $db = \Config\Database::connect();
        $builder = $db->table('my_users');
        $data = $userModel->where('user_id',session()->get('user_id'))->first();
        if($pass == $pass2 && $data != "")
        {
            $data2 = [
                'password' => password_hash($pass2, PASSWORD_DEFAULT)
            ];
            $builder->set($data);
            $builder->where('user_id',session()->get('user_id'));
            if($builder->update())
            {
                session()->setFlashdata('msg2','Password successfully changed.');
                return redirect()->to('password_reset');
            }
            else
            {
                session->setFlashdata('msg','Password not reset');
                return redirect()->to('password_reset');
            }
            
        }
    }
}
?>
