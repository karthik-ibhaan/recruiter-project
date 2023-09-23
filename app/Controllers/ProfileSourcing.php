<?php

    namespace App\Controllers;
    use CodeIgniter\Controller;
    use App\Models\UserModel;
    use CodeIgniter\Files\File;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    class ProfileSourcing extends Controller
    {
        public function initialise()
        {
            $db = \Config\Database::connect();
            $tz = 'Asia/Kolkata';
            $timestamp = time();
            $dt = new \DateTime("now", new \DateTimeZone($tz));
            $dt->setTimestamp($timestamp);

            $builder = $db->table('candidates');
            $builder->select('client.CLIENT_NAME, demand.DEMAND_ID, candidates.CANDIDATE_ID, my_users.FULL_NAME, candidates.SUBMISSION_DATE');
            $builder->join('demand', 'demand.DEMAND_ID = candidates.DEMAND_ID');
            $builder->join('client', 'client.CLIENT_ID = demand.CLIENT_ID');
            $builder->join('my_users','my_users.USER_ID = candidates.RECRUITER');
            $candidatesTotal = $builder->get()->getResultArray();
            $fieldNames = $builder->get()->getFieldNames();
            $data['dt'] = $dt;
            $data['sourced'] = $candidatesTotal;
            $data['fieldNames'] = $fieldNames;
            return $data;
        }

        public function index()
        {
            helper(['form']);
            $data = ProfileSourcing::initialise();
            echo view('profilesourcing', $data);
        }

        public function GetData()
        {
            if($this->request->isAJAX())
            {
                helper(['form']);
                $db = \Config\Database::connect();
                $date = $this->request->getVar('date');
                $tz = 'Asia/Kolkata';
                $dt = new \DateTime($date, new \DateTimeZone($tz));
                $dt2 = new \DateTime($date, new \DateTimeZone($tz));
                $dt2->modify('+1 day');
                $details = ProfileSourcing::profileSourcingData($dt, $dt2);
                echo json_encode($details);    
            }
        }

        function profileSourcingData($dt, $dt2)
        {
            $db = \Config\Database::connect();
            $userModel = new UserModel();
            $users = $userModel->orderBy('user_id', 'DESC')->findColumn('user_id');
            $userName = $userModel->orderBy('user_id','DESC')->findColumn('full_name');
            $data = [];
            foreach($users as $key => $value)
            {
                $builder = $db->table('candidates');
                $builder->select('client.CLIENT_NAME, demand.DEMAND_ID, candidates.CANDIDATE_ID, my_users.FULL_NAME, candidates.SUBMISSION_DATE');
                $builder->join('demand', 'demand.DEMAND_ID = candidates.DEMAND_ID');
                $builder->join('client', 'client.CLIENT_ID = demand.CLIENT_ID');
                $builder->join('my_users','my_users.USER_ID = candidates.RECRUITER');
                $builder->where('candidates.SUBMISSION_DATE>=',$dt->format('Y-m-d 00:00:00'));
                $builder->where('candidates.SUBMISSION_DATE<',$dt2->format('Y-m-d 00:00:00'));
                $builder->where('candidates.RECRUITER', $value);
                $candidates = $builder->get()->getResultArray();
                $data[$userName[$key]] = $candidates;
            }
            $details = array($users, $userName, $data, $dt, $dt2);
            return $details;
        }

        public function GetDataOfWeek()
        {
            if($this->request->isAJAX())
            {
                helper(['form']);
                $db = \Config\Database::connect();
                $date = $this->request->getVar('date');
                $tz = 'Asia/Kolkata';
                $dt = new \DateTime($date, new \DateTimeZone($tz));
                $dt2 = new \DateTime(date('Y-m-d'), new \DateTimeZone($tz));
                $details = ProfileSourcing::profileSourcingData($dt, $dt2);
                echo json_encode($details);
            }
        }
    }
?>