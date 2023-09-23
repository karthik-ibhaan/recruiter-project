<?php 
namespace App\Controllers;
use App\Models\FormModel;
use CodeIgniter\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class SendMail extends Controller
{
    public function index() 
	{
        $subject = "Daily Report Dated ".(string) date('d F Y', strtotime("today"));
        $date = date('d F Y', strtotime('today'));
        $message = "Dear recipient, \n\nPlease find the attachment below containing the Daily Report.";
        $fileName = SendMail::FileExport();
        $email = \Config\Services::email();
        $email->setTo('sushil@ibhaan-global.com, shiva@ibhaan-global.com, sangamesh@ibhaan-global.com');
        $email->setCc('karthik@ibhaan-global.com, info@ibhaan-global.com');
        $email->setFrom('karthik@ibhaan-global.com', 'Ibhaan Reports');
        $email->attach(WRITEPATH.'daily/'.$fileName);
        $email->setSubject($subject);
        $email->setMessage($message);
        if ($email->send())
		{
            echo 'Email successfully sent';
        }
		else 
		{
            $data = $email->printDebugger(['headers']);
            print_r($data);
        }
    }

    public function FileExport()
    {
        helper(['form']);
        helper('filesystem');

        $session = session();

        $header=true;
        $count = 0;
        $fileName = 'Daily Report '.'- '.date('d F Y', strtotime("today")).'.xlsx';
        $tz = "Asia/Kolkata";
        $dt = new \DateTime(date('Y-m-d'), new \DateTimeZone($tz));
        $dt2 = new \DateTime(date('Y-m-d'), new \DateTimeZone($tz));
        $dt2->modify('+1 day');
        $date = date('Y-m-d', strtotime("tomorrow"));
        $dt3 = new \DateTime($date, new \DateTimeZone($tz));
        $dt3->modify('+1 day');
        $db = \Config\Database::connect();
        $spreadsheet = new Spreadsheet();
        $db = \Config\Database::connect();
        $interviewQuery = $db->table('candidates');
        $interviewQuery->select('client.CLIENT_NAME, candidates.RECRUITER, my_users.FULL_NAME, candidates.CANDIDATE_NAME, demand.JOB_TITLE, candidates.WORK_LOCATION, demand.CUS_SPOC, candidates.PHONE_NO, candidates.EMAIL_ADDRESS, candidates.INTERVIEW_DATE, candidates.RECRUITMENT_STATUS');
        $interviewQuery->join('my_users','my_users.USER_ID = candidates.RECRUITER');
        $interviewQuery->join('demand','demand.DEMAND_ID = candidates.DEMAND_ID');
        $interviewQuery->join('client', 'client.CLIENT_ID = demand.CLIENT_ID');
        $interviewQuery->where('candidates.INTERVIEW_DATE>=',$dt2->format('Y-m-d 00:00:00'));
        $interviewQuery->where('candidates.INTERVIEW_DATE<',$dt3->format('Y-m-d 00:00:00'));
        $interview = $interviewQuery->get()->getResultArray();
        $interviewQuery2 = $db->table('candidates');
        $interviewQuery2->select('client.CLIENT_NAME, candidates.RECRUITER, my_users.FULL_NAME, candidates.CANDIDATE_NAME, demand.JOB_TITLE, candidates.WORK_LOCATION, demand.CUS_SPOC, candidates.PHONE_NO, candidates.EMAIL_ADDRESS, candidates.INTERVIEW_DATE, candidates.RECRUITMENT_STATUS');
        $interviewQuery2->join('my_users','my_users.USER_ID = candidates.RECRUITER');
        $interviewQuery2->join('demand','demand.DEMAND_ID = candidates.DEMAND_ID');
        $interviewQuery2->join('client', 'client.CLIENT_ID = demand.CLIENT_ID');
        $interviewQuery2->where('candidates.INTERVIEW_DATE>=',$dt->format('Y-m-d 00:00:00'));
        $interviewQuery2->where('candidates.INTERVIEW_DATE<',$dt2->format('Y-m-d 00:00:00'));
        $interview2 = $interviewQuery2->get()->getResultArray();
        foreach($interview as $keys=>$data2)
        {
            foreach($data2 as $keys2=>$value)
            {
                if($keys2 == "INTERVIEW_DATE")
                {
                    $dt2 = new \DateTime($value, new \DateTimeZone($tz));
                    $interview[$keys]['TIME'] = $dt2->format('H:i:s');
                    $interview[$keys]['INTERVIEW_DATE'] = $dt2->format('d F Y');
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
        foreach($interview2 as $keys=>$data2)
        {
            foreach($data2 as $keys2=>$value)
            {
                if($keys2 == "INTERVIEW_DATE")
                {
                    $dt2 = new \DateTime($value, new \DateTimeZone($tz));
                    $interview2[$keys]['TIME'] = $dt2->format('H:i:s');
                    $interview2[$keys]['INTERVIEW_DATE'] = $dt2->format('d F Y');
                }
                if($keys2 == "PHONE_NO")
                {
                    $value = json_decode($value);
                    if($value[1] != "")
                    {
                        $interview2[$keys][$keys2] = $value;
                    }
                    else
                    {
                        $interview2[$keys][$keys2] = $value[0];
                    }
                }
            }
        }

        $query2 = $db->table('my_users');
        $query2->select('user_id, full_name');
        $query2->where('level>',1);
        $users = $query2->get()->getResultArray();

        foreach($users as $keys=>$data)
        {
            $tz = "Asia/Kolkata";
            $dt = new \DateTime(date('Y-m-d'), new \DateTimeZone($tz));
            $dt2 = new \DateTime(date('Y-m-d'), new \DateTimeZone($tz));
            $dt2->modify('+1 day');    
            $builder = $db->table('candidates');
            $builder->select('client.CLIENT_NAME, demand.JOB_TITLE, candidates.CANDIDATE_NAME, candidates.RECRUITMENT_STATUS, candidates.INTERVIEW_DATE, candidates.PLANNED_DOJ, candidates.ACTUAL_DOJ, candidates.SUBMISSION_DATE');
            $builder->join('demand', 'demand.DEMAND_ID = candidates.DEMAND_ID');
            $builder->join('client', 'client.CLIENT_ID = demand.CLIENT_ID');
            $builder->join('my_users','my_users.USER_ID = candidates.RECRUITER');
            $builder->where('candidates.SUBMISSION_DATE>=',$dt->format('Y-m-d 00:00:00'));
            $builder->where('candidates.SUBMISSION_DATE<',$dt2->format('Y-m-d 00:00:00'));
            $builder->where('candidates.RECRUITMENT_STATUS!=','00_Sourcing');
            $builder->where('candidates.RECRUITER', $data['user_id']);
            $query = $builder->get();
            $candidates = $query->getResultArray();
            $fieldNames = $query->getFieldNames();
            $candidatesCount = $query->getNumRows();
            
            $worksheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $data['full_name']);
            $spreadsheet->addSheet($worksheet, 0);
            $sheet = $spreadsheet->getSheet(0);

            $col = "A";
            $row = "1";
            $sheet->setCellValue("$col$row", "TOTAL SOURCED PROFILES: ".$candidatesCount);

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
            $col = "A";
            foreach ($sheet->getColumnIterator() as $column) {
                $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
            }
        }

        $worksheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, "INTERVIEWS SCHEDULED TOMORROW");
        $spreadsheet->addSheet($worksheet, 0);
        $sheet = $spreadsheet->getSheet(0);
        $col = "A";
        $row = "1";
        
        $sheet->setCellValue("$col$row", ucwords("INTERVIEWS SCHEDULED FOR DATE: ".date("d F Y", strtotime("tomorrow"))));
        
        $col="A";
        $row="3";

        $i=0;
        foreach($interview as $keys=>$data)
        {
            for($i=0;$i<1;$i++)
            {
                $fieldNames = array_keys($data);
            }
        }

        foreach($fieldNames as $keys=>$values){
            $sheet->setCellValue("$col$row", ucwords($values));
            $col++;
        }

        $col="A";
        $row="4";

        foreach($interview as $keys=>$data){
            foreach($fieldNames as $keys2=>$value){
                $sheet->setCellValue("$col$row", $data[$value]);
                $col++;
            }
            $col="A";
            $row++;
        }
        foreach ($sheet->getColumnIterator() as $column) {
            $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }

        $worksheet2 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, "INTERVIEWS SCHEDULED TODAY");
        $spreadsheet->addSheet($worksheet2, 0);
        $sheet = $spreadsheet->getSheet(0);
        $col = "A";
        $row = "1";
        
        $sheet->setCellValue("$col$row", ucwords("INTERVIEWS SCHEDULED FOR DATE: ".date("d F Y", strtotime("today"))));
        
        $col="A";
        $row="3";

        $i=0;
        foreach($interview2 as $keys=>$data)
        {
            for($i=0;$i<1;$i++)
            {
                $fieldNames = array_keys($data);
            }
        }

        foreach($fieldNames as $keys=>$values){
            $sheet->setCellValue("$col$row", ucwords($values));
            $col++;
        }

        $col="A";
        $row="4";

        foreach($interview2 as $keys=>$data){
            foreach($fieldNames as $keys2=>$value){
                $sheet->setCellValue("$col$row", $data[$value]);
                $col++;
            }
            $col="A";
            $row++;
        }
        foreach ($sheet->getColumnIterator() as $column) {
            $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }

        $users = SendMail::GetDataofMonth();
        $worksheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, "OVERALL STATUS");
        $spreadsheet->addSheet($worksheet, 0);
        $sheet = $spreadsheet->getSheet(0);

        $col = "A";
        $row = "1";

        $sheet->setCellValue("$col$row", ucwords("OVERALL STATUS OF MONTH: ".date("F Y")));
        
        $col = "A";
        $row = "3";
        $sheet->setCellValue("$col$row", ucwords("RECRUITER NAME"));
        $col++;
        $sheet->setCellValue("$col$row", ucwords("TOTAL PROFILES SOURCED"));
        $col++;
        $sheet->setCellValue("$col$row", ucwords("FEEDBACK PENDING"));
        $col++;

        $col="A";
        $row="4";
        foreach($users as $keys=>$data)
        {
            $sheet->setCellValue("$col$row", ucwords($data['FULL_NAME']));
            $col++;
            $sheet->setCellValue("$col$row", ucwords($data['Total']));
            $col++;
            $sheet->setCellValue("$col$row", ucwords($data['Pending']));
            $col="A";
            $row++;
        }
        foreach ($sheet->getColumnIterator() as $column) {
            $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }

        $worksheetindex = $spreadsheet->getIndex($spreadsheet->getSheetByName('Worksheet'));
        $spreadsheet->removeSheetByIndex($worksheetindex);
        $writer = new Xlsx($spreadsheet);
        $fileLocation = WRITEPATH.'daily/'.$fileName;
        $writer->save($fileLocation);

        $fileData = file_get_contents($fileLocation);
        return $fileName;
    }

    public function GetDataofMonth()
    {
        $date = date('Y-m', strtotime("today"));
        $db = \Config\Database::connect();
        $tz = 'Asia/Kolkata';
        $dt = new \DateTime($date, new \DateTimeZone($tz));
        
        $dt2 = new \DateTime($date, new \DateTimeZone($tz));
        $dt2->modify('+1 month');
        $builder2 = $db->table('my_users');
        $builder2->select('my_users.FULL_NAME,my_users.USER_ID');
        $builder2->where('level>', 1);
        $users = $builder2->get()->getResultArray();
        $statuses = ['00_Sourcing','00_Profile Sent','00_CV sent to client','00_No Feedback from client','02_Feedback Pending'];
        foreach($users as $keys=>$data)
        {
            foreach($data as $keys2=>$value) 
            {
                $builder = $db->table('candidates');
                $builder->select('candidates.CANDIDATE_ID, candidates.RECRUITER');
                $builder->where('candidates.SUBMISSION_DATE>=',$dt->format('Y-m-d h:i:s'));
                $builder->where('candidates.SUBMISSION_DATE<',$dt2->format('Y-m-d h:i:s'));
                $builder->where('candidates.RECRUITER',$data['USER_ID']);
                $candidatesTotal = $builder->countAllResults();
                $users[$keys]['Total'] = $candidatesTotal;
            }
        }
        foreach($users as $keys=>$data)
        {
            foreach($data as $keys2=>$value)
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
        }
        return $users;
    }
}
?>