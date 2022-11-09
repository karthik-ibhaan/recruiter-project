<?php
    namespace App\Controllers;
    use CodeIgniter\Controller;
    use App\Models\UserModel;
    use App\Config\Email;
    class Registration extends Controller
    {
        public function index()
        {
            helper(['form']);
            $data = [];
            echo view('registration',$data);
        }

        //Storing Registration Information
        public function RegistrationStore()
        {
            $userModel = new UserModel();
            helper(['form']);
            $session = session();
            $email =  \Config\Services::email();

            //Saving to Table
            if($this->validate($userModel -> validationRules)) {

                $data = [
                    'full_name' => $this->request->getVar('full_name'),
                    'email' => $this->request->getVar('email'),
                    'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                    'level' => $this->request->getVar('level')
                ];

                $userModel->save($data);

                $user = $userModel->where('email', $this->request->getVar('email'))->first();
                $user_id = $user['user_id'];

                //Setting Session Data
                $session_data = [
                    'user_id' => $user['user_id'],
                    'name' => $data['full_name'],
                    'email' => $data['email'],
                    'level' => $data['level'],
                    'isLoggedIn' => TRUE
                ];

                $session->set($session_data);

/* 
                $email->setFrom('recruiter.project0@gmail.com','Recruiter Project');
                $email->setTo($data['email']);
                $email->setSubject('Login Details');
                $email->setMessage('Your Login Details For Recruiter Project Are: <br> Password'.$this->request->getVar('password').'<br>Username:'.$data['email']);

                $email->send();
 */

                return redirect()->to('/home');


            }

            else {
                $data['validation'] = $this->validator;
                echo view('registration', $data);
            }
        }
    }
?>