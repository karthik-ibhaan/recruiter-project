<?php
    
    namespace App\Models;
    use CodeIgniter\Model;

    class UserModel extends Model{
        protected $table = 'my_users';
        protected $primaryKey = 'user_id';
        protected $useSoftDeletes = false;

        protected $validationRules = [
            'full_name' => 'required|min_length[2]',
            'email' => 'required|valid_email|is_unique[my_users.email]',
            'password' => 'required|min_length[6]',
            'level' => 'required|is_natural|less_than_equal_to[3]',
        ];

        protected $allowedFields = [
            'user_id',
            'full_name',
            'email',
            'password',
            'level',
            'created_by',
            'created_on'
        ];

        protected $validationRulesEdit = [
            'full_name' => 'required|min_length[2]',
            'email' => 'required|valid_email',
            'level' => 'required|is_natural|less_than_equal_to[3]',
        ];

    }
?>