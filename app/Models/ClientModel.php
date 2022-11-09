<?php
    
    namespace App\Models;
    use CodeIgniter\Model;

    class ClientModel extends Model{
        protected $table = 'client';
        protected $primaryKey = 'client_id';

        protected $allowedFields = [
            'CLIENT_ID',
            'CLIENT_NAME',
            'CUSTOMER_ID'
        ];

        protected $validationRules = [
            'CLIENT_NAME' => 'required',
            'CUSTOMER_ID' => 'required|is_natural'
        ];
    }
?>