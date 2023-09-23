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
    use App\Libraries\RecruitmentStatuses;

    class OverallStatus extends Controller
    {
        public function index()
        {
            helper(['form']);
            $tz = 'Asia/Kolkata';
            $timestamp = time();
            $date = "2022-01";
            $dt = new \DateTime($date, new \DateTimeZone($tz));
            echo view('overallstatus');
        }

        public function GetDataofMonth()
        {
            $date = $this->request->getVar('month');
            $db = \Config\Database::connect();
            $tz = 'Asia/Kolkata';
            $dt = new \DateTime($date, new \DateTimeZone($tz));
            
            $dt2 = new \DateTime($date, new \DateTimeZone($tz));
            $dt2->modify('+1 month');
            $builder2 = $db->table('my_users');
            $builder2->select('my_users.FULL_NAME,my_users.USER_ID');
            $builder2->where('my_users.level>',1);
            $users = $builder2->get()->getResultArray();
            $recruitmentStatuses = new RecruitmentStatuses();
            $statuses = $recruitmentStatuses->feedbackPendingStatuses();
            foreach($users as $keys=>$data)
            {
                $builder = $db->table('candidates');
                $builder->select('candidates.CANDIDATE_ID, candidates.RECRUITER');
                $builder->where('candidates.SUBMISSION_DATE>=',$dt->format('Y-m-d h:i:s'));
                $builder->where('candidates.SUBMISSION_DATE<',$dt2->format('Y-m-d h:i:s'));
                $builder->where('candidates.RECRUITER',$data['USER_ID']);
                $candidatesTotal = $builder->countAllResults();
                $users[$keys]['Total'] = $candidatesTotal;
            }
            foreach($users as $keys=>$data)
            {
                $builder3 = $db->table('candidates');
                $builder3->select('candidates.CANDIDATE_ID, candidates.RECRUITER');
                $builder3->where('candidates.RECRUITER',$data['USER_ID']);
                $builder3->where('candidates.SUBMISSION_DATE>=',$dt->format('Y-m-d h:i:s'));
                $builder3->where('candidates.SUBMISSION_DATE<',$dt2->format('Y-m-d h:i:s'));
                $builder3->whereIn('candidates.RECRUITMENT_STATUS', $statuses);
                $candidatesPending = $builder3->countAllResults();
                $users[$keys]['Pending'] = $candidatesPending;
            }
            echo json_encode($users);
        }
    }