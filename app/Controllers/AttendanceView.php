<?php

    namespace App\Controllers;
    use CodeIgniter\Controller;
    use App\Models\ClientModel;
    use App\Models\CustomerModel;
    use CodeIgniter\Files\File;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    class AttendanceView extends Controller
    {
        public function initialise()
        {
            $db = \Config\Database::connect();
            $query = $db->table('attendance');
            $query->select('*');
            $query = $query->get();
            $attendance = $query->getResultArray();
            $fieldNames = $query->getFieldNames();

            $query2 = $db->table('my_users');
            $query2->select('user_id,full_name');
            $users = $query2->get()->getResultArray();

            // // $clientModel = new ClientModel();
            // $customerModel = new CustomerModel();
            // $customers = $customerModel->orderBy('CUSTOMER_ID', 'DESC')->findAll();
            // // $attendance = $clientModel->orderBy('CLIENT_ID', 'DESC')->findAll();
            // // $fieldNames = $clientModel->allowedFields;
            $data = [];
            $data['fieldNames'] = $fieldNames;
            $data['users'] = $users;
            $data['attendance'] = $attendance;
            return $data;
        }
        public function index()
        {
            helper(['form']);
            $data = AttendanceView::initialise();
            echo view('attendanceview', $data);
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
                    $saturdayCounter++;
                    if($saturdayCounter % 2 != 0)
                    {
                        $date = date("Y-m-d", $mktime);
                        $dates_month[$i] = $date;            
                    }
                }
                else if($weekday > 0 && $weekday < 6)
                {
                    $date = date("Y-m-d", $mktime);
                    $dates_month[$i] = $date;
                }
            }    
            return $dates_month;
        }

        public function FetchAttendanceOfUser()
        {
            helper(['form']);
            $db = \Config\Database::connect();
            $session = session();
            $dates = array();
            $monthDates = array();
            $userID = (string) $session->get('user_id');
            $month = $this->request->getVar('month');
            $tz = 'Asia/Kolkata';
            $dt = new \DateTime(date($month), new \DateTimeZone($tz));
            $dt3 = new \DateTime(date($month), new \DateTimeZone($tz));
            $dt3->modify('+1 month');
            $query = $db->table('attendance');
            $query->select('ATTENDANCE_DATE, PRESENT, DEV_APPROVED');
            $query->where('recruiter_id',$userID);
            $query->where('ATTENDANCE_DATE>=',$dt->format('Y-m-d'));
            $query->where('ATTENDANCE_DATE<',$dt3->format('Y-m-d'));
            $details = $query->get()->getResultArray();
            foreach($details as $data)
            {
                $dates[] = $data['ATTENDANCE_DATE'];            
            }
            for($date = $dt; $date < $dt3; $date->modify('+1 day'))
            {
                $monthDates[] = $date->format('Y-m-d');
            }
            $datesToAdd = array_diff($monthDates, $dates);
            if($datesToAdd)
            {
                foreach($datesToAdd as $keys => $data)
                {
                    $details[] = array("ATTENDANCE_DATE"=>$data, "PRESENT" => "0", "DEV_APPROVED" => "0");
                }
            }
            foreach($details as $keys=>$data)
            {
                $leaveDate = new \DateTime(date($data["ATTENDANCE_DATE"]), new \DateTimeZone($tz));
                $leaves = $db->table('approval');
                $leaves->select('APPROVAL_STATUS');
                $leaves->where("FROM_DATE<=", $leaveDate->format("Y-m-d"));
                $leaves->where("TO_DATE>=", $leaveDate->format("Y-m-d"));
                $leaves->where("RECRUITER_ID", $userID);
                $details2 = $leaves->get()->getResultArray();
                $holidays = $db->table('holidays');
                $holidays->select('HOLIDAY_DATE');
                $holidays->where("HOLIDAY_DATE", $leaveDate->format("Y-m-d"));
                $details3 = $holidays->get()->getResultArray();
                if(count($details3) == 1)
                {
                    $details[$keys]['HOLIDAY']  = "1";                
                }
                else if(count($details2) == 1)
                {
                    if(strtolower($details2[0]['APPROVAL_STATUS']) == "processing")
                    {
                        $details[$keys]['LEAVE_APPLIED'] = "1";
                    }
                    else if(strtolower($details2[0]['APPROVAL_STATUS']) == "approved")
                    {
                        $details[$keys]['LEAVE_APPROVED'] = "1";
                    }                
                }
                else
                {
                    $candidatesCount = $db->table('candidates');
                    $candidatesCount->select('candidate_id');
                    $candidatesCount->where('SUBMISSION_DATE>=', $leaveDate->format("Y-m-d 00:00:00"));
                    $candidatesCount->where('SUBMISSION_DATE<=', $leaveDate->format("Y-m-d 23:59:59"));
                    $candidatesCount->where('RECRUITER', $userID);
                    $details4 = $candidatesCount->countAllResults();
                    if($details4 >=0)
                    {
                        $details[$keys]["SOURCED"] = (string) $details4;
                    }
                }
            }
            echo json_encode($details);
        }

    }
?>