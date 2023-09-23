<?php

    namespace App\Controllers;
    use CodeIgniter\Controller;
    use App\Models\DemandModel;
    use App\Models\ClientModel;
    use CodeIgniter\Files\File;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use App\Libraries\RecruitmentStatuses;

    class IGInterviews extends Controller
    {
        public function index()
        {
            $data = IGInterviews::initialise();
            echo view('iginterviews', $data);
        }
        
        public function initialise()
        {
            $db = \Config\Database::connect();
            $interviews = $db->table('ig_interview');
            $interviews->select('ig_interview.INTERVIEW_ID, interview_consultants.INTERVIEWER_NAME, candidates.CANDIDATE_NAME, demand.JOB_TITLE, client.CLIENT_NAME, ig_interview.INTERVIEW_DATETIME, ig_interview.INTERVIEW_SELECTION, ig_interview.SKILL_ANALYSIS, ig_interview.SKILL_ANALYSIS_2');
            $interviews->join('interview_consultants', 'ig_interview.INTERVIEWER_ID  = interview_consultants.INTERVIEWER_ID');
            $interviews->join('candidates', 'candidates.CANDIDATE_ID = ig_interview.CANDIDATE_ID');
            $interviews->join('demand', 'demand.DEMAND_ID = candidates.DEMAND_ID');
            $interviews->join('client', 'client.CLIENT_ID = demand.CLIENT_ID');
            if(session()->get('level') == "1")
            {

            }
            else if(session()->get('level') == "2" || session()->get('level') == "3")
            {
                $interviews->where('candidates.RECRUITER', session()->get('user_id'));
            }
            else
            {
                return redirect()->to('home');
            }
            $interviewArray = $interviews->get()->getResultArray();
            $fieldNames = ['INTERVIEW_ID', 'CLIENT_NAME', 'JOB_TITLE', 'INTERVIEWER_NAME', 'CANDIDATE_NAME', 'INTERVIEW_DATETIME', 'INTERVIEW_SELECTION', 'SKILL_ANALYSIS', 'SKILL_ANALYSIS_2'];
            $data['fieldNames'] = $fieldNames;
            $data['interviews'] = $interviewArray;
            return $data;
        }
    }
?>