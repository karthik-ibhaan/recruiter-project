<?php 
namespace App\Controllers;
use App\Models\FormModel;
use CodeIgniter\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class WeeklyMail extends Controller
{
    public function index() 
	{
        $subject = "Weekly Report of Week ".(string) date('d F Y', strtotime("last monday")).' - '.(string) date('d F Y', strtotime("friday"));
        $message = "Dear recipient, \n\nPlease find the attachment below containing the Weekly Report.";
        $fileName = WeeklyMail::FileExport();
        $email = \Config\Services::email();
        $email->setTo('sushil@ibhaan-global.com, shiva@ibhaan-global.com, sangamesh@ibhaan-global.com');
        $email->setCc('karthik@ibhaan-global.com, info@ibhaan-global.com');
        $email->setFrom('karthik@ibhaan-global.com', 'Ibhaan Reports');
        $email->attach(WRITEPATH.'weekly/'.$fileName);
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
        $fileName = 'Weekly Report '.'- '.date('d F Y', strtotime("last monday")).' to '.date('d F Y', strtotime("friday")).'.xlsx';
        $tz = "Asia/Kolkata";
        $dt = new \DateTime(date('Y-m-d', strtotime("last monday")), new \DateTimeZone($tz));
        $dt2 = new \DateTime(date('Y-m-d', strtotime("friday")), new \DateTimeZone($tz));
        $dt2->modify('+1 day');
        $db = \Config\Database::connect();
        $query2 = $db->table('my_users');
        $query2->select('user_id, full_name');
        $query2->where('level>',1);
        $users = $query2->get()->getResultArray();
        $spreadsheet = new Spreadsheet();
        foreach($users as $keys=>$data)
        {
            $builder = $db->table('candidates');
            $builder->select('client.CLIENT_NAME, demand.JOB_TITLE, candidates.CANDIDATE_NAME, candidates.RECRUITMENT_STATUS, candidates.INTERVIEW_DATE, candidates.PLANNED_DOJ, candidates.ACTUAL_DOJ, candidates.SUBMISSION_DATE');
            $builder->join('demand', 'demand.DEMAND_ID = candidates.DEMAND_ID');
            $builder->join('client', 'client.CLIENT_ID = demand.CLIENT_ID');
            $builder->join('my_users','my_users.USER_ID = candidates.RECRUITER');
            $builder->where('candidates.SUBMISSION_DATE>=',$dt->format('Y-m-d 00:00:00'));
            $builder->where('candidates.SUBMISSION_DATE<',$dt2->format('Y-m-d 00:00:00'));
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
            foreach ($sheet->getColumnIterator() as $column) {
                $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
            }
        }
        $worksheetindex = $spreadsheet->getIndex($spreadsheet->getSheetByName('Worksheet'));
        $spreadsheet->removeSheetByIndex($worksheetindex);
        $writer = new Xlsx($spreadsheet);
        $fileLocation = WRITEPATH.'weekly/'.$fileName;
        $writer->save($fileLocation);

        $fileData = file_get_contents($fileLocation);
        return $fileName;
    }
}
?>