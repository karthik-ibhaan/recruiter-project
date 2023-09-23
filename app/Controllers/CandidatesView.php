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
            $recruitmentStatuses = new RecruitmentStatuses;
            $recruitmentStatus = $recruitmentStatuses->recruitmentStatuses();

            // $builder->select('candidates.*','demand.demand_id','demand.job_title, my_users.full_name');
            // $builder->join('demand', 'demand.demand_id = candidates.demand_id');
            $sqlquery = 'SELECT client.CLIENT_NAME, demand.DEMAND_ID, demand.DEMAND_STATUS, demand.CUS_SPOC, demand.JOB_TITLE, candidates.*, my_users.FULL_NAME FROM candidates JOIN demand ON demand.demand_id = candidates.DEMAND_ID JOIN client ON client.CLIENT_ID = demand.CLIENT_ID JOIN my_users ON my_users.USER_ID = candidates.RECRUITER ORDER BY candidates.CANDIDATE_ID DESC';
            $query = $db->query($sqlquery);
            $candidates = $query->getResultArray();
            $fieldNames = $query->getFieldNames();
            $fieldNames = array_unique($fieldNames);
            // $candidates = $candidateModel->orderBy('CANDIDATE_ID', 'DESC')->findAll();
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
            $data = CandidatesView::initialise();
            echo view('candidatesview', $data);
        }
    }
?>