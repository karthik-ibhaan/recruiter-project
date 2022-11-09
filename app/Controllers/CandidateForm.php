<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\CandidateModel;
use CodeIgniter\Files\File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CandidateForm extends Controller
{
    public function index()
    {
        helper(['form']);
        $data = [];
        $submissionDate = '';
        $data['submissionDate'] = $submissionDate;
        echo view('candidateform', $data);
    }

    public function AddCandidate(){
        helper(['form']);
        $data = [];
        $candidateID = $this->request->getVar('candidateID');
        $customerName = $this->request->getVar('customerName');
        $clientName = $this->request->getVar('clientName');
        $JTitle = $this->request->getVar('JTitle');
        $recStatus = $this->request->getVar('rec_status');
        $submissionDate = $this->request->getVar('submissionDate');
        $interviewDate = $this->request->getVar('candidateName');
        $email = $this->request->getVar('emailAdd');
        $workLocation = $this->request->getVar('workLocation');
        $totExp = $this->request->getVar('totExp');
        $CCTC = $this->request->getVar('CCTC');
        $ECTC = $this->request->getVar('ECTC');
        $NP = $this->request->getVar('NP');
        $plannedDOJ = $this->request->getVar('plannedDOJ');
        $actualDOJ = $this->request->getVar('actualDOJ');
        echo view('candidateform', $data);
    }
}
?>