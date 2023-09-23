<?php

    namespace App\Controllers;
    use CodeIgniter\Controller;
    use App\Models\DemandModel;
    use App\Models\ClientModel;
    use CodeIgniter\Files\File;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    class PriorityChange extends Controller
    {
        public function index()
        {
            helper(['form']);
            $data = PriorityChange::initialise();
            echo view('prioritychange');
        }

        public function initialise()
        {
            $db = \Config\Database::connect();
            $previous5Days = date("Y-m-d", strtotime("-4 weekdays"));
            print_r($previous5Days);
            $demandModel = new DemandModel();
            $demandsArray = $demandModel->where('SUBMISSION_DATE <', $previous5Days)->where('SUBMISSION_DATE != ', null)->where('PRIORITY', 'New')->findColumn('DEMAND_ID');

            if($demandsArray)
            {
                if(count($demandsArray) >= 1)
                {
                    $builder = $db->table('demand');
                    $builder->whereIn('demand.DEMAND_ID', $demandsArray);
                    if($builder->update(['PRIORITY' => '']))
                    {
                        echo "UPDATED!";
                    }
                }    
            }
        }
    }
?>