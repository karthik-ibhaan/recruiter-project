<?php

    namespace App\Controllers;
    use CodeIgniter\Controller;
    use App\Models\CandidateModel;
    use App\Models\DemandModel;
    use App\Models\ClientModel;
    use CodeIgniter\Files\File;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
            $demandOptions->select('demand.DEMAND_ID, demand.JOB_TITLE, client.CLIENT_ID, client.CLIENT_NAME');
            $demandOptions->join('client', 'demand.client_id = client.client_id');
            $demandOptions = $demandOptions->get()->getResultArray();
            $builder = $db->table('candidates');
            $builder2 = $db->table('candidates');
            $table = $candidateModel->table;
            $recruitmentStatus['In Process'] = ['00_Sourcing','00_Profile Sent','00_CV sent to client','00_No Feedback from client'];
            $recruitmentStatus['Shortlisted'] = ['001_Pos. Hold','01_L1-In progress','01_R1-In progress','01_R2-In progress','01_F2F-In progress','01_HR-In progress','01_Reject-No Show','01_Reject-Cand. Dropped','02_Feedback Pending'];
            $recruitmentStatus['Selected'] = ['04_Selected','05_Offer Declined'];
            $recruitmentStatus['Rejected-Sc'] = ['00_Duplicate','00_Reject-Budget','00_Reject-HNP','00_Reject-No Responce','00_Reject-Screen','00_Reject-Fake','001_Pos. closed','001_Pos. Modified'];
            $recruitmentStatus['Rejected-Skill'] = ['03_Reject-Skill'];
            $recruitmentStatus['Selected'] = ['04_Selected','05_Offer Declined'];

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
            // $sqlquery = 'SELECT demand.DEMAND_ID, CLIENT.CLIENT_ID, CLIENT.CLIENT_NAME, demand.JOB_TITLE, CANDIDATES.*, my_users.FULL_NAME FROM CANDIDATES JOIN demand ON demand.demand_id = candidates.demand_id JOIN client ON client.client_id = demand.client_id JOIN my_users ON my_users.user_id = candidates.RECRUITER WHERE candidates.recruiter = '.$session->get('user_id');
            // $query = $db->query($sqlquery);
            // $candidates = $query->getResultArray();
            // $fieldNames = $query->getFieldNames();
            $candidates = $candidateModel->orderBy('CANDIDATE_ID', 'DESC')->where('recruiter', $session->get('user_id'))->findAll();
            $fieldNames = $candidateModel->allowedFields;
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

            $fileData = file_get_contents($fileLocation);
            return $this->response->download($fileLocation,null)->setFileName($fileName);
            return redirect()->to('/candidates');
        }

        public function AddCandidate(){
            helper(['form']);
            $candidateModel = new CandidateModel();
            $session = session();
            $datetime = "";
            $client_id = $this->request->getVar('client');
            $id = str_replace("&", "", $client_id);
            $id = str_replace(" ", "", $id);
            $demand_id = $this->request->getVar((string) $id);
            $action = $this->request->getVar('action');
            $id = str_replace("&", "", $action);
            $id = str_replace(" ", "", $id);
            $status = $this->request->getVar((string) $id);
            $c_name = $this->request->getVar('candidate_name');
            $emailAdd = $this->request->getVar('emailAdd');
            $phno_1 = $this->request->getVar('phno_1');
            $phno_2 = $this->request->getVar('phno_2');
            $phno = array($phno_1, $phno_2);
            $location = $this->request->getVar('location');
            $org = $this->request->getVar('organisation');
            $experience = $this->request->getVar('experience');
            $cctc = $this->request->getVar('cctc');
            $ectc = $this->request->getVar('ectc');
            $NP = $this->request->getVar('NP');
            $plannedDOJ = "";
            $actualDOJ = "";
            $interviewDate = $this->request->getVar('interview-date');
            $interviewTime = $this->request->getVar('interview-time');
            if($interviewDate != "" && $interviewTime != "")
            {
            $datetime = date('Y-m-d H:i:s', strtotime("$interviewDate $interviewTime"));
            }
            if(strtolower($action) == "selected")
            {
                $plannedDOJ = $this->request->getVar('plannedDOJ');
                $actualDOJ = $this->request->getvar('actualDOJ');
            }
            $recruiter = session()->get('user_id');
                
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
            ];

            if($datetime != "")
            {
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

            $db = \Config\Database::connect();
            $builder = $db->table('candidates');

            if($builder->insert($saveData))
            {
                $session->setFlashdata('success','Update of Candidate '.$c_name.' successful.');
                return redirect()->to('candidates');
            }
            else
            {
                $session->setFlashdata('error','Update of Candidate '.$c_name.' unsuccessful. Error occured.');
                return redirect()->to('candidates');
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

        public function EditCandidate(){
            helper(['form']);
            $session = session();
            $candidateID = $this->request->getVar('candidateID2');
            $demand2 = $this->request->getVar('demand2');
            $action = $this->request->getVar('action2');
            $id = str_replace("&", "", $action);
            $id = str_replace(" ", "", $id);
            $status = $this->request->getVar((string) $id);
            $c_name = $this->request->getVar('candidate_name2');
            $emailAdd = $this->request->getVar('emailAdd2');
            $phno_1 = $this->request->getVar('edit_phno_1');
            $phno_2 = $this->request->getVar('edit_phno_2');
            $phno = array($phno_1, $phno_2);
            $location = $this->request->getVar('location2');
            $org = $this->request->getVar('organisation2');
            $experience = $this->request->getVar('experience2');
            $cctc = $this->request->getVar('cctc2');
            $ectc = $this->request->getVar('ectc2');
            $NP = $this->request->getVar('NP2');
            $plannedDOJ = "";
            $actualDOJ = "";
            $interviewDate = $this->request->getVar('interview-date2');
            $interviewTime = $this->request->getVar('interview-time2');
            if($interviewDate != "" && $interviewTime != "")
            {
            $datetime = date('Y-m-d H:i:s', strtotime("$interviewDate $interviewTime"));
            }
            if(strtolower($action) == "selected")
            {
                $plannedDOJ = $this->request->getVar('plannedDOJ2');
                $actualDOJ = $this->request->getvar('actualDOJ2');
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
                'INTERVIEW_DATE' => $datetime,
                'RECRUITER' => $recruiter,
                'PLANNED_DOJ' => $plannedDOJ,
                'ACTUAL_DOJ' => $actualDOJ
            ];
            print_r($saveData);

            $db = \Config\Database::connect();
            $builder = $db->table('candidates');
            $builder->set($saveData);
            $builder->where('candidate_id',$candidateID);
            if($builder->update())
            {
                $session->setFlashdata('success','Update of Candidate '.$c_name.' successful.');
                return redirect()->to('candidates');
            }
            else
            {
                $session->setFlashdata('error','Update of Candidate '.$c_name.' unsuccessful. Error occured.');
                return redirect()->to('candidates');
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
    }

?>