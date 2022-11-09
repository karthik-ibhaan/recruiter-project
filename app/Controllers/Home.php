<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\UserModel;
use CodeIgniter\Files\File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Home extends Controller
{

    public function initialise()
    {
        $candidateQueries = array();
        $db = \Config\Database::connect();
        $session = session();
        //Setting Current Timezone and Time
        $tz = 'Asia/Kolkata';
        $timestamp = time();
        $dt = new \DateTime("now", new \DateTimeZone($tz)); //first argument "must" be a string
        $dt->setTimestamp($timestamp); //adjust the object to correct timestamp

        //Setting Recruitment Statuses
        $recruitmentStatus['In Process'] = ['00_Sourcing','00_Profile Sent','00_CV sent to client','00_No Feedback from client'];
        $recruitmentStatus['Shortlisted'] = ['001_Pos. Hold','01_L1-In progress','01_R1-In progress','01_R2-In progress','01_F2F-In progress','01_HR-In progress','01_Reject-No Show','01_Reject-Cand. Dropped','02_Feedback Pending'];
        $recruitmentStatus['Rejected-Sc'] = ['00_Duplicate','00_Reject-Budget','00_Reject-HNP','00_Reject-No Response','00_Reject-Screen','00_Reject-Fake','001_Pos. closed','001_Pos. Modified'];
        $recruitmentStatus['Rejected-Skill'] = ['03_Reject-Skill'];
        $recruitmentStatus['Selected'] = ['04_Selected','05_Offer Declined'];

        foreach($recruitmentStatus as $r=>$status)
        {
            foreach($status as $s=>$value)
            {
                $builder = $db->table('candidates');
                $builder->select('recruitment_status');
                $builder->where('recruitment_status', $value);
                $builder->where('recruiter',$session->get('user_id'));
                $queryResult = $builder->countAllResults();
                $candidateQueries[$value] = $queryResult;
            }
        }
        $builder3 = $db->table('candidates');
        $builder3->select('candidate_name');
        $builder3->where('recruiter', $session->get('user_id'));        
        $builder4 = $db->table('candidates');
        $builder4->select('candidate_name');
        $builder4->where('recruiter', $session->get('user_id'));        
        $candidatesRecent = $builder3->orderBy('candidate_id','DESC')->limit(1)->get()->getResultArray();
        $candidatesTotal = $builder4->countAllResults();
        /* Number of Interviews */
        $builder5 = $db->table('candidates');
        $builder5->select('interview_date');
        $interviewDateTime = $builder5->where('interview_date>=',$dt->format('Y-m-d H:i:s'));
        $interviewDate = $builder5->countAllResults();
        /* Demand Details */
        $builder2 = $db->table('demand');
        $builder2->select('demand.job_title, demand.complexity, client.client_name')->orderBy('demand_id','DESC');
        $builder2->join('client', 'client.client_id = demand.client_id')->limit(3);
        $query = $builder2->get();
        $demandQueries = $query->getResultArray();
        $fieldNames = $query->getFieldNames();
        /* Data */
        $data['time'] = $dt;
        $data['interviewDateTime'] = $interviewDateTime;
        $data['interviewDate'] = $interviewDate;
        $data['candidateQueries'] = $candidateQueries;
        $data['candidatesTotal'] = $candidatesTotal;
        $data['candidatesRecent'] = $candidatesRecent;
        $data['recruitmentStatus'] = $recruitmentStatus;
        $data['demandQueries'] = $demandQueries;
        return $data;
    }
    public function index()
    {
        helper(['form']);
        $data = Home::initialise();
        echo view('home', $data);
    }

    public function FileExport()
    {
        helper(['form']);
        helper('filesystem');

        $session = session();

        $header=true;
        $count = 0;

        $userModel = new UserModel();
        $users = $userModel->orderBy('user_id', 'DESC')->findAll();
        $fieldNames = $userModel->allowedFields;
        $fileName = 'users.xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        unset($fieldNames[3]);

        $col = "A";
        $row = "1";
        foreach($fieldNames as $keys=>$values){
            $sheet->setCellValue("$col$row", ucwords($values));
            $col++;
        }

        $col = "A";
        $row = "2";
        foreach($users as $keys=>$data){
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
        return redirect()->to('/home');
    }

    public function Logout()
    {
        $array = array('user_id','name','email','level','isLoggedIn');
        session()->remove($array);
        return redirect()->to('/signin');
    }
}
?>