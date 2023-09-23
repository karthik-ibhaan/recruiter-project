<?php

    namespace App\Controllers;
    use CodeIgniter\Controller;
    use App\Models\ClientModel;
    use App\Models\CustomerModel;
    use CodeIgniter\Files\File;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    class RunRateReport extends Controller
    {
        public function initialise()
        {
            $db = \Config\Database::connect();
            $query2 = $db->table('my_users');
            $query2->select('user_id, full_name');
            $query2->where('level>',1);
            $users = $query2->get()->getResultArray();
            $dates_month = RunRateReport::dates_month(date("m", strtotime("today")), date("Y", strtotime("today")));
            $details = array();
            foreach($users as $keys=>$data)
            {
                $interviewData = array();
                $sourcedData = array();
                $total = array();
                $months = array();

                $total["RECRUITER_NAME"] = $data['full_name'];
                foreach($dates_month as $keys2=>$monthDate)
                {
                    $query = $db->table('candidates');
                    $query->select('candidates.INTERVIEW_DATE');
                    $query->where('candidates.RECRUITER', $data['user_id']);
                    $query->where('candidates.INTERVIEW_DATE>=', date('Y-m-d 00:00:00', strtotime($monthDate)));
                    $query->where('candidates.INTERVIEW_DATE<=', date('Y-m-d 23:59:59', strtotime($monthDate)));
                    $interviews = $query->countAllResults();
                    $interviewData[$keys2] = $interviews;
                    $query2 = $db->table('candidates');
                    $query2->select('*');
                    $query2->where('candidates.RECRUITER', $data['user_id']);
                    $query2->where('candidates.SUBMISSION_DATE>=', date('Y-m-d 00:00:00', strtotime($monthDate)));
                    $query2->where('candidates.SUBMISSION_DATE<=', date('Y-m-d 23:59:59', strtotime($monthDate)));
                    $sourced = $query2->countAllResults();
                    $sourcedData[$keys2] = $sourced;
                    array_push($months, $keys2);
                }
                $total["DATES"] = $months;
                $total["INTERVIEWS"] = $interviewData;
                $total["SOURCED"] = $sourcedData;
                array_push($details, $total);
                $interviewData = [];
                $sourcedData = [];
                $total = [];
            }
            $data = [];
            $data['monthDate'] = $dates_month;
            $data['details'] = json_encode($details);
            return $data;
        }

        public function index()
        {
            $data = RunRateReport::initialise();
            echo view('runratereport', $data);
        }

        function dates_month($month, $year) {
            $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $dates_month = array();
            $saturdayCounter = 0;
            for ($i = 1; $i <= $num; $i++) {
                $mktime = mktime(0, 0, 0, $month, $i, $year);
                $weekday = date("w",$mktime);
                if($weekday == 6)
                {
                    $date = date("Y-m-d", $mktime);
                    $dates_month[$i] = $date;
                }
                else if($weekday > 0 && $weekday < 6)
                {
                    $date = date("Y-m-d", $mktime);
                    $dates_month[$i] = $date;
                }
            }
            return $dates_month;
        }

        public function GetDataOfMonth()
        {
            $db = \Config\Database::connect();
            $query2 = $db->table('my_users');
            $query2->select('user_id, full_name');
            $query2->where('level>',1);
            $users = $query2->get()->getResultArray();
            $date = $this->request->getVar("date");
            $dates_month = RunRateReport::dates_month(date("m", strtotime($date)), date("Y", strtotime($date)));
            $details = array();
            foreach($users as $keys=>$data)
            {
                $interviewData = array();
                $sourcedData = array();
                $total = array();
                $months = array();

                $total["RECRUITER_NAME"] = $data['full_name'];
                foreach($dates_month as $keys2=>$monthDate)
                {
                    $query = $db->table('candidates');
                    $query->select('candidates.INTERVIEW_DATE');
                    $query->where('candidates.RECRUITER', $data['user_id']);
                    $query->where('candidates.INTERVIEW_DATE>=', date('Y-m-d 00:00:00', strtotime($monthDate)));
                    $query->where('candidates.INTERVIEW_DATE<=', date('Y-m-d 23:59:59', strtotime($monthDate)));
                    $interviews = $query->countAllResults();
                    $interviewData[$keys2] = $interviews;
                    $query2 = $db->table('candidates');
                    $query2->select('*');
                    $query2->where('candidates.RECRUITER', $data['user_id']);
                    $query2->where('candidates.SUBMISSION_DATE>=', date('Y-m-d 00:00:00', strtotime($monthDate)));
                    $query2->where('candidates.SUBMISSION_DATE<=', date('Y-m-d 23:59:59', strtotime($monthDate)));
                    $sourced = $query2->countAllResults();
                    $sourcedData[$keys2] = $sourced;
                    array_push($months, $keys2);
                }
                $total["DATES"] = $months;
                $total["INTERVIEWS"] = $interviewData;
                $total["SOURCED"] = $sourcedData;
                array_push($details, $total);
                $interviewData = [];
                $sourcedData = [];
                $total = [];
            }
            echo json_encode($details);        
        }
    }
?>