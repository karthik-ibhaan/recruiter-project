<?php

    namespace App\Controllers;
    use CodeIgniter\Controller;
    use App\Models\DemandModel;
    use App\Models\ClientModel;
    use CodeIgniter\Files\File;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use App\Libraries\RecruitmentStatuses;

    class DemandsView extends Controller
    {
        public function initialise()
        {
            $action = '';
            $header = true;
            $clientModel = new ClientModel();
            $demandModel = new DemandModel();
            $recruitmentStatuses = new RecruitmentStatuses;
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
            $builder = $db->table('demand');
            $builder->select('client.CLIENT_NAME, demand.*');
            $builder->join('client', 'demand.client_id = client.client_id');
            $query = $builder->get();
            $demands = $query->getResultArray();
            $fieldNames = $query->getFieldNames();
            array_push($fieldNames, "PROFILES_SHARED");
            array_push($fieldNames, "INTERVIEWS_SCHEDULED");
            array_push($fieldNames, "CANDIDATES_SHORTLISTED");
            array_push($fieldNames, "CANDIDATES_SELECTED");
            array_push($fieldNames, "CANDIDATES_REJECTED");
            $fieldNames = array_diff($fieldNames, ["JD_LOCATION","JOB_DESCRIPTION", "RECRUITER", "FULL_NAME", "SUBMISSION_DATE"]);
            foreach($demands as $keys=>$values)
            {
                if($values["DEMAND_ID"])
                {
                    $details = $recruitmentStatuses->recruitmentStatusesDemands($values["DEMAND_ID"]);
                    $demands[$keys]["PROFILES_SHARED"] = $details[0];
                    $demands[$keys]["INTERVIEWS_SCHEDULED"] = $details[1];
                    $demands[$keys]["CANDIDATES_SELECTED"] = $details[2];
                    $demands[$keys]["CANDIDATES_REJECTED"] = $details[3];
                    $demands[$keys]["CANDIDATES_SHORTLISTED"] = $details[4];
                }
            }
            // $fieldNames = array_diff( $fieldNames, ["PRIMARY_SKILL", "SECONDARY_SKILL", "JOB_DESCRIPTION"]);
            $clients = $clientModel->orderBy('CLIENT_ID', 'DESC')->findAll();
            $data = [];
            $data['fieldNames'] = $fieldNames;
            $data['options'] = $options;
            $data['demands'] = $demands;
            $data['clients'] = $clients;
            // $data['customers'] = $customers;
            $data['industry'] = $industrySegment;
            $data['domain'] = $domain;
            $data['priority'] = $priority;
            $data['complexity'] = $complexity;
            $data['status'] = $status;
            $data['location'] = $location;
            return $data;
        }

        public function index()
        {
            helper(['form']);
            $data = DemandsView::initialise();
            echo view('demandsview', $data);
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
    }
?>