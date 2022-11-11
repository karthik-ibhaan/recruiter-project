<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\DemandModel;
use App\Models\ClientModel;
use CodeIgniter\Files\File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Demands extends Controller
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
        $data = Demands::initialise();
        echo view('demands', $data);
    }

    public function FileExport()
    {
        helper(['form']);
        helper('filesystem');

        $session = session();

        $header=true;
        $count = 0;

        $demandModel = new DemandModel();
        $demands = $demandModel->orderBy('demand_id', 'DESC')->findAll();
        $demandFields = $demandModel->allowedFields;
        $fileName = 'demands.xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $col = "A";
        $row = "1";
        foreach($demandFields as $keys=>$values){
            $sheet->setCellValue("$col$row", ucwords($values));
            $col++;
        }

        $col = "A";
        $row = "2";
        foreach($demands as $keys=>$data){
            foreach($demandFields as $keys=>$value){
                $sheet->setCellValue("$col$row", $data[$value]);
                $col++;
            }
            $col="A";
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($fileName);
        $fileLocation = ROOTPATH."/public/".$fileName;

        $fileData = file_get_contents($fileLocation);
        return $this->response->download($fileLocation,null)->setFileName($fileName);
        return redirect()->to('/demands');
    }

    public function DeleteDemand() {
        helper(['form']);
        $demandModel = new DemandModel();
        $demand_id = (int) $this->request->getVar('demand_id3');
        echo $demand_id;
        $demandModel->delete($demand_id);
    }
    public function AddDemand() {
        helper(['form']);
        $demandModel = new DemandModel();
        $session = session();
        $client = $this->request->getVar('client');
        $jd_id = $this->request->getVar('jd_id');
        $demand_status = $this->request->getVar('demand_status');
        $priority = $this->request->getVar('priority');
        $complexity = $this->request->getVar('complexity');
        $no_positions = $this->request->getVar('no_positions');
        $cus_spoc = $this->request->getVar('cus_spoc');
        $ibhaan_spoc = $this->request->getVar('ibhaan_spoc');
        $industry = $this->request->getVar('industry');
        $domain = $this->request->getVar('domain');
        $id = str_replace("&", "", $domain);
        $id = str_replace(" ", "", $id);
        $skill = $this->request->getVar((string) $id);
        $band = $this->request->getVar('band');
        $min_experience = $this->request->getVar('min_experience');
        $max_experience = $this->request->getVar('max_experience');
        if($max_experience != "")
        {
            $experience = $min_experience." - ".$max_experience." Years";
        }
        else
        {
            $experience = $min_experience." Years";
        }
        $min_budget = $this->request->getVar('min_budget');
        $max_budget = $this->request->getVar('max_budget');
        if($max_budget != "")
        {
            $budget = $min_budget." - ".$max_budget." LPA";
        }
        else
        {
            $budget = $min_budget." LPA";
        }
        $location = $this->request->getVar('location');
        $j_title = $this->request->getVar('j_title');
        $p_skills = $this->request->getVar('p_skills');
        $s_skills = $this->request->getVar('s_skills');
        $recruiter = $this->request->getVar('recruiter');
        $data = [
            'CLIENT_ID' => $client,
            'JD_ID' => $jd_id,
            'DEMAND_STATUS' => $demand_status,
            'PRIORITY' => $priority,
            'COMPLEXITY' => $complexity,
            'NO_POSITIONS' => $no_positions,
            'CUS_SPOC' => $cus_spoc,
            'IBHAAN_SPOC' => $ibhaan_spoc,
            'INDUSTRY_SEGMENT' => $industry,
            'DOMAIN' => $domain,
            'SKILL' => $skill,
            'BAND' => $band,
            'MIN_EXPERIENCE' => $min_experience,
            'MAX_EXPERIENCE' => $max_experience,
            'MIN_BUDGET' => $min_budget,
            'MAX_BUDGET' => $max_budget,
            'LOCATION' => $location,
            'JOB_TITLE' => $j_title,
            'PRIMARY_SKILL' => $p_skills,
            'SECONDARY_SKILL' => $s_skills,
            'RECRUITER' => $recruiter
        ];

        if($demandModel->save($data))
        {
            $session->setFlashdata('updated','Demand'.$j_title.'has been updated'.'by'.$recruiter);
            return redirect()->to('demands');
        }
        else
        {
            $session->setFlashdata('error','Insufficient Details Provided');
            return redirect()->to('demands');
        }
    }

    public function GetDemand()
    {
        helper(['form']);
        $demandModel = new DemandModel();
        $demandID = $this->request->getVar('demand_id');
        $demand = $demandModel->find($demandID);
        echo json_encode($demand);
    }

    public function EditDemand()
    {
        helper(['form']);
        $demandModel = new DemandModel();
        $session = session();
        $demand = $this->request->getVar('demand_id2');
        $client = $this->request->getVar('client2');
        $jd_id = $this->request->getVar('jd_id2');
        $demand_status = $this->request->getVar('demand_status2');
        $priority = $this->request->getVar('priority2');
        $complexity = $this->request->getVar('complexity2');
        $no_positions = $this->request->getVar('no_positions2');
        $cus_spoc = $this->request->getVar('cus_spoc2');
        $ibhaan_spoc = $this->request->getVar('ibhaan_spoc2');
        $industry = $this->request->getVar('industry2');
        $domain = $this->request->getVar('domain2');
        $id = str_replace("&", "", $domain);
        $id = str_replace(" ", "", $id);
        $skill = $this->request->getVar((string) $id);
        $band = $this->request->getVar('band');
        $min_experience = $this->request->getVar('min_experience2');
        $max_experience = $this->request->getVar('max_experience2');
        if($max_experience != "")
        {
            $experience = $min_experience." - ".$max_experience." Years";
        }
        else
        {
            $experience = $min_experience." Years";
        }
        $min_budget = $this->request->getVar('min_budget2');
        $max_budget = $this->request->getVar('max_budget2');
        if($max_budget != "")
        {
            $budget = $min_budget." - ".$max_budget." LPA";
        }
        else
        {
            $budget = $min_budget." LPA";
        }
        $location = $this->request->getVar('location2');
        $j_title = $this->request->getVar('j_title2');
        $p_skills = $this->request->getVar('p_skills2');
        $s_skills = $this->request->getVar('s_skills2');
        $recruiter = $this->request->getVar('recruiter2');
        $data = [
            'CLIENT_ID' => $client,
            'JD_ID' => $jd_id,
            'DEMAND_STATUS' => $demand_status,
            'PRIORITY' => $priority,
            'COMPLEXITY' => $complexity,
            'NO_POSITIONS' => $no_positions,
            'CUS_SPOC' => $cus_spoc,
            'IBHAAN_SPOC' => $ibhaan_spoc,
            'BAND' => $band,
            'MIN_EXPERIENCE' => $min_experience,
            'MAX_EXPERIENCE' => $max_experience,
            'MIN_BUDGET' => $min_budget,
            'MAX_BUDGET' => $max_budget,
            'LOCATION' => $location,
            'JOB_TITLE' => $j_title,
            'PRIMARY_SKILL' => $p_skills,
            'SECONDARY_SKILL' => $s_skills,
            'RECRUITER' => $recruiter
        ];

        if($demandModel->update($demand, $data))
        {
            $session->setFlashdata('updated','Demand'.$j_title.'has been updated'.'by'.$recruiter);
            return redirect()->to('demands');
        }
        else
        {
            $session->setFlashdata('error','Insufficient Details Provided');
            return redirect()->to('demands');
        }
    }

    public function Filter()
    {
        $j_title = $this->request->getVar('j_title');

    }
}
?>