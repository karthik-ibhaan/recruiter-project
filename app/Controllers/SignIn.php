<?php
    namespace App\Controllers;
    use CodeIgniter\Controller;
    use App\Models\UserModel;

    class SignIn extends Controller {

        public function index(){
            helper(['form']);
            echo view('signin');
        }

        //Login Authentication

        public function LoginAuth(){
            $session = session();

            $userModel = new UserModel();

            $email = $this->request->getVar('email');
            $password = $this->request->getVar('password');

            $data = $userModel->where('email', $email)->first();

            if($data)
            {
                $pass = $data['password'];
                $authenticatePassword = password_verify($password, $pass);
                if($authenticatePassword) {
                    $level = $userModel->where('email', $email)->findColumn('level');
                    $session_data = [
                        'user_id' => $data['user_id'],
                        'name' => $data['full_name'],
                        'email' => $data['email'],
                        'level' => $level[0],
                        'isLoggedIn' => TRUE
                    ];

                    $session->set($session_data);
                    return redirect()->to('/home');
                }

                else {
                    $session->setFlashdata('msg', 'Password is incorrect.');
                    return redirect()->to('/signin');
                }
            }
            else {
                $session->setFlashdata('msg', 'Email does not exist.');
                return redirect()->to('/signin');
            }
        }
    }
?>