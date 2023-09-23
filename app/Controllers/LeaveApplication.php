<?php

    namespace App\Controllers;
    use CodeIgniter\Controller;
    use CodeIgniter\Files\File;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    class LeaveApplication extends Controller
    {
        public function initialise()
        {
            $db = \Config\Database::connect();
            $session = session();
            $query = $db->table('approval');
            $query->select('APPROVAL_ID, FROM_DATE, TO_DATE, APPROVAL_STATUS');
            $query->where('RECRUITER_ID', $session->get('user_id'));
            $query = $query->get();
            $leaves = $query->getResultArray();
            $fieldNames = $query->getFieldNames();

            $data = [];
            $data['fieldNames'] = $fieldNames;
            $data['leaves'] = $leaves;
            return $data;
        }
        public function index()
        {
            helper(['form']);
            $data = LeaveApplication::initialise();
            echo view('leaveapplication', $data);
        }

        public function ApplyLeave()
        {
            helper(['form']);
            $db = \Config\Database::connect();
            $session = session();
            $user_id = $session->get('user_id');
            $from_date = $this->request->getVar('from_date');
            $to_date = $this->request->getVar('to_date');
            $comments = $this->request->getVar('comments');
            $saveData = [
                'FROM_DATE' => $from_date,
                'TO_DATE' => $to_date,
                'APPROVAL_STATUS' => "PROCESSING",
                'COMMENTS' => $comments,
                'RECRUITER_ID' => $user_id
            ];
            if(!$from_date || !$to_date)
            {
                $session->setFlashdata('error', "From Date or To Date is Missing. Please Try Again.");
                return redirect()->to('/leaveapplication');
            }
            else
            {
                $query = $db->table('approval');
                if($query->insert($saveData))
                {
                    $session->setFlashdata('success', "Leave Has Been Applied. Details Given Below.");
                    return redirect()->to('/leaveapplication');
                }
                else
                {
                    $session->setFlashdata('error', "Error Occured. Please Try Again.");
                    return redirect()->to('/leaveapplication');
                }
            }
        }

        public function CancelApplication()
        {
            helper(["form"]);
            $approvalID = $this->request->getVar("approval_id");
            $db = \Config\Database::connect();
            $session = session();
            $query = $db->table("approval");
            if($query->delete(["APPROVAL_ID" => $approvalID]))
            {
                $session->setFlashdata('success', "Leave Application Cancelled.");
                return redirect()->to('/leaveapplication');
            }
            else
            {
                $session->setFlashdata('error', "Error Occurred. Please Try Again.");
                return redirect()->to('/leaveapplication');
            }
        }
    }
?>