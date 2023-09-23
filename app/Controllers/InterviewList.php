<?php

    namespace App\Controllers;
    use CodeIgniter\Controller;
    use App\Models\CandidateModel;
    use App\Models\DemandModel;
    use App\Models\UserModel;
    use App\Models\ClientModel;
    use CodeIgniter\Files\File;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    class InterviewList extends Controller
    {
        public function index()
        {
            helper(['form']);
            echo view('interviewlist');
        }

        public function GetData()
        {
            $date = $this->request->getVar('date');
            $tz = 'Asia/Kolkata';
            $dt = new \DateTime($date, new \DateTimeZone($tz));
            $dt3 = new \DateTime($date, new \DateTimeZone($tz));
            $dt3->modify('+1 day');
            $db = \Config\Database::connect();
            $builder = $db->table('candidates');
            $builder->select('client.CLIENT_NAME, candidates.RECRUITER, my_users.FULL_NAME, candidates.CANDIDATE_NAME, demand.JOB_TITLE, candidates.WORK_LOCATION, demand.CUS_SPOC, candidates.PHONE_NO, candidates.EMAIL_ADDRESS, candidates.INTERVIEW_DATE, candidates.RECRUITMENT_STATUS');
            $builder->join('my_users','my_users.USER_ID = candidates.RECRUITER');
            $builder->join('demand','demand.DEMAND_ID = candidates.DEMAND_ID');
            $builder->join('client', 'client.CLIENT_ID = demand.CLIENT_ID');
            $builder->where('candidates.INTERVIEW_DATE>=',$dt->format('Y-m-d 00:00:00'));
            $builder->where('candidates.INTERVIEW_DATE<',$dt3->format('Y-m-d 00:00:00'));
            $interview = $builder->get()->getResultArray();
            $fieldNames = $builder->get()->getFieldNames();
            foreach($interview as $keys=>$data2)
            {
                foreach($data2 as $keys2=>$value)
                {
                    if($keys2 == "INTERVIEW_DATE")
                    {
                        $dt2 = new \DateTime($value, new \DateTimeZone($tz));
                        $interview[$keys]['TIME'] = $dt2->format('H:i:s');
                    }
                    if($keys2 == "PHONE_NO")
                    {
                        $value = json_decode($value);
                        if($value[1] != "")
                        {
                            $interview[$keys][$keys2] = $value;
                        }
                        else
                        {
                            $interview[$keys][$keys2] = $value[0];
                        }
                    }
                }
            }
            echo json_encode($interview);
        }
        public function GetDataOfWeek()
        {
            $date = $this->request->getVar('date');
            $date2 = $this->request->getVar('date2');
            $tz = 'Asia/Kolkata';
            $dt = new \DateTime($date, new \DateTimeZone($tz));
            $dt3 = new \DateTime($date2, new \DateTimeZone($tz));
            $db = \Config\Database::connect();
            $builder = $db->table('candidates');
            $builder->select('client.CLIENT_NAME, candidates.RECRUITER, my_users.FULL_NAME, candidates.CANDIDATE_NAME, demand.JOB_TITLE, candidates.WORK_LOCATION, demand.CUS_SPOC, candidates.PHONE_NO, candidates.EMAIL_ADDRESS, candidates.INTERVIEW_DATE, candidates.RECRUITMENT_STATUS');
            $builder->join('my_users','my_users.USER_ID = candidates.RECRUITER');
            $builder->join('demand','demand.DEMAND_ID = candidates.DEMAND_ID');
            $builder->join('client', 'client.CLIENT_ID = demand.CLIENT_ID');
            $builder->where('candidates.INTERVIEW_DATE>=',$dt->format('Y-m-d 00:00:00'));
            $builder->where('candidates.INTERVIEW_DATE<=',$dt3->format('Y-m-d 00:00:00'));
            $interview = $builder->get()->getResultArray();
            $fieldNames = $builder->get()->getFieldNames();
            foreach($interview as $keys=>$data2)
            {
                foreach($data2 as $keys2=>$value)
                {
                    if($keys2 == "INTERVIEW_DATE")
                    {
                        $dt2 = new \DateTime($value, new \DateTimeZone($tz));
                        $interview[$keys]['TIME'] = $dt2->format('Y-m-d H:i:s');
                    }
                    if($keys2 == "PHONE_NO")
                    {
                        $value = json_decode($value);
                        if($value[1] != "")
                        {
                            $interview[$keys][$keys2] = $value;
                        }
                        else
                        {
                            $interview[$keys][$keys2] = $value[0];
                        }
                    }
                }
            }
            echo json_encode($interview);            
        }
    }
?>