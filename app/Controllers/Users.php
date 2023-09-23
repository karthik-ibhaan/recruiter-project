<?php

    namespace App\Controllers;
    use CodeIgniter\Controller;
    use App\Models\UserModel;
    use CodeIgniter\Files\File;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    class Users extends Controller
    {
        public function initialise(){
            //Getting User Data To Display
            $userModel = new UserModel();
            $users = $userModel->orderBy('user_id', 'DESC')->findAll();
            $fields = ['user_id','full_name','email','level','created_by'];
            $fieldNames = $userModel->allowedFields;
            //Not Displaying Password
            $fieldNames = array_diff($fieldNames, array('password'));
            $data = [];
            $data['levels'] = ['1'=>'ADMINISTRATOR','2'=>'CO-ORDINATOR','3'=>'RECRUITER', '4'=>'INTERVIEW CONSULTANT'];
            $domain['E&E Development'] = ['Analysis','Circuit Simulation','Embed HW +DDS +MSD','EMI / EMC','Firmware','FPGA','FPGA / RTL Design','Hardware','Layout','Mechanical','Middleware','PCB Layout design','Power Management','Power Supply','RF','Signal & Power Integrity','Software'];
            $domain['E&E Manufacturing'] = ['Box build', 'PCBA - Production', 'PCBA - Proto', 'Power Chords', 'Solar Solutions', 'Transformers', 'Turnkey Mfg', 'UPS', 'Wire Harness'];
            $domain['Information Technology'] = ['Testing', 'Development','Analysis','Support'];
            $domain['Mechanical'] = ['Cost Engineering', 'Design', 'Manufacturing', 'Quality Assurance', 'Quality Management', 'Supply Chain Management'];
            $industrySegment = ['Aero Engine','Aerostructure','Automotive','Avionics','Consumer Durables','Defence','Industrial Automation','Information Technology','Locomotive','Marine','Medical Devices','Renewable Energy','Survelience','Telecom'];
            $data['fieldNames'] = $fieldNames;
            $data['users'] = $users;
            $data['domain'] = $domain;
            $data['industry'] = $industrySegment;
            return $data;
        }

        public function index()
        {
            helper(['form']);
            $data = Users::initialise();
            echo view('users', $data);
            $userModel = new UserModel();
        }

        public function ResetPassword()
        {
            helper(['form']);
            $session = session();
            $user_id = $this->request->getVar('reset_user_id');
            $pass = (string) "ibhaan@1234";
            $db = \Config\Database::connect();
            $sql = $db->table('my_users');
            $sql->set('password',password_hash($pass, PASSWORD_DEFAULT));
            $sql->where('user_id',$user_id);
            if($sql->update())
            {
                $session->setFlashdata('success',"The Password Has Been Reset for User ID ".$user_id.".");
                return redirect()->to('/users');
            }
            else
            {
                $session->setFlashdata('error',$db->error());
                return redirect()->to('/users');
            }
        }

        public function AddUser()
        {
            helper(['form']);
            $header=true;
            $count = 0;

            $session = session();
            $userModel = new UserModel();
            //Generating A Random Password
            $chrList = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $chrRepeatMin = 1;
            $chrRepeatMax = 10;
            $chrRandomLength = 10;

            $full_name = $this->request->getVar('full_name');
            $email = $this->request->getVar('email');
            $pass = (string) "ibhaan@1234";
            $level = $this->request->getVar('user_level');
            $level = strtolower($level);
            $userLevel = ['administrator', 'co-ordinator', 'recruiter'];

            //Validation
            if(strlen($full_name) <= 2)
            {
                $session->setFlashdata('error', 'Full name field invalid.');
                return redirect()->to('/users');
            }

            if(filter_var($email, FILTER_VALIDATE_EMAIL) == FALSE)
            {
                $session->setFlashdata('error', 'Email field invalid.');
                return redirect()->to('/users');
            }

            if($userModel->where('email', $email)->first() != NULL){
                $session->setFlashdata('error', 'Email field is not unique.');
                return redirect()->to('/users');
            }

            if(!$level)
            {
                $session->setFlashdata('error', 'Level not set for '.$full_name);
                return redirect()->to('/users');
            }

            //Saving To Table
            else
            {
                $admin_id = (int) session()->get('user_id');
                $userModel = new UserModel();
                $data = [
                    'full_name' => $full_name,
                    'email' => $email,
                    'password' => password_hash($pass, PASSWORD_DEFAULT),
                    'created_by' => $session->get('email'),
                    'level' => $level
                ];

                if($userModel->save($data))
                {
                    if($level == "4")
                    {
                        $db = \Config\Database::connect();
                        $userID = $userModel->getInsertID();
                        $interviewerName = $full_name;
                        $interviewerEmail = $email;
                        $interviewerPhone = $this->request->getVar('interviewerPhone');
                        $domain = $this->request->getVar('domain');
                        $industry = $this->request->getVar('industry');
                        $skills = $this->request->getVar('skills');
                        $consultants = $db->table('interview_consultants');
                        $saveData = [
                            'USER_ID' => $userID,
                            'INTERVIEWER_NAME' => $interviewerName,
                            'INTERVIEWER_EMAIL' => $interviewerEmail,
                            'INTERVIEWER_PHONE' => $interviewerPhone,
                            'DOMAIN' => $domain,
                            'INDUSTRY' => $industry,
                            'SKILLS' => $skills
                        ];
                        if($consultants->insert($saveData))
                        {
                            $session->setFlashdata('success', 'User has been added to the database.');
                        }
                        else
                        {
                            $session->setFlashdata('error', 'Error occurred.');
                        }
                    }
                    else
                    {
                        $session->setFlashdata('success', 'User has been added to the database.');
                    }
                }
                else
                {
                    $session->setFlashdata('error', 'Error occurred.');
                }

            }
            
            return redirect()->to('/users');

        }

        public function EditUser()
        {
            helper(['form']);
            $db = \Config\Database::connect();
            $userModel = new UserModel();
            $session = session();
            $userID = $this->request->getVar('edit_user_id');
            $userEmail = $this->request->getVar('edit_email');
            $userLevel = $this->request->getVar('edit_user_level');
            $data = [
                'level' => $userLevel
            ];
            echo $userID, $userEmail, $userLevel;
            if($userID != "" && $userEmail != "" && $userLevel != "")
            {
                $sql = $db->table('my_users');
                $sql->set('email',$userEmail);
                $sql->set('level',$userLevel);
                $sql->where('user_id',$userID);
                if($sql->update())
                {
                    if($userLevel == 1)
                    {
                        $userLevel = "ADMINISTRATOR";
                    }
                    if($userLevel == 2)
                    {
                        $userLevel = "CO-ORDINATOR";
                    }
                    if($userLevel == 3)
                    {
                        $userLevel = "RECRUITER";
                    }
                    $session->setFlashdata('success', 'User ID '.$userID.' has successfully been updated to '.$userLevel.'.');
                }
                else
                {
                    $session->setFlashdata('error',print_r($db->error()));
                }
            }
            else
            {
                $session->setFlashdata('error',"Details are insufficient. Please add the required details");
            }
            return redirect()->to('/users');
        }

        //FileUpload Functionality
        public function FileUpload()
        {
            helper(['form']);

            $session = session();

            $header=true;
            $count = 0;

            $userModel = new UserModel();

            $fileName = $this->request->getFile('userfile');
            $ext = esc($fileName->guessExtension());

            if($ext != "csv")
            {
                $session->setFlashdata('error', 'Only CSV accepted. Please insert a CSV file.');
                return redirect()->to('/users');
            }
            $path = WRITEPATH . 'uploads/' . $fileName->store();
            
            $handle = fopen($path, "r");

            while (($details = fgetcsv($handle, 1000, ",")) !== FALSE)
            {

                if ($details[0] == '' || $details[1] == '' || $details[2] == '')
                {
                    break;                        
                }            

                //Generating A Random Password
                $chrList = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $chrRepeatMin = 1;
                $chrRepeatMax = 10;
                $chrRandomLength = 10;

                $full_name = (string) trim($details[0]);
                $email = (string) trim($details[1]);
                $pass = 'ibhaan@1234';
                $level = (string) trim($details[2]);
                $level = strtolower($level);

                //Validation
                if(strlen($full_name) <= 2)
                {
                    $session->setFlashdata('error', 'Full name field invalid.'.$full_name);
                    return redirect()->to('/users');
                }

                if(filter_var($email, FILTER_VALIDATE_EMAIL) == FALSE)
                {
                    $session->setFlashdata('error', 'Email field invalid.');
                    return redirect()->to('/users');
                }

                if($userModel->where('email', $email)->first() != NULL){
                    $session->setFlashdata('error', 'Email field is not unique.');
                    return redirect()->to('/users');
                }

                if(!$level)
                {
                    $session->setFlashdata('error', 'Level not set for '.$full_name);
                    return redirect()->to('/users');
                }

                if($level != 1 && $level != 2 && $level != 3)
                {
                    $session->setFlashdata('error', 'Level not set for '.$full_name.' properly.'.$level);
                    return redirect()->to('/users');
                }

                //Saving To Table
                else
                {
                    $admin_id = (int) session()->get('user_id');
                    $userModel = new UserModel();
                    $data = [
                        'full_name' => $full_name,
                        'email' => $email,
                        'password' => password_hash($pass, PASSWORD_DEFAULT),
                        'level' => $level
                    ];

                    $userModel->save($data);
                    $count = $count+1;
                    session()->setFlashdata('count',$count);

                }
            }
            return redirect()->to('/users');    
        }

        public function FileExport()
        {
            helper(['form']);
            helper('filesystem');

            $session = session();

            $header=true;
            $count = 0;

            $userModel = new UserModel();
            $users = $userModel->orderBy('user_id', 'DESC')->findAll();
            $fieldNames = $userModel->allowedFields;
            $fileName = 'users.xlsx';
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            unset($fieldNames[3]);

            $col = "A";
            $row = "1";
            foreach($fieldNames as $keys=>$values){
                $sheet->setCellValue("$col$row", ucwords($values));
                $col++;
            }

            $col = "A";
            $row = "2";
            foreach($users as $keys=>$data){
                foreach($fieldNames as $keys=>$value){
                    $sheet->setCellValue("$col$row", $data[$value]);
                    $col++;
                }
                $col="A";
                $row++;
            }

            $writer = new Xlsx($spreadsheet);
            $writer->save($fileName);
            $fileLocation = ROOTPATH."/public/".$fileName;

            $fileData = file_get_contents($fileLocation);
            return $this->response->download($fileLocation,null)->setFileName($fileName);
            return redirect()->to('/users');
        }
    
        public function DeleteUser()
        {
            helper(['form']);
            $btnID = (int) $this->request->getVar('userIDDel');
            $userModel = new UserModel();
            $userModel->delete($btnID);
            $userID = (int) $btnID;
            return redirect()->to('/users');
        }

    }
?>