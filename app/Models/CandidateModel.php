<?php
    
    namespace App\Models;
    use CodeIgniter\Model;

    class CandidateModel extends Model{
        protected $table = 'candidates';
        protected $primaryKey = 'candidate_id';

        protected $allowedFields = [
            'CANDIDATE_ID',
            'DEMAND_ID',
            'RECRUITMENT_STATUS',
            'CANDIDATE_NAME',
            'PHONE_NO',
            'EMAIL_ADDRESS',
            'ORGANISATION',
            'WORK_LOCATION',
            'TOTAL_EXPERIENCE',
            'CCTC_LPA',
            'ECTC_LPA',
            'NOTICE_PERIOD_DAYS',
            'SUBMISSION_DATE',
            'INTERVIEW_DATE',
            'PLANNED_DOJ',
            'ACTUAL_DOJ',
            'RECRUITER'
        ];

        protected $validationRules = [
            'DEMAND_ID' => 'required',
            'RECRUITMENT_STATUS' => 'required',
            'CANDIDATE_NAME' => 'required',
            'EMAIL_ADDRESS' => 'required|valid_email',
            'ORGANISATION' => 'required',
            'WORK_LOCATION' => 'required',
            'TOTAL_EXPERIENCE' => 'required',
            'CCTC_LPA' => 'required',
            'ECTC_LPA' => 'required',
            'NOTICE_PERIOD_DAYS' => 'required',
            'RECRUITER' => 'required'
        ];

    }
?>