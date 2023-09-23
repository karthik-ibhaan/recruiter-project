<?php

    namespace App\Controllers;
    use CodeIgniter\Controller;
    use App\Models\CandidateModel;
    use App\Models\DemandModel;
    use App\Models\ClientModel;
    use CodeIgniter\Files\File;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use App\Libraries\RecruitmentStatuses;

    class Candidates extends Controller
    {

        public function initialise()
        {
            $header = true;
            $session = session();
            $candidateModel = new CandidateModel();
            $demandModel = new DemandModel();
            $clientModel = new ClientModel();
            $demands = $demandModel->orderBy('DEMAND_ID','DESC')->findAll();
            $clients = $clientModel->orderBy('CLIENT_ID','DESC')->findAll();
            $db = \Config\Database::connect();
            $options = [];
            $demandOptions = $db->table('demand');
            $demandOptions->select('demand.DEMAND_ID, demand.JOB_TITLE, demand.JD_ID, client.CLIENT_ID, client.CLIENT_NAME');
            $demandOptions->where('demand.demand_status','Open');
            $demandOptions->join('client', 'demand.client_id = client.client_id');
            $demandOptions = $demandOptions->get()->getResultArray();
            $builder = $db->table('candidates');
            $builder2 = $db->table('candidates');
            $table = $candidateModel->table;
            $recruitmentStatuses = new RecruitmentStatuses;
            $recruitmentStatus = $recruitmentStatuses->recruitmentStatuses();

            $location = [];
            $path = WRITEPATH . 'uploads/' . 'locations.csv';
            $handle = fopen($path, "r");

            while (($details = fgetcsv($handle, 1000, ",")) !== FALSE)
            {
                if($header == true)
                {
                    $header = false;
                    continue;
                }

                if($details[0] != NULL)
                {
                    array_push($location, $details[0]);
                }
            }

            // $builder->select('candidates.*','demand.demand_id','demand.job_title, my_users.full_name');
            // $builder->join('demand', 'demand.demand_id = candidates.demand_id');
            $sqlquery = 'SELECT client.CLIENT_NAME, demand.DEMAND_ID, demand.DEMAND_STATUS, demand.CUS_SPOC, demand.JOB_TITLE, candidates.*, my_users.FULL_NAME FROM candidates JOIN demand ON demand.demand_id = candidates.DEMAND_ID JOIN client ON client.CLIENT_ID = demand.CLIENT_ID JOIN my_users ON my_users.USER_ID = candidates.RECRUITER WHERE candidates.RECRUITER = '.$session->get('user_id');
            $query = $db->query($sqlquery);
            $candidates = $query->getResultArray();
            $fieldNames = $query->getFieldNames();
            $fieldNames = array_diff($fieldNames, ["CV_LOCATION"]);
            $fieldNames = array_unique($fieldNames);

            // $candidates = $candidateModel->orderBy('CANDIDATE_ID', 'DESC')->where('recruiter', $session->get('user_id'))->findAll();
            // $fieldNames = $candidateModel->allowedFields;
            foreach($candidates as $keys=>$data)
            {
                $datetime = $data['SUBMISSION_DATE'];
                $date = strtotime("$datetime");
                $candidates[$keys]['SUBMISSION_DATE'] = date("Y-m-d", $date);
                $candidates[$keys]['SUBMISSION_TIME'] = date("H:i:s", $date);
            }
            $fieldNames = array_diff($fieldNames, ["SUBMISSION_DATE", "FULL_NAME"]);
            array_push($fieldNames, "SUBMISSION_DATE", "SUBMISSION_TIME", "FULL_NAME");
            $data['demands'] = $demands;
            $data['statusFilter'] = "";
            $data['demandOptions'] = $demandOptions;
            $data['clients'] = $clients;
            $data['location'] = $location;
            $data['status'] = $recruitmentStatus;
            $data['candidates'] = $candidates;
            $data['fieldNames'] = $fieldNames;
            return $data;
        }
        
        public function index()
        {
            helper(['form']);
            $data = Candidates::initialise();
            echo view('candidates', $data);
        }

        public function FileExport()
        {
            helper(['form']);
            helper('filesystem');

            $session = session();

            $header=true;
            $count = 0;

            $candidateModel = new CandidateModel();
            $candidates = $candidateModel->orderBy('candidate_id', 'DESC')->findAll();
            $fieldNames = $candidateModel->allowedFields;
            $fileName = 'candidates.xlsx';
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $col = "A";
            $row = "1";
            foreach($fieldNames as $keys=>$values){
                $sheet->setCellValue("$col$row", ucwords($values));
                $col++;
            }

            $col = "A";
            $row = "2";
            foreach($candidates as $keys=>$data){
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
            $mime = mime_content_type($fileLocation);
    
            if(!is_readable($fileLocation))
            {
                exit(json_encode("ERROR OCCURRED!"));
            }

            // Build the headers to push out the file properly.
            header('Pragma: public');     // required
            header('Expires: 0');         // no cache
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($fileLocation)).' GMT');
            header('Cache-Control: private',false);
            header('Content-Type: '.$mime);  // Add the mime type from Code igniter.
            header('Content-Disposition: attachment; filename="'.basename($fileLocation).'"');  // Add the file name
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: '.filesize($fileLocation)); // provide file size
            header('Connection: close');

            readfile($fileLocation); //push it out

            unlink($fileLocation); //Delete Temporary File
        }

        public function AddCandidate(){
            helper(['form']);
            $candidateModel = new CandidateModel();
            $csrfToken = csrf_token();
            $csrfHash = csrf_hash();
            $session = session();
            $datetime = "";
            $client_id = $this->request->getVar('client');
            $id = str_replace("&", "", $client_id);
            $id = str_replace(" ", "", $id);
            $demand_id = $this->request->getVar((string) $id);
            $status = $this->request->getVar('status');
            if($status == "")
            {
                $error = 'Update of Candidate '.$c_name.' unsuccessful. Recruitment Status empty.';
                exit(json_encode($data));
            }
            $c_name = trim($this->request->getVar('candidate_name'));
            $emailAdd = trim($this->request->getVar('emailAdd'));
            $phno_1 = $this->request->getVar('phno_1');
            $phno_2 = $this->request->getVar('phno_2');
            $phno = array($phno_1, $phno_2);
            $location = $this->request->getVar('location');
            $org = trim($this->request->getVar('organisation'));
            $experience = $this->request->getVar('experience');
            $cctc = $this->request->getVar('cctc');
            $ectc = trim($this->request->getVar('ectc'));
            $NP = $this->request->getVar('NP');
            $plannedDOJ = "";
            $actualDOJ = "";
            $selectionCTC = "";
            $selectionDate = "";
            $exitDate = "";
            $interviewDate = $this->request->getVar('interview-date');
            $interviewTime = $this->request->getVar('interview-time');
            $tz = 'Asia/Kolkata';
            $timestamp = time();
            $dt = new \DateTime("now", new \DateTimeZone($tz)); 
            $dt->setTimestamp($timestamp);
            $submissionDate = date('Y-m-d');
            $submissionTime = date('H:i');
            if($submissionDate != "")
            {
                $datetime2 = date('Y-m-d H:i:s', strtotime("$submissionDate $submissionTime"));
            }
            if($interviewDate != "" && $interviewTime != "")
            {
                $datetime = date('Y-m-d H:i:s', strtotime("$interviewDate $interviewTime"));
            }
            if(preg_match('/^(11|10|09|08)/', $status))
            {
                $selectionDate = $this->request->getVar('selectionDate');
                $selectionCTC = $this->request->getVar('selectionCTC');
                $exitDate = $this->request->getVar('exitDate');
                $plannedDOJ = $this->request->getVar('plannedDOJ');
                $actualDOJ = $this->request->getvar('actualDOJ');

                if($selectionCTC == "")
                {
                    $error = "SELECTION CTC MISSING. PLEASE TRY AGAIN.";
                    $data = array($error);
                    exit(json_encode($data));
                }
                else if($selectionDate == "")
                {
                    $error = "SELECTION DATE MISSING. PLEASE TRY AGAIN.";
                    $data = array($error);
                    exit(json_encode($data));
                }
                else if($plannedDOJ == "")
                {
                    $error = "PLANNED DOJ MISSING. PLEASE TRY AGAIN.";
                    $data = array($error);
                    exit(json_encode($data));
                }
            }
            if(preg_match('/^(10)/', $status))
            {
                if($actualDOJ == "")
                {
                    $error = "ACTUAL DOJ MISSING. PLEASE TRY AGAIN.";                    
                    $data = array($error);
                    exit(json_encode($data));
                }
            }
            $recruiter = session()->get('user_id');
            $cv = $this->request->getFile('cv');
            if(is_uploaded_file($cv))
            {
                $ext = $cv->guessExtension();
                if(!$ext)
                {
                    $error = "CV Could Not Be Stored. Try Again.";
                    $data = array($error);
                    exit(json_encode($data));
                }
                $fileName = time() . '_' . str_replace('"', '', $c_name) . '.' . $ext;
                $fileName = str_replace(' ', '_', $fileName);
                $fileName = preg_replace("/[^a-zA-Z0-9\s!?.,_]/", "", $fileName);
                $fileName = preg_replace("/_+/", "_", $fileName);
                $path = $cv->store('cv', $fileName);
                if(!$path)
                {
                    $error = "CV Could Not Be Stored. Try Again.";
                    $data = array($error);
                    exit(json_encode($data));
                }
            }
            else
            {
                $error = "CV Was Not Found/Corrupted. Try Again.";
                $data = array($error);
                exit(json_encode($data));
            }

            $saveData = [
                'DEMAND_ID' => $demand_id,
                'RECRUITMENT_STATUS' => $status,
                'CANDIDATE_NAME' => $c_name,
                'EMAIL_ADDRESS' => $emailAdd,
                "PHONE_NO" => json_encode($phno),
                'ORGANISATION' => $org,
                'WORK_LOCATION' => $location,
                'TOTAL_EXPERIENCE' => $experience,
                'CCTC_LPA' => $cctc,
                'ECTC_LPA' => $ectc,
                'NOTICE_PERIOD_DAYS' => $NP,
                'RECRUITER' => $recruiter,
                'CV_LOCATION' => $path
            ];

            if($datetime != "")
            {
                $saveData['INTERVIEW_DATE'] = $datetime;
            }
            if($datetime2 != "")
            {
                $saveData['SUBMISSION_DATE'] = $datetime2;
            }
            if($plannedDOJ != "")
            {
                $saveData['PLANNED_DOJ'] = $plannedDOJ;
            }
            if($actualDOJ != "")
            {
                $saveData['ACTUAL_DOJ'] = $actualDOJ;
            }
            if($exitDate != "")
            {
                $saveData['EXIT_DATE'] = $exitDate;
            }
            if($selectionDate != "")
            {
                $saveData['SELECTION_DATE'] = $selectionDate;
            }
            if($selectionCTC != "")
            {
                $saveData['SELECTION_CTC'] = $selectionCTC;
            } 

            $db = \Config\Database::connect();
            $builder = $db->table('candidates');

            $candidateModel = new CandidateModel();
            $candidates = $candidateModel->orderBy('CANDIDATE_ID', 'DESC')->where('DEMAND_ID', $demand_id)->where('email_address', $emailAdd)->findAll();
            
            if(count($candidates) == 0)
            {
                if($builder->insert($saveData))
                {
                    $builder2 = $db->table('attendance');
                    $builder3 = $db->table('candidates');
                    $builder3->select('CANDIDATE_ID');
                    $builder3->where('SUBMISSION_DATE>=',date('Y-m-d 00:00:00', strtotime('today')));
                    $builder3->where('SUBMISSION_DATE<',date('Y-m-d 00:00:00', strtotime('tomorrow')));
                    $builder3->where('RECRUITER',$recruiter);
                    $candidatesCount = $builder3->countAllResults();
                    if($candidatesCount >= 4)
                    {
                        $attendanceCheck = $db->table('attendance');
                        $attendanceCheck->where('RECRUITER_ID',$session->get('user_id'));
                        $attendanceCheck->where('ATTENDANCE_DATE>=',date('Y-m-d',strtotime("today")));
                        $attendanceCheck->where('ATTENDANCE_DATE<',date('Y-m-d',strtotime("tomorrow")));
                        $attendanceArray = $attendanceCheck->get()->getResultArray();
                        if(is_array($attendanceArray) && count($attendanceArray) == 0)
                        {
                            $saveData2 = [
                                'RECRUITER_ID' => $recruiter,
                                'ATTENDANCE_DATE' => date('Y-m-d', strtotime('today')),
                                'PRESENT' => 1
                            ];
                            if($builder2->insert($saveData2))
                            {
                            }
                            else
                            {
                                $error = $db->error();
                                $data = array($error);
                                exit(json_encode($data));
                            }
                        }
                    }
                    $sqlquery = 'SELECT client.CLIENT_NAME, demand.DEMAND_ID, demand.DEMAND_STATUS, demand.CUS_SPOC, demand.JOB_TITLE, candidates.*, my_users.FULL_NAME FROM candidates JOIN demand ON demand.DEMAND_ID = candidates.DEMAND_ID JOIN client ON client.CLIENT_ID = demand.CLIENT_ID JOIN my_users ON my_users.USER_ID = candidates.RECRUITER WHERE candidates.DEMAND_ID = "'.$demand_id.'" AND candidates.EMAIL_ADDRESS = "'.$emailAdd.'" LIMIT 1';
                    $query = $db->query($sqlquery);
                    $candidates = $query->getResultArray();
                    foreach($candidates as $keys=>$data)
                    {
                        $datetime = $data['SUBMISSION_DATE'];
                        $date = strtotime("$datetime");
                        $candidates[$keys]['SUBMISSION_DATE'] = date("Y-m-d", $date);
                        $candidates[$keys]['SUBMISSION_TIME'] = date("H:i:s", $date);
                    }
                    $data = array("success", $csrfToken, $csrfHash, $candidates);
                    exit(json_encode($data));

                }
                else
                {
                    $error = $db->error();
                    $data = array($error);
                    exit(json_encode($data));
                }
            }
        }

        public function AddCandidate2(){
            helper(['form']);
            $candidateModel = new CandidateModel();
            $session = session();
            $datetime = "";
            $demand_id = $this->request->getVar('demand');
            $status = $this->request->getVar('status');
            $c_name = trim($this->request->getVar('candidate_name'));
            $emailAdd = trim($this->request->getVar('emailAdd'));
            $phno_1 = $this->request->getVar('phno_1');
            $phno_2 = $this->request->getVar('phno_2');
            $phno = array($phno_1, $phno_2);
            $location = $this->request->getVar('location');
            $org = trim($this->request->getVar('organisation'));
            $experience = $this->request->getVar('experience');
            $cctc = $this->request->getVar('cctc');
            $ectc = trim($this->request->getVar('ectc'));
            $NP = $this->request->getVar('NP');
            $plannedDOJ = "";
            $actualDOJ = "";
            $selectionCTC = "";
            $selectionDate = "";
            $exitDate = "";
            $interviewDate = $this->request->getVar('interview-date');
            $interviewTime = $this->request->getVar('interview-time');
            $tz = 'Asia/Kolkata';
            $timestamp = time();
            $dt = new \DateTime("now", new \DateTimeZone($tz)); 
            $dt->setTimestamp($timestamp);
            $submissionDate = date('Y-m-d');
            $submissionTime = date('H:i');
            if($submissionDate != "")
            {
                $datetime2 = date('Y-m-d H:i:s', strtotime("$submissionDate $submissionTime"));
            }
            if($interviewDate != "" && $interviewTime != "")
            {
                $datetime = date('Y-m-d H:i:s', strtotime("$interviewDate $interviewTime"));
            }
            if(preg_match('/^(11|10|09|08)/', $status))
            {
                $selectionDate = $this->request->getVar('selectionDate');
                $selectionCTC = $this->request->getVar('selectionCTC');
                $exitDate = $this->request->getVar('exitDate');
                $plannedDOJ = $this->request->getVar('plannedDOJ');
                $actualDOJ = $this->request->getvar('actualDOJ');

                if($selectionCTC == "")
                {
                    $error = "SELECTION CTC MISSING. PLEASE TRY AGAIN.";
                    $data = array($error);
                    exit(json_encode($data));
                }
                else if($selectionDate == "")
                {
                    $error = "SELECTION DATE MISSING. PLEASE TRY AGAIN.";
                    $data = array($error);
                    exit(json_encode($data));
                }
                else if($plannedDOJ == "")
                {
                    $error = "PLANNED DOJ MISSING. PLEASE TRY AGAIN.";
                    $data = array($error);
                    exit(json_encode($data));
                }
            }
            if(preg_match('/^(10)/', $status))
            {
                if($actualDOJ == "")
                {
                    $error = "ACTUAL DOJ MISSING. PLEASE TRY AGAIN.";                    
                    $data = array($error);
                    exit(json_encode($data));
                }
            }

            $recruiter = session()->get('user_id');

            $cv = $this->request->getFile('cv');
            if(is_uploaded_file($cv))
            {
                $ext = $cv->guessExtension();
                if(!$ext)
                {
                    $error = "CV Could Not Be Stored. Try Again.";
                    $data = array($error);
                    exit(json_encode($data));
                }
                $fileName = time() . '_' . str_replace('"', '', $c_name) . '.' . $ext;
                $fileName = str_replace(' ', '_', $fileName);
                $fileName = preg_replace("/[^a-zA-Z0-9\s!?.,_]/", "", $fileName);
                $fileName = preg_replace("/_+/", "_", $fileName);
                $path = $cv->store('cv', $fileName);
                if(!$path)
                {
                    $error = "CV Could Not Be Stored. Try Again.";
                    $data = array($error);
                    exit(json_encode($data));
                }
            }
            else
            {
                $error = "CV Was Not Found/Corrupted. Try Again.";
                $data = array($error);
                exit(json_encode($data));
            }

            $saveData = [
                'DEMAND_ID' => $demand_id,
                'RECRUITMENT_STATUS' => $status,
                'CANDIDATE_NAME' => $c_name,
                'EMAIL_ADDRESS' => $emailAdd,
                "PHONE_NO" => json_encode($phno),
                'ORGANISATION' => $org,
                'WORK_LOCATION' => $location,
                'TOTAL_EXPERIENCE' => $experience,
                'CCTC_LPA' => $cctc,
                'ECTC_LPA' => $ectc,
                'NOTICE_PERIOD_DAYS' => $NP,
                'RECRUITER' => $recruiter,
                'CV_LOCATION' => $path
            ];

            if($datetime != "")
            {
                $saveData['INTERVIEW_DATE'] = $datetime;
            }
            if($datetime2 != "")
            {
                $saveData['SUBMISSION_DATE'] = $datetime2;
            }
            if($plannedDOJ != "")
            {
                $saveData['PLANNED_DOJ'] = $plannedDOJ;
            }
            if($actualDOJ != "")
            {
                $saveData['ACTUAL_DOJ'] = $actualDOJ;
            }
            if($exitDate != "")
            {
                $saveData['EXIT_DATE'] = $exitDate;
            }
            if($selectionDate != "")
            {
                $saveData['SELECTION_DATE'] = $selectionDate;
            }
            if($selectionCTC != "")
            {
                $saveData['SELECTION_CTC'] = $selectionCTC;
            } 

            $db = \Config\Database::connect();
            $builder = $db->table('candidates');

            $candidateModel = new CandidateModel();
            $candidates = $candidateModel->orderBy('CANDIDATE_ID', 'DESC')->where('DEMAND_ID', $demand_id)->where('email_address', $emailAdd)->findAll();
            
            if(count($candidates) == 0)
            {
                if($builder->insert($saveData))
                {
                    $builder2 = $db->table('attendance');
                    $builder3 = $db->table('candidates');
                    $builder3->select('CANDIDATE_ID');
                    $builder3->where('SUBMISSION_DATE>=',date('Y-m-d 00:00:00', strtotime('today')));
                    $builder3->where('SUBMISSION_DATE<',date('Y-m-d 00:00:00', strtotime('tomorrow')));
                    $builder3->where('RECRUITER',$recruiter);
                    $candidatesCount = $builder3->countAllResults();
                    if($candidatesCount >= 4)
                    {
                        $attendanceCheck = $db->table('attendance');
                        $attendanceCheck->where('RECRUITER_ID',$session->get('user_id'));
                        $attendanceCheck->where('ATTENDANCE_DATE>=',date('Y-m-d',strtotime("today")));
                        $attendanceCheck->where('ATTENDANCE_DATE<',date('Y-m-d',strtotime("tomorrow")));
                        $attendanceArray = $attendanceCheck->get()->getResultArray();
                        if(is_array($attendanceArray) && count($attendanceArray) == 0)
                        {
                            $saveData2 = [
                                'RECRUITER_ID' => $recruiter,
                                'ATTENDANCE_DATE' => date('Y-m-d', strtotime('today')),
                                'PRESENT' => 1
                            ];
                            if($builder2->insert($saveData2))
                            {
                            }
                            else
                            {
                                $error = $db->error();
                                $data = array($error);
                                exit(json_encode($data));
                            }
                        }
                    }
                    $sqlquery = 'SELECT demand.DEMAND_ID, client.CLIENT_ID, client.CLIENT_NAME, demand.JOB_TITLE, candidates.*, my_users.FULL_NAME FROM candidates JOIN demand ON demand.DEMAND_ID = candidates.DEMAND_ID JOIN client ON client.CLIENT_ID = demand.CLIENT_ID JOIN my_users ON my_users.USER_ID = candidates.RECRUITER WHERE candidates.DEMAND_ID = "'.$demand_id.'" AND candidates.EMAIL_ADDRESS = "'.$emailAdd.'" LIMIT 1';
                    $query = $db->query($sqlquery);
                    $candidates = $query->getResultArray();
                    $csrfToken = csrf_token();
                    $csrfHash = csrf_hash();
                    $data = array("success", $csrfToken, $csrfHash, $candidates);
                    exit(json_encode($data));

                }
                else
                {
                    $error = $db->error();
                    $data = array($error);
                    exit(json_encode($data));
                }
            }
        }
        
        public function CheckExisting()
        {
            helper(['form']);
            $data = Candidates::initialise();
            $demand_id = $this->request->getVar('demand_id');
            $email = $this->request->getVar('email');
            $candidateModel = new CandidateModel();
            $candidates = $candidateModel->orderBy('CANDIDATE_ID', 'DESC')->where('DEMAND_ID', $demand_id)->where('email_address', $email)->findAll();
            if($candidates)            
                echo json_encode("true");
            else
                echo json_encode("false");
        }

        public function CheckExisting2()
        {
            helper(['form']);
            $data = Candidates::initialise();
            $demand_id = $this->request->getVar('demand_id');
            $email = $this->request->getVar('email');
            $candidateID = $this->request->getVar('candidate_id');
            $candidateModel = new CandidateModel();
            $candidates = $candidateModel->orderBy('CANDIDATE_ID', 'DESC')->where('DEMAND_ID', $demand_id)->where('email_address', $email)->where('candidate_id!=', $candidateID)->findAll();
            if($candidates)
                echo json_encode("true");
            else
                echo json_encode("false");
        }

        public function EditCandidate(){
            helper(['form']);
            $session = session();
            $candidateID = $this->request->getVar('candidateID2');
            $demand2 = $this->request->getVar('demand2');
            $status = $this->request->getVar('status2');
            if($status == "")
            {
                $error = 'Update of Candidate '.$c_name.' unsuccessful. Recruitment Status empty.';
                exit(json_encode($data));
            }
            $c_name = trim($this->request->getVar('candidate_name2'));
            $emailAdd = trim($this->request->getVar('emailAdd2'));
            $phno_1 = $this->request->getVar('edit_phno_1');
            $phno_2 = $this->request->getVar('edit_phno_2');
            $phno = array($phno_1, $phno_2);
            $location = $this->request->getVar('location2');
            $org = trim($this->request->getVar('organisation2'));
            $experience = $this->request->getVar('experience2');
            $cctc = $this->request->getVar('cctc2');
            $ectc = trim($this->request->getVar('ectc2'));
            $NP = $this->request->getVar('NP2');
            $plannedDOJ = "";
            $actualDOJ = "";
            $selectionCTC = "";
            $selectionDate = "";
            $exitDate = "";
            $interviewDate = $this->request->getVar('interview-date2');
            $interviewTime = $this->request->getVar('interview-time2');
            if(preg_match('/^(11|10|09|08)/', $status))
            {
                $selectionDate = $this->request->getVar('selectionDate2');
                $selectionCTC = $this->request->getVar('selectionCTC2');
                $exitDate = $this->request->getVar('exitDate2');
                $plannedDOJ = $this->request->getVar('plannedDOJ2');
                $actualDOJ = $this->request->getvar('actualDOJ2');

                if($selectionCTC == "")
                {
                    $error = "SELECTION CTC MISSING. PLEASE TRY AGAIN.";
                    $data = array($error);
                    exit(json_encode($data));
                }
                else if($selectionDate == "")
                {
                    $error = "SELECTION DATE MISSING. PLEASE TRY AGAIN.";
                    $data = array($error);
                    exit(json_encode($data));
                }
                else if($plannedDOJ == "")
                {
                    $error = "PLANNED DOJ MISSING. PLEASE TRY AGAIN.";
                    $data = array($error);
                    exit(json_encode($data));
                }
            }
            if(preg_match('/^(10)/', $status))
            {
                if($actualDOJ == "")
                {
                    $error = "ACTUAL DOJ MISSING. PLEASE TRY AGAIN.";                    
                    $data = array($error);
                    exit(json_encode($data));
                }
            }

            $recruiter = session()->get('user_id');


            $saveData = [
                'DEMAND_ID' => $demand2,
                'RECRUITMENT_STATUS' => $status,
                'CANDIDATE_NAME' => $c_name,
                'EMAIL_ADDRESS' => $emailAdd,
                "PHONE_NO" => json_encode($phno),
                'ORGANISATION' => $org,
                'WORK_LOCATION' => $location,
                'TOTAL_EXPERIENCE' => $experience,
                'CCTC_LPA' => $cctc,
                'ECTC_LPA' => $ectc,
                'NOTICE_PERIOD_DAYS' => $NP,
                'RECRUITER' => $recruiter,
            ];

            $cv = $this->request->getFile('cv2');
            if(is_uploaded_file($cv))
            {
                $ext = $cv->guessExtension();
                if($ext)
                {
                    $fileName = time() . '_' . str_replace('"', '', $c_name) . '.' . $ext;
                    $fileName = str_replace(' ', '_', $fileName);
                    $fileName = preg_replace("/[^a-zA-Z0-9\s!?.,_]/", "", $fileName);
                    $fileName = preg_replace("/_+/", "_", $fileName);
                    $path = $cv->store('cv', $fileName);
                    if(!$path)
                    {
                        $error = "cv Could Not Be Stored. Try Again.";
                        $data = array($error);
                        exit(json_encode($data));
                    }
                    else
                    {
                        $saveData['CV_LOCATION'] = $path;
                    }
                }
            }

            if($interviewDate != "" && $interviewTime != "")
            {
                $datetime = date('Y-m-d H:i:s', strtotime("$interviewDate $interviewTime"));
                $saveData['INTERVIEW_DATE'] = $datetime;
            }
            if($plannedDOJ != "")
            {
                $saveData['PLANNED_DOJ'] = $plannedDOJ;
            }
            if($actualDOJ != "")
            {
                $saveData['ACTUAL_DOJ'] = $actualDOJ;
            }
            if($exitDate != "")
            {
                $saveData['EXIT_DATE'] = $exitDate;
            }
            if($selectionDate != "")
            {
                $saveData['SELECTION_DATE'] = $selectionDate;
            }
            if($selectionCTC != "")
            {
                $saveData['SELECTION_CTC'] = $selectionCTC;
            }

            $db = \Config\Database::connect();
            $builder = $db->table('candidates');
            $builder->set($saveData);
            $builder->where('candidate_id',$candidateID);
            if($builder->update())
            {
                $csrfToken = csrf_token();
                $csrfHash = csrf_hash();
                $data = array("success", $csrfToken, $csrfHash);
                exit(json_encode($data));
            }
            else
            {
                $error = $db->error();
                $data = array($error);
                exit(json_encode($data));
            }
        }

        public function GetCandidate(){
            helper(['form']);
            $candidateID = $this->request->getVar('candidate_id');
            $candidateModel = new CandidateModel();
            $demandModel = new DemandModel();
            $candidate = $candidateModel->find($candidateID);
            $demand = $demandModel->where('DEMAND_ID', $candidate['DEMAND_ID'])->findAll();
            $details = array($candidate,$demand);
            echo json_encode($details);
        }
        public function Filter()
        {
            
        }

        public function cvDownload(){
            helper(['form']);
            $location = $this->request->getVar('cvLocation');
            if(!empty($location))
            {
                $path = WRITEPATH.'uploads/'.$location;
                $mime = mime_content_type($path);
    
                if(!is_readable($path))
                {
                    exit(json_encode("ERROR OCCURRED!"));
                }
    
                // Build the headers to push out the file properly.
                header('Pragma: public');     // required
                header('Expires: 0');         // no cache
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($path)).' GMT');
                header('Cache-Control: private',false);
                header('Content-Type: '.$mime);  // Add the mime type from Code igniter.
                header('Content-Disposition: attachment; filename="'.basename($path).'"');  // Add the file name
                header('Content-Transfer-Encoding: binary');
                header('Content-Length: '.filesize($path)); // provide file size
                header('Connection: close');
    
                readfile($path); //push it out
            }
        }
        
        public function DeleteCandidate() {
            helper(['form']);
            $candidateModel = new CandidateModel();
            $session = session();
            $candidate_id = $this->request->getVar('candidate_id3');
            $userID = $candidateModel->where('RECRUITER', $session->get('user_id'))->where('CANDIDATE_ID', $candidate_id)->findColumn('RECRUITER');
            if(count($userID) == 1)
            {
                if($candidateModel->delete($candidate_id))
                {
                    $success = 'Candidate '.$candidate_id.' has been deleted by '.session()->get('name').".";
                    $data = array("success", $success, $userID);
                    exit(json_encode($data));
                }
                else
                {
                    $error = $candidateModel->errors();
                    $data = array("error", $errors);
                    exit(json_encode($data));
                }
            }
            else
            {
                $error = "YOU ARE NOT AUTHORIZED TO MAKE THIS CHANGE.";
                $data = array("error", $errors);
                exit(json_encode($data));
            }
        }
    }

?>