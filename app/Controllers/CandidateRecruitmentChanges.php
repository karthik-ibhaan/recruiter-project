<?php 

    namespace App\Controllers;
    use CodeIgniter\Controller;
    use App\Models\DemandModel;
    use App\Models\ClientModel;
    use CodeIgniter\Files\File;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use App\Libraries\RecruitmentStatuses;

    class CandidateRecruitmentChanges extends Controller
    {
        public function index()
        {
            helper(['form']);
            $data = CandidateRecruitmentChanges::initialise();
            echo view('candidaterecruitmentchanges');
        }

        public function initialise()
        {
            $recruitmentStatus1 = ['00. Sourcing', '01. CV sent to client', '01. No Feedback from client', '01. Profile Sent', '02. Duplicate', '02. Quest Duplicate', '02. Reject-Budget', '02. Reject-Fake', '02. Reject-HNP', '02. Reject-No Response', '02. Reject-Screen', '03. Pos. Closed', '03. Pos. Hold', '03. Pos. Modified', '04. F2F-In progress', '04. HR-In progress', '04. L1-In progress', '04. R1-In progress', '04. R2-In progress', '05. Reject-Cand. Dropped', '05. Reject-No Show', '06. Reject-Skill', '07. Feedback Pending', '08. Documents Uploaded', '08. Offer Accepted', '08. Offer Pending', '08. Offer Released', '09. Candidate Dropped Offer', '09. Client Rejected', '09. Offer Declined', '10. Joined'];
            $recruitmentStatus = ['00_Sourcing', '00_CV sent to client', '00_No Feedback from client', '00_Profile Sent', '00_Duplicate', 'S6. Quest Duplicate', '00_Reject-Budget', '00_Reject-Fake', '00_Reject-HNP', '00_Reject-No Responce', '00_Reject-Screen', '001_Pos. closed', '001_Pos. Hold', '001_Pos. Modified', '01_F2F-In progress', '01_HR-In progress', '01_L1-In progress', '01_R1-In progress', '01_R2-In progress', '01_Reject-Cand. Dropped', '01_Reject-No Show', '03_Reject-Skill', '02_Feedback Pending', 'S2. Documents Uploaded', 'S2. Offer Accepted', 'S1. Offer Pending', 'S2. Offer Released', 'S6. Candidate Dropped Offer', 'S6. Client Rejected', 'S6. Offer Declined', 'S5. Joined'];
            foreach($recruitmentStatus as $keys=>$data)
            {
                print_r($recruitmentStatus[$keys]);
                echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                print_r($recruitmentStatus1[$keys]);
                echo "<br>";
                $db = \Config\Database::connect();
                $table = $db->table('candidates');
                $changes = ['RECRUITMENT_STATUS' => $recruitmentStatus1[$keys]];
                $table->set($changes);
                $table->where('candidates.RECRUITMENT_STATUS', $recruitmentStatus[$keys]);
                if($table->update())
                {
                    echo "UPDATED!";
                }
            }
            $recruitmentStatusesJSON = json_decode('{"00": ["00. Sourcing"], "01. Screening": ["01. CV sent to client", "01. No Feedback from client", "01. Profile Sent"], "02. Rejected-Sc": ["02. Duplicate", "02. Quest Duplicate", "02. Reject-Budget", "02. Reject-Fake", "02. Reject-HNP", "02. Reject-No Response", "02. Reject-Screen"], "03. Pos Cancelled": ["03. Pos.  closed", "03. Pos.  Hold", "03. Pos.  Modified"], "04. Interview": ["04. F2F-In progress", "04. HR-In progress", "04. L1-In progress", "04. R1-In progress", "04. R2-In progress"], "05. Cand. Drop": ["05. Reject-Cand.  Dropped", "05. Reject-No Show"], "06. Rejected-Skill": ["06. Reject-Skill"], "07. Decision Pending": ["07. Feedback Pending"], "08. Selected": ["08. Documents Uploaded", "08. Offer Accepted", "08. Offer Pending", "08. Offer Released"], "09. Offer Cancelled": ["09. Candidate Dropped Offer", "09. Client Rejected", "09. Offer Declined"], "10. Joined": ["10. Joined"], "11. Resigned": ["11. Resigned"]}', true);
            $feedbackPending = array_merge($recruitmentStatusesJSON['01. Screening'], $recruitmentStatusesJSON['07. Decision Pending']);
            $recruitmentStatuses = new RecruitmentStatuses();
            print_r($recruitmentStatusesJSON);
        }
    }
?>