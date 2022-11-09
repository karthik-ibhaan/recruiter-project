<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\DemandModel;
use CodeIgniter\Files\File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Demands extends Controller
{
    public function index()
    {
        helper(['form']);
        $data = [];
        echo view('demandform', $data);
    }
}