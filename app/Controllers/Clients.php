<?php

    namespace App\Controllers;
    use CodeIgniter\Controller;
    use App\Models\ClientModel;
    use App\Models\CustomerModel;
    use CodeIgniter\Files\File;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    class Clients extends Controller
    {
        public function initialise()
        {
            $db = \Config\Database::connect();
            $query = $db->table('client');
            $query->select('client.*, customer.CUSTOMER_NAME');
            $query->join('customer', 'customer.customer_id = client.customer_id');
            $query = $query->get();
            $clients = $query->getResultArray();
            $fieldNames = $query->getFieldNames();

            // $clientModel = new ClientModel();
            $customerModel = new CustomerModel();
            $customers = $customerModel->orderBy('CUSTOMER_ID', 'DESC')->findAll();
            // $clients = $clientModel->orderBy('CLIENT_ID', 'DESC')->findAll();
            // $fieldNames = $clientModel->allowedFields;
            $data = [];
            $data['fieldNames'] = $fieldNames;
            $data['clients'] = $clients;
            $data['customers'] = $customers;
            return $data;
        }
        public function index()
        {
            helper(['form']);
            $data = Clients::initialise();
            echo view('clients', $data);
        }

        public function AddClient()
        {
            helper(['form']);
            $header=true;
            $count = 0;

            $session = session();
            $clientModel = new ClientModel();
            $customerModel = new CustomerModel();
            $clientName = $this->request->getVar('client_name');
            $customerName = (int) $this->request->getVar('customer_name');

            //Validation
            if(strlen($clientName) <= 2)
            {
                $session->setFlashdata('error', 'Client Name too small.');
                return redirect()->to('/clients');
            }
            if(!$customerName)
            {
                $session->setFlashdata('error', 'Customer Name not Selected');
                return redirect()->to('/clients');
            }
            //Saving To Table
            else
            {
                $clientModel = new ClientModel();
                $data = [
                    'CLIENT_NAME' => $clientName,
                    'CUSTOMER_ID' => $customerName
                ];

                $clientModel->save($data);
                $session->setFlashdata('success', 'User has been added to the database.');
            }
            return redirect()->to('/clients');
        }
        public function EditClient()
        {
            $session = session();
            $clientModel = new ClientModel();
            $clientID = (int) $this->request->getVar('client_id');
            $clientName = $this->request->getVar('client_name');
            $customerID = $this->request->getVar('customer_name');
            //Validation
            if(strlen($clientName) <= 2)
            {
                $session->setFlashdata('error', 'Client Name too small.');
                return redirect()->to('/clients');
            }
            if(!$customerID)
            {
                $session->setFlashdata('error', 'Customer Name not Selected');
                return redirect()->to('/clients');
            }
            //Saving To Table
            else
            {
                $clientModel = new ClientModel();
                $data = [
                    'CLIENT_NAME' => $clientName,
                    'CUSTOMER_ID' => $customerID
                ];
                $clientModel->update($clientID, $data);
                $session->setFlashdata('success', 'User has been added to the database.');
            }
            return redirect()->to('/clients');
        }

        public function DeleteClient()
        {
            $client_id = (int) $this->request->getVar('client_id');
            $clientModel = new ClientModel();
            $clientModel->delete($client_id);

            return redirect()->to('/clients');
        }
        public function FileExport()
        {
            helper(['form']);
            helper('filesystem');

            $session = session();

            $header=true;
            $count = 0;

            $clientModel = new ClientModel();
            $clients = $clientModel->orderBy('client_id', 'DESC')->findAll();
            $fieldNames = $clientModel->allowedFields;
            $fileName = 'clients.xlsx';
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $col = "A";
            $row = "1";
            foreach($fieldNames as $keys=>$values){
                $sheet->setCellValue("$col$row", ucwords($values));
                $col++;
            }

            $col = "A";
            $row = "2";
            foreach($clients as $keys=>$data){
                foreach($fieldNames as $keys=>$value){
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
            return redirect()->to('/clients');
        }

    }
?>