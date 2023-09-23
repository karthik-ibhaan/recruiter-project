<?php

    namespace App\Controllers;
    use CodeIgniter\Controller;
    use App\Models\DemandModel;
    use App\Models\ClientModel;
    use CodeIgniter\Files\File;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use App\Libraries\RecruitmentStatuses;

    class InterviewApproval extends Controller
    {
        public function index()
        {
            $data = InterviewApproval::initialise();
            echo view('interviewapproval', $data);
        }
        public function initialise()
        {
            $db = \Config\Database::connect();
            $interviews = $db->table('ig_interview');
            $interviews->select('ig_interview.INTERVIEW_ID, interview_consultants.INTERVIEWER_NAME, candidates.CANDIDATE_NAME, demand.JOB_TITLE, client.CLIENT_NAME, ig_interview.INTERVIEW_DATETIME');
            $interviews->join('interview_consultants', 'ig_interview.INTERVIEWER_ID  = interview_consultants.INTERVIEWER_ID');
            $interviews->join('candidates', 'candidates.CANDIDATE_ID = ig_interview.CANDIDATE_ID');
            $interviews->join('demand', 'demand.DEMAND_ID = candidates.DEMAND_ID');
            $interviews->join('client', 'client.CLIENT_ID = demand.CLIENT_ID');
            $interviews->where('ig_interview.INTERVIEW_APPROVER', session()->get('user_id'));
            $interviews->where('ig_interview.INTERVIEW_APPROVAL', '0');
            $interviewArray = $interviews->get()->getResultArray();
            $fieldNames = ['INTERVIEW_ID', 'CLIENT_NAME', 'JOB_TITLE', 'INTERVIEWER_NAME', 'CANDIDATE_NAME', 'INTERVIEW_DATETIME'];
            $data['interviews'] = $interviewArray;
            $data['fieldNames'] = $fieldNames;
            return $data;
        }

        public function GetInterviews()
        {
            $db = \Config\Database::connect();
            $interviews = $db->table('ig_interview');
            $interviews->select('ig_interview.INTERVIEW_ID, interview_consultants.INTERVIEWER_NAME, candidates.CANDIDATE_NAME, demand.JOB_TITLE, client.CLIENT_NAME, ig_interview.INTERVIEW_DATETIME, ig_interview.INTERVIEW_SELECTION, ig_interview.SKILL_ANALYSIS, ig_interview.SKILL_ANALYSIS_2');
            $interviews->join('interview_consultants', 'ig_interview.INTERVIEWER_ID  = interview_consultants.INTERVIEWER_ID');
            $interviews->join('candidates', 'candidates.CANDIDATE_ID = ig_interview.CANDIDATE_ID');
            $interviews->join('demand', 'demand.DEMAND_ID = candidates.DEMAND_ID');
            $interviews->join('client', 'client.CLIENT_ID = demand.CLIENT_ID');
            $interviews->where('ig_interview.INTERVIEW_APPROVER', session()->get('user_id'));
            $interviewArray = $interviews->get()->getResultArray();
            $fieldNames = ['INTERVIEW_ID', 'CLIENT_NAME', 'JOB_TITLE', 'INTERVIEWER_NAME', 'CANDIDATE_NAME', 'INTERVIEW_DATETIME', 'INTERVIEW_SELECTION', 'SKILL_ANALYSIS', 'SKILL_ANALYSIS_2'];
            exit(json_encode([$interviewArray, $fieldNames]));
        }
        
        public function Approval()
        {
            if($this->request->isAJAX())
            {
                $interviewID = $this->request->getVar('interviewID');
                $saveData = [
                    'INTERVIEW_APPROVAL' => "1"
                ];
                $db = \Config\Database::connect();
                $interview = $db->table('ig_interview');
                $interview->set($saveData);
                $interview->where('interview_id', $interviewID);
                if($interview->update())
                {
                    $array = ['success', 'Interview Approved Successfully.'];
                    exit(json_encode($array));
                }
                else
                {
                    $array = ['error', 'Error Occurred.'];
                    exit(json_encode($array));
                }
            }
        }

        public function Download(){
            helper(['form']);
            $location = $this->request->getVar('location');
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
    }
?>