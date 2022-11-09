<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\DemandModel;
use App\Models\ClientModel;
use CodeIgniter\Files\File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DemandConfirmation extends Controller
{

    public function index()
    {
        helper(['form']);
        $data = [];
        echo view('demand_confirmation', $data);

    }
}