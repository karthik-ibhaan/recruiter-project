<?php

    namespace App\Controllers;
    use CodeIgniter\Controller;
    use App\Models\DemandModel;
    use App\Models\ClientModel;
    use CodeIgniter\Files\File;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use App\Libraries\RecruitmentStatuses;

    class AssignedDemands extends Controller
    {

        public function initialise()
        {
            $action = '';
            $header = true;
            $session = session();
            $clientModel = new ClientModel();
            $demandModel = new DemandModel();
            $industrySegment = ['Aero Engine','Aerostructure','Automotive','Avionics','Consumer Durables','Defence','Industrial Automation','Information Technology','Locomotive','Marine','Medical Devices','Renewable Energy','Survelience','Telecom'];
            $status = ['Open','Closed','Hold'];
            $domain['E&E Development'] = ['Analysis','Circuit Simulation','Embed HW +DDS +MSD','EMI / EMC','Firmware','FPGA','FPGA / RTL Design','Hardware','Layout','Mechanical','Middleware','PCB Layout design','Power Management','Power Supply','RF','Signal & Power Integrity','Software'];
            $domain['E&E Manufacturing'] = ['Box build', 'PCBA - Production', 'PCBA - Proto', 'Power Chords', 'Solar Solutions', 'Transformers', 'Turnkey Mfg', 'UPS', 'Wire Harness'];
            $domain['Information Technology'] = ['Back-End Development', 'Blockchain Development', 'Data Analysis','Front-End Development', 'Java Development', 'Machine Learning', 'Mobile App Development', 'Python Development', 'Testing'];
            $domain['Mechanical'] = ['Cost Engineering', 'Design', 'Manufacturing', 'Quality Assurance', 'Quality Management', 'Supply Chain Management'];

            $recruitmentStatuses = new RecruitmentStatuses;
            $recruitmentStatus = $recruitmentStatuses->recruitmentStatuses();
            
            $priority = ['High'];
            $complexity = ['low', 'medium', 'high'];
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

            sort($location);
            // $customers = $customerModel->orderBy('CUSTOMER_ID', 'DESC')->findAll();

            // $builder = $db->table('demand');
            // $builder->select('*');
            // $builder->join('client', 'client.CLIENT_ID = demand.client_id');
            // $demands = $builder->get()->getResult();
            $db = \Config\Database::connect();
            $session = session();
            $options = [];
            $assignments = $db->table('assign');
            $assignments->select('assign.ASSIGN_DEMAND_ID');
            $assignments->where('assign.ASSIGNEE_ID', $session->get('user_id'));
            $assignments->where('assign.ASSIGNMENT_DATE', date("Y-m-d", strtotime("today")));
            $assignedArray = $assignments->get()->getResultArray();
            $assignedValues = array();
            foreach($assignedArray as $keys=>$data)
            {
                $assignedValues[] = $data['ASSIGN_DEMAND_ID'];
            }
            $builder = $db->table('demand');
            $builder->select('client.CLIENT_NAME, demand.*');
            $builder->distinct();
            $builder->join('client', 'demand.client_id = client.client_id');
            if($session->get('level')<"3" && $session->get('level')>="1")
            {
                $builder->where('demand.IBHAAN_SPOC = ', $session->get('name'));
                if(count($assignedValues)>=1)
                $builder->orWhereIn('demand.DEMAND_ID', $assignedValues);
            }
            else
            {
                if(count($assignedValues)>= 1)
                $builder->whereIn('demand.DEMAND_ID', $assignedValues);
                else
                $builder->whereIn('demand.DEMAND_ID', [0]);
            }

            $query = $builder->get();
            $demands = $query->getResultArray();
            $fieldNames = $query->getFieldNames();
            $fieldNames = array_diff($fieldNames, ["JD_LOCATION","JOB_DESCRIPTION"]);

            $clients = $clientModel->orderBy('CLIENT_ID', 'DESC')->findAll();
            $demandOptions = $db->table('demand');
            $demandOptions->select('demand.DEMAND_ID, demand.JOB_TITLE, client.CLIENT_ID, client.CLIENT_NAME');
            $demandOptions->distinct();
            $demandOptions->where('demand.demand_status','Open');
            $demandOptions->where('assign.ASSIGNEE_ID', $session->get('user_id'));
            if($session->get('level')<3 && $session->get('level')>=1)
            {
                $builder->orWhere('demand.IBHAAN_SPOC = ', $session->get('name'));
            }
            $demandOptions->where('assign.ASSIGNMENT_DATE', date("Y-m-d"));
            if($session->get('level')<3 && $session->get('level')>=1)
            {
                $demandOptions->orWhere('demand.IBHAAN_SPOC = ', $session->get('name'));
            }
            $demandOptions->join('assign', 'assign.ASSIGN_DEMAND_ID = demand.DEMAND_ID');
            $demandOptions->join('client', 'demand.client_id = client.client_id');
            $demandOptions = $demandOptions->get()->getResultArray();
            $data = [];
            $data['fieldNames'] = $fieldNames;
            $data['status'] = $recruitmentStatus;
            $data['options'] = $options;
            $data['demands'] = $demands;
            $data['clients'] = $clients;
            $data['demandOptions'] = $demandOptions;
            $data['location'] = $location;
            // $data['customers'] = $customers;
            $data['industry'] = $industrySegment;
            $data['domain'] = $domain;
            $data['priority'] = $priority;
            $data['complexity'] = $complexity;
            $data['status'] = $recruitmentStatus;
            $data['location'] = $location;
            return $data;
        }

        public function index()
        {
            helper(['form']);
            $data = AssignedDemands::initialise();
            echo view('assigneddemands', $data);
        }

        public function GetClients()
        {
            helper(['form']);
            $db = \Config\Database::connect();
            $session = session();
            $demandsQuery = $db->table('demand');
            $demandsQuery->select('demand.CLIENT_ID, client.CLIENT_NAME');
            $demandsQuery->distinct();
            $demandsQuery->join('client', 'demand.client_id = client.client_id');
            $demandsQuery->where('DEMAND_STATUS', 'Open');
            $demandsQuery->whereIn('PRIORITY', ['New', 'High']);
            if($session->get('level') == "2" || $session->get('level') == "1")
            {
                $demandsQuery->where('demand.IBHAAN_SPOC !=', $session->get('name'));
            }
            $clients = $demandsQuery->get()->getResultArray();
            exit(json_encode($clients));
        }

        public function GetDemands()
        {
            helper(['form']);
            $session = session();
            $db = \Config\Database::connect();
            $demandsQuery = $db->table('demand');
            $demandsQuery->select('DEMAND_ID, JOB_TITLE, JD_ID');
            $demandsQuery->where('CLIENT_ID', $this->request->getVar('client_id'));
            $demandsQuery->where('DEMAND_STATUS', 'Open');
            $demandsQuery->whereIn('PRIORITY', ['New', 'High']);
            if($session->get('level') == "2" || $session->get('level') == "1")
            {
                $demandsQuery->where('demand.IBHAAN_SPOC !=', $session->get('name'));
            }
            $demands = $demandsQuery->get()->getResultArray();
            exit(json_encode($demands));
        }

        public function SelfAssignment()
        {
            helper(['form']);
            $session = session();
            $db = \Config\Database::connect();
            $count = 0;
            $assignDemand = $this->request->getVar('assign_demand');
            $builder = $db->table('assign');
            $builder->select('assign.ASSIGNEE_ID, assign.ASSIGNMENT_DATE, assign.ASSIGN_DEMAND_ID');
            $builder->where('assign.ASSIGN_DEMAND_ID = ', $assignDemand);
            $builder->where('assign.ASSIGNMENT_DATE = ', date('Y-m-d'));
            $builder->where('assign.ASSIGNEE_ID = ', $session->get('user_id'));
            $countResults = $builder->countAllResults();
            if($countResults <= 0)
            {
                $saveData = [
                    "ASSIGN_DEMAND_ID" => $assignDemand,
                    "ASSIGNER_ID" => $session->get('user_id'),
                    "ASSIGNEE_ID" => $session->get('user_id'),
                    "ASSIGNMENT_DATE" => date("Y-m-d")
                ];
                $builder = $db->table('assign');
                if($builder->insert($saveData))
                {
                    $builder = $db->table('demand');
                    $builder->select('client.CLIENT_NAME, demand.*');
                    $builder->distinct();
                    $builder->join('client', 'demand.client_id = client.client_id');
                    $builder->where('demand.DEMAND_ID', $assignDemand);
                    $demands = $builder->get()->getResultArray();
                    $array = ["success", "Demand ".$assignDemand." Assigned For Recruiter ID(s): ".$session->get('user_id'), $demands];
                    exit(json_encode($array));
                }
                else
                {
                    $array = ["error","ERROR OCCURED:".$db->errors()];
                    exit(json_encode($array));
                }
            }
            else
            {
                $array = ['error', 'Demand Is Already Assigned.'];
                exit(json_encode($array));                
            }
        }
        // public function GetDetails()
        // {
        //     helper(['form']);
        //     $db = \Config\Database::connect();
        //     $demandModel = new DemandModel();
        //     $demandID = $this->request->getVar('demand_id');
        //     $details = $demandModel->select(['PRIMARY_SKILL', 'SECONDARY_SKILL', 'JOB_DESCRIPTION'])->where('demand_id', $demandID)->first();
        //     exit(json_encode($details));
        // }

        public function GetInterviewerData()
        {
            helper(['form']);
            $db = \Config\Database::connect();
            $interviewerQuery = $db->table('interview_consultants');
            $interviewerQuery->select('INTERVIEWER_ID, INTERVIEWER_NAME');
            $interviewers = $interviewerQuery->get()->getResultArray();
            $usersQuery = $db->table('my_users');
            $usersQuery->select('my_users.USER_ID, my_users.FULL_NAME');
            $usersQuery->where('my_users.LEVEL=',"1");
            $users = $usersQuery->get()->getResultArray();
            $array = array($users, $interviewers);
            exit(json_encode($array));
        }

        public function ScheduleInterview()
        {
            helper(['form']);
            $db = \Config\Database::connect();
            $candidateID = $this->request->getVar('scheduleCandidateID');
            $scheduleInterviewDate = $this->request->getVar('scheduleInterviewDate');
            $scheduleInterviewTime = $this->request->getVar('scheduleInterviewTime');
            $scheduleInterviewer = $this->request->getVar('scheduleInterviewer');
            $scheduleApprover = $this->request->getVar('scheduleApprover');
            $datetime = date('Y-m-d H:i:s', strtotime("$scheduleInterviewDate $scheduleInterviewTime"));
            $saveData = [
                'INTERVIEWER_ID' => $scheduleInterviewer,
                'CANDIDATE_ID' => $candidateID,
                'INTERVIEW_DATETIME' => $datetime,
                'INTERVIEW_APPROVAL' => 0,
                'INTERVIEW_APPROVER' => $scheduleApprover,
                // 'INTERVIEW_SELECTION' => 0,
                // 'SKILL_ANALYSIS' => ""
            ];
            $interview = $db->table('ig_interview');
            if($interview->insert($saveData))
            {
                $candidate = $db->table('candidates');
                $candidate->set(['RECRUITMENT_STATUS' => '04. IG-Interview in Progress']);
                $candidate->where('CANDIDATE_ID', $candidateID);
                if($candidate->update())
                {
                    $array = ['successs', 'Interview Successfully Scheduled.'];
                    exit(json_encode($array));    
                }
                else
                {
                    $array = ['error', 'ERROR OCCURRED.'];
                    exit(json_encode($array));
                }
            }
            else
            {
                $array = ['error', 'ERROR OCCURRED'];
                exit(json_encode($array));    
            }
        }
        public function DemandsData()
        {
            helper(['form']);
            $db = \Config\Database::connect();
            $builder = $db->table('demand');
            $builder->select('client.CLIENT_NAME, demand.DEMAND_ID, demand.JOB_TITLE');
            $builder->join('client', 'demand.client_id = client.client_id');
            $builder->where('demand.IBHAAN_SPOC = ',session()->get('name'));
            $query = $builder->get();
            $demands = $query->getResultArray();
            $builder2 = $db->table('my_users');
            $builder2->select('my_users.USER_ID, my_users.FULL_NAME');
            $builder2->where('level>',1);
            $query2 = $builder2->get();
            $users = $query2->getResultArray();
            $data = array($demands, $users);
            exit(json_encode($data));
        }

        public function CandidateDetails()
        {
            $db = \Config\Database::connect();
            $session = session();
            $demandID = $this->request->getVar("demand_id");
            $subDate = $this->request->getVar("submission_date");
            $subDateTime = date("Y-m-d 23:59:59", strtotime("$subDate"));
            $sqlquery = $db->table('candidates');
            $sqlquery->select('*');
            $sqlquery->where('candidates.DEMAND_ID', $demandID);
            $sqlquery->where('candidates.RECRUITER', $session->get('user_id'));
            $sqlquery->where('candidates.SUBMISSION_DATE>=', date("Y-m-d 00:00:00", strtotime("today")));
            $sqlquery->where('candidates.SUBMISSION_DATE<', date("Y-m-d 00:00:00", strtotime("tomorrow")));
            $query = $sqlquery->get();
            $candidates = $query->getResultArray();
            $fieldNames = $query->getFieldNames();
            $array = array($candidates, $fieldNames);
            exit(json_encode($array));
        }

        public function CandidateDetails2()
        {
            $db = \Config\Database::connect();
            $session = session();
            $demandID = $this->request->getVar("demand_id");
            $subDate = $this->request->getVar("submission_date");
            $subDateTime = date("Y-m-d 23:59:59", strtotime("$subDate"));
            $sqlquery = $db->table('candidates');
            $sqlquery->select('*');
            $sqlquery->where('candidates.DEMAND_ID', $demandID);
            $sqlquery->where('candidates.RECRUITER', $session->get('user_id'));
            $sqlquery->where('candidates.SUBMISSION_DATE<', $subDateTime);
            $query = $sqlquery->get();
            $candidates = $query->getResultArray();
            $fieldNames = $query->getFieldNames();
            $array = array($candidates, $fieldNames);
            exit(json_encode($array));
        }

        public function CVDownloads()
        {
            helper(['form']);
            helper('filesystem');

            $candidateIDs = $this->request->getVar('candidate_ids');
            $db = \Config\Database::connect();
            $zipname = WRITEPATH.'uploads/'.time().'candidateExports.zip';
            $zip = new \ZipArchive();
            $res = $zip->open($zipname, \ZipArchive::CREATE);
            $builder = $db->table('candidates');
            $builder->select('*');
            $builder->whereIn('CANDIDATE_ID', $candidateIDs);
            $candidates = $builder->get()->getResultArray();
            $fieldNames = $builder->get()->getFieldNames();
            foreach($candidates as $keys2=>$data2)
            {
                $demand = $data2['DEMAND_ID'];
                if(!is_null($data2['CV_LOCATION']) && isset($data2['CV_LOCATION']))
                {
                    $path = WRITEPATH.'uploads/'.$data2['CV_LOCATION'];
                    if(is_file($path))
                    {
                        $zip->addFile($path, basename($path));
                    }
                }
            }
            $builder = $db->table('demand');
            $builder->select('client.CLIENT_NAME, demand.*, my_users.FULL_NAME');
            $builder->join('client', 'demand.client_id = client.client_id');
            $builder->join('my_users','demand.recruiter = my_users.user_id');
            $builder->where('demand.DEMAND_ID = ', $demand);
            $query = $builder->get();
            $demands = $query->getResultArray();
            $fieldNames2 = $query->getFieldNames();
            foreach($demands as $keys=>$data2)
            {
                if(!is_null($data2['JD_LOCATION']) && isset($data2['JD_LOCATION']))
                {
                    $path2 = WRITEPATH.'uploads/'.$data2['JD_LOCATION'];
                    if(is_file($path2))
                    {
                        $zip->addFile($path2, basename($path2));
                    }
                }
            }
            $session = session();

            $header=true;
            $count = 0;

            $fileName = 'candidates - '.(string) $demand.'.xlsx';
            $spreadsheet = new Spreadsheet();
            $worksheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, "Demand");
            $spreadsheet->addSheet($worksheet, 0);
            $sheet = $spreadsheet->getSheet(0);

            $col = "A";
            $row = "1";
            $col2 = "B";
            $sheet->setCellValue("$col$row", ucwords("DEMAND DATA"));
            $sheet->mergeCells("$col$row:$col2$row");
            $col = "A";
            $row = "3";
            
            foreach($fieldNames2 as $keys=>$values){
                $sheet->setCellValue("$col$row", ucwords($values));
                $col++;
            }

            $col = "A";
            $row = "4";
            foreach($demands as $keys=>$data){
                foreach($fieldNames2 as $keys=>$value){
                    $sheet->setCellValue("$col$row", $data[$value]);
                    $col++;
                }
                $col="A";
                $row++;
            }
            $worksheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, "Candidates");
            $spreadsheet->addSheet($worksheet, 0);
            $sheet = $spreadsheet->getSheet(0);
            $col = "A";
            $row = "1";
            $col2 = "B";
            $sheet->setCellValue("$col$row", ucwords("CANDIDATES DATA"));
            $sheet->mergeCells("$col$row:$col2$row");
            $col = "A";
            $row = "3";
            foreach($fieldNames as $keys=>$values){
                $sheet->setCellValue("$col$row", ucwords($values));
                $col++;
            }
            $col = "A";
            $row = "4";
            foreach($candidates as $keys=>$data){
                foreach($fieldNames as $keys=>$value){
                    $sheet->setCellValue("$col$row", $data[$value]);
                    $col++;
                }
                $col="A";
                $row++;
            }
            $worksheetindex = $spreadsheet->getIndex($spreadsheet->getSheetByName('Worksheet'));
            $spreadsheet->removeSheetByIndex($worksheetindex);

            $writer = new Xlsx($spreadsheet);
            $writer->save($fileName);
            $fileLocation = ROOTPATH."/public/".$fileName;
            $mime = mime_content_type($fileLocation);

            if(!is_readable($fileLocation))
            {
                exit(json_encode("ERROR OCCURRED!"));
            }

            $zip->addFile($fileLocation, $fileName);

            $zip->close();
            // exit(json_encode($ret));
            header('Content-Type: application/zip');
            header('Content-disposition: attachment; filename='.$zipname);
            header('Content-Length: ' . filesize($zipname));
            readfile($zipname);

            unlink($fileLocation); //Delete Temporary File

            unlink($zipname); //Delete Temporary Zip
        }

        public function CVDownloads2()
        {
            helper(['form']);
            helper('filesystem');

            $db = \Config\Database::connect();
            $zipname = WRITEPATH.'uploads/'.time().'candidateExports.zip';
            $zip = new \ZipArchive();
            $res = $zip->open($zipname, \ZipArchive::CREATE);
            $demand = $this->request->getVar('demand');
            $builder = $db->table('candidates');
            $builder->select('candidates.*, my_users.FULL_NAME');
            $builder->join('my_users', 'my_users.USER_ID = candidates.RECRUITER');
            $builder->where('candidates.DEMAND_ID = ', $demand);
            if(session()->get('level') == 3)
            {
                $builder->where('candidates.RECRUITER = ', session()->get('user_id'));
            }
            $candidates = $builder->get()->getResultArray();
            $fieldNames = $builder->get()->getFieldNames();        
            foreach($candidates as $keys2=>$data2)
            {
                if(!is_null($data2['CV_LOCATION']) && isset($data2['CV_LOCATION']))
                {
                    $path = WRITEPATH.'uploads/'.$data2['CV_LOCATION'];
                    if(is_file($path))
                    {
                        $zip->addFile($path, basename($path));
                    }
                }
            }
            $builder = $db->table('demand');
            $builder->select('client.CLIENT_NAME, demand.*, my_users.FULL_NAME');
            $builder->join('client', 'demand.client_id = client.client_id');
            $builder->join('my_users','demand.recruiter = my_users.user_id');
            $builder->where('demand.DEMAND_ID = ', $demand);
            $query = $builder->get();
            $demands = $query->getResultArray();
            $fieldNames2 = $query->getFieldNames();
            foreach($demands as $keys=>$data2)
            {
                if(!is_null($data2['JD_LOCATION']) && isset($data2['JD_LOCATION']))
                {
                    $path2 = WRITEPATH.'uploads/'.$data2['JD_LOCATION'];
                    if(is_file($path2))
                    {
                        $zip->addFile($path2, basename($path2));
                    }
                }
            }
            $session = session();

            $header=true;
            $count = 0;

            $fileName = 'candidates - '.(string) $demand.'.xlsx';
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $col = "A";
            $row = "1";
            $col2 = "B";
            $sheet->setCellValue("$col$row", ucwords("CANDIDATES DATA"));
            $sheet->mergeCells("$col$row:$col2$row");
            $col = "A";
            $row = "3";
            
            foreach($fieldNames2 as $keys=>$values){
                $sheet->setCellValue("$col$row", ucwords($values));
                $col++;
            }

            $col = "A";
            $row = "4";
            foreach($demands as $keys=>$data){
                foreach($fieldNames2 as $keys=>$value){
                    $sheet->setCellValue("$col$row", $data[$value]);
                    $col++;
                }
                $col="A";
                $row++;
            }
            $col = "A";
            $row = "6";
            foreach($fieldNames as $keys=>$values){
                $sheet->setCellValue("$col$row", ucwords($values));
                $col++;
            }
            $col = "A";
            $row = "7";
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

            $zip->addFile($fileLocation, $fileName);

            $zip->close();
            // exit(json_encode($ret));
            header('Content-Type: application/zip');
            header('Content-disposition: attachment; filename='.$zipname);
            header('Content-Length: ' . filesize($zipname));
            readfile($zipname);

            unlink($fileLocation); //Delete Temporary File

            unlink($zipname); //Delete Temporary Zip
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

        public function PreviousDemands()
        {
            $db = \Config\Database::connect();
            $demandQuery = $db->table('candidates');
            $demandQuery->distinct();
            $demandQuery->select('candidates.DEMAND_ID');
            $demandQuery->where('candidates.SUBMISSION_DATE<', date("Y-m-d 23:59:59", strtotime("yesterday")));
            $demandQuery->where('candidates.RECRUITER', session()->get('user_id'));
            $demandQuery = $demandQuery->get();
            $demandIDsArray = $demandQuery->getResultArray();
            foreach ($demandIDsArray as $item) {
                $demandIDs[] = $item['DEMAND_ID'];
            }
            $builder = $db->table('demand');
            $builder->select('client.CLIENT_NAME, demand.*');
            $builder->distinct();
            $builder->join('client', 'demand.client_id = client.client_id');
            $builder->whereIn('demand.demand_id', $demandIDs);
            $query = $builder->get();
            $demands = $query->getResultArray();
            $fieldNames = $query->getFieldNames();
            $fieldNames = array_diff($fieldNames, ["JD_LOCATION","JOB_DESCRIPTION"]);
            $array = array($demands, $fieldNames);
            exit(json_encode($array));
        }
    }

?>