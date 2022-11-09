<?php
    
    namespace App\Models;
    use CodeIgniter\Model;

    class CustomerModel extends Model{
        protected $table = 'customer';

        protected $allowedFields = [
            'CUSTOMER_ID',
            'CUSTOMER_NAME'

        ];        
    }
?>