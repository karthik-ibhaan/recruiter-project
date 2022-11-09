<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\DemandModel;
use App\Models\ClientModel;
use CodeIgniter\Files\File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DemandsView extends Controller
{

    public function initialise()
    {
        $action = '';
        $header = true;
        $clientModel = new ClientModel();
        $demandModel = new DemandModel();
        $industrySegment = ['Aero Engine','Aerostructure','Automotive','Avionics','Consumer Durables','Defence','Industrial Automation','Information Technology','Locomotive','Marine','Medical Devices','Renewable Energy','Survelience','Telecom'];
        $status = ['Open','Closed','Hold'];
        $domain['E&E Development'] = ['Analysis','Circuit Simulation','Embed HW +DDS +MSD','EMI / EMC','Firmware','FPGA','FPGA / RTL Design','Hardware','Layout','Mechanical','Middleware','PCB Layout design','Power Management','Power Supply','RF','Signal & Power Integrity','Software'];
        $domain['E&E Manufacturing'] = ['Box build', 'PCBA - Production', 'PCBA - Proto', 'Power Chords', 'Solar Solutions', 'Transformers', 'Turnkey Mfg', 'UPS', 'Wire Harness'];
        $domain['Information Technology'] = ['Back-End Development', 'Blockchain Development', 'Data Analysis','Front-End Development', 'Java Development', 'Machine Learning', 'Mobile App Development', 'Python Development', 'Testing'];
        $domain['Mechanical'] = ['Cost Engineering', 'Design', 'Manufacturing', 'Quality Assurance', 'Quality Management', 'Supply Chain Management'];
        $priority = ['High'];
        $complexity = ['low', 'medium', 'high'];
        $location = [];
        $path = WRITEPATH . 'uploads/' . 'locations.csv';
        $handle = fopen($path, "r");

        while (($details = fgetcsv($handle, 1000, ",")) !== FALSE)
        {
            if($header == true)
            {
                $header = false;
                continue;
            }

            if($details[0] != NULL)
            {
                array_push($location, $details[0]);
            }
        }

        sort($location);
        // $customers = $customerModel->orderBy('CUSTOMER_ID', 'DESC')->findAll();

        // $builder = $db->table('demand');
        // $builder->select('*');
        // $builder->join('client', 'client.CLIENT_ID = demand.client_id');
        // $demands = $builder->get()->getResult();
        $db = \Config\Database::connect();
        $options = [];
        $builder = $db->table('demand');
        $builder->select('client.CLIENT_NAME, demand.*');
        $builder->join('client', 'demand.client_id = client.client_id');
        $query = $builder->get();
        $demands = $query->getResultArray();
        $fieldNames = $query->getFieldNames();

        $clients = $clientModel->orderBy('CLIENT_ID', 'DESC')->findAll();
        foreach($fieldNames as $keys=>$values)
        {
            if($values == "CLIENT_NAME")
            {
                $sql = 'SELECT DISTINCT '.$values.' FROM '.$clientModel->table.';';
                $query = $db->query($sql);
                array_push($options, $query->getResultArray());
            }
            else
            {
                $sql = 'SELECT DISTINCT '.$values.' FROM '.$demandModel->table.';';
                $query = $db->query($sql);
                array_push($options, $query->getResultArray());
            }
        }
        $data = [];
        $data['fieldNames'] = $fieldNames;
        $data['options'] = $options;
        $data['demands'] = $demands;
        $data['clients'] = $clients;
        // $data['customers'] = $customers;
        $data['industry'] = $industrySegment;
        $data['domain'] = $domain;
        $data['priority'] = $priority;
        $data['complexity'] = $complexity;
        $data['status'] = $status;
        $data['location'] = $location;
        return $data;
    }

    public function index()
    {
        helper(['form']);
        $data = DemandsView::initialise();
        echo view('demandsview', $data);
    }
}

?>