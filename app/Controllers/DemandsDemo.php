<?php

    namespace App\Controllers;
    use CodeIgniter\Controller;
    use App\Models\DemandModel;
    use App\Models\ClientModel;
    use CodeIgniter\Files\File;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    class Demands extends Controller
    {

        public function initialise()
        {
            $action = '';
            $header = true;
            $clientModel = new ClientModel();
            $demandModel = new DemandModel();
            $industrySegment = ['Aero Engine','Aerostructure','Automotive','Avionics','Consumer Durables','Defence','Industrial Automation','Information Technology','Locomotive','Marine','Medical Devices','Renewable Energy','Survelience','Telecom'];
            $status = ['Open','Closed','Hold'];
            $domain['E&E Development'] = ['Analysis','Circuit Simulation','Embed HW +DDS +MSD','EMI / EMC','Firmware','FPGA','FPGA / RTL Design','Hardware','Layout','Mechanical','Middleware','PCB Layout design','Power Management','Power Supply','RF','Signal & Power Integrity','Software'];
            $domain['E&E Manufacturing'] = ['Box build', 'PCBA - Production', 'PCBA - Proto', 'Power Chords', 'Solar Solutions', 'Transformers', 'Turnkey Mfg', 'UPS', 'Wire Harness'];
            $domain['Information Technology'] = ['Testing', 'Development','Analysis','Support'];
            $domain['Mechanical'] = ['Cost Engineering', 'Design', 'Manufacturing', 'Quality Assurance', 'Quality Management', 'Supply Chain Management'];
            $priority = ['High'];
            $complexity = ['low', 'medium', 'high'];
            $recruitmentStatus = array();
            $recruitmentStatus['Shortlisted'] = ['001_Pos. Hold','01_L1-In progress','01_R1-In progress','01_R2-In progress','01_F2F-In progress','01_HR-In progress','01_Reject-No Show','01_Reject-Cand. Dropped','02_Feedback Pending'];
            $recruitmentStatus['Selected'] = ['S1. Offer Pending','S2. Documents Uploaded','S2. Offer Accepted','S2. Offer Released','S5. Joined','S6. Candidate Dropped Offer','S6. Client Rejected'
            ,'S6. Offer Declined','S6. Quest Duplicate'];
            $recruitmentStatus['Rejected'] = ['00_Duplicate','00_Reject-Budget','00_Reject-HNP','00_Reject-No Responce','00_Reject-Screen','00_Reject-Fake','001_Pos. closed','001_Pos. Modified','03_Reject-Skill'];
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
            $builder->select('client.CLIENT_NAME, demand.*, my_users.FULL_NAME');
            $builder->join('client', 'demand.client_id = client.client_id');
            $builder->join('my_users','demand.recruiter = my_users.user_id');
            $query = $builder->get();
            $demands = $query->getResultArray();
            $fieldNames = $query->getFieldNames();
            array_push($fieldNames, "PROFILES_SHARED");
            array_push($fieldNames, "INTERVIEWS_SCHEDULED");
            array_push($fieldNames, "CANDIDATES_SHORTLISTED");
            array_push($fieldNames, "CANDIDATES_SELECTED");
            array_push($fieldNames, "CANDIDATES_REJECTED");
            $fieldNames = array_diff($fieldNames, ["JD_LOCATION","JOB_DESCRIPTION"]);
            foreach($demands as $keys=>$values)
            {
                if($values["DEMAND_ID"])
                {
                    $builder = $db->table('candidates');
                    $builder->where('DEMAND_ID',$values["DEMAND_ID"]);
                    $profilesShared = $builder->countAllResults();
                    $builder2 = $db->table('candidates');
                    $builder2->where('DEMAND_ID',$values["DEMAND_ID"]);
                    $builder2->where('interview_date!=',"");
                    $interviewsScheduled = $builder2->countAllResults();
                    $builder3 = $db->table('candidates');
                    $builder3->where('DEMAND_ID',$values["DEMAND_ID"]);
                    $builder3->whereIn('RECRUITMENT_STATUS',$recruitmentStatus['Selected']);
                    $candidatesSelected = $builder3->countAllResults();
                    $builder4 = $db->table('candidates');
                    $builder4->where('DEMAND_ID',$values["DEMAND_ID"]);
                    $builder4->whereIn('RECRUITMENT_STATUS',$recruitmentStatus['Rejected']);
                    $candidatesRejected = $builder4->countAllResults();
                    $builder5 = $db->table('candidates');
                    $builder5->where('DEMAND_ID',$values["DEMAND_ID"]);
                    $builder5->whereIn('RECRUITMENT_STATUS',$recruitmentStatus['Shortlisted']);
                    $candidatesShortlisted = $builder5->countAllResults();
                    $demands[$keys]["PROFILES_SHARED"] = $profilesShared;
                    $demands[$keys]["INTERVIEWS_SCHEDULED"] = $interviewsScheduled;
                    $demands[$keys]["CANDIDATES_SHORTLISTED"] = $candidatesShortlisted;
                    $demands[$keys]["CANDIDATES_SELECTED"] = $candidatesSelected;
                    $demands[$keys]["CANDIDATES_REJECTED"] = $candidatesRejected;
                }
            }
            // $fieldNames = array_diff( $fieldNames, ["PRIMARY_SKILL", "SECONDARY_SKILL", "JOB_DESCRIPTION"]);
            $usersQuery = $db->table('my_users');
            $usersQuery->select('full_name');
            $usersQuery->where('level>0');
            $userNames = $usersQuery->get()->getResultArray();
            $clients = $clientModel->orderBy('CLIENT_ID', 'DESC')->findAll();
            $data = [];
            $data['fieldNames'] = $fieldNames;
            $data['demands'] = $demands;
            $data['clients'] = $clients;
            // $data['customers'] = $customers;
            $data['industry'] = $industrySegment;
            $data['domain'] = $domain;
            $data['userNames'] = $userNames;
            $data['priority'] = $priority;
            $data['complexity'] = $complexity;
            $data['status'] = $status;
            $data['location'] = $location;
            return $data;
        }

        public function index()
        {
            helper(['form']);
            $data = Demands::initialise();
            echo view('demands', $data);
        }

        public function FileExport()
        {
            helper(['form']);
            helper('filesystem');

            $session = session();

            $header=true;
            $count = 0;

            $demandModel = new DemandModel();
            $demands = $demandModel->orderBy('demand_id', 'DESC')->findAll();
            $demandFields = $demandModel->allowedFields;
            $fileName = 'demands.xlsx';
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $col = "A";
            $row = "1";
            foreach($demandFields as $keys=>$values){
                $sheet->setCellValue("$col$row", ucwords($values));
                $col++;
            }

            $col = "A";
            $row = "2";
            foreach($demands as $keys=>$data){
                foreach($demandFields as $keys=>$value){
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
            return redirect()->to('/demands');
        }

        public function DeleteDemand() {
            helper(['form']);
            $demandModel = new DemandModel();
            $session = session();
            $demand_id = $this->request->getVar('demand_id3');
            if($demandModel->delete($demand_id))
            {
                $session->setFlashdata('updated','Demand '.$demand_id.' has been deleted '.' by '.session()->get('name').".");
                return redirect()->to('demands');
            }
            else
            {
                $session->setFlashdata('error','Error occured, cannot delete demand.');
                return redirect()->to('demands');            
            }
        }
        public function AddDemand() {
            helper(['form']);
            $demandModel = new DemandModel();
            $clientModel = new ClientModel();
            $session = session();
            $db = \Config\Database::connect();
            $client = $this->request->getVar('client');
            $clientName = $clientModel->where('CLIENT_ID', $client)->findColumn('CLIENT_NAME');
            $jd_id = $this->request->getVar('jd_id');
            $demand_status = $this->request->getVar('demand_status');
            $priority = $this->request->getVar('priority');
            $complexity = $this->request->getVar('complexity');
            $no_positions = $this->request->getVar('no_positions');
            $cus_spoc = $this->request->getVar('cus_spoc');
            $ibhaan_spoc = $this->request->getVar('ibhaan_spoc');
            $industry = $this->request->getVar('industry');
            $domain = $this->request->getVar('domain');
            $id = str_replace("&", "", $domain);
            $id = str_replace(" ", "", $id);
            $skill = $this->request->getVar((string) $id);
            $band = $this->request->getVar('band');
            $min_experience = $this->request->getVar('min_experience');
            $max_experience = $this->request->getVar('max_experience');
            $min_budget = $this->request->getVar('min_budget');
            $max_budget = $this->request->getVar('max_budget');
            $location = $this->request->getVar('location');
            $j_title = $this->request->getVar('j_title');
            $p_skills = $this->request->getVar('p_skills');
            $s_skills = $this->request->getVar('s_skills');
            $jd1 = $this->request->getVar('jd');
            $recruiter = $this->request->getVar('recruiter');
            $jd_document = $this->request->getFile('jd_document');

            $data = [
                'CLIENT_ID' => $client,
                'JD_ID' => $jd_id,
                'DEMAND_STATUS' => $demand_status,
                'PRIORITY' => $priority,
                'COMPLEXITY' => $complexity,
                'NO_POSITIONS' => $no_positions,
                'CUS_SPOC' => $cus_spoc,
                'IBHAAN_SPOC' => $ibhaan_spoc,
                'INDUSTRY_SEGMENT' => $industry,
                'DOMAIN' => $domain,
                'SKILL' => $skill,
                'BAND' => $band,
                'MIN_EXPERIENCE' => $min_experience,
                'MAX_EXPERIENCE' => $max_experience,
                'MIN_BUDGET' => $min_budget,
                'MAX_BUDGET' => $max_budget,
                'LOCATION' => $location,
                'JOB_TITLE' => $j_title,
                'PRIMARY_SKILL' => $p_skills,
                'SECONDARY_SKILL' => $s_skills,
                'JOB_DESCRIPTION' => $jd1,
                'RECRUITER' => $recruiter,
            ];
            
            if(is_uploaded_file($jd_document))
            {
                $ext = $jd_document->guessExtension();
                if($ext)
                {
                    $fileName = time() . '_' . $clientName[0] . '-' . $j_title . '.' . $ext;
                    $fileName = str_replace(' ', '_', $fileName);
                    $fileName = preg_replace("/[^a-zA-Z0-9\s!?.,_\'\"]/", "_", $fileName);
                    $fileName = preg_replace("/_+/", "_", $fileName);
                    $path = $jd_document->store('jds', $fileName);
                    if(!$path)
                    {
                        $error = "The JD Could Not Be Stored. Try Again.";
                        $data = array($error);
                        exit(json_encode($data));
                    }
                    else
                    {
                        $data['JD_LOCATION'] = $path;
                    }
                }
            }
            
            if($demandModel->save($data))
            {
                $csrfToken = csrf_token();
                $csrfHash = csrf_hash();
                $id = $demandModel->getInsertID();
                $builder = $db->table('demand');
                $builder->select('client.CLIENT_NAME, demand.*, my_users.FULL_NAME');
                $builder->join('client', 'demand.client_id = client.client_id');
                $builder->join('my_users','demand.recruiter = my_users.user_id');
                $builder->where('demand.DEMAND_ID', $id);
                $query = $builder->get();
                $demands = $query->getResultArray();
                $builder6 = $db->table('candidates');
                $builder6->where('DEMAND_ID',$id);
                $profilesShared = $builder6->countAllResults();
                $builder2 = $db->table('candidates');
                $builder2->where('DEMAND_ID',$id);
                $builder2->where('interview_date!=',"");
                $interviewsScheduled = $builder2->countAllResults();
                $builder3 = $db->table('candidates');
                $builder3->where('DEMAND_ID',$id);
                $builder3->whereIn('RECRUITMENT_STATUS',['001_Pos. Hold','01_L1-In progress','01_R1-In progress','01_R2-In progress','01_F2F-In progress','01_HR-In progress','01_Reject-No Show','01_Reject-Cand. Dropped','02_Feedback Pending']);
                $candidatesShortListed = $builder3->countAllResults();
                $builder7 = $db->table('candidates');
                $builder7->where('DEMAND_ID',$id);
                $builder7->whereIn('RECRUITMENT_STATUS',['S1. Offer Pending','S2. Documents Uploaded','S2. Offer Accepted','S2. Offer Released','S5. Joined','S6. Candidate Dropped Offer','S6. Client Rejected','S6. Offer Declined','S6. Quest Duplicate']);
                $candidatesSelected = $builder7->countAllResults();
                $builder4 = $db->table('candidates');
                $builder4->whereIn('RECRUITMENT_STATUS',['00_Duplicate','00_Reject-Budget','00_Reject-HNP','00_Reject-No Responce','00_Reject-Screen','00_Reject-Fake','001_Pos. closed','001_Pos. Modified','03_Reject-Skill']);
                $candidatesRejected = $builder4->countAllResults();
                $demands[0]["PROFILES_SHARED"] = $profilesShared;
                $demands[0]["INTERVIEWS_SCHEDULED"] = $interviewsScheduled;
                $demands[0]["CANDIDATES_SHORTLISTED"] = $candidatesShortListed;
                $demands[0]["CANDIDATES_SELECTED"] = $candidatesSelected;
                $demands[0]["CANDIDATES_REJECTED"] = $candidatesRejected;
                $data = array("success", $csrfToken, $csrfHash, $demands);
                exit(json_encode($data));
            }
            else
            {
                $result = array("Error Occurred While Saving Data. Please Try Again.");
                exit(json_encode($result));
            }
        }

        public function GetDemand()
        {
            helper(['form']);
            $demandModel = new DemandModel();
            $demandID = $this->request->getVar('demand_id');
            $demand = $demandModel->find($demandID);
            echo json_encode($demand);
        }

        public function EditDemand()
        {
            helper(['form']);
            $demandModel = new DemandModel();
            $clientModel = new ClientModel();
            $session = session();
            $demand = $this->request->getVar('demand_id2');
            $client = $this->request->getVar('client2');
            $clientName = $clientModel->where('CLIENT_ID', $client)->findColumn('CLIENT_NAME');
            $jd_id = $this->request->getVar('jd_id2');
            $demand_status = $this->request->getVar('demand_status2');
            $priority = $this->request->getVar('priority2');
            $complexity = $this->request->getVar('complexity2');
            $no_positions = $this->request->getVar('no_positions2');
            $cus_spoc = $this->request->getVar('cus_spoc2');
            $ibhaan_spoc = $this->request->getVar('ibhaan_spoc2');
            $industry = $this->request->getVar('industry2');
            $domain = $this->request->getVar('domain2');
            $id = str_replace("&", "", $domain);
            $id = str_replace(" ", "", $id);
            $skill = $this->request->getVar((string) $id);
            $band = $this->request->getVar('band');
            $min_experience = $this->request->getVar('min_experience2');
            $max_experience = $this->request->getVar('max_experience2');
            $min_budget = $this->request->getVar('min_budget2');
            $max_budget = $this->request->getVar('max_budget2');
            $location = $this->request->getVar('location2');
            $j_title = $this->request->getVar('j_title2');
            $p_skills = $this->request->getVar('p_skills2');
            $s_skills = $this->request->getVar('s_skills2');
            $jd = $this->request->getVar('jd2');

            $recruiter = $this->request->getVar('recruiter2');

            $jd_document = $this->request->getFile('jd_document2');

            $data = [
                'CLIENT_ID' => $client,
                'JD_ID' => $jd_id,
                'DEMAND_STATUS' => $demand_status,
                'PRIORITY' => $priority,
                'COMPLEXITY' => $complexity,
                'NO_POSITIONS' => $no_positions,
                'CUS_SPOC' => $cus_spoc,
                'IBHAAN_SPOC' => $ibhaan_spoc,
                'BAND' => $band,
                'MIN_EXPERIENCE' => $min_experience,
                'MAX_EXPERIENCE' => $max_experience,
                'MIN_BUDGET' => $min_budget,
                'MAX_BUDGET' => $max_budget,
                'LOCATION' => $location,
                'JOB_TITLE' => $j_title,
                'PRIMARY_SKILL' => $p_skills,
                'SECONDARY_SKILL' => $s_skills,
                'JOB_DESCRIPTION' => $jd,
                'RECRUITER' => $recruiter,
            ];

            if(is_uploaded_file($jd_document))
            {
                $ext = $jd_document->guessExtension();
                if($ext)
                {
                    $fileName = time() . '_' . $clientName[0] . '-' . $j_title . '.' . $ext;
                    $fileName = str_replace(' ', '_', $fileName);
                    $fileName = preg_replace("/[^a-zA-Z0-9\s!?.,_\'\"]/", "_", $fileName);
                    $fileName = preg_replace("/_+/", "_", $fileName);
                    $path = $jd_document->store('jds', $fileName);
                    if(!$path)
                    {
                        $error = "The JD Could Not Be Stored. Try Again.";
                        $data = array($error);
                        exit(json_encode($data));
                    }
                    else
                    {
                        $data['JD_LOCATION'] = $path;
                    }
                }
            }

            if($demandModel->update($demand, $data))
            {
                $csrfToken = csrf_token();
                $csrfHash = csrf_hash();
                $data = array("success", $csrfToken, $csrfHash);
                exit(json_encode($data));
            }
            else
            {
                $csrfToken = csrf_token();
                $csrfHash = csrf_hash();
                $data = array("error", $csrfToken, $csrfHash);
                exit(json_encode($data));
            }
        }

        public function Filter()
        {
            $j_title = $this->request->getVar('j_title');

        }

        public function JDDownload(){
            helper(['form']);
            $location = $this->request->getVar('jdLocation');
            $path = WRITEPATH.'uploads/'.$location;
            $mime = mime_content_type($path);

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

            readfile($path); // push it out
        }

        public function getCSRFHash()
        {
            helper(['form']);
            $csrfToken = csrf_token();
            $csrfHash = csrf_hash();
            $data = array($csrfToken, $csrfHash);
            exit(json_encode($data));
        }
    }
?>