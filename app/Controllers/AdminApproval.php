<?php

    namespace App\Controllers;
    use CodeIgniter\Controller;
    use CodeIgniter\Files\File;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    class AdminApproval extends Controller
    {
        public function initialise()
        {
            $db = \Config\Database::connect();
            $session = session();
            $query = $db->table('approval');
            $query->select('approval.APPROVAL_ID, approval.RECRUITER_ID, approval.FROM_DATE, approval.COMMENTS, approval.TO_DATE, approval.APPROVAL_STATUS, my_users.FULL_NAME');
            $query->join('my_users', 'my_users.USER_ID = approval.RECRUITER_ID');
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
            $data = adminapproval::initialise();
            echo view('adminapproval', $data);
        }

        public function ApproveLeave()
        {
            helper(['form']);
            $db = \Config\Database::connect();
            $session = session();
            $approvalID = $this->request->getVar('approval_id');
            $saveData = [
                'APPROVAL_STATUS' => "APPROVED"
            ];
            $query = $db->table('approval');
            $query->set($saveData);
            $query->where("APPROVAL_ID", $approvalID);
            if($query->update())
            {
                $session->setFlashdata('success', "Leave Has Been Approved.");
                return redirect()->to('/adminapproval');
            }
            else
            {
                $session->setFlashdata('error', "Error Occured. Please Try Again.");
                return redirect()->to('/adminapproval');
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
                return redirect()->to('/adminapproval');
            }
            else
            {
                $session->setFlashdata('error', "Error Occurred. Please Try Again.");
                return redirect()->to('/adminapproval');
            }
        }
    }
?>