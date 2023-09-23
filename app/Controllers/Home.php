<?php

    namespace App\Controllers;
    use CodeIgniter\Controller;
    use App\Models\UserModel;
    use App\Models\ClientModel;
    use CodeIgniter\Files\File;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use App\Libraries\RecruitmentStatuses;

    class Home extends Controller
    {

        public function initialise()
        {
            $recruitmentStatuses = new RecruitmentStatuses();
            $candidateQueries = array();
            $db = \Config\Database::connect();
            $session = session();
            //Setting Current Timezone and Time
            $tz = 'Asia/Kolkata';
            $timestamp = time();
            $dt = new \DateTime("now", new \DateTimeZone($tz)); 
            $dt->setTimestamp($timestamp);

            $dt2 = new \DateTime("now", new \DateTimeZone($tz));
            $dt2->modify('+1 month');

            /* ------ Monthly Data ------ */

            /* Feedback Pending Count */
            $feedbackCount = $recruitmentStatuses->feedbackPendingTotal();

            /* Candidates Total Count */
            $candidatesTotal = $recruitmentStatuses->candidatesTotalMonthly();

            /* Recruitment Statuses Count */
            $candidateQueries = $recruitmentStatuses->recruitmentStatusMonthly();

            /* ------ Daily Data ------ */
            $candidateSentToday = $recruitmentStatuses->candidateSentDaily();

            /* Candidates Total for Today */
            $candidatesTotalToday = $recruitmentStatuses->candidatesTotalDaily();

            /* Interviews Scheduled for Today */
            $interviews = $recruitmentStatuses->interviewScheduledDaily();

            /* Demands Worked Today */
            $demandsTotal = $recruitmentStatuses->demandsWorkedDaily();            
            /* ------ Daily Data End ------ */

            /* ------ Interviews ------ */
            $interview = $recruitmentStatuses->interviewScheduledArray();


            /* ------ Selection Data ------ */
            $selectionData = [];
            $selections = $recruitmentStatuses->selectionData();

            /* ------ Recruitment Statuses Array ------ */
            $recruitmentStatus = $recruitmentStatuses->recruitmentStatuses();

            /* Data */
            $data['time'] = $dt;
            $data['candidateQueries'] = $candidateQueries;
            $data['candidatesTotal'] = $candidatesTotal;
            $data['recruitmentStatus'] = $recruitmentStatus;
            $data['feedbacksPending'] = $feedbackCount;
            $data['selections'] = $selections;
            $data['interviews'] = $interviews;
            $data['candidatesTotalToday'] = $candidatesTotalToday;
            $data['demandsTotal'] = $demandsTotal;
            $data['candidateSentToday'] = $candidateSentToday;
            $data['scheduled'] = $interview;
            return $data;
        }
        public function index()
        {
            helper(['form']);
            $data = Home::initialise();
            echo view('home', $data);
        }

        public function GetDemandDetails()
        {
            $db = \Config\Database::connect();
            $userModel = new UserModel();
            $userIDs = $userModel->orderBy('user_id', 'DESC')->where('level>=',1)->findColumn('user_id');
            $userNames = $userModel->orderBy('user_id','DESC')->where('level>=',1)->findColumn('full_name');
            $demandsData = [];
            foreach($userNames as $keys=>$values)
            {
                $builder = $db->table('demand');
                $builder->select('DEMAND_ID');
                $builder->where('demand.DEMAND_STATUS', "Open");
                $builder->where('demand.IBHAAN_SPOC', $values);
                $openDemands = $builder->countAllResults();
                $builder2 = $db->table('demand');
                $builder2->select('DEMAND_ID');
                $builder2->where('demand.DEMAND_STATUS', "Hold");
                $builder2->where('demand.IBHAAN_SPOC', $values);
                $holdDemands = $builder2->countAllResults();
                $builder3 = $db->table('demand');
                $builder3->select('DEMAND_ID');
                $builder3->where('demand.DEMAND_STATUS', "Hold");
                $builder3->where('demand.IBHAAN_SPOC', $values);
                $closeDemands = $builder3->countAllResults();
                $total = $openDemands+$holdDemands+$closeDemands;
                if($total != 0)
                {
                    $overall = [
                        "RECRUITER" => $userNames[$keys],
                        "OPEN" => $openDemands,
                        "HOLD" => $holdDemands,
                        "CLOSED" => $closeDemands,
                        "GRANDTOTAL" => $total
                    ];
                    array_push($demandsData, $overall);
                }
            }
            exit(json_encode($demandsData));
        }
        
        public function Logout()
        {
            $array = array('user_id','name','email','level','isLoggedIn');
            session()->remove($array);
            return redirect()->to('/signin');
        }

        public function GetMonthlyData()
        {
            helper(['form']);
            $db = \Config\Database::connect();
            $date = $this->request->getVar('date');
            $recruitmentStatuses = new RecruitmentStatuses();

            /* ------ Monthly Data ------ */
            $candidateQueries = $recruitmentStatuses->recruitmentStatusMonthly($date);
            $candidatesTotal = $recruitmentStatuses->candidatesTotalMonthly($date);
            $feedbackCount = $recruitmentStatuses->feedbackPendingTotal();
            /* ------ Monthly Data End ------ */

            $details = array($candidateQueries, $candidatesTotal, $feedbackCount);
            echo json_encode($details);
        }

        public function GetDailyData()
        {
            helper(['form']);
            $date = $this->request->getVar('date');

            $recruitmentStatuses = new RecruitmentStatuses();

            /* ------ Daily Data ------ */

            /* Candidates Sent to Client Daily */
            $candidateSentDaily = $recruitmentStatuses->candidateSentDaily($date);

            /* Candidates Total Daily */
            $candidatesTotalDaily = $recruitmentStatuses->candidatesTotalDaily($date);

            /* Demands Worked Daily */
            $demandsTotal = $recruitmentStatuses->demandsWorkedDaily($date);

            /* Interviews Scheduled Daily */
            $interviews = $recruitmentStatuses->interviewScheduledDaily($date);

            /* ------ Daily Data End ------ */
            $details = array($candidatesTotalDaily, $demandsTotal, $candidateSentDaily, $interviews);
            echo json_encode($details);
        }

        public function GetInterviewDetails()
        {
            $date = $this->request->getVar('date');
            $recruitmentStatuses = new RecruitmentStatuses;
            $interview = $recruitmentStatuses->interviewScheduledArray($date);
            echo json_encode($interview);
        }
        
        public function GetFeedbackPendingDetails() 
        {
            helper(['form']);
            $recruitmentStatuses = new RecruitmentStatuses;
            $details = $recruitmentStatuses->feedbackPendingData();
            echo json_encode($details);
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
    }
?>