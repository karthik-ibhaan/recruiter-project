<?php

    namespace App\Controllers;
    use CodeIgniter\Controller;
    use App\Models\ClientModel;
    use App\Models\UserModel;
    use App\Models\CustomerModel;
    use CodeIgniter\Files\File;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    class Attendance extends Controller
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
            $query2->where('level>',1);
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
            $data = Attendance::initialise();
            echo view('attendance', $data);
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

        function dates_month2($month, $year) {
            $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $dates_month = array();
            $saturdayCounter = 0;
            for ($i = 1; $i <= $num; $i++) {
                $mktime = mktime(0, 0, 0, $month, $i, $year);
                $date = date("Y-m-d", $mktime);
                $dates_month[$i] = $date;
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
            $userID = (string) $this->request->getVar("user");
            $month = $this->request->getVar('month');
            $tz = 'Asia/Kolkata';
            $dt = new \DateTime(date($month), new \DateTimeZone($tz));
            $dt3 = new \DateTime(date($month), new \DateTimeZone($tz));
            $dt3->modify('+1 month');
            $query = $db->table('attendance');
            $query->select('ATTENDANCE_DATE, PRESENT, DEV_APPROVED');
            $query->where('RECRUITER_ID', $userID);
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
                    $details[] = array("ATTENDANCE_DATE"=>$data, "PRESENT" => "0", "DEV_APPROVED"=> "0");
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
                    $candidatesCount->select('CANDIDATE_ID');
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
        
        public function ApproveMultipleDeviations()
        {
            helper(['form']);
            $approveMultiple = $this->request->getVar("approvalMultipleSelect");
            $recruiterID = $this->request->getVar("approvalRecruiter2");
            print_r($approveMultiple);
            $tz = "Asia/Kolkata";
            $session = session();
            $approvalID = (string) $session->get('user_id');
            $db = \Config\Database::connect();
            foreach($approveMultiple as $keys=>$data)
            {
                $date = new \DateTime(date($data), new \DateTimeZone($tz));
                $query = $db->table('attendance');
                $query->select('attendance_id');
                $query->where('attendance_date', $date->format('Y-m-d'));
                $query->where('recruiter_id', $recruiterID);
                $attendance = $query->get()->getResultArray();
                if(count($attendance) != 0)
                {
                    $saveData = [
                        "DEV_APPROVED" => 1,
                        "APPROVED_BY" => $approvalID
                    ];
                    $query2 = $db->table('attendance');
                    $query2->set($saveData);
                    $query2->where('attendance_id',$attendance[0]['attendance_id']);
                    if($query2->update())
                    {
                    }
                    else
                    {
                        $session->setFlashdata('error', "Error Occurred. Please Try Again.");
                        return redirect()->to('attendance');
                    }
                }
                else if(!$attendance)
                {
                    $saveData = [
                        "DEV_APPROVED" => 1,
                        "APPROVED_BY" => $approvalID,
                        "ATTENDANCE_DATE" => $date->format("Y-m-d"),
                        "RECRUITER_ID" => $recruiterID,
                        "PRESENT" => 0
                    ];
                    $query2 = $db->table('attendance');
                    if($query2->insert($saveData))
                    {
                    }
                    else
                    {
                        $session->setFlashdata('error', "Error Occurred. Please Try Again.");
                        return redirect()->to('attendance');
                    }
                }
            }
            $session->setFlashdata('success', "Deviation on Dates Approved for User ID: ".$recruiterID);
            return redirect()->to('attendance');
        }

        public function ApproveDeviation()
        {
            helper(['form']);
            $recruiterID = $this->request->getVar('approvalRecruiter');
            $approvalDate = $this->request->getVar('approvalDate');
            $tz = "Asia/Kolkata";
            $session = session();
            $approvalID = (string) $session->get('user_id');
            $db = \Config\Database::connect();
            $date = new \DateTime(date($approvalDate), new \DateTimeZone($tz));
            $query = $db->table('attendance');
            $query->select('attendance_id');
            $query->where('attendance_date', $date->format('Y-m-d'));
            $query->where('recruiter_id', $recruiterID);
            $attendance = $query->get()->getResultArray();
            if(count($attendance) != 0)
            {
                $saveData = [
                    "DEV_APPROVED" => 1,
                    "APPROVED_BY" => $approvalID
                ];
                $query2 = $db->table('attendance');
                $query2->set($saveData);
                $query2->where('attendance_id',$attendance[0]['attendance_id']);
                if($query2->update())
                {
                    $session->setFlashdata('success', "Deviation on Date: ".$date->format("Y-m-d")." Approved for User ID: ".$recruiterID);
                    return redirect()->to('attendance');
                }
                else
                {
                    $session->setFlashdata('error', "Error Occurred. Please Try Again.");
                    return redirect()->to('attendance');
                }
            }
            else if(!$attendance)
            {
                $saveData = [
                    "DEV_APPROVED" => 1,
                    "APPROVED_BY" => $approvalID,
                    "ATTENDANCE_DATE" => $date->format("Y-m-d"),
                    "RECRUITER_ID" => $recruiterID,
                    "PRESENT" => 0
                ];
                $query2 = $db->table('attendance');
                if($query2->insert($saveData))
                {
                    $session->setFlashdata('success', "Deviation on Date: ".$date->format("Y-m-d")." Approved for User ID: ".$recruiterID);
                    return redirect()->to('attendance');
                }
                else
                {
                    $session->setFlashdata('error', "Error Occurred. Please Try Again.");
                    return redirect()->to('attendance');
                }
            }
        }

        public function FileExport()
        {
            helper(['form']);
            helper('filesystem');

            $session = session();

            $header=true;
            $count = 0;
            $db = \Config\Database::connect();

            $userModel = new UserModel();
            $users2 = $userModel->orderBy('user_id', 'DESC')->where('level>',1)->findColumn('user_id');
            $month = (string) $this->request->getVar('monthYear');
            $dates_month = Attendance::dates_month(date("m", strtotime($month)), date("Y", strtotime($month)));
            $dates_month2 = Attendance::dates_month2(date("m", strtotime($month)), date("Y", strtotime($month)));
            $attendance = array();
            $holidays = $db->table('holidays');
            $holidays->select('HOLIDAY_DATE');
            $holidays->where('HOLIDAY_DATE>=', date('01-m-d', strtotime("this month")));
            $holidays->where('HOLIDAY_DATE<', date('01-m-d', strtotime("next month")));
            $holidaysCount = $holidays->countAllResults();
            $i = 0;
            foreach($users2 as $keys => $values)
            {
                $userName = $userModel->where('user_id', $values)->findColumn('full_name');
                $present = 0;
                $absent = 0;
                foreach($dates_month as $keys=>$data)
                {
                    $query4 = $db->table('attendance');
                    $query4->select('*');
                    $query4->where('attendance_date', $data);
                    $query4->where('recruiter_id',$values);
                    $query4->where('present', 1);
                    $presentDays = $query4->countAllResults();
                    if($presentDays != 1)
                    {
                        $absent = $absent + 1;
                    }
                    else if($presentDays == 1)
                    {
                        $present = $present + 1;
                    }
                }
                $attendance[$i] = [
                    "NAME" => $userName[0],
                    "DAYS_WORKED" => $present,
                    "LOP DAYS" => "0",
                    "DAYS_NOT_WORKED"=>$absent
                ];
                $i++;
                $present = 0;
                $absent = 0;
            }

            $fileName = 'attendance.xlsx';
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $col = "A";
            $row = "1";
            
            $sheet->setCellValue("$col$row",ucwords("ibhaan-Global attendance report as on ".date("d F Y", strtotime("today"))));
            $row = "2";
            $col = "A";
            $sheet->setCellvalue("$col$row", ucwords("Total No. Of Days"));
            $col++;
            $sheet->setCellValue("$col$row", ucwords(count($dates_month2)));
            $col="A";
            $row="3";
            $sheet->setCellvalue("$col$row", ucwords("Holidays"));
            $col++;
            $sheet->setCellValue("$col$row", ucwords($holidaysCount));
            $col="A";
            $row="4";
            $sheet->setCellValue("$col$row", ucwords("No. Of Non Working Days"));
            $col++;
            $sheet->setCellValue("$col$row", ucwords(count($dates_month2)-count($dates_month)));        
            $col="A";
            $row="5";
            $sheet->setCellvalue("$col$row", ucwords("Total No. Of Working Days"));
            $col++;
            $sheet->setCellValue("$col$row", ucwords(count($dates_month)));
            
            $col = "A";
            $row = "7";
            
            $sheet->setCellValue("$col$row", ucwords("EMPLOYEE NAME"));
            $col++;
            $sheet->setCellValue("$col$row", ucwords("PRESENT DAYS"));
            $col++;
            $sheet->setCellValue("$col$row", ucwords("LOP DAYS"));
            $col++;
            $sheet->setCellValue("$col$row", ucwords("ABSENT DAYS"));
            $col++;

            $col = "A";
            $row = "8";
            foreach($attendance as $keys=>$data){
                foreach($data as $keys=>$value){
                    $sheet->setCellValue("$col$row", $value);
                    $col++;
                }
                $col="A";
                $row++;
            }
            
            foreach ($sheet->getColumnIterator() as $column) {
                $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
            }

            $writer = new Xlsx($spreadsheet);
            $writer->save($fileName);
            $fileLocation = ROOTPATH."/public/".$fileName;

            $fileData = file_get_contents($fileLocation);
            return $this->response->download($fileLocation,null)->setFileName($fileName);
            return redirect()->to('/attendance');
        }
    }
?>