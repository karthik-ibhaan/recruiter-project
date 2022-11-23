<?php

    namespace App\Controllers;
    use CodeIgniter\Controller;
    use App\Models\CandidateModel;
    use App\Models\DemandModel;
    use App\Models\ClientModel;
    use CodeIgniter\Files\File;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    class CandidatesView extends Controller
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
            $demandOptions->where('demand.demand_status','Open');
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
            $data = CandidatesView::initialise();
            echo view('candidatesview', $data);
        }
    }