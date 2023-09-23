<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\DemandModel;
use App\Models\ClientModel;
use CodeIgniter\Files\File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class IbhaanInterview extends Controller
{
    public function index()
    {
        $data = IbhaanInterview::initialise();
        echo view('ibhaaninterview', $data);
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
        $interviews->where('ig_interview.INTERVIEW_APPROVAL =', "1");
        $interviews->where('interview_consultants.USER_ID', session()->get('user_id'));
        $interviewArray = $interviews->get()->getResultArray();
        $fieldNames = ['INTERVIEW_ID', 'CLIENT_NAME', 'JOB_TITLE', 'INTERVIEWER_NAME', 'CANDIDATE_NAME', 'INTERVIEW_DATETIME'];
        $data['interviews'] = $interviewArray;
        $data['fieldNames'] = $fieldNames;
        return $data;
    }

    public function Logout()
    {
        $array = array('user_id','name','email','level','isLoggedIn');
        session()->remove($array);
        return redirect()->to('/signin');
    }

    public function JD_CVData($interviewID)
    {
        $db = \Config\Database::connect();
        $interviews = $db->table('ig_interview');
        $interviews->select('CANDIDATE_ID');
        $interviews->where('ig_interview.INTERVIEW_ID = ', $interviewID);
        $candidateID = $interviews->get()->getResultArray();
        return $candidateID[0]['CANDIDATE_ID'];
    }

    public function CandidateData()
    {
        helper(['form']);
        helper('filesystem');

        $interviewID = $this->request->getVar('interview_id');
        $candidateID = IbhaanInterview::JD_CVData($interviewID);
        $db = \Config\Database::connect();
        $zipname = WRITEPATH.'uploads/'.time().'candidateExports.zip';
        $zip = new \ZipArchive();
        $res = $zip->open($zipname, \ZipArchive::CREATE);
        $builder = $db->table('candidates');
        $builder->select('candidates.CANDIDATE_NAME, candidates.PHONE_NO, candidates.EMAIL_ADDRESS, candidates.ORGANISATION, candidates.WORK_LOCATION, candidates.TOTAL_EXPERIENCE, candidates.CCTC_LPA, candidates.ECTC_LPA, candidates.NOTICE_PERIOD_DAYS, candidates.DEMAND_ID, candidates.CV_LOCATION');
        $builder->where('CANDIDATE_ID =', $candidateID);
        $candidates = $builder->get()->getResultArray();
        $fieldNames = ['CANDIDATE_NAME', 'PHONE_NO', 'EMAIL_ADDRESS', 'ORGANISATION', 'WORK_LOCATION', 'TOTAL_EXPERIENCE', 'CCTC_LPA', 'ECTC_LPA', 'NOTICE_PERIOD_DAYS'];
        foreach($candidates as $keys2=>$data2)
        {
            $candidate = $data2['CANDIDATE_NAME'];
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
        $builder->select('client.CLIENT_NAME, demand.JOB_TITLE, demand.PRIMARY_SKILL, demand.SECONDARY_SKILL, demand.JOB_DESCRIPTION, demand.INDUSTRY_SEGMENT, demand.DOMAIN, demand.SKILL, demand.MIN_BUDGET, demand.MAX_BUDGET, demand.MIN_EXPERIENCE, demand.MAX_EXPERIENCE, demand.JD_LOCATION, my_users.FULL_NAME');
        $builder->join('client', 'demand.client_id = client.client_id');
        $builder->join('my_users','demand.recruiter = my_users.user_id');
        $builder->where('demand.DEMAND_ID = ', $demand);
        $query = $builder->get();
        $demands = $query->getResultArray();
        $fieldNames2 = ['CLIENT_NAME', 'JOB_TITLE', 'PRIMARY_SKILL', 'SECONDARY_SKILL', 'JOB_DESCRIPTION', 'INDUSTRY_SEGMENT', 'DOMAIN', 'SKILL', 'MIN_EXPERIENCE', 'MAX_EXPERIENCE', 'MIN_BUDGET', 'MAX_BUDGET'];
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

        $candidateName = str_replace('.', '', $candidate);
        $candidateName = str_replace(' ', '_', $candidateName);
        $candidateName = preg_replace("/[^a-zA-Z0-9\s!?.,_]/", "", $candidateName);
        $candidateName = preg_replace("/_+/", "_", $candidateName);

        $fileName = $candidateName .' - '.(string) $demand.'.xlsx';
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

    public function StatusUpdate()
    {
        $db = \Config\Database::connect();
        $interviewID = $this->request->getVar('interviewID');
        $interview = $db->table('ig_interview');
        $interview->select('CANDIDATE_ID');
        $interview->where('INTERVIEW_ID', $interviewID);
        $candidateID = $interview->get()->getResultArray();
        $candidateID = $candidateID[0]['CANDIDATE_ID'];
        $interviewResult = $this->request->getVar('interviewResult');
        $sa = $this->request->getFile('skillAnalysis');
        $c_name = $this->request->getVar('candidateName');
        $c_name = str_replace('.', '', $c_name);

        $saveData = [
            'INTERVIEW_SELECTION' => $interviewResult,
        ];

        if($interviewResult == "1")
        {
            $sa = $this->request->getFile('skillAnalysis');
            if(is_uploaded_file($sa))
            {
                $ext = $sa->guessExtension();
                if($ext)
                {
                    $fileName = time() . '_' . str_replace('"', '', $c_name) . '_' . "SAS". '.' . $ext;
                    $fileName = str_replace(' ', '_', $fileName);
                    $fileName = preg_replace("/[^a-zA-Z0-9\s!?.,_]/", "", $fileName);
                    $fileName = preg_replace("/_+/", "_", $fileName);
                    $path = $sa->store('sa', $fileName);
                    if(!$path)
                    {
                        $error = "Skill Analysis Sheet Could Not Be Stored. Try Again.";
                        $data = array($error);
                        exit(json_encode($data));
                    }
                }
                else
                {
                    $error = "Skill Analysis Sheet Could Not Be Stored. Try Again.";
                    $data = array($error);
                    exit(json_encode($data));
                }
            }
            else
            {
                $error = "Skill Analysis Sheet Could Not Be Stored. Try Again.";
                $data = array($error);
                exit(json_encode($data));
            }
            $saveData['SKILL_ANALYSIS'] = $path;
        }
        else if($interviewResult == "0")
        {
            $sa2 = $this->request->getVar('skillAnalysis2');
            $saveData['SKILL_ANALYSIS_2'] = $sa2;
        }
        $interview = $db->table('ig_interview');
        $interview->set($saveData);
        $interview->where('interview_id', $interviewID);
        if($interview->update())
        {
            if($interviewResult == "1")
            {
                $candidate = $db->table('candidates');
                $candidate->set(['RECRUITMENT_STATUS' => "04. R1-In progress"]);
                $candidate->where('CANDIDATE_ID', $candidateID);
                if($candidate->update())
                {
                    $array = ['success', 'Interview Status Updated Successfully.'];
                    exit(json_encode($array));        
                }
                else
                {
                    $array = ['error', 'Error Occurred.'];
                    exit(json_encode($array));        
                }
            }
            else if($interviewResult == "0")
            {
                $candidate = $db->table('candidates');
                $candidate->set(['RECRUITMENT_STATUS' => "06. Reject-Skill"]);
                $candidate->where('CANDIDATE_ID', $candidateID);
                if($candidate->update())
                {
                    $array = ['success', 'Interview Status Updated Successfully.'];
                    exit(json_encode($array));        
                }
                else
                {
                    $array = ['error', 'Error Occurred.'];
                    exit(json_encode($array));        
                }
            }
        }
        else
        {
            $array = ['error', 'Error Occurred.'];
            exit(json_encode($array));
        }
    }

}
?>