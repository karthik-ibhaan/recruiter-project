<?php

    namespace App\Controllers;
    use CodeIgniter\Controller;
    use App\Models\UserModel;
    use CodeIgniter\Files\File;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use App\Libraries\RecruitmentStatuses;

    class SelectionDetails extends Controller
    {
        public function index()
        {
            $db = \Config\Database::connect();
            $session = session();
            $tz = 'Asia/Kolkata';
            $timestamp = time();
            //Selections
            $recruitmentStatuses = new RecruitmentStatuses();
            $selections = $recruitmentStatuses->selectionData();
            //Selection Data
            $sqlquery = 'SELECT client.CLIENT_NAME, demand.CUS_SPOC, candidates.CANDIDATE_NAME, demand.JOB_TITLE, candidates.PHONE_NO, candidates.EMAIL_ADDRESS, candidates.TOTAL_EXPERIENCE, candidates.SELECTION_CTC, candidates.WORK_LOCATION, demand.LOCATION, my_users.FULL_NAME, candidates.SELECTION_DATE, candidates.RECRUITMENT_STATUS, candidates.PLANNED_DOJ, candidates.ACTUAL_DOJ, candidates.EXIT_DATE FROM candidates JOIN demand ON demand.demand_id = candidates.DEMAND_ID JOIN client ON client.CLIENT_ID = demand.CLIENT_ID JOIN my_users ON my_users.USER_ID = candidates.RECRUITER WHERE candidates.SELECTION_DATE IS NOT NULL AND candidates.SELECTION_DATE != \'0000-00-00\'';
            $query = $db->query($sqlquery);
            $selectionData = $query->getResultArray();
            $fieldNames = $query->getFieldNames();
            foreach($selectionData as $keys1=>$data)
            {
                foreach($data as $keys2=>$values)
                {
                    if($keys2 == "SELECTION_DATE")
                    {
                        $dt = new \DateTime($values, new \DateTimeZone($tz));
                        $selectionData[$keys1]['SELECTION_MONTH'] = $dt->format('F Y');
                    }
                }
            }
            array_unshift($fieldNames, 'SELECTION_MONTH');
            $data['selections'] = $selections;
            $data['selectionData'] = $selectionData;
            $data['fieldNames'] = $fieldNames;
            echo view('selectiondetails', $data);
        }
    }
?>