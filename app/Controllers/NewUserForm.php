<?php
    namespace App\Controllers;
    use CodeIgniter\Controller;
    use App\Models\UserModel;
    use App\Config\Email;

    class NewUserForm extends Controller {

        public function index()
        {
            helper(['form']);
            $data = [];
            echo view('registration',$data);
        }
    }
?>