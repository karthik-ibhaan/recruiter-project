<?php

    namespace App\Controllers;
    use CodeIgniter\Controller;
    use App\Models\DemandModel;
    use App\Models\ClientModel;
    use CodeIgniter\Files\File;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use App\Libraries\RecruitmentStatuses;

    class CoordinatorDemands extends Controller
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
            $priority = ['High'];
            $complexity = ['low', 'medium', 'high'];
            $location = [];
            $path = WRITEPATH . 'uploads/' . 'locations.csv';
            $handle = fopen($path, "r");
            $recruitmentStatuses = new RecruitmentStatuses;
            $recruitmentStatus = $recruitmentStatuses->recruitmentStatuses();
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
            $options = [];
            $arrays = [];
            $builder = $db->table('demand');
            $builder->select('client.CLIENT_ID, client.CLIENT_NAME, demand.*');
            $builder->join('client', 'demand.client_id = client.client_id');
            $builder->where('demand.IBHAAN_SPOC = ',$session->get('name'));
            $query = $builder->get();
            $demands = $query->getResultArray();
            $fieldNames = $query->getFieldNames();
            $fieldNames = array_diff($fieldNames, ["JD_LOCATION","JOB_DESCRIPTION"]);
            foreach($demands as $keys=>$data)
            {
                array_push($arrays, $data["DEMAND_ID"]);
            }
            $coordinatorDemands = array_merge($demands);
            $clients = $clientModel->orderBy('CLIENT_ID', 'DESC')->findAll();
            foreach($fieldNames as $keys=>$values)
            {
                if($values == "CLIENT_NAME")
                {
                    $sql = 'SELECT DISTINCT '.$values.' FROM '.$clientModel->table.';';
                    $query = $db->query($sql);
                    array_push($options, $query->getResultArray());
                }
                else
                {
                    $sql = 'SELECT DISTINCT '.$values.' FROM '.$demandModel->table.';';
                    $query = $db->query($sql);
                    array_push($options, $query->getResultArray());
                }
            }
            $usersQuery = $db->table('my_users');
            $usersQuery->select('my_users.USER_ID, my_users.FULL_NAME');
            $usersQuery->where('level>',1);
            $usersQuery->where('user_id!=', session()->get('user_id'));
            $usersQuery = $usersQuery->get();
            $users = $usersQuery->getResultArray();

            $data = [];
            $data['users'] = $users;
            $data['fieldNames'] = $fieldNames;
            $data['options'] = $options;
            $data['demands'] = $demands;
            $data['coordinatorDemands'] = $coordinatorDemands;
            $data['clients'] = $clients;
            // $data['customers'] = $customers;
            $data['status'] = $recruitmentStatus;
            $data['industry'] = $industrySegment;
            $data['domain'] = $domain;
            $data['priority'] = $priority;
            $data['complexity'] = $complexity;
            $data['location'] = $location;
            return $data;
        }

        public function index()
        {
            helper(['form']);
            $data = CoordinatorDemands::initialise();
            echo view('coordinatordemands', $data);
        }

        // public function GetDetails()
        // {
        //     helper(['form']);
        //     $db = \Config\Database::connect();
        //     $demandModel = new DemandModel();
        //     $recruitmentStatus = array();
        //     $recruitmentStatus['Shortlisted'] = ['001_Pos. Hold','01_L1-In progress','01_R1-In progress','01_R2-In progress','01_F2F-In progress','01_HR-In progress','01_Reject-No Show','01_Reject-Cand. Dropped','02_Feedback Pending'];
        //     $recruitmentStatus['Selected'] = ['S1. Offer Pending','S2. Documents Uploaded','S2. Offer Accepted','S2. Offer Released','S5. Joined','S6. Candidate Dropped Offer','S6. Client Rejected'
        //     ,'S6. Offer Declined','S6. Quest Duplicate'];
        //     $recruitmentStatus['Rejected'] = ['00_Duplicate','00_Reject-Budget','00_Reject-HNP','00_Reject-No Responce','00_Reject-Screen','00_Reject-Fake','001_Pos. closed','001_Pos. Modified','03_Reject-Skill'];
        //     $demandID = $this->request->getVar('demand_id');
        //     $details = $demandModel->select(['PRIMARY_SKILL', 'SECONDARY_SKILL', 'JOB_DESCRIPTION'])->where('demand_id', $demandID)->first();
        //     exit(json_encode($details));
        // }
        public function DemandsData()
        {
            helper(['form']);
            $db = \Config\Database::connect();
            $data = array($demands, $users);
            exit(json_encode($data));
        }

        public function CandidateDetails()
        {
            $db = \Config\Database::connect();
            $session = session();
            $demandID = $this->request->getVar("demand_id");
            $sqlquery = 'SELECT candidates.*, my_users.FULL_NAME
            FROM candidates 
            JOIN my_users on my_users.USER_ID = candidates.RECRUITER 
            WHERE candidates.DEMAND_ID = ' .$demandID;
            $query = $db->query($sqlquery);
            $candidates = $query->getResultArray();
            $fieldNames = $query->getFieldNames();
            $array = array($candidates, $fieldNames);
            exit(json_encode($array));
        }

        //Function to Display Assigned Demands in a Table
        public function AssignedToday()
        {
            helper(['form']);
            $db = \Config\Database::connect();
            $builder = $db->table('assign');
            $builder->select('client.CLIENT_NAME, assign.ASSIGN_DEMAND_ID, demand.JOB_TITLE, my_users.FULL_NAME');
            $builder->join('demand', 'demand.DEMAND_ID = assign.ASSIGN_DEMAND_ID');
            $builder->join('client', 'client.CLIENT_ID = demand.CLIENT_ID');
            $builder->join('my_users', 'my_users.USER_ID = assign.ASSIGNEE_ID');
            $builder->where('assign.ASSIGNMENT_DATE', date('Y-m-d'));
            $demandsArray = $builder->get()->getResultArray();
            $fieldNames = $builder->get()->getFieldNames();
            $array = array($demandsArray, $fieldNames);
            echo json_encode($array);
        }

        public function AssignMultiple()
        {
            helper(['form']);
            $session = session();
            $db = \Config\Database::connect();
            $count = 0;
            $assignDemand = $this->request->getVar('demandID');
            $recruiterMultiple = $this->request->getVar("recruiterMultipleSelect");
            foreach($recruiterMultiple as $keys=>$data)
            {
                $builder = $db->table('assign');
                $builder->select('assign.ASSIGNEE_ID, assign.ASSIGNMENT_DATE, assign.ASSIGN_DEMAND_ID');
                $builder->where('assign.ASSIGN_DEMAND_ID = ', $assignDemand);
                $builder->where('assign.ASSIGNMENT_DATE = ', date('Y-m-d'));
                $builder->where('assign.ASSIGNEE_ID = ', $data);
                $countResults = $builder->countAllResults();
                if($countResults <= 0)
                {
                    $saveData = [
                        "ASSIGN_DEMAND_ID" => $assignDemand,
                        "ASSIGNER_ID" => $session->get('user_id'),
                        "ASSIGNEE_ID" => $data,
                        "ASSIGNMENT_DATE" => date("Y-m-d")
                    ];
                    $builder = $db->table('assign');
                    if($builder->insert($saveData))
                    {
                        $count = $count+1;
                    }
                }
                else
                {
                    $count = $count+1;
                }
            }
            if($count == count($recruiterMultiple))
            {
                $recruiterString = implode(',', $recruiterMultiple);
                $array = ["success", "Demand ".$assignDemand." Assigned For Recruiter ID(s): ".$recruiterString];
                exit(json_encode($array));
            }
            else
            {
                $array = ["error","ERROR OCCURED:".$db->errors()];
                exit(json_encode($array));
            }
        }
    }

?>