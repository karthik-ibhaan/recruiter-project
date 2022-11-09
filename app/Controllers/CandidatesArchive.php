<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\UserModel;
use CodeIgniter\Files\File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CandidatesArchive extends Controller
{
    public function initialise()
    {
        $db = \Config\Database::connect();
        $candidatesArchive = $db->table('candidates_archive');
        $candidatesArchive->select('*');
        $candidatesArchive->orderBy('candidate_id','DESC');
        $candidatesArchive = $candidatesArchive->get()->getResultArray();
        $fieldNames = $db->getFieldNames('candidates_archive');
        $data['candidatesArchive'] = $candidatesArchive;
        $data['fieldNames'] = $fieldNames;
        return $data;
    }

    public function index()
    {
        helper(['form']);
        $data = CandidatesArchive::initialise();
        echo view('candidates_archive', $data);
    }

}

?>