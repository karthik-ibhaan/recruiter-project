<?php
    
    namespace App\Models;
    use CodeIgniter\Model;

    class DemandModel extends Model{
        protected $table = 'demand';
        protected $primaryKey = 'demand_id';
        protected $allowedFields = [
            'DEMAND_ID',
            'CLIENT_ID',
            'DEMAND_STATUS',
            'PRIORITY',
            'COMPLEXITY',
            'JD_ID',
            'NO_POSITIONS',
            'CUS_SPOC',
            'IBHAAN_SPOC',
            'INDUSTRY_SEGMENT',
            'DOMAIN',
            'SKILL',
            'BAND',
            'MIN_EXPERIENCE',
            'MAX_EXPERIENCE',
            'MIN_BUDGET',
            'MAX_BUDGET',
            'LOCATION',
            'JOB_TITLE',
            'PRIMARY_SKILL',
            'SECONDARY_SKILL',
            'JOB_DESCRIPTION',
            'RECRUITER',
            'JD_LOCATION',
            'SUBMISSION_DATE'
        ];

        protected $validationRules = [
            'CLIENT_ID' => 'required',
            'DEMAND_STATUS' => 'required',
            'COMPLEXITY' => 'required',
            'NO_POSITIONS' => 'required',
            'CUS_SPOC' => 'required',
            'IBHAAN_SPOC' => 'required', 	
            'INDUSTRY_SEGMENT' => 'required', 	
            'DOMAIN' => 'required',
            'MIN_EXPERIENCE' => 'required',
            'MIN_BUDGET' => 'required',
            'LOCATION' => 'required',
            'JOB_TITLE' => 'required',
            'PRIMARY_SKILL' => 'required'          
        ];
    }
?>
